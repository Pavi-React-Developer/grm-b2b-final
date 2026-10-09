<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use App\Models\OrderModification;

class OrderModificationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Session::get('user_id')) {
            $this->redirect('/login');
        }
    }

    /**
     * Buyer review page for a pending modification.
     */
    public function review()
    {
        $modId  = (int)($_GET['id'] ?? 0);
        $userId = (int)Session::get('user_id');

        $modModel = new OrderModification();
        $mod      = $modModel->getModificationWithItems($modId);

        if (!$mod || (int)$mod['buyer_id'] !== $userId) {
            Session::setFlash('error', 'Modification not found.');
            $this->redirect('/dashboard/orders');
        }

        $this->render('storefront/order_modification_review', [
            'title' => 'Review Order Modification — ' . $mod['order_number'],
            'mod'   => $mod,
        ]);
    }

    /**
     * Buyer submits accept/reject decision.
     */
    public function respond()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard/orders');
        }

        $modId    = (int)($_POST['modification_id'] ?? 0);
        $decision = $_POST['decision'] ?? '';
        $userId   = (int)Session::get('user_id');

        if (!$modId || !in_array($decision, ['accept', 'reject'])) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/dashboard/orders');
        }

        try {
            $modModel = new OrderModification();
            $ok       = $modModel->buyerRespond($modId, $userId, $decision);

            if ($ok) {
                if ($decision === 'accept') {
                    Session::setFlash('success', 'You accepted the modification. Your order has been updated and will be processed shortly.');
                } else {
                    Session::setFlash('success', 'You rejected the modification. Your order has been cancelled and a refund will be initiated.');
                }
            } else {
                Session::setFlash('error', 'Could not process your response. The modification may have already expired.');
            }
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error: ' . $e->getMessage());
        }

        $this->redirect('/dashboard/orders');
    }
}
