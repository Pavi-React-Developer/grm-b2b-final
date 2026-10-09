<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

$db = Core\Database::getInstance();

try {
    echo "Creating settings table...\n";

    $db->exec("
    CREATE TABLE IF NOT EXISTS settings (
        setting_key VARCHAR(100) PRIMARY KEY,
        setting_value TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
    ");

    // Insert default value for cancellations_enabled
    $db->exec("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES ('cancellations_enabled', '1')");

    echo "Migration completed successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
