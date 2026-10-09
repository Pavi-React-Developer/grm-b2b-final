<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/constants.php';
require_once ROOT_PATH . '/core/Database.php';

try {
    $db = \Core\Database::getInstance();
    
    // 1. Add upi_id and upi_name to vendor_profiles if they don't exist
    $cols = $db->query("SHOW COLUMNS FROM `vendor_profiles`")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('upi_id', $cols)) {
        $db->exec("ALTER TABLE `vendor_profiles` ADD COLUMN `upi_id` VARCHAR(100) NULL AFTER `account_holder_name`");
        echo "✓ Added 'upi_id' column to 'vendor_profiles' table.\n";
    } else {
        echo "• 'upi_id' already exists in 'vendor_profiles'.\n";
    }

    if (!in_array('upi_name', $cols)) {
        $db->exec("ALTER TABLE `vendor_profiles` ADD COLUMN `upi_name` VARCHAR(150) NULL AFTER `upi_id`");
        echo "✓ Added 'upi_name' column to 'vendor_profiles' table.\n";
    } else {
        echo "• 'upi_name' already exists in 'vendor_profiles'.\n";
    }

    echo "Migration completed successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
