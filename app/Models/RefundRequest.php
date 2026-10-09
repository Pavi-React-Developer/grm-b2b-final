<?php
namespace App\Models;

use Core\Model;

/**
 * RefundRequest Model
 * Handles all DB operations for the refund_requests table.
 */
class RefundRequest extends Model
{
    // ---------------------------------------------------------------------------
    // CREATE
    // ---------------------------------------------------------------------------

    /**
     * Insert a new refund request row.
     *
     * @param array $data Keys: order_id, user_id, amount, reason,
     *                    cancellation_type, cashfree_order_id (optional)
     * @return int  The new row ID
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO refund_requests
                (order_id, user_id, amount, reason, cancellation_type,
                 cashfree_order_id, refund_method, refund_details, status, created_at, updated_at)
            VALUES
                (:order_id, :user_id, :amount, :reason, :cancellation_type,
                 :cashfree_order_id, :refund_method, :refund_details, 'Pending_Admin_Approval', NOW(), NOW())
        ");

        $stmt->execute([
            'order_id'          => $data['order_id'],
            'user_id'           => $data['user_id'],
            'amount'            => $data['amount'],
            'reason'            => $data['reason'],
            'cancellation_type' => $data['cancellation_type'] ?? 'Full',
            'cashfree_order_id' => $data['cashfree_order_id'] ?? null,
            'refund_method'     => $data['refund_method'] ?? null,
            'refund_details'    => $data['refund_details'] ?? null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    // ---------------------------------------------------------------------------
    // READ
    // ---------------------------------------------------------------------------

    /**
     * Find a single refund request by its primary key.
     * Joins order and user info for convenience.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT rr.*,
                   o.order_number, o.grand_total, o.cashfree_order_id AS order_cashfree_id,
                   o.razorpay_order_id AS order_razorpay_id, o.razorpay_payment_id AS order_razorpay_payment_id,
                   u.name AS user_name, u.email AS user_email, u.phone AS user_phone
            FROM   refund_requests rr
            JOIN   orders o ON rr.order_id = o.id
            JOIN   users  u ON rr.user_id  = u.id
            WHERE  rr.id = :id
            LIMIT  1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Check if a refund request already exists for an order.
     */
    public function findByOrderId(int $orderId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM refund_requests
            WHERE order_id = :order_id
            LIMIT 1
        ");
        $stmt->execute(['order_id' => $orderId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Paginated list of refund requests for the admin panel.
     *
     * @param int         $page    1-indexed page number
     * @param int         $perPage Items per page
     * @param string|null $status  Filter by status (null = all)
     * @return array  ['data' => [...], 'total' => int, 'pages' => int]
     */
    public function getAllWithPagination(int $page = 1, int $perPage = 15, ?string $status = null): array
    {
        $page    = max(1, $page);
        $offset  = ($page - 1) * $perPage;

        $whereClause = '';
        $params      = [];

        if ($status && $status !== 'all') {
            $whereClause = 'WHERE rr.status = :status';
            $params['status'] = $status;
        }

        // Total count
        $countSql = "
            SELECT COUNT(*) FROM refund_requests rr
            $whereClause
        ";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Data query
        $params['limit']  = $perPage;
        $params['offset'] = $offset;

        $dataSql = "
            SELECT rr.*,
                   o.order_number, o.grand_total,
                   o.cashfree_order_id AS order_cashfree_id,
                   o.razorpay_order_id AS order_razorpay_id,
                   o.razorpay_payment_id AS order_razorpay_payment_id,
                   u.name  AS user_name,
                   u.email AS user_email,
                   u.phone AS user_phone
            FROM   refund_requests rr
            JOIN   orders o ON rr.order_id = o.id
            JOIN   users  u ON rr.user_id  = u.id
            $whereClause
            ORDER  BY rr.created_at DESC
            LIMIT  :limit OFFSET :offset
        ";

        $dataStmt = $this->db->prepare($dataSql);

        // Bind individually so PDO handles LIMIT/OFFSET as integers
        if ($status && $status !== 'all') {
            $dataStmt->bindValue(':status', $status, \PDO::PARAM_STR);
        }
        $dataStmt->bindValue(':limit',  $perPage, \PDO::PARAM_INT);
        $dataStmt->bindValue(':offset', $offset,  \PDO::PARAM_INT);
        $dataStmt->execute();

        $data = $dataStmt->fetchAll(\PDO::FETCH_ASSOC);

        return [
            'data'    => $data,
            'total'   => $total,
            'pages'   => (int)ceil($total / $perPage),
            'current' => $page,
        ];
    }

    /**
     * Count requests grouped by status (for admin dashboard badges).
     */
    public function getStatusCounts(): array
    {
        $stmt = $this->db->query("
            SELECT status, COUNT(*) AS cnt
            FROM refund_requests
            GROUP BY status
        ");
        $rows   = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $counts = [
            'all'                    => 0,
            'Pending_Admin_Approval' => 0,
            'Processing'             => 0,
            'Completed'              => 0,
            'Rejected'               => 0,
        ];
        $map = [
            'pending_admin_approval' => 'Pending_Admin_Approval',
            'processing'             => 'Processing',
            'completed'              => 'Completed',
            'rejected'               => 'Rejected',
        ];
        foreach ($rows as $row) {
            $normalizedStatus = $map[strtolower($row['status'])] ?? $row['status'];
            if (array_key_exists($normalizedStatus, $counts)) {
                $counts[$normalizedStatus] += (int)$row['cnt'];
            }
            $counts['all'] += (int)$row['cnt'];
        }
        return $counts;
    }

    // ---------------------------------------------------------------------------
    // UPDATE
    // ---------------------------------------------------------------------------

    /**
     * Update the status and optional extra fields of a refund request.
     *
     * @param int    $id     Refund request ID
     * @param string $status New status value
     * @param array  $extra  Optional: cashfree_refund_id, admin_notes, processed_by
     */
    public function updateStatus(int $id, string $status, array $extra = []): bool
    {
        $sets   = ['status = :status', 'updated_at = NOW()'];
        $params = ['id' => $id, 'status' => $status];

        if (!empty($extra['razorpay_refund_id'])) {
            $sets[]                       = 'razorpay_refund_id = :rzprid';
            $params['rzprid']             = $extra['razorpay_refund_id'];
        }
        if (!empty($extra['cashfree_refund_id'])) {
            $sets[]                       = 'cashfree_refund_id = :cfrid';
            $params['cfrid']              = $extra['cashfree_refund_id'];
        }
        if (!empty($extra['admin_notes'])) {
            $sets[]                = 'admin_notes = :notes';
            $params['notes']       = $extra['admin_notes'];
        }
        if (!empty($extra['processed_by'])) {
            $sets[]                    = 'processed_by = :pb';
            $sets[]                    = 'processed_at = NOW()';
            $params['pb']              = $extra['processed_by'];
        }

        $sql  = 'UPDATE refund_requests SET ' . implode(', ', $sets) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // ---------------------------------------------------------------------------
    // Cancellation-module specific queries
    // ---------------------------------------------------------------------------

    /**
     * Paginated list filtered to a fixed set of statuses.
     * Used by the Refunds sub-page (approved/processing/completed).
     *
     * @param array $statuses e.g. ['processing','completed']
     */
    public function getPaginatedByStatuses(array $statuses, int $page = 1, int $perPage = 15): array
    {
        $page   = max(1, $page);
        $offset = ($page - 1) * $perPage;

        if (empty($statuses)) {
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current' => 1];
        }

        $namedPlaceholders = [];
        foreach ($statuses as $i => $status) {
            $namedPlaceholders[] = ":status_$i";
        }
        $placeholders = implode(',', $namedPlaceholders);

        // Count
        $countStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM refund_requests WHERE status IN ($placeholders)"
        );
        foreach ($statuses as $i => $status) {
            $countStmt->bindValue(":status_$i", $status, \PDO::PARAM_STR);
        }
        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();

        // Data — bind LIMIT/OFFSET as INT explicitly
        $dataStmt = $this->db->prepare("
            SELECT rr.*,
                   o.order_number, o.grand_total,
                   o.cashfree_order_id AS order_cashfree_id,
                   o.razorpay_order_id AS order_razorpay_id,
                   o.razorpay_payment_id AS order_razorpay_payment_id,
                   u.name  AS user_name,
                   u.email AS user_email,
                   u.phone AS user_phone
            FROM   refund_requests rr
            JOIN   orders o ON rr.order_id = o.id
            JOIN   users  u ON rr.user_id  = u.id
            WHERE  rr.status IN ($placeholders)
            ORDER  BY rr.updated_at DESC
            LIMIT  :limit OFFSET :offset
        ");

        foreach ($statuses as $i => $status) {
            $dataStmt->bindValue(":status_$i", $status, \PDO::PARAM_STR);
        }
        $dataStmt->bindValue(':limit',  $perPage, \PDO::PARAM_INT);
        $dataStmt->bindValue(':offset', $offset,  \PDO::PARAM_INT);
        $dataStmt->execute();

        return [
            'data'    => $dataStmt->fetchAll(\PDO::FETCH_ASSOC),
            'total'   => $total,
            'pages'   => (int)ceil($total / $perPage),
            'current' => $page,
        ];
    }


    /**
     * Count rows per status for the Cancellations dashboard badges.
     * Returns: pending_admin_approval, processing, completed, rejected, total
     */
    public function getCancellationStatusCounts(): array
    {
        $stmt = $this->db->query("
            SELECT status, COUNT(*) AS cnt FROM refund_requests GROUP BY status
        ");
        $rows   = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $counts = [
            'Pending_Admin_Approval' => 0,
            'Processing'             => 0,
            'Completed'              => 0,
            'Rejected'               => 0,
            'total'                  => 0,
        ];
        $map = [
            'pending_admin_approval' => 'Pending_Admin_Approval',
            'processing'             => 'Processing',
            'completed'              => 'Completed',
            'rejected'               => 'Rejected',
        ];
        foreach ($rows as $r) {
            $normalizedStatus = $map[strtolower($r['status'])] ?? $r['status'];
            if (array_key_exists($normalizedStatus, $counts)) {
                $counts[$normalizedStatus] += (int)$r['cnt'];
            }
            $counts['total'] += (int)$r['cnt'];
        }
        return $counts;
    }

    /**
     * Get sum of amounts per status for the Cancellations dashboard badges.
     */
    public function getCancellationStatusAmounts(): array
    {
        $stmt = $this->db->query("
            SELECT status, SUM(amount) AS sum_amt FROM refund_requests GROUP BY status
        ");
        $rows   = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $amounts = [
            'Pending_Admin_Approval' => 0.0,
            'Processing'             => 0.0,
            'Completed'              => 0.0,
            'Rejected'               => 0.0,
            'total'                  => 0.0,
        ];
        $map = [
            'pending_admin_approval' => 'Pending_Admin_Approval',
            'processing'             => 'Processing',
            'completed'              => 'Completed',
            'rejected'               => 'Rejected',
        ];
        foreach ($rows as $r) {
            $normalizedStatus = $map[strtolower($r['status'])] ?? $r['status'];
            if (array_key_exists($normalizedStatus, $amounts)) {
                $amounts[$normalizedStatus] += (float)$r['sum_amt'];
            }
            $amounts['total'] += (float)$r['sum_amt'];
        }
        return $amounts;
    }
}

