<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\OrderRule;
use App\Models\Category;

class OrderRuleController extends Controller
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
        $this->requirePermission('order_rules', 'view');
        $ruleModel = new OrderRule();
        $rules = $ruleModel->getAll();

        $this->render('admin/order_rules/index', [
            'title' => 'Manage Order Rules',
            'rules' => $rules
        ], 'admin');
    }

    public function create()
    {
        $this->requirePermission('order_rules', 'create');
        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive();

        $this->render('admin/order_rules/create', [
            'title' => 'Create Order Rule',
            'categories' => $categories
        ], 'admin');
    }

    public function store()
    {
        $this->requirePermission('order_rules', 'create');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/order-rules');
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $minAmount = (float)($_POST['min_amount'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        // Build the JSON rule mapping
        $secondCategoryRules = null;
        if (!empty($_POST['second_category_ids']) && is_array($_POST['second_category_ids'])) {
            $rulesMap = [];
            foreach ($_POST['second_category_ids'] as $scId) {
                $scId = (int)$scId;
                if (!empty($_POST['second_category_amounts'][$scId])) {
                    $rulesMap[$scId] = (float)$_POST['second_category_amounts'][$scId];
                }
            }
            if (!empty($rulesMap)) {
                $secondCategoryRules = json_encode($rulesMap);
            }
        }

        if ($categoryId <= 0 || $minAmount <= 0) {
            Session::setFlash('error', 'Category and Main Category Minimum Amount are required.');
            $this->redirect('/admin/order-rules/create');
        }

        try {
            $ruleModel = new OrderRule();
            $ruleModel->create([
                'category_id' => $categoryId,
                'second_category_rules' => $secondCategoryRules,
                'min_amount' => $minAmount,
                'status' => $status
            ]);
            
            Session::setFlash('success', 'Order Rule created successfully.');
            $this->redirect('/admin/order-rules');
        } catch (\Exception $e) {
            Session::setFlash('error', $e->getMessage());
            $this->redirect('/admin/order-rules/create');
        }
    }

    public function edit()
    {
        $this->requirePermission('order_rules', 'edit');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $ruleModel = new OrderRule();
        $rule = $ruleModel->findById($id);

        if (!$rule) {
            Session::setFlash('error', 'Rule not found.');
            $this->redirect('/admin/order-rules');
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive();

        $allCategories = $categoryModel->getAllActive();
        // Filter for second categories (all except the main one)
        $secondCategories = array_values(array_filter($allCategories, function($cat) use ($rule) {
            return $cat['id'] != $rule['category_id'];
        }));

        $this->render('admin/order_rules/edit', [
            'title' => 'Edit Order Rule',
            'rule' => $rule,
            'categories' => $allCategories,
            'secondCategories' => $secondCategories
        ], 'admin');
    }

    public function update()
    {
        $this->requirePermission('order_rules', 'edit');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/order-rules');
        }

        $id = (int)($_POST['id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $minAmount = (float)($_POST['min_amount'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        // Build the JSON rule mapping
        $secondCategoryRules = null;
        if (!empty($_POST['second_category_ids']) && is_array($_POST['second_category_ids'])) {
            $rulesMap = [];
            foreach ($_POST['second_category_ids'] as $scId) {
                $scId = (int)$scId;
                if (!empty($_POST['second_category_amounts'][$scId])) {
                    $rulesMap[$scId] = (float)$_POST['second_category_amounts'][$scId];
                }
            }
            if (!empty($rulesMap)) {
                $secondCategoryRules = json_encode($rulesMap);
            }
        }

        if ($id <= 0 || $categoryId <= 0 || $minAmount <= 0) {
            Session::setFlash('error', 'Category and Main Category Minimum Amount are required.');
            $this->redirect('/admin/order-rules/edit?id=' . $id);
        }

        try {
            $ruleModel = new OrderRule();
            $ruleModel->update($id, [
                'category_id' => $categoryId,
                'second_category_rules' => $secondCategoryRules,
                'min_amount' => $minAmount,
                'status' => $status
            ]);
            
            Session::setFlash('success', 'Order Rule updated successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', $e->getMessage());
        }

        $this->redirect('/admin/order-rules');
    }

    public function delete()
    {
        $this->requirePermission('order_rules', 'delete');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/order-rules');
        }

        $id = (int)($_POST['id'] ?? 0);
        
        if ($id > 0) {
            $ruleModel = new OrderRule();
            $ruleModel->delete($id);
            Session::setFlash('success', 'Order Rule deleted successfully.');
        }

        $this->redirect('/admin/order-rules');
    }

    public function apiSecondCategories()
    {
        $this->requirePermission('order_rules', 'view');
        header('Content-Type: application/json');
        
        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        
        if ($categoryId <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid category ID']);
            return;
        }

        $categoryModel = new Category();
        $allCategories = $categoryModel->getAll();
        
        // Filter out the selected category
        $secondCategories = array_filter($allCategories, function($cat) use ($categoryId) {
            return $cat['id'] != $categoryId;
        });

        echo json_encode([
            'status' => 'success',
            // Re-index array for JSON encoding as list
            'data' => array_values($secondCategories)
        ]);
    }
}
