<?php
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/../core/Database.php';

try {
    $db = Core\Database::getInstance();
    
    // Check if column min_cart_value exists in categories table
    $stmt = $db->query("SHOW COLUMNS FROM categories LIKE 'min_cart_value'");
    $col = $stmt->fetch();
    
    if (!$col) {
        $db->exec("ALTER TABLE categories ADD COLUMN min_cart_value DECIMAL(10,2) NULL DEFAULT NULL AFTER min_order_value");
        echo "Successfully added 'min_cart_value' column to 'categories' table.\n";
    } else {
        echo "'min_cart_value' column already exists in 'categories' table.\n";
    }
} catch (\Throwable $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
