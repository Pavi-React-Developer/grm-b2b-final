<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/constants.php';
require_once ROOT_PATH . '/core/Database.php';

try {
    $db = \Core\Database::getInstance();
    echo "Starting migration for dynamic Vendor Types module...\n";

    // 1. Create vendor_types table
    $db->exec("
        CREATE TABLE IF NOT EXISTS `vendor_types` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `slug` VARCHAR(100) NOT NULL UNIQUE,
            `description` VARCHAR(255) NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `display_order` INT NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_vt_slug` (`slug`),
            INDEX `idx_vt_active` (`is_active`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "✓ Created table 'vendor_types'.\n";

    // 2. Modify vendor_profiles.vendor_type to VARCHAR(100) to support dynamic types
    try {
        $db->exec("ALTER TABLE `vendor_profiles` MODIFY `vendor_type` VARCHAR(100) NOT NULL DEFAULT 'wholesaler'");
        echo "✓ Altered 'vendor_profiles.vendor_type' column to VARCHAR(100).\n";
    } catch (\PDOException $e) {
        echo "Note: " . $e->getMessage() . "\n";
    }

    // 3. Seed default vendor types with ONLY 'Wholesaler' active and others inactive
    $defaultTypes = [
        [
            'name' => 'Wholesaler',
            'slug' => 'wholesaler',
            'description' => 'Bulk wholesale suppliers and B2B stockists',
            'is_active' => 1,
            'display_order' => 1
        ],
        [
            'name' => 'Manufacturer',
            'slug' => 'manufacturer',
            'description' => 'Direct product manufacturers and factories',
            'is_active' => 0,
            'display_order' => 2
        ],
        [
            'name' => 'Distributor',
            'slug' => 'distributor',
            'description' => 'Authorized regional distributors and supply agents',
            'is_active' => 0,
            'display_order' => 3
        ],
        [
            'name' => 'Brand Owner',
            'slug' => 'brand',
            'description' => 'Direct trademark / brand proprietors and creators',
            'is_active' => 0,
            'display_order' => 4
        ]
    ];

    $checkStmt = $db->prepare("SELECT id FROM `vendor_types` WHERE `slug` = ?");
    $insertStmt = $db->prepare("
        INSERT INTO `vendor_types` (`name`, `slug`, `description`, `is_active`, `display_order`)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($defaultTypes as $vt) {
        $checkStmt->execute([$vt['slug']]);
        if (!$checkStmt->fetch()) {
            $insertStmt->execute([
                $vt['name'],
                $vt['slug'],
                $vt['description'],
                $vt['is_active'],
                $vt['display_order']
            ]);
            echo "✓ Seeded vendor type: {$vt['name']} (Active: {$vt['is_active']})\n";
        }
    }

    echo "Migration completed successfully!\n";
} catch (\Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
