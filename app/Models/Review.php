<?php
namespace App\Models;

use Core\Model;

class Review extends Model
{
    /**
     * Get products the user has ordered and paid for but hasn't reviewed yet.
     */
    public function getPendingReviews(int $userId)
    {
        $sql = "
            SELECT 
                oi.product_id,
                oi.order_id,
                p.name AS product_name,
                (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) AS primary_image,
                o.order_number,
                o.created_at AS order_date
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = ? 
              AND o.payment_status = 'paid'
              AND o.status = 'delivered'
              AND NOT EXISTS (
                  SELECT 1 FROM reviews r 
                  WHERE r.product_id = oi.product_id 
                    AND r.user_id = o.user_id 
                    AND r.order_id = o.id
              )
            GROUP BY oi.product_id, oi.order_id, p.name, o.order_number, o.created_at
            ORDER BY o.created_at DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Submit a new review
     */
    public function createReview(array $data)
    {
        // First check if a review already exists for this product/user/order combination
        $stmtCheck = $this->db->prepare("
            SELECT id FROM reviews 
            WHERE product_id = ? AND user_id = ? AND order_id = ?
        ");
        $stmtCheck->execute([
            $data['product_id'],
            $data['user_id'],
            $data['order_id']
        ]);
        
        if ($stmtCheck->fetch()) {
            throw new \Exception("You have already reviewed this product for this order.");
        }

        $stmt = $this->db->prepare("
            INSERT INTO reviews (product_id, user_id, order_id, rating, review_text, photos, is_approved)
            VALUES (:pid, :uid, :oid, :rating, :text, :photos, 0)
        ");

        return $stmt->execute([
            'pid' => $data['product_id'],
            'uid' => $data['user_id'],
            'oid' => $data['order_id'],
            'rating' => $data['rating'],
            'text' => $data['review_text'] ?? null,
            'photos' => isset($data['photos']) ? json_encode($data['photos']) : null
        ]);
    }

    /**
     * Get all reviews submitted by a specific user
     */
    public function getUserReviews(int $userId)
    {
        $sql = "
            SELECT 
                r.*,
                p.name AS product_name,
                (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) AS primary_image
            FROM reviews r
            JOIN products p ON r.product_id = p.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Get all reviews for the Admin panel
     */
    public function getAdminReviews()
    {
        $sql = "
            SELECT 
                r.*,
                p.name AS product_name,
                u.name AS reviewer_name,
                u.email AS reviewer_email
            FROM reviews r
            JOIN products p ON r.product_id = p.id
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC
        ";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get all reviews for a specific vendor's products
     */
    public function getVendorReviews(int $vendorId)
    {
        $sql = "
            SELECT 
                r.*,
                p.name AS product_name,
                u.name AS reviewer_name,
                u.email AS reviewer_email
            FROM reviews r
            JOIN products p ON r.product_id = p.id
            JOIN users u ON r.user_id = u.id
            WHERE p.vendor_id = :vendor_id
            ORDER BY r.created_at DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['vendor_id' => $vendorId]);
        return $stmt->fetchAll();
    }

    /**
     * Check if a review belongs to a vendor's product
     */
    public function isReviewOwnedByVendor(int $reviewId, int $vendorId): bool
    {
        $sql = "
            SELECT r.id 
            FROM reviews r
            JOIN products p ON r.product_id = p.id
            WHERE r.id = :review_id AND p.vendor_id = :vendor_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'review_id' => $reviewId,
            'vendor_id' => $vendorId
        ]);
        return (bool)$stmt->fetch();
    }

    /**
     * Approve or reject a review
     */
    public function updateStatus(int $reviewId, bool $isApproved)
    {
        $stmt = $this->db->prepare("UPDATE reviews SET is_approved = ? WHERE id = ?");
        $success = $stmt->execute([$isApproved ? 1 : 0, $reviewId]);
        
        if ($success) {
            // Fetch the product_id for this review
            $stmt = $this->db->prepare("SELECT product_id FROM reviews WHERE id = ?");
            $stmt->execute([$reviewId]);
            $review = $stmt->fetch();
            
            if ($review) {
                $this->recalculateProductRating($review['product_id']);
            }
        }
        
        return $success;
    }

    /**
     * Recalculate average rating and number of reviews for a product
     */
    public function recalculateProductRating(int $productId)
    {
        $sql = "
            SELECT 
                COUNT(*) as num_reviews,
                AVG(rating) as average_rating
            FROM reviews 
            WHERE product_id = ? AND is_approved = 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        $stats = $stmt->fetch();

        $numReviews = $stats['num_reviews'] ?? 0;
        $avgRating = $stats['average_rating'] ?? 0.00;

        $updateStmt = $this->db->prepare("
            UPDATE products 
            SET num_reviews = ?, average_rating = ?
            WHERE id = ?
        ");
        return $updateStmt->execute([$numReviews, round($avgRating, 2), $productId]);
    }

    /**
     * Get approved reviews for a specific product
     */
    public function getApprovedProductReviews(int $productId)
    {
        $sql = "
            SELECT 
                r.*,
                u.name AS reviewer_name
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.product_id = ? AND r.is_approved = 1
            ORDER BY r.created_at DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }
}
