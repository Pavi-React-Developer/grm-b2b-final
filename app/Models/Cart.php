<?php
namespace App\Models;

use Core\Model;

class Cart extends Model
{
    public function getItems(int $userId)
    {
        $sql = "
            SELECT ci.id as cart_item_id, ci.quantity, ci.variant_id,
                   pv.name as variant_name, pv.base_price as variant_price, pv.discount_price as variant_discount_price,
                   p.id as product_id, p.name, p.wholesale_price, p.slug, p.moq_override,
                   p.category_id, p.sub_category_id, c.name as category_name,
                   c.moq as category_moq,
                   c.sgst as category_sgst, c.cgst as category_cgst,
                   pv.sgst as variant_sgst, pv.cgst as variant_cgst, pv.total_gst as variant_total_gst,
                   p.sgst as product_sgst, p.cgst as product_cgst, p.total_gst as product_total_gst,
                   COALESCE(pv.weight, p.weight) as weight,
                   COALESCE(pv.weight_unit, p.weight_unit, 'kg') as weight_unit,
                   COALESCE(pv.max_amount, p.max_amount, 0.00) as max_amount,
                   COALESCE(
                       pv.current_stock, 
                       (SELECT SUM(current_stock) FROM product_variants WHERE product_id = p.id), 
                       p.stock_quantity
                   ) as available_stock,
                   COALESCE(
                       (SELECT image_path FROM product_images WHERE variant_id = ci.variant_id ORDER BY is_primary DESC, id ASC LIMIT 1),
                       (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1)
                   ) as primary_image,
                   (SELECT av.color_code 
                    FROM product_variant_attributes pva 
                    JOIN attribute_values av ON pva.attribute_value_id = av.id 
                    JOIN product_variants pv_col ON pva.variant_id = pv_col.id 
                    WHERE pv_col.id = ci.variant_id AND av.color_code IS NOT NULL AND av.color_code != '' 
                    LIMIT 1) as color_code
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            LEFT JOIN product_variants pv ON ci.variant_id = pv.id
            JOIN categories c ON p.category_id = c.id
            WHERE ci.user_id = :user_id
            ORDER BY ci.id ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function addItem(int $userId, int $productId, int $quantity, ?int $variantId = null, ?int $maxStock = null)
    {
        if ($variantId !== null) {
            $sqlCheck = "SELECT id, quantity FROM cart_items 
                         WHERE user_id = :uid AND product_id = :pid AND variant_id = :vid";
            $stmt = $this->db->prepare($sqlCheck);
            $stmt->execute(['uid' => $userId, 'pid' => $productId, 'vid' => $variantId]);
            $existing = $stmt->fetch();

            if ($existing) {
                $newQuantity = $existing['quantity'] + $quantity;
                if ($maxStock !== null && $maxStock > 0 && $newQuantity > $maxStock) {
                    $newQuantity = $maxStock;
                }
                $update = $this->db->prepare("UPDATE cart_items SET quantity = :q WHERE id = :id");
                return $update->execute(['q' => $newQuantity, 'id' => $existing['id']]);
            } else {
                if ($maxStock !== null && $maxStock > 0 && $quantity > $maxStock) {
                    $quantity = $maxStock;
                }
                $insert = $this->db->prepare("INSERT INTO cart_items (user_id, product_id, variant_id, quantity) VALUES (:uid, :pid, :vid, :q)");
                return $insert->execute(['uid' => $userId, 'pid' => $productId, 'vid' => $variantId, 'q' => $quantity]);
            }
        } else {
            $sqlCheck = "SELECT id, quantity FROM cart_items 
                         WHERE user_id = :uid AND product_id = :pid AND variant_id IS NULL";
            $stmt = $this->db->prepare($sqlCheck);
            $stmt->execute(['uid' => $userId, 'pid' => $productId]);
            $existing = $stmt->fetch();

            if ($existing) {
                $newQuantity = $existing['quantity'] + $quantity;
                if ($maxStock !== null && $maxStock > 0 && $newQuantity > $maxStock) {
                    $newQuantity = $maxStock;
                }
                $update = $this->db->prepare("UPDATE cart_items SET quantity = :q WHERE id = :id");
                return $update->execute(['q' => $newQuantity, 'id' => $existing['id']]);
            } else {
                if ($maxStock !== null && $maxStock > 0 && $quantity > $maxStock) {
                    $quantity = $maxStock;
                }
                $insert = $this->db->prepare("INSERT INTO cart_items (user_id, product_id, variant_id, quantity) VALUES (:uid, :pid, NULL, :q)");
                return $insert->execute(['uid' => $userId, 'pid' => $productId, 'q' => $quantity]);
            }
        }
    }

    public function updateQuantity(int $cartItemId, int $userId, int $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeItem($cartItemId, $userId);
        }
        $stmt = $this->db->prepare("UPDATE cart_items SET quantity = :q WHERE id = :id AND user_id = :uid");
        return $stmt->execute(['q' => $quantity, 'id' => $cartItemId, 'uid' => $userId]);
    }

    public function removeItem(int $cartItemId, int $userId)
    {
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE id = :id AND user_id = :uid");
        return $stmt->execute(['id' => $cartItemId, 'uid' => $userId]);
    }

    public function clearCart(int $userId)
    {
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE user_id = :uid");
        return $stmt->execute(['uid' => $userId]);
    }
}
