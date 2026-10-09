<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/constants.php';
require_once ROOT_PATH . '/core/Database.php';

try {
    $db = \Core\Database::getInstance();
    echo "Starting migration for Settings (Bank Accounts) and Support modules...\n";

    // 1. Create vendor_bank_accounts table
    $db->exec("
        CREATE TABLE IF NOT EXISTS `vendor_bank_accounts` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `vendor_id` INT NOT NULL,
            `bank_name` VARCHAR(150) NOT NULL,
            `account_holder_name` VARCHAR(150) NOT NULL,
            `account_number` VARCHAR(50) NOT NULL,
            `ifsc_code` VARCHAR(25) NOT NULL,
            `branch_name` VARCHAR(150) NULL,
            `account_type` ENUM('current', 'savings') NOT NULL DEFAULT 'current',
            `upi_id` VARCHAR(100) NULL,
            `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
            `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_vba_vendor` (`vendor_id`),
            INDEX `idx_vba_primary` (`is_primary`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "✓ Created table vendor_bank_accounts.\n";

    // 2. Migrate existing bank details from vendor_profiles into vendor_bank_accounts
    $existingVendors = $db->query("
        SELECT user_id, bank_name, account_number, ifsc_code, account_holder_name 
        FROM vendor_profiles 
        WHERE account_number IS NOT NULL AND TRIM(account_number) != ''
    ")->fetchAll();

    foreach ($existingVendors as $vp) {
        $checkStmt = $db->prepare("SELECT id FROM vendor_bank_accounts WHERE vendor_id = ? AND account_number = ?");
        $checkStmt->execute([$vp['user_id'], $vp['account_number']]);
        if (!$checkStmt->fetch()) {
            $insertStmt = $db->prepare("
                INSERT INTO vendor_bank_accounts 
                (vendor_id, bank_name, account_holder_name, account_number, ifsc_code, is_primary) 
                VALUES (?, ?, ?, ?, ?, 1)
            ");
            $insertStmt->execute([
                $vp['user_id'],
                $vp['bank_name'] ?: 'Primary Bank',
                $vp['account_holder_name'] ?: 'Account Holder',
                $vp['account_number'],
                $vp['ifsc_code'] ?: 'IFSC0000',
            ]);
            echo "  -> Seeded primary bank account for vendor #{$vp['user_id']}.\n";
        }
    }

    // 3. Create support_tickets table
    $db->exec("
        CREATE TABLE IF NOT EXISTS `support_tickets` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `ticket_number` VARCHAR(50) UNIQUE NOT NULL,
            `vendor_id` INT NOT NULL,
            `subject` VARCHAR(255) NOT NULL,
            `category` VARCHAR(100) NOT NULL,
            `priority` ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
            `status` ENUM('open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'open',
            `message` TEXT NOT NULL,
            `attachment` VARCHAR(255) NULL,
            `admin_notes` TEXT NULL,
            `last_reply_by` ENUM('vendor', 'admin') NOT NULL DEFAULT 'vendor',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_st_vendor` (`vendor_id`),
            INDEX `idx_st_status` (`status`),
            INDEX `idx_st_priority` (`priority`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "✓ Created table support_tickets.\n";

    // 4. Create support_ticket_messages table
    $db->exec("
        CREATE TABLE IF NOT EXISTS `support_ticket_messages` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `ticket_id` INT NOT NULL,
            `sender_id` INT NOT NULL,
            `sender_role` ENUM('vendor', 'admin', 'super_admin', 'staff') NOT NULL,
            `message` TEXT NOT NULL,
            `attachment` VARCHAR(255) NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_stm_ticket` (`ticket_id`),
            INDEX `idx_stm_sender` (`sender_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "✓ Created table support_ticket_messages.\n";

    echo "Migration completed successfully!\n";
} catch (\Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
}
