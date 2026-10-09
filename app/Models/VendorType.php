<?php
namespace App\Models;

use Core\Model;

class VendorType extends Model
{
    /**
     * Get all vendor types with their associated vendor count
     */
    public function getAll(): array
    {
        $sql = "
            SELECT vt.*, 
                   COUNT(vp.id) AS vendor_count 
            FROM `vendor_types` vt
            LEFT JOIN `vendor_profiles` vp ON LOWER(vp.vendor_type) = LOWER(vt.slug)
            GROUP BY vt.id
            ORDER BY vt.display_order ASC, vt.id ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Get only active vendor types for storefront forms
     */
    public function getAllActive(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM `vendor_types` 
            WHERE `is_active` = 1 
            ORDER BY `display_order` ASC, `name` ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Find vendor type by ID
     */
    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM `vendor_types` WHERE `id` = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Find vendor type by slug
     */
    public function findBySlug(string $slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM `vendor_types` WHERE `slug` = :slug LIMIT 1");
        $stmt->execute(['slug' => strtolower(trim($slug))]);
        return $stmt->fetch();
    }

    /**
     * Create a new vendor type
     */
    public function create(array $data): int
    {
        $name = trim($data['name'] ?? '');
        $slug = !empty($data['slug']) 
            ? strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', trim($data['slug']))) 
            : strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $name));
        
        $description = trim($data['description'] ?? '');
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;
        $displayOrder = isset($data['display_order']) ? (int)$data['display_order'] : 0;

        $stmt = $this->db->prepare("
            INSERT INTO `vendor_types` (`name`, `slug`, `description`, `is_active`, `display_order`)
            VALUES (:name, :slug, :description, :is_active, :display_order)
        ");
        $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $isActive,
            'display_order' => $displayOrder
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Update an existing vendor type
     */
    public function update(int $id, array $data): bool
    {
        $name = trim($data['name'] ?? '');
        $slug = !empty($data['slug']) 
            ? strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', trim($data['slug']))) 
            : strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $name));
        
        $description = trim($data['description'] ?? '');
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;
        $displayOrder = isset($data['display_order']) ? (int)$data['display_order'] : 0;

        $stmt = $this->db->prepare("
            UPDATE `vendor_types` 
            SET `name` = :name, 
                `slug` = :slug, 
                `description` = :description, 
                `is_active` = :is_active, 
                `display_order` = :display_order
            WHERE `id` = :id
        ");
        return $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $isActive,
            'display_order' => $displayOrder,
            'id' => $id
        ]);
    }

    /**
     * Toggle active/visibility status
     */
    public function toggleStatus(int $id): bool
    {
        $type = $this->findById($id);
        if (!$type) {
            return false;
        }
        $newStatus = $type['is_active'] ? 0 : 1;
        $stmt = $this->db->prepare("UPDATE `vendor_types` SET `is_active` = :status WHERE `id` = :id");
        return $stmt->execute([
            'status' => $newStatus,
            'id' => $id
        ]);
    }

    /**
     * Delete a vendor type
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `vendor_types` WHERE `id` = :id");
        return $stmt->execute(['id' => $id]);
    }
}
