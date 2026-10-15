<?php
namespace App\Models;

use Core\Model;

class Media extends Model
{
    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO media (user_id, file_path, file_type, purpose)
            VALUES (:user_id, :file_path, :file_type, :purpose)
        ");
        
        $stmt->execute([
            'user_id' => $data['user_id'],
            'file_path' => $data['file_path'],
            'file_type' => $data['file_type'],
            'purpose' => $data['purpose'] ?? 'shop_verification'
        ]);
        return $this->db->lastInsertId();
    }

    public function createBatch(array $records): bool
    {
        if (empty($records)) return true;

        $values = [];
        $params = [];
        foreach ($records as $r) {
            $values[] = "(?, ?, ?, ?)";
            $params[] = $r['user_id'];
            $params[] = $r['file_path'];
            $params[] = $r['file_type'];
            $params[] = $r['purpose'] ?? 'shop_verification';
        }

        $sql = "INSERT INTO media (user_id, file_path, file_type, purpose) VALUES " . implode(', ', $values);
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
