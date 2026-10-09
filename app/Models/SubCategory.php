<?php
namespace App\Models;

use Core\Model;

class SubCategory extends Model
{
    public function getAll(?int $isCustomizable = null)
    {
        $sql = "
            SELECT s.*, c.name as category_name 
            FROM sub_categories s
            JOIN categories c ON s.category_id = c.id
        ";
        if ($isCustomizable !== null) {
            if ($isCustomizable === 1) {
                $sql .= " WHERE (s.is_customizable = 1 OR c.is_customizable = 1)";
            } else {
                $sql .= " WHERE (s.is_customizable = 0 OR s.is_customizable IS NULL) AND (c.is_customizable = 0 OR c.is_customizable IS NULL)";
            }
        }
        $sql .= " ORDER BY c.name ASC, s.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllActive(?int $isCustomizable = null)
    {
        $sql = "
            SELECT s.*, c.name as category_name 
            FROM sub_categories s
            JOIN categories c ON s.category_id = c.id
            WHERE s.status = 'active'
        ";
        if ($isCustomizable !== null) {
            if ($isCustomizable === 1) {
                $sql .= " AND (s.is_customizable = 1 OR c.is_customizable = 1)";
            } else {
                $sql .= " AND (s.is_customizable = 0 OR s.is_customizable IS NULL) AND (c.is_customizable = 0 OR c.is_customizable IS NULL)";
            }
        }
        $sql .= " ORDER BY c.name ASC, s.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCategory($categoryId)
    {
        $stmt = $this->db->prepare("SELECT * FROM sub_categories WHERE category_id = :cat_id ORDER BY name ASC");
        $stmt->execute(['cat_id' => $categoryId]);
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM sub_categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function generateUniqueSlug(string $nameOrSlug, ?int $excludeId = null): string
    {
        $baseSlug = preg_replace('/[^a-z0-9]+/i', '-', trim($nameOrSlug));
        $baseSlug = trim(strtolower($baseSlug), '-');
        if (empty($baseSlug)) {
            $baseSlug = 'subcategory';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $sql = "SELECT id FROM sub_categories WHERE slug = :slug";
            $params = ['slug' => $slug];
            if ($excludeId !== null) {
                $sql .= " AND id != :exclude_id";
                $params['exclude_id'] = $excludeId;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            if (!$stmt->fetch()) {
                return $slug;
            }
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
    }

    public function create(array $data)
    {
        $slug = !empty($data['slug']) ? $data['slug'] : $data['name'];
        $uniqueSlug = $this->generateUniqueSlug($slug);

        $stmt = $this->db->prepare("
            INSERT INTO sub_categories (category_id, name, slug, status, is_customizable)
            VALUES (:category_id, :name, :slug, :status, :is_customizable)
        ");
        
        $stmt->execute([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $uniqueSlug,
            'status' => $data['status'] ?? 'active',
            'is_customizable' => !empty($data['is_customizable']) ? 1 : 0
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data)
    {
        $slug = !empty($data['slug']) ? $data['slug'] : $data['name'];
        $uniqueSlug = $this->generateUniqueSlug($slug, $id);

        $stmt = $this->db->prepare("
            UPDATE sub_categories 
            SET category_id = :category_id, name = :name, slug = :slug, status = :status, is_customizable = :is_customizable
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $uniqueSlug,
            'status' => $data['status'] ?? 'active',
            'is_customizable' => !empty($data['is_customizable']) ? 1 : 0
        ]);
    }

    public function delete(int $id)
    {
        $ownsTx = false;
        if (!$this->db->inTransaction()) {
            $ownsTx = true;
            $this->db->beginTransaction();
        }

        try {
            $this->db->exec("SET FOREIGN_KEY_CHECKS=0");

            // Unlink products belonging to this subcategory
            $this->db->prepare("UPDATE products SET sub_category_id = NULL WHERE sub_category_id = :id")->execute(['id' => $id]);

            // Find and delete associated attributes and their values
            $stmtAttr = $this->db->prepare("SELECT id FROM attributes WHERE sub_category_id = :id");
            $stmtAttr->execute(['id' => $id]);
            $attrIds = $stmtAttr->fetchAll(\PDO::FETCH_COLUMN);

            if (!empty($attrIds)) {
                $attrPlaceholders = implode(',', array_fill(0, count($attrIds), '?'));
                $this->db->prepare("DELETE FROM product_variant_attributes WHERE attribute_id IN ($attrPlaceholders)")->execute($attrIds);
                $this->db->prepare("DELETE FROM product_attribute_values WHERE attribute_id IN ($attrPlaceholders)")->execute($attrIds);
                $this->db->prepare("DELETE FROM attribute_values WHERE attribute_id IN ($attrPlaceholders)")->execute($attrIds);
                $this->db->prepare("DELETE FROM attributes WHERE id IN ($attrPlaceholders)")->execute($attrIds);
            }

            // Clean up fabric_customizations referencing this subcategory
            $this->db->prepare("DELETE FROM fabric_customizations WHERE sub_category_id = :id")->execute(['id' => $id]);

            // Delete the subcategory
            $stmt = $this->db->prepare("DELETE FROM sub_categories WHERE id = :id");
            $result = $stmt->execute(['id' => $id]);
            
            $this->db->exec("SET FOREIGN_KEY_CHECKS=1");

            if ($ownsTx) {
                $this->db->commit();
            }

            if (class_exists('\Core\Cache')) {
                \Core\Cache::delete('catalog_active');
            }
            return $result;
        } catch (\Exception $e) {
            $this->db->exec("SET FOREIGN_KEY_CHECKS=1");
            if ($ownsTx && $this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}
