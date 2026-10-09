<?php
namespace App\Models;

use Core\Model;
use PDO;

class VendorProfile extends Model
{
    public function create(array $data)
    {
        $sql = "
            INSERT INTO vendor_profiles (
                user_id, unique_vendor_id, store_name, company_name, vendor_type,
                gst_number, pan_number, no_gst_reason, address, city, state, pincode,
                bank_name, account_number, ifsc_code, account_holder_name, upi_id, upi_name, description,
                shop_images, gst_document
            ) VALUES (
                :user_id, :unique_vendor_id, :store_name, :company_name, :vendor_type,
                :gst_number, :pan_number, :no_gst_reason, :address, :city, :state, :pincode,
                :bank_name, :account_number, :ifsc_code, :account_holder_name, :upi_id, :upi_name, :description,
                :shop_images, :gst_document
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $data['user_id'],
            'unique_vendor_id' => $data['unique_vendor_id'] ?? null,
            'store_name' => $data['store_name'],
            'company_name' => $data['company_name'] ?? null,
            'vendor_type' => $data['vendor_type'] ?? 'wholesaler',
            'gst_number' => $data['gst_number'] ?? null,
            'pan_number' => $data['pan_number'] ?? null,
            'no_gst_reason' => $data['no_gst_reason'] ?? null,
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'bank_name' => $data['bank_name'] ?? null,
            'account_number' => $data['account_number'] ?? null,
            'ifsc_code' => $data['ifsc_code'] ?? null,
            'account_holder_name' => $data['account_holder_name'] ?? null,
            'upi_id' => $data['upi_id'] ?? null,
            'upi_name' => $data['upi_name'] ?? null,
            'description' => $data['description'] ?? null,
            'shop_images' => $data['shop_images'] ?? null,
            'gst_document' => $data['gst_document'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function findByUserId(int $userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM vendor_profiles WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }

    public function getByUserId(int $userId)
    {
        return $this->findByUserId($userId);
    }

    public function getAllVendorsBasic(): array
    {
        $stmt = $this->db->query("
            SELECT u.id as user_id, u.name, u.email, vp.store_name, vp.unique_vendor_id 
            FROM users u
            JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE u.role = 'vendor' AND u.status = 'active'
            ORDER BY vp.store_name ASC
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function findByVendorId(string $vendorId)
    {
        $stmt = $this->db->prepare("SELECT * FROM vendor_profiles WHERE unique_vendor_id = :vendor_id");
        $stmt->execute(['vendor_id' => $vendorId]);
        return $stmt->fetch();
    }

    public function createRegistrationRequest(int $userId)
    {
        $stmt = $this->db->prepare("
            INSERT INTO vendor_registration_requests (user_id, status)
            VALUES (:user_id, 'pending')
        ");
        return $stmt->execute(['user_id' => $userId]);
    }

    public function updateRequestStatus(int $userId, string $status, ?string $rejectionReason = null, ?int $reviewedBy = null)
    {
        $stmt = $this->db->prepare("
            UPDATE vendor_registration_requests
            SET status = :status,
                rejection_reason = :rejection_reason,
                reviewed_by = :reviewed_by,
                reviewed_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id
        ");
        return $stmt->execute([
            'status' => $status,
            'rejection_reason' => $rejectionReason,
            'reviewed_by' => $reviewedBy,
            'user_id' => $userId
        ]);
    }

    public function generateUniqueVendorId(): string
    {
        $stmt = $this->db->query("
            SELECT unique_vendor_id 
            FROM vendor_profiles 
            WHERE unique_vendor_id IS NOT NULL 
              AND unique_vendor_id != ''
        ");
        $allIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $maxNum = 0;
        foreach ($allIds as $vId) {
            if (preg_match('/^VN(\d+)$/i', $vId, $matches)) {
                $num = (int)$matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;
        return 'VN' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);
    }

    public function setUniqueVendorId(int $userId, string $vendorId)
    {
        $stmt = $this->db->prepare("UPDATE vendor_profiles SET unique_vendor_id = :vendor_id WHERE user_id = :user_id");
        $stmt->execute(['vendor_id' => $vendorId, 'user_id' => $userId]);

        $stmt2 = $this->db->prepare("UPDATE users SET unique_vendor_id = :vendor_id WHERE id = :user_id");
        return $stmt2->execute(['vendor_id' => $vendorId, 'user_id' => $userId]);
    }

    public function getAllVendorsRevenueSummary(?string $range = 'all'): array
    {
        $dateFilter = "";
        if ($range === '7') {
            $dateFilter = " AND o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        } elseif ($range === '30') {
            $dateFilter = " AND o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        } elseif ($range === 'this_month') {
            $dateFilter = " AND o.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')";
        }

        $sql = "
            SELECT 
                u.id as user_id,
                u.name as owner_name,
                u.email,
                u.phone,
                u.status as user_status,
                u.created_at as registered_at,
                vp.unique_vendor_id,
                vp.store_name,
                vp.company_name,
                vp.vendor_type,
                vp.city,
                vp.state,
                vp.gst_number,
                vp.pan_number,
                (SELECT COUNT(*) FROM products p WHERE p.vendor_id = u.id) as total_products,
                (SELECT COUNT(*) FROM products p WHERE p.vendor_id = u.id AND p.stock_quantity <= 5) as low_stock_count,
                COALESCE(SUM(CASE WHEN o.payment_status = 'paid' AND o.status NOT IN ('cancelled', 'refund_processing', 'refund_completed') THEN oi.total_price ELSE 0 END), 0) as gross_revenue,
                COALESCE(SUM(CASE WHEN o.payment_status = 'paid' AND o.status NOT IN ('cancelled', 'refund_processing', 'refund_completed') THEN oi.quantity ELSE 0 END), 0) as total_units_sold,
                COUNT(DISTINCT CASE WHEN o.payment_status = 'paid' AND o.status NOT IN ('cancelled', 'refund_processing', 'refund_completed') THEN o.id ELSE NULL END) as total_paid_orders,
                COUNT(DISTINCT CASE WHEN o.payment_status = 'paid' AND o.status IN ('placed', 'packed') THEN o.id ELSE NULL END) as pending_fulfillment_orders,
                COUNT(DISTINCT CASE WHEN o.payment_status = 'paid' AND o.status = 'delivered' THEN o.id ELSE NULL END) as delivered_orders,
                COUNT(DISTINCT CASE WHEN o.status = 'cancelled' THEN o.id ELSE NULL END) as cancelled_orders
            FROM users u
            JOIN vendor_profiles vp ON u.id = vp.user_id
            LEFT JOIN products p ON p.vendor_id = u.id
            LEFT JOIN order_items oi ON oi.product_id = p.id
            LEFT JOIN orders o ON oi.order_id = o.id {$dateFilter}
            WHERE u.role = 'vendor'
            GROUP BY u.id, u.name, u.email, u.phone, u.status, u.created_at, vp.unique_vendor_id, vp.store_name, vp.company_name, vp.vendor_type, vp.city, vp.state, vp.gst_number, vp.pan_number
            ORDER BY gross_revenue DESC, total_units_sold DESC
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getGlobalVendorMetrics(?string $range = 'all'): array
    {
        $dateFilter = "";
        if ($range === '7') {
            $dateFilter = " AND o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        } elseif ($range === '30') {
            $dateFilter = " AND o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        } elseif ($range === 'this_month') {
            $dateFilter = " AND o.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')";
        }

        // 1. Revenue & Sales
        $sqlSales = "
            SELECT 
                COALESCE(SUM(oi.total_price), 0) as total_revenue,
                COALESCE(SUM(oi.quantity), 0) as total_units_sold,
                COUNT(DISTINCT o.id) as total_orders
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE p.vendor_id IS NOT NULL 
              AND p.vendor_id > 0
              AND o.payment_status = 'paid'
              AND o.status NOT IN ('cancelled', 'refund_processing', 'refund_completed')
              {$dateFilter}
        ";
        $sales = $this->db->query($sqlSales)->fetch(\PDO::FETCH_ASSOC) ?: [];

        // 2. Orders Fulfillment breakdown
        $sqlOrders = "
            SELECT 
                COUNT(DISTINCT CASE WHEN o.status IN ('placed', 'packed') THEN o.id ELSE NULL END) as pending_fulfillment,
                COUNT(DISTINCT CASE WHEN o.status = 'delivered' THEN o.id ELSE NULL END) as delivered_orders,
                COUNT(DISTINCT CASE WHEN o.status = 'cancelled' THEN o.id ELSE NULL END) as cancelled_orders
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE p.vendor_id IS NOT NULL 
              AND p.vendor_id > 0
              AND o.payment_status = 'paid'
              {$dateFilter}
        ";
        $orderStats = $this->db->query($sqlOrders)->fetch(\PDO::FETCH_ASSOC) ?: [];

        // 3. Vendors & Products stats
        $stmtV = $this->db->query("
            SELECT 
                COUNT(*) as total_vendors,
                SUM(CASE WHEN status IN ('active', 'approved') THEN 1 ELSE 0 END) as active_vendors,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_vendors
            FROM users WHERE role = 'vendor'
        ");
        $vStats = $stmtV->fetch(\PDO::FETCH_ASSOC) ?: [];

        $stmtP = $this->db->query("
            SELECT 
                COUNT(*) as total_products,
                SUM(CASE WHEN stock_quantity <= 5 THEN 1 ELSE 0 END) as low_stock_products
            FROM products 
            WHERE vendor_id IS NOT NULL AND vendor_id > 0
        ");
        $pStats = $stmtP->fetch(\PDO::FETCH_ASSOC) ?: [];

        return [
            'total_revenue' => (float)($sales['total_revenue'] ?? 0),
            'total_units_sold' => (int)($sales['total_units_sold'] ?? 0),
            'total_orders' => (int)($sales['total_orders'] ?? 0),
            'pending_fulfillment' => (int)($orderStats['pending_fulfillment'] ?? 0),
            'delivered_orders' => (int)($orderStats['delivered_orders'] ?? 0),
            'cancelled_orders' => (int)($orderStats['cancelled_orders'] ?? 0),
            'total_vendors' => (int)($vStats['total_vendors'] ?? 0),
            'active_vendors' => (int)($vStats['active_vendors'] ?? 0),
            'pending_vendors' => (int)($vStats['pending_vendors'] ?? 0),
            'total_products' => (int)($pStats['total_products'] ?? 0),
            'low_stock_products' => (int)($pStats['low_stock_products'] ?? 0),
        ];
    }

    public function getTopRevenueVendors(int $limit = 5, ?string $range = 'all'): array
    {
        $dateFilter = "";
        if ($range === '7') {
            $dateFilter = " AND o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        } elseif ($range === '30') {
            $dateFilter = " AND o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        } elseif ($range === 'this_month') {
            $dateFilter = " AND o.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')";
        }

        $sql = "
            SELECT 
                vp.store_name,
                vp.unique_vendor_id,
                u.name as owner_name,
                COALESCE(SUM(oi.total_price), 0) as total_revenue,
                COALESCE(SUM(oi.quantity), 0) as units_sold,
                COUNT(DISTINCT o.id) as orders_count
            FROM vendor_profiles vp
            JOIN users u ON vp.user_id = u.id
            JOIN products p ON p.vendor_id = u.id
            JOIN order_items oi ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.payment_status = 'paid'
              AND o.status NOT IN ('cancelled', 'refund_processing', 'refund_completed')
              {$dateFilter}
            GROUP BY vp.store_name, vp.unique_vendor_id, u.name
            ORDER BY total_revenue DESC
            LIMIT :lim
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMonthlyVendorRevenueTrend(int $months = 6): array
    {
        $sql = "
            SELECT 
                DATE_FORMAT(o.created_at, '%b %Y') as month_label,
                DATE_FORMAT(o.created_at, '%Y-%m') as ym,
                COALESCE(SUM(oi.total_price), 0) as revenue,
                COALESCE(SUM(oi.quantity), 0) as units_sold
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE p.vendor_id IS NOT NULL AND p.vendor_id > 0
              AND o.payment_status = 'paid'
              AND o.status NOT IN ('cancelled', 'refund_processing', 'refund_completed')
              AND o.created_at >= DATE_SUB(NOW(), INTERVAL :months MONTH)
            GROUP BY ym, month_label
            ORDER BY ym ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':months', $months, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
