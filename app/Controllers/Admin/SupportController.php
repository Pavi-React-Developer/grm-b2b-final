<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\SupportTicket;
use App\Models\VendorProfile;

class SupportController extends Controller
{
    private $ticketModel;
    private $vendorProfileModel;

    public function __construct()
    {
        parent::__construct();
        $this->ticketModel = new SupportTicket();
        $this->vendorProfileModel = new VendorProfile();

        $role = Session::get('user_role');
        if (!$role) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if (!headers_sent()) { header('Content-Type: application/json'); }
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }
            Session::setFlash('error', 'Unauthorized access.');
            $this->redirect('/login');
            return;
        }

        if (!is_vendor_module_enabled()) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if (!headers_sent()) { header('Content-Type: application/json'); }
                echo json_encode(['success' => false, 'message' => 'Support Desk is unavailable because the vendor module is disabled.']);
                exit;
            }
            Session::setFlash('error', 'Support Desk is unavailable because the vendor module is disabled.');
            $this->redirect('/admin/dashboard');
            return;
        }
    }

    /**
     * Support & Helpdesk Index
     */
    public function index()
    {
        $this->requirePermission('support', 'view');

        $role = Session::get('user_role');
        $userId = (int)Session::get('user_id');
        $isVendor = ($role === 'vendor');

        $statusFilter = $_GET['status'] ?? 'all';
        $priorityFilter = $_GET['priority'] ?? 'all';
        $search = $_GET['search'] ?? null;
        $vendorFilter = isset($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : null;

        if ($isVendor) {
            $tickets = $this->ticketModel->getVendorTickets($userId, $statusFilter, $priorityFilter);
            $counts = $this->ticketModel->getCounts($userId);
            $vendorsList = [];
        } else {
            $tickets = $this->ticketModel->getAllTickets($statusFilter, $priorityFilter, $vendorFilter, $search);
            $counts = $this->ticketModel->getCounts();
            $vendorsList = $this->vendorProfileModel->getAllVendorsBasic() ?? [];
        }

        $this->render('admin/support/index', [
            'title'          => $isVendor ? 'Helpdesk & Support Queries' : 'Vendor Support Desk & Inquiries',
            'isVendor'       => $isVendor,
            'tickets'        => $tickets,
            'counts'         => $counts,
            'statusFilter'   => $statusFilter,
            'priorityFilter' => $priorityFilter,
            'vendorFilter'   => $vendorFilter,
            'search'         => $search,
            'vendorsList'    => $vendorsList
        ], 'admin');
    }

    /**
     * Raise a new query / ticket (Vendor action)
     */
    public function create()
    {
        if (!headers_sent() && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
            header('Content-Type: application/json');
        }

        $role = Session::get('user_role');
        $vendorId = (int)Session::get('user_id');

        $subject = trim($_POST['subject'] ?? '');
        $category = trim($_POST['category'] ?? 'General Inquiry');
        $priority = strtolower(trim($_POST['priority'] ?? 'medium'));
        $message = trim($_POST['message'] ?? '');

        if (empty($subject) || empty($message)) {
            $err = 'Please provide a subject and query details.';
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                echo json_encode(['success' => false, 'message' => $err]);
                return;
            }
            Session::setFlash('error', $err);
            $this->redirect('/admin/support');
            return;
        }

        // Handle attachment upload directly to Cloudinary (no local storage)
        $attachmentPath = null;
        if (!empty($_FILES['attachment']['name']) && !empty($_FILES['attachment']['tmp_name']) && (isset($_FILES['attachment']['error']) ? $_FILES['attachment']['error'] === UPLOAD_ERR_OK : true)) {
            $file = $_FILES['attachment'];
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'gif', 'doc', 'docx', 'svg'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowedExts) && $file['size'] <= 20 * 1024 * 1024) {
                require_once BASE_PATH . '/app/Core/CloudinaryUploader.php';
                $uploader = new CloudinaryUploader();
                $publicId = 'support/tkt_' . $vendorId . '_' . time() . '_' . mt_rand(100, 999);
                $cloudRes = $uploader->uploadMedia($file['tmp_name'], $publicId, $file['name']);
                if ($cloudRes && !empty($cloudRes['secure_url'])) {
                    $attachmentPath = $cloudRes['secure_url'];
                }
            }
        }

        try {
            $ticketNumber = $this->ticketModel->createTicket($vendorId, [
                'subject'  => $subject,
                'category' => $category,
                'priority' => $priority,
                'message'  => $message
            ], $attachmentPath);

            $msg = "Support query raised successfully with Reference ID: {$ticketNumber}. Our team will review and reply shortly.";
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                echo json_encode(['success' => true, 'message' => $msg, 'ticket_number' => $ticketNumber]);
                return;
            }

            Session::setFlash('success', $msg);
            $this->redirect('/admin/support');
        } catch (\Throwable $e) {
            $err = 'Failed to submit query: ' . $e->getMessage();
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                echo json_encode(['success' => false, 'message' => $err]);
                return;
            }
            Session::setFlash('error', $err);
            $this->redirect('/admin/support');
        }
    }

    /**
     * Get ticket details and conversation thread (AJAX API)
     */
    public function viewDetails($id = null)
    {
        if (!headers_sent()) { header('Content-Type: application/json'); }

        $id = $id ?? $_GET['id'] ?? null;
        $role = Session::get('user_role');
        $userId = (int)Session::get('user_id');
        $vendorId = ($role === 'vendor') ? $userId : null;

        $ticket = $this->ticketModel->getTicketById((int)$id, $vendorId);
        if (!$ticket) {
            echo json_encode(['success' => false, 'message' => 'Ticket not found or permission denied.']);
            return;
        }

        $messages = $this->ticketModel->getMessages((int)$id);

        echo json_encode([
            'success'  => true,
            'ticket'   => $ticket,
            'messages' => $messages
        ]);
    }

    /**
     * Reply to a ticket
     */
    public function reply($id = null)
    {
        if (!headers_sent()) { header('Content-Type: application/json'); }

        $role = Session::get('user_role');
        $userId = (int)Session::get('user_id');
        $ticketId = (int)($id ?? $_POST['ticket_id'] ?? $_POST['id'] ?? 0);

        $vendorId = ($role === 'vendor') ? $userId : null;
        $ticket = $this->ticketModel->getTicketById($ticketId, $vendorId);
        if (!$ticket) {
            echo json_encode(['success' => false, 'message' => 'Ticket not found or access denied.']);
            return;
        }

        $message = trim($_POST['message'] ?? '');
        if (empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Reply message cannot be empty.']);
            return;
        }

        // Handle reply attachment directly to Cloudinary (no local storage)
        $attachmentPath = null;
        if (!empty($_FILES['attachment']['name']) && !empty($_FILES['attachment']['tmp_name']) && (isset($_FILES['attachment']['error']) ? $_FILES['attachment']['error'] === UPLOAD_ERR_OK : true)) {
            $file = $_FILES['attachment'];
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'gif', 'doc', 'docx', 'svg'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowedExts) && $file['size'] <= 20 * 1024 * 1024) {
                require_once BASE_PATH . '/app/Core/CloudinaryUploader.php';
                $uploader = new CloudinaryUploader();
                $publicId = 'support/reply_' . $userId . '_' . time() . '_' . mt_rand(100, 999);
                $cloudRes = $uploader->uploadMedia($file['tmp_name'], $publicId, $file['name']);
                if ($cloudRes && !empty($cloudRes['secure_url'])) {
                    $attachmentPath = $cloudRes['secure_url'];
                }
            }
        }

        try {
            $senderRole = ($role === 'vendor') ? 'vendor' : 'admin';
            $msgId = $this->ticketModel->addReply($ticketId, $userId, $senderRole, $message, $attachmentPath);

            echo json_encode([
                'success' => true,
                'message' => 'Reply posted successfully.',
                'msg_id'  => $msgId
            ]);
        } catch (\Throwable $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Update ticket status & admin internal notes (Admin action)
     */
    public function updateStatus($id = null)
    {
        if (!headers_sent()) { header('Content-Type: application/json'); }

        $role = Session::get('user_role');
        if ($role === 'vendor') {
            echo json_encode(['success' => false, 'message' => 'Only Admin can update ticket status.']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $ticketId = (int)($id ?? $input['ticket_id'] ?? $input['id'] ?? 0);
        $status = trim($input['status'] ?? '');
        $adminNotes = isset($input['admin_notes']) ? trim($input['admin_notes']) : null;

        if (!in_array($status, ['open', 'in_progress', 'resolved', 'closed'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid status option.']);
            return;
        }

        try {
            if ($this->ticketModel->updateStatus($ticketId, $status, $adminNotes)) {
                echo json_encode(['success' => true, 'message' => 'Ticket status updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
            }
        } catch (\Throwable $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Update ticket priority (Admin action)
     */
    public function updatePriority($id = null)
    {
        if (!headers_sent()) { header('Content-Type: application/json'); }

        $role = Session::get('user_role');
        if ($role === 'vendor') {
            echo json_encode(['success' => false, 'message' => 'Only Admin can update priority.']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $ticketId = (int)($id ?? $input['ticket_id'] ?? $input['id'] ?? 0);
        $priority = strtolower(trim($input['priority'] ?? ''));

        if (!in_array($priority, ['high', 'medium', 'low'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid priority option.']);
            return;
        }

        try {
            if ($this->ticketModel->updatePriority($ticketId, $priority)) {
                echo json_encode(['success' => true, 'message' => 'Ticket priority updated.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update priority.']);
            }
        } catch (\Throwable $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
