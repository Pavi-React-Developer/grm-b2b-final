<?php
namespace App\Models;

use Core\Model;

class SubCategory extends Model
{
    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT s.*, c.name as category_name 
            FROM sub_categories s
            JOIN categories c ON s.category_id = c.id
            ORDER BY c.name ASC, s.name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllActive()
    {
        $stmt = $this->db->prepare("
            SELECT s.*, c.name as category_name 
            FROM sub_categories s
            JOIN categories c ON s.category_id = c.id
            WHERE s.status = 'active'
            ORDER BY c.name ASC, s.name ASC
        ");
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

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO sub_categories (category_id, name, slug, status)
            VALUES (:category_id, :name, :slug, :status)
        ");
        
        $stmt->execute([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status'] ?? 'active'
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data)
    {
        $stmt = $this->db->prepare("
            UPDATE sub_categories 
            SET category_id = :category_id, name = :name, slug = :slug, status = :status
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status'] ?? 'active'
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

            // Check if there are any products in this subcategory
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE sub_category_id = :id");
            $stmt->execute(['id' => $id]);
            if ($stmt->fetchColumn() > 0) {
                throw new \Exception("Cannot delete subcategory because it contains products.");
            }

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

            // Delete the subcategory
            $stmt = $this->db->prepare("DELETE FROM sub_categories WHERE id = :id");
            $result = $stmt->execute(['id' => $id]);
            
            $this->db->exec("SET FOREIGN_KEY_CHECKS=1");

            if ($ownsTx) {
                $this->db->commit();
            }

            \Core\Cache::delete('catalog_active');
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
