<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\CancellationRule;

/**
 * Admin\CancellationRuleController
 *
 * Routes:
 *   GET  /admin/cancellations/rules         → index()
 *   POST /admin/cancellations/rules/add     → add()
 *   POST /admin/cancellations/rules/update  → update()
 *   POST /admin/cancellations/rules/toggle  → toggle()
 *   POST /admin/cancellations/rules/delete  → delete()
 *
 * Security: super_admin and manager only.
 */
class CancellationRuleController extends Controller
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
    // GET /admin/cancellations/rules
    // -------------------------------------------------------------------------

    public function index(): void
    {
        if (!$this->hasPermission('cancellation_rules', 'view') && !$this->hasAnyPermission('cancellation_rules')) {
            $this->requirePermission('cancellation_rules', 'view');
        }

        $model = new CancellationRule();
        $settingModel = new \App\Models\Setting();
        $this->render('admin/cancellations/rules', [
            'title' => 'Cancellation Rules',
            'rules' => $model->getAll(),
            'cancellationsEnabled' => $settingModel->get('cancellations_enabled', '1') === '1',
        ], 'admin');
    }

    // -------------------------------------------------------------------------
    // POST /admin/cancellations/rules/add
    // -------------------------------------------------------------------------

    public function add(): void
    {
        $this->requirePermission('cancellation_rules', 'create');
        header('Content-Type: application/json');
        $input = $this->parseJsonInput();

        $ruleName = trim($input['rule_name'] ?? '');
        if (!$ruleName) {
            $this->json(['success' => false, 'message' => 'Rule Name (Order Status) is required.'], 422);
        }
        
        $paymentMethod = in_array($input['payment_method'] ?? '', ['cod', 'prepaid']) ? $input['payment_method'] : 'prepaid';

        $model = new CancellationRule();
        try {
            $id = $model->create([
                'rule_name'         => $ruleName,
                'payment_method'    => $paymentMethod,
                'cancellation_fee'  => (float)($input['cancellation_fee'] ?? 0),
                'refund_percentage' => (int)($input['refund_percentage'] ?? 100),
                'sla_days'          => (int)($input['sla_days'] ?? 0),
                'is_active'         => (int)($input['is_active'] ?? 1),
                'created_by'        => (int)Session::get('user_id'),
            ]);
            $this->json(['success' => true, 'message' => 'Rule created successfully.', 'id' => $id]);
        } catch (\PDOException $e) {
            // Check for duplicate key
            if ($e->getCode() === '23000') {
                $this->json(['success' => false, 'message' => 'A rule for this status and payment method already exists.'], 422);
            }
            $this->json(['success' => false, 'message' => 'Database error.'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // POST /admin/cancellations/rules/update
    // -------------------------------------------------------------------------

    public function update(): void
    {
        $this->requirePermission('cancellation_rules', 'edit');
        header('Content-Type: application/json');
        $input = $this->parseJsonInput();
        $id    = (int)($input['id'] ?? 0);

        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid rule ID.'], 422);
        }

        $ruleName = trim($input['rule_name'] ?? '');
        if (!$ruleName) {
            $this->json(['success' => false, 'message' => 'Rule Name (Order Status) is required.'], 422);
        }

        $paymentMethod = in_array($input['payment_method'] ?? '', ['cod', 'prepaid']) ? $input['payment_method'] : 'prepaid';

        $model = new CancellationRule();
        if (!$model->findById($id)) {
            $this->json(['success' => false, 'message' => 'Rule not found.'], 404);
        }

        try {
            $model->update($id, [
                'rule_name'         => $ruleName,
                'payment_method'    => $paymentMethod,
                'cancellation_fee'  => (float)($input['cancellation_fee'] ?? 0),
                'refund_percentage' => (int)($input['refund_percentage'] ?? 100),
                'sla_days'          => (int)($input['sla_days'] ?? 0),
                'is_active'         => (int)($input['is_active'] ?? 1),
            ]);
            $this->json(['success' => true, 'message' => 'Rule updated successfully.']);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->json(['success' => false, 'message' => 'A rule for this status and payment method already exists.'], 422);
            }
            $this->json(['success' => false, 'message' => 'Database error.'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // POST /admin/cancellations/rules/toggle
    // -------------------------------------------------------------------------

    public function toggle(): void
    {
        $this->requirePermission('cancellation_rules', 'edit');
        header('Content-Type: application/json');
        $input = $this->parseJsonInput();
        $id    = (int)($input['id'] ?? 0);

        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid rule ID.'], 422);
        }

        $model = new CancellationRule();
        $model->toggleActive($id);

        $rule = $model->findById($id);
        $state = $rule && $rule['is_active'] ? 'enabled' : 'disabled';

        $this->json(['success' => true, 'message' => "Rule {$state}.", 'is_active' => (int)$rule['is_active']]);
    }

    // -------------------------------------------------------------------------
    // POST /admin/cancellations/rules/delete
    // -------------------------------------------------------------------------

    public function delete(): void
    {
        $this->requirePermission('cancellation_rules', 'delete');
        header('Content-Type: application/json');
        $input = $this->parseJsonInput();
        $id    = (int)($input['id'] ?? 0);

        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid rule ID.'], 422);
        }

        (new CancellationRule())->delete($id);
        $this->json(['success' => true, 'message' => 'Rule deleted.']);
    }

    // -------------------------------------------------------------------------
    // POST /admin/cancellations/rules/toggle-global
    // -------------------------------------------------------------------------

    public function toggleGlobal(): void
    {
        $this->requirePermission('cancellation_rules', 'edit');
        header('Content-Type: application/json');
        $input = $this->parseJsonInput();
        $enabled = isset($input['enabled']) ? (bool)$input['enabled'] : true;

        $settingModel = new \App\Models\Setting();
        $settingModel->set('cancellations_enabled', $enabled ? '1' : '0');

        $this->json([
            'success' => true,
            'message' => 'Cancellations ' . ($enabled ? 'enabled' : 'disabled') . ' successfully.',
            'cancellations_enabled' => $enabled,
        ]);
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
