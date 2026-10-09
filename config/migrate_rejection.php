<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

$db = Core\Database::getInstance();

// Fix users.status column to support 'rejected' (8 chars)
$db->exec("ALTER TABLE users MODIFY COLUMN status ENUM('pending','under_review','approved','active','rejected','blocked','deactivated') NOT NULL DEFAULT 'pending'");
echo "users.status column fixed\n";

// Ensure registration_requests has rejection_reason column
$cols = $db->query("SHOW COLUMNS FROM registration_requests LIKE 'rejection_reason'")->fetchAll();
if (empty($cols)) {
    $db->exec("ALTER TABLE registration_requests ADD COLUMN rejection_reason TEXT NULL AFTER status");
    echo "rejection_reason column added to registration_requests\n";
} else {
    echo "rejection_reason already exists\n";
}

echo "Migration complete!\n";
