<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../core/Database.php';

$db = Core\Database::getInstance();
$rows = $db->query('SELECT id, original_filename, cloudinary_url, public_id, created_at FROM media_uploads ORDER BY id DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
