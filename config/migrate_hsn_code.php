<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $db = Core\Database::getInstance();

    echo "Running migration: Add hsn_code field to categories table...\n";

    $existingColumns = $db->query("SHOW COLUMNS FROM categories")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('hsn_code', $existingColumns)) {
        $db->exec("ALTER TABLE categories ADD COLUMN hsn_code VARCHAR(10) NULL DEFAULT NULL AFTER slug");
        echo "Added column: hsn_code to categories table.\n";
    } else {
        echo "Column already exists: hsn_code\n";
    }

    echo "Migration completed successfully!\n";
} catch (\Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
    exit(1);
}
