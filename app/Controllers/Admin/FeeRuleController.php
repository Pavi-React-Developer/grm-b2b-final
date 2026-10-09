<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\FeeRule;
use App\Models\FeeCategory;

class FeeRuleController extends Controller
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
        $this->requirePermission('fee_rules', 'view');
        $feeRuleModel = new FeeRule();
        $feeCatModel = new FeeCategory();

        $this->render('admin/fee_rules/index', [
            'title' => 'Fee Rules',
            'rules' => $feeRuleModel->getAll(),
            'categories' => $feeCatModel->getAllActive()
        ], 'admin');
    }

    public function create()
    {
        $this->requirePermission('fee_rules', 'create');
        $feeCatModel = new FeeCategory();
        $this->render('admin/fee_rules/create', [
            'title' => 'Create Fee Rule',
            'categories' => $feeCatModel->getAllActive()
        ], 'admin');
    }

    public function store()
    {
        $this->requirePermission('fee_rules', 'create');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/fee-rules');
        }

        try {
            $weightSlabs = $this->parseWeightSlabs($_POST);
            $states = $this->parseStates($_POST['application_states'] ?? []);

            $data = [
                'fee_name' => trim($_POST['fee_name'] ?? ''),
                'fee_category_id' => (int)($_POST['fee_category_id'] ?? 0),
                'fee_type' => $_POST['fee_type'] ?? 'Fixed Amount',
                'flat_fee_value' => (float)($_POST['flat_fee_value'] ?? 0),
                'application_states' => $states,
                'payment_method' => $_POST['payment_method'] ?? 'Both',
                'minimum_order_amount' => !empty($_POST['minimum_order_amount']) ? (float)$_POST['minimum_order_amount'] : null,
                'maximum_order_amount' => !empty($_POST['maximum_order_amount']) ? (float)$_POST['maximum_order_amount'] : null,
                'active' => isset($_POST['active']) ? 1 : 0,
                'weight_slabs' => $weightSlabs
            ];

            if (strlen($data['fee_name']) < 3) {
                throw new \Exception("Fee name must be at least 3 characters.");
            }
            if (!$data['fee_category_id']) {
                throw new \Exception("Please select a fee category.");
            }

            $model = new FeeRule();
            $model->create($data);

            Session::setFlash('success', 'Fee rule created successfully.');
            $this->redirect('/admin/fee-rules');
        } catch (\Exception $e) {
            Session::setFlash('error', $e->getMessage());
            $this->redirect('/admin/fee-rules/create');
        }
    }

    public function edit()
    {
        $this->requirePermission('fee_rules', 'edit');
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->redirect('/admin/fee-rules');

        $model = new FeeRule();
        $rule = $model->findById($id);
        if (!$rule) {
            Session::setFlash('error', 'Fee rule not found.');
            $this->redirect('/admin/fee-rules');
        }

        $feeCatModel = new FeeCategory();
        $rule['application_states'] = json_decode($rule['application_states'] ?? '[]', true) ?: [];
        $rule['weight_slabs'] = json_decode($rule['weight_slabs'] ?? '[]', true) ?: [];

        $this->render('admin/fee_rules/edit', [
            'title' => 'Edit Fee Rule',
            'rule' => $rule,
            'categories' => $feeCatModel->getAllActive()
        ], 'admin');
    }

    public function update()
    {
        $this->requirePermission('fee_rules', 'edit');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/fee-rules');
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            Session::setFlash('error', 'Invalid rule ID.');
            $this->redirect('/admin/fee-rules');
        }

        try {
            $weightSlabs = $this->parseWeightSlabs($_POST);
            $states = $this->parseStates($_POST['application_states'] ?? []);

            $data = [
                'fee_name' => trim($_POST['fee_name'] ?? ''),
                'fee_category_id' => (int)($_POST['fee_category_id'] ?? 0),
                'fee_type' => $_POST['fee_type'] ?? 'Fixed Amount',
                'flat_fee_value' => (float)($_POST['flat_fee_value'] ?? 0),
                'application_states' => $states,
                'payment_method' => $_POST['payment_method'] ?? 'Both',
                'minimum_order_amount' => !empty($_POST['minimum_order_amount']) ? (float)$_POST['minimum_order_amount'] : null,
                'maximum_order_amount' => !empty($_POST['maximum_order_amount']) ? (float)$_POST['maximum_order_amount'] : null,
                'active' => isset($_POST['active']) ? 1 : 0,
                'weight_slabs' => $weightSlabs
            ];

            if (strlen($data['fee_name']) < 3) {
                throw new \Exception("Fee name must be at least 3 characters.");
            }

            $model = new FeeRule();
            $model->update($id, $data);

            Session::setFlash('success', 'Fee rule updated successfully.');
            $this->redirect('/admin/fee-rules');
        } catch (\Exception $e) {
            Session::setFlash('error', $e->getMessage());
            $this->redirect('/admin/fee-rules/edit?id=' . $id);
        }
    }

    public function delete()
    {
        $this->requirePermission('fee_rules', 'delete');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/fee-rules');
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            Session::setFlash('error', 'Invalid rule ID.');
            $this->redirect('/admin/fee-rules');
        }

        $model = new FeeRule();
        $model->delete($id);

        Session::setFlash('success', 'Fee rule deleted.');
        $this->redirect('/admin/fee-rules');
    }

    // ---- Helper Methods ----

    private function parseWeightSlabs(array $post): array
    {
        $slabs = [];
        $minWeights  = $post['slab_min_weight'] ?? [];
        $maxWeights  = $post['slab_max_weight'] ?? [];
        $charges     = $post['slab_charge'] ?? [];
        $statuses    = $post['slab_status'] ?? [];
        $orders      = $post['slab_order'] ?? [];

        foreach ($minWeights as $key => $minWeight) {
            $slabs[] = [
                'minWeight'    => (float)$minWeight,
                'maxWeight'    => (float)($maxWeights[$key] ?? 0),
                'charge'       => (float)($charges[$key] ?? 0),
                'status'       => isset($statuses[$key]) && $statuses[$key] === '1',
                'displayOrder' => (int)($orders[$key] ?? $key)
            ];
        }

        // Sort by displayOrder
        usort($slabs, fn($a, $b) => $a['displayOrder'] <=> $b['displayOrder']);

        return $slabs;
    }

    private function parseStates($raw): array
    {
        // Handles both array input (from checkboxes) and string input (textarea/comma-list)
        if (is_array($raw)) {
            $parts = array_map('trim', $raw);
            $parts = array_filter($parts, fn($s) => $s !== '');
            return empty($parts) ? ['All'] : array_values($parts);
        }

        $raw = (string)$raw;
        if (empty(trim($raw))) return ['All'];
        $parts = preg_split('/[\n,]+/', $raw);
        $parts = array_map('trim', $parts);
        $parts = array_filter($parts, fn($s) => $s !== '');
        return array_values($parts);
    }

    public function addCategory()
    {
        $this->requirePermission('fee_rules', 'create');
        $name = trim($_POST['name'] ?? '');
        $isWeightBased = isset($_POST['is_weight_based']) && $_POST['is_weight_based'] === '1' ? 1 : 0;
        
        if (empty($name)) {
            echo json_encode(['success' => false, 'error' => 'Category name is required.']);
            return;
        }

        $db = \Core\Database::getInstance();
        $stmt = $db->prepare("INSERT INTO fee_categories (name, is_weight_based) VALUES (:name, :is_weight_based)");
        try {
            $stmt->execute(['name' => $name, 'is_weight_based' => $isWeightBased]);
            $id = $db->lastInsertId();
            echo json_encode(['success' => true, 'id' => $id, 'name' => $name, 'is_weight_based' => $isWeightBased]);
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Could not add category or it already exists.']);
        }
    }

    public function deleteCategory()
    {
        $this->requirePermission('fee_rules', 'delete');
        $id = $_POST['id'] ?? null;
        if (empty($id)) {
            echo json_encode(['success' => false, 'error' => 'Category ID is required.']);
            return;
        }

        $db = \Core\Database::getInstance();
        
        try {
            $stmt = $db->prepare("DELETE FROM fee_categories WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo json_encode(['success' => true]);
        } catch (\PDOException $e) {
            // Check if error is related to foreign key constraint
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'foreign key') !== false || strpos($e->getMessage(), 'Constraint fails') !== false) {
                echo json_encode(['success' => false, 'error' => 'Cannot delete this category because it is currently assigned to one or more fee rules.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Could not delete category. Error: ' . $e->getMessage()]);
            }
        }
    }
}
