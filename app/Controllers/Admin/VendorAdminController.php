<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\VendorType;
use App\Services\EmailService;

class VendorAdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $role = Session::get('user_role');
        if (!$role || !in_array($role, ['super_admin', 'manager', 'staff'])) {
            Session::setFlash('error', 'Unauthorized access.');
            $this->redirect('/login');
            return;
        }

        if (!is_vendor_module_enabled()) {
            Session::setFlash('error', 'The Vendor module is currently disabled. Please enable it in Settings to access this section.');
            $this->redirect('/admin/dashboard');
            return;
        }
    }

    public function index()
    {
        $this->requirePermission('all_vendors', 'view');
        $userModel = new User();
        $vendorModel = new VendorProfile();
        $vendors = $userModel->getActiveVendors();
        $stats = $userModel->getVendorStats();
        $metrics = $vendorModel->getGlobalVendorMetrics('all');
        $vendorSummaries = $vendorModel->getAllVendorsRevenueSummary('all');

        $this->render('admin/vendors/index', [
            'title' => 'Active Vendors & Sellers',
            'vendors' => $vendors,
            'stats' => $stats,
            'metrics' => $metrics,
            'vendorSummaries' => $vendorSummaries
        ], 'admin');
    }

    public function dashboard()
    {
        $this->requirePermission('vendor_analytics', 'view');
        $range = $_GET['range'] ?? 'all';
        $vendorModel = new VendorProfile();

        $metrics = $vendorModel->getGlobalVendorMetrics($range);
        $vendorSummaries = $vendorModel->getAllVendorsRevenueSummary($range);
        $topVendors = $vendorModel->getTopRevenueVendors(5, $range);
        $revenueTrend = $vendorModel->getMonthlyVendorRevenueTrend(6);

        $this->render('admin/vendors/dashboard', [
            'title' => 'Vendor Revenues & Analytics Dashboard',
            'range' => $range,
            'metrics' => $metrics,
            'vendorSummaries' => $vendorSummaries,
            'topVendors' => json_encode($topVendors),
            'revenueTrend' => json_encode($revenueTrend)
        ], 'admin');
    }

    public function exportRevenue()
    {
        $this->requirePermission('vendor_analytics', 'view');
        $range = $_GET['range'] ?? 'all';
        $vendorModel = new VendorProfile();
        $vendorSummaries = $vendorModel->getAllVendorsRevenueSummary($range);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=vendor_revenue_report_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'Vendor ID',
            'Store Name',
            'Company Name',
            'Owner Name',
            'Email',
            'Phone',
            'City',
            'State',
            'GST Number',
            'Status',
            'Total Products',
            'Total Orders',
            'Units Sold',
            'Pending Orders',
            'Delivered Orders',
            'Gross Revenue (INR)'
        ]);

        foreach ($vendorSummaries as $v) {
            fputcsv($output, [
                $v['unique_vendor_id'] ?? ('VN' . str_pad($v['user_id'], 5, '0', STR_PAD_LEFT)),
                $v['store_name'],
                $v['company_name'] ?? '',
                $v['owner_name'],
                $v['email'],
                $v['phone'],
                $v['city'] ?? '',
                $v['state'] ?? '',
                $v['gst_number'] ?? 'N/A',
                ucfirst($v['user_status']),
                $v['total_products'],
                $v['total_paid_orders'],
                $v['total_units_sold'],
                $v['pending_fulfillment_orders'],
                $v['delivered_orders'],
                number_format((float)$v['gross_revenue'], 2, '.', '')
            ]);
        }

        fclose($output);
        exit;
    }

    public function pending()
    {
        $this->requirePermission('pending_vendors', 'view');
        $userModel = new User();
        $vendors = $userModel->getPendingVendors();
        $stats = $userModel->getVendorStats();

        $this->render('admin/vendors/pending', [
            'title' => 'Pending Vendor Approvals',
            'vendors' => $vendors,
            'stats' => $stats
        ], 'admin');
    }

    public function view()
    {
        $this->requirePermission('all_vendors', 'view');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            Session::setFlash('error', 'Vendor ID required.');
            $this->redirect('/admin/vendors');
            return;
        }

        $userModel = new User();
        $vendor = $userModel->getVendorDetails($id);

        if (!$vendor) {
            Session::setFlash('error', 'Vendor not found.');
            $this->redirect('/admin/vendors');
            return;
        }

        $orderModel = new \App\Models\Order();
        $vendorSalesStats = $orderModel->getVendorSalesStats($id);
        $vendorTopProducts = $orderModel->getVendorTopSellingProducts($id, 5);
        $vendorRecentOrders = array_slice($orderModel->getVendorOrders($id, 'all'), 0, 5);

        $this->render('admin/vendors/view', [
            'title' => 'Vendor Profile - ' . ($vendor['store_name'] ?? $vendor['name']),
            'vendor' => $vendor,
            'salesStats' => $vendorSalesStats,
            'topProducts' => $vendorTopProducts,
            'recentOrders' => $vendorRecentOrders
        ], 'admin');
    }

    public function approve()
    {
        $this->requirePermission('pending_vendors', 'edit');
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if (!$id) {
            Session::setFlash('error', 'Invalid vendor ID.');
            $this->redirect('/admin/vendors/pending');
            return;
        }

        $userModel = new User();
        $vendorModel = new VendorProfile();

        $vendor = $userModel->getVendorDetails($id);
        if (!$vendor) {
            Session::setFlash('error', 'Vendor profile not found.');
            $this->redirect('/admin/vendors/pending');
            return;
        }

        // Generate Unique Vendor ID if not set
        $vendorId = $vendor['unique_vendor_id'];
        if (empty($vendorId)) {
            $vendorId = $vendorModel->generateUniqueVendorId();
            $vendorModel->setUniqueVendorId($id, $vendorId);
        }

        // Update User status to 'active'
        $userModel->updateStatus($id, 'active');

        // Update request status to 'approved'
        $adminId = Session::get('user_id');
        $vendorModel->updateRequestStatus($id, 'approved', null, $adminId);

        // Send Approval Email
        try {
            $emailService = new EmailService();
            $emailService->sendEmail(
                $vendor['email'],
                'Vendor Account Approved - GRM B2B',
                "<h2>Congratulations " . htmlspecialchars($vendor['name']) . "!</h2>
                 <p>Your vendor account for <strong>" . htmlspecialchars($vendor['store_name']) . "</strong> has been approved by our administration team.</p>
                 <p><strong>Your Unique Vendor ID:</strong> " . htmlspecialchars($vendorId) . "</p>
                 <p>You can now log into the portal to manage your vendor account.</p>
                 <br>
                 <p>Best regards,<br>GRM B2B Team</p>"
            );
        } catch (\Exception $e) {
            // Email error handled silently
        }

        Session::setFlash('success', 'Vendor "' . htmlspecialchars($vendor['store_name']) . '" approved successfully! Vendor ID: ' . $vendorId);
        $this->redirect('/admin/vendors');
    }

    public function reject()
    {
        $this->requirePermission('pending_vendors', 'edit');
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $reason = trim($_POST['rejection_reason'] ?? '');

        if (!$id) {
            Session::setFlash('error', 'Invalid vendor ID.');
            $this->redirect('/admin/vendors/pending');
            return;
        }

        $userModel = new User();
        $vendorModel = new VendorProfile();

        $vendor = $userModel->getVendorDetails($id);
        if (!$vendor) {
            Session::setFlash('error', 'Vendor profile not found.');
            $this->redirect('/admin/vendors/pending');
            return;
        }

        // Update status to 'rejected'
        $userModel->updateStatus($id, 'rejected');

        $adminId = Session::get('user_id');
        $vendorModel->updateRequestStatus($id, 'rejected', $reason, $adminId);

        // Send Rejection Email
        try {
            $emailService = new EmailService();
            $emailService->sendEmail(
                $vendor['email'],
                'Vendor Application Update - GRM B2B',
                "<h2>Hello " . htmlspecialchars($vendor['name']) . ",</h2>
                 <p>We regret to inform you that your vendor application for <strong>" . htmlspecialchars($vendor['store_name']) . "</strong> has been rejected.</p>
                 " . ($reason ? "<p><strong>Reason:</strong> " . htmlspecialchars($reason) . "</p>" : "") . "
                 <p>If you believe this was an error, please contact our support team.</p>
                 <br>
                 <p>Best regards,<br>GRM B2B Team</p>"
            );
        } catch (\Exception $e) {
            // Email error handled silently
        }

        Session::setFlash('success', 'Vendor application rejected.');
        $this->redirect('/admin/vendors/pending');
    }

    /**
     * List all dynamic Vendor Types
     */
    public function types()
    {
        $this->requirePermission('all_vendors', 'view');
        $vendorTypeModel = new VendorType();
        $types = $vendorTypeModel->getAll();

        $totalTypes = count($types);
        $activeTypes = count(array_filter($types, fn($t) => (int)$t['is_active'] === 1));
        $hiddenTypes = $totalTypes - $activeTypes;
        $totalVendors = array_sum(array_column($types, 'vendor_count'));

        $this->render('admin/vendors/types', [
            'title' => 'Vendor Types Configuration',
            'types' => $types,
            'totalTypes' => $totalTypes,
            'activeTypes' => $activeTypes,
            'hiddenTypes' => $hiddenTypes,
            'totalVendors' => $totalVendors
        ], 'admin');
    }

    /**
     * Create a new Vendor Type
     */
    public function createType()
    {
        $this->requirePermission('all_vendors', 'edit');
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        if (empty($name)) {
            Session::setFlash('error', 'Vendor type name is required.');
            $this->redirect('/admin/vendors/types');
            return;
        }

        $vendorTypeModel = new VendorType();
        $generatedSlug = !empty($slug) 
            ? strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $slug)) 
            : strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $name));

        if ($vendorTypeModel->findBySlug($generatedSlug)) {
            Session::setFlash('error', 'A vendor type with this slug or name already exists.');
            $this->redirect('/admin/vendors/types');
            return;
        }

        $vendorTypeModel->create([
            'name' => $name,
            'slug' => $generatedSlug,
            'description' => $description,
            'is_active' => $isActive,
            'display_order' => $displayOrder
        ]);

        Session::setFlash('success', 'Vendor type added successfully.');
        $this->redirect('/admin/vendors/types');
    }

    /**
     * Update an existing Vendor Type
     */
    public function updateType()
    {
        $this->requirePermission('all_vendors', 'edit');
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        if (!$id || empty($name)) {
            Session::setFlash('error', 'Invalid vendor type ID or missing name.');
            $this->redirect('/admin/vendors/types');
            return;
        }

        $vendorTypeModel = new VendorType();
        $existing = $vendorTypeModel->findById($id);
        if (!$existing) {
            Session::setFlash('error', 'Vendor type not found.');
            $this->redirect('/admin/vendors/types');
            return;
        }

        $generatedSlug = !empty($slug) 
            ? strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $slug)) 
            : strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $name));
        $slugCheck = $vendorTypeModel->findBySlug($generatedSlug);
        if ($slugCheck && (int)$slugCheck['id'] !== $id) {
            Session::setFlash('error', 'Another vendor type already uses this slug.');
            $this->redirect('/admin/vendors/types');
            return;
        }

        $vendorTypeModel->update($id, [
            'name' => $name,
            'slug' => $generatedSlug,
            'description' => $description,
            'is_active' => $isActive,
            'display_order' => $displayOrder
        ]);

        Session::setFlash('success', 'Vendor type updated successfully.');
        $this->redirect('/admin/vendors/types');
    }

    /**
     * Toggle active/visibility status
     */
    public function toggleTypeStatus()
    {
        $this->requirePermission('all_vendors', 'edit');
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }
            $this->redirect('/admin/vendors/types');
            return;
        }

        $vendorTypeModel = new VendorType();
        $vendorTypeModel->toggleStatus($id);
        $type = $vendorTypeModel->findById($id);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'is_active' => $type['is_active']]);
            exit;
        }

        Session::setFlash('success', 'Vendor type visibility updated.');
        $this->redirect('/admin/vendors/types');
    }

    /**
     * Delete a Vendor Type
     */
    public function deleteType()
    {
        $this->requirePermission('all_vendors', 'edit');
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            Session::setFlash('error', 'Invalid vendor type ID.');
            $this->redirect('/admin/vendors/types');
            return;
        }

        $vendorTypeModel = new VendorType();
        $type = $vendorTypeModel->findById($id);
        if (!$type) {
            Session::setFlash('error', 'Vendor type not found.');
            $this->redirect('/admin/vendors/types');
            return;
        }

        // Check if any vendors exist with this type
        $typesWithCount = $vendorTypeModel->getAll();
        foreach ($typesWithCount as $t) {
            if ((int)$t['id'] === $id && (int)$t['vendor_count'] > 0) {
                Session::setFlash('error', "Cannot delete '{$type['name']}' because {$t['vendor_count']} vendor(s) are associated with it. Disable/hide its visibility instead.");
                $this->redirect('/admin/vendors/types');
                return;
            }
        }

        $vendorTypeModel->delete($id);
        Session::setFlash('success', 'Vendor type removed successfully.');
        $this->redirect('/admin/vendors/types');
    }
}
