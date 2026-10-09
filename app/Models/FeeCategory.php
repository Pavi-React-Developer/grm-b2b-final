<?php
namespace App\Models;

use Core\Model;

class FeeCategory extends Model
{
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM fee_categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getAllActive()
    {
        $stmt = $this->db->query("SELECT * FROM fee_categories WHERE is_active = 1 ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM fee_categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM fee_categories WHERE name = :name");
        $stmt->execute(['name' => trim($name)]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $name = trim($data['name']);
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        $stmt = $this->db->prepare("INSERT INTO fee_categories (name, is_active) VALUES (:name, :is_active)");
        $stmt->execute([
            'name' => $name,
            'is_active' => $isActive
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        $name = trim($data['name']);
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        $stmt = $this->db->prepare("UPDATE fee_categories SET name = :name, is_active = :is_active WHERE id = :id");
        return $stmt->execute([
            'name' => $name,
            'is_active' => $isActive,
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM fee_categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
