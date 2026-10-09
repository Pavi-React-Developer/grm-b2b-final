<?php
namespace App\Models;

use Core\Model;

class BusinessProfile extends Model
{
    public function findByUserId(int $userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM business_profiles WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO business_profiles (user_id, business_name, shop_location, gst_number, pan_number, no_gst_reason, instagram_link)
            VALUES (:user_id, :business_name, :shop_location, :gst_number, :pan_number, :no_gst_reason, :instagram_link)
        ");
        
        $stmt->execute([
            'user_id' => $data['user_id'],
            'business_name' => $data['business_name'],
            'shop_location' => $data['shop_location'] ?? null,
            'gst_number' => $data['gst_number'] ?? null,
            'pan_number' => $data['pan_number'] ?? null,
            'no_gst_reason' => $data['no_gst_reason'] ?? null,
            'instagram_link' => $data['instagram_link'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }
}
