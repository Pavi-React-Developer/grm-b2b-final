<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\AttributeValue;
use App\Models\Attribute;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $role = Session::get('user_role');
        if (!$role) {
            Session::setFlash('error', 'Unauthorized access.');
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $this->requirePermission('products', 'view');
        $isVendor = (Session::get('user_role') === 'vendor');
        $vendorId = $isVendor ? (int)Session::get('user_id') : (!empty($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : null);
        $status = $_GET['status'] ?? 'all';
        $module = $_GET['module'] ?? '';
        $isCustomize = ($module === 'customize');

        $productModel = new Product();
        $products = $productModel->getAllAdmin($vendorId, $status, $isCustomize ? 1 : 0);
        $pendingCount = $productModel->getPendingApprovalCount($vendorId);

        if (!empty($products)) {
            $productIds = array_column($products, 'id');
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            
            $db = \Core\Database::getInstance();
            $stmt = $db->prepare("SELECT product_id, name, sku, base_price, discount_price, inventory, current_stock, sgst, cgst, total_gst, max_amount FROM product_variants WHERE product_id IN ($placeholders) ORDER BY id ASC");
            $stmt->execute($productIds);
            $allVariants = $stmt->fetchAll();
            
            $variantsByProduct = [];
            foreach ($allVariants as $v) {
                $variantsByProduct[$v['product_id']][] = $v;
            }
            
            foreach ($products as &$prod) {
                $prod['variants'] = $variantsByProduct[$prod['id']] ?? [];
            }
        }
        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive(null, $isCustomize ? 1 : 0);

        $subCategoryModel = new SubCategory();
        $subCategories = $subCategoryModel->getAll(null, $isCustomize ? 1 : 0);

        $this->render('admin/catalog/products/index', [
            'title' => ($module === 'customize') ? 'Customizable Products & Fabrics' : ($isVendor ? 'My Products' : 'Manage Products'),
            'products' => $products,
            'isVendor' => $isVendor,
            'currentStatus' => $status,
            'pendingCount' => $pendingCount,
            'module' => $module,
            'isCustomize' => $isCustomize
        ], 'admin');
    }

    public function create()
    {
        $this->requirePermission('products', 'create');
        $isVendor = (Session::get('user_role') === 'vendor');
        $module = $_GET['module'] ?? '';
        $isCustomize = ($module === 'customize');

        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive(null, $isCustomize ? 1 : 0);

        $sizeChartModel = new \App\Models\SizeChart();
        $sizeCharts = $sizeChartModel->getAllActive();

        $attrValueModel = new AttributeValue();
        $attributes = $attrValueModel->getAllGroupedByAttribute();

        $vendors = [];
        if (!$isVendor && is_vendor_module_enabled()) {
            $userModel = new \App\Models\User();
            $vendors = $userModel->getActiveVendors();
        }

        $this->render('admin/catalog/products/create', [
            'title' => ($module === 'customize') ? 'Add Customizable Product / Fabric' : 'Add Product',
            'categories' => $categories,
            'sizeCharts' => $sizeCharts,
            'attributes' => $attributes,
            'isVendor' => $isVendor,
            'vendors' => $vendors,
            'module' => $module,
            'isCustomize' => $isCustomize
        ], 'admin');
    }

    public function view()
    {
        $this->requirePermission('products', 'view');
        $id = $_GET['id'] ?? null;
        $module = $_GET['module'] ?? '';
        $isCustomize = ($module === 'customize');
        if (!$id) {
            $this->redirect('/admin/catalog/products' . ($isCustomize ? '?module=customize' : ''));
        }

        $productModel = new Product();
        $product = $productModel->findById($id);

        if (!$product) {
            $this->redirect('/admin/catalog/products' . ($isCustomize ? '?module=customize' : ''));
        }

        $isVendor = (Session::get('user_role') === 'vendor');
        if ($isVendor && (int)($product['vendor_id'] ?? 0) !== (int)Session::get('user_id')) {
            Session::setFlash('error', 'You do not have permission to view this product.');
            $this->redirect('/admin/catalog/products' . ($isCustomize ? '?module=customize' : ''));
            return;
        }

        // Get Category and SubCategory details
        $categoryModel = new Category();
        $category = $categoryModel->findById($product['category_id']);
        
        $subCategory = null;
        if ($product['sub_category_id']) {
            $subCategoryModel = new SubCategory();
            $subCategory = $subCategoryModel->findById($product['sub_category_id']);
        }

        // Get Variants
        $variantModel = new ProductVariant();
        $variants = $variantModel->getByProductId($id);

        // Get Images
        $images = $productModel->getImages($id);

        $sizeChart = null;
        if (!empty($product['size_chart_id'])) {
            $sizeChartModel = new \App\Models\SizeChart();
            $sizeChart = $sizeChartModel->getById((int)$product['size_chart_id']);
        }

        $this->render('admin/catalog/products/view', [
            'product' => $product,
            'category' => $category,
            'subCategory' => $subCategory,
            'sizeChart' => $sizeChart,
            'variants' => $variants,
            'images' => $images,
            'isVendor' => $isVendor,
            'module' => $module,
            'isCustomize' => $isCustomize
        ], 'admin');
    }

    public function edit()
    {
        $this->requirePermission('products', 'edit');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $productModel = new Product();
        $product = $productModel->findById($id);
        
        $module = $_GET['module'] ?? '';
        $isCustomize = ($module === 'customize');

        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('/admin/catalog/products' . ($isCustomize ? '?module=customize' : ''));
        }

        $isVendor = (Session::get('user_role') === 'vendor');
        if ($isVendor && (int)($product['vendor_id'] ?? 0) !== (int)Session::get('user_id')) {
            Session::setFlash('error', 'You do not have permission to edit this product.');
            $this->redirect('/admin/catalog/products' . ($isCustomize ? '?module=customize' : ''));
            return;
        }

        if (!$isCustomize && !empty($product['id'])) {
            if (!empty($product['is_customizable'])) {
                $isCustomize = true;
                $module = 'customize';
            } else {
                $db = \Core\Database::getInstance();
                $stmt = $db->prepare("SELECT id FROM fabric_customizations WHERE fabric_id = ? LIMIT 1");
                $stmt->execute([(int)$product['id']]);
                if ($stmt->fetch()) {
                    $isCustomize = true;
                    $module = 'customize';
                }
            }
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive(null, $isCustomize ? 1 : 0);

        $sizeChartModel = new \App\Models\SizeChart();
        $sizeCharts = $sizeChartModel->getAllActive();

        $subCategoryModel = new SubCategory();
        $subCategories = $subCategoryModel->getByCategory($product['category_id']);

        $attrValueModel = new AttributeValue();
        $attributes = $attrValueModel->getAllGroupedByAttribute();

        $selectedAttributes = $productModel->getAttributes($product['id']);

        $variantModel = new ProductVariant();
        $variants = $variantModel->getByProductId($product['id']);
        
        $productImages = $productModel->getImages($product['id']);

        $vendors = [];
        if (!$isVendor && is_vendor_module_enabled()) {
            $userModel = new \App\Models\User();
            $vendors = $userModel->getActiveVendors();
        }

        $this->render('admin/catalog/products/edit', [
            'title' => ($isCustomize) ? 'Edit Customizable Product / Fabric' : 'Edit Product',
            'product' => $product,
            'categories' => $categories,
            'subCategories' => $subCategories,
            'sizeCharts' => $sizeCharts,
            'attributes' => $attributes,
            'selectedAttributes' => $selectedAttributes,
            'variants' => $variants,
            'productImages' => $productImages,
            'isVendor' => $isVendor,
            'vendors' => $vendors,
            'module' => $module,
            'isCustomize' => $isCustomize
        ], 'admin');
    }

    public function store()
    {
        $this->requirePermission('products', 'create');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/products');
        }

        $isVendor = (Session::get('user_role') === 'vendor');
        $vendorId = $isVendor ? (int)Session::get('user_id') : (!empty($_POST['vendor_id']) ? (int)$_POST['vendor_id'] : null);
        $module = $_POST['module'] ?? ($_GET['module'] ?? '');
        $isCustomize = ($module === 'customize');

        $postedVariants = !empty($_POST['variants']) ? $_POST['variants'] : (!empty($_POST['new_variants']) ? $_POST['new_variants'] : []);

        if (empty($postedVariants)) {
            Session::setFlash('error', 'Please generate or configure at least one variant with price, weight, and stock.');
            $this->redirect('/admin/catalog/products/create' . ($isCustomize ? '?module=customize' : ''));
        }

        $firstVariant = reset($postedVariants);
        $firstVariantBasePrice = (float)($firstVariant['base_price'] ?? 0);
        $firstVariantDiscountPrice = (isset($firstVariant['discount_price']) && $firstVariant['discount_price'] !== '') ? (float)$firstVariant['discount_price'] : null;
        $firstVariantEffectivePrice = ($firstVariantDiscountPrice !== null && $firstVariantDiscountPrice > 0) ? $firstVariantDiscountPrice : $firstVariantBasePrice;
        $firstVariantWeight = !empty($firstVariant['weight']) ? (float)$firstVariant['weight'] : null;
        $firstVariantSgst = isset($firstVariant['sgst']) && $firstVariant['sgst'] !== '' ? (float)$firstVariant['sgst'] : 0.00;
        $firstVariantCgst = isset($firstVariant['cgst']) && $firstVariant['cgst'] !== '' ? (float)$firstVariant['cgst'] : 0.00;
        $firstVariantTotalGst = isset($firstVariant['total_gst']) && $firstVariant['total_gst'] !== '' ? (float)$firstVariant['total_gst'] : ($firstVariantSgst + $firstVariantCgst);
        $firstVariantMaxAmount = isset($firstVariant['max_amount']) && $firstVariant['max_amount'] !== '' ? (float)$firstVariant['max_amount'] : null;

        $approvalStatus = $isVendor ? 'pending' : 'approved';
        $status = $isVendor ? 'draft' : ($_POST['status'] ?? 'active');

        // Extract custom description fields
        $customFields = [];
        if (!empty($_POST['custom_fields']) && is_array($_POST['custom_fields'])) {
            foreach ($_POST['custom_fields'] as $cf) {
                $fName = trim($cf['name'] ?? '');
                $fVal = trim($cf['value'] ?? '');
                if ($fName !== '' || $fVal !== '') {
                    $customFields[] = [
                        'name' => $fName,
                        'value' => $fVal
                    ];
                }
            }
        }
        $customFieldsJson = !empty($customFields) ? json_encode($customFields, JSON_UNESCAPED_UNICODE) : null;

        $data = [
            'vendor_id' => $vendorId,
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'sub_category_id' => !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : null,
            'size_chart_id' => !empty($_POST['size_chart_id']) ? (int)$_POST['size_chart_id'] : null,
            'is_customizable' => $isCustomize ? 1 : 0,
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'how_to_use' => !empty($_POST['how_to_use']) ? trim($_POST['how_to_use']) : null,
            'why_choose' => !empty($_POST['why_choose']) ? trim($_POST['why_choose']) : null,
            'custom_fields' => $customFieldsJson,
            'wholesale_price' => $firstVariantEffectivePrice,
            'retail_price' => $firstVariantBasePrice,
            'weight' => $firstVariantWeight,
            'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
            'moq_override' => (!$isCustomize && !empty($_POST['moq_override'])) ? (int)$_POST['moq_override'] : null,
            'status' => $status,
            'approval_status' => $approvalStatus,
            'rejection_reason' => null,
            'sgst' => $firstVariantSgst,
            'cgst' => $firstVariantCgst,
            'total_gst' => $firstVariantTotalGst,
            'max_amount' => $firstVariantMaxAmount
        ];

        if ($data['category_id'] <= 0 || empty($data['name']) || empty($data['slug']) || empty($data['description']) || (!$isCustomize && empty($data['moq_override']))) {
            Session::setFlash('error', 'Category, ' . ($isCustomize ? 'Fabric Name' : 'Product Name') . ', Slug, and Description' . (!$isCustomize ? ', and MOQ' : '') . ' are all required.');
            $this->redirect('/admin/catalog/products/create' . ($isCustomize ? '?module=customize' : ''));
        }

        // Validate variants price and weight
        foreach ($postedVariants as $vData) {
            $basePrice = isset($vData['base_price']) && $vData['base_price'] !== '' ? (float)$vData['base_price'] : 0;
            $weight = isset($vData['weight']) && $vData['weight'] !== '' ? (float)$vData['weight'] : 0;
            $sku = trim($vData['sku'] ?? '');

            if ($basePrice <= 0) {
                Session::setFlash('error', 'Base Price is required and must be greater than 0 for all variants.');
                $this->redirect('/admin/catalog/products/create' . ($isCustomize ? '?module=customize' : ''));
            }

            if ($weight <= 0) {
                Session::setFlash('error', 'Weight is required and must be greater than 0 kg for all variants.');
                $this->redirect('/admin/catalog/products/create' . ($isCustomize ? '?module=customize' : ''));
            }

            if (empty($sku)) {
                Session::setFlash('error', 'Variant SKU is required and cannot be empty.');
                $this->redirect('/admin/catalog/products/create' . ($isCustomize ? '?module=customize' : ''));
            }
        }

        try {
            $db = \Core\Database::getInstance();
            $db->beginTransaction();

            // --- SKU Validation for Store ---
            $skus = [];
            foreach ($postedVariants as $vData) {
                $sku = trim($vData['sku'] ?? '');
                if (in_array($sku, $skus)) {
                    $db->rollBack();
                    Session::setFlash('error', 'Duplicate SKU found in the form data: ' . $sku);
                    $this->redirect('/admin/catalog/products/create' . ($isCustomize ? '?module=customize' : ''));
                }
                $skus[] = $sku;
                
                $existing = $db->prepare("SELECT id FROM product_variants WHERE sku = ?");
                $existing->execute([$sku]);
                if ($existing->fetch()) {
                    $db->rollBack();
                    Session::setFlash('error', 'The SKU "' . $sku . '" is already in use by another product.');
                    $this->redirect('/admin/catalog/products/create' . ($isCustomize ? '?module=customize' : ''));
                }
            }
            // ----------------------

            $productModel = new Product();
            
            // Check for duplicate slug
            if ($productModel->findBySlug($data['slug'])) {
                $db->rollBack();
                Session::setFlash('error', 'A product with this slug already exists. Please choose a different name or manually change the slug to be unique.');
                $this->redirect('/admin/catalog/products/create' . ($isCustomize ? '?module=customize' : ''));
            }

            $productId = $productModel->create($data);
            
            // Sync attributes
            $attributeValues = $_POST['attributes'] ?? [];
            $parsedAttributes = $this->parsePostedAttributes($attributeValues);
            $productModel->syncAttributes($productId, $parsedAttributes);
            
            // Process Variants
            $variantModel = new \App\Models\ProductVariant();
            $postedVariants = !empty($_POST['variants']) ? $_POST['variants'] : (!empty($_POST['new_variants']) ? $_POST['new_variants'] : []);
            
            // Purge any orphan/stale variants for this productId before inserting
            $variantModel->deleteByProductId($productId);

            if (empty($postedVariants)) {
                // Insert a default variant for inventory tracking if no variants were provided
                $variantModel->create([
                    'product_id' => $productId,
                    'name' => 'Default',
                    'sku' => $data['slug'],
                    'inventory' => 0,
                    'base_price' => $data['wholesale_price']
                ]);
            } else {
                $firstVariantImage = null;
                $fileGroup = !empty($_POST['variants']) ? 'variants' : 'new_variants';
                foreach ($postedVariants as $key => $vData) {
                    $vData['product_id'] = $productId;
                    if ($isCustomize) {
                        $vData['inventory'] = (int)($_POST['stock_quantity'] ?? 0);
                        $vData['current_stock'] = (int)($_POST['stock_quantity'] ?? 0);
                    }
                    $variantId = $variantModel->create($vData);
                    
                    if (!empty($vData['attributes']) && is_array($vData['attributes'])) {
                        $variantModel->syncAttributes($variantId, $vData['attributes']);
                    }
                    if (!empty($vData['image_paths']) && is_array($vData['image_paths'])) {
                        $uniqueImgs = array_values(array_unique(array_filter($vData['image_paths'])));
                        foreach ($uniqueImgs as $imgUrl) {
                            if (!empty($imgUrl)) {
                                $productModel->addImage($productId, $imgUrl, false, $variantId);
                                if (!$firstVariantImage) {
                                    $firstVariantImage = $imgUrl;
                                }
                            }
                        }
                    }
                }
            }

            // Handle Primary Image (with fallback to first variant image if primary not specified)
            $primaryImgPath = !empty($_POST['primary_image_path']) ? trim($_POST['primary_image_path']) : $firstVariantImage;
            if (!empty($primaryImgPath)) {
                $productModel->addImage($productId, $primaryImgPath, true);
            }
            
            $db->commit();
            \Core\Cache::delete('catalog_active');
            Session::setFlash('success', $isVendor ? 'Product submitted successfully! It is now pending administrator review.' : ($isCustomize ? 'Fabric created successfully.' : 'Product created successfully.'));
        } catch (\Exception $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            Session::setFlash('error', 'Error creating product: ' . $e->getMessage());
        }

        $this->redirect('/admin/catalog/products' . ($isCustomize ? '?module=customize' : ''));
    }

    public function update()
    {
        $this->requirePermission('products', 'edit');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/products');
        }

        $id = (int)($_POST['id'] ?? 0);
        $productModel = new Product();
        $existingProduct = $productModel->findById($id);
        if (!$existingProduct) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('/admin/catalog/products');
        }

        $isVendor = (Session::get('user_role') === 'vendor');
        if ($isVendor && (int)($existingProduct['vendor_id'] ?? 0) !== (int)Session::get('user_id')) {
            Session::setFlash('error', 'You do not have permission to edit this product.');
            $this->redirect('/admin/catalog/products');
        }

        $vendorId = $isVendor ? (int)Session::get('user_id') : (!empty($_POST['vendor_id']) ? (int)$_POST['vendor_id'] : null);

        $postedVariants = $_POST['variants'] ?? [];
        $newVariants = $_POST['new_variants'] ?? [];
        $allVariantsList = array_merge(is_array($postedVariants) ? $postedVariants : [], is_array($newVariants) ? $newVariants : []);

        if (empty($allVariantsList)) {
            Session::setFlash('error', 'Please configure at least one variant with price, weight, and stock.');
            $this->redirect('/admin/catalog/products/edit?id=' . $id);
        }

        $firstVariant = reset($allVariantsList);
        $firstVariantBasePrice = (float)($firstVariant['base_price'] ?? 0);
        $firstVariantDiscountPrice = (isset($firstVariant['discount_price']) && $firstVariant['discount_price'] !== '') ? (float)$firstVariant['discount_price'] : null;
        $firstVariantEffectivePrice = ($firstVariantDiscountPrice !== null && $firstVariantDiscountPrice > 0) ? $firstVariantDiscountPrice : $firstVariantBasePrice;
        $firstVariantWeight = !empty($firstVariant['weight']) ? (float)$firstVariant['weight'] : null;
        $firstVariantSgst = isset($firstVariant['sgst']) && $firstVariant['sgst'] !== '' ? (float)$firstVariant['sgst'] : 0.00;
        $firstVariantCgst = isset($firstVariant['cgst']) && $firstVariant['cgst'] !== '' ? (float)$firstVariant['cgst'] : 0.00;
        $firstVariantTotalGst = isset($firstVariant['total_gst']) && $firstVariant['total_gst'] !== '' ? (float)$firstVariant['total_gst'] : ($firstVariantSgst + $firstVariantCgst);
        $firstVariantMaxAmount = isset($firstVariant['max_amount']) && $firstVariant['max_amount'] !== '' ? (float)$firstVariant['max_amount'] : null;

        $module = $_POST['module'] ?? ($_GET['module'] ?? '');
        $isCustomize = ($module === 'customize');
        if (!$isCustomize && !empty($id)) {
            if (!empty($existingProduct['is_customizable'])) {
                $isCustomize = true;
            } else {
                $db = \Core\Database::getInstance();
                $stmt = $db->prepare("SELECT id FROM fabric_customizations WHERE fabric_id = ? LIMIT 1");
                $stmt->execute([(int)$id]);
                if ($stmt->fetch()) {
                    $isCustomize = true;
                }
            }
        }

        // Extract custom description fields
        $customFields = [];
        if (!empty($_POST['custom_fields']) && is_array($_POST['custom_fields'])) {
            foreach ($_POST['custom_fields'] as $cf) {
                $fName = trim($cf['name'] ?? '');
                $fVal = trim($cf['value'] ?? '');
                if ($fName !== '' || $fVal !== '') {
                    $customFields[] = [
                        'name' => $fName,
                        'value' => $fVal
                    ];
                }
            }
        }
        $customFieldsJson = !empty($customFields) ? json_encode($customFields, JSON_UNESCAPED_UNICODE) : null;

        $data = [
            'vendor_id' => $vendorId,
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'sub_category_id' => !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : null,
            'size_chart_id' => !empty($_POST['size_chart_id']) ? (int)$_POST['size_chart_id'] : null,
            'is_customizable' => $isCustomize ? 1 : 0,
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'how_to_use' => !empty($_POST['how_to_use']) ? trim($_POST['how_to_use']) : null,
            'why_choose' => !empty($_POST['why_choose']) ? trim($_POST['why_choose']) : null,
            'custom_fields' => $customFieldsJson,
            'wholesale_price' => $firstVariantEffectivePrice,
            'retail_price' => $firstVariantBasePrice,
            'weight' => $firstVariantWeight,
            'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
            'moq_override' => (!$isCustomize && !empty($_POST['moq_override'])) ? (int)$_POST['moq_override'] : null,
            'status' => $isVendor ? 'draft' : ($_POST['status'] ?? 'active'),
            'sgst' => $firstVariantSgst,
            'cgst' => $firstVariantCgst,
            'total_gst' => $firstVariantTotalGst,
            'max_amount' => $firstVariantMaxAmount
        ];

        if ($isVendor) {
            $data['approval_status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        if ($id <= 0 || $data['category_id'] <= 0 || empty($data['name']) || empty($data['slug']) || empty($data['description']) || (!$isCustomize && empty($data['moq_override']))) {
            Session::setFlash('error', 'Category, ' . ($isCustomize ? 'Fabric Name' : 'Product Name') . ', Slug, and Description' . (!$isCustomize ? ', and MOQ' : '') . ' are all required.');
            $this->redirect('/admin/catalog/products/edit?id=' . $id . ($isCustomize ? '&module=customize' : ''));
        }

        foreach ($allVariantsList as $vData) {
            $basePrice = isset($vData['base_price']) && $vData['base_price'] !== '' ? (float)$vData['base_price'] : 0;
            $weight = isset($vData['weight']) && $vData['weight'] !== '' ? (float)$vData['weight'] : 0;
            $sku = trim($vData['sku'] ?? '');

            if ($basePrice <= 0) {
                Session::setFlash('error', 'Base Price is required and must be greater than 0 for all variants.');
                $this->redirect('/admin/catalog/products/edit?id=' . $id . ($isCustomize ? '&module=customize' : ''));
            }

            if ($weight <= 0) {
                Session::setFlash('error', 'Weight is required and must be greater than 0 kg for all variants.');
                $this->redirect('/admin/catalog/products/edit?id=' . $id . ($isCustomize ? '&module=customize' : ''));
            }

            if (empty($sku)) {
                Session::setFlash('error', 'Variant SKU is required and cannot be empty.');
                $this->redirect('/admin/catalog/products/edit?id=' . $id . ($isCustomize ? '&module=customize' : ''));
            }
        }

        try {
            $db = \Core\Database::getInstance();
            $db->beginTransaction();

            // --- SKU Validation for Update ---
            $postedVariants = $_POST['variants'] ?? [];
            $newVariants = $_POST['new_variants'] ?? [];
            
            // Combine all variants for validation (maintaining ID keys for existing variants)
            $allVariants = [];
            if (is_array($postedVariants)) {
                foreach ($postedVariants as $k => $v) { $allVariants[$k] = $v; }
            }
            if (is_array($newVariants)) {
                foreach ($newVariants as $k => $v) { $allVariants['new_'.$k] = $v; }
            }
            
            if (!empty($allVariants)) {
                $skus = [];
                foreach ($allVariants as $vId => $vData) {
                    $sku = trim($vData['sku'] ?? '');
                    $variantId = (is_numeric($vId) && strpos($vId, 'new_') === false) ? (int)$vId : 0;
                    
                    if (empty($sku)) {
                        $db->rollBack();
                        Session::setFlash('error', 'Variant SKU cannot be empty.');
                        $this->redirect('/admin/catalog/products/edit?id=' . $id . ($isCustomize ? '&module=customize' : ''));
                    }
                    if (in_array($sku, $skus)) {
                        $db->rollBack();
                        Session::setFlash('error', 'Duplicate SKU found in the form data: ' . $sku);
                        $this->redirect('/admin/catalog/products/edit?id=' . $id . ($isCustomize ? '&module=customize' : ''));
                    }
                    $skus[] = $sku;
                    
                    $existing = $db->prepare("SELECT id FROM product_variants WHERE sku = ? AND product_id != ?");
                    $existing->execute([$sku, $id]);
                    
                    if ($existing->fetch()) {
                        $db->rollBack();
                        Session::setFlash('error', 'The SKU "' . $sku . '" is already in use by another product.');
                        $this->redirect('/admin/catalog/products/edit?id=' . $id . ($isCustomize ? '&module=customize' : ''));
                    }
                }
            }
            // ----------------------

            $productModel = new Product();
            
            // Check for duplicate slug on update
            $existingProduct = $productModel->findBySlug($data['slug']);
            if ($existingProduct && $existingProduct['id'] != $id) {
                $db->rollBack();
                Session::setFlash('error', 'Another product with this slug already exists. Please choose a different name or manually change the slug to be unique.');
                $this->redirect('/admin/catalog/products/edit?id=' . $id . ($isCustomize ? '&module=customize' : ''));
            }

            $productModel->update($id, $data);
            
            // Sync attributes
            $attributeValues = $_POST['attributes'] ?? [];
            $parsedAttributes = $this->parsePostedAttributes($attributeValues);
            $productModel->syncAttributes($id, $parsedAttributes);

            $variantModel = new ProductVariant();
            $activeVariantIds = [];
            $firstVariantImage = null;

            // Update existing variants
            if (!empty($_POST['variants']) && is_array($_POST['variants'])) {
                foreach ($_POST['variants'] as $vId => $vData) {
                    $vId = (int)$vId;
                    if ($vId > 0) {
                        if ($isCustomize) {
                            $vData['inventory'] = (int)($_POST['stock_quantity'] ?? 0);
                            $vData['current_stock'] = (int)($_POST['stock_quantity'] ?? 0);
                        }
                        $variantModel->update($vId, $vData);
                        $activeVariantIds[] = $vId;
                        
                        if (!empty($vData['attributes']) && is_array($vData['attributes'])) {
                            $variantModel->syncAttributes($vId, $vData['attributes']);
                        }

                        // Sync variant images: delete all then re-insert kept + new images
                        $productModel->deleteVariantImages($vId);
                        $keptImages  = !empty($vData['existing_image_paths']) && is_array($vData['existing_image_paths']) ? $vData['existing_image_paths'] : [];
                        $newImages   = !empty($vData['image_paths'])          && is_array($vData['image_paths'])          ? $vData['image_paths']          : [];
                        $allImages   = array_values(array_unique(array_filter(array_merge($keptImages, $newImages))));
                        foreach ($allImages as $imgUrl) {
                            $productModel->addImage($id, $imgUrl, false, $vId);
                            if (!$firstVariantImage) {
                                $firstVariantImage = $imgUrl;
                            }
                        }
                    }
                }
            }

            // Create new variants
            if (!empty($_POST['new_variants']) && is_array($_POST['new_variants'])) {
                foreach ($_POST['new_variants'] as $key => $vData) {
                    $vData['product_id'] = $id;
                    if ($isCustomize) {
                        $vData['inventory'] = (int)($_POST['stock_quantity'] ?? 0);
                        $vData['current_stock'] = (int)($_POST['stock_quantity'] ?? 0);
                    }
                    $variantId = $variantModel->create($vData);
                    $activeVariantIds[] = $variantId;
                    
                    if (!empty($vData['attributes']) && is_array($vData['attributes'])) {
                        $variantModel->syncAttributes($variantId, $vData['attributes']);
                    }

                    if (!empty($vData['image_paths']) && is_array($vData['image_paths'])) {
                        $uniqueImgs = array_values(array_unique(array_filter($vData['image_paths'])));
                        foreach ($uniqueImgs as $imgUrl) {
                            if (!empty($imgUrl)) {
                                $productModel->addImage($id, $imgUrl, false, $variantId);
                                if (!$firstVariantImage) {
                                    $firstVariantImage = $imgUrl;
                                }
                            }
                        }
                    }
                }
            }

            // Delete removed variants
            $variantModel->deleteUnused($id, $activeVariantIds);

            // Handle Primary Image
            if (isset($_POST['primary_image_path'])) {
                $primaryImgPath = trim($_POST['primary_image_path']);
                if (!empty($primaryImgPath)) {
                    $productModel->deletePrimaryImage($id);
                    $productModel->addImage($id, $primaryImgPath, true);
                } else {
                    $productModel->deletePrimaryImage($id);
                    if ($firstVariantImage) {
                        $productModel->addImage($id, $firstVariantImage, true);
                    }
                }
            } else {
                $existingPrimary = $productModel->getImages($id);
                if (empty($existingPrimary) && $firstVariantImage) {
                    $productModel->addImage($id, $firstVariantImage, true);
                }
            }

            $db->commit();
            \Core\Cache::delete('catalog_active');
            Session::setFlash('success', $isCustomize ? 'Fabric updated successfully.' : 'Product updated successfully.');
        } catch (\Exception $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            Session::setFlash('error', 'Error updating product: ' . $e->getMessage());
        }

        $this->redirect('/admin/catalog/products' . ($isCustomize ? '?module=customize' : ''));
    }

    public function delete()
    {
        $this->requirePermission('products', 'delete');
        $module = $_POST['module'] ?? ($_GET['module'] ?? '');
        $isCustomize = ($module === 'customize');
        $redirectUrl = '/admin/catalog/products' . ($isCustomize ? '?module=customize' : '');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($this->isJsonRequest()) {
                $this->json(['success' => false, 'message' => 'Invalid request method.']);
            }
            $this->redirect($redirectUrl);
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $jsonInput = json_decode(file_get_contents('php://input'), true);
            $id = (int)($jsonInput['id'] ?? 0);
        }
        
        if ($id > 0) {
            try {
                $productModel = new Product();
                $existingProduct = $productModel->findById($id);
                if (!$existingProduct) {
                    if ($this->isJsonRequest()) {
                        $this->json(['success' => false, 'message' => 'Product not found.']);
                    }
                    Session::setFlash('error', 'Product not found.');
                    $this->redirect($redirectUrl);
                    return;
                }

                if (!$isCustomize && !empty($existingProduct['is_customizable'])) {
                    $isCustomize = true;
                    $redirectUrl = '/admin/catalog/products?module=customize';
                }

                $isVendor = (Session::get('user_role') === 'vendor');
                if ($isVendor && (int)($existingProduct['vendor_id'] ?? 0) !== (int)Session::get('user_id')) {
                    if ($this->isJsonRequest()) {
                        $this->json(['success' => false, 'message' => 'You do not have permission to delete this product.']);
                    }
                    Session::setFlash('error', 'You do not have permission to delete this product.');
                    $this->redirect($redirectUrl);
                    return;
                }

                $productModel->delete($id);
                \Core\Cache::delete('catalog_active');
                
                if ($this->isJsonRequest()) {
                    $this->json(['success' => true, 'message' => ($isCustomize ? 'Fabric' : 'Product') . ' deleted successfully.']);
                }
                Session::setFlash('success', ($isCustomize ? 'Fabric' : 'Product') . ' deleted successfully.');
            } catch (\Exception $e) {
                if ($this->isJsonRequest()) {
                    $this->json(['success' => false, 'message' => 'Cannot delete product: ' . $e->getMessage()]);
                }
                Session::setFlash('error', 'Cannot delete product: ' . $e->getMessage());
            }
        }

        $this->redirect($redirectUrl);
    }

    private function parsePostedAttributes(array $postedAttributes): array
    {
        $parsed = [];
        foreach ($postedAttributes as $attrId => $val) {
            $attrId = (int)$attrId;
            if (is_array($val)) {
                // Checkbox / Multiselect (picklist)
                foreach ($val as $v) {
                    $parsed[] = [
                        'attribute_id' => $attrId,
                        'attribute_value_id' => (int)$v,
                        'value_text' => null
                    ];
                }
            } else {
                // Single value (could be picklist ID for dropdown, or text for textbox)
                $attributeModel = new Attribute();
                $attr = $attributeModel->findById($attrId);
                if (!$attr) continue;

                $val = trim($val);
                if ($val === '') continue;

                if (in_array($attr['input_type'], ['dropdown', 'multiselect', 'checkbox', 'colorpicker'])) {
                    $parsed[] = [
                        'attribute_id' => $attrId,
                        'attribute_value_id' => (int)$val,
                        'value_text' => null
                    ];
                } else {
                    $parsed[] = [
                        'attribute_id' => $attrId,
                        'attribute_value_id' => null,
                        'value_text' => $val
                    ];
                }
            }
        }
        return $parsed;
    }

    public function deleteImage()
    {
        $this->requirePermission('products', 'edit');
        
        $jsonInput = json_decode(file_get_contents('php://input'), true);
        $productId = (int)($_POST['product_id'] ?? ($jsonInput['product_id'] ?? 0));
        $imageId = (int)($_POST['image_id'] ?? ($jsonInput['image_id'] ?? 0));
        $imagePath = trim($_POST['image_path'] ?? ($jsonInput['image_path'] ?? ''));

        $deleted = false;
        $db = \Core\Database::getInstance();

        if ($imageId > 0) {
            $stmt = $db->prepare("DELETE FROM product_images WHERE id = ?");
            $stmt->execute([$imageId]);
            $deleted = true;
        } elseif ($productId > 0 && !empty($imagePath)) {
            $productModel = new Product();
            $productModel->deleteImageByPath($productId, $imagePath);
            $deleted = true;
        }

        if ($deleted) {
            \Core\Cache::delete('catalog_active');
            if ($this->isJsonRequest()) {
                $this->json(['success' => true, 'message' => 'Image removed successfully.']);
            }
            Session::setFlash('success', 'Image removed successfully.');
        } else {
            if ($this->isJsonRequest()) {
                $this->json(['success' => false, 'message' => 'Image not found or missing parameters.'], 400);
            }
            Session::setFlash('error', 'Image not found.');
        }

        $module = $_POST['module'] ?? ($_GET['module'] ?? '');
        $this->redirect('/admin/catalog/products/edit?id=' . $productId . ($module === 'customize' ? '&module=customize' : ''));
    }

    public function approve()
    {
        $role = Session::get('user_role');
        if ($role !== 'super_admin' && !$this->hasPermission('products', 'edit')) {
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
                exit;
            }
            Session::setFlash('error', 'Unauthorized action.');
            $this->redirect('/admin/catalog/products');
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $msg = 'Invalid product ID.';
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
            $this->redirect('/admin/catalog/products');
            return;
        }

        try {
            $productModel = new Product();
            $product = $productModel->findById($id);
            if (!$product) {
                throw new \Exception('Product not found.');
            }

            $productModel->approveProduct($id, (int)Session::get('user_id'));
            \Core\Cache::delete('catalog_active');

            $msg = "Product '{$product['name']}' has been approved and is now active on the catalog!";
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => true, 'message' => $msg]);
                exit;
            }
            Session::setFlash('success', $msg);
        } catch (\Exception $e) {
            $msg = 'Approval failed: ' . $e->getMessage();
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
        }
        $this->redirect('/admin/catalog/products');
    }

    public function reject()
    {
        $role = Session::get('user_role');
        if ($role !== 'super_admin' && !$this->hasPermission('products', 'edit')) {
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
                exit;
            }
            Session::setFlash('error', 'Unauthorized action.');
            $this->redirect('/admin/catalog/products');
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $reason = trim($_POST['rejection_reason'] ?? 'Not approved by administrator');

        if (!$id) {
            $msg = 'Invalid product ID.';
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
            $this->redirect('/admin/catalog/products');
            return;
        }

        try {
            $productModel = new Product();
            $product = $productModel->findById($id);
            if (!$product) {
                throw new \Exception('Product not found.');
            }

            $productModel->rejectProduct($id, (int)Session::get('user_id'), $reason);
            \Core\Cache::delete('catalog_active');

            $msg = "Product '{$product['name']}' has been rejected.";
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => true, 'message' => $msg]);
                exit;
            }
            Session::setFlash('success', $msg);
        } catch (\Exception $e) {
            $msg = 'Rejection failed: ' . $e->getMessage();
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
        }
        $this->redirect('/admin/catalog/products');
    }

    private function isJsonRequest(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
}

