<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\OrderRule;
use Core\Database;

class CartController extends Controller
{
    private function requireAuth(): int
    {
        $userId = \Core\Session::get('user_id');
        if (!$userId || \Core\Session::get('user_status') !== 'active') {
            \Core\Session::setFlash('error', 'Please login to access your cart.');
            $this->redirect('/login');
        }
        return (int)$userId;
    }
    public function index()
    {
        $userId = $this->requireAuth();

        $cartModel = new Cart();
        $cartItems = $cartModel->getItems($userId);

        $subtotal = 0;
        $moqErrors = [];
        $db = Database::getInstance();

        // For order rules
        $categoryTotals = [];
        $variantModel = new \App\Models\ProductVariant();
        $cartVariantIds = array_filter(array_column($cartItems, 'variant_id'));

        foreach ($cartItems as $key => &$item) {
            $availableStock = (int)$item['available_stock'];
            // Auto-heal any existing cart item where quantity exceeds current available stock
            if ($availableStock > 0 && $item['quantity'] > $availableStock) {
                $item['quantity'] = $availableStock;
                $db->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?")->execute([$availableStock, $item['id']]);
            }
            $requiredMoq = (int)($item['moq_override'] ?? (!empty($item['category_moq']) ? $item['category_moq'] : 1));
            $item['moq'] = $requiredMoq;
            $item['is_out_of_stock'] = ($availableStock < $requiredMoq || $item['quantity'] > $availableStock);
            $item['is_max_stock_reached'] = ($item['quantity'] >= $availableStock && $availableStock > 0);
            $item['item_error'] = null;
            $item['item_notice'] = null;
            $item['alternative_variants'] = [];

            if ($item['is_out_of_stock'] || $item['is_max_stock_reached']) {
                if ($availableStock < $requiredMoq) {
                    $item['item_error'] = "Out of stock (Available: {$availableStock}, Min. Order: {$requiredMoq}).";
                } elseif ($item['quantity'] > $availableStock) {
                    $item['item_error'] = "You cannot order more than the available stock ({$availableStock}).";
                } elseif ($item['is_max_stock_reached']) {
                    $item['item_notice'] = "Max available inventory reached ({$availableStock} units).";
                }

                // Fetch alternative in-stock variants whenever out of stock OR max stock reached (excluding variants already added to cart)
                $allV = $variantModel->getByProductId((int)$item['product_id']);
                foreach ($allV as $v) {
                    if ($v['id'] != $item['variant_id'] && !in_array($v['id'], $cartVariantIds) && ($v['status'] ?? 'active') === 'active' && ((int)$v['current_stock'] >= $requiredMoq)) {
                        $vBase = (float)($v['base_price'] ?? 0);
                        $vDisc = !empty($v['discount_price']) && (float)$v['discount_price'] > 0 ? (float)$v['discount_price'] : null;
                        $vEff = $vDisc ?? $vBase;
                        $item['alternative_variants'][] = [
                            'id' => (int)$v['id'],
                            'name' => $v['name'],
                            'price' => $vEff,
                            'formatted_price' => format_price($vEff),
                            'stock' => (int)$v['current_stock'],
                            'moq' => $requiredMoq
                        ];
                    }
                }
            }

            $gst = (float)($item['variant_total_gst'] ?? 0);
            if ($gst <= 0) $gst = (float)($item['product_total_gst'] ?? 0);
            if ($gst <= 0) $gst = (float)($item['category_sgst'] ?? 0) + (float)($item['category_cgst'] ?? 0);

            // Check B2B Volume Pricing Tiers first
            $tierStmt = $db->prepare("
                SELECT tier_price FROM product_volume_tiers 
                WHERE product_id = ? AND min_qty <= ? AND (max_qty >= ? OR max_qty IS NULL)
                ORDER BY min_qty DESC LIMIT 1
            ");
            $tierStmt->execute([$item['product_id'], $item['quantity'], $item['quantity']]);
            $tierPrice = $tierStmt->fetchColumn();

            $basePrice = (!empty($item['variant_price']) && (float)$item['variant_price'] > 0) 
                ? (float)$item['variant_price'] 
                : (float)($item['wholesale_price'] ?? 0);
            $discountPrice = (!empty($item['variant_discount_price']) && (float)$item['variant_discount_price'] > 0) 
                ? (float)$item['variant_discount_price'] 
                : null;

            if ($tierPrice !== false && (float)$tierPrice > 0) {
                $rawEffective = (float)$tierPrice;
            } elseif ($discountPrice !== null) {
                $rawEffective = $discountPrice;
            } else {
                $rawEffective = $basePrice;
            }

            $unitPrice = $rawEffective;

            $item['unit_price'] = $unitPrice;
            $item['base_price'] = $basePrice;
            $item['discount_price'] = $discountPrice;
            $item['gst_rate'] = $gst;
            $itemTotal = round($unitPrice * $item['quantity'], 2);
            $subtotal += $itemTotal;
            
            // Check MOQ logic
            if ($item['quantity'] < $requiredMoq) {
                $moqErrors[] = "Minimum order quantity for <strong>{$item['name']}</strong> is {$requiredMoq} pieces.";
            }
            
            // Accumulate category totals for Order Rules
            $catId = $item['category_id'];
            if (!isset($categoryTotals[$catId])) {
                $categoryTotals[$catId] = ['name' => $item['category_name'], 'total' => 0];
            }
            $categoryTotals[$catId]['total'] += $itemTotal;
        }
        unset($item);
        
        // Validate against Order Rules
        $ruleValidation = $this->validateOrderRules($categoryTotals);
        $moqErrors = array_merge($moqErrors, $ruleValidation['errors']);
        
        $activeOffers = $this->getActiveOffersData($categoryTotals);
        $unlockedCatIds = [];
        foreach ($activeOffers as $off) {
            if ($off['is_unlocked']) {
                foreach ($off['secondary_offers'] as $so) {
                    $unlockedCatIds[] = $so['id'];
                }
            }
        }

        // Prioritize suggesting products from failing categories or unlocked offer categories
        $cartCategoryIds = !empty($ruleValidation['failing_categories']) 
            ? $ruleValidation['failing_categories'] 
            : (!empty($unlockedCatIds) ? $unlockedCatIds : array_keys($categoryTotals));
            
        $relatedProducts = $this->getRelatedProducts($cartCategoryIds, $unlockedCatIds);
        
        $suggestionUrl = BASE_URL . "/catalog";
        if (!empty($ruleValidation['failing_categories'])) {
            $suggestionUrl .= "?category_id=" . $ruleValidation['failing_categories'][0];
        } elseif (!empty($unlockedCatIds)) {
            $suggestionUrl .= "?category_id=" . $unlockedCatIds[0];
        }

        $this->render('storefront/cart', [
            'title' => 'Your Shopping Cart',
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'moqErrors' => $moqErrors,
            'activeOffers' => $activeOffers,
            'relatedProducts' => $relatedProducts,
            'suggestionUrl' => $suggestionUrl
        ]);
    }

    public function add()
    {
        $userId = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)($_POST['product_id'] ?? 0);
            $variantId = !empty($_POST['variant_id']) ? (int)$_POST['variant_id'] : null;
            $quantity = (int)($_POST['quantity'] ?? 1);

            $productModel = new Product();
            $product = $productModel->findById($productId);

            if ($product) {
                $db = Database::getInstance();
                
                // If variant not specified, pick default/first variant
                if (!$variantId) {
                    $stmtV = $db->prepare("SELECT id FROM product_variants WHERE product_id = ? AND status = 'active' ORDER BY is_default DESC, id ASC LIMIT 1");
                    $stmtV->execute([$productId]);
                    $variantId = $stmtV->fetchColumn() ?: null;
                }

                // Check actual stock of variant
                $stockQty = 0;
                if ($variantId) {
                    $stmtStock = $db->prepare("SELECT current_stock FROM product_variants WHERE id = ?");
                    $stmtStock->execute([$variantId]);
                    $stockQty = (int)$stmtStock->fetchColumn();
                } else {
                    $stockQty = (int)($product['stock_quantity'] ?? 0);
                }

                $moq = !empty($product['moq_override']) ? (int)$product['moq_override'] : 1;
                if ($moq < 1) $moq = 1;

                if ($stockQty < $moq) {
                    $_SESSION['error'] = "This item is currently out of stock (available: {$stockQty}, MOQ: {$moq}).";
                    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/cart'));
                    exit;
                }

                if ($quantity < $moq) {
                    $quantity = $moq;
                }

                $cartModel = new Cart();
                $cartModel->addItem($userId, $productId, $quantity, $variantId, $stockQty);
                
                // If added from wishlist page or explicitly requested, remove from wishlist
                if (!empty($_POST['from_wishlist'])) {
                    $wishlistModel = new Wishlist();
                    $wishlistModel->removeItem($userId, $productId);
                }
                
                $_SESSION['success'] = "Added to cart successfully.";
            } else {
                $_SESSION['error'] = "Product not found.";
            }

            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/cart'));
            exit;
        }
    }

    public function update()
    {
        $userId = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 1);

            $cartModel = new Cart();
            if ($quantity > 0) {
                // Check and clamp at available stock
                $db = Database::getInstance();
                $stmt = $db->prepare("
                    SELECT ci.quantity, 
                           COALESCE(pv.current_stock, (SELECT SUM(current_stock) FROM product_variants WHERE product_id = ci.product_id), p.stock_quantity, 0) as available_stock
                    FROM cart_items ci
                    JOIN products p ON ci.product_id = p.id
                    LEFT JOIN product_variants pv ON ci.variant_id = pv.id
                    WHERE ci.id = ? AND ci.user_id = ?
                ");
                $stmt->execute([$cartItemId, $userId]);
                $itemRow = $stmt->fetch();
                if ($itemRow) {
                    $avail = (int)$itemRow['available_stock'];
                    if ($avail > 0 && $quantity > $avail) {
                        $quantity = $avail; // Clamp at max stock limit
                    }
                }
                $cartModel->updateQuantity($cartItemId, $userId, $quantity);
            } else {
                $cartModel->removeItem($cartItemId, $userId);
            }

            header('Location: ' . BASE_URL . '/cart');
            exit;
        }
    }

    public function remove()
    {
        $userId = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItemId = (int)($_POST['cart_item_id'] ?? 0);

            $cartModel = new Cart();
            $cartModel->removeItem($cartItemId, $userId);

            header('Location: ' . BASE_URL . '/cart');
            exit;
        }
    }

    public function switchVariant()
    {
        $userId = $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
            $newVariantId = (int)($_POST['new_variant_id'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 1);

            if ($cartItemId > 0 && $newVariantId > 0) {
                $db = Database::getInstance();
                $stmt = $db->prepare("UPDATE cart_items SET variant_id = ?, quantity = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$newVariantId, $quantity, $cartItemId, $userId]);
                $_SESSION['success'] = "Switched to in-stock variant.";
            }
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/cart'));
            exit;
        }
    }

    public function ajaxSwitchVariant()
    {
        header('Content-Type: application/json');
        if (!\Core\Session::get('user_id')) {
            echo json_encode(['success' => false, 'message' => 'Please log in']);
            exit;
        }
        $userId = (int)\Core\Session::get('user_id');
        $rawInput = json_decode(file_get_contents('php://input'), true) ?: [];
        $cartItemId = (int)($rawInput['cart_item_id'] ?? $_POST['cart_item_id'] ?? 0);
        $newVariantId = (int)($rawInput['new_variant_id'] ?? $_POST['new_variant_id'] ?? 0);
        $quantity = (int)($rawInput['quantity'] ?? $_POST['quantity'] ?? 1);

        if ($cartItemId > 0 && $newVariantId > 0) {
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE cart_items SET variant_id = ?, quantity = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$newVariantId, $quantity, $cartItemId, $userId]);
            
            $data = $this->getCartDataResponse($userId);
            echo json_encode(array_merge(['success' => true, 'message' => 'Switched to in-stock variant', 'cart_data' => $data], $data));
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
        exit;
    }

    public function ajaxGet()
    {
        $this->getCartData();
    }

    public function getCartData()
    {
        ob_start();
        header('Content-Type: application/json');
        if (!\Core\Session::get('user_id')) {
            ob_end_clean();
            echo json_encode([
                'success' => true,
                'items' => [],
                'subtotal' => '₹0',
                'count' => 0,
                'moqErrors' => [],
                'hasItemErrors' => false,
                'activeOffers' => [],
                'relatedProducts' => []
            ]);
            exit;
        }
        try {
            $userId = (int)\Core\Session::get('user_id');
            $data = $this->getCartDataResponse($userId);
            ob_end_clean();
            echo json_encode(array_merge(['success' => true, 'cart_data' => $data], $data));
        } catch (\Throwable $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function ajaxAdd()
    {
        ob_start(); // Buffer any PHP notices/warnings so they don't corrupt JSON
        header('Content-Type: application/json');
        if (!\Core\Session::get('user_id')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Please log in to add items to your cart']);
            exit;
        }

        $userId = (int)\Core\Session::get('user_id');
        $rawInput = json_decode(file_get_contents('php://input'), true) ?: [];
        $productId = (int)($rawInput['product_id'] ?? $_POST['product_id'] ?? 0);
        $variantId = !empty($rawInput['variant_id']) ? (int)$rawInput['variant_id'] : (!empty($_POST['variant_id']) ? (int)$_POST['variant_id'] : null);
        $quantity = (int)($rawInput['quantity'] ?? $_POST['quantity'] ?? 1);

        try {
            $productModel = new Product();

            // If variant_id is given, fetch product_id from the variant directly
            // (the frontend might pass the variant's product_id, but let's verify)
            if ($variantId && !$productId) {
                $db = Database::getInstance();
                $stmtPid = $db->prepare("SELECT product_id FROM product_variants WHERE id = ? LIMIT 1");
                $stmtPid->execute([$variantId]);
                $productId = (int)$stmtPid->fetchColumn();
            }

            $product = $productModel->findById($productId);

            if (!$product) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }

            $db = Database::getInstance();
            if (!$variantId) {
                $stmtV = $db->prepare("SELECT id FROM product_variants WHERE product_id = ? AND status = 'active' ORDER BY id ASC LIMIT 1");
                $stmtV->execute([$productId]);
                $variantId = $stmtV->fetchColumn() ?: null;
            }

            $stockQty = 0;
            if ($variantId) {
                $stmtStock = $db->prepare("SELECT current_stock FROM product_variants WHERE id = ?");
                $stmtStock->execute([$variantId]);
                $stockQty = (int)$stmtStock->fetchColumn();
            } else {
                $stockQty = (int)($product['stock_quantity'] ?? 0);
            }

            $moq = !empty($product['moq_override']) ? (int)$product['moq_override'] : 1;
            if ($moq < 1) $moq = 1;

            if ($stockQty < $moq) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => "Item out of stock (available: {$stockQty}, MOQ: {$moq})."]);
                exit;
            }

            if ($quantity < $moq) {
                $quantity = $moq;
            }

            // Check existing cart quantity to prevent exceeding available stock
            $existingQty = 0;
            if ($variantId) {
                $stmtCheck = $db->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ? AND variant_id = ?");
                $stmtCheck->execute([$userId, $productId, $variantId]);
                $existingQty = (int)$stmtCheck->fetchColumn();
            } else {
                $stmtCheck = $db->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ? AND variant_id IS NULL");
                $stmtCheck->execute([$userId, $productId]);
                $existingQty = (int)$stmtCheck->fetchColumn();
            }

            // If user already reached max stock in cart
            if ($stockQty > 0 && $existingQty >= $stockQty) {
                if (!empty($_POST['from_wishlist']) || !empty($rawInput['from_wishlist'])) {
                    $wishlistModel = new Wishlist();
                    $wishlistModel->removeItem($userId, $productId);
                }
                $data = $this->getCartDataResponse($userId);
                ob_end_clean();
                echo json_encode(array_merge([
                    'success' => true,
                    'message' => "Maximum available stock ({$stockQty}) already in your cart.",
                    'cart_data' => $data
                ], $data));
                exit;
            }

            // Clamp quantity if adding would exceed stockQty
            if ($stockQty > 0 && ($existingQty + $quantity) > $stockQty) {
                $quantity = $stockQty - $existingQty;
            }

            if ($quantity > 0) {
                $cartModel = new Cart();
                $cartModel->addItem($userId, $productId, $quantity, $variantId, $stockQty);
            }

            if (!empty($_POST['from_wishlist']) || !empty($rawInput['from_wishlist'])) {
                $wishlistModel = new Wishlist();
                $wishlistModel->removeItem($userId, $productId);
            }

            $data = $this->getCartDataResponse($userId);
            ob_end_clean();
            echo json_encode(array_merge(['success' => true, 'message' => 'Added to cart!', 'cart_data' => $data], $data));
        } catch (\Throwable $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function ajaxUpdate()
    {
        ob_start();
        header('Content-Type: application/json');
        if (!\Core\Session::get('user_id')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Please log in']);
            exit;
        }

        try {
            $userId = (int)\Core\Session::get('user_id');
            $rawInput = json_decode(file_get_contents('php://input'), true) ?: [];
            $cartItemId = (int)($rawInput['cart_item_id'] ?? $_POST['cart_item_id'] ?? 0);
            $quantity = (int)($rawInput['quantity'] ?? $_POST['quantity'] ?? 1);

            $cartModel = new Cart();
            if ($quantity > 0) {
                $db = Database::getInstance();
                $stmt = $db->prepare("
                    SELECT ci.quantity, 
                           COALESCE(pv.current_stock, (SELECT SUM(current_stock) FROM product_variants WHERE product_id = ci.product_id), p.stock_quantity, 0) as available_stock
                    FROM cart_items ci
                    JOIN products p ON ci.product_id = p.id
                    LEFT JOIN product_variants pv ON ci.variant_id = pv.id
                    WHERE ci.id = ? AND ci.user_id = ?
                ");
                $stmt->execute([$cartItemId, $userId]);
                $itemRow = $stmt->fetch();
                if ($itemRow) {
                    $avail = (int)$itemRow['available_stock'];
                    if ($avail > 0 && $quantity > $avail) {
                        $quantity = $avail;
                    }
                }
                $cartModel->updateQuantity($cartItemId, $userId, $quantity);
            } else {
                $cartModel->removeItem($cartItemId, $userId);
            }

            $data = $this->getCartDataResponse($userId, false);
            ob_end_clean();
            echo json_encode(array_merge(['success' => true, 'cart_data' => $data], $data));
        } catch (\Throwable $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function ajaxRemove()
    {
        ob_start();
        header('Content-Type: application/json');
        if (!\Core\Session::get('user_id')) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Please log in']);
            exit;
        }

        try {
            $userId = (int)\Core\Session::get('user_id');
            $rawInput = json_decode(file_get_contents('php://input'), true) ?: [];
            $cartItemId = (int)($rawInput['cart_item_id'] ?? $_POST['cart_item_id'] ?? 0);

            $cartModel = new Cart();
            $cartModel->removeItem($cartItemId, $userId);

            $data = $this->getCartDataResponse($userId, true);
            ob_end_clean();
            echo json_encode(array_merge(['success' => true, 'cart_data' => $data], $data));
        } catch (\Throwable $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    private function getCartDataResponse(int $userId, bool $includeRelated = true): array
    {
        $cartModel = new Cart();
        $cartItems = $cartModel->getItems($userId);

        $subtotal = 0;
        $moqErrors = [];
        $hasItemErrors = false;
        $categoryTotals = [];
        $db = Database::getInstance();
        $variantModel = new \App\Models\ProductVariant();
        $cartVariantIds = array_filter(array_column($cartItems, 'variant_id'));

        foreach ($cartItems as $key => &$item) {
            $availableStock = (int)$item['available_stock'];
            // Auto-heal any existing cart item where quantity exceeds current available stock
            if ($availableStock > 0 && $item['quantity'] > $availableStock) {
                $item['quantity'] = $availableStock;
                $db->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?")->execute([$availableStock, $item['id']]);
            }
            $requiredMoq = (int)($item['moq_override'] ?? (!empty($item['category_moq']) ? $item['category_moq'] : 1));
            $item['moq'] = $requiredMoq;
            $item['is_out_of_stock'] = ($availableStock < $requiredMoq || $item['quantity'] > $availableStock);
            $item['is_max_stock_reached'] = ($item['quantity'] >= $availableStock && $availableStock > 0);
            $item['item_error'] = null;
            $item['item_notice'] = null;
            $item['alternative_variants'] = [];

            if ($item['is_out_of_stock'] || $item['is_max_stock_reached']) {
                if ($availableStock < $requiredMoq) {
                    $hasItemErrors = true;
                    $item['item_error'] = "Out of stock (Available: {$availableStock}, Min. Order: {$requiredMoq}).";
                } elseif ($item['quantity'] > $availableStock) {
                    $hasItemErrors = true;
                    $item['item_error'] = "You cannot order more than the available stock ({$availableStock}).";
                } elseif ($item['is_max_stock_reached']) {
                    $item['item_notice'] = "Max available inventory reached ({$availableStock} units).";
                }

                // Fetch alternative in-stock variants whenever out of stock OR max stock reached (excluding variants already in cart)
                $allV = $variantModel->getByProductId((int)$item['product_id']);
                foreach ($allV as $v) {
                    if ($v['id'] != $item['variant_id'] && !in_array($v['id'], $cartVariantIds) && ($v['status'] ?? 'active') === 'active' && ((int)$v['current_stock'] >= $requiredMoq)) {
                        $vBase = (float)($v['base_price'] ?? 0);
                        $vDisc = !empty($v['discount_price']) && (float)$v['discount_price'] > 0 ? (float)$v['discount_price'] : null;
                        $vEff = $vDisc ?? $vBase;
                        $item['alternative_variants'][] = [
                            'id' => (int)$v['id'],
                            'name' => $v['name'],
                            'price' => $vEff,
                            'formatted_price' => format_price($vEff),
                            'stock' => (int)$v['current_stock'],
                            'moq' => $requiredMoq
                        ];
                    }
                }
            }

            $gst = (float)($item['variant_total_gst'] ?? 0);
            if ($gst <= 0) $gst = (float)($item['product_total_gst'] ?? 0);
            if ($gst <= 0) $gst = (float)($item['category_sgst'] ?? 0) + (float)($item['category_cgst'] ?? 0);

            $basePrice = (!empty($item['variant_price']) && (float)$item['variant_price'] > 0) 
                ? (float)$item['variant_price'] 
                : (float)($item['wholesale_price'] ?? 0);
            $discountPrice = (!empty($item['variant_discount_price']) && (float)$item['variant_discount_price'] > 0) 
                ? (float)$item['variant_discount_price'] 
                : null;

            $unitPrice = $discountPrice ?? $basePrice;

            $item['unit_price'] = $unitPrice;
            $item['base_price'] = $basePrice;
            $item['discount_price'] = $discountPrice;
            $item['gst_rate'] = $gst;
            $itemTotal = round($unitPrice * $item['quantity'], 2);
            $subtotal += $itemTotal;
            $item['formatted_price'] = '₹' . format_price($itemTotal);
            $item['formatted_unit_price'] = '₹' . format_price($unitPrice);
            $item['formatted_base_price'] = ($discountPrice && $basePrice > $discountPrice) ? ('₹' . format_price($basePrice)) : null;
            $item['formatted_discount_price'] = $discountPrice ? ('₹' . format_price($discountPrice)) : null;
            
            if ($item['quantity'] < $requiredMoq) {
                $hasItemErrors = true;
                $item['item_error'] = ($item['item_error'] ? $item['item_error'] . ' ' : '') 
                    . "Minimum order quantity is {$requiredMoq} pieces.";
            }

            $catId = $item['category_id'];
            if (!isset($categoryTotals[$catId])) {
                $categoryTotals[$catId] = ['name' => $item['category_name'], 'total' => 0];
            }
            $categoryTotals[$catId]['total'] += $itemTotal;

            if (!empty($item['primary_image'])) {
                $item['primary_image'] = get_image_url($item['primary_image']);
            }
        }
        unset($item);
        
        $ruleValidation = $this->validateOrderRules($categoryTotals);
        $ruleErrorsText = array_map(function($err) {
            return strip_tags($err, '<a><strong><br><span><i>');
        }, $ruleValidation['errors']);
        
        $moqErrors = array_merge($moqErrors, $ruleErrorsText);

        $cartCategoryIds = [];
        foreach ($cartItems as $item) {
            if (!in_array($item['category_id'], $cartCategoryIds)) {
                $cartCategoryIds[] = $item['category_id'];
            }
        }

        $activeOffers = $this->getActiveOffersData($categoryTotals);
        $unlockedCatIds = [];
        foreach ($activeOffers as $off) {
            if ($off['is_unlocked']) {
                foreach ($off['secondary_offers'] as $so) {
                    $unlockedCatIds[] = $so['id'];
                }
            }
        }

        $suggestionCategoryIds = !empty($ruleValidation['failing_categories']) 
            ? $ruleValidation['failing_categories'] 
            : (!empty($unlockedCatIds) ? $unlockedCatIds : $cartCategoryIds);

        $relatedProducts = $includeRelated ? $this->getRelatedProducts($suggestionCategoryIds, $unlockedCatIds) : [];
        
        $suggestionUrl = BASE_URL . "/catalog";
        if (!empty($ruleValidation['failing_categories'])) {
            $suggestionUrl .= "?category_id=" . $ruleValidation['failing_categories'][0];
        } elseif (!empty($unlockedCatIds)) {
            $suggestionUrl .= "?category_id=" . $unlockedCatIds[0];
        }

        return [
            'success' => true,
            'items' => array_values($cartItems),
            'subtotal' => '₹' . format_price($subtotal),
            'count' => count($cartItems),
            'moqErrors' => $moqErrors,
            'hasItemErrors' => $hasItemErrors,
            'activeOffers' => $activeOffers,
            'relatedProducts' => $relatedProducts,
            'suggestionUrl' => $suggestionUrl
        ];
    }

    private function getRelatedProducts($cartCategoryIds, $unlockedCategoryIds = [])
    {
        $db = Database::getInstance();
        $relatedProducts = [];
        $catCondition = "";
        $params = [];
        if (!empty($cartCategoryIds)) {
            $inQuery = implode(',', array_fill(0, count($cartCategoryIds), '?'));
            $catCondition = "AND p.category_id IN ($inQuery)";
            $params = $cartCategoryIds;
        }

        $stmt = $db->prepare("
            SELECT p.id, p.name, p.wholesale_price, p.category_id, c.name as category_name, c.moq as category_moq, p.moq_override,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image,
                   (SELECT base_price FROM product_variants WHERE product_id = p.id AND status = 'active' ORDER BY id ASC LIMIT 1) as variant_price,
                   (SELECT discount_price FROM product_variants WHERE product_id = p.id AND status = 'active' ORDER BY id ASC LIMIT 1) as variant_discount_price
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active' $catCondition
            ORDER BY RAND() LIMIT 8
        ");
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        foreach ($products as $p) {
            $base = !empty($p['variant_price']) && (float)$p['variant_price'] > 0 ? (float)$p['variant_price'] : (float)$p['wholesale_price'];
            $disc = !empty($p['variant_discount_price']) && (float)$p['variant_discount_price'] > 0 ? (float)$p['variant_discount_price'] : null;
            $eff = $disc ?? $base;
            $moq = !empty($p['moq_override']) ? $p['moq_override'] : ($p['category_moq'] ?? 1);
            $isUnlocked = in_array($p['category_id'], $unlockedCategoryIds);

            $relatedProducts[] = [
                'id' => (int)$p['id'],
                'name' => $p['name'],
                'price' => $eff,
                'base_price' => $base,
                'primary_image' => !empty($p['primary_image']) ? get_image_url($p['primary_image']) : null,
                'category_name' => $p['category_name'],
                'moq' => (int)$moq,
                'is_unlocked_offer' => $isUnlocked
            ];
        }

        return $relatedProducts;
    }

    private function validateOrderRules(array $categoryTotals): array
    {
        return OrderRule::validateCartRules($categoryTotals);
    }

    private function getActiveOffersData(array $categoryTotals): array
    {
        $db = Database::getInstance();
        $offers = [];

        try {
            $ruleModel = new OrderRule();
            $rules = $ruleModel->getActiveRules();

            $catStmt = $db->query("SELECT id, name FROM categories");
            $catMap = $catStmt ? $catStmt->fetchAll(\PDO::FETCH_KEY_PAIR) : [];

            foreach ($rules as $rule) {
                $mainCatId = (int)$rule['category_id'];
                $minAmount = (float)$rule['min_amount'];
                $mainCatTotal = isset($categoryTotals[$mainCatId]) ? $categoryTotals[$mainCatId]['total'] : 0;
                $mainCatName = $rule['category_name'] ?? ($catMap[$mainCatId] ?? "Category #$mainCatId");

                $secondRules = json_decode($rule['second_category_rules'] ?? '[]', true) ?? [];

                $secondaries = [];
                foreach ($secondRules as $sId => $sMin) {
                    $sId = (int)$sId;
                    $sMin = (float)$sMin;
                    $sTotal = isset($categoryTotals[$sId]) ? $categoryTotals[$sId]['total'] : 0;
                    $secondaries[] = [
                        'id' => $sId,
                        'name' => $catMap[$sId] ?? "Category #$sId",
                        'special_min_amount' => $sMin,
                        'current_spent' => $sTotal,
                        'in_cart' => $sTotal > 0,
                        'is_met' => $sTotal >= $sMin,
                        'remaining' => max(0, $sMin - $sTotal)
                    ];
                }

                $isUnlocked = $mainCatTotal >= $minAmount;
                $offers[] = [
                    'rule_id' => $rule['id'],
                    'main_category_id' => $mainCatId,
                    'main_category_name' => $mainCatName,
                    'min_required' => $minAmount,
                    'main_spent' => $mainCatTotal,
                    'is_unlocked' => $isUnlocked,
                    'remaining' => max(0, $minAmount - $mainCatTotal),
                    'progress_percent' => min(100, round(($mainCatTotal / ($minAmount ?: 1)) * 100)),
                    'secondary_offers' => $secondaries
                ];
            }
        } catch (\Throwable $e) {
            // Silently fallback if table has any issues
        }

        return $offers;
    }
}