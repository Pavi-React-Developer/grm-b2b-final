<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $role = Session::get('user_role');
        if (!$role) {
            $this->redirect('/login');
        }
    }

    public function ajaxGetByCategory()
    {
        $this->requirePermission('subcategories', 'view');
        header('Content-Type: application/json');
        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        
        if ($categoryId <= 0) {
            echo json_encode([]);
            exit;
        }

        $subCategoryModel = new SubCategory();
        $subCategories = $subCategoryModel->getByCategory($categoryId);
        echo json_encode($subCategories);
        exit;
    }

    public function index()
    {
        $this->requirePermission('subcategories', 'view');
        
        $module = $_GET['module'] ?? '';
        $isCustomize = ($module === 'customize');

        $subCategoryModel = new SubCategory();
        $subCategories = $subCategoryModel->getAll($isCustomize ? 1 : 0);
        
        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive(null, $isCustomize ? 1 : 0);

        $this->render('admin/catalog/subcategories/index', [
            'title' => $isCustomize ? 'Customize Subcategories' : 'Manage Subcategories',
            'subCategories' => $subCategories,
            'categories' => $categories,
            'module' => $module,
            'isCustomize' => $isCustomize
        ], 'admin');
    }

    public function store()
    {
        $this->requirePermission('subcategories', 'create');

        $module = $_POST['module'] ?? ($_GET['module'] ?? '');
        $isCustomize = ($module === 'customize') || (!empty($_POST['is_customizable']) && (int)$_POST['is_customizable'] === 1);
        $redirectUrl = '/admin/catalog/subcategories' . ($isCustomize ? '?module=customize' : '');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($redirectUrl);
        }

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = $name;
        }

        $data = [
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'name' => $name,
            'slug' => $slug,
            'status' => $_POST['status'] ?? 'active',
            'is_customizable' => $isCustomize ? 1 : 0
        ];

        if ($data['category_id'] <= 0 || empty($data['name'])) {
            Session::setFlash('error', 'Parent Category and Name are required.');
            $this->redirect($redirectUrl);
            return;
        }

        try {
            $subCategoryModel = new SubCategory();

            // Prevent duplicate subcategory names within parent category
            $existingDuplicate = $subCategoryModel->findByName($data['name'], $data['category_id'], $isCustomize ? 1 : 0);
            if ($existingDuplicate) {
                Session::setFlash('error', 'A subcategory named "' . htmlspecialchars($data['name']) . '" already exists in this category. Duplicate subcategories are not allowed.');
                $this->redirect($redirectUrl);
                return;
            }

            $subCategoryModel->create($data);
            Session::setFlash('success', ($isCustomize ? 'Customize Subcategory' : 'Subcategory') . ' created successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error creating subcategory: ' . $e->getMessage());
        }

        $this->redirect($redirectUrl);
    }

    public function update()
    {
        $this->requirePermission('subcategories', 'edit');

        $module = $_POST['module'] ?? ($_GET['module'] ?? '');
        $isCustomize = ($module === 'customize') || (!empty($_POST['is_customizable']) && (int)$_POST['is_customizable'] === 1);
        $redirectUrl = '/admin/catalog/subcategories' . ($isCustomize ? '?module=customize' : '');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($redirectUrl);
        }

        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = $name;
        }

        $data = [
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'name' => $name,
            'slug' => $slug,
            'status' => $_POST['status'] ?? 'active',
            'is_customizable' => $isCustomize ? 1 : 0
        ];

        if ($id <= 0 || $data['category_id'] <= 0 || empty($data['name'])) {
            Session::setFlash('error', 'Invalid input.');
            $this->redirect($redirectUrl);
            return;
        }

        try {
            $subCategoryModel = new SubCategory();

            // Prevent duplicate subcategory names within parent category
            $existingDuplicate = $subCategoryModel->findByName($data['name'], $data['category_id'], $isCustomize ? 1 : 0, $id);
            if ($existingDuplicate) {
                Session::setFlash('error', 'A subcategory named "' . htmlspecialchars($data['name']) . '" already exists in this category. Duplicate subcategories are not allowed.');
                $this->redirect($redirectUrl);
                return;
            }

            $subCategoryModel->update($id, $data);
            Session::setFlash('success', 'Subcategory updated successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error updating subcategory: ' . $e->getMessage());
        }

        $this->redirect($redirectUrl);
    }

    public function delete()
    {
        $this->requirePermission('subcategories', 'delete');

        $module = $_POST['module'] ?? ($_GET['module'] ?? '');
        $isCustomize = ($module === 'customize');
        $redirectUrl = '/admin/catalog/subcategories' . ($isCustomize ? '?module=customize' : '');

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
                $subCategoryModel = new SubCategory();
                $subCategoryModel->delete($id);
                if (class_exists('\Core\Cache')) {
                    \Core\Cache::delete('catalog_active');
                }
                if ($this->isJsonRequest()) {
                    $this->json(['success' => true, 'message' => ($isCustomize ? 'Customize Subcategory' : 'Subcategory') . ' deleted successfully.']);
                }
                Session::setFlash('success', ($isCustomize ? 'Customize Subcategory' : 'Subcategory') . ' deleted successfully.');
            } catch (\Exception $e) {
                if ($this->isJsonRequest()) {
                    $this->json(['success' => false, 'message' => $e->getMessage() ?: 'Cannot delete subcategory because it is in use.']);
                }
                Session::setFlash('error', $e->getMessage() ?: 'Cannot delete subcategory because it is in use.');
            }
        }

        $this->redirect($redirectUrl);
    }

    private function isJsonRequest(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
}
