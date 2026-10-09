<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use App\Models\User;
use App\Models\BusinessProfile;
use App\Models\Order;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (!Session::get('user_id')) {
            Session::setFlash('error', 'Please login to access your dashboard.');
            $this->redirect('/login');
        }

        // Redirect admins & vendors to the admin dashboard instead of the storefront user dashboard
        if (in_array(Session::get('user_role'), ['super_admin', 'manager', 'staff', 'vendor'])) {
            $this->redirect('/admin/dashboard');
        }
    }

    public function index()
    {
        $this->redirect('/dashboard/profile');
    }

    public function profile()
    {
        $userId = Session::get('user_id');
        $userModel = new User();
        $user = $userModel->findById($userId);
        
        $bpModel = new BusinessProfile();
        $profile = $bpModel->findByUserId($userId);

        $db = \Core\Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM media WHERE user_id = ? AND purpose = 'shop_verification'");
        $stmt->execute([$userId]);
        $shopImages = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT * FROM addresses WHERE user_id = ? AND is_default = 1 LIMIT 1");
        $stmt->execute([$userId]);
        $defaultAddress = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->render('storefront/dashboard/profile', [
            'title' => 'My Profile',
            'user' => $user,
            'profile' => $profile,
            'shopImages' => $shopImages,
            'defaultAddress' => $defaultAddress,
            'activeTab' => 'profile'
        ]);
    }

    public function updateProfile()
    {
        $userId = Session::get('user_id');
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $businessName = trim($_POST['business_name'] ?? '');
        $gstNumber = trim($_POST['gst_number'] ?? '');
        $panNumber = trim($_POST['pan_number'] ?? '');

        $instagramLink = trim($_POST['instagram_link'] ?? '');


        if (empty($name) || empty($phone)) {
            Session::setFlash('error', 'Name and phone are required.');
            $this->redirect('/dashboard/profile');
        }

        if (!preg_match('/^\d{10}$/', $phone)) {
            Session::setFlash('error', 'Phone number must be exactly 10 digits.');
            $this->redirect('/dashboard/profile');
        }



        $db = \Core\Database::getInstance();
        
        // Handle file upload
        $profilePicturePath = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['profile_picture']['tmp_name'];
            $uploader = new \App\Core\CloudinaryUploader();
            $cloudinaryResponse = $uploader->uploadMedia($tmpName);
            
            if ($cloudinaryResponse && isset($cloudinaryResponse['secure_url'])) {
                $profilePicturePath = $cloudinaryResponse['secure_url'];
            }
        }

        // Update User table
        if ($profilePicturePath) {
            $stmt = $db->prepare("UPDATE users SET name = ?, phone = ?, profile_picture = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $profilePicturePath, $userId]);
        } else {
            $stmt = $db->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $userId]);
        }
        
        // Update Business Profile table
        // First check if business profile exists
        $stmt = $db->prepare("SELECT id FROM business_profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $bpExists = $stmt->fetch();
        
        if ($bpExists) {
            $stmt = $db->prepare("UPDATE business_profiles SET business_name = ?, gst_number = ?, pan_number = ?, instagram_link = ? WHERE user_id = ?");
            $stmt->execute([$businessName, $gstNumber, $panNumber, $instagramLink, $userId]);
        } else {
            $stmt = $db->prepare("INSERT INTO business_profiles (user_id, business_name, gst_number, pan_number, instagram_link) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $businessName ?: $name . "'s Business", $gstNumber, $panNumber, $instagramLink]);
        }

        // Update session name if changed
        Session::set('user_name', $name);

        Session::setFlash('success', 'Profile updated successfully.');
        $this->redirect('/dashboard/profile');
    }

    public function orders()
    {
        $userId = Session::get('user_id');
        
        $db = \Core\Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? AND payment_status = 'paid' ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();

        $orderItems = [];
        if (!empty($orders)) {
            $orderIds = array_column($orders, 'id');
            $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
            $stmtItems = $db->prepare("
                SELECT oi.*, 
                       COALESCE(oi.product_id, pv.product_id, fc.fabric_id) as resolved_product_id,
                       COALESCE(p.name, 'Custom Item') as name, 
                       p.weight, 
                       pi.image_path as image, 
                       pv.name as variant_name, 
                       c.hsn_code as category_hsn_code
                FROM order_items oi 
                LEFT JOIN product_variants pv ON oi.variant_id = pv.id
                LEFT JOIN fabric_customizations fc ON oi.customization_id = fc.id
                LEFT JOIN products p ON p.id = COALESCE(oi.product_id, pv.product_id, fc.fabric_id)
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN (SELECT product_id, MIN(image_path) as image_path FROM product_images WHERE is_primary = 1 GROUP BY product_id) pi ON p.id = pi.product_id
                WHERE oi.order_id IN ($placeholders)
            ");
            $stmtItems->execute($orderIds);
            $items = $stmtItems->fetchAll();
            foreach ($items as $item) {
                $orderItems[$item['order_id']][] = $item;
            }
        }

        // Fetch refund requests for this user's orders so views can show badges
        $refundRequests = [];
        // Evaluate cancellation rules for each order
        $orderRules = [];
        
        if (!empty($orders)) {
            $orderIds     = array_column($orders, 'id');
            $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
            $stmtRefunds  = $db->prepare("
                SELECT order_id, status, amount, reason, cancellation_type, cashfree_refund_id
                FROM refund_requests
                WHERE order_id IN ($placeholders)
            ");
            $stmtRefunds->execute($orderIds);
            foreach ($stmtRefunds->fetchAll() as $rr) {
                $refundRequests[$rr['order_id']] = $rr;
            }

            // Calculate exact refund amounts based on active rules
            $ruleModel = new \App\Models\CancellationRule();
            foreach ($orders as $order) {
                $rule = $ruleModel->getRuleForOrder($order);
                $total = (float)($order['grand_total'] ?? $order['total_amount']);
                
                $weightFee    = (float)($order['weight_fee'] ?? 0);
                $packagingFee = (float)($order['packaging_fee'] ?? 0);
                $shippingFee  = (float)($order['shipping_fee'] ?? 0);
                $platformFee  = (float)($order['platform_fee'] ?? 0);
                
                $feeAmount = (float)$rule['cancellation_fee'];
                
                // Final refund is Total - Flat Fee
                $refundAmount = $total - $feeAmount;
                if ($refundAmount < 0) {
                    $refundAmount = 0;
                }

                $orderRules[$order['id']] = [
                    'fee_percent'   => 0, // Unused now but kept for UI compatibility if needed
                    'fee_amount'    => $feeAmount,
                    'refund_amount' => $refundAmount,
                    'eligible'      => $rule['eligible'],
                ];
            }
            
            // Fetch order modifications
            $orderModifications = [];
            $stmtMods = $db->prepare("
                SELECT om.* FROM order_modifications om
                JOIN orders o ON om.order_id = o.id
                WHERE om.order_id IN ($placeholders) 
                  AND om.status IN ('accepted', 'applied')
                  AND om.created_at >= o.created_at
                ORDER BY om.created_at ASC
            ");
            $stmtMods->execute($orderIds);
            foreach ($stmtMods->fetchAll() as $mod) {
                // Keep overwriting so we get the most recent modification
                $orderModifications[$mod['order_id']] = $mod;
            }
        }
        $settingModel = new \App\Models\Setting();
        $cancellationsEnabled = $settingModel->get('cancellations_enabled', '1') === '1';

        $this->render('storefront/dashboard/orders', [
            'title'          => 'My Orders',
            'orders'         => $orders,
            'orderItems'     => $orderItems,
            'refundRequests' => $refundRequests,
            'orderRules'     => $orderRules,
            'orderModifications' => $orderModifications ?? [],
            'cancellationsEnabled' => $cancellationsEnabled,
            'activeTab'      => 'orders'
        ]);
    }

    public function cancelOrder()
    {
        $userId = Session::get('user_id');
        $orderId = $_POST['order_id'] ?? '';

        if (!$orderId) {
            Session::setFlash('error', 'Invalid order.');
            $this->redirect('/dashboard/orders');
        }

        $db = \Core\Database::getInstance();
        $stmt = $db->prepare("SELECT id, status FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch();

        if (!$order) {
            Session::setFlash('error', 'Order not found.');
            $this->redirect('/dashboard/orders');
        }

        $ruleModel = new \App\Models\CancellationRule();
        $rule = $ruleModel->getRuleForOrder($order);

        if ($rule['eligible']) {
            $stmt = $db->prepare("UPDATE orders SET status = 'cancelled', cancelled_by_role = 'buyer', cancelled_at = NOW() WHERE id = ?");
            $stmt->execute([$orderId]);
            
            // Restore inventory stock
            $orderModel = new Order();
            $orderModel->restoreStockForOrderId($orderId);
            
            Session::setFlash('success', 'Order cancelled successfully.');
        } else {
            Session::setFlash('error', 'This order cannot be cancelled under its current status.');
        }

        $this->redirect('/dashboard/orders');
    }

    public function orderDetail()
    {
        $userId = Session::get('user_id');
        $orderNumber = $_GET['order_id'] ?? null;

        if (!$orderNumber) {
            $this->redirect('/dashboard/orders');
        }

        $db = \Core\Database::getInstance();

        // Fetch the order (only for this user)
        $stmt = $db->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
        $stmt->execute([$orderNumber, $userId]);
        $order = $stmt->fetch();

        if (!$order) {
            Session::setFlash('error', 'Order not found.');
            $this->redirect('/dashboard/orders');
        }

        // Fetch order items with product image, weight, variant
        $stmtItems = $db->prepare("
            SELECT oi.*,
                   p.name,
                   p.weight,
                   c.hsn_code as category_hsn_code,
                   COALESCE(
                       (SELECT image_path FROM product_images WHERE variant_id = oi.variant_id ORDER BY is_primary DESC, id ASC LIMIT 1),
                       (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1)
                   ) as image,
                   pv.name as variant_name
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE oi.order_id = ?
            ORDER BY oi.id ASC
        ");
        $stmtItems->execute([$order['id']]);
        $items = $stmtItems->fetchAll();

        // Fetch latest accepted order modification if any
        $modification = null;
        $stmtMod = $db->prepare("
            SELECT * FROM order_modifications 
            WHERE order_id = ? AND status IN ('accepted', 'applied')
            ORDER BY created_at DESC LIMIT 1
        ");
        $stmtMod->execute([$order['id']]);
        $modification = $stmtMod->fetch(\PDO::FETCH_ASSOC) ?: null;

        $this->render('storefront/dashboard/order_detail', [
            'title'        => 'Order Details — ' . $orderNumber,
            'order'        => $order,
            'items'        => $items,
            'modification' => $modification,
            'activeTab'    => 'orders'
        ]);
    }

    public function addresses()
    {
        $userId = Session::get('user_id');
        $db = \Core\Database::getInstance();

        // Auto-seed: if user has no addresses yet, pull from business_profiles.shop_location
        $countStmt = $db->prepare("SELECT COUNT(*) FROM addresses WHERE user_id = ?");
        $countStmt->execute([$userId]);
        if ((int)$countStmt->fetchColumn() === 0) {
            $bpStmt = $db->prepare("SELECT shop_location FROM business_profiles WHERE user_id = ?");
            $bpStmt->execute([$userId]);
            $bp = $bpStmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($bp['shop_location'])) {
                $shopLocation = $bp['shop_location'];
                $line1 = $shopLocation;
                $city = 'Unknown';
                $state = 'Unknown';
                $postal = '000000';
                
                if (preg_match('/^(.*?),\s*(.*?),\s*(.*?)\s*-\s*(\d{6})$/', $shopLocation, $matches)) {
                    $line1 = trim($matches[1]);
                    $city = trim($matches[2]);
                    $state = trim($matches[3]);
                    $postal = $matches[4];
                }
                
                $db->prepare("INSERT INTO addresses (user_id, label, line1, city, state, postal_code, country, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, 1)")
                   ->execute([$userId, 'Shop Address', $line1, $city, $state, $postal, 'India']);
            }
        }

        $stmt = $db->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
        $stmt->execute([$userId]);
        $addresses = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('storefront/dashboard/addresses', [
            'title'     => 'My Addresses',
            'addresses' => $addresses,
            'activeTab' => 'addresses'
        ]);
    }

    public function addAddress()
    {
        $userId   = Session::get('user_id');
        $label    = trim($_POST['label'] ?? '');
        $line1    = trim($_POST['line1'] ?? '');
        $line2    = trim($_POST['line2'] ?? '');
        $city     = trim($_POST['city'] ?? '');
        $state    = trim($_POST['state'] ?? '');
        $otherState = trim($_POST['other_state_name'] ?? '');
        if ($state === 'Others' && !empty($otherState)) {
            $state = $otherState;
        }
        $postal   = trim($_POST['postal_code'] ?? '');
        $country  = trim($_POST['country'] ?? 'India') ?: 'India';
        $isDefault = isset($_POST['is_default']) ? 1 : 0;

        if (empty($line1) || empty($city) || empty($state) || $state === 'Others' || empty($postal)) {
            Session::setFlash('error', 'Address Line 1, City, State, and Pincode are required.');
            $this->redirect('/dashboard/addresses');
        }

        if (!preg_match('/^\d{6}$/', $postal)) {
            Session::setFlash('error', 'Pincode must be exactly 6 digits.');
            $this->redirect('/dashboard/addresses');
        }

        $db = \Core\Database::getInstance();

        if ($isDefault) {
            $db->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
        }

        $stmt = $db->prepare("INSERT INTO addresses (user_id, label, line1, line2, city, state, postal_code, country, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $label, $line1, $line2, $city, $state, $postal, $country, $isDefault]);

        Session::setFlash('success', 'Address added successfully.');
        $this->redirect('/dashboard/addresses');
    }

    public function editAddress()
    {
        $userId    = Session::get('user_id');
        $addressId = (int)($_POST['address_id'] ?? 0);
        $label     = trim($_POST['label'] ?? '');
        $line1     = trim($_POST['line1'] ?? '');
        $line2     = trim($_POST['line2'] ?? '');
        $city      = trim($_POST['city'] ?? '');
        $state     = trim($_POST['state'] ?? '');
        $otherState = trim($_POST['other_state_name'] ?? '');
        if ($state === 'Others' && !empty($otherState)) {
            $state = $otherState;
        }
        $postal    = trim($_POST['postal_code'] ?? '');
        $country   = trim($_POST['country'] ?? 'India') ?: 'India';
        $isDefault = isset($_POST['is_default']) ? 1 : 0;

        if (!$addressId || empty($line1) || empty($city) || empty($state) || $state === 'Others' || empty($postal)) {
            Session::setFlash('error', 'Address Line 1, City, State, and Pincode are required.');
            $this->redirect('/dashboard/addresses');
        }

        if (!preg_match('/^\d{6}$/', $postal)) {
            Session::setFlash('error', 'Pincode must be exactly 6 digits.');
            $this->redirect('/dashboard/addresses');
        }

        $db = \Core\Database::getInstance();

        // Verify ownership
        $check = $db->prepare("SELECT id FROM addresses WHERE id = ? AND user_id = ?");
        $check->execute([$addressId, $userId]);
        if (!$check->fetch()) {
            Session::setFlash('error', 'Address not found.');
            $this->redirect('/dashboard/addresses');
        }

        if ($isDefault) {
            $db->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
        }

        $stmt = $db->prepare("UPDATE addresses SET label = ?, line1 = ?, line2 = ?, city = ?, state = ?, postal_code = ?, country = ?, is_default = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$label, $line1, $line2, $city, $state, $postal, $country, $isDefault, $addressId, $userId]);

        Session::setFlash('success', 'Address updated successfully.');
        $this->redirect('/dashboard/addresses');
    }

    public function deleteAddress()
    {
        $userId    = Session::get('user_id');
        $addressId = (int)($_POST['address_id'] ?? 0);

        if (!$addressId) {
            $this->redirect('/dashboard/addresses');
        }

        $db = \Core\Database::getInstance();

        // Don't delete the default address
        $check = $db->prepare("SELECT is_default FROM addresses WHERE id = ? AND user_id = ?");
        $check->execute([$addressId, $userId]);
        $addr = $check->fetch(\PDO::FETCH_ASSOC);

        if (!$addr) {
            Session::setFlash('error', 'Address not found.');
            $this->redirect('/dashboard/addresses');
        }

        if ($addr['is_default']) {
            Session::setFlash('error', 'Cannot delete the default address. Set another address as default first.');
            $this->redirect('/dashboard/addresses');
        }

        $stmt = $db->prepare("DELETE FROM addresses WHERE id = ? AND user_id = ?");
        $stmt->execute([$addressId, $userId]);

        Session::setFlash('success', 'Address deleted.');
        $this->redirect('/dashboard/addresses');
    }

    public function setDefaultAddress()
    {
        $userId    = Session::get('user_id');
        $addressId = (int)($_POST['address_id'] ?? 0);

        if (!$addressId) {
            $this->redirect('/dashboard/addresses');
        }

        $db = \Core\Database::getInstance();

        // Verify ownership
        $check = $db->prepare("SELECT id FROM addresses WHERE id = ? AND user_id = ?");
        $check->execute([$addressId, $userId]);
        if (!$check->fetch()) {
            Session::setFlash('error', 'Address not found.');
            $this->redirect('/dashboard/addresses');
        }

        $db->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
        $db->prepare("UPDATE addresses SET is_default = 1 WHERE id = ? AND user_id = ?")->execute([$addressId, $userId]);

        Session::setFlash('success', 'Default address updated.');
        $this->redirect('/dashboard/addresses');
    }

    public function settings()
    {
        $userId = Session::get('user_id');
        $userModel = new User();
        $user = $userModel->findById($userId);

        $this->render('storefront/dashboard/settings', [
            'title' => 'Settings',
            'user' => $user,
            'activeTab' => 'settings'
        ]);
    }
}

