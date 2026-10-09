<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

$db = Core\Database::getInstance();

try {
    echo "Updating order_modification_items schema...\n";

    // 1. Allow order_item_id to be NULL
    $db->exec("ALTER TABLE order_modification_items MODIFY order_item_id BIGINT NULL");
    
    // 2. Add product_id and variant_id columns if they don't exist
    $cols = $db->query("SHOW COLUMNS FROM order_modification_items")->fetchAll(PDO::FETCH_ASSOC);
    $colNames = array_column($cols, 'Field');

    if (!in_array('product_id', $colNames)) {
        $db->exec("ALTER TABLE order_modification_items ADD COLUMN product_id BIGINT NULL AFTER order_item_id");
        $db->exec("ALTER TABLE order_modification_items ADD FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE");
    }

    if (!in_array('variant_id', $colNames)) {
        $db->exec("ALTER TABLE order_modification_items ADD COLUMN variant_id BIGINT NULL AFTER product_id");
        $db->exec("ALTER TABLE order_modification_items ADD FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE");
    }

    // 3. Update change_type ENUM to include 'added'
    $db->exec("ALTER TABLE order_modification_items MODIFY change_type ENUM('removed','qty_changed','price_changed','replaced','unchanged','added') NOT NULL DEFAULT 'unchanged'");

    echo "Migration completed successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
