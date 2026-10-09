<?php
namespace App\Models;

use Core\Database;

/**
 * CancellationRule model
 *
 * Maps specific order statuses and payment methods to cancellation fees, 
 * refund percentages, and SLAs.
 */
class CancellationRule
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ---------------------------------------------------------------------------
    // READ
    // ---------------------------------------------------------------------------

    /** All rules ordered by payment method and status */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM cancellation_rules ORDER BY payment_method ASC, rule_name ASC"
        );
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM cancellation_rules WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // ---------------------------------------------------------------------------
    // WRITE
    // ---------------------------------------------------------------------------

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO cancellation_rules
                (rule_name, payment_method, cancellation_fee, refund_percentage, sla_days, is_active, created_by)
            VALUES
                (:rule_name, :payment_method, :cancellation_fee, :refund_percentage, :sla_days, :is_active, :created_by)
        ");
        $stmt->execute([
            'rule_name'         => $data['rule_name'],
            'payment_method'    => $data['payment_method'],
            'cancellation_fee'  => (float)($data['cancellation_fee'] ?? 0),
            'refund_percentage' => (int)($data['refund_percentage'] ?? 100),
            'sla_days'          => (int)($data['sla_days'] ?? 0),
            'is_active'         => (int)($data['is_active'] ?? 1),
            'created_by'        => $data['created_by'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE cancellation_rules
            SET rule_name         = :rule_name,
                payment_method    = :payment_method,
                cancellation_fee  = :cancellation_fee,
                refund_percentage = :refund_percentage,
                sla_days          = :sla_days,
                is_active         = :is_active
            WHERE id = :id
        ");
        return $stmt->execute([
            'id'                => $id,
            'rule_name'         => $data['rule_name'],
            'payment_method'    => $data['payment_method'],
            'cancellation_fee'  => (float)($data['cancellation_fee'] ?? 0),
            'refund_percentage' => (int)($data['refund_percentage'] ?? 100),
            'sla_days'          => (int)($data['sla_days'] ?? 0),
            'is_active'         => (int)($data['is_active'] ?? 1),
        ]);
    }

    public function toggleActive(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE cancellation_rules SET is_active = IF(is_active = 1, 0, 1) WHERE id = :id"
        );
        return $stmt->execute(['id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM cancellation_rules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // ---------------------------------------------------------------------------
    // Eligibility engine (used by Storefront RefundController)
    // ---------------------------------------------------------------------------

    /**
     * Look up the specific rule for an order's status and payment method.
     * Returns an array with financial/SLA metrics.
     */
    public function getRuleForOrder(array $order): array
    {
        // Normalize payment method to 'cod' or 'prepaid'
        $pm = strtolower($order['payment_method'] ?? '');
        $pmType = ($pm === 'cod') ? 'cod' : 'prepaid';
        
        $status = strtolower($order['status'] ?? 'pending');

        $stmt = $this->db->prepare("
            SELECT * FROM cancellation_rules 
            WHERE is_active = 1 
              AND payment_method = :pm 
              AND rule_name = :status
            LIMIT 1
        ");
        $stmt->execute([
            'pm'     => $pmType,
            'status' => $status
        ]);
        
        $rule = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($rule) {
            return [
                'eligible'          => true,
                'cancellation_fee'  => (float)$rule['cancellation_fee'],
                'refund_percentage' => (int)$rule['refund_percentage'],
                'sla_days'          => (int)$rule['sla_days'],
                'reason'            => ''
            ];
        }

        // Default behavior if no active rule maps to this status
        // Assume cancellation is allowed with 0 fee if not explicitly denied by a rule
        return [
            'eligible'          => true,
            'cancellation_fee'  => 0.00,
            'refund_percentage' => 100,
            'sla_days'          => 2,
            'reason'            => ''
        ];
    }
}
