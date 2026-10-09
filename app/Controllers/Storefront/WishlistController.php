<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function __construct()
    {
        if (!Session::get('user_id') || Session::get('user_status') !== 'active') {
            $isAjax = false;
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $isAjax = true;
            } elseif (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $isAjax = true;
            } elseif (strpos($_SERVER['REQUEST_URI'] ?? '', 'toggle') !== false || strpos($_SERVER['REQUEST_URI'] ?? '', 'ajax') !== false) {
                $isAjax = true;
            }

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Please login to use wishlist']);
                exit;
            }
            Session::setFlash('error', 'Please login to access wishlist.');
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $userId = Session::get('user_id');
        $wishlistModel = new Wishlist();
        $items = $wishlistModel->getItems($userId);

        $this->render('storefront/wishlist', [
            'title' => 'My Wishlist',
            'items' => $items,
            'activeTab' => 'wishlist'
        ]);
    }

    public function toggle()
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $productId = $input['product_id'] ?? null;
        
        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'Invalid product.']);
            return;
        }

        $userId = Session::get('user_id');
        $wishlistModel = new Wishlist();
        
        $currentIds = $wishlistModel->getProductIds($userId);
        
        try {
            if (in_array($productId, $currentIds)) {
                $wishlistModel->removeItem($userId, $productId);
                echo json_encode(['success' => true, 'action' => 'removed']);
            } else {
                $wishlistModel->addItem($userId, $productId);
                echo json_encode(['success' => true, 'action' => 'added']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function ajaxGet()
    {
        header('Content-Type: application/json');
        
        $userId = Session::get('user_id');
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Please login to view wishlist']);
            return;
        }
        
        // Unblock session file so concurrent AJAX requests from the same user don't queue up
        session_write_close();

        $wishlistModel = new Wishlist();
        $items = $wishlistModel->getItems($userId);
        
        // Format for JSON
        $formattedItems = [];
        foreach ($items as $item) {
            $catGst = (float)($item['category_sgst'] ?? 0) + (float)($item['category_cgst'] ?? 0);
            $gstRate = $catGst > 0 ? $catGst : ((float)($item['total_gst'] ?? 0));
            
            $rawBase = (isset($item['base_price']) && (float)$item['base_price'] > 0) ? (float)$item['base_price'] : (float)($item['wholesale_price'] ?? 0);
            $rawDiscount = (!empty($item['discount_price']) && (float)$item['discount_price'] > 0) ? (float)$item['discount_price'] : null;
            
            $sellingPrice = $rawDiscount ?? $rawBase;
            $originalPrice = ($rawDiscount && $rawDiscount < $rawBase) ? $rawBase : null;
            $discPct = ($originalPrice && $originalPrice > $sellingPrice) ? round((($originalPrice - $sellingPrice) / $originalPrice) * 100) : 0;
            
            $moq = (int)($item['moq_override'] ?? $item['category_moq'] ?? 1);
            $totalStock = (int)($item['total_variant_stock'] ?? $item['stock_quantity'] ?? 0);
            $inStock = $totalStock >= $moq;
            
            $variantText = !empty($item['first_variant_attrs']) 
                ? $item['first_variant_attrs'] 
                : (!empty($item['first_variant_name']) ? $item['first_variant_name'] : (!empty($item['category_name']) ? $item['category_name'] : 'Standard'));

            $formattedItems[] = [
                'product_id'     => (int)$item['id'],
                'name'           => $item['name'],
                'price'          => '₹' . format_price($sellingPrice),
                'price_raw'      => $sellingPrice,
                'original_price' => $originalPrice ? ('₹' . format_price($originalPrice)) : null,
                'discount_pct'   => $discPct,
                'primary_image'  => !empty($item['primary_image']) ? get_image_url($item['primary_image']) : null,
                'moq'            => $moq,
                'in_stock'       => $inStock,
                'stock_qty'      => $totalStock,
                'variant_text'   => $variantText,
                'category_name'  => $item['category_name'] ?? '',
                'vendor_brand'   => $item['vendor_brand_name'] ?? ''
            ];
        }

        echo json_encode(['success' => true, 'items' => $formattedItems]);
    }
}
