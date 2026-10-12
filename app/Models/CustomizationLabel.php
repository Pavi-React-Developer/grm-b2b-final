<?php

namespace App\Models;

use Core\Database;
use PDO;

class CustomizationLabel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all customization labels with optional filters
     */
    public function getAll($filters = [])
    {
        $sql = "SELECT cl.*, 
                       (SELECT COUNT(*) FROM fabric_customization_label_rel fclr WHERE fclr.label_id = cl.id) as used_in_fabrics_count
                FROM customization_labels cl
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND cl.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['group_name'])) {
            $sql .= " AND cl.group_name = ?";
            $params[] = $filters['group_name'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (cl.name LIKE ? OR cl.display_label LIKE ? OR cl.group_name LIKE ? OR cl.sub_group LIKE ?)";
            $term = '%' . trim($filters['search']) . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY cl.group_name ASC, cl.sort_order ASC, cl.id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all active customization labels
     */
    public function getAllActive()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM customization_labels 
            WHERE status = 'active' 
            ORDER BY group_name ASC, sort_order ASC, id ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a single label by ID
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM customization_labels WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Create a new customization label / template
     */
    public function create($data)
    {
        $groupName = trim($data['group_name'] ?? '');
        $subGroup = trim($data['sub_group'] ?? '');
        $name = trim($data['name'] ?? '');
        $description = trim($data['description'] ?? '');
        $sortOrder = (int)($data['sort_order'] ?? 0);
        $status = $data['status'] ?? 'active';

        // Auto-compose display_label if not explicitly passed
        $displayLabel = trim($data['display_label'] ?? '');
        if (empty($displayLabel)) {
            $parts = array_filter([$groupName, $subGroup, $name]);
            $displayLabel = implode(' -> ', $parts);
        }

        $stmt = $this->db->prepare("
            INSERT INTO customization_labels (group_name, sub_group, name, display_label, description, sort_order, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $groupName ?: null,
            $subGroup ?: null,
            $name,
            $displayLabel,
            $description ?: null,
            $sortOrder,
            $status
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Update an existing customization label
     */
    public function update($id, $data)
    {
        $groupName = trim($data['group_name'] ?? '');
        $subGroup = trim($data['sub_group'] ?? '');
        $name = trim($data['name'] ?? '');
        $description = trim($data['description'] ?? '');
        $sortOrder = (int)($data['sort_order'] ?? 0);
        $status = $data['status'] ?? 'active';

        $displayLabel = trim($data['display_label'] ?? '');
        if (empty($displayLabel)) {
            $parts = array_filter([$groupName, $subGroup, $name]);
            $displayLabel = implode(' -> ', $parts);
        }

        $stmt = $this->db->prepare("
            UPDATE customization_labels 
            SET group_name = ?, sub_group = ?, name = ?, display_label = ?, description = ?, sort_order = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $groupName ?: null,
            $subGroup ?: null,
            $name,
            $displayLabel,
            $description ?: null,
            $sortOrder,
            $status,
            (int)$id
        ]);
    }

    /**
     * Delete a label
     */
    public function delete($id)
    {
        $this->db->beginTransaction();
        try {
            $stmtRel = $this->db->prepare("DELETE FROM fabric_customization_label_rel WHERE label_id = ?");
            $stmtRel->execute([(int)$id]);

            $stmt = $this->db->prepare("DELETE FROM customization_labels WHERE id = ?");
            $stmt->execute([(int)$id]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Toggle status active/inactive
     */
    public function toggleStatus($id)
    {
        $stmt = $this->db->prepare("
            UPDATE customization_labels 
            SET status = IF(status = 'active', 'inactive', 'active') 
            WHERE id = ?
        ");
        return $stmt->execute([(int)$id]);
    }

    /**
     * Get distinct group names for quick select/filters
     */
    public function getDistinctGroups()
    {
        $stmt = $this->db->prepare("
            SELECT DISTINCT group_name 
            FROM customization_labels 
            WHERE group_name IS NOT NULL AND group_name != '' 
            ORDER BY group_name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get all labels assigned to a specific fabric customization rule
     */
    public function getLabelsByCustomizationId($customizationId)
    {
        $stmt = $this->db->prepare("
            SELECT cl.* 
            FROM customization_labels cl
            JOIN fabric_customization_label_rel fclr ON cl.id = fclr.label_id
            WHERE fclr.customization_id = ? AND cl.status = 'active'
            ORDER BY cl.group_name ASC, cl.sort_order ASC, cl.id ASC
        ");
        $stmt->execute([(int)$customizationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sync/save assigned labels for a fabric customization rule
     */
    public function syncCustomizationLabels($customizationId, array $labelIds)
    {
        $customizationId = (int)$customizationId;
        if (!$customizationId) return;

        // Delete current relations
        $delStmt = $this->db->prepare("DELETE FROM fabric_customization_label_rel WHERE customization_id = ?");
        $delStmt->execute([$customizationId]);

        if (empty($labelIds)) {
            return;
        }

        $insStmt = $this->db->prepare("
            INSERT IGNORE INTO fabric_customization_label_rel (customization_id, label_id) 
            VALUES (?, ?)
        ");

        foreach ($labelIds as $lid) {
            $lid = (int)$lid;
            if ($lid > 0) {
                $insStmt->execute([$customizationId, $lid]);
            }
        }
    }
}
