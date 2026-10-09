<?php
namespace App\Models;

use Core\Database;
use PDO;

class Wishlist
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getItems(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT w.id as wishlist_id, p.*, 
                   c.name as category_name, c.moq as category_moq,
                   c.sgst as category_sgst, c.cgst as category_cgst,
                   COALESCE(vp.store_name, vp.company_name, u.name) as vendor_brand_name,
                   (SELECT COALESCE(SUM(current_stock), 0) FROM product_variants WHERE product_id = p.id) as total_variant_stock,
                   (SELECT GROUP_CONCAT(DISTINCT CONCAT(av.value, ':', av.color_code) SEPARATOR '||') 
                    FROM product_variant_attributes pva 
                    JOIN attribute_values av ON pva.attribute_value_id = av.id 
                    JOIN product_variants pv_col ON pva.variant_id = pv_col.id 
                    WHERE pv_col.product_id = p.id AND av.color_code IS NOT NULL AND av.color_code != '') AS variant_colors_concat,
                   (SELECT GROUP_CONCAT(av.value SEPARATOR ' / ') 
                    FROM product_variant_attributes pva 
                    JOIN attribute_values av ON pva.attribute_value_id = av.id 
                    WHERE pva.variant_id = (SELECT id FROM product_variants WHERE product_id = p.id AND status = 'active' ORDER BY id ASC LIMIT 1)) as first_variant_attrs,
                   (SELECT pv.name FROM product_variants pv WHERE pv.product_id = p.id AND pv.status = 'active' ORDER BY pv.id ASC LIMIT 1) as first_variant_name,
                   (SELECT MIN(base_price) FROM product_variants WHERE product_id = p.id) as base_price,
                   (SELECT MIN(CASE WHEN discount_price > 0 THEN discount_price ELSE NULL END) FROM product_variants WHERE product_id = p.id) as discount_price,
                   (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
            FROM wishlists w
            JOIN products p ON w.product_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.vendor_id = u.id
            LEFT JOIN vendor_profiles vp ON p.vendor_id = vp.user_id
            WHERE w.user_id = ?
            ORDER BY w.created_at DESC
        ");
        $stmt->execute([$userId]);
        $items = $stmt->fetchAll();

        foreach ($items as &$item) {
            $item['colors'] = [];
            if (!empty($item['variant_colors_concat'])) {
                $pairs = explode('||', $item['variant_colors_concat']);
                foreach ($pairs as $pair) {
                    $parts = explode(':', $pair, 2);
                    if (count($parts) === 2 && !empty($parts[1])) {
                        $item['colors'][] = [
                            'name' => $parts[0],
                            'color_code' => $parts[1]
                        ];
                    }
                }
            }
            unset($item['variant_colors_concat']);
        }
        unset($item);

        return $items;
    }

    public function addItem(int $userId, int $productId): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO wishlists (user_id, product_id)
                VALUES (?, ?)
            ");
            return $stmt->execute([$userId, $productId]);
        } catch (\PDOException $e) {
            // Might be duplicate entry, just ignore
            if ($e->getCode() == 23000) {
                return true; 
            }
            throw $e;
        }
    }

    public function removeItem(int $userId, int $productId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM wishlists 
            WHERE user_id = ? AND product_id = ?
        ");
        return $stmt->execute([$userId, $productId]);
    }
    
    public function getProductIds(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT product_id FROM wishlists WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
