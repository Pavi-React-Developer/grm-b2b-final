<?php
namespace App\Models;

use Core\Database;

class OrderRule
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        $sql = "
            SELECT r.*, c.name as category_name 
            FROM order_rules r
            JOIN categories c ON r.category_id = c.id
            ORDER BY c.name ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getActiveRules()
    {
        $sql = "
            SELECT r.*, c.name as category_name 
            FROM order_rules r
            JOIN categories c ON r.category_id = c.id
            WHERE r.status = 'active'
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM order_rules WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        // Check for duplicates
        if ($this->ruleExists($data['category_id'])) {
            throw new \Exception("An order rule already exists for this main category.");
        }

        $sql = "INSERT INTO order_rules (category_id, second_category_rules, min_amount, status) 
                VALUES (:category_id, :second_category_rules, :min_amount, :status)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'category_id' => $data['category_id'],
            'second_category_rules' => $data['second_category_rules'] ?: null,
            'min_amount' => $data['min_amount'],
            'status' => $data['status'] ?? 'active'
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        // Check for duplicates if category changed
        if ($this->ruleExists($data['category_id'], $id)) {
            throw new \Exception("An order rule already exists for this main category.");
        }

        $sql = "UPDATE order_rules 
                SET category_id = :category_id, 
                    second_category_rules = :second_category_rules, 
                    min_amount = :min_amount, 
                    status = :status 
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'category_id' => $data['category_id'],
            'second_category_rules' => $data['second_category_rules'] ?: null,
            'min_amount' => $data['min_amount'],
            'status' => $data['status'] ?? 'active',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM order_rules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function ruleExists($categoryId, $excludeId = null)
    {
        $sql = "SELECT id FROM order_rules WHERE category_id = :category_id";
        $params = ['category_id' => $categoryId];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }
}
