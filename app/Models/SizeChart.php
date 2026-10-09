<?php
namespace App\Models;

use Core\Model;
use Core\Database;

class SizeChart extends Model
{
    protected string $table = 'size_charts';

    public function __construct()
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS size_charts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            category_id INT NULL DEFAULT 0,
            category_name VARCHAR(255) NULL,
            sub_category_id INT NULL DEFAULT 0,
            sub_category_name VARCHAR(255) NULL,
            dress_type VARCHAR(100) NULL,
            tolerance_note VARCHAR(255) DEFAULT 'Size in inches (+ or - 0.5\")',
            columns_json TEXT NOT NULL,
            rows_json LONGTEXT NOT NULL,
            is_active TINYINT(1) DEFAULT 1,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->exec($sql);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM size_charts ORDER BY sort_order ASC, id DESC");
        return $stmt->fetchAll();
    }

    public function getAllActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM size_charts WHERE is_active = 1 ORDER BY sort_order ASC, id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM size_charts WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getByCategoryId(int $categoryId): array
    {
        return $this->getByCategoryAndSubCategory($categoryId, null);
    }

    public function getByCategoryAndSubCategory(?int $categoryId = null, ?int $subCategoryId = null): array
    {
        $catId = (int)($categoryId ?? 0);
        $subCatId = (int)($subCategoryId ?? 0);

        if ($subCatId > 0 && $catId > 0) {
            $stmt = $this->db->prepare("
                SELECT * FROM size_charts 
                WHERE is_active = 1 
                  AND (sub_category_id = ? OR category_id = ? OR category_id = 0 OR category_id IS NULL) 
                ORDER BY (sub_category_id = ?) DESC, (category_id = ?) DESC, sort_order ASC, id DESC
            ");
            $stmt->execute([$subCatId, $catId, $subCatId, $catId]);
            return $stmt->fetchAll();
        } elseif ($catId > 0) {
            $stmt = $this->db->prepare("
                SELECT * FROM size_charts 
                WHERE is_active = 1 
                  AND (category_id = ? OR category_id = 0 OR category_id IS NULL) 
                ORDER BY (category_id = ?) DESC, sort_order ASC, id DESC
            ");
            $stmt->execute([$catId, $catId]);
            return $stmt->fetchAll();
        }

        return $this->getAllActive();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO size_charts (title, category_id, category_name, sub_category_id, sub_category_name, dress_type, tolerance_note, columns_json, rows_json, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['title'] ?? '',
            (int)($data['category_id'] ?? 0),
            $data['category_name'] ?? '',
            !empty($data['sub_category_id']) ? (int)$data['sub_category_id'] : null,
            $data['sub_category_name'] ?? null,
            $data['dress_type'] ?? '',
            $data['tolerance_note'] ?? 'Size in inches (+ or - 0.5")',
            $data['columns_json'] ?? '[]',
            $data['rows_json'] ?? '[]',
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            (int)($data['sort_order'] ?? 0)
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE size_charts SET title = ?, category_id = ?, category_name = ?, sub_category_id = ?, sub_category_name = ?, dress_type = ?, tolerance_note = ?, columns_json = ?, rows_json = ?, is_active = ?, sort_order = ? WHERE id = ?");
        return $stmt->execute([
            $data['title'] ?? '',
            (int)($data['category_id'] ?? 0),
            $data['category_name'] ?? '',
            !empty($data['sub_category_id']) ? (int)$data['sub_category_id'] : null,
            $data['sub_category_name'] ?? null,
            $data['dress_type'] ?? '',
            $data['tolerance_note'] ?? 'Size in inches (+ or - 0.5")',
            $data['columns_json'] ?? '[]',
            $data['rows_json'] ?? '[]',
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            (int)($data['sort_order'] ?? 0),
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM size_charts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleActive(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE size_charts SET is_active = IF(is_active=1, 0, 1) WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
