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
        
        $subCategoryModel = new SubCategory();
        $subCategories = $subCategoryModel->getAll();
        
        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive();

        $this->render('admin/catalog/subcategories/index', [
            'title' => 'Manage Subcategories',
            'subCategories' => $subCategories,
            'categories' => $categories
        ], 'admin');
    }

    public function store()
    {
        $this->requirePermission('subcategories', 'create');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/subcategories');
        }

        $data = [
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'status' => $_POST['status'] ?? 'active'
        ];

        if ($data['category_id'] <= 0 || empty($data['name']) || empty($data['slug'])) {
            Session::setFlash('error', 'Category, Name, and Slug are required.');
            $this->redirect('/admin/catalog/subcategories');
        }

        try {
            $subCategoryModel = new SubCategory();
            $subCategoryModel->create($data);
            Session::setFlash('success', 'Subcategory created successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error creating subcategory: ' . $e->getMessage());
        }

        $this->redirect('/admin/catalog/subcategories');
    }

    public function update()
    {
        $this->requirePermission('subcategories', 'edit');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/subcategories');
        }

        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'status' => $_POST['status'] ?? 'active'
        ];

        if ($id <= 0 || $data['category_id'] <= 0 || empty($data['name']) || empty($data['slug'])) {
            Session::setFlash('error', 'Invalid input.');
            $this->redirect('/admin/catalog/subcategories');
        }

        try {
            $subCategoryModel = new SubCategory();
            $subCategoryModel->update($id, $data);
            Session::setFlash('success', 'Subcategory updated successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error updating subcategory: ' . $e->getMessage());
        }

        $this->redirect('/admin/catalog/subcategories');
    }

    public function delete()
    {
        $this->requirePermission('subcategories', 'delete');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/subcategories');
        }

        $id = (int)($_POST['id'] ?? 0);
        
        if ($id > 0) {
            try {
                $subCategoryModel = new SubCategory();
                $subCategoryModel->delete($id);
                Session::setFlash('success', 'Subcategory deleted successfully.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage() ?: 'Cannot delete subcategory because it is in use.');
            }
        }

        $this->redirect('/admin/catalog/subcategories');
    }
}
