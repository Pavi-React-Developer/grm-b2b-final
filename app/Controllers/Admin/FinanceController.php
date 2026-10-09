<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\Finance;
use App\Models\User;
use App\Models\VendorProfile;

class FinanceController extends Controller
{
    private Finance $financeModel;

    public function __construct()
    {
        parent::__construct();
        $role = Session::get('user_role');
        if (!$role) {
            Session::setFlash('error', 'Unauthorized access. Please log in.');
            $this->redirect('/login');
            return;
        }

        if ($role === 'vendor' && !is_vendor_module_enabled()) {
            Session::setFlash('error', 'Vendor operations and portal access are currently disabled by platform administration.');
            $this->redirect('/login');
            return;
        }
        $this->financeModel = new Finance();
    }

    /**
     * Payments Overview & Wallet
     */
    public function payments()
    {
        $this->requirePermission('payments', 'view');

        $role = Session::get('user_role');
        $isVendor = ($role === 'vendor');
        $userId = (int)Session::get('user_id');

        if ($isVendor) {
            $summary = $this->financeModel->getVendorFinancialSummary($userId);
            $statusFilter = $_GET['status'] ?? 'all';
            $ordersBreakdown = $this->financeModel->getVendorOrdersBreakdown($userId, $statusFilter);
            
            $vendorProfileModel = new VendorProfile();
            $vendorProfile = $vendorProfileModel->findByUserId($userId);

            $bankAccountModel = new \App\Models\VendorBankAccount();
            $savedBankAccounts = $bankAccountModel->getByVendorId($userId);
            $primaryAccount = $bankAccountModel->getPrimaryAccount($userId);

            $this->render('admin/finance/payments', [
                'title' => 'My Earnings & Payment Wallet',
                'isVendor' => true,
                'summary' => $summary,
                'orders' => $ordersBreakdown,
                'vendorProfile' => $vendorProfile,
                'savedBankAccounts' => $savedBankAccounts,
                'primaryAccount' => $primaryAccount,
                'currentStatus' => $statusFilter
            ], 'admin');
            return;
        }

        // Admin View
        $selectedVendorId = !empty($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : null;
        $platformSummary = $this->financeModel->getPlatformFinancialSummary();
        $vendorsList = $this->financeModel->getAllVendorsFinancialList();
        
        $selectedVendorSummary = null;
        $ordersBreakdown = [];
        if ($selectedVendorId) {
            $selectedVendorSummary = $this->financeModel->getVendorFinancialSummary($selectedVendorId);
            $ordersBreakdown = $this->financeModel->getVendorOrdersBreakdown($selectedVendorId, $_GET['status'] ?? 'all');
        }

        $userModel = new User();
        $activeVendors = $userModel->getActiveVendors();

        $this->render('admin/finance/payments', [
            'title' => 'Finance & Vendor Payments Overview',
            'isVendor' => false,
            'platformSummary' => $platformSummary,
            'vendorsList' => $vendorsList,
            'activeVendors' => $activeVendors,
            'selectedVendorId' => $selectedVendorId,
            'selectedVendorSummary' => $selectedVendorSummary,
            'orders' => $ordersBreakdown,
            'currentStatus' => $_GET['status'] ?? 'all'
        ], 'admin');
    }

    /**
     * Withdrawals & Payout Requests
     */
    public function withdrawals()
    {
        if (!is_vendor_module_enabled()) {
            Session::setFlash('error', 'The Vendor Withdrawal module is currently disabled.');
            $this->redirect('/admin/dashboard');
            return;
        }

        $this->requirePermission('withdrawals', 'view');

        $role = Session::get('user_role');
        $isVendor = ($role === 'vendor');
        $userId = (int)Session::get('user_id');
        $statusFilter = $_GET['status'] ?? 'all';

        if ($isVendor) {
            $summary = $this->financeModel->getVendorFinancialSummary($userId);
            $withdrawals = $this->financeModel->getWithdrawalsList($userId, $statusFilter);
            
            $vendorProfileModel = new VendorProfile();
            $vendorProfile = $vendorProfileModel->findByUserId($userId);

            $bankAccountModel = new \App\Models\VendorBankAccount();
            $savedBankAccounts = $bankAccountModel->getByVendorId($userId);
            $primaryAccount = $bankAccountModel->getPrimaryAccount($userId);

            $this->render('admin/finance/withdrawals', [
                'title' => 'Payouts & Withdrawal Requests',
                'isVendor' => true,
                'summary' => $summary,
                'withdrawals' => $withdrawals,
                'vendorProfile' => $vendorProfile,
                'savedBankAccounts' => $savedBankAccounts,
                'primaryAccount' => $primaryAccount,
                'currentStatus' => $statusFilter
            ], 'admin');
            return;
        }

        // Admin View
        $vendorFilter = !empty($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : null;
        $withdrawals = $this->financeModel->getWithdrawalsList($vendorFilter, $statusFilter);
        $platformSummary = $this->financeModel->getPlatformFinancialSummary();

        $userModel = new User();
        $activeVendors = $userModel->getActiveVendors();

        $this->render('admin/finance/withdrawals', [
            'title' => 'Vendor Withdrawal Requests',
            'isVendor' => false,
            'withdrawals' => $withdrawals,
            'platformSummary' => $platformSummary,
            'activeVendors' => $activeVendors,
            'selectedVendorId' => $vendorFilter,
            'currentStatus' => $statusFilter
        ], 'admin');
    }

    /**
     * Vendor submits a withdrawal request
     */
    public function requestWithdrawal()
    {
        $this->requirePermission('withdrawals', 'create');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/finance/withdrawals');
            return;
        }

        $userId = (int)Session::get('user_id');
        $amount = (float)($_POST['amount'] ?? 0);
        $payoutMethod = $_POST['payout_method'] ?? 'bank_transfer';
        $vendorNotes = trim($_POST['vendor_notes'] ?? '');

        // Bank details from form or profile
        $bankDetails = [
            'payout_method' => $payoutMethod,
            'bank_name' => trim($_POST['bank_name'] ?? ''),
            'account_number' => trim($_POST['account_number'] ?? ''),
            'ifsc_code' => strtoupper(trim($_POST['ifsc_code'] ?? '')),
            'account_holder_name' => trim($_POST['account_holder_name'] ?? ''),
            'upi_id' => trim($_POST['upi_id'] ?? '')
        ];

        // If empty in form, fallback to vendor profile details
        if (empty($bankDetails['account_number'])) {
            $vpModel = new VendorProfile();
            $profile = $vpModel->findByUserId($userId);
            if ($profile) {
                $bankDetails['bank_name'] = $profile['bank_name'] ?? '';
                $bankDetails['account_number'] = $profile['account_number'] ?? '';
                $bankDetails['ifsc_code'] = $profile['ifsc_code'] ?? '';
                $bankDetails['account_holder_name'] = $profile['account_holder_name'] ?? '';
            }
        }

        if ($amount <= 0) {
            Session::setFlash('error', 'Please enter a valid withdrawal amount.');
            $this->redirect('/admin/finance/withdrawals');
            return;
        }

        $res = $this->financeModel->createWithdrawalRequest($userId, $amount, $bankDetails, $vendorNotes);

        if ($res['success']) {
            Session::setFlash('success', "Withdrawal request of ₹" . number_format($amount, 2) . " submitted successfully! Request ID: " . $res['payout_number']);
        } else {
            Session::setFlash('error', $res['error'] ?? 'Failed to submit withdrawal request.');
        }

        $this->redirect('/admin/finance/withdrawals');
    }

    /**
     * Admin processes a vendor withdrawal request (Approve with UTR or Reject)
     */
    public function processWithdrawal()
    {
        $this->requirePermission('withdrawals', 'edit');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/finance/withdrawals');
            return;
        }

        $payoutId = (int)($_POST['payout_id'] ?? 0);
        $action = $_POST['action'] ?? '';
        $reference = trim($_POST['transaction_reference'] ?? '');
        $adminNotes = trim($_POST['admin_notes'] ?? '');
        $adminId = (int)Session::get('user_id');

        if (!$payoutId || !in_array($action, ['approve', 'reject'])) {
            Session::setFlash('error', 'Invalid payout processing action.');
            $this->redirect('/admin/finance/withdrawals');
            return;
        }

        $res = $this->financeModel->processWithdrawalRequest($payoutId, $action, $reference, $adminNotes, $adminId);

        if ($res['success']) {
            Session::setFlash('success', $res['message']);
        } else {
            Session::setFlash('error', $res['error'] ?? 'Failed to process payout.');
        }

        $this->redirect('/admin/finance/withdrawals');
    }

    /**
     * Commission Settings & 3-Tier Hierarchy Management (Admin Only)
     */
    public function commissions()
    {
        if (!is_vendor_module_enabled()) {
            Session::setFlash('error', 'The Vendor Commission module is currently disabled.');
            $this->redirect('/admin/dashboard');
            return;
        }

        $this->requirePermission('commissions', 'view');

        $role = Session::get('user_role');
        if ($role === 'vendor') {
            Session::setFlash('error', 'Commission settings are managed by platform administrators.');
            $this->redirect('/admin/finance/payments');
            return;
        }

        $globalRate = (float)$this->financeModel->getSetting('default_commission_rate', '10.00');
        $minWithdrawal = (float)$this->financeModel->getSetting('min_withdrawal_amount', '500.00');
        $holdingDays = (int)$this->financeModel->getSetting('vendor_revenue_holding_days', '7');
        $platformSummary = $this->financeModel->getPlatformFinancialSummary();

        $vendors = $this->financeModel->getAllVendorsWithCommission();
        $categories = $this->financeModel->getAllCategoriesWithCommission();
        $products = $this->financeModel->getProductsWithCommission();

        $this->render('admin/finance/commissions', [
            'title' => 'Commission Configuration & Fee Rules',
            'globalRate' => $globalRate,
            'minWithdrawal' => $minWithdrawal,
            'holdingDays' => $holdingDays,
            'platformSummary' => $platformSummary,
            'vendors' => $vendors,
            'categories' => $categories,
            'products' => $products
        ], 'admin');
    }

    /**
     * Save Commission Settings (Global, Category, Vendor, or Product)
     */
    public function saveCommissionSettings()
    {
        $this->requirePermission('commissions', 'edit');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/finance/commissions');
            return;
        }

        $type = $_POST['setting_type'] ?? '';

        if ($type === 'global') {
            $rate = floatval($_POST['default_commission_rate'] ?? 10.0);
            $minWithdraw = floatval($_POST['min_withdrawal_amount'] ?? 500.0);
            $holdingDays = max(0, intval($_POST['vendor_revenue_holding_days'] ?? 7));

            $this->financeModel->updateSetting('default_commission_rate', (string)$rate, 'Default Platform Commission Rate (%)');
            $this->financeModel->updateSetting('min_withdrawal_amount', (string)$minWithdraw, 'Minimum vendor withdrawal threshold (INR)');
            $this->financeModel->updateSetting('vendor_revenue_holding_days', (string)$holdingDays, 'Vendor Revenue Maturation / Holding Period (Days)');

            Session::setFlash('success', 'Global commission, withdrawal limit, and vendor holding period settings updated successfully!');

        } elseif ($type === 'vendor') {
            $vendorId = (int)($_POST['vendor_id'] ?? 0);
            $rateInput = trim($_POST['commission_rate'] ?? '');
            $rate = ($rateInput === '') ? null : floatval($rateInput);

            $this->financeModel->updateVendorCommission($vendorId, $rate);
            Session::setFlash('success', 'Vendor commission rate override updated successfully!');

        } elseif ($type === 'category') {
            $categoryId = (int)($_POST['category_id'] ?? 0);
            $rateInput = trim($_POST['commission_rate'] ?? '');
            $rate = ($rateInput === '') ? null : floatval($rateInput);

            $this->financeModel->updateCategoryCommission($categoryId, $rate);
            Session::setFlash('success', 'Category commission rate override updated successfully!');

        } elseif ($type === 'product') {
            $productId = (int)($_POST['product_id'] ?? 0);
            $rateInput = trim($_POST['commission_rate'] ?? '');
            $rate = ($rateInput === '') ? null : floatval($rateInput);

            $this->financeModel->updateProductCommission($productId, $rate);
            Session::setFlash('success', 'Product commission rate override updated successfully!');
        }

        $this->redirect('/admin/finance/commissions');
    }

    /**
     * Financial Ledger Statement & Transaction History
     */
    public function transactions()
    {
        $this->requirePermission('transactions', 'view');

        $role = Session::get('user_role');
        $isVendor = ($role === 'vendor');
        $userId = (int)Session::get('user_id');

        $vendorId = $isVendor ? $userId : (!empty($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : null);
        $transactions = $this->financeModel->getTransactionsList($vendorId);

        $vendors = [];
        if (!$isVendor) {
            $userModel = new User();
            $vendors = $userModel->getActiveVendors();
        }

        $this->render('admin/finance/transactions', [
            'title' => $isVendor ? 'Earnings & Payout Ledger' : 'Platform Financial Statement',
            'isVendor' => $isVendor,
            'transactions' => $transactions,
            'vendors' => $vendors,
            'selectedVendorId' => $vendorId
        ], 'admin');
    }
}
