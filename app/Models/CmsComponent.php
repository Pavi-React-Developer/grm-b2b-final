<?php
namespace App\Models;

use Core\Model;

class CmsComponent extends Model
{
    public function getAllHeroBanners()
    {
        $stmt = $this->db->prepare("SELECT * FROM cms_components WHERE section_type = 'hero_banner' ORDER BY updated_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllProductCarousels()
    {
        $stmt = $this->db->prepare("SELECT * FROM cms_components WHERE section_type = 'product_carousel' ORDER BY updated_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllCategoryCarousels()
    {
        $stmt = $this->db->prepare("SELECT * FROM cms_components WHERE section_type = 'category_carousel' ORDER BY updated_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllCategoriesGrids()
    {
        $stmt = $this->db->prepare("SELECT * FROM cms_components WHERE section_type = 'categories_grid' ORDER BY updated_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM cms_components WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO cms_components (id, section_type, content_data, is_active)
            VALUES (?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['id'],
            $data['section_type'],
            $data['content_data'],
            $data['is_active']
        ]);
        
        return $data['id'];
    }

    public function update($id, array $data)
    {
        $stmt = $this->db->prepare("
            UPDATE cms_components 
            SET content_data = ?, is_active = ?
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['content_data'],
            $data['is_active'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM cms_components WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
