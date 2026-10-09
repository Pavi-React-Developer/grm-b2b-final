<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

$db = Core\Database::getInstance();
$cols = $db->query("SHOW COLUMNS FROM products")->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    echo $c['Field'] . " (" . $c['Type'] . ")\n";
}
