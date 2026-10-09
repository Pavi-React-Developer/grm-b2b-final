<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\CategoryRequest;
use App\Models\Category;

class CategoryRequestController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $role = Session::get('user_role');
        if (!$role) {
            $this->redirect('/login');
            return;
        }

        if (!is_vendor_module_enabled()) {
            Session::setFlash('error', 'Category Request module is vendor-related and is disabled while Multi-Vendor module is inactive.');
            $this->redirect('/admin/dashboard');
            return;
        }
    }

    /**
     * Admin view: Listing category & subcategory requests
     */
    public function index()
    {
        $role = Session::get('user_role');
        if ($role !== 'super_admin' && !$this->hasPermission('categories', 'view')) {
            Session::setFlash('error', 'Unauthorized access.');
            $this->redirect('/admin/dashboard');
            return;
        }

        $status = $_GET['status'] ?? 'pending';
        $search = trim($_GET['search'] ?? '');

        $model = new CategoryRequest();
        $requests = $model->getAllWithFilters($status === 'all' ? null : $status, $search ?: null);
        $pendingCount = $model->getPendingCount();

        $this->render('admin/catalog/category_requests/index', [
            'title' => 'Category Approval Requests',
            'requests' => $requests,
            'currentStatus' => $status,
            'search' => $search,
            'pendingCount' => $pendingCount
        ], 'admin');
    }

    /**
     * Vendor/User action: Submit a new category or subcategory request
     */
    public function store()
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => 'Please log in to submit category requests.']);
                exit;
            }
            $this->redirect('/login');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $requestType = $_POST['request_type'] ?? 'category';
        $parentCategoryId = !empty($_POST['parent_category_id']) ? (int)$_POST['parent_category_id'] : null;
        $hsnCode = trim($_POST['hsn_code'] ?? '');
        $sgst = $_POST['sgst'] ?? '0.00';
        $cgst = $_POST['cgst'] ?? '0.00';
        $description = trim($_POST['description'] ?? '');
        $proposedAttributes = trim($_POST['proposed_attributes'] ?? $_POST['attributes_json'] ?? '');

        if (empty($name)) {
            $msg = 'Category name is required.';
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
            $this->redirect('/admin/catalog/products/create');
            return;
        }

        if ($requestType === 'subcategory' && empty($parentCategoryId)) {
            $msg = 'Please select a parent category for the subcategory.';
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
            $this->redirect('/admin/catalog/products/create');
            return;
        }

        // Check if category name already exists in active categories
        $db = \Core\Database::getInstance();
        if ($requestType === 'category') {
            $stmt = $db->prepare("SELECT id FROM categories WHERE LOWER(name) = LOWER(?) LIMIT 1");
            $stmt->execute([$name]);
            if ($stmt->fetch()) {
                $msg = 'A category with this name already exists in the catalog.';
                if ($this->isJsonRequest()) {
                    echo json_encode(['success' => false, 'message' => $msg]);
                    exit;
                }
                Session::setFlash('error', $msg);
                $this->redirect('/admin/catalog/products/create');
                return;
            }
        } else {
            $stmt = $db->prepare("SELECT id FROM sub_categories WHERE category_id = ? AND LOWER(name) = LOWER(?) LIMIT 1");
            $stmt->execute([$parentCategoryId, $name]);
            if ($stmt->fetch()) {
                $msg = 'A subcategory with this name already exists under the selected category.';
                if ($this->isJsonRequest()) {
                    echo json_encode(['success' => false, 'message' => $msg]);
                    exit;
                }
                Session::setFlash('error', $msg);
                $this->redirect('/admin/catalog/products/create');
                return;
            }
        }

        try {
            $model = new CategoryRequest();
            $requestId = $model->createRequest([
                'vendor_id' => $userId,
                'request_type' => $requestType,
                'parent_category_id' => $parentCategoryId,
                'name' => $name,
                'hsn_code' => $hsnCode,
                'sgst' => $sgst,
                'cgst' => $cgst,
                'description' => $description,
                'proposed_attributes' => $proposedAttributes
            ]);

            $successMsg = ($requestType === 'subcategory' ? 'Subcategory' : 'Category') . " request for '{$name}' submitted successfully! Administrator review is pending.";

            if ($this->isJsonRequest()) {
                echo json_encode([
                    'success' => true,
                    'message' => $successMsg,
                    'request_id' => $requestId
                ]);
                exit;
            }

            Session::setFlash('success', $successMsg);
            $this->redirect('/admin/catalog/products/create');
        } catch (\Exception $e) {
            $errMsg = 'Error submitting category request: ' . $e->getMessage();
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $errMsg]);
                exit;
            }
            Session::setFlash('error', $errMsg);
            $this->redirect('/admin/catalog/products/create');
        }
    }

    /**
     * AJAX endpoint: Fetch my requested categories
     */
    public function myRequests()
    {
        header('Content-Type: application/json');
        $userId = Session::get('user_id');
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $model = new CategoryRequest();
        $requests = $model->getByVendor($userId);

        echo json_encode([
            'success' => true,
            'requests' => $requests
        ]);
        exit;
    }

    /**
     * Admin action: Approve a request
     */
    public function approve()
    {
        $role = Session::get('user_role');
        if ($role !== 'super_admin' && !$this->hasPermission('categories', 'edit')) {
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
                exit;
            }
            Session::setFlash('error', 'Unauthorized action.');
            $this->redirect('/admin/catalog/category-requests');
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $msg = 'Invalid request ID.';
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
            $this->redirect('/admin/catalog/category-requests');
            return;
        }

        try {
            $model = new CategoryRequest();
            $result = $model->approve($id, Session::get('user_id'));

            $msg = "{$result['name']} has been approved and added to live catalog categories!";
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => true, 'message' => $msg, 'result' => $result]);
                exit;
            }

            Session::setFlash('success', $msg);
            $this->redirect('/admin/catalog/category-requests');
        } catch (\Exception $e) {
            $msg = 'Approval failed: ' . $e->getMessage();
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
            $this->redirect('/admin/catalog/category-requests');
        }
    }

    /**
     * Admin action: Reject a request
     */
    public function reject()
    {
        $role = Session::get('user_role');
        if ($role !== 'super_admin' && !$this->hasPermission('categories', 'edit')) {
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
                exit;
            }
            Session::setFlash('error', 'Unauthorized action.');
            $this->redirect('/admin/catalog/category-requests');
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $reason = trim($_POST['rejection_reason'] ?? 'Not approved by administrator');

        if (!$id) {
            $msg = 'Invalid request ID.';
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
            $this->redirect('/admin/catalog/category-requests');
            return;
        }

        try {
            $model = new CategoryRequest();
            $model->reject($id, Session::get('user_id'), $reason);

            $msg = 'Category request has been rejected.';
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => true, 'message' => $msg]);
                exit;
            }

            Session::setFlash('success', $msg);
            $this->redirect('/admin/catalog/category-requests');
        } catch (\Exception $e) {
            $msg = 'Rejection failed: ' . $e->getMessage();
            if ($this->isJsonRequest()) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
            $this->redirect('/admin/catalog/category-requests');
        }
    }

    private function isJsonRequest(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
}
