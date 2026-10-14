<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $db = Core\Database::getInstance();

    $sql = "
    CREATE TABLE IF NOT EXISTS user_email_logs (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT NOT NULL,
        old_email VARCHAR(255) NOT NULL,
        new_email VARCHAR(255) NOT NULL,
        reason TEXT NOT NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $db->exec($sql);
    echo "user_email_logs table created or already exists.\n";
} catch (\Throwable $e) {
    echo "Error running migration: " . $e->getMessage() . "\n";
    exit(1);
}
