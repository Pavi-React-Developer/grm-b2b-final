<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $db = Core\Database::getInstance();

    echo "Starting Vendor Database Migration...\n";

    // 1. Update role column in users table to allow 'vendor'
    $db->exec("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'buyer'");
    echo "1. Updated 'users.role' column to support 'vendor'.\n";

    // 2. Add unique_vendor_id column to users table if not existing
    $cols = $db->query("SHOW COLUMNS FROM users LIKE 'unique_vendor_id'")->fetchAll();
    if (empty($cols)) {
        $db->exec("ALTER TABLE users ADD COLUMN unique_vendor_id VARCHAR(50) NULL AFTER unique_buyer_id");
        echo "2. Added 'unique_vendor_id' column to 'users' table.\n";
    } else {
        echo "2. 'unique_vendor_id' column already exists in 'users' table.\n";
    }

    // 3. Create vendor_profiles table
    $db->exec("
    CREATE TABLE IF NOT EXISTS vendor_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNIQUE NOT NULL,
        unique_vendor_id VARCHAR(50) UNIQUE,
        store_name VARCHAR(255) NOT NULL,
        company_name VARCHAR(255),
        vendor_type ENUM('manufacturer', 'wholesaler', 'distributor', 'brand') NOT NULL DEFAULT 'wholesaler',
        gst_number VARCHAR(15),
        pan_number VARCHAR(10),
        no_gst_reason VARCHAR(255),
        address VARCHAR(255) NOT NULL,
        city VARCHAR(100) NOT NULL,
        state VARCHAR(100) NOT NULL,
        pincode VARCHAR(10) NOT NULL,
        bank_name VARCHAR(100),
        account_number VARCHAR(50),
        ifsc_code VARCHAR(20),
        account_holder_name VARCHAR(150),
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "3. 'vendor_profiles' table created successfully.\n";

    // 4. Create vendor_registration_requests table
    $db->exec("
    CREATE TABLE IF NOT EXISTS vendor_registration_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        status ENUM('pending', 'under_review', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
        rejection_reason TEXT,
        reviewed_by INT,
        reviewed_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "4. 'vendor_registration_requests' table created successfully.\n";

    echo "\nVendor Migration Completed Successfully!\n";
} catch (Exception $e) {
    echo "Migration Failed: " . $e->getMessage() . "\n";
    exit(1);
}
