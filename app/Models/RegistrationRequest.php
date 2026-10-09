<?php
namespace App\Models;

use Core\Model;

class RegistrationRequest extends Model
{
    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO registration_requests (user_id, gst_flag, status)
            VALUES (:user_id, :gst_flag, 'pending')
        ");
        
        $stmt->execute([
            'user_id' => $data['user_id'],
            'gst_flag' => $data['gst_flag']
        ]);
        
        return $this->db->lastInsertId();
    }
}
