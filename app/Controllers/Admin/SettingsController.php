<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use Core\Database;
use Core\Cache;
use App\Models\Backup;
use App\Models\VendorBankAccount;
use App\Models\VendorProfile;

class SettingsController extends Controller
{
    private $backupModel;
    private $bankAccountModel;
    private $vendorProfileModel;

    public function __construct()
    {
        parent::__construct();
        $this->backupModel = new Backup();
        $this->bankAccountModel = new VendorBankAccount();
        $this->vendorProfileModel = new VendorProfile();

        $role = Session::get('user_role');
        if (!$role) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }
            Session::setFlash('error', 'Unauthorized access.');
            $this->redirect('/login');
        }
    }

    /**
     * Unified Settings page (General/Feature Flags, Data Backup & Restore, Vendor Bank Accounts)
     */
    public function index()
    {
        $role = Session::get('user_role');
        $userId = (int)Session::get('user_id');

        // If vendor visits, redirect to vendor-specific settings or handle bank accounts
        if ($role === 'vendor') {
            $bankAccounts = $this->bankAccountModel->getByVendorId($userId);
            $primaryAccount = $this->bankAccountModel->getPrimaryAccount($userId);
            $vendorProfile = $this->vendorProfileModel->getByUserId($userId);

            $this->render('admin/vendor_settings/index', [
                'title'          => 'Store & Account Settings',
                'bankAccounts'   => $bankAccounts,
                'primaryAccount' => $primaryAccount,
                'vendorProfile'  => $vendorProfile,
                'activeTab'      => $_GET['tab'] ?? 'bank_accounts'
            ], 'admin');
            return;
        }

        $this->requirePermission('settings', 'view');

        // Fetch vendor module toggle state
        $vendorEnabled = is_vendor_module_enabled();

        // Fetch database table statistics for backup tab
        $tableStats = $this->backupModel->getTableStats();

        // Total stats calculation
        $totalTables = count($tableStats);
        $totalRows = array_sum(array_column($tableStats, 'rows'));
        $totalSizeMb = array_sum(array_column($tableStats, 'size_mb'));

        // Also fetch bank accounts if admin is inspecting vendor or platform accounts
        $vendorId = isset($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : $userId;
        $bankAccounts = $this->bankAccountModel->getByVendorId($vendorId);
        $primaryAccount = $this->bankAccountModel->getPrimaryAccount($vendorId);

        $activeTab = $_GET['tab'] ?? 'general';

        $this->render('admin/settings/index', [
            'title'          => 'System & Platform Settings',
            'vendorEnabled'  => $vendorEnabled,
            'tableStats'     => $tableStats,
            'totalTables'    => $totalTables,
            'totalRows'      => $totalRows,
            'totalSizeMb'    => round($totalSizeMb, 2),
            'bankAccounts'   => $bankAccounts,
            'primaryAccount' => $primaryAccount,
            'activeTab'      => $activeTab,
            'isSuperAdmin'   => ($role === 'super_admin')
        ], 'admin');
    }

    /**
     * Dynamically toggle Vendor Module ON/OFF
     */
    public function toggleVendorModule()
    {
        $role = Session::get('user_role');
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
                  || (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false);

        if ($role !== 'super_admin' && !$this->hasPermission('settings', 'edit')) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Permission denied. Super Admin privileges required.'], 403);
            }
            Session::setFlash('error', 'Permission denied.');
            $this->redirect('/admin/settings');
            return;
        }

        try {
            $db = Database::getInstance();
            $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

            // Determine target state
            if (isset($input['status'])) {
                $newState = $input['status'] ? '1' : '0';
            } else {
                $current = is_vendor_module_enabled();
                $newState = $current ? '0' : '1';
            }

            // Update in settings table
            $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, created_at, updated_at) 
                                  VALUES ('vendor_module_enabled', ?, NOW(), NOW()) 
                                  ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()");
            $stmt->execute([$newState]);

            // Flush cache
            Cache::delete('setting_vendor_module_enabled');

            $statusText = ($newState === '1') ? 'ENABLED' : 'DISABLED';
            $msg = "Vendor Module has been successfully {$statusText}. " . 
                   (($newState === '1') 
                        ? "Vendor registration, portal login, and storefront vendor tabs are now live." 
                        : "Vendor registration, portal login, and storefront vendor tabs have been deactivated across the platform.");

            if ($isAjax) {
                $this->json([
                    'success' => true,
                    'enabled' => ($newState === '1'),
                    'message' => $msg
                ]);
            }

            Session::setFlash('success', $msg);
            $this->redirect('/admin/settings?tab=general');

        } catch (\Exception $e) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Error toggling vendor module: ' . $e->getMessage()], 500);
            }
            Session::setFlash('error', 'Error: ' . $e->getMessage());
            $this->redirect('/admin/settings?tab=general');
        }
    }

    /**
     * Single-click Database Backup Export (JSON dump download)
     */
    public function exportBackup()
    {
        $role = Session::get('user_role');
        if ($role !== 'super_admin' && !$this->hasPermission('settings', 'view')) {
            Session::setFlash('error', 'Permission denied.');
            $this->redirect('/admin/settings');
            return;
        }

        try {
            $backupData = $this->backupModel->exportDatabase();
            $jsonString = json_encode($backupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $filename = 'grm_b2b_backup_' . date('Y-m-d_His') . '.json';

            // Clean any buffer
            while (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($jsonString));
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');

            echo $jsonString;
            exit;

        } catch (\Exception $e) {
            Session::setFlash('error', 'Backup export failed: ' . $e->getMessage());
            $this->redirect('/admin/settings?tab=backup');
        }
    }

    /**
     * Single-file Database Backup Restore (Transactional Import)
     */
    public function importBackup()
    {
        $role = Session::get('user_role');
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
                  || (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false);

        if ($role !== 'super_admin' && !$this->hasPermission('settings', 'edit')) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Permission denied. Super Admin privileges required.'], 403);
            }
            Session::setFlash('error', 'Permission denied.');
            $this->redirect('/admin/settings?tab=backup');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings?tab=backup');
            return;
        }

        // Validate uploaded file
        if (empty($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
            $errCode = $_FILES['backup_file']['error'] ?? 'No file uploaded';
            $errMsg = 'Please select a valid backup (.json) file. (Error code: ' . $errCode . ')';
            if ($isAjax) {
                $this->json(['success' => false, 'message' => $errMsg], 400);
            }
            Session::setFlash('error', $errMsg);
            $this->redirect('/admin/settings?tab=backup');
            return;
        }

        $fileTmp = $_FILES['backup_file']['tmp_name'];
        $fileName = $_FILES['backup_file']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($ext !== 'json') {
            $errMsg = 'Invalid file format. Only JSON backup files (.json) generated by GRM B2B are supported.';
            if ($isAjax) {
                $this->json(['success' => false, 'message' => $errMsg], 400);
            }
            Session::setFlash('error', $errMsg);
            $this->redirect('/admin/settings?tab=backup');
            return;
        }

        // Read file contents
        $content = file_get_contents($fileTmp);
        if (empty($content)) {
            $errMsg = 'The uploaded backup file is empty.';
            if ($isAjax) {
                $this->json(['success' => false, 'message' => $errMsg], 400);
            }
            Session::setFlash('error', $errMsg);
            $this->redirect('/admin/settings?tab=backup');
            return;
        }

        try {
            $result = $this->backupModel->importDatabase($content);

            if ($result['success']) {
                $successMsg = "Database successfully restored from backup! {$result['restored_tables_count']} tables restored, {$result['total_rows_restored']} total records updated.";
                if ($isAjax) {
                    $this->json([
                        'success' => true,
                        'message' => $successMsg,
                        'stats'   => $result
                    ]);
                }
                Session::setFlash('success', $successMsg);
            } else {
                $errMsg = $result['message'] ?? 'Database restore encountered an error.';
                if ($isAjax) {
                    $this->json(['success' => false, 'message' => $errMsg], 400);
                }
                Session::setFlash('error', $errMsg);
            }
        } catch (\Exception $e) {
            $errMsg = 'Import exception: ' . $e->getMessage();
            if ($isAjax) {
                $this->json(['success' => false, 'message' => $errMsg], 500);
            }
            Session::setFlash('error', $errMsg);
        }

        $this->redirect('/admin/settings?tab=backup');
    }
}
