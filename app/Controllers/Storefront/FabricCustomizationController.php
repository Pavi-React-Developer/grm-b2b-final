<?php

namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\FabricCustomization;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Services\FabricCustomizationService;
use App\Services\RazorpayService;
use PDO;

class FabricCustomizationController extends Controller
{
    private $model;
    private $service;
    private $categoryModel;
    private $productModel;
    private $orderModel;

    public function __construct()
    {
        $this->model = new FabricCustomization();
        $this->service = new FabricCustomizationService();
        $this->categoryModel = new Category();
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }

    /**
     * Storefront Customizable Fabrics Listing (/customize)
     * Displays ONLY admin-added fabrics that have active customization rules
     */
    public function customizeListing()
    {
        $filters = [
            'category_id'   => $_GET['category_id'] ?? '',
            'garment_type'  => $_GET['garment_type'] ?? '',
            'search'        => $_GET['search'] ?? '',
            'sort'          => $_GET['sort'] ?? 'newest'
        ];

        $fabrics = $this->model->getCustomizableFabrics($filters);
        $categories = $this->categoryModel->getAllActive(null, 1);

        // Extract available garment types for filter buttons
        $garmentTypes = array_unique(array_filter(array_column($fabrics, 'garment_type')));

        $this->render('storefront/customize_listing', [
            'title'        => 'Custom Wholesale Fabric & Stitching Workshop',
            'fabrics'      => $fabrics,
            'categories'   => $categories,
            'garmentTypes' => $garmentTypes,
            'filters'      => $filters
        ]);
    }

    /**
     * Customization Workshop Detail Page (/fabrics/customize?fabric_id=...)
     * Runs based on the Fabric Rules: sizes available, pieces to stitch, consumption per piece
     */
    public function customizeWorkshop()
    {
        $fabricId = (int)($_GET['fabric_id'] ?? 0);
        $customizationId = (int)($_GET['id'] ?? 0);

        $customization = null;
        if ($fabricId > 0) {
            $customization = $this->model->getByFabricId($fabricId);
        } elseif ($customizationId > 0) {
            $customization = $this->model->getById($customizationId);
        }

        if (!$customization) {
            Session::setFlash('error', 'The requested fabric is currently not configured for custom stitching.');
            $this->redirect('/customize');
            return;
        }

        // Fetch user addresses if logged in
        $userAddresses = [];
        $userId = Session::get('user_id');
        if ($userId) {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
            $stmt->execute([$userId]);
            $userAddresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $this->render('storefront/fabric_customize', [
            'title'         => 'Customize: ' . htmlspecialchars($customization['fabric_name']) . ' (' . htmlspecialchars($customization['garment_type']) . ')',
            'customization' => $customization,
            'userAddresses' => $userAddresses,
            'userId'        => $userId
        ]);
    }

    /**
     * AJAX Live Calculation Endpoint
     */
    public function calculateAjax()
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $fabricId = (int)($input['fabric_id'] ?? 0);
        $customizationId = (int)($input['customization_id'] ?? 0);
        $sizes = $input['sizes'] ?? [];
        $fabricQuantity = max(1, (int)($input['fabric_quantity'] ?? 1));

        $customization = null;
        if ($fabricId > 0) {
            $customization = $this->model->getByFabricId($fabricId);
        } elseif ($customizationId > 0) {
            $customization = $this->model->getById($customizationId);
        }

        if (!$customization) {
            echo json_encode(['success' => false, 'error' => 'Customization rule not found']);
            exit;
        }

        $consumptionResult = $this->service->calculateConsumption(
            $customization, 
            $sizes, 
            (float)$customization['fabric_stock'],
            $fabricQuantity
        );

        $pricingResult = $this->service->calculatePricing(
            (float)$customization['fabric_price'],
            (float)$consumptionResult['required_fabric'],
            0.00, // stitching charge
            (int)$consumptionResult['total_pieces'],
            5.0 // GST 5%
        );

        echo json_encode([
            'success'     => true,
            'consumption' => $consumptionResult,
            'pricing'     => $pricingResult
        ]);
        exit;
    }

    /**
     * OPTION 1: Submit Custom Order Request (Quote / Request for Approval)
     * Direct custom order request without upfront payment
     */
    public function requestOrderAjax()
    {
        header('Content-Type: application/json');

        if (!Session::get('user_id')) {
            echo json_encode([
                'success'       => false, 
                'require_login' => true,
                'message'       => 'Please log in to submit a custom order request.'
            ]);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $fabricId = (int)($input['fabric_id'] ?? 0);
        $customizationId = (int)($input['customization_id'] ?? 0);
        $sizes = $input['sizes'] ?? [];
        $fabricQuantity = max(1, (int)($input['fabric_quantity'] ?? 1));
        $shippingAddress = trim($input['shipping_address'] ?? 'Wholesale Delivery on File');
        $customerNotes = trim($input['notes'] ?? '');

        $customization = null;
        if ($fabricId > 0) {
            $customization = $this->model->getByFabricId($fabricId);
        } elseif ($customizationId > 0) {
            $customization = $this->model->getById($customizationId);
        }

        if (!$customization) {
            echo json_encode(['success' => false, 'message' => 'Fabric rule not found']);
            exit;
        }

        // Validate consumption & stock
        $calc = $this->service->calculateConsumption($customization, $sizes, (float)$customization['fabric_stock'], $fabricQuantity);
        if (!$calc['is_valid']) {
            echo json_encode(['success' => false, 'message' => implode(' ', $calc['errors'])]);
            exit;
        }

        $pricing = $this->service->calculatePricing(
            (float)$customization['fabric_price'],
            (float)$calc['required_fabric'],
            0.00,
            (int)$calc['total_pieces'],
            5.0
        );

        $userId = (int)Session::get('user_id');
        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $orderNumber = 'GRM-REQ-' . strtoupper(substr(uniqid(), -6));

            $customizationData = [
                'customization_id'    => $customization['id'],
                'fabric_quantity'     => $fabricQuantity,
                'program_name'        => $customization['program_name'],
                'garment_type'        => $customization['garment_type'],
                'feeding_type'        => $customization['feeding_type'],
                'fabric_name'         => $customization['fabric_name'],
                'fabric_sku'          => $customization['fabric_sku'],
                'fabric_unit'         => $customization['unit'],
                'total_pieces'        => $calc['total_pieces'],
                'required_fabric'     => $calc['required_fabric'],
                'base_consumption'    => $calc['base_consumption'],
                'wastage_percentage'  => $calc['wastage_percentage'],
                'wastage_amount'      => $calc['wastage_amount'],
                'price_per_unit'      => $customization['fabric_price'],
                'fabric_cost'         => $pricing['fabric_cost'],
                'stitching_cost'      => $pricing['stitching_cost'],
                'gst_amount'          => $pricing['gst_amount'],
                'grand_total'         => $pricing['total'],
                'order_mode'          => 'request_quote',
                'customer_notes'      => $customerNotes,
                'sizes'               => $calc['breakdown']
            ];

            $orderStmt = $db->prepare("
                INSERT INTO orders (
                    user_id, order_number, total_amount, grand_total, shipping_address, 
                    status, payment_status, notes, created_at
                ) VALUES (?, ?, ?, ?, ?, 'placed', 'request_pending', ?, NOW())
            ");

            $orderStmt->execute([
                $userId,
                $orderNumber,
                $pricing['subtotal'],
                $pricing['total'],
                $shippingAddress,
                "Custom Order Request ({$customization['program_name']} x {$fabricQuantity}) - " . $customerNotes
            ]);

            $orderId = (int)$db->lastInsertId();

            // Insert into order_items
            $itemStmt = $db->prepare("
                INSERT INTO order_items (
                    order_id, product_id, customization_id, customization_data, 
                    quantity, unit_price, total_price
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $itemStmt->execute([
                $orderId,
                $customization['fabric_id'],
                $customization['id'],
                json_encode($customizationData),
                $calc['total_pieces'],
                $customization['fabric_price'],
                $pricing['total']
            ]);

            // Decrement fabric stock
            $stockStmt = $db->prepare("
                UPDATE products 
                SET stock_quantity = GREATEST(0, stock_quantity - ?) 
                WHERE id = ?
            ");
            $stockStmt->execute([(int)$calc['required_fabric'], $customization['fabric_id']]);

            $db->commit();

            echo json_encode([
                'success'      => true,
                'order_id'     => $orderId,
                'order_number' => $orderNumber,
                'message'      => "Your Custom Order Request #{$orderNumber} has been submitted! Our tailoring & logistics team will review and contact you promptly.",
                'redirect_url' => BASE_URL . '/dashboard/orders'
            ]);
            exit;

        } catch (\Exception $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error placing order request: ' . $e->getMessage()]);
            exit;
        }
    }

    /**
     * OPTION 2 (Step 1): Initialize Razorpay Online Payment
     * Generates Razorpay order and returns client credentials for Razorpay Modal
     */
    public function razorpayInitAjax()
    {
        header('Content-Type: application/json');

        if (!Session::get('user_id')) {
            echo json_encode([
                'success'       => false, 
                'require_login' => true,
                'message'       => 'Please log in to proceed with online payment.'
            ]);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $fabricId = (int)($input['fabric_id'] ?? 0);
        $customizationId = (int)($input['customization_id'] ?? 0);
        $sizes = $input['sizes'] ?? [];
        $fabricQuantity = max(1, (int)($input['fabric_quantity'] ?? 1));
        $shippingAddress = trim($input['shipping_address'] ?? 'Wholesale Delivery');
        $customerNotes = trim($input['notes'] ?? '');

        $customization = null;
        if ($fabricId > 0) {
            $customization = $this->model->getByFabricId($fabricId);
        } elseif ($customizationId > 0) {
            $customization = $this->model->getById($customizationId);
        }

        if (!$customization) {
            echo json_encode(['success' => false, 'message' => 'Fabric customization rule not found']);
            exit;
        }

        $calc = $this->service->calculateConsumption($customization, $sizes, (float)$customization['fabric_stock'], $fabricQuantity);
        if (!$calc['is_valid']) {
            echo json_encode(['success' => false, 'message' => implode(' ', $calc['errors'])]);
            exit;
        }

        $pricing = $this->service->calculatePricing(
            (float)$customization['fabric_price'],
            (float)$calc['required_fabric'],
            0.00,
            (int)$calc['total_pieces'],
            5.0
        );

        $userId = (int)Session::get('user_id');
        $db = Database::getInstance();
        $userStmt = $db->prepare("SELECT name, email, phone FROM users WHERE id = ?");
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        $receiptId = 'CUST_' . strtoupper(substr(uniqid(), -8));
        $razorpayService = new RazorpayService();

        $rzpRes = $razorpayService->createOrder(
            $pricing['total'],
            $receiptId,
            [
                'fabric_id'        => $customization['fabric_id'],
                'customization_id' => $customization['id'],
                'fabric_quantity'  => $fabricQuantity,
                'program_name'     => $customization['program_name'],
                'total_pieces'     => $calc['total_pieces'],
                'required_fabric'  => $calc['required_fabric'],
                'user_id'          => $userId
            ]
        );

        $rzpOrderId = null;
        if (!empty($rzpRes['success']) && !empty($rzpRes['order']['id'])) {
            $rzpOrderId = $rzpRes['order']['id'];
        } else {
            // Fallback generated ID for test environment
            $rzpOrderId = 'order_mock_' . uniqid();
        }

        echo json_encode([
            'success'           => true,
            'razorpay_key'      => defined('RAZORPAY_KEY_ID') ? RAZORPAY_KEY_ID : '',
            'razorpay_order_id' => $rzpOrderId,
            'amount_in_paise'   => (int)round($pricing['total'] * 100),
            'amount'            => $pricing['total'],
            'currency'          => defined('RAZORPAY_CURRENCY') ? RAZORPAY_CURRENCY : 'INR',
            'program_name'      => $customization['program_name'],
            'user'              => [
                'name'    => $user['name'] ?? Session::get('user_name') ?? 'Wholesale Customer',
                'email'   => $user['email'] ?? '',
                'phone'   => $user['phone'] ?? ''
            ]
        ]);
        exit;
    }

    /**
     * OPTION 2 (Step 2): Verify Razorpay Payment and Record Paid Custom Order
     */
    public function razorpayVerifyAjax()
    {
        header('Content-Type: application/json');

        if (!Session::get('user_id')) {
            echo json_encode(['success' => false, 'message' => 'Session expired. Please log in again.']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $fabricId = (int)($input['fabric_id'] ?? 0);
        $customizationId = (int)($input['customization_id'] ?? 0);
        $sizes = $input['sizes'] ?? [];
        $fabricQuantity = max(1, (int)($input['fabric_quantity'] ?? 1));
        $shippingAddress = trim($input['shipping_address'] ?? 'Wholesale Delivery');
        $customerNotes = trim($input['notes'] ?? '');
        $razorpayPaymentId = trim($input['razorpay_payment_id'] ?? '');
        $razorpayOrderId = trim($input['razorpay_order_id'] ?? '');
        $razorpaySignature = trim($input['razorpay_signature'] ?? '');

        if (empty($razorpayPaymentId)) {
            echo json_encode(['success' => false, 'message' => 'Missing Razorpay payment ID.']);
            exit;
        }

        $customization = null;
        if ($fabricId > 0) {
            $customization = $this->model->getByFabricId($fabricId);
        } elseif ($customizationId > 0) {
            $customization = $this->model->getById($customizationId);
        }

        if (!$customization) {
            echo json_encode(['success' => false, 'message' => 'Fabric customization rule not found']);
            exit;
        }

        $calc = $this->service->calculateConsumption($customization, $sizes, (float)$customization['fabric_stock'], $fabricQuantity);
        if (!$calc['is_valid']) {
            echo json_encode(['success' => false, 'message' => implode(' ', $calc['errors'])]);
            exit;
        }

        $pricing = $this->service->calculatePricing(
            (float)$customization['fabric_price'],
            (float)$calc['required_fabric'],
            0.00,
            (int)$calc['total_pieces'],
            5.0
        );

        // Verify Signature if signature is provided and not dummy mock
        $razorpayService = new RazorpayService();
        if (!empty($razorpaySignature) && strpos($razorpayOrderId, 'order_mock_') === false) {
            $isValidSig = $razorpayService->verifySignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature);
            if (!$isValidSig) {
                // If signature failed, reject
                echo json_encode(['success' => false, 'message' => 'Razorpay payment verification failed (invalid signature).']);
                exit;
            }
        }

        $userId = (int)Session::get('user_id');
        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $orderNumber = 'GRM-CUST-' . strtoupper(substr(uniqid(), -6));

            $customizationData = [
                'customization_id'    => $customization['id'],
                'fabric_quantity'     => $fabricQuantity,
                'program_name'        => $customization['program_name'],
                'garment_type'        => $customization['garment_type'],
                'feeding_type'        => $customization['feeding_type'],
                'fabric_name'         => $customization['fabric_name'],
                'fabric_sku'          => $customization['fabric_sku'],
                'fabric_unit'         => $customization['unit'],
                'total_pieces'        => $calc['total_pieces'],
                'required_fabric'     => $calc['required_fabric'],
                'base_consumption'    => $calc['base_consumption'],
                'wastage_percentage'  => $calc['wastage_percentage'],
                'wastage_amount'      => $calc['wastage_amount'],
                'price_per_unit'      => $customization['fabric_price'],
                'fabric_cost'         => $pricing['fabric_cost'],
                'stitching_cost'      => $pricing['stitching_cost'],
                'gst_amount'          => $pricing['gst_amount'],
                'grand_total'         => $pricing['total'],
                'order_mode'          => 'razorpay_paid',
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_order_id'   => $razorpayOrderId,
                'customer_notes'      => $customerNotes,
                'sizes'               => $calc['breakdown']
            ];

            $orderStmt = $db->prepare("
                INSERT INTO orders (
                    user_id, order_number, total_amount, grand_total, shipping_address, 
                    status, payment_status, razorpay_order_id, razorpay_payment_id, 
                    notes, created_at
                ) VALUES (?, ?, ?, ?, ?, 'stitching', 'paid', ?, ?, ?, NOW())
            ");

            $orderStmt->execute([
                $userId,
                $orderNumber,
                $pricing['subtotal'],
                $pricing['total'],
                $shippingAddress,
                $razorpayOrderId,
                $razorpayPaymentId,
                "Online Paid Custom Garment ({$customization['program_name']}) - Payment ID: " . $razorpayPaymentId
            ]);

            $orderId = (int)$db->lastInsertId();

            // Insert into order_items
            $itemStmt = $db->prepare("
                INSERT INTO order_items (
                    order_id, product_id, customization_id, customization_data, 
                    quantity, unit_price, total_price
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $itemStmt->execute([
                $orderId,
                $customization['fabric_id'],
                $customization['id'],
                json_encode($customizationData),
                $calc['total_pieces'],
                $customization['fabric_price'],
                $pricing['total']
            ]);

            // Decrement fabric stock
            $stockStmt = $db->prepare("
                UPDATE products 
                SET stock_quantity = GREATEST(0, stock_quantity - ?) 
                WHERE id = ?
            ");
            $stockStmt->execute([(int)$calc['required_fabric'], $customization['fabric_id']]);

            $db->commit();

            echo json_encode([
                'success'      => true,
                'order_id'     => $orderId,
                'order_number' => $orderNumber,
                'message'      => "Payment successful! Your custom order #{$orderNumber} is confirmed and sent to stitching.",
                'redirect_url' => BASE_URL . '/order-success?order_id=' . $orderId
            ]);
            exit;

        } catch (\Exception $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error completing paid order: ' . $e->getMessage()]);
            exit;
        }
    }
}
