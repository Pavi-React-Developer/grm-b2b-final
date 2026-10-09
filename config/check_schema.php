<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

$db = Core\Database::getInstance();
$tables = ['fabric_customizations', 'fabric_customization_sizes', 'order_items', 'products', 'categories', 'sub_categories'];
foreach ($tables as $table) {
    echo "=== Table: $table ===\n";
    $stmt = $db->query("SHOW TABLES LIKE '$table'");
    if (!$stmt->fetch()) {
        echo "Table does not exist.\n\n";
        continue;
    }
    $cols = $db->query("SHOW COLUMNS FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c) {
        echo "  " . $c['Field'] . " (" . $c['Type'] . ")\n";
    }
    echo "\n";
}
