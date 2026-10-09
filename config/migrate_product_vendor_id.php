<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $db = Core\Database::getInstance();
    echo "Starting Product Vendor ID Migration...\n";

    // 1. Add vendor_id column to products table if not present
    $cols = $db->query("SHOW COLUMNS FROM products LIKE 'vendor_id'")->fetchAll();
    if (empty($cols)) {
        $db->exec("ALTER TABLE products ADD COLUMN vendor_id INT NULL AFTER id");
        echo "1. Added 'vendor_id' column to 'products' table.\n";
    } else {
        echo "1. 'vendor_id' column already exists in 'products' table.\n";
    }

    // 2. Add foreign key or index if not present
    try {
        $db->exec("ALTER TABLE products ADD CONSTRAINT fk_products_vendor FOREIGN KEY (vendor_id) REFERENCES users(id) ON DELETE SET NULL");
        echo "2. Added foreign key constraint 'fk_products_vendor'.\n";
    } catch (\Exception $e) {
        // May already exist
        echo "2. Foreign key/index check completed: " . $e->getMessage() . "\n";
    }

    echo "\nProduct Vendor ID Migration Completed Successfully!\n";
} catch (\Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
