<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Attribute;
use App\Models\AttributeValue;

class CatalogController extends Controller
{
    public function index()
    {
        $productModel = new Product();
        $searchQuery = $_GET['q'] ?? null;
        $categoryId = $_GET['category_id'] ?? $_GET['id'] ?? null;
        $subCategoryId = $_GET['sub_category_id'] ?? $_GET['sub_id'] ?? null;
        $attributeFilters = $_GET['attr'] ?? [];
        $sort = in_array($_GET['sort'] ?? '', ['newest','price_asc','price_desc']) ? $_GET['sort'] : 'newest';
        
        $products = $productModel->getAllActive($searchQuery, $categoryId, $subCategoryId, $attributeFilters, $sort);
        
        // Attach variants to each product
        foreach ($products as &$product) {
            $product['variants'] = $productModel->getInventoryProductVariants($product['id']);
        }
        
        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive();

        $subCategoryModel = new SubCategory();
        $subCategories = $subCategoryModel->getAll();
        
        // Fetch Attributes if subcategory is selected
        $filterableAttributes = [];
        $attributeValues = [];
        if ($subCategoryId) {
            $attributeModel = new Attribute();
            $attributeValueModel = new AttributeValue();
            
            $filterableAttributes = $attributeModel->getFilterableBySubCategory($subCategoryId);
            
            if (!empty($filterableAttributes)) {
                $attrIds = array_column($filterableAttributes, 'id');
                $attributeValues = $attributeValueModel->getValuesForAttributes($attrIds);
            }
        }

        // Find selected category name for the title/banner
        $selectedCategoryName = "Wholesale Catalog";
        if ($categoryId) {
            foreach ($categories as $cat) {
                if ($cat['id'] == $categoryId) {
                    $selectedCategoryName = $cat['name'];
                    break;
                }
            }
        }

        $wishlistIds = [];
        if (Session::get('user_id')) {
            $wishlistModel = new Wishlist();
            $wishlistIds = $wishlistModel->getProductIds(Session::get('user_id'));
        }

        $canAddToCart = (Session::get('user_id') && Session::get('user_status') === 'active');

        $cmsData = \Core\Database::getInstance()->query("SELECT content_data FROM cms_components WHERE section_type = 'product_carousel' LIMIT 1")->fetchColumn();
        $cmsSettings = $cmsData ? json_decode($cmsData, true) : [];

        $this->render('storefront/catalog', [
            'title' => $selectedCategoryName,
            'products' => $products,
            'categories' => $categories,
            'subCategories' => $subCategories,
            'filterableAttributes' => $filterableAttributes,
            'attributeValues' => $attributeValues,
            'wishlistIds' => $wishlistIds,
            'canAddToCart' => $canAddToCart,
            'searchQuery' => $searchQuery,
            'selectedCategoryId' => $categoryId,
            'selectedSubCategoryId' => $subCategoryId,
            'selectedCategoryName' => $selectedCategoryName,
            'attributeFilters' => $attributeFilters,
            'currentSort' => $sort,
            'wishlist_color' => $cmsSettings['wishlist_color'] ?? '#111827',
            'subtitle_color' => $cmsSettings['subtitle_color'] ?? '#6b7280',
            'text_color' => $cmsSettings['text_color'] ?? '#4b5563'
        ]);
    }

    public function ajaxSearch()
    {
        $searchQuery = $_GET['q'] ?? '';
        
        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive($searchQuery);
        $categories = array_slice($categories, 0, 5); // Limit to 5 categories

        $productModel = new Product();
        $products = $productModel->getAllActive($searchQuery);
        $products = array_slice($products, 0, 10); // Limit to 10 products
        
        $isLoggedIn = Session::get('user_id') ? true : false;
        
        $formattedProducts = [];
        foreach ($products as $p) {
            $gst = (float)($p['variant_total_gst'] ?? 0);
            if ($gst <= 0) $gst = (float)($p['total_gst'] ?? 0);
            if ($gst <= 0) $gst = (float)($p['category_sgst'] ?? 0) + (float)($p['category_cgst'] ?? 0);
            
            $basePrice = (isset($p['base_price']) && (float)$p['base_price'] > 0) ? (float)$p['base_price'] : (float)($p['wholesale_price'] ?? 0);
            $discountPrice = (!empty($p['discount_price']) && (float)$p['discount_price'] > 0) ? (float)$p['discount_price'] : null;
            $effPrice = $discountPrice ?? $basePrice;

            $displayPrice = $effPrice;

            $formattedProducts[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'category_name' => $p['category_name'],
                'primary_image' => $p['primary_image'] ? get_image_url($p['primary_image']) : null,
                'base_price' => $isLoggedIn ? $basePrice : null,
                'discount_price' => $isLoggedIn && $discountPrice ? $displayPrice : null,
                'display_price' => $isLoggedIn ? $displayPrice : null,
            ];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'categories' => $categories,
            'products' => $formattedProducts
        ]);
        exit;
    }

    public function ajaxAttributes()
    {
        $subCategoryId = $_GET['sub_category_id'] ?? null;
        if (!$subCategoryId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'attributes' => [], 'values' => []]);
            return;
        }

        $attributeModel = new Attribute();
        $attributeValueModel = new AttributeValue();
        
        $attributes = $attributeModel->getFilterableBySubCategory($subCategoryId);
        if (!empty($attributes)) {
            $attrIds = array_column($attributes, 'id');
            $values = $attributeValueModel->getValuesForAttributes($attrIds);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'attributes' => $attributes, 'values' => $values]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'attributes' => [], 'values' => []]);
        }
    }

    public function view()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/catalog');
        }

        $productModel = new Product();
        $product = $productModel->findById($id);

        if (!$product || $product['status'] !== 'active') {
            $this->redirect('/catalog');
        }

        $categoryModel = new Category();
        $category = $categoryModel->findById($product['category_id']);
        $subCategory = !empty($product['sub_category_id']) ? $categoryModel->findById($product['sub_category_id']) : null;

        $images = $productModel->getImages($id);
        
        // Ensure there's a primary image available in $product for easy access
        $product['primary_image'] = null;
        foreach ($images as $img) {
            if ($img['is_primary']) {
                $product['primary_image'] = $img['image_path'];
                break;
            }
        }
        if (!$product['primary_image'] && !empty($images)) {
            $product['primary_image'] = $images[0]['image_path'];
        }

        // Fetch variants if applicable (or mock available variants for UI)
        $variants = $productModel->getInventoryProductVariants($id);
        foreach ($variants as &$v) {
            if (!empty($v['images'])) {
                $v['images'] = array_map(function($img) {
                    return get_image_url($img);
                }, $v['images']);
            }
        }

        // Fetch frequently bought together (random active products for now)
        // Fast random sampling: avoids ORDER BY RAND() full table scan
        // Uses primary key index for near-instant random selection
        $db = \Core\Database::getInstance();
        $maxStmt = $db->query("SELECT MAX(id) FROM products WHERE status = 'active'");
        $maxId   = (int) $maxStmt->fetchColumn();
        $randomOffset = max(0, rand(0, $maxId - 10));

        $stmt = $db->prepare("
            SELECT p.*, c.name AS category_name,
                   img.image_path AS primary_image,
                   pv_agg.base_price,
                   pv_agg.discount_price
            FROM products p
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_images img ON img.product_id = p.id AND img.is_primary = 1
            LEFT JOIN (
                SELECT product_id, 
                       MIN(base_price) AS base_price,
                       MIN(CASE WHEN discount_price > 0 THEN discount_price ELSE NULL END) AS discount_price 
                FROM product_variants GROUP BY product_id
            ) pv_agg ON pv_agg.product_id = p.id
            WHERE p.status = 'active' AND p.id != :id AND p.id >= :offset
            LIMIT 4
        ");
        $stmt->execute(['id' => $id, 'offset' => $randomOffset]);
        $relatedProducts = $stmt->fetchAll();

        // If we didn't get 4 results (near end of table), get from the top
        if (count($relatedProducts) < 4) {
            $stmt = $db->prepare("
                 SELECT p.*, c.name AS category_name,
                        img.image_path AS primary_image,
                        pv_agg.base_price,
                        pv_agg.discount_price
                 FROM products p
                 JOIN categories c ON p.category_id = c.id
                 LEFT JOIN product_images img ON img.product_id = p.id AND img.is_primary = 1
                 LEFT JOIN (
                     SELECT product_id, 
                            MIN(base_price) AS base_price,
                            MIN(CASE WHEN discount_price > 0 THEN discount_price ELSE NULL END) AS discount_price 
                     FROM product_variants GROUP BY product_id
                 ) pv_agg ON pv_agg.product_id = p.id
                 WHERE p.status = 'active' AND p.id != :id
                LIMIT 4
            ");
            $stmt->execute(['id' => $id]);
            $relatedProducts = $stmt->fetchAll();
        }

        $wishlistIds = [];
        if (Session::get('user_id')) {
            $wishlistModel = new Wishlist();
            $wishlistIds = $wishlistModel->getProductIds(Session::get('user_id'));
        }

        $reviewModel = new Review();
        $reviews = $reviewModel->getApprovedProductReviews($id);

        // Fetch any active B2B Category-to-Subcategory Offer Rules for this product's category
        $categoryOfferRule = null;
        if (!empty($product['category_id'])) {
            $ruleModel = new \App\Models\OrderRule();
            $activeRules = $ruleModel->getActiveRules();
            foreach ($activeRules as $r) {
                if ($r['category_id'] == $product['category_id']) {
                    $categoryModel = new \App\Models\Category();
                    $allCats = $categoryModel->getAll();
                    $catsById = [];
                    foreach ($allCats as $ac) {
                        $catsById[$ac['id']] = $ac;
                    }

                    $secondRulesRaw = json_decode($r['second_category_rules'], true) ?? [];
                    $secondaryOffers = [];
                    foreach ($secondRulesRaw as $scId => $sAmt) {
                        $scId = (int)$scId;
                        if (isset($catsById[$scId])) {
                            $secondaryOffers[] = [
                                'id' => $scId,
                                'name' => $catsById[$scId]['name'],
                                'slug' => $catsById[$scId]['slug'],
                                'min_amount' => (float)$sAmt
                            ];
                        }
                    }

                    $categoryOfferRule = [
                        'min_amount' => (float)$r['min_amount'],
                        'main_category_name' => $r['category_name'],
                        'secondary_offers' => $secondaryOffers
                    ];
                    break;
                }
            }
        }

        // Fetch Volume Pricing Tiers for this product
        $volumeTiers = [];
        $db = \Core\Database::getInstance();
        $tierStmt = $db->prepare("SELECT * FROM product_volume_tiers WHERE product_id = ? ORDER BY min_qty ASC");
        $tierStmt->execute([$id]);
        $volumeTiers = $tierStmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('storefront/product_details', [
            'title' => $product['name'],
            'product' => $product,
            'category' => $category,
            'subCategory' => $subCategory,
            'categoryOfferRule' => $categoryOfferRule,
            'images' => $images,
            'variants' => $variants,
            'relatedProducts' => $relatedProducts,
            'wishlistIds' => $wishlistIds,
            'reviews' => $reviews,
            'volumeTiers' => $volumeTiers
        ]);
    }

    public function searchAjax()
    {
        header('Content-Type: application/json');
        
        // Unblock session file so concurrent AJAX requests from the same user don't queue up
        session_write_close();
        
        $query = $_GET['q'] ?? '';
        
        if (empty(trim($query))) {
            echo json_encode(['success' => true, 'results' => []]);
            return;
        }

        $productModel = new Product();
        $results = $productModel->searchActive($query);

        $formattedResults = [];
        foreach ($results as $r) {
            $gst = (float)($r['product_total_gst'] ?? 0);
            if ($gst <= 0) $gst = (float)($r['category_sgst'] ?? 0) + (float)($r['category_cgst'] ?? 0);

            $basePrice = (isset($r['base_price']) && (float)$r['base_price'] > 0) ? (float)$r['base_price'] : (float)($r['wholesale_price'] ?? 0);
            $discountPrice = (!empty($r['discount_price']) && (float)$r['discount_price'] > 0) ? (float)$r['discount_price'] : null;
            $effPrice = $discountPrice ?? $basePrice;

            $displayPrice = $effPrice;

            $formattedResults[] = [
                'id' => $r['id'],
                'name' => $r['name'],
                'url' => BASE_URL . '/product?id=' . $r['id'],
                'image' => $r['primary_image'] ? get_image_url($r['primary_image']) : 'https://placehold.co/100x100?text=No+Img',
                'price' => '₹' . number_format($displayPrice, 2)
            ];
        }

        echo json_encode(['success' => true, 'results' => $formattedResults]);
        return;
    }
}
