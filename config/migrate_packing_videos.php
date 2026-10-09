<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $db = Core\Database::getInstance();

    echo "Running migration: Add packing videos & retention fields to orders table...\n";

    $columnsToAdd = [
        'packing_video_1' => "VARCHAR(255) NULL AFTER notes",
        'packing_video_2' => "VARCHAR(255) NULL AFTER packing_video_1",
        'packed_at' => "DATETIME NULL AFTER packing_video_2",
        'delivered_at' => "DATETIME NULL AFTER packed_at",
        'packing_video_expires_at' => "DATETIME NULL AFTER delivered_at"
    ];

    $existingColumns = $db->query("SHOW COLUMNS FROM orders")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($columnsToAdd as $col => $definition) {
        if (!in_array($col, $existingColumns)) {
            $db->exec("ALTER TABLE orders ADD COLUMN {$col} {$definition}");
            echo "Added column: {$col}\n";
        } else {
            echo "Column already exists: {$col}\n";
        }
    }

    echo "Migration completed successfully!\n";
} catch (\Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
    exit(1);
}
