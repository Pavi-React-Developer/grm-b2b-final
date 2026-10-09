<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $productModel = new Product();
        $products = $productModel->getAllActive(null, null, null, [], 'newest', 0);

        $categoryModel = new \App\Models\Category();
        $categories = $categoryModel->getAll(0);

        // Attach variants to each product
        foreach ($products as &$product) {
            $product['variants'] = $productModel->getInventoryProductVariants($product['id']);
        }

        $wishlistIds = [];
        if (\Core\Session::get('user_id')) {
            $wishlistModel = new \App\Models\Wishlist();
            $wishlistIds = $wishlistModel->getProductIds(\Core\Session::get('user_id'));
        }

        // Fetch layout dynamically without curl to avoid PHP dev server deadlock
        $db = \Core\Database::getInstance();
        $isPreview = isset($_GET['preview']) && $_GET['preview'] == '1';
        $cachedData = $isPreview ? null : \Core\Cache::get('cms_layout_home');
        
        if ($cachedData !== null) {
            $layoutSections = $cachedData;
        } else {
            $stmt = $db->prepare("SELECT layout_data FROM cms_layouts WHERE page_name = 'home'");
            $stmt->execute();
            $layout = $stmt->fetch();
            
            if ($isPreview && isset($_GET['layout']) && !empty($_GET['layout'])) {
                $componentIds = explode(',', $_GET['layout']);
            } else {
                $componentIds = $layout && !empty($layout['layout_data']) ? json_decode($layout['layout_data'], true) : [];
            }
            
            $layoutSections = [];
            if (!empty($componentIds)) {
                $placeholders = implode(',', array_fill(0, count($componentIds), '?'));
                $activeCond = $isPreview ? "" : " AND is_active = 1";
                $stmt = $db->prepare("SELECT * FROM cms_components WHERE id IN ($placeholders) $activeCond");
                $stmt->execute($componentIds);
                $componentsData = $stmt->fetchAll();
                
                $componentsMap = [];
                foreach ($componentsData as $cmp) {
                    $cmp['content_data'] = json_decode($cmp['content_data'], true);
                    $componentsMap[$cmp['id']] = $cmp;
                }
                
                foreach ($componentIds as $id) {
                    if (isset($componentsMap[$id])) {
                        $layoutSections[] = $componentsMap[$id];
                    }
                }
                if (!$isPreview) {
                    \Core\Cache::set('cms_layout_home', $layoutSections, 3600);
                }
            }
        }

        $this->render('storefront/home', [
            'title' => 'Wholesale Fashion Hub',
            'products' => $products,
            'categories' => $categories,
            'wishlistIds' => $wishlistIds,
            'layoutSections' => $layoutSections
        ]);
    }
    public function about()
    {
        $db = \Core\Database::getInstance();
        $isPreview = isset($_GET['preview']) && $_GET['preview'] == '1';
        $cachedData = $isPreview ? null : \Core\Cache::get('cms_about_us');
        
        if ($cachedData !== null) {
            $aboutUsComponent = $cachedData;
        } else {
            $activeCond = $isPreview ? "" : " AND is_active = 1";
            $stmt = $db->prepare("SELECT * FROM cms_components WHERE section_type = 'about_us' $activeCond ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $aboutUsComponent = $stmt->fetch();
            
            if ($aboutUsComponent) {
                $aboutUsComponent['content_data'] = json_decode($aboutUsComponent['content_data'], true);
            }
            if (!$isPreview) {
                \Core\Cache::set('cms_about_us', $aboutUsComponent, 3600);
            }
        }

        $this->render('storefront/about', [
            'title' => 'About Us - GRM B2B Wholesale',
            'aboutUs' => $aboutUsComponent
        ]);
    }

    public function terms()
    {
        $this->render('storefront/terms', [
            'title' => 'Terms & Conditions'
        ]);
    }

    public function privacyPolicy()
    {
        $this->render('storefront/privacy_policy', [
            'title' => 'Privacy & Return Policy'
        ]);
    }

    public function sizeChart()
    {
        $sizeChartModel = new \App\Models\SizeChart();
        $charts = $sizeChartModel->getAllActive();
        $categoryModel = new \App\Models\Category();
        $categories = $categoryModel->getAll();

        foreach ($charts as &$chart) {
            $chart['columns'] = json_decode($chart['columns_json'] ?? '[]', true) ?: [];
            $chart['rows'] = json_decode($chart['rows_json'] ?? '[]', true) ?: [];
        }
        unset($chart);

        $this->render('storefront/size_chart', [
            'title' => 'Garment Size & Measurement Charts',
            'charts' => $charts,
            'categories' => $categories
        ]);
    }

    public function apiSizeChart()
    {
        header('Content-Type: application/json');
        $chartId = (int)($_GET['chart_id'] ?? 0);
        $productId = (int)($_GET['product_id'] ?? 0);
        $categoryId = (int)($_GET['category_id'] ?? 0);
        $subCategoryId = (int)($_GET['sub_category_id'] ?? 0);
        
        $sizeChartModel = new \App\Models\SizeChart();

        // 1. If product_id is given, resolve product-specific size_chart_id and category/subcategory
        if ($productId > 0) {
            $productModel = new Product();
            $product = $productModel->findById($productId);
            if ($product) {
                if (empty($chartId) && !empty($product['size_chart_id'])) {
                    $chartId = (int)$product['size_chart_id'];
                }
                if (empty($categoryId) && !empty($product['category_id'])) {
                    $categoryId = (int)$product['category_id'];
                }
                if (empty($subCategoryId) && !empty($product['sub_category_id'])) {
                    $subCategoryId = (int)$product['sub_category_id'];
                }
            }
        }

        $charts = [];
        // 2. If a specific chart_id is requested or assigned to the product
        if ($chartId > 0) {
            $specificChart = $sizeChartModel->getById($chartId);
            if ($specificChart && (!isset($specificChart['is_active']) || $specificChart['is_active'])) {
                $charts[] = $specificChart;
            }
        }

        // 3. If no specific chart was found, fall back to subcategory/category matching
        if (empty($charts)) {
            if ($subCategoryId > 0 || $categoryId > 0) {
                $charts = $sizeChartModel->getByCategoryAndSubCategory($categoryId, $subCategoryId);
            } else {
                $charts = $sizeChartModel->getAllActive();
            }
        }

        foreach ($charts as &$chart) {
            $chart['columns'] = is_array($chart['columns_json'] ?? null) 
                ? $chart['columns_json'] 
                : (json_decode($chart['columns_json'] ?? '[]', true) ?: []);
            $chart['rows'] = is_array($chart['rows_json'] ?? null) 
                ? $chart['rows_json'] 
                : (json_decode($chart['rows_json'] ?? '[]', true) ?: []);
        }
        unset($chart);

        echo json_encode(['success' => true, 'charts' => $charts]);
        exit;
    }
}


