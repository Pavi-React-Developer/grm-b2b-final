<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\Category;
use App\Models\Product;

class CatalogController extends Controller
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
        if (!$this->hasPermission('categories', 'view') && !$this->hasPermission('products', 'view')) {
            Session::setFlash('error', 'You do not have permission to view the catalog.');
            $this->redirect('/admin/dashboard');
        }
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        $productModel = new Product();
        $products = $productModel->getAllAdmin();

        $this->render('admin/catalog', [
            'title' => 'Manage Catalog',
            'categories' => $categories,
            'products' => $products
        ], 'admin');
    }

    public function storeCategory()
    {
        $this->requirePermission('categories', 'create');
        $name = trim($_POST['name'] ?? '');
        $moq = (int)($_POST['moq'] ?? 10);
        
        if (empty($name)) {
            Session::setFlash('error', 'Category name is required.');
            $this->redirect('/admin/catalog');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

        try {
            $model = new Category();
            $model->create([
                'name' => $name,
                'slug' => $slug,
                'moq' => $moq,
                'status' => 'active'
            ]);
            Session::setFlash('success', 'Category created successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error creating category. (Slug might exist)');
        }
        
        $this->redirect('/admin/catalog');
    }

    public function storeProduct()
    {
        $this->requirePermission('products', 'create');
        // Simple mock for Phase 3 testing
        $categoryId = $_POST['category_id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $wholesalePrice = $_POST['wholesale_price'] ?? 0;
        
        if (empty($name) || empty($categoryId) || empty($wholesalePrice)) {
            Session::setFlash('error', 'Product name, category, and wholesale price are required.');
            $this->redirect('/admin/catalog');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . uniqid();

        try {
            $model = new Product();
            $productId = $model->create([
                'category_id' => $categoryId,
                'name' => $name,
                'slug' => $slug,
                'wholesale_price' => $wholesalePrice,
                'status' => 'active'
            ]);
            
            // Note: In a full app, we'd handle image uploads here and save to product_images
            
            Session::setFlash('success', 'Product created successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error creating product.');
        }
        
        $this->redirect('/admin/catalog');
    }
}
