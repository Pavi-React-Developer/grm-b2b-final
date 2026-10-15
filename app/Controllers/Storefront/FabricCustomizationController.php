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

        $userId = Session::get('user_id');
        if (!$userId) {
            Session::setFlash('info', 'Please log in to customize fabrics and view wholesale pricing.');
            $redirectTarget = '/fabrics/customize?' . ($fabricId > 0 ? 'fabric_id=' . $fabricId : 'id=' . $customizationId);
            $this->redirect('/login?redirect=' . urlencode($redirectTarget));
            return;
        }

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

        // Fetch user addresses
        $userAddresses = [];
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
        $stmt->execute([$userId]);
        $userAddresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $wishlistIds = [];
        try {
            $wishlistModel = new \App\Models\Wishlist();
            $wishlistIds = $wishlistModel->getProductIds($userId);
        } catch (\Throwable $e) {
            $wishlistIds = [];
        }

        $reviewModel = new \App\Models\Review();
        $customizationReviews = $reviewModel->getApprovedCustomizationReviews((int)$customization['id'], (int)$customization['fabric_id']);
        $customizationStats = $reviewModel->getCustomizationReviewStats((int)$customization['id'], (int)$customization['fabric_id']);

        $this->render('storefront/fabric_customize', [
            'title'                => 'Customize: ' . htmlspecialchars($customization['fabric_name']) . ' (' . htmlspecialchars($customization['garment_type']) . ')',
            'customization'        => $customization,
            'userAddresses'        => $userAddresses,
            'wishlistIds'          => $wishlistIds,
            'userId'               => $userId,
            'customizationReviews' => $customizationReviews,
            'customizationStats'   => $customizationStats
        ]);
    }

    /**
     * Dedicated Custom Checkout & Razorpay Review Page
     */
    public function checkoutPage()
    {
        $userId = Session::get('user_id');
        $fabricId = (int)($_POST['fabric_id'] ?? ($_GET['fabric_id'] ?? 0));
        $customizationId = (int)($_POST['customization_id'] ?? ($_GET['id'] ?? ($_GET['customization_id'] ?? 0)));

        $redirectTarget = '/fabrics/customize/checkout?' . ($fabricId > 0 ? 'fabric_id=' . $fabricId : 'customization_id=' . $customizationId);

        if (!$userId || Session::get('user_status') !== 'active') {
            Session::setFlash('info', 'Please log in to review your custom garment order and proceed to payment.');
            $this->redirect('/login?redirect=' . urlencode($redirectTarget));
            return;
        }

        $customization = null;
        if ($fabricId > 0) {
            $customization = $this->model->getByFabricId($fabricId);
        } elseif ($customizationId > 0) {
            $customization = $this->model->getById($customizationId);
        }

        if (!$customization) {
            Session::setFlash('error', 'Fabric customization program not found.');
            $this->redirect('/customize');
            return;
        }

        // Parse sizes from POST, session or GET
        $sizes = [];
        if (!empty($_POST['sizes']) && is_array($_POST['sizes'])) {
            $sizes = $_POST['sizes'];
            Session::set('custom_checkout_sizes_' . $customization['id'], $sizes);
        } elseif (!empty($_GET['sizes']) && is_array($_GET['sizes'])) {
            $sizes = $_GET['sizes'];
            Session::set('custom_checkout_sizes_' . $customization['id'], $sizes);
        } else {
            $sizes = Session::get('custom_checkout_sizes_' . $customization['id']) ?? [];
        }

        // If sizes are still empty, default to base configuration from the program
        if (empty($sizes) && !empty($customization['sizes'])) {
            foreach ($customization['sizes'] as $sz) {
                $sizes[$sz['size_name']] = (int)($sz['base_quantity'] ?? 2);
            }
        }

        $fabricQuantity = max(1, (int)($_POST['fabric_quantity'] ?? ($_GET['fabric_quantity'] ?? (Session::get('custom_checkout_qty_' . $customization['id']) ?? 1))));
        Session::set('custom_checkout_qty_' . $customization['id'], $fabricQuantity);

        $packagingOption = $_POST['packaging_option'] ?? ($_GET['packaging_option'] ?? (Session::get('custom_checkout_pkg_' . $customization['id']) ?? 'with_cover'));
        Session::set('custom_checkout_pkg_' . $customization['id'], $packagingOption);
        $withoutCover = ($packagingOption === 'without_cover');
        $discountPct = (float)($customization['without_cover_discount_percentage'] ?? 0.0);

        // Calculate consumption and pricing
        $consumption = $this->service->calculateConsumption(
            $customization, 
            $sizes, 
            (float)$customization['fabric_stock'],
            $fabricQuantity
        );

        $pricing = $this->service->calculatePricing(
            (float)$customization['fabric_price'],
            (float)$consumption['required_fabric'],
            0.00,
            (int)$consumption['total_pieces'],
            5.0,
            $withoutCover,
            $discountPct
        );

        $delivery = $this->service->calculateDeliveryTimeline(
            (int)$consumption['total_pieces'],
            (int)($customization['delivery_days'] ?? 7),
            (int)($customization['delivery_base_pieces'] ?? 100)
        );

        // Fetch user addresses
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
        $stmt->execute([$userId]);
        $userAddresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userStmt = $db->prepare("SELECT name, email, phone FROM users WHERE id = ?");
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        $this->render('storefront/fabric_checkout', [
            'title'           => 'Checkout & Payment: ' . htmlspecialchars($customization['fabric_name']) . ' Customization',
            'customization'   => $customization,
            'sizes'           => $sizes,
            'fabricQuantity'  => $fabricQuantity,
            'packagingOption' => $packagingOption,
            'consumption'     => $consumption,
            'pricing'         => $pricing,
            'delivery'        => $delivery,
            'userAddresses'   => $userAddresses,
            'user'            => $user
        ]);
    }

    /**
     * AJAX Live Calculation Endpoint
     */
    public function calculateAjax()
    {
        header('Content-Type: application/json');

        if (!Session::get('user_id')) {
            echo json_encode(['success' => false, 'error' => 'Authentication required to view customization pricing.']);
            exit;
        }
        
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

        $packagingOption = $input['packaging_option'] ?? 'with_cover';
        $withoutCover = ($packagingOption === 'without_cover' || !empty($input['without_cover']));
        $discountPct = (float)($customization['without_cover_discount_percentage'] ?? 0.0);

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
            5.0, // GST 5%
            $withoutCover,
            $discountPct
        );

        $deliveryResult = $this->service->calculateDeliveryTimeline(
            (int)$consumptionResult['total_pieces'],
            (int)($customization['delivery_days'] ?? 7),
            (int)($customization['delivery_base_pieces'] ?? 100)
        );

        echo json_encode([
            'success'     => true,
            'consumption' => $consumptionResult,
            'pricing'     => $pricingResult,
            'delivery'    => $deliveryResult
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

        $packagingOption = $input['packaging_option'] ?? 'with_cover';
        $withoutCover = ($packagingOption === 'without_cover' || !empty($input['without_cover']));
        $discountPct = (float)($customization['without_cover_discount_percentage'] ?? 0.0);

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
            5.0,
            $withoutCover,
            $discountPct
        );

        $delivery = $this->service->calculateDeliveryTimeline(
            (int)$calc['total_pieces'],
            (int)($customization['delivery_days'] ?? 7),
            (int)($customization['delivery_base_pieces'] ?? 100)
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
                'packaging_option'    => $packagingOption,
                'without_cover'       => $withoutCover,
                'discount_percentage' => $discountPct,
                'total_saved'         => $pricing['total_saved'] ?? 0.00,
                'delivery_timeline'   => $delivery,
                'fabric_name'         => $customization['fabric_name'],
                'fabric_sku'          => $customization['fabric_sku'],
                'fabric_unit'         => $customization['unit'],
                'total_pieces'        => $calc['total_pieces'],
                'required_fabric'     => $calc['required_fabric'],
                'base_consumption'    => $calc['base_consumption'],
                'wastage_percentage'  => $calc['wastage_percentage'],
                'wastage_amount'      => $calc['wastage_amount'],
                'price_per_unit'      => $pricing['price_per_unit'],
                'original_price'      => $pricing['original_price_per_unit'],
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

        $packagingOption = $input['packaging_option'] ?? 'with_cover';
        $withoutCover = ($packagingOption === 'without_cover' || !empty($input['without_cover']));
        $discountPct = (float)($customization['without_cover_discount_percentage'] ?? 0.0);

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
            5.0,
            $withoutCover,
            $discountPct
        );

        $userId = (int)Session::get('user_id');
        $db = Database::getInstance();
        $userStmt = $db->prepare("SELECT name, email, phone FROM users WHERE id = ?");
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        $receiptId = 'CUST_' . strtoupper(substr(uniqid(), -8));
        $razorpayService = new RazorpayService();

        $fabricTitle = $customization['fabric_name'] ?? 'Custom Order';

        $rzpRes = $razorpayService->createOrder(
            $pricing['total'],
            $receiptId,
            [
                'fabric_id'        => $customization['fabric_id'],
                'customization_id' => $customization['id'],
                'fabric_quantity'  => $fabricQuantity,
                'fabric_name'      => substr($fabricTitle, 0, 40),
                'total_pieces'     => $calc['total_pieces'],
                'required_fabric'  => $calc['required_fabric'],
                'user_id'          => $userId
            ]
        );

        if (empty($rzpRes['success']) || empty($rzpRes['order']['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Unable to create Razorpay payment order: ' . ($rzpRes['error'] ?? 'Gateway request failed')
            ]);
            exit;
        }

        $rzpOrderId = $rzpRes['order']['id'];

        echo json_encode([
            'success'           => true,
            'razorpay_key'      => defined('RAZORPAY_KEY_ID') ? RAZORPAY_KEY_ID : '',
            'razorpay_order_id' => $rzpOrderId,
            'amount_in_paise'   => (int)round($pricing['total'] * 100),
            'amount'            => $pricing['total'],
            'currency'          => defined('RAZORPAY_CURRENCY') ? RAZORPAY_CURRENCY : 'INR',
            'program_name'      => $fabricTitle,
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

        $packagingOption = $input['packaging_option'] ?? 'with_cover';
        $withoutCover = ($packagingOption === 'without_cover' || !empty($input['without_cover']));
        $discountPct = (float)($customization['without_cover_discount_percentage'] ?? 0.0);

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
            5.0,
            $withoutCover,
            $discountPct
        );

        $delivery = $this->service->calculateDeliveryTimeline(
            (int)$calc['total_pieces'],
            (int)($customization['delivery_days'] ?? 7),
            (int)($customization['delivery_base_pieces'] ?? 100)
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
                'program_name'        => $customization['program_name'] ?? $customization['fabric_name'] ?? 'Custom Order',
                'garment_type'        => $customization['garment_type'],
                'feeding_type'        => $customization['feeding_type'],
                'packaging_option'    => $packagingOption,
                'without_cover'       => $withoutCover,
                'discount_percentage' => $discountPct,
                'total_saved'         => $pricing['total_saved'] ?? 0.00,
                'delivery_timeline'   => $delivery,
                'fabric_name'         => $customization['fabric_name'],
                'fabric_sku'          => $customization['fabric_sku'],
                'fabric_unit'         => $customization['unit'],
                'total_pieces'        => $calc['total_pieces'],
                'required_fabric'     => $calc['required_fabric'],
                'base_consumption'    => $calc['base_consumption'],
                'wastage_percentage'  => $calc['wastage_percentage'],
                'wastage_amount'      => $calc['wastage_amount'],
                'price_per_unit'      => $pricing['price_per_unit'],
                'original_price'      => $pricing['original_price_per_unit'],
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
