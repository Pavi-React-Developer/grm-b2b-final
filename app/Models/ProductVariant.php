<?php
namespace App\Models;

use Core\Model;

class ProductVariant extends Model
{
    public function getByProductId(int $productId)
    {
        $stmt = $this->db->prepare("SELECT * FROM product_variants WHERE product_id = :product_id");
        $stmt->execute(['product_id' => $productId]);
        $variants = $stmt->fetchAll();

        foreach ($variants as &$variant) {
            $variant['attributes'] = $this->getAttributes($variant['id']);
            
            $imgStmt = $this->db->prepare("SELECT image_path FROM product_images WHERE variant_id = :vid ORDER BY is_primary DESC, id ASC");
            $imgStmt->execute(['vid' => $variant['id']]);
            $variant['images'] = $imgStmt->fetchAll(\PDO::FETCH_COLUMN);
        }
        
        return $variants;
    }

    public function getAttributes(int $variantId)
    {
        $stmt = $this->db->prepare("SELECT attribute_id, attribute_value_id FROM product_variant_attributes WHERE variant_id = :variant_id");
        $stmt->execute(['variant_id' => $variantId]);
        $rows = $stmt->fetchAll();
        $attributes = [];
        foreach ($rows as $row) {
            $attributes[$row['attribute_id']] = $row['attribute_value_id'];
        }
        return $attributes;
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO product_variants (product_id, name, base_price, discount_price, weight, inventory, current_stock, low_stock_alert, sku, status, max_amount, sgst, cgst, total_gst)
            VALUES (:product_id, :name, :base_price, :discount_price, :weight, :inventory, :current_stock, :low_stock_alert, :sku, :status, :max_amount, :sgst, :cgst, :total_gst)
        ");
        
        $inventory = ($data['inventory'] === '' || $data['inventory'] === null) ? 0 : $data['inventory'];
        $basePrice = (!isset($data['base_price']) || $data['base_price'] === '' || $data['base_price'] === null) ? 0.00 : (float)$data['base_price'];
        $discountPrice = (!isset($data['discount_price']) || $data['discount_price'] === '') ? null : (float)$data['discount_price'];
        $sgst = isset($data['sgst']) && $data['sgst'] !== '' ? (float)$data['sgst'] : 0.00;
        $cgst = isset($data['cgst']) && $data['cgst'] !== '' ? (float)$data['cgst'] : 0.00;
        $totalGst = isset($data['total_gst']) && $data['total_gst'] !== '' ? (float)$data['total_gst'] : ($sgst + $cgst);
        
        $effectivePrice = ($discountPrice !== null && $discountPrice > 0) ? $discountPrice : $basePrice;
        $maxAmount = isset($data['max_amount']) && $data['max_amount'] !== '' ? (float)$data['max_amount'] : $effectivePrice;

        $stmt->execute([
            'product_id' => $data['product_id'],
            'name' => $data['name'],
            'base_price' => $basePrice,
            'discount_price' => $discountPrice,
            'weight' => (!isset($data['weight']) || $data['weight'] === '' || $data['weight'] === null) ? null : $data['weight'],
            'inventory' => $inventory,
            'current_stock' => $inventory,
            'low_stock_alert' => (!isset($data['low_stock_alert']) || $data['low_stock_alert'] === '' || $data['low_stock_alert'] === null) ? 5 : $data['low_stock_alert'],
            'sku' => empty($data['sku']) ? null : $data['sku'],
            'status' => empty($data['status']) ? 'active' : $data['status'],
            'max_amount' => $maxAmount,
            'sgst' => $sgst,
            'cgst' => $cgst,
            'total_gst' => $totalGst
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data)
    {
        // Calculate the difference in inventory to update current_stock
        $stmtOld = $this->db->prepare("SELECT inventory, current_stock FROM product_variants WHERE id = :id");
        $stmtOld->execute(['id' => $id]);
        $oldData = $stmtOld->fetch();
        $oldTotal = (int)($oldData['inventory'] ?? 0);
        $oldCurrent = (int)($oldData['current_stock'] ?? 0);
        
        $newTotal = (!isset($data['inventory']) || $data['inventory'] === '' || $data['inventory'] === null) ? 0 : (int)$data['inventory'];
        $diff = $newTotal - $oldTotal;
        $newCurrent = max(0, $oldCurrent + $diff);

        $basePrice = (!isset($data['base_price']) || $data['base_price'] === '' || $data['base_price'] === null) ? 0.00 : (float)$data['base_price'];
        $discountPrice = (!isset($data['discount_price']) || $data['discount_price'] === '') ? null : (float)$data['discount_price'];
        $sgst = isset($data['sgst']) && $data['sgst'] !== '' ? (float)$data['sgst'] : 0.00;
        $cgst = isset($data['cgst']) && $data['cgst'] !== '' ? (float)$data['cgst'] : 0.00;
        $totalGst = isset($data['total_gst']) && $data['total_gst'] !== '' ? (float)$data['total_gst'] : ($sgst + $cgst);
        
        $effectivePrice = ($discountPrice !== null && $discountPrice > 0) ? $discountPrice : $basePrice;
        $maxAmount = isset($data['max_amount']) && $data['max_amount'] !== '' ? (float)$data['max_amount'] : $effectivePrice;

        $stmt = $this->db->prepare("
            UPDATE product_variants 
            SET name = :name, 
                base_price = :base_price, 
                discount_price = :discount_price,
                weight = :weight, 
                inventory = :inventory, 
                current_stock = :current_stock,
                low_stock_alert = :low_stock_alert, 
                sku = :sku, 
                status = :status,
                max_amount = :max_amount,
                sgst = :sgst,
                cgst = :cgst,
                total_gst = :total_gst
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'base_price' => $basePrice,
            'discount_price' => $discountPrice,
            'weight' => (!isset($data['weight']) || $data['weight'] === '' || $data['weight'] === null) ? null : $data['weight'],
            'inventory' => $newTotal,
            'current_stock' => $newCurrent,
            'low_stock_alert' => (!isset($data['low_stock_alert']) || $data['low_stock_alert'] === '' || $data['low_stock_alert'] === null) ? 5 : $data['low_stock_alert'],
            'sku' => empty($data['sku']) ? null : $data['sku'],
            'status' => empty($data['status']) ? 'active' : $data['status'],
            'max_amount' => $maxAmount,
            'sgst' => $sgst,
            'cgst' => $cgst,
            'total_gst' => $totalGst
        ]);
    }

    public function deleteUnused(int $productId, array $activeVariantIds)
    {
        if (empty($activeVariantIds)) {
            return $this->deleteByProductId($productId);
        }

        $placeholders = implode(',', array_fill(0, count($activeVariantIds), '?'));
        $stmtFind = $this->db->prepare("SELECT id FROM product_variants WHERE product_id = ? AND id NOT IN ($placeholders)");
        $stmtFind->execute(array_merge([$productId], $activeVariantIds));
        $toDeleteIds = $stmtFind->fetchAll(\PDO::FETCH_COLUMN);

        if (!empty($toDeleteIds)) {
            $delPlaceholders = implode(',', array_fill(0, count($toDeleteIds), '?'));
            $this->db->prepare("DELETE FROM product_variant_attributes WHERE variant_id IN ($delPlaceholders)")->execute($toDeleteIds);
            $this->db->prepare("DELETE FROM product_images WHERE variant_id IN ($delPlaceholders)")->execute($toDeleteIds);
            $this->db->prepare("DELETE FROM product_variants WHERE id IN ($delPlaceholders)")->execute($toDeleteIds);
        }

        return true;
    }

    public function deleteByProductId(int $productId)
    {
        $stmtFind = $this->db->prepare("SELECT id FROM product_variants WHERE product_id = ?");
        $stmtFind->execute([$productId]);
        $toDeleteIds = $stmtFind->fetchAll(\PDO::FETCH_COLUMN);

        if (!empty($toDeleteIds)) {
            $delPlaceholders = implode(',', array_fill(0, count($toDeleteIds), '?'));
            $this->db->prepare("DELETE FROM product_variant_attributes WHERE variant_id IN ($delPlaceholders)")->execute($toDeleteIds);
            $this->db->prepare("DELETE FROM product_images WHERE variant_id IN ($delPlaceholders)")->execute($toDeleteIds);
            $this->db->prepare("DELETE FROM product_variants WHERE id IN ($delPlaceholders)")->execute($toDeleteIds);
        }

        return true;
    }

    public function syncAttributes(int $variantId, array $attributes)
    {
        // First, clear existing attributes for this variant
        $stmt = $this->db->prepare("DELETE FROM product_variant_attributes WHERE variant_id = :variant_id");
        $stmt->execute(['variant_id' => $variantId]);

        if (empty($attributes)) {
            return;
        }

        // Insert new attributes
        $stmt = $this->db->prepare("INSERT INTO product_variant_attributes (variant_id, attribute_id, attribute_value_id) VALUES (:variant_id, :attribute_id, :attribute_value_id)");
        
        foreach ($attributes as $attrId => $attrValueId) {
            if ($attrId && $attrValueId) {
                $stmt->execute([
                    'variant_id' => $variantId,
                    'attribute_id' => (int)$attrId,
                    'attribute_value_id' => (int)$attrValueId
                ]);
            }
        }
    }
}

