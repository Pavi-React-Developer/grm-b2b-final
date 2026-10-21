<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\CustomizationLabel;

class CustomizationLabelController extends Controller
{
    private $labelModel;

    public function __construct()
    {
        parent::__construct();

        // Require admin login
        if (!Session::get('user_id') || !in_array(Session::get('user_role'), ['super_admin', 'manager', 'staff'])) {
            Session::setFlash('error', 'Unauthorized access. Please login as admin.');
            $this->redirect('/login');
            exit;
        }

        $this->labelModel = new CustomizationLabel();
    }

    /**
     * List all customization labels / templates
     */
    public function index()
    {
        $this->requirePermission('customization_labels', 'view');
        $filters = [
            'status'     => $_GET['status'] ?? '',
            'group_name' => $_GET['group_name'] ?? '',
            'search'     => $_GET['search'] ?? ''
        ];

        $labels = $this->labelModel->getAll($filters);
        $groups = $this->labelModel->getDistinctGroups();

        // Calculate statistics
        $totalLabels = count($labels);
        $activeLabels = count(array_filter($labels, fn($l) => $l['status'] === 'active'));

        $this->render('admin/customization_labels/index', [
            'title'        => 'Customization Labels & Templates',
            'labels'       => $labels,
            'groups'       => $groups,
            'filters'      => $filters,
            'totalLabels'  => $totalLabels,
            'activeLabels' => $activeLabels
        ], 'admin');
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->requirePermission('customization_labels', 'create');
        $groups = $this->labelModel->getDistinctGroups();

        $this->render('admin/customization_labels/create', [
            'title'  => 'Create Customization Label',
            'groups' => $groups
        ], 'admin');
    }

    /**
     * Store new label
     */
    public function store()
    {
        $this->requirePermission('customization_labels', 'create');
        $groupName = trim($_POST['group_name'] ?? '');
        $subGroup = trim($_POST['sub_group'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $displayLabel = trim($_POST['display_label'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        if (empty($name)) {
            Session::setFlash('error', 'Label / Style Name is required.');
            $this->redirect('/admin/customization-labels/create');
            return;
        }

        try {
            $this->labelModel->create([
                'group_name'    => $groupName,
                'sub_group'     => $subGroup,
                'name'          => $name,
                'display_label' => $displayLabel,
                'description'   => $description,
                'sort_order'    => $sortOrder,
                'status'        => $status
            ]);

            Session::setFlash('success', 'Customization label template created successfully.');
            $this->redirect('/admin/customization-labels');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error creating label: ' . $e->getMessage());
            $this->redirect('/admin/customization-labels/create');
        }
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $this->requirePermission('customization_labels', 'edit');
        $id = (int)($_GET['id'] ?? 0);
        $label = $this->labelModel->getById($id);

        if (!$label) {
            Session::setFlash('error', 'Customization label not found.');
            $this->redirect('/admin/customization-labels');
            return;
        }

        $groups = $this->labelModel->getDistinctGroups();

        $this->render('admin/customization_labels/edit', [
            'title'  => 'Edit Customization Label',
            'label'  => $label,
            'groups' => $groups
        ], 'admin');
    }

    /**
     * Update label
     */
    public function update()
    {
        $this->requirePermission('customization_labels', 'edit');
        $id = (int)($_POST['id'] ?? 0);
        $groupName = trim($_POST['group_name'] ?? '');
        $subGroup = trim($_POST['sub_group'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $displayLabel = trim($_POST['display_label'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        if (!$id || empty($name)) {
            Session::setFlash('error', 'Invalid label ID or missing name.');
            $this->redirect('/admin/customization-labels/edit?id=' . $id);
            return;
        }

        try {
            $this->labelModel->update($id, [
                'group_name'    => $groupName,
                'sub_group'     => $subGroup,
                'name'          => $name,
                'display_label' => $displayLabel,
                'description'   => $description,
                'sort_order'    => $sortOrder,
                'status'        => $status
            ]);

            Session::setFlash('success', 'Customization label updated successfully.');
            $this->redirect('/admin/customization-labels');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error updating label: ' . $e->getMessage());
            $this->redirect('/admin/customization-labels/edit?id=' . $id);
        }
    }

    /**
     * Delete label
     */
    public function delete()
    {
        $this->requirePermission('customization_labels', 'delete');
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            try {
                $this->labelModel->delete($id);
                Session::setFlash('success', 'Customization label deleted successfully.');
            } catch (\Exception $e) {
                Session::setFlash('error', 'Error deleting label: ' . $e->getMessage());
            }
        }
        $this->redirect('/admin/customization-labels');
    }

    /**
     * Toggle active/inactive status (AJAX/POST)
     */
    public function toggleStatus()
    {
        $this->requirePermission('customization_labels', 'edit');
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $this->labelModel->toggleStatus($id);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo json_encode(['success' => true]);
                exit;
            }
            Session::setFlash('success', 'Status toggled successfully.');
        }
        $this->redirect('/admin/customization-labels');
    }

    /**
     * Quick Add AJAX (for adding a label directly from fabric create/edit page)
     */
    public function quickAddAjax()
    {
        header('Content-Type: application/json');

        if (!$this->hasPermission('customization_labels', 'create')) {
            echo json_encode(['success' => false, 'message' => 'Permission denied.']);
            exit;
        }

        $groupName = trim($_POST['group_name'] ?? '');
        $subGroup = trim($_POST['sub_group'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Style name is required.']);
            exit;
        }

        try {
            $parts = array_filter([$groupName, $subGroup, $name]);
            $displayLabel = implode(' -> ', $parts);

            $id = $this->labelModel->create([
                'group_name'    => $groupName,
                'sub_group'     => $subGroup,
                'name'          => $name,
                'display_label' => $displayLabel,
                'description'   => $description,
                'sort_order'    => 0,
                'status'        => 'active'
            ]);

            $label = $this->labelModel->getById($id);

            echo json_encode([
                'success' => true,
                'label'   => $label,
                'message' => 'Label created successfully!'
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
