<?php
namespace App\Models;

use Core\Model;
use PDO;

class Finance extends Model
{
    /**
     * Get system setting value with fallback
     */
    public function getSetting(string $key, $default = null)
    {
        $stmt = $this->db->prepare("SELECT key_value FROM system_settings WHERE key_name = :key LIMIT 1");
        $stmt->execute(['key' => $key]);
        $val = $stmt->fetchColumn();
        return ($val !== false && $val !== null) ? $val : $default;
    }

    /**
     * Update or insert system setting
     */
    public function updateSetting(string $key, string $value, ?string $description = null): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO system_settings (key_name, key_value, description)
            VALUES (:key, :val, :desc)
            ON DUPLICATE KEY UPDATE key_value = :val2, description = COALESCE(:desc2, description)
        ");
        return $stmt->execute([
            'key' => $key,
            'val' => $value,
            'desc' => $description,
            'val2' => $value,
            'desc2' => $description
        ]);
    }

    /**
     * Calculate effective commission percentage for an item based on 3-tier precedence:
     * 1. Product-specific commission override
     * 2. Vendor-specific commission override
     * 3. Category-specific commission override
     * 4. Global platform default commission
     */
    public function getEffectiveCommissionRate(int $productId, int $vendorId, ?int $categoryId = null): float
    {
        // Tier 1: Check Product override
        $stmt = $this->db->prepare("SELECT commission_rate, category_id FROM products WHERE id = :pid LIMIT 1");
        $stmt->execute(['pid' => $productId]);
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($prod && $prod['commission_rate'] !== null && $prod['commission_rate'] !== '') {
            return (float)$prod['commission_rate'];
        }
        if (!$categoryId && $prod && !empty($prod['category_id'])) {
            $categoryId = (int)$prod['category_id'];
        }

        // Tier 2: Check Vendor custom override
        $stmt = $this->db->prepare("SELECT commission_rate FROM vendor_profiles WHERE user_id = :vid LIMIT 1");
        $stmt->execute(['vid' => $vendorId]);
        $vComm = $stmt->fetchColumn();
        if ($vComm !== false && $vComm !== null && $vComm !== '') {
            return (float)$vComm;
        }

        // Tier 3: Check Category override
        if ($categoryId) {
            $stmt = $this->db->prepare("SELECT commission_rate FROM categories WHERE id = :cid LIMIT 1");
            $stmt->execute(['cid' => $categoryId]);
            $cComm = $stmt->fetchColumn();
            if ($cComm !== false && $cComm !== null && $cComm !== '') {
                return (float)$cComm;
            }
        }

        // Tier 4: Global Platform Default
        $global = $this->getSetting('default_commission_rate', '10.00');
        return (float)$global;
    }

    /**
     * Get complete financial overview for a specific vendor
     */
    public function getVendorFinancialSummary(int $vendorId): array
    {
        $globalMinWithdrawal = (float)$this->getSetting('min_withdrawal_amount', '500.00');
        $holdingDays = (int)$this->getSetting('vendor_revenue_holding_days', '7');
        
        // 1. Fetch all items for this vendor in paid non-cancelled orders
        $sql = "
            SELECT 
                oi.id as item_id,
                oi.order_id,
                oi.product_id,
                oi.variant_id,
                oi.quantity,
                oi.unit_price,
                oi.total_price as item_gross,
                o.order_number,
                o.status as order_status,
                o.payment_status,
                o.created_at as order_date,
                TIMESTAMPDIFF(DAY, o.created_at, NOW()) as order_age_days,
                p.category_id,
                p.total_gst as product_gst,
                p.sgst as product_sgst,
                p.cgst as product_cgst,
                p.commission_rate as product_comm,
                vp.commission_rate as vendor_comm,
                c.commission_rate as category_comm,
                c.sgst as category_sgst,
                c.cgst as category_cgst,
                pv.total_gst as variant_gst,
                pv.sgst as variant_sgst,
                pv.cgst as variant_cgst
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            JOIN products p ON oi.product_id = p.id
            JOIN vendor_profiles vp ON p.vendor_id = vp.user_id
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE p.vendor_id = :vid
              AND o.payment_status = 'paid'
              AND o.status NOT IN ('cancelled', 'refund_processing', 'refund_completed')
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['vid' => $vendorId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $defaultRate = (float)$this->getSetting('default_commission_rate', '10.00');

        $totalGross = 0.0;
        $totalCommission = 0.0;
        $totalNetDelivered = 0.0;
        $totalNetPendingOrders = 0.0;
        $totalHoldingGross = 0.0;
        $totalHoldingNet = 0.0;
        $totalHoldingAdmin = 0.0;
        $totalReleasedGross = 0.0;
        $totalReleasedNet = 0.0;

        foreach ($items as $item) {
            $gross = (float)$item['item_gross'];
            
            // Determine effective GST %
            $gst = 0.0;
            if (!empty($item['variant_gst']) && (float)$item['variant_gst'] > 0) {
                $gst = (float)$item['variant_gst'];
            } elseif (!empty($item['product_gst']) && (float)$item['product_gst'] > 0) {
                $gst = (float)$item['product_gst'];
            } elseif (((float)($item['product_sgst'] ?? 0) + (float)($item['product_cgst'] ?? 0)) > 0) {
                $gst = (float)$item['product_sgst'] + (float)$item['product_cgst'];
            } elseif (((float)($item['category_sgst'] ?? 0) + (float)($item['category_cgst'] ?? 0)) > 0) {
                $gst = (float)$item['category_sgst'] + (float)$item['category_cgst'];
            }

            if ($gst > 0) {
                $rate = $gst;
            } elseif ($item['product_comm'] !== null && $item['product_comm'] !== '') {
                $rate = (float)$item['product_comm'];
            } elseif ($item['vendor_comm'] !== null && $item['vendor_comm'] !== '') {
                $rate = (float)$item['vendor_comm'];
            } elseif ($item['category_comm'] !== null && $item['category_comm'] !== '') {
                $rate = (float)$item['category_comm'];
            } else {
                $rate = $defaultRate;
            }

            $adminShare = round($gross * ($rate / 100.0), 2);
            $vendorShare = round($gross - $adminShare, 2);

            $totalGross += $gross;
            $totalCommission += $adminShare;

            $ageDays = (int)($item['order_age_days'] ?? 0);
            $isMatured = ($holdingDays <= 0) || ($ageDays >= $holdingDays);

            if ($isMatured) {
                $totalReleasedGross += $gross;
                $totalReleasedNet += $vendorShare;
                if ($item['order_status'] === 'delivered') {
                    $totalNetDelivered += $vendorShare;
                } else {
                    $totalNetPendingOrders += $vendorShare;
                }
            } else {
                $totalHoldingGross += $gross;
                $totalHoldingNet += $vendorShare;
                $totalHoldingAdmin += $adminShare;
            }
        }

        // 2. Fetch completed withdrawals & pending withdrawal requests
        $stmtWithdraw = $this->db->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END), 0) as total_withdrawn,
                COALESCE(SUM(CASE WHEN status IN ('pending', 'processing') THEN amount ELSE 0 END), 0) as pending_withdrawals,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_requests_count
            FROM vendor_payout_requests
            WHERE vendor_id = :vid
        ");
        $stmtWithdraw->execute(['vid' => $vendorId]);
        $withdrawStats = $stmtWithdraw->fetch(PDO::FETCH_ASSOC);

        $totalWithdrawn = (float)($withdrawStats['total_withdrawn'] ?? 0);
        $pendingWithdrawals = (float)($withdrawStats['pending_withdrawals'] ?? 0);

        // Available balance = Released Net Delivered - (Completed Withdrawals + Pending/Locked Withdrawal Requests)
        $availableBalance = max(0.0, round($totalNetDelivered - ($totalWithdrawn + $pendingWithdrawals), 2));

        return [
            'vendor_id' => $vendorId,
            'holding_days' => $holdingDays,
            'total_gross_sales' => $totalReleasedGross,
            'all_time_gross_sales' => $totalGross,
            'total_holding_gross' => $totalHoldingGross,
            'total_holding_net' => $totalHoldingNet,
            'total_holding_admin_share' => $totalHoldingAdmin,
            'total_commission_fee' => $totalCommission,
            'total_admin_amount' => $totalCommission,
            'total_net_earnings' => round($totalReleasedNet, 2),
            'total_vendor_amount' => round($totalReleasedNet, 2),
            'all_time_net_earnings' => round($totalGross - $totalCommission, 2),
            'total_net_delivered' => $totalNetDelivered,
            'available_balance' => $availableBalance,
            'pending_orders_amount' => $totalNetPendingOrders,
            'total_withdrawn' => $totalWithdrawn,
            'pending_withdrawals' => $pendingWithdrawals,
            'pending_requests_count' => (int)($withdrawStats['pending_requests_count'] ?? 0),
            'min_withdrawal_limit' => $globalMinWithdrawal,
            'can_withdraw' => ($availableBalance >= $globalMinWithdrawal),
        ];
    }

    /**
     * Get platform-wide financial summary for Admin
     */
    public function getPlatformFinancialSummary(): array
    {
        $defaultRate = (float)$this->getSetting('default_commission_rate', '10.00');

        // 1. Calculate In-House Admin Sales (where product has vendor_id = 0 or NULL or not in active vendors)
        $sqlInhouse = "
            SELECT COALESCE(SUM(oi.total_price), 0) as inhouse_sales
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            JOIN products p ON oi.product_id = p.id
            LEFT JOIN users u ON p.vendor_id = u.id AND u.role = 'vendor' AND u.status = 'active'
            WHERE o.payment_status = 'paid'
              AND o.status NOT IN ('cancelled', 'refund_processing', 'refund_completed')
              AND (p.vendor_id = 0 OR p.vendor_id IS NULL OR u.id IS NULL)
        ";
        $inhouseSales = (float)$this->db->query($sqlInhouse)->fetchColumn();

        // 2. Aggregate Vendor Financial Summaries for all active registered vendors
        $vendorsList = $this->getAllVendorsFinancialList();
        
        $totalVendorGrossGMV = 0.0;
        $totalCommissionEarned = 0.0;
        $totalVendorDeliveredNet = 0.0;
        $totalVendorAvailable = 0.0;
        $totalPendingEscrow = 0.0;
        $totalDisbursed = 0.0;
        $pendingPayoutsAmount = 0.0;
        $pendingPayoutsCount = 0;

        foreach ($vendorsList as $v) {
            $totalVendorGrossGMV += (float)($v['total_gross_sales'] ?? 0);
            $totalCommissionEarned += (float)($v['total_commission_fee'] ?? 0);
            $totalVendorDeliveredNet += (float)($v['total_net_delivered'] ?? 0);
            $totalVendorAvailable += (float)($v['available_balance'] ?? 0);
            $totalPendingEscrow += (float)($v['pending_orders_amount'] ?? 0);
            $totalDisbursed += (float)($v['total_withdrawn'] ?? 0);
            $pendingPayoutsAmount += (float)($v['pending_withdrawals'] ?? 0);
            $pendingPayoutsCount += (int)($v['pending_requests_count'] ?? 0);
        }

        $overallPlatformGMV = round($inhouseSales + $totalVendorGrossGMV, 2);
        $adminTotalEarnings = round($inhouseSales + $totalCommissionEarned, 2);

        return [
            'total_gross_gmv' => round($totalVendorGrossGMV, 2),
            'total_commission_earned' => round($totalCommissionEarned, 2),
            'total_admin_amount' => round($totalCommissionEarned, 2),
            'total_vendor_delivered_net' => round($totalVendorDeliveredNet, 2),
            'total_vendor_amount' => round($totalVendorDeliveredNet, 2),
            'total_vendor_available' => round($totalVendorAvailable, 2),
            'total_pending_escrow' => round($totalPendingEscrow, 2),
            'total_disbursed' => round($totalDisbursed, 2),
            'pending_payouts_amount' => round($pendingPayoutsAmount, 2),
            'pending_payouts_count' => $pendingPayoutsCount,
            'inhouse_sales' => round($inhouseSales, 2),
            'overall_platform_gmv' => $overallPlatformGMV,
            'admin_total_earnings' => $adminTotalEarnings,
            'default_commission_rate' => $defaultRate,
            'min_withdrawal_limit' => (float)$this->getSetting('min_withdrawal_amount', '500.00'),
        ];
    }

    /**
     * Get detailed order-wise and item-wise financial breakdown for a vendor
     */
    public function getVendorOrdersBreakdown(int $vendorId, ?string $statusFilter = null): array
    {
        $defaultRate = (float)$this->getSetting('default_commission_rate', '10.00');
        $holdingDays = (int)$this->getSetting('vendor_revenue_holding_days', '7');

        $whereStatus = "";
        if ($statusFilter && $statusFilter !== 'all') {
            $whereStatus = " AND o.status = :order_status";
        }

        $sql = "
            SELECT 
                oi.id as item_id,
                oi.order_id,
                oi.product_id,
                oi.variant_id,
                oi.quantity,
                oi.unit_price,
                oi.total_price as item_gross,
                o.order_number,
                o.status as order_status,
                o.payment_status,
                o.created_at as order_date,
                TIMESTAMPDIFF(DAY, o.created_at, NOW()) as order_age_days,
                p.name as product_name,
                p.category_id,
                p.total_gst as product_gst,
                p.sgst as product_sgst,
                p.cgst as product_cgst,
                p.commission_rate as product_comm,
                vp.commission_rate as vendor_comm,
                c.name as category_name,
                c.commission_rate as category_comm,
                c.sgst as category_sgst,
                c.cgst as category_cgst,
                pv.name as variant_name,
                pv.total_gst as variant_gst,
                pv.sgst as variant_sgst,
                pv.cgst as variant_cgst,
                u.name as buyer_name,
                u.email as buyer_email
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            JOIN products p ON oi.product_id = p.id
            JOIN users u ON o.user_id = u.id
            JOIN vendor_profiles vp ON p.vendor_id = vp.user_id
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE p.vendor_id = :vid
              AND o.payment_status = 'paid'
              AND o.status NOT IN ('cancelled', 'refund_processing', 'refund_completed')
              {$whereStatus}
            ORDER BY o.created_at DESC, oi.id DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $params = ['vid' => $vendorId];
        if ($statusFilter && $statusFilter !== 'all') {
            $params['order_status'] = $statusFilter;
        }
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $gross = (float)$row['item_gross'];
            
            // Check GST first
            $gst = 0.0;
            if (!empty($row['variant_gst']) && (float)$row['variant_gst'] > 0) {
                $gst = (float)$row['variant_gst'];
            } elseif (!empty($row['product_gst']) && (float)$row['product_gst'] > 0) {
                $gst = (float)$row['product_gst'];
            } elseif (((float)($row['product_sgst'] ?? 0) + (float)($row['product_cgst'] ?? 0)) > 0) {
                $gst = (float)$row['product_sgst'] + (float)$row['product_cgst'];
            } elseif (((float)($row['category_sgst'] ?? 0) + (float)($row['category_cgst'] ?? 0)) > 0) {
                $gst = (float)$row['category_sgst'] + (float)$row['category_cgst'];
            }

            if ($gst > 0) {
                $rate = $gst;
                $tier = 'Product/Category GST (' . number_format($rate, 1) . '%)';
            } elseif ($row['product_comm'] !== null && $row['product_comm'] !== '') {
                $rate = (float)$row['product_comm'];
                $tier = 'Product Rate (' . number_format($rate, 1) . '%)';
            } elseif ($row['vendor_comm'] !== null && $row['vendor_comm'] !== '') {
                $rate = (float)$row['vendor_comm'];
                $tier = 'Vendor Rate (' . number_format($rate, 1) . '%)';
            } elseif ($row['category_comm'] !== null && $row['category_comm'] !== '') {
                $rate = (float)$row['category_comm'];
                $tier = 'Category Rate (' . number_format($rate, 1) . '%)';
            } else {
                $rate = $defaultRate;
                $tier = 'Standard GST / Fee (' . number_format($rate, 1) . '%)';
            }

            $adminShare = round($gross * ($rate / 100.0), 2);
            $vendorShare = round($gross - $adminShare, 2);

            $ageDays = (int)($row['order_age_days'] ?? 0);
            $isMatured = ($holdingDays <= 0) || ($ageDays >= $holdingDays);
            $remainingHoldingDays = max(0, $holdingDays - $ageDays);
            $releaseDate = date('d M Y', strtotime($row['order_date'] . " + {$holdingDays} days"));

            $row['gst_rate'] = $rate;
            $row['commission_rate'] = $rate;
            $row['commission_tier'] = $tier;
            $row['admin_amount'] = $adminShare;
            $row['commission_fee'] = $adminShare;
            $row['vendor_amount'] = $vendorShare;
            $row['net_amount'] = $vendorShare;
            $row['holding_days'] = $holdingDays;
            $row['order_age_days'] = $ageDays;
            $row['is_matured'] = $isMatured;
            $row['remaining_holding_days'] = $remainingHoldingDays;
            $row['release_date'] = $releaseDate;
            
            // Settlement status
            if (!$isMatured) {
                $row['settlement_status'] = 'holding_period'; // Maturing in X days
            } elseif ($row['order_status'] === 'delivered') {
                $row['settlement_status'] = 'settled_wallet'; // In available balance
            } else {
                $row['settlement_status'] = 'pending_escrow'; // In escrow until delivered
            }
        }

        return $rows;
    }

    /**
     * Get all vendor balances summary table for Admin
     */
    public function getAllVendorsFinancialList(): array
    {
        $stmt = $this->db->query("
            SELECT u.id, u.name, u.email, u.phone, u.status, u.unique_vendor_id, vp.store_name, vp.company_name, vp.commission_rate
            FROM users u
            JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE u.role = 'vendor' AND u.status = 'active'
            ORDER BY u.id ASC
        ");
        $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = [];
        foreach ($vendors as $v) {
            $summary = $this->getVendorFinancialSummary((int)$v['id']);
            $list[] = array_merge($v, $summary);
        }

        return $list;
    }

    /**
     * Submit a vendor withdrawal request
     */
    public function createWithdrawalRequest(int $vendorId, float $amount, array $bankDetails, ?string $vendorNotes = null): array
    {
        $summary = $this->getVendorFinancialSummary($vendorId);
        $adminLimit = (float)$summary['min_withdrawal_limit'];

        if ($adminLimit > 0 && $amount > $adminLimit) {
            return ['success' => false, 'error' => "Withdrawal amount cannot exceed the limit configured by Admin (₹" . number_format($adminLimit, 2) . "). You can withdraw up to ₹" . number_format($adminLimit, 2) . " max per request."];
        }

        if ($amount > $summary['available_balance']) {
            return ['success' => false, 'error' => "Requested amount exceeds your available withdrawable balance (₹" . number_format($summary['available_balance'], 2) . ")."];
        }

        $stmt = $this->db->query("SELECT MAX(id) FROM vendor_payout_requests");
        $nextId = ((int)$stmt->fetchColumn()) + 1;
        $payoutNumber = 'PAY-' . date('Y') . '-' . str_pad((string)$nextId, 5, '0', STR_PAD_LEFT);

        $stmt = $this->db->prepare("
            INSERT INTO vendor_payout_requests (
                payout_number, vendor_id, amount, fee_deducted, net_amount,
                payout_method, bank_name, account_number, ifsc_code,
                account_holder_name, upi_id, vendor_notes, status
            ) VALUES (
                :pnum, :vid, :amt, 0.00, :net,
                :pmethod, :bname, :acc_no, :ifsc,
                :holder, :upi, :notes, 'pending'
            )
        ");

        $success = $stmt->execute([
            'pnum' => $payoutNumber,
            'vid' => $vendorId,
            'amt' => $amount,
            'net' => $amount,
            'pmethod' => $bankDetails['payout_method'] ?? 'bank_transfer',
            'bname' => $bankDetails['bank_name'] ?? null,
            'acc_no' => $bankDetails['account_number'] ?? null,
            'ifsc' => $bankDetails['ifsc_code'] ?? null,
            'holder' => $bankDetails['account_holder_name'] ?? null,
            'upi' => $bankDetails['upi_id'] ?? null,
            'notes' => $vendorNotes
        ]);

        if ($success) {
            $payoutId = (int)$this->db->lastInsertId();
            
            // Log pending transaction in ledger
            $txnNum = 'TXN-' . date('Y') . '-' . str_pad((string)$payoutId, 5, '0', STR_PAD_LEFT);
            $newBal = round($summary['available_balance'] - $amount, 2);
            $stmtTxn = $this->db->prepare("
                INSERT INTO vendor_transactions (
                    transaction_number, vendor_id, payout_request_id,
                    type, gross_amount, net_amount, balance_after,
                    status, description
                ) VALUES (
                    :txnum, :vid, :pid,
                    'payout_withdrawal', :gross_amt, :net_amt, :bal,
                    'pending', :desc
                )
            ");
            $stmtTxn->execute([
                'txnum' => $txnNum,
                'vid' => $vendorId,
                'pid' => $payoutId,
                'gross_amt' => $amount,
                'net_amt' => $amount,
                'bal' => $newBal,
                'desc' => "Withdrawal request submitted: {$payoutNumber}"
            ]);

            return ['success' => true, 'payout_number' => $payoutNumber, 'payout_id' => $payoutId];
        }

        return ['success' => false, 'error' => 'Database error submitting withdrawal request.'];
    }

    /**
     * Admin processes a vendor withdrawal request (Approve & Enter UTR, or Reject)
     */
    public function processWithdrawalRequest(int $payoutId, string $action, ?string $reference = null, ?string $adminNotes = null, ?int $adminId = null): array
    {
        $stmt = $this->db->prepare("SELECT * FROM vendor_payout_requests WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $payoutId]);
        $payout = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payout) {
            return ['success' => false, 'error' => 'Withdrawal request not found.'];
        }

        if ($payout['status'] === 'completed') {
            return ['success' => false, 'error' => 'This withdrawal request has already been completed.'];
        }

        if ($action === 'approve') {
            if (empty($reference)) {
                return ['success' => false, 'error' => 'Please provide a Transaction Reference / UTR Number for this payout.'];
            }

            $stmt = $this->db->prepare("
                UPDATE vendor_payout_requests 
                SET status = 'completed',
                    transaction_reference = :ref,
                    admin_notes = :notes,
                    processed_by = :admin_id,
                    processed_at = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                'ref' => trim($reference),
                'notes' => $adminNotes,
                'admin_id' => $adminId,
                'id' => $payoutId
            ]);

            // Update ledger status to cleared
            $this->db->prepare("
                UPDATE vendor_transactions 
                SET status = 'cleared', description = CONCAT(description, ' - Completed UTR: ', :ref)
                WHERE payout_request_id = :pid
            ")->execute(['ref' => trim($reference), 'pid' => $payoutId]);

            return ['success' => true, 'message' => "Payout {$payout['payout_number']} marked as completed! UTR: {$reference}"];

        } elseif ($action === 'reject') {
            $stmt = $this->db->prepare("
                UPDATE vendor_payout_requests 
                SET status = 'rejected',
                    admin_notes = :notes,
                    processed_by = :admin_id,
                    processed_at = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                'notes' => $adminNotes ?: 'Rejected by administrator',
                'admin_id' => $adminId,
                'id' => $payoutId
            ]);

            // Update ledger status to cancelled & create refund adjustment
            $this->db->prepare("UPDATE vendor_transactions SET status = 'cancelled' WHERE payout_request_id = :pid")->execute(['pid' => $payoutId]);

            $vendorId = (int)$payout['vendor_id'];
            $summary = $this->getVendorFinancialSummary($vendorId);
            $stmtRefund = $this->db->prepare("
                INSERT INTO vendor_transactions (
                    transaction_number, vendor_id, payout_request_id,
                    type, gross_amount, net_amount, balance_after,
                    status, description
                ) VALUES (
                    :txnum, :vid, :pid,
                    'payout_refund', :gross_amt, :net_amt, :bal,
                    'cleared', :desc
                )
            ");
            $stmtRefund->execute([
                'txnum' => 'TXN-REF-' . time() . '-' . $payoutId,
                'vid' => $vendorId,
                'pid' => $payoutId,
                'gross_amt' => $payout['amount'],
                'net_amt' => $payout['amount'],
                'bal' => $summary['available_balance'],
                'desc' => "Withdrawal rejected: {$payout['payout_number']} - Funds returned to wallet. Reason: {$adminNotes}"
            ]);

            return ['success' => true, 'message' => "Payout {$payout['payout_number']} has been rejected. Funds returned to vendor wallet."];
        }

        return ['success' => false, 'error' => 'Invalid action specified.'];
    }

    /**
     * Get list of withdrawal requests
     */
    public function getWithdrawalsList(?int $vendorId = null, ?string $statusFilter = null): array
    {
        $where = [];
        $params = [];

        if ($vendorId !== null) {
            $where[] = "pr.vendor_id = :vid";
            $params['vid'] = $vendorId;
        }

        if ($statusFilter && $statusFilter !== 'all') {
            $where[] = "pr.status = :status";
            $params['status'] = $statusFilter;
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        $sql = "
            SELECT 
                pr.*,
                COALESCE(pr.bank_name, vp.bank_name) as bank_name,
                COALESCE(pr.account_number, vp.account_number) as account_number,
                COALESCE(pr.ifsc_code, vp.ifsc_code) as ifsc_code,
                COALESCE(pr.account_holder_name, vp.account_holder_name) as account_holder_name,
                u.name as vendor_name,
                u.email as vendor_email,
                u.phone as vendor_phone,
                u.unique_vendor_id,
                vp.store_name,
                vp.company_name,
                admin_u.name as processed_by_name
            FROM vendor_payout_requests pr
            JOIN users u ON pr.vendor_id = u.id
            JOIN vendor_profiles vp ON u.id = vp.user_id
            LEFT JOIN users admin_u ON pr.processed_by = admin_u.id
            {$whereClause}
            ORDER BY pr.created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get transaction ledger history
     */
    public function getTransactionsList(?int $vendorId = null, int $limit = 100): array
    {
        $whereClause = $vendorId !== null ? "WHERE vt.vendor_id = :vid" : "";
        $params = $vendorId !== null ? ['vid' => $vendorId] : [];

        $sql = "
            SELECT 
                vt.*,
                u.name as vendor_name,
                u.unique_vendor_id,
                vp.store_name
            FROM vendor_transactions vt
            JOIN users u ON vt.vendor_id = u.id
            JOIN vendor_profiles vp ON u.id = vp.user_id
            {$whereClause}
            ORDER BY vt.created_at DESC, vt.id DESC
            LIMIT {$limit}
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update individual vendor commission rate override
     */
    public function updateVendorCommission(int $vendorId, ?float $commissionRate): bool
    {
        $stmt = $this->db->prepare("UPDATE vendor_profiles SET commission_rate = :comm WHERE user_id = :vid");
        return $stmt->execute([
            'comm' => ($commissionRate !== null && $commissionRate >= 0) ? $commissionRate : null,
            'vid' => $vendorId
        ]);
    }

    /**
     * Update category commission rate override
     */
    public function updateCategoryCommission(int $categoryId, ?float $commissionRate): bool
    {
        $stmt = $this->db->prepare("UPDATE categories SET commission_rate = :comm WHERE id = :cid");
        return $stmt->execute([
            'comm' => ($commissionRate !== null && $commissionRate >= 0) ? $commissionRate : null,
            'cid' => $categoryId
        ]);
    }

    /**
     * Update product commission rate override
     */
    public function updateProductCommission(int $productId, ?float $commissionRate): bool
    {
        $stmt = $this->db->prepare("UPDATE products SET commission_rate = :comm WHERE id = :pid");
        return $stmt->execute([
            'comm' => ($commissionRate !== null && $commissionRate >= 0) ? $commissionRate : null,
            'pid' => $productId
        ]);
    }

    /**
     * Get all categories with commission rates
     */
    public function getAllCategoriesWithCommission(): array
    {
        $stmt = $this->db->query("
            SELECT id, name, commission_rate,
                   (SELECT COUNT(*) FROM products WHERE category_id = categories.id) as product_count
            FROM categories 
            ORDER BY name ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all vendors with their custom commission rates
     */
    public function getAllVendorsWithCommission(): array
    {
        $stmt = $this->db->query("
            SELECT u.id, u.unique_vendor_id, u.name, u.email, u.phone, u.status,
                   vp.store_name, vp.company_name, vp.commission_rate
            FROM users u
            JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE u.role = 'vendor' AND u.status IN ('active', 'approved', 'blocked')
            ORDER BY u.id ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all products with commission overrides
     */
    public function getProductsWithCommission(?int $vendorId = null): array
    {
        $where = $vendorId ? "WHERE p.vendor_id = " . (int)$vendorId : "";
        $stmt = $this->db->query("
            SELECT p.id, p.name, p.wholesale_price, p.commission_rate as product_comm,
                   c.name as category_name, c.commission_rate as category_comm,
                   vp.store_name, vp.commission_rate as vendor_comm
            FROM products p
            JOIN vendor_profiles vp ON p.vendor_id = vp.user_id
            LEFT JOIN categories c ON p.category_id = c.id
            {$where}
            ORDER BY p.name ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get count of pending payout requests
     */
    public function getPendingPayoutCount(?int $vendorId = null): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM vendor_payout_requests WHERE status = 'pending'";
            if ($vendorId) {
                $sql .= " AND vendor_id = " . (int)$vendorId;
            }
            return (int)$this->db->query($sql)->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }
}
