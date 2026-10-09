<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use Core\ImageProcessor;
use App\Models\Category;
use App\Models\MediaUpload;
use App\Core\CloudinaryUploader;

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Base admin authentication is handled by routing or BaseController usually.
        // We will strictly rely on requirePermission for authorization.
        $role = Session::get('user_role');
        if (!$role) {
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $this->requirePermission('categories', 'view');
        
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        
        $this->render('admin/catalog/categories/index', [
            'title' => 'Manage Categories',
            'categories' => $categories
        ], 'admin');
    }

    public function store()
    {
        $this->requirePermission('categories', 'create');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/categories');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'hsn_code' => !empty($_POST['hsn_code']) ? trim($_POST['hsn_code']) : null,
            'description' => trim($_POST['description'] ?? ''),
            'image_path' => '',
            'min_order_value' => !empty($_POST['min_order_value']) ? (float)$_POST['min_order_value'] : null,
            'sgst' => isset($_POST['sgst']) && $_POST['sgst'] !== '' ? (float)$_POST['sgst'] : 0.00,
            'cgst' => isset($_POST['cgst']) && $_POST['cgst'] !== '' ? (float)$_POST['cgst'] : 0.00,
            'status' => $_POST['status'] ?? 'active'
        ];

        // Handle image upload via Cloudinary
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploader = new \App\Core\CloudinaryUploader();
            $cloudinaryResponse = $uploader->uploadImage($_FILES['image']['tmp_name']);
            if ($cloudinaryResponse && isset($cloudinaryResponse['secure_url'])) {
                $data['image_path'] = $cloudinaryResponse['secure_url'];
            } else {
                Session::setFlash('error', 'Failed to upload category image to cloud storage.');
            }
        } elseif (isset($_POST['image_path'])) {
            $data['image_path'] = $_POST['image_path'];
        }

        if (empty($data['name']) || empty($data['slug'])) {
            Session::setFlash('error', 'Name and Slug are required.');
            $this->redirect('/admin/catalog/categories');
        }

        try {
            $categoryModel = new Category();
            $categoryModel->create($data);
            Session::setFlash('success', 'Category created successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error creating category: ' . $e->getMessage());
        }

        $this->redirect('/admin/catalog/categories');
    }

    public function update()
    {
        $this->requirePermission('categories', 'edit');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/categories');
        }

        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'hsn_code' => !empty($_POST['hsn_code']) ? trim($_POST['hsn_code']) : null,
            'description' => trim($_POST['description'] ?? ''),
            'min_order_value' => !empty($_POST['min_order_value']) ? (float)$_POST['min_order_value'] : null,
            'sgst' => isset($_POST['sgst']) && $_POST['sgst'] !== '' ? (float)$_POST['sgst'] : 0.00,
            'cgst' => isset($_POST['cgst']) && $_POST['cgst'] !== '' ? (float)$_POST['cgst'] : 0.00,
            'status' => $_POST['status'] ?? 'active'
        ];

        // Handle image upload via Cloudinary (fallback for traditional file upload)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploader = new \App\Core\CloudinaryUploader();
            $cloudinaryResponse = $uploader->uploadImage($_FILES['image']['tmp_name']);
            if ($cloudinaryResponse && isset($cloudinaryResponse['secure_url'])) {
                $data['image_path'] = $cloudinaryResponse['secure_url'];
            } else {
                Session::setFlash('error', 'Failed to upload new category image to cloud storage.');
            }
        } elseif (isset($_POST['image_path'])) {
            // Media selector sets the image_path directly
            $data['image_path'] = $_POST['image_path'];
        } else {
            // Retrieve current image_path if no new file is uploaded and image_path is not in POST
            $categoryModel = new Category();
            $existing = $categoryModel->findById($id);
            $data['image_path'] = $existing['image_path'] ?? '';
        }

        if ($id <= 0 || empty($data['name']) || empty($data['slug'])) {
            Session::setFlash('error', 'Invalid input.');
            $this->redirect('/admin/catalog/categories');
        }

        try {
            $categoryModel = new Category();
            $categoryModel->update($id, $data);

            // Sync updated GST rates to all products & variants in this category
            $newSgst = (float)$data['sgst'];
            $newCgst = (float)$data['cgst'];
            $newTotalGst = $newSgst + $newCgst;

            $db = \Core\Database::getInstance();
            $db->prepare("UPDATE products SET sgst = ?, cgst = ?, total_gst = ? WHERE category_id = ?")
               ->execute([$newSgst, $newCgst, $newTotalGst, $id]);

            $db->prepare("UPDATE product_variants pv JOIN products p ON pv.product_id = p.id SET pv.sgst = ?, pv.cgst = ?, pv.total_gst = ? WHERE p.category_id = ?")
               ->execute([$newSgst, $newCgst, $newTotalGst, $id]);

            // Recalculate max_amount for all variants in this category
            $variants = $db->query("SELECT pv.id, pv.base_price, pv.discount_price FROM product_variants pv JOIN products p ON pv.product_id = p.id WHERE p.category_id = {$id}")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($variants as $v) {
                $eff = (!empty($v['discount_price']) && (float)$v['discount_price'] > 0) ? (float)$v['discount_price'] : (float)$v['base_price'];
                $maxAmt = $eff;
                $db->prepare("UPDATE product_variants SET max_amount = ? WHERE id = ?")->execute([$maxAmt, $v['id']]);
            }

            // Recalculate max_amount for all products in this category
            $prods = $db->query("SELECT id, wholesale_price FROM products WHERE category_id = {$id}")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($prods as $p) {
                $firstVarMax = $db->query("SELECT max_amount FROM product_variants WHERE product_id = {$p['id']} ORDER BY id ASC LIMIT 1")->fetchColumn();
                $prodMax = $firstVarMax ? (float)$firstVarMax : (float)($p['wholesale_price'] ?? 0);
                $db->prepare("UPDATE products SET max_amount = ? WHERE id = ?")->execute([$prodMax, $p['id']]);
            }

            Session::setFlash('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error updating category: ' . $e->getMessage());
        }

        $this->redirect('/admin/catalog/categories');
    }

    public function delete()
    {
        $this->requirePermission('categories', 'delete');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/categories');
        }

        $id = (int)($_POST['id'] ?? 0);
        
        if ($id > 0) {
            try {
                $categoryModel = new Category();
                $categoryModel->delete($id);
                Session::setFlash('success', 'Category deleted successfully.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage() ?: 'Cannot delete category.');
            }
        }

        $this->redirect('/admin/catalog/categories');
    }

    public function checkDelete()
    {
        $this->requirePermission('categories', 'delete');
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id > 0) {
            $categoryModel = new Category();
            $count = $categoryModel->getProductCount($id);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'count' => $count]);
            exit;
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }
}
