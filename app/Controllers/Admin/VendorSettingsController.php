<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\VendorBankAccount;
use App\Models\VendorProfile;

class VendorSettingsController extends Controller
{
    private $bankAccountModel;
    private $vendorProfileModel;

    public function __construct()
    {
        parent::__construct();
        if (!is_vendor_module_enabled()) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Vendor module is currently disabled.']);
                exit;
            }
            Session::setFlash('error', 'The Vendor module is currently disabled.');
            $this->redirect('/admin/dashboard');
            return;
        }

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
            return;
        }
    }

    /**
     * Settings index page
     */
    public function index()
    {
        $this->requirePermission('settings', 'view');
        
        $role = Session::get('user_role');
        $vendorId = (int)Session::get('user_id');

        // If admin visits, they can view vendor settings if vendor_id is passed, or default to general settings
        if ($role !== 'vendor') {
            $vendorId = isset($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : $vendorId;
        }

        $bankAccounts = $this->bankAccountModel->getByVendorId($vendorId);
        $primaryAccount = $this->bankAccountModel->getPrimaryAccount($vendorId);
        $vendorProfile = $this->vendorProfileModel->getByUserId($vendorId);

        $this->render('admin/vendor_settings/index', [
            'title'          => 'Store & Account Settings',
            'bankAccounts'   => $bankAccounts,
            'primaryAccount' => $primaryAccount,
            'vendorProfile'  => $vendorProfile,
            'activeTab'      => $_GET['tab'] ?? 'bank_accounts'
        ], 'admin');
    }

    /**
     * Save (Create or Update) Bank Account
     */
    public function saveBankAccount()
    {
        header('Content-Type: application/json');

        if (!$this->hasPermission('settings', 'edit') && !$this->hasPermission('settings', 'create')) {
            echo json_encode(['success' => false, 'message' => 'Permission denied.']);
            exit;
        }

        $vendorId = (int)Session::get('user_id');
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

        $id = !empty($input['id']) ? (int)$input['id'] : null;
        $bankName = trim($input['bank_name'] ?? '');
        $accountHolderName = trim($input['account_holder_name'] ?? '');
        $accountNumber = trim($input['account_number'] ?? '');
        $ifscCode = strtoupper(trim($input['ifsc_code'] ?? ''));
        $branchName = trim($input['branch_name'] ?? '');
        $accountType = in_array($input['account_type'] ?? '', ['current', 'savings']) ? $input['account_type'] : 'current';
        $upiId = trim($input['upi_id'] ?? '');
        $isPrimary = !empty($input['is_primary']) ? 1 : 0;

        if (empty($bankName) || empty($accountHolderName) || empty($accountNumber) || empty($ifscCode)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in Bank Name, Account Holder Name, Account Number, and IFSC Code.']);
            return;
        }

        if (!preg_match('/^[0-9]{9,18}$/', $accountNumber)) {
            echo json_encode(['success' => false, 'message' => 'Account number must be between 9 and 18 numeric digits.']);
            return;
        }

        if (!preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifscCode)) {
            echo json_encode(['success' => false, 'message' => 'Invalid IFSC Code format (e.g. HDFC0001234).']);
            return;
        }

        $data = [
            'bank_name'           => $bankName,
            'account_holder_name' => $accountHolderName,
            'account_number'      => $accountNumber,
            'ifsc_code'           => $ifscCode,
            'branch_name'         => $branchName,
            'account_type'        => $accountType,
            'upi_id'              => $upiId,
            'is_primary'          => $isPrimary
        ];

        try {
            if ($id) {
                $success = $this->bankAccountModel->update($id, $vendorId, $data);
                $msg = $success ? 'Bank account updated successfully.' : 'Failed to update bank account.';
            } else {
                $newId = $this->bankAccountModel->create($vendorId, $data);
                $success = ($newId > 0);
                $msg = $success ? 'New bank account added successfully.' : 'Failed to add bank account.';
            }

            echo json_encode(['success' => $success, 'message' => $msg]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Set a bank account as active / primary
     */
    public function setPrimaryBankAccount()
    {
        header('Content-Type: application/json');

        if (!$this->hasPermission('settings', 'edit')) {
            echo json_encode(['success' => false, 'message' => 'Permission denied.']);
            exit;
        }

        $vendorId = (int)Session::get('user_id');
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $id = (int)($input['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid bank account ID.']);
            return;
        }

        try {
            if ($this->bankAccountModel->setPrimary($id, $vendorId)) {
                echo json_encode(['success' => true, 'message' => 'Primary bank account updated successfully. Active bank will now be dynamically used across all Finance disbursals.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update primary bank account.']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete a bank account
     */
    public function deleteBankAccount()
    {
        header('Content-Type: application/json');

        if (!$this->hasPermission('settings', 'delete')) {
            echo json_encode(['success' => false, 'message' => 'Permission denied.']);
            exit;
        }

        $vendorId = (int)Session::get('user_id');
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $id = (int)($input['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid bank account ID.']);
            return;
        }

        try {
            if ($this->bankAccountModel->delete($id, $vendorId)) {
                echo json_encode(['success' => true, 'message' => 'Bank account removed successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete bank account.']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
