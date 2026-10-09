<?php
namespace App\Models;

use Core\Model;

class Category extends Model
{
    public function getAllActive($search = null, ?int $isCustomizable = null)
    {
        $sql = "SELECT * FROM categories WHERE status = 'active'";
        $params = [];
        
        if ($isCustomizable !== null) {
            if ($isCustomizable === 1) {
                $sql .= " AND is_customizable = 1";
            } else {
                $sql .= " AND (is_customizable = 0 OR is_customizable IS NULL)";
            }
        }

        if ($search) {
            $sql .= " AND name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Deduplicate by category name to ensure each unique category is only shown once
        $uniqueCategories = [];
        $seenNames = [];
        foreach ($results as $cat) {
            $normName = strtolower(trim($cat['name']));
            if (!isset($seenNames[$normName])) {
                $seenNames[$normName] = true;
                $uniqueCategories[] = $cat;
            }
        }
        return $uniqueCategories;
    }
    
    public function getAll(?int $isCustomizable = null)
    {
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM categories c 
                LEFT JOIN products p ON p.category_id = c.id";
        $conditions = [];
        if ($isCustomizable !== null) {
            if ($isCustomizable === 1) {
                $conditions[] = "c.is_customizable = 1";
            } else {
                $conditions[] = "(c.is_customizable = 0 OR c.is_customizable IS NULL)";
            }
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        $sql .= " GROUP BY c.id ORDER BY c.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Deduplicate by category name to ensure each unique category is only shown once
        $uniqueCategories = [];
        $seenNames = [];
        foreach ($results as $cat) {
            $normName = strtolower(trim($cat['name']));
            if (!isset($seenNames[$normName])) {
                $seenNames[$normName] = true;
                $uniqueCategories[] = $cat;
            }
        }
        return $uniqueCategories;
    }

    public function findByName(string $name, ?int $isCustomizable = null, ?int $excludeId = null)
    {
        $sql = "SELECT * FROM categories WHERE LOWER(TRIM(name)) = LOWER(TRIM(:name))";
        $params = ['name' => trim($name)];
        if ($isCustomizable !== null) {
            if ($isCustomizable === 1) {
                $sql .= " AND is_customizable = 1";
            } else {
                $sql .= " AND (is_customizable = 0 OR is_customizable IS NULL)";
            }
        }
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        $sql .= " LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function generateUniqueSlug(string $nameOrSlug, ?int $excludeId = null): string
    {
        $baseSlug = preg_replace('/[^a-z0-9]+/i', '-', trim($nameOrSlug));
        $baseSlug = trim(strtolower($baseSlug), '-');
        if (empty($baseSlug)) {
            $baseSlug = 'category';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $sql = "SELECT id FROM categories WHERE slug = :slug";
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
            INSERT INTO categories (name, slug, hsn_code, description, image_path, min_order_value, min_cart_value, sgst, cgst, status, is_customizable)
            VALUES (:name, :slug, :hsn_code, :description, :image_path, :min_order_value, :min_cart_value, :sgst, :cgst, :status, :is_customizable)
        ");
        
        $stmt->execute([
            'name' => $data['name'],
            'slug' => $uniqueSlug,
            'hsn_code' => !empty($data['hsn_code']) ? trim($data['hsn_code']) : null,
            'description' => $data['description'] ?? null,
            'image_path' => $data['image_path'] ?? null,
            'min_order_value' => !empty($data['min_order_value']) ? (float)$data['min_order_value'] : null,
            'min_cart_value' => !empty($data['min_cart_value']) ? (float)$data['min_cart_value'] : null,
            'sgst' => isset($data['sgst']) && $data['sgst'] !== '' ? (float)$data['sgst'] : 0.00,
            'cgst' => isset($data['cgst']) && $data['cgst'] !== '' ? (float)$data['cgst'] : 0.00,
            'status' => $data['status'] ?? 'active',
            'is_customizable' => !empty($data['is_customizable']) ? 1 : 0
        ]);
        
        return $this->db->lastInsertId();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getById($id)
    {
        return $this->findById($id);
    }

    public function update(int $id, array $data)
    {
        $slug = !empty($data['slug']) ? $data['slug'] : $data['name'];
        $uniqueSlug = $this->generateUniqueSlug($slug, $id);

        $stmt = $this->db->prepare("
            UPDATE categories 
            SET name = :name, 
                slug = :slug, 
                hsn_code = :hsn_code,
                description = :description, 
                image_path = :image_path,
                min_order_value = :min_order_value,
                min_cart_value = :min_cart_value,
                sgst = :sgst,
                cgst = :cgst,
                status = :status,
                is_customizable = :is_customizable
            WHERE id = :id
        ");
        
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'slug' => $uniqueSlug,
            'hsn_code' => !empty($data['hsn_code']) ? trim($data['hsn_code']) : null,
            'description' => $data['description'] ?? null,
            'image_path' => $data['image_path'] ?? null,
            'min_order_value' => !empty($data['min_order_value']) ? (float)$data['min_order_value'] : null,
            'min_cart_value' => !empty($data['min_cart_value']) ? (float)$data['min_cart_value'] : null,
            'sgst' => isset($data['sgst']) && $data['sgst'] !== '' ? (float)$data['sgst'] : 0.00,
            'cgst' => isset($data['cgst']) && $data['cgst'] !== '' ? (float)$data['cgst'] : 0.00,
            'status' => $data['status'] ?? 'active',
            'is_customizable' => !empty($data['is_customizable']) ? 1 : 0
        ]);
    }

    public function getProductCount(int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn();
    }

    public function getSubCategoryCount(int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM sub_categories WHERE category_id = :id");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn();
    }

    public function getCounts(int $id): array
    {
        return [
            'product_count' => $this->getProductCount($id),
            'subcategory_count' => $this->getSubCategoryCount($id)
        ];
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

            // 1. Unassign all products belonging to this category (do NOT delete products)
            $stmtProd = $this->db->prepare("UPDATE products SET category_id = NULL WHERE category_id = :id");
            $stmtProd->execute(['id' => $id]);

            // 2. Unassign all subcategories belonging to this category (do NOT delete subcategories)
            $stmtSub = $this->db->prepare("UPDATE sub_categories SET category_id = NULL WHERE category_id = :id");
            $stmtSub->execute(['id' => $id]);

            // 3. Unassign category from attributes (do NOT delete attributes)
            $stmtAttr = $this->db->prepare("UPDATE attributes SET category_id = NULL WHERE category_id = :id");
            $stmtAttr->execute(['id' => $id]);

            // 4. Remove category-specific rules / references
            $this->db->prepare("DELETE FROM order_rules WHERE category_id = :id")->execute(['id' => $id]);
            $this->db->prepare("UPDATE size_charts SET category_id = NULL WHERE category_id = :id")->execute(['id' => $id]);
            $this->db->prepare("UPDATE fabric_customizations SET category_id = NULL WHERE category_id = :id")->execute(['id' => $id]);
            $this->db->prepare("UPDATE category_requests SET parent_category_id = NULL WHERE parent_category_id = :id")->execute(['id' => $id]);

            // 5. Delete the category record
            $stmtDel = $this->db->prepare("DELETE FROM categories WHERE id = :id");
            $result = $stmtDel->execute(['id' => $id]);

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
