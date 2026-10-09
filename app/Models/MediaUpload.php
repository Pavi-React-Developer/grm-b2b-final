<?php
namespace App\Models;

use Core\Database;
use PDO;

class MediaUpload
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByHash(string $hash)
    {
        $stmt = $this->db->prepare("SELECT * FROM media_uploads WHERE file_hash = :hash LIMIT 1");
        $stmt->execute(['hash' => $hash]);
        return $stmt->fetch();
    }
    
    public function findById(int $id)
    {
        if ($id >= 1000000) {
            $realId = $id - 1000000;
            $stmt = $this->db->prepare("SELECT (1000000 + id) as id, SUBSTRING_INDEX(file_path, '/', -1) as original_filename, MD5(file_path) as file_hash, file_path as cloudinary_url, '' as public_id, 0 as file_size, user_id as uploaded_by, created_at, 'image' as file_type FROM media WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $realId]);
            return $stmt->fetch();
        }
        $stmt = $this->db->prepare("SELECT * FROM media_uploads WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO media_uploads (original_filename, file_hash, cloudinary_url, public_id, file_size, uploaded_by)
                VALUES (:original_filename, :file_hash, :cloudinary_url, :public_id, :file_size, :uploaded_by)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'original_filename' => $data['original_filename'],
            'file_hash' => $data['file_hash'],
            'cloudinary_url' => $data['cloudinary_url'],
            'public_id' => $data['public_id'],
            'file_size' => $data['file_size'] ?? 0,
            'uploaded_by' => $data['uploaded_by'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }
    
    public function getAll(?int $vendorId = null, ?string $scope = null)
    {
        if ($vendorId !== null) {
            $sql = "
                SELECT 
                    m.id,
                    m.original_filename,
                    m.file_hash,
                    m.cloudinary_url,
                    m.public_id,
                    m.file_size,
                    m.uploaded_by,
                    m.created_at,
                    u.name as uploader_name,
                    u.role as uploader_role,
                    vp.store_name
                FROM media_uploads m
                LEFT JOIN users u ON m.uploaded_by = u.id
                LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
                WHERE m.uploaded_by = :vendor_id_1
                  AND m.public_id NOT LIKE 'shop_img_%'
                  AND m.original_filename NOT LIKE 'vendor_doc_%'
                  AND m.cloudinary_url NOT LIKE 'uploads/vendors/%'

                UNION ALL

                SELECT 
                    (1000000 + med.id) as id,
                    SUBSTRING_INDEX(med.file_path, '/', -1) as original_filename,
                    MD5(med.file_path) as file_hash,
                    med.file_path as cloudinary_url,
                    '' as public_id,
                    0 as file_size,
                    med.user_id as uploaded_by,
                    med.created_at,
                    u.name as uploader_name,
                    u.role as uploader_role,
                    vp.store_name
                FROM media med
                LEFT JOIN users u ON med.user_id = u.id
                LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
                WHERE med.user_id = :vendor_id_2
                  AND (med.purpose IS NULL OR med.purpose NOT IN ('shop_image', 'vendor_verification', 'shop_verification', 'business_verification'))
                  AND med.file_path NOT LIKE 'uploads/vendors/%'
                  AND NOT EXISTS (
                      SELECT 1 FROM media_uploads mu 
                      WHERE mu.uploaded_by = med.user_id 
                        AND (mu.cloudinary_url = med.file_path OR mu.original_filename = SUBSTRING_INDEX(med.file_path, '/', -1))
                  )

                ORDER BY created_at DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['vendor_id_1' => $vendorId, 'vendor_id_2' => $vendorId]);
            return $stmt->fetchAll();
        }

        if ($scope === 'admin') {
            $sql = "
                SELECT m.*, u.name as uploader_name, u.role as uploader_role, vp.store_name
                FROM media_uploads m
                LEFT JOIN users u ON m.uploaded_by = u.id
                LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
                WHERE (m.uploaded_by IS NULL OR u.role IN ('super_admin', 'admin', 'staff'))
                  AND (u.role != 'vendor' OR u.role IS NULL)
                  AND (m.uploaded_by NOT IN (SELECT id FROM users WHERE role = 'vendor') OR m.uploaded_by IS NULL)
                  AND m.public_id NOT LIKE 'shop_img_%'
                  AND m.original_filename NOT LIKE 'vendor_doc_%'
                  AND m.cloudinary_url NOT LIKE 'uploads/vendors/%'
                ORDER BY m.created_at DESC
            ";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        }

        if ($scope === 'vendors') {
            $sql = "
                SELECT 
                    m.id,
                    m.original_filename,
                    m.file_hash,
                    m.cloudinary_url,
                    m.public_id,
                    m.file_size,
                    m.uploaded_by,
                    m.created_at,
                    u.name as uploader_name,
                    u.role as uploader_role,
                    vp.store_name
                FROM media_uploads m
                LEFT JOIN users u ON m.uploaded_by = u.id
                LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
                WHERE (u.role = 'vendor' OR m.uploaded_by IN (SELECT id FROM users WHERE role = 'vendor'))
                  AND m.public_id NOT LIKE 'shop_img_%'
                  AND m.original_filename NOT LIKE 'vendor_doc_%'
                  AND m.cloudinary_url NOT LIKE 'uploads/vendors/%'

                UNION ALL

                SELECT 
                    (1000000 + med.id) as id,
                    SUBSTRING_INDEX(med.file_path, '/', -1) as original_filename,
                    MD5(med.file_path) as file_hash,
                    med.file_path as cloudinary_url,
                    '' as public_id,
                    0 as file_size,
                    med.user_id as uploaded_by,
                    med.created_at,
                    u.name as uploader_name,
                    u.role as uploader_role,
                    vp.store_name
                FROM media med
                JOIN users u ON med.user_id = u.id AND u.role = 'vendor'
                LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
                WHERE (med.purpose IS NULL OR med.purpose NOT IN ('shop_image', 'vendor_verification', 'shop_verification', 'business_verification'))
                  AND med.file_path NOT LIKE 'uploads/vendors/%'
                  AND NOT EXISTS (
                    SELECT 1 FROM media_uploads mu 
                    WHERE mu.uploaded_by = med.user_id 
                      AND (mu.cloudinary_url = med.file_path OR mu.original_filename = SUBSTRING_INDEX(med.file_path, '/', -1))
                )

                ORDER BY created_at DESC
            ";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        }

        // All platform media (when explicitly requested)
        $sql = "
            SELECT m.*, u.name as uploader_name, u.role as uploader_role, vp.store_name
            FROM media_uploads m
            LEFT JOIN users u ON m.uploaded_by = u.id
            LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE (m.uploaded_by IS NOT NULL OR u.role IS NULL OR u.role != 'buyer')
              AND m.public_id NOT LIKE 'shop_img_%'
              AND m.original_filename NOT LIKE 'vendor_doc_%'
              AND m.cloudinary_url NOT LIKE 'uploads/vendors/%'
              AND NOT EXISTS (SELECT 1 FROM media bm WHERE bm.file_path = m.cloudinary_url)
              AND NOT EXISTS (SELECT 1 FROM users bu WHERE bu.profile_picture = m.cloudinary_url)
            ORDER BY m.created_at DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function delete(int $id)
    {
        if ($id >= 1000000) {
            $realId = $id - 1000000;
            $stmt = $this->db->prepare("DELETE FROM media WHERE id = :id");
            return $stmt->execute(['id' => $realId]);
        }
        $stmt = $this->db->prepare("DELETE FROM media_uploads WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
