<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\FabricCustomization;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\CustomizationLabel;
use PDO;

class FabricCustomizationController extends Controller
{
    private $model;
    private $categoryModel;
    private $subCategoryModel;
    private $productModel;
    private $labelModel;

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
        $this->labelModel = new CustomizationLabel();
    }

    /**
     * List all Fabric Customization Rules (Redirected to Unified Customizable Products/Fabrics)
     */
    public function index()
    {
        $this->redirect('/admin/catalog/products?module=customize');
    }

    /**
     * Show create rule form (Redirected to Unified Customizable Fabric Create)
     */
    public function create()
    {
        $this->redirect('/admin/catalog/products/create?module=customize');
    }

    /**
     * Show edit form (Redirected to Unified Customizable Fabric Edit)
     */
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $rule = $this->model->getById($id);
            if ($rule && !empty($rule['fabric_id'])) {
                $this->redirect('/admin/catalog/products/edit?id=' . $rule['fabric_id'] . '&module=customize');
                return;
            }
        }
        $this->redirect('/admin/catalog/products?module=customize');
    }

    /**
     * Update existing rule
     */
    public function update()
    {
        $id = (int)($_POST['id'] ?? 0);
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
        $unit = trim($_POST['unit'] ?? 'Qty');
        $status = $_POST['status'] ?? 'active';
        $labelIds = array_filter(array_map('intval', $_POST['label_ids'] ?? []));

        if (!$id || !$fabricId || empty($programName) || empty($garmentType)) {
            Session::setFlash('error', 'Missing required fields.');
            $this->redirect('/admin/fabric-customizations/edit?id=' . $id);
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
                'fabric_id'             => $fabricId,
                'program_name'          => $programName,
                'garment_type'          => $garmentType,
                'category_id'           => $categoryId,
                'sub_category_id'       => $subCategoryId,
                'feeding_type'          => $feedingType,
                'minimum_pieces'        => $minPieces,
                'maximum_pieces'        => $maxPieces,
                'wastage_percentage'    => $wastagePct,
                'unit'                  => $unit,
                'customization_enabled' => 1,
                'status'                => $status
            ];

            $this->model->update($id, $data, $sizes, $labelIds);
            Session::setFlash('success', "Fabric Customization Rule updated successfully.");
            $this->redirect('/admin/fabric-customizations');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error updating rule: ' . $e->getMessage());
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
