<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\RefundRequest;
use App\Models\Order;
use App\Models\User;
use App\Services\EmailService;

/**
 * Admin\CancellationController
 *
 * Handles the FIRST step of the cancellation workflow:
 * Admin reviews cancellation requests, then either:
 *   - APPROVES  → status becomes 'processing' (moves to Refunds sub-module for payment)
 *   - REJECTS   → status becomes 'rejected', order goes to 'cancelled', user emailed
 *
 * Routes:
 *   GET  /admin/cancellations          → index()
 *   POST /admin/cancellations/approve  → approve()
 *   POST /admin/cancellations/reject   → reject()
 *
 * Security: super_admin, manager, staff (staff can view; only manager+ can approve/reject)
 */
class CancellationController extends Controller
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

    // -------------------------------------------------------------------------
    // GET /admin/cancellations
    // -------------------------------------------------------------------------

    public function index(): void
    {
        if (!$this->hasPermission('all_cancellations', 'view') && !$this->hasPermission('all_cancellations', 'edit')) {
            $this->requirePermission('all_cancellations', 'view');
        }
        $refundModel = new RefundRequest();
        $filter      = $_GET['filter'] ?? 'pending';   // pending | all | rejected
        $page        = max(1, (int)($_GET['page'] ?? 1));
        $counts      = $refundModel->getCancellationStatusCounts();

        // Map tab filter → DB statuses shown on Cancellations page
        $statusMap = [
            'pending'  => ['Pending_Admin_Approval'],
            'all'      => ['Pending_Admin_Approval', 'Processing', 'Completed', 'Rejected'],
            'rejected' => ['Rejected'],
        ];
        $statuses = $statusMap[$filter] ?? ['Pending_Admin_Approval'];

        $result = $refundModel->getPaginatedByStatuses($statuses, $page, 15);

        $this->render('admin/cancellations/index', [
            'title'    => 'all_cancellations',
            'requests' => $result['data'],
            'total'    => $result['total'],
            'pages'    => $result['pages'],
            'current'  => $result['current'],
            'filter'   => $filter,
            'counts'   => $counts,
        ], 'admin');
    }

    // -------------------------------------------------------------------------
    // POST /admin/cancellations/approve
    // -------------------------------------------------------------------------

    /**
     * Approve the cancellation request.
     * Moves status to 'processing' — the refund payment is handled in RefundController.
     */
    public function approve(): void
    {
        $this->requirePermission('all_cancellations', 'edit');
        header('Content-Type: application/json');

        $input     = $this->parseJsonInput();
        $reqId     = (int)($input['refund_request_id'] ?? 0);
        $adminNote = trim($input['admin_notes'] ?? '');
        $adminId   = (int)Session::get('user_id');

        if (!$reqId) {
            $this->json(['success' => false, 'message' => 'Invalid request ID.'], 422);
        }

        $refundModel = new RefundRequest();
        $req         = $refundModel->findById($reqId);

        if (!$req) {
            $this->json(['success' => false, 'message' => 'Cancellation request not found.'], 404);
        }

        if ($req['status'] !== 'Pending_Admin_Approval') {
            $this->json([
                'success' => false,
                'message' => "Cannot approve — current status is: " . str_replace('_', ' ', $req['status']),
            ], 409);
        }

        // Mark as 'processing' (approved, ready for Cashfree refund)
        $success = $refundModel->updateStatus($reqId, 'Processing', [
            'admin_notes'  => $adminNote ?: 'Cancellation approved. Pending payment refund.',
            'processed_by' => $adminId,
        ]);

        // Update order → refund_processing
        $orderModel = new Order();
        $orderModel->updateOrderCancellationStatus((int)$req['order_id'], 'refund_processing');

        $this->json([
            'success' => true,
            'message' => "Cancellation approved. The refund is now queued — go to Refunds to process the payment.",
        ]);
    }

    // -------------------------------------------------------------------------
    // POST /admin/cancellations/reject
    // -------------------------------------------------------------------------

    public function reject(): void
    {
        $this->requirePermission('all_cancellations', 'edit');
        header('Content-Type: application/json');

        $input     = $this->parseJsonInput();
        $reqId     = (int)($input['refund_request_id'] ?? 0);
        $adminNote = trim($input['admin_notes'] ?? '');
        $adminId   = (int)Session::get('user_id');

        if (!$reqId) {
            $this->json(['success' => false, 'message' => 'Invalid request ID.'], 422);
        }

        if (empty($adminNote) || strlen($adminNote) < 5) {
            $this->json(['success' => false, 'message' => 'A rejection reason (min 5 characters) is required.'], 422);
        }

        $refundModel = new RefundRequest();
        $req         = $refundModel->findById($reqId);

        if (!$req) {
            $this->json(['success' => false, 'message' => 'Request not found.'], 404);
        }

        if (!in_array($req['status'], ['Pending_Admin_Approval'])) {
            $this->json([
                'success' => false,
                'message' => "Cannot reject — current status is: " . str_replace('_', ' ', $req['status']),
            ], 422);
        }

        $success = $refundModel->updateStatus($reqId, 'Rejected', [
            'admin_notes'  => $adminNote,
            'processed_by' => $adminId,
        ]);

        // Revert order to 'cancelled'
        $orderModel = new Order();
        $orderModel->updateOrderCancellationStatus((int)$req['order_id'], 'cancelled');

        // Email user
        try {
            $userModel = new User();
            $user      = $userModel->findById((int)$req['user_id']);
            if ($user) {
                (new EmailService())->sendRefundRejected($user, $req, $adminNote);
            }
        } catch (\Exception $e) {
            error_log('[Admin\CancellationController::reject] Email: ' . $e->getMessage());
        }

        $this->json(['success' => true, 'message' => 'Cancellation rejected and the buyer has been notified by email.']);
    }

    // -------------------------------------------------------------------------
    private function parseJsonInput(): array
    {
        $ct = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($ct, 'application/json')) {
            return json_decode(file_get_contents('php://input'), true) ?? [];
        }
        return $_POST;
    }
}
