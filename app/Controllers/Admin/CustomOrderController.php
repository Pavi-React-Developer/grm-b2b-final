<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\Order;

class CustomOrderController extends Controller
{
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        $role = Session::get('user_role');
        if (!$role) {
            Session::setFlash('error', 'Unauthorized access.');
            $this->redirect('/login');
        }
        $this->orderModel = new Order();
    }

    /**
     * List all customized fabric orders & requests
     */
    public function index()
    {
        $this->requirePermission('custom_orders', 'view');

        $status = $_GET['status'] ?? 'all';
        $orders = $this->orderModel->getCustomOrders($status);
        $statusCounts = $this->orderModel->getCustomOrderStatusCounts();

        $this->render('admin/custom_orders/index', [
            'title' => 'Customized Garments & Fabric Orders',
            'orders' => $orders,
            'currentStatus' => $status,
            'statusCounts' => $statusCounts
        ], 'admin');
    }

    /**
     * Update order status via AJAX or POST
     */
    public function updateStatus()
    {
        $this->requirePermission('custom_orders', 'edit');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            return;
        }

        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $orderId = $input['order_id'] ?? $_POST['order_id'] ?? null;
        $status = $input['status'] ?? $_POST['status'] ?? null;
        $paymentStatus = $input['payment_status'] ?? $_POST['payment_status'] ?? null;

        if (!$orderId || !$status) {
            echo json_encode(['success' => false, 'message' => 'Order ID and Status are required.']);
            return;
        }

        $db = Database::getInstance();
        $allowedStatuses = ['placed', 'stitching', 'in_production', 'quality_check', 'packed', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($status, $allowedStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Invalid status provided.']);
            return;
        }

        $sets = ["status = :status", "updated_at = NOW()"];
        $params = ['status' => $status, 'order_id' => $orderId];

        if ($paymentStatus && in_array($paymentStatus, ['paid', 'request_pending', 'pending'])) {
            $sets[] = "payment_status = :pstatus";
            $params['pstatus'] = $paymentStatus;
        }

        $sql = "UPDATE orders SET " . implode(', ', $sets) . " WHERE id = :order_id OR order_number = :order_id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($params);

        if ($res) {
            echo json_encode(['success' => true, 'message' => "Order status successfully updated to " . ucfirst(str_replace('_', ' ', $status))]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error updating status.']);
        }
    }
}
