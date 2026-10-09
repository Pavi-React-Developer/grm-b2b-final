<?php
namespace App\Models;

use Core\Model;

class Category extends Model
{
    public function getAllActive($search = null)
    {
        $sql = "SELECT * FROM categories WHERE status = 'active'";
        $params = [];
        
        if ($search) {
            $sql .= " AND name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name, slug, hsn_code, description, image_path, min_order_value, sgst, cgst, status)
            VALUES (:name, :slug, :hsn_code, :description, :image_path, :min_order_value, :sgst, :cgst, :status)
        ");
        
        $stmt->execute([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'hsn_code' => !empty($data['hsn_code']) ? trim($data['hsn_code']) : null,
            'description' => $data['description'] ?? null,
            'image_path' => $data['image_path'] ?? null,
            'min_order_value' => !empty($data['min_order_value']) ? (float)$data['min_order_value'] : null,
            'sgst' => isset($data['sgst']) && $data['sgst'] !== '' ? (float)$data['sgst'] : 0.00,
            'cgst' => isset($data['cgst']) && $data['cgst'] !== '' ? (float)$data['cgst'] : 0.00,
            'status' => $data['status'] ?? 'active'
        ]);
        
        return $this->db->lastInsertId();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function update(int $id, array $data)
    {
        $stmt = $this->db->prepare("
            UPDATE categories 
            SET name = :name, 
                slug = :slug, 
                hsn_code = :hsn_code,
                description = :description, 
                image_path = :image_path,
                min_order_value = :min_order_value,
                sgst = :sgst,
                cgst = :cgst,
                status = :status 
            WHERE id = :id
        ");
        
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'hsn_code' => !empty($data['hsn_code']) ? trim($data['hsn_code']) : null,
            'description' => $data['description'] ?? null,
            'image_path' => $data['image_path'] ?? null,
            'min_order_value' => !empty($data['min_order_value']) ? (float)$data['min_order_value'] : null,
            'sgst' => isset($data['sgst']) && $data['sgst'] !== '' ? (float)$data['sgst'] : 0.00,
            'cgst' => isset($data['cgst']) && $data['cgst'] !== '' ? (float)$data['cgst'] : 0.00,
            'status' => $data['status'] ?? 'active'
        ]);
    }

    public function getProductCount(int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn();
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

            // 1. Delete all products belonging to this category
            $stmt = $this->db->prepare("SELECT id FROM products WHERE category_id = :id");
            $stmt->execute(['id' => $id]);
            $productIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            $productModel = new Product();
            foreach ($productIds as $pId) {
                $productModel->delete($pId);
            }

            // 2. Fetch all subcategory IDs for this category
            $stmtSubIds = $this->db->prepare("SELECT id FROM sub_categories WHERE category_id = :id");
            $stmtSubIds->execute(['id' => $id]);
            $subCategoryIds = $stmtSubIds->fetchAll(\PDO::FETCH_COLUMN);

            // 3. Find all attributes belonging to this category or its subcategories
            $attrSql = "SELECT id FROM attributes WHERE category_id = :id";
            if (!empty($subCategoryIds)) {
                $subPlaceholders = implode(',', array_fill(0, count($subCategoryIds), '?'));
                $attrSql = "SELECT id FROM attributes WHERE category_id = ? OR sub_category_id IN ($subPlaceholders)";
                $stmtAttr = $this->db->prepare($attrSql);
                $stmtAttr->execute(array_merge([$id], $subCategoryIds));
            } else {
                $stmtAttr = $this->db->prepare($attrSql);
                $stmtAttr->execute(['id' => $id]);
            }
            $attributeIds = $stmtAttr->fetchAll(\PDO::FETCH_COLUMN);

            if (!empty($attributeIds)) {
                $attrPlaceholders = implode(',', array_fill(0, count($attributeIds), '?'));
                $this->db->prepare("DELETE FROM product_variant_attributes WHERE attribute_id IN ($attrPlaceholders)")->execute($attributeIds);
                $this->db->prepare("DELETE FROM product_attribute_values WHERE attribute_id IN ($attrPlaceholders)")->execute($attributeIds);
                $this->db->prepare("DELETE FROM attribute_values WHERE attribute_id IN ($attrPlaceholders)")->execute($attributeIds);
                $this->db->prepare("DELETE FROM attributes WHERE id IN ($attrPlaceholders)")->execute($attributeIds);
            }

            // 4. Delete associated subcategories
            $stmtSub = $this->db->prepare("DELETE FROM sub_categories WHERE category_id = :id");
            $stmtSub->execute(['id' => $id]);

            // 5. Delete associated order rules
            $stmtRules = $this->db->prepare("DELETE FROM order_rules WHERE category_id = :id");
            $stmtRules->execute(['id' => $id]);

            // 6. Delete the category itself
            $stmtDel = $this->db->prepare("DELETE FROM categories WHERE id = :id");
            $result = $stmtDel->execute(['id' => $id]);

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
