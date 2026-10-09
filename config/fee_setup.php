<?php
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/../core/Database.php';

try {
    $db = \Core\Database::getInstance();

    echo "Creating fee_categories table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS fee_categories (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) UNIQUE NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    echo "Creating fee_rules table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS fee_rules (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            fee_name VARCHAR(255) NOT NULL,
            fee_category_id BIGINT NOT NULL,
            fee_type ENUM('Fixed Amount', 'Percentage') NOT NULL,
            flat_fee_value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            application_states TEXT, -- JSON array of strings, e.g. ['Karnataka', 'Tamil Nadu']
            payment_method ENUM('COD', 'Online', 'Both') NOT NULL DEFAULT 'Both',
            minimum_order_amount DECIMAL(10,2) DEFAULT NULL,
            maximum_order_amount DECIMAL(10,2) DEFAULT NULL,
            active BOOLEAN DEFAULT TRUE,
            weight_slabs TEXT, -- JSON array
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (fee_category_id) REFERENCES fee_categories(id) ON DELETE CASCADE
        )
    ");

    echo "Creating packing_fee_rules table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS packing_fee_rules (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            min_amount INT NOT NULL,
            max_amount INT NOT NULL,
            packing_fee DECIMAL(10,2) NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // Add columns to products and variants if they don't exist
    echo "Adding columns to products and variants...\n";
    $productsCols = $db->query("SHOW COLUMNS FROM products")->fetchAll(\PDO::FETCH_COLUMN);
    if (!in_array('weight_unit', $productsCols)) {
        $db->exec("ALTER TABLE products ADD COLUMN weight_unit VARCHAR(10) DEFAULT 'kg'");
    }
    if (!in_array('max_amount', $productsCols)) {
        $db->exec("ALTER TABLE products ADD COLUMN max_amount DECIMAL(10,2) DEFAULT 0.00");
    }

    $variantCols = $db->query("SHOW COLUMNS FROM product_variants")->fetchAll(\PDO::FETCH_COLUMN);
    if (!in_array('weight_unit', $variantCols)) {
        $db->exec("ALTER TABLE product_variants ADD COLUMN weight_unit VARCHAR(10) DEFAULT 'kg'");
    }
    if (!in_array('max_amount', $variantCols)) {
        $db->exec("ALTER TABLE product_variants ADD COLUMN max_amount DECIMAL(10,2) DEFAULT 0.00");
    }

    // Add columns to orders
    echo "Adding columns to orders...\n";
    $orderCols = $db->query("SHOW COLUMNS FROM orders")->fetchAll(\PDO::FETCH_COLUMN);
    $newOrderCols = [
        'shipping_fee' => "DECIMAL(10,2) DEFAULT 0.00",
        'weight_fee' => "DECIMAL(10,2) DEFAULT 0.00",
        'platform_fee' => "DECIMAL(10,2) DEFAULT 0.00",
        'packaging_fee' => "DECIMAL(10,2) DEFAULT 0.00",
        'grand_total' => "DECIMAL(12,2) DEFAULT 0.00",
        'cod_advance_paid' => "DECIMAL(10,2) DEFAULT 0.00",
        'balance_amount' => "DECIMAL(12,2) DEFAULT 0.00"
    ];

    foreach ($newOrderCols as $colName => $colDef) {
        if (!in_array($colName, $orderCols)) {
            $db->exec("ALTER TABLE orders ADD COLUMN {$colName} {$colDef}");
        }
    }

    // Seeding default categories
    echo "Seeding default categories...\n";
    $categories = ['Shipping Fee', 'Weight Fee', 'Platform Fee', 'Packing Fee', 'COD Advance'];
    $catInsert = $db->prepare("INSERT IGNORE INTO fee_categories (name) VALUES (?)");
    foreach ($categories as $cat) {
        $catInsert->execute([$cat]);
    }

    echo "Migration completed successfully!\n";
} catch (\Exception $e) {
    echo "Migration Failed: " . $e->getMessage() . "\n";
}
