<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $db = Core\Database::getInstance();
    
    // Check if color_code column exists in attribute_values
    $columns = $db->query("SHOW COLUMNS FROM attribute_values LIKE 'color_code'")->fetchAll();
    if (empty($columns)) {
        $db->exec("ALTER TABLE attribute_values ADD COLUMN color_code VARCHAR(50) NULL DEFAULT NULL AFTER value");
        echo "Successfully added 'color_code' column to 'attribute_values'.\n";
    } else {
        echo "'color_code' column already exists in 'attribute_values'.\n";
    }
} catch (\Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
}
