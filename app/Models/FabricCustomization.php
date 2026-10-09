<?php

namespace App\Models;

use Core\Database;
use PDO;

class FabricCustomization
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all fabric customization rules with product & category information
     */
    public function getAllWithProductInfo($filters = [])
    {
        $sql = "SELECT fc.*, 
                       p.name as fabric_name, 
                       p.base_sku as fabric_sku, 
                       COALESCE(
                           (SELECT MIN(CASE WHEN pv.discount_price > 0 THEN pv.discount_price ELSE pv.base_price END) FROM product_variants pv WHERE pv.product_id = p.id),
                           p.wholesale_price
                       ) as fabric_price,
                       COALESCE(
                           (SELECT MIN(pv.base_price) FROM product_variants pv WHERE pv.product_id = p.id),
                           p.retail_price,
                           p.wholesale_price
                       ) as fabric_base_price,
                       (SELECT MIN(CASE WHEN pv.discount_price > 0 THEN pv.discount_price ELSE NULL END) FROM product_variants pv WHERE pv.product_id = p.id) as fabric_discount_price,
                       p.stock_quantity as fabric_stock,
                       c.name as category_name,
                       sc.name as subcategory_name,
                       (SELECT COUNT(*) FROM fabric_customization_sizes fcs WHERE fcs.customization_id = fc.id AND fcs.enabled = 1) as enabled_sizes_count,
                       (SELECT MIN(fabric_consumption) FROM fabric_customization_sizes fcs WHERE fcs.customization_id = fc.id AND fcs.enabled = 1) as min_consumption,
                       (SELECT MAX(fabric_consumption) FROM fabric_customization_sizes fcs WHERE fcs.customization_id = fc.id AND fcs.enabled = 1) as max_consumption
                FROM fabric_customizations fc
                JOIN products p ON fc.fabric_id = p.id
                LEFT JOIN categories c ON fc.category_id = c.id
                LEFT JOIN sub_categories sc ON fc.sub_category_id = sc.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND fc.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['garment_type'])) {
            $sql .= " AND fc.garment_type = ?";
            $params[] = $filters['garment_type'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.base_sku LIKE ? OR fc.program_name LIKE ? OR fc.garment_type LIKE ?)";
            $searchTerm = '%' . trim($filters['search']) . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY fc.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Attach sizes to each
        foreach ($programs as &$program) {
            $program['sizes'] = $this->getSizesByCustomizationId($program['id']);
        }

        return $programs;
    }

    /**
     * Get a single fabric customization rule by ID
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT fc.*, 
                   p.name as fabric_name, 
                   p.base_sku as fabric_sku, 
                   COALESCE(
                       (SELECT MIN(CASE WHEN pv.discount_price > 0 THEN pv.discount_price ELSE pv.base_price END) FROM product_variants pv WHERE pv.product_id = p.id),
                       p.wholesale_price
                   ) as fabric_price,
                   COALESCE(
                       (SELECT MIN(pv.base_price) FROM product_variants pv WHERE pv.product_id = p.id),
                       p.retail_price,
                       p.wholesale_price
                   ) as fabric_base_price,
                   (SELECT MIN(CASE WHEN pv.discount_price > 0 THEN pv.discount_price ELSE NULL END) FROM product_variants pv WHERE pv.product_id = p.id) as fabric_discount_price,
                   p.stock_quantity as fabric_stock,
                   p.description as fabric_description,
                   c.name as category_name,
                   sc.name as subcategory_name
            FROM fabric_customizations fc
            JOIN products p ON fc.fabric_id = p.id
            LEFT JOIN categories c ON fc.category_id = c.id
            LEFT JOIN sub_categories sc ON fc.sub_category_id = sc.id
            WHERE fc.id = ?
        ");
        $stmt->execute([(int)$id]);
        $customization = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customization) {
            $customization['sizes'] = $this->getSizesByCustomizationId($customization['id']);
        }

        return $customization ?: null;
    }

    /**
     * Get active customization rule for a specific fabric product ID
     */
    public function getByFabricId($fabricId)
    {
        $stmt = $this->db->prepare("
            SELECT fc.*, 
                   p.name as fabric_name, 
                   p.base_sku as fabric_sku, 
                   COALESCE(
                       (SELECT MIN(CASE WHEN pv.discount_price > 0 THEN pv.discount_price ELSE pv.base_price END) FROM product_variants pv WHERE pv.product_id = p.id),
                       p.wholesale_price
                   ) as fabric_price,
                   COALESCE(
                       (SELECT MIN(pv.base_price) FROM product_variants pv WHERE pv.product_id = p.id),
                       p.retail_price,
                       p.wholesale_price
                   ) as fabric_base_price,
                   (SELECT MIN(CASE WHEN pv.discount_price > 0 THEN pv.discount_price ELSE NULL END) FROM product_variants pv WHERE pv.product_id = p.id) as fabric_discount_price,
                   p.retail_price as fabric_retail_price,
                   p.stock_quantity as fabric_stock,
                   p.description as fabric_description,
                   p.weight as fabric_weight,
                   p.weight_unit as fabric_weight_unit,
                   c.name as category_name,
                   sc.name as subcategory_name
            FROM fabric_customizations fc
            JOIN products p ON fc.fabric_id = p.id
            LEFT JOIN categories c ON fc.category_id = c.id
            LEFT JOIN sub_categories sc ON fc.sub_category_id = sc.id
            WHERE fc.fabric_id = ? AND fc.status = 'active' AND fc.customization_enabled = 1
            LIMIT 1
        ");
        $stmt->execute([(int)$fabricId]);
        $customization = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customization) {
            $customization['sizes'] = $this->getSizesByCustomizationId($customization['id']);
            $customization['images'] = $this->getProductImages($fabricId);
        }

        return $customization ?: null;
    }

    /**
     * Get all customizable fabrics for the storefront listing
     * Only returns fabrics that have an active customization rule and available inventory
     */
    public function getCustomizableFabrics($filters = [])
    {
        $sql = "SELECT fc.*, 
                       p.id as product_id,
                       p.name as fabric_name, 
                       p.base_sku as fabric_sku, 
                       p.slug as fabric_slug, 
                       COALESCE(
                           (SELECT MIN(CASE WHEN pv.discount_price > 0 THEN pv.discount_price ELSE pv.base_price END) FROM product_variants pv WHERE pv.product_id = p.id),
                           p.wholesale_price
                       ) as fabric_price,
                       COALESCE(
                           (SELECT MIN(pv.base_price) FROM product_variants pv WHERE pv.product_id = p.id),
                           p.retail_price,
                           p.wholesale_price
                       ) as fabric_base_price,
                       (SELECT MIN(CASE WHEN pv.discount_price > 0 THEN pv.discount_price ELSE NULL END) FROM product_variants pv WHERE pv.product_id = p.id) as fabric_discount_price,
                       p.retail_price as fabric_retail_price,
                       p.stock_quantity as fabric_stock,
                       p.description as fabric_description,
                       c.name as category_name,
                       c.slug as category_slug,
                       sc.name as subcategory_name,
                       (SELECT pi.image_path FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.is_primary DESC, pi.sort_order ASC, pi.id ASC LIMIT 1) as primary_image
                FROM fabric_customizations fc
                JOIN products p ON fc.fabric_id = p.id
                LEFT JOIN categories c ON fc.category_id = c.id
                LEFT JOIN sub_categories sc ON fc.sub_category_id = sc.id
                WHERE fc.status = 'active' 
                  AND fc.customization_enabled = 1 
                  AND p.status = 'active'
                  AND p.stock_quantity > 0";

        $params = [];

        if (!empty($filters['category_id'])) {
            $sql .= " AND (fc.category_id = ? OR p.category_id = ?)";
            $params[] = (int)$filters['category_id'];
            $params[] = (int)$filters['category_id'];
        }

        if (!empty($filters['garment_type'])) {
            $sql .= " AND fc.garment_type = ?";
            $params[] = $filters['garment_type'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.base_sku LIKE ? OR fc.program_name LIKE ? OR fc.garment_type LIKE ?)";
            $searchTerm = '%' . trim($filters['search']) . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $sql .= " ORDER BY fabric_price ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY fabric_price DESC";
                break;
            case 'stock':
                $sql .= " ORDER BY p.stock_quantity DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY fc.id DESC";
                break;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $fabrics = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($fabrics as &$fabric) {
            $fabric['sizes'] = $this->getSizesByCustomizationId($fabric['id']);
            $fabric['images'] = $this->getProductImages($fabric['fabric_id']);
            if (empty($fabric['primary_image'])) {
                $fabric['primary_image'] = !empty($fabric['images'][0]) ? $fabric['images'][0] : '/assets/images/fabric-placeholder.png';
            }
        }

        return $fabrics;
    }

    /**
     * Get sizes configured for a customization rule
     */
    public function getSizesByCustomizationId($customizationId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM fabric_customization_sizes 
            WHERE customization_id = ? AND enabled = 1 
            ORDER BY sort_order ASC, id ASC
        ");
        $stmt->execute([(int)$customizationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all images for a product
     */
    public function getProductImages($productId)
    {
        $stmt = $this->db->prepare("
            SELECT image_path, is_primary FROM product_images 
            WHERE product_id = ? 
            ORDER BY is_primary DESC, sort_order ASC, id ASC
        ");
        $stmt->execute([(int)$productId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_column($rows, 'image_path');
    }

    /**
     * Get products available for fabric rules dropdown (excluding fabrics already assigned unless editing)
     */
    public function getAvailableFabricsForDropdown($excludeCustomizationId = null)
    {
        $sql = "SELECT p.id, p.name, p.base_sku, 
                       COALESCE(
                           (SELECT MIN(CASE WHEN pv.discount_price > 0 THEN pv.discount_price ELSE pv.base_price END) FROM product_variants pv WHERE pv.product_id = p.id),
                           p.wholesale_price
                       ) as wholesale_price, 
                       p.stock_quantity, p.category_id, p.sub_category_id 
                FROM products p 
                WHERE p.status = 'active'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new fabric rule and its size consumption rules
     */
    public function create($data, $sizes = [])
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO fabric_customizations 
                (fabric_id, program_name, garment_type, category_id, sub_category_id, feeding_type, minimum_pieces, maximum_pieces, wastage_percentage, unit, customization_enabled, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['fabric_id'],
                $data['program_name'],
                $data['garment_type'],
                $data['category_id'] ?? null,
                $data['sub_category_id'] ?? null,
                $data['feeding_type'] ?? 'Non-Feeding',
                $data['minimum_pieces'] ?? 10,
                $data['maximum_pieces'] ?? 100,
                $data['wastage_percentage'] ?? 0.00,
                $data['unit'] ?? 'Meter',
                isset($data['customization_enabled']) ? (int)$data['customization_enabled'] : 1,
                $data['status'] ?? 'active'
            ]);

            $customizationId = (int)$this->db->lastInsertId();

            // Insert sizes
            $sizeStmt = $this->db->prepare("
                INSERT INTO fabric_customization_sizes 
                (customization_id, size_id, size_name, fabric_consumption, base_quantity, max_quantity, unit, enabled, sort_order)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $sort = 1;
            foreach ($sizes as $sz) {
                if (empty($sz['size_name'])) {
                    continue;
                }
                $consumption = isset($sz['fabric_consumption']) && (float)$sz['fabric_consumption'] > 0 ? (float)$sz['fabric_consumption'] : 1.00;
                $baseQty = isset($sz['base_quantity']) && (int)$sz['base_quantity'] >= 0 ? (int)$sz['base_quantity'] : 2;
                $maxQty = isset($sz['max_quantity']) && (int)$sz['max_quantity'] > 0 ? (int)$sz['max_quantity'] : 4;

                $sizeStmt->execute([
                    $customizationId,
                    $sz['size_id'] ?? null,
                    trim($sz['size_name']),
                    $consumption,
                    $baseQty,
                    $maxQty,
                    $sz['unit'] ?? $data['unit'] ?? 'Meter',
                    isset($sz['enabled']) ? (int)$sz['enabled'] : 1,
                    $sz['sort_order'] ?? $sort++
                ]);
            }

            $this->db->commit();
            return $customizationId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing fabric rule and replace/sync its size consumption rules
     */
    public function update($id, $data, $sizes = [])
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                UPDATE fabric_customizations 
                SET fabric_id = ?, 
                    program_name = ?, 
                    garment_type = ?, 
                    category_id = ?, 
                    sub_category_id = ?, 
                    feeding_type = ?, 
                    minimum_pieces = ?, 
                    maximum_pieces = ?, 
                    wastage_percentage = ?, 
                    unit = ?, 
                    customization_enabled = ?, 
                    status = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $data['fabric_id'],
                $data['program_name'],
                $data['garment_type'],
                $data['category_id'] ?? null,
                $data['sub_category_id'] ?? null,
                $data['feeding_type'] ?? 'Non-Feeding',
                $data['minimum_pieces'] ?? 10,
                $data['maximum_pieces'] ?? 100,
                $data['wastage_percentage'] ?? 0.00,
                $data['unit'] ?? 'Meter',
                isset($data['customization_enabled']) ? (int)$data['customization_enabled'] : 1,
                $data['status'] ?? 'active',
                (int)$id
            ]);

            // Clear old sizes and insert new ones
            $delStmt = $this->db->prepare("DELETE FROM fabric_customization_sizes WHERE customization_id = ?");
            $delStmt->execute([(int)$id]);

            $sizeStmt = $this->db->prepare("
                INSERT INTO fabric_customization_sizes 
                (customization_id, size_id, size_name, fabric_consumption, base_quantity, max_quantity, unit, enabled, sort_order)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $sort = 1;
            foreach ($sizes as $sz) {
                if (empty($sz['size_name'])) {
                    continue;
                }
                $consumption = isset($sz['fabric_consumption']) && (float)$sz['fabric_consumption'] > 0 ? (float)$sz['fabric_consumption'] : 1.00;
                $baseQty = isset($sz['base_quantity']) && (int)$sz['base_quantity'] >= 0 ? (int)$sz['base_quantity'] : 2;
                $maxQty = isset($sz['max_quantity']) && (int)$sz['max_quantity'] > 0 ? (int)$sz['max_quantity'] : 4;

                $sizeStmt->execute([
                    (int)$id,
                    $sz['size_id'] ?? null,
                    trim($sz['size_name']),
                    $consumption,
                    $baseQty,
                    $maxQty,
                    $sz['unit'] ?? $data['unit'] ?? 'Meter',
                    isset($sz['enabled']) ? (int)$sz['enabled'] : 1,
                    $sz['sort_order'] ?? $sort++
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Delete fabric rule
     */
    public function delete($id)
    {
        $this->db->beginTransaction();
        try {
            $delSizes = $this->db->prepare("DELETE FROM fabric_customization_sizes WHERE customization_id = ?");
            $delSizes->execute([(int)$id]);

            $delRule = $this->db->prepare("DELETE FROM fabric_customizations WHERE id = ?");
            $delRule->execute([(int)$id]);

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
            UPDATE fabric_customizations 
            SET status = IF(status = 'active', 'inactive', 'active') 
            WHERE id = ?
        ");
        return $stmt->execute([(int)$id]);
    }
}
