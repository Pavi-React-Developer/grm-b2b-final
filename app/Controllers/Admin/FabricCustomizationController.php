<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\FabricCustomization;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use PDO;

class FabricCustomizationController extends Controller
{
    private $model;
    private $categoryModel;
    private $subCategoryModel;
    private $productModel;

    public function __construct()
    {
        // Require admin login
        if (!Session::get('user_id') || !in_array(Session::get('user_role'), ['super_admin', 'manager', 'staff'])) {
            Session::setFlash('error', 'Unauthorized access. Please login as admin.');
            $this->redirect('/login');
            exit;
        }

        $this->model = new FabricCustomization();
        $this->categoryModel = new Category();
        $this->subCategoryModel = new SubCategory();
        $this->productModel = new Product();
    }

    /**
     * List all Fabric Customization Rules
     */
    public function index()
    {
        $filters = [
            'status'        => $_GET['status'] ?? '',
            'garment_type'  => $_GET['garment_type'] ?? '',
            'search'        => $_GET['search'] ?? ''
        ];

        $rules = $this->model->getAllWithProductInfo($filters);
        
        // Calculate statistics
        $totalRules = count($rules);
        $activeRules = count(array_filter($rules, fn($r) => $r['status'] === 'active'));
        $totalConfiguredFabrics = count(array_unique(array_column($rules, 'fabric_id')));

        $this->render('admin/fabric_customizations/index', [
            'title'                  => 'Fabric Customization Rules',
            'rules'                  => $rules,
            'filters'                => $filters,
            'totalRules'             => $totalRules,
            'activeRules'            => $activeRules,
            'totalConfiguredFabrics' => $totalConfiguredFabrics
        ], 'admin');
    }

    /**
     * Show create rule form
     */
    public function create()
    {
        $products = $this->model->getAvailableFabricsForDropdown();
        $defaultProduct = !empty($products[0]) ? $products[0] : null;
        
        $this->render('admin/fabric_customizations/create', [
            'title'          => 'Add New Fabric Rule',
            'products'       => $products,
            'defaultProduct' => $defaultProduct
        ], 'admin');
    }

    /**
     * Store new rule
     */
    public function store()
    {
        $fabricId = (int)($_POST['fabric_id'] ?? 0);
        $programName = trim($_POST['program_name'] ?? '');
        $garmentType = trim($_POST['garment_type'] ?? '');
        $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $subCategoryId = !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : null;
        $feedingTypeInput = $_POST['feeding_type'] ?? 'Non-Feeding';
        if (is_array($feedingTypeInput)) {
            $feedingType = implode(', ', array_filter(array_map('trim', $feedingTypeInput)));
        } else {
            $feedingType = trim((string)$feedingTypeInput);
        }
        if (empty($feedingType)) {
            $feedingType = 'Non-Feeding';
        }

        $minPieces = (int)($_POST['minimum_pieces'] ?? 10);
        $maxPieces = !empty($_POST['maximum_pieces']) ? (int)$_POST['maximum_pieces'] : null;
        $wastagePct = (float)($_POST['wastage_percentage'] ?? 0.00);
        $withoutCoverDiscountPct = (float)($_POST['without_cover_discount_percentage'] ?? 0.00);
        $deliveryDays = (int)($_POST['delivery_days'] ?? 7);
        $deliveryBasePieces = (int)($_POST['delivery_base_pieces'] ?? 100);
        $unit = trim($_POST['unit'] ?? 'Qty');
        $status = $_POST['status'] ?? 'active';

        // Auto-resolve fabric and program if not submitted from form
        if (!$fabricId) {
            $db = Database::getInstance();
            $stmtP = $db->query("SELECT id, name, category_id, sub_category_id FROM products WHERE is_customizable = 1 ORDER BY id DESC LIMIT 1");
            $pRow = $stmtP ? $stmtP->fetch(\PDO::FETCH_ASSOC) : null;
            if ($pRow) {
                $fabricId = (int)$pRow['id'];
                if (empty($programName)) {
                    $programName = $pRow['name'] . ' Rule';
                }
                if ($categoryId === null) {
                    $categoryId = $pRow['category_id'];
                }
                if ($subCategoryId === null) {
                    $subCategoryId = $pRow['sub_category_id'];
                }
            }
        }
        if (empty($programName)) {
            $programName = 'Fabric Customization Rule';
        }
        if (empty($garmentType)) {
            $garmentType = 'Custom Garment';
        }

        if (!$fabricId) {
            Session::setFlash('error', 'No customizable fabric product found in inventory.');
            $this->redirect('/admin/fabric-customizations');
            return;
        }

        // Parse sizes array if provided
        $sizesPost = $_POST['sizes'] ?? [];
        $sizes = [];
        if (is_array($sizesPost)) {
            foreach ($sizesPost as $idx => $s) {
                if (!empty($s['size_name'])) {
                    $sizes[] = [
                        'size_name'          => trim($s['size_name']),
                        'fabric_consumption' => isset($s['fabric_consumption']) ? (float)$s['fabric_consumption'] : 1.00,
                        'base_quantity'      => isset($s['base_quantity']) ? max(0, (int)$s['base_quantity']) : 2,
                        'max_quantity'       => isset($s['max_quantity']) ? max(1, (int)$s['max_quantity']) : 4,
                        'unit'               => $unit,
                        'enabled'            => isset($s['enabled']) ? 1 : 0,
                        'sort_order'         => (int)($idx + 1)
                    ];
                }
            }
        }

        try {
            $data = [
                'fabric_id'                         => $fabricId,
                'program_name'                      => $programName,
                'garment_type'                      => $garmentType,
                'category_id'                       => $categoryId,
                'sub_category_id'                   => $subCategoryId,
                'feeding_type'                      => $feedingType,
                'minimum_pieces'                    => $minPieces,
                'maximum_pieces'                    => $maxPieces,
                'wastage_percentage'                => $wastagePct,
                'without_cover_discount_percentage' => $withoutCoverDiscountPct,
                'delivery_days'                     => $deliveryDays,
                'delivery_base_pieces'              => $deliveryBasePieces,
                'unit'                              => $unit,
                'customization_enabled'             => 1,
                'status'                            => $status
            ];

            $newId = $this->model->create($data, $sizes);
            Session::setFlash('success', "Fabric Customization Rule saved successfully.");
            $this->redirect('/admin/fabric-customizations');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error creating fabric rule: ' . $e->getMessage());
            $this->redirect('/admin/fabric-customizations/create');
        }
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            Session::setFlash('error', 'Invalid Fabric Rule ID.');
            $this->redirect('/admin/fabric-customizations');
            return;
        }

        $rule = $this->model->getById($id);
        if (!$rule) {
            Session::setFlash('error', 'Fabric Rule not found.');
            $this->redirect('/admin/fabric-customizations');
            return;
        }

        $products = $this->model->getAvailableFabricsForDropdown($id);
        $categories = $this->categoryModel->getAllActive(null, 1);
        $subCategories = $this->subCategoryModel->getAllActive(1);

        $this->render('admin/fabric_customizations/edit', [
            'title'         => 'Edit Fabric Rule #' . $rule['id'],
            'rule'          => $rule,
            'products'      => $products,
            'categories'    => $categories,
            'subCategories' => $subCategories
        ], 'admin');
    }

    /**
     * Update existing rule
     */
    public function update()
    {
        $id = (int)($_POST['id'] ?? 0);
        $rule = $id > 0 ? $this->model->getById($id) : null;
        if (!$id || !$rule) {
            Session::setFlash('error', 'Fabric Rule not found.');
            $this->redirect('/admin/fabric-customizations');
            return;
        }

        $fabricId = (int)($_POST['fabric_id'] ?? $rule['fabric_id'] ?? 0);
        $programName = trim($_POST['program_name'] ?? $rule['program_name'] ?? 'Fabric Customization Rule');
        $garmentType = trim($_POST['garment_type'] ?? $rule['garment_type'] ?? 'Custom Garment');
        $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : ($rule['category_id'] ?? null);
        $subCategoryId = !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : ($rule['sub_category_id'] ?? null);
        $feedingType = $_POST['feeding_type'] ?? ($rule['feeding_type'] ?? 'Non-Feeding');

        $minPieces = (int)($_POST['minimum_pieces'] ?? 10);
        $maxPieces = !empty($_POST['maximum_pieces']) ? (int)$_POST['maximum_pieces'] : null;
        $wastagePct = (float)($_POST['wastage_percentage'] ?? 0.00);
        $withoutCoverDiscountPct = (float)($_POST['without_cover_discount_percentage'] ?? 0.00);
        $deliveryDays = (int)($_POST['delivery_days'] ?? 7);
        $deliveryBasePieces = (int)($_POST['delivery_base_pieces'] ?? 100);
        $unit = trim($_POST['unit'] ?? 'Qty');
        $status = $_POST['status'] ?? 'active';

        // Parse sizes array if provided
        $sizesPost = $_POST['sizes'] ?? [];
        $sizes = [];
        if (is_array($sizesPost)) {
            foreach ($sizesPost as $idx => $s) {
                if (!empty($s['size_name'])) {
                    $sizes[] = [
                        'size_name'          => trim($s['size_name']),
                        'fabric_consumption' => isset($s['fabric_consumption']) ? (float)$s['fabric_consumption'] : 1.00,
                        'base_quantity'      => isset($s['base_quantity']) ? max(0, (int)$s['base_quantity']) : 2,
                        'max_quantity'       => isset($s['max_quantity']) ? max(1, (int)$s['max_quantity']) : 4,
                        'unit'               => $unit,
                        'enabled'            => isset($s['enabled']) ? 1 : 0,
                        'sort_order'         => (int)($idx + 1)
                    ];
                }
            }
        }

        try {
            $data = [
                'fabric_id'                         => $fabricId,
                'program_name'                      => $programName,
                'garment_type'                      => $garmentType,
                'category_id'                       => $categoryId,
                'sub_category_id'                   => $subCategoryId,
                'feeding_type'                      => $feedingType,
                'minimum_pieces'                    => $minPieces,
                'maximum_pieces'                    => $maxPieces,
                'wastage_percentage'                => $wastagePct,
                'without_cover_discount_percentage' => $withoutCoverDiscountPct,
                'delivery_days'                     => $deliveryDays,
                'delivery_base_pieces'              => $deliveryBasePieces,
                'unit'                              => $unit,
                'customization_enabled'             => 1,
                'status'                            => $status
            ];

            $this->model->update($id, $data, $sizes);
            Session::setFlash('success', "Fabric Customization Rule updated successfully.");
            $this->redirect('/admin/fabric-customizations');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error updating fabric rule: ' . $e->getMessage());
            $this->redirect('/admin/fabric-customizations/edit?id=' . $id);
        }
    }

    /**
     * Delete rule
     */
    public function delete()
    {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            Session::setFlash('error', 'Invalid rule ID.');
            $this->redirect('/admin/fabric-customizations');
            return;
        }

        try {
            $this->model->delete($id);
            Session::setFlash('success', 'Fabric Customization Rule deleted successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Failed to delete rule: ' . $e->getMessage());
        }

        $this->redirect('/admin/fabric-customizations');
    }

    /**
     * AJAX Toggle status
     */
    public function toggleStatus()
    {
        header('Content-Type: application/json');
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        $res = $this->model->toggleStatus($id);
        echo json_encode(['success' => (bool)$res]);
        exit;
    }

    /**
     * AJAX Get Product Details for dynamic form preview
     */
    public function ajaxProductInfo()
    {
        header('Content-Type: application/json');
        $productId = (int)($_GET['product_id'] ?? 0);
        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
            exit;
        }

        $stmt = Database::getInstance()->prepare("
            SELECT id, name, base_sku, wholesale_price, stock_quantity, category_id, sub_category_id, description 
            FROM products WHERE id = ?
        ");
        $stmt->execute([$productId]);
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prod) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        echo json_encode(['success' => true, 'product' => $prod]);
        exit;
    }
}
