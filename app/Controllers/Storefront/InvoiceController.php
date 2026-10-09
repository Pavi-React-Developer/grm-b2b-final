<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    public function download()
    {
        $userId = Session::get('user_id');
        $userRole = Session::get('user_role');
        if (!$userId) {
            $this->redirect('/login');
        }

        $orderNumber = trim($_GET['order'] ?? '');
        if (!$orderNumber) {
            $this->redirect(in_array($userRole, ['admin', 'super_admin', 'staff', 'manager', 'vendor']) ? '/admin/orders' : '/dashboard/orders');
        }

        $isAdmin = in_array($userRole, ['admin', 'super_admin', 'staff', 'manager', 'vendor']);

        // Check ownership if not admin
        if (!$isAdmin) {
            $db = \Core\Database::getInstance();
            $stmt = $db->prepare("SELECT id FROM orders WHERE order_number = ? AND user_id = ? LIMIT 1");
            $stmt->execute([$orderNumber, $userId]);
            if (!$stmt->fetch()) {
                Session::setFlash('error', 'Order not found.');
                $this->redirect('/dashboard/orders');
            }
        }

        $result = InvoiceService::generateInvoicePdf($orderNumber);
        if (!$result) {
            Session::setFlash('error', 'Order invoice not found.');
            $this->redirect($isAdmin ? '/admin/orders' : '/dashboard/orders');
        }

        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
        header('Content-Length: ' . strlen($result['pdf_binary']));
        echo $result['pdf_binary'];
        exit;
    }
}
