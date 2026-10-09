<?php
namespace App\Models;

use Core\Model;

class SupportTicket extends Model
{
    /**
     * Create a new support ticket
     */
    public function createTicket(int $vendorId, array $data, ?string $attachment = null): string
    {
        $ticketNumber = 'TKT-' . date('Y') . '-' . str_pad((string)mt_rand(1000, 99999), 5, '0', STR_PAD_LEFT);

        $stmt = $this->db->prepare("
            INSERT INTO support_tickets (
                ticket_number, vendor_id, subject, category, priority, status, 
                message, attachment, last_reply_by
            ) VALUES (
                :ticket_number, :vendor_id, :subject, :category, :priority, 'open', 
                :message, :attachment, 'vendor'
            )
        ");

        $stmt->execute([
            'ticket_number' => $ticketNumber,
            'vendor_id'     => $vendorId,
            'subject'       => trim($data['subject'] ?? 'General Query'),
            'category'      => trim($data['category'] ?? 'General Inquiry'),
            'priority'      => in_array(strtolower($data['priority'] ?? ''), ['high', 'medium', 'low']) ? strtolower($data['priority']) : 'medium',
            'message'       => trim($data['message'] ?? ''),
            'attachment'    => $attachment
        ]);

        $ticketId = (int)$this->db->lastInsertId();

        // Also add the first message into support_ticket_messages
        $stmtMsg = $this->db->prepare("
            INSERT INTO support_ticket_messages (
                ticket_id, sender_id, sender_role, message, attachment
            ) VALUES (
                :ticket_id, :sender_id, 'vendor', :message, :attachment
            )
        ");
        $stmtMsg->execute([
            'ticket_id'  => $ticketId,
            'sender_id'  => $vendorId,
            'message'    => trim($data['message'] ?? ''),
            'attachment' => $attachment
        ]);

        return $ticketNumber;
    }

    /**
     * Get tickets for a specific vendor
     */
    public function getVendorTickets(int $vendorId, ?string $status = null, ?string $priority = null): array
    {
        $sql = "
            SELECT 
                st.*,
                (SELECT COUNT(*) FROM support_ticket_messages WHERE ticket_id = st.id) as message_count,
                (SELECT MAX(created_at) FROM support_ticket_messages WHERE ticket_id = st.id) as last_activity_at
            FROM support_tickets st
            WHERE st.vendor_id = :vendor_id
        ";
        $params = ['vendor_id' => $vendorId];

        if ($status && $status !== 'all') {
            $sql .= " AND st.status = :status";
            $params['status'] = $status;
        }

        if ($priority && $priority !== 'all') {
            $sql .= " AND st.priority = :priority";
            $params['priority'] = $priority;
        }

        $sql .= " ORDER BY FIELD(st.status, 'open', 'in_progress', 'resolved', 'closed'), st.updated_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Get all tickets across all vendors (Admin view)
     */
    public function getAllTickets(?string $status = null, ?string $priority = null, ?int $vendorId = null, ?string $search = null): array
    {
        $sql = "
            SELECT 
                st.*,
                u.name as vendor_name,
                u.email as vendor_email,
                u.phone as vendor_phone,
                vp.store_name as vendor_store_name,
                vp.unique_vendor_id,
                (SELECT COUNT(*) FROM support_ticket_messages WHERE ticket_id = st.id) as message_count,
                (SELECT MAX(created_at) FROM support_ticket_messages WHERE ticket_id = st.id) as last_activity_at
            FROM support_tickets st
            JOIN users u ON st.vendor_id = u.id
            LEFT JOIN vendor_profiles vp ON st.vendor_id = vp.user_id
            WHERE 1=1
        ";
        $params = [];

        if ($vendorId) {
            $sql .= " AND st.vendor_id = :vendor_id";
            $params['vendor_id'] = $vendorId;
        }

        if ($status && $status !== 'all') {
            $sql .= " AND st.status = :status";
            $params['status'] = $status;
        }

        if ($priority && $priority !== 'all') {
            $sql .= " AND st.priority = :priority";
            $params['priority'] = $priority;
        }

        if ($search) {
            $sql .= " AND (st.ticket_number LIKE :search OR st.subject LIKE :search OR st.category LIKE :search OR u.name LIKE :search OR vp.store_name LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY FIELD(st.status, 'open', 'in_progress', 'resolved', 'closed'), FIELD(st.priority, 'high', 'medium', 'low'), st.updated_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Get a single ticket by ID
     */
    public function getTicketById(int $ticketId, ?int $vendorId = null): ?array
    {
        $sql = "
            SELECT 
                st.*,
                u.name as vendor_name,
                u.email as vendor_email,
                u.phone as vendor_phone,
                vp.store_name as vendor_store_name,
                vp.company_name as vendor_company_name,
                vp.unique_vendor_id
            FROM support_tickets st
            JOIN users u ON st.vendor_id = u.id
            LEFT JOIN vendor_profiles vp ON st.vendor_id = vp.user_id
            WHERE st.id = :id
        ";
        $params = ['id' => $ticketId];

        if ($vendorId) {
            $sql .= " AND st.vendor_id = :vendor_id";
            $params['vendor_id'] = $vendorId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get all conversation messages for a ticket
     */
    public function getMessages(int $ticketId): array
    {
        $sql = "
            SELECT 
                stm.*,
                u.name as sender_name,
                u.email as sender_email,
                u.role as sender_user_role
            FROM support_ticket_messages stm
            JOIN users u ON stm.sender_id = u.id
            WHERE stm.ticket_id = ?
            ORDER BY stm.created_at ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Add a message / reply to a ticket
     */
    public function addReply(int $ticketId, int $senderId, string $senderRole, string $message, ?string $attachment = null): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO support_ticket_messages (
                ticket_id, sender_id, sender_role, message, attachment
            ) VALUES (
                :ticket_id, :sender_id, :sender_role, :message, :attachment
            )
        ");
        $stmt->execute([
            'ticket_id'   => $ticketId,
            'sender_id'   => $senderId,
            'sender_role' => in_array($senderRole, ['vendor', 'admin', 'super_admin', 'staff']) ? $senderRole : 'vendor',
            'message'     => trim($message),
            'attachment'  => $attachment
        ]);

        $msgId = (int)$this->db->lastInsertId();

        // Update ticket's last_reply_by and updated_at
        $replyBy = ($senderRole === 'vendor') ? 'vendor' : 'admin';
        
        $statusUpdateSql = "";
        if ($replyBy === 'admin') {
            // When admin replies to open ticket, move to in_progress
            $statusUpdateSql = ", status = IF(status = 'open', 'in_progress', status)";
        }

        $stmtTicket = $this->db->prepare("
            UPDATE support_tickets 
            SET last_reply_by = :reply_by, updated_at = NOW() {$statusUpdateSql}
            WHERE id = :id
        ");
        $stmtTicket->execute([
            'reply_by' => $replyBy,
            'id'       => $ticketId
        ]);

        return $msgId;
    }

    /**
     * Update ticket status and optional admin notes
     */
    public function updateStatus(int $ticketId, string $status, ?string $adminNotes = null): bool
    {
        $allowed = ['open', 'in_progress', 'resolved', 'closed'];
        if (!in_array($status, $allowed)) {
            return false;
        }

        $sql = "UPDATE support_tickets SET status = :status";
        $params = ['status' => $status, 'id' => $ticketId];

        if ($adminNotes !== null) {
            $sql .= ", admin_notes = :admin_notes";
            $params['admin_notes'] = $adminNotes;
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Update ticket priority
     */
    public function updatePriority(int $ticketId, string $priority): bool
    {
        $allowed = ['high', 'medium', 'low'];
        if (!in_array($priority, $allowed)) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE support_tickets SET priority = ? WHERE id = ?");
        return $stmt->execute([$priority, $ticketId]);
    }

    /**
     * Get summary counts
     */
    public function getCounts(?int $vendorId = null): array
    {
        $sql = "
            SELECT 
                COUNT(*) as total,
                SUM(IF(status = 'open', 1, 0)) as `open`,
                SUM(IF(status = 'in_progress', 1, 0)) as in_progress,
                SUM(IF(status = 'resolved', 1, 0)) as resolved,
                SUM(IF(status = 'closed', 1, 0)) as closed,
                SUM(IF(priority = 'high' AND status IN ('open', 'in_progress'), 1, 0)) as high_priority_active
            FROM support_tickets
            WHERE 1=1
        ";
        $params = [];
        if ($vendorId) {
            $sql .= " AND vendor_id = :vendor_id";
            $params['vendor_id'] = $vendorId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetch();

        return [
            'total'                => (int)($res['total'] ?? 0),
            'open'                 => (int)($res['open'] ?? 0),
            'in_progress'          => (int)($res['in_progress'] ?? 0),
            'resolved'             => (int)($res['resolved'] ?? 0),
            'closed'               => (int)($res['closed'] ?? 0),
            'high_priority_active' => (int)($res['high_priority_active'] ?? 0),
        ];
    }
}
