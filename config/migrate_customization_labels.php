<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $db = Core\Database::getInstance();
    
    // 1. Create customization_labels table
    $db->exec("
        CREATE TABLE IF NOT EXISTS customization_labels (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            group_name VARCHAR(255) NULL,
            sub_group VARCHAR(255) NULL,
            name VARCHAR(255) NOT NULL,
            display_label VARCHAR(500) NOT NULL,
            description TEXT NULL,
            sort_order INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_status (status),
            INDEX idx_group (group_name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "Table 'customization_labels' created or already exists.\n";

    // 2. Create fabric_customization_label_rel junction table
    $db->exec("
        CREATE TABLE IF NOT EXISTS fabric_customization_label_rel (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            customization_id BIGINT NOT NULL,
            label_id BIGINT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_customization_label (customization_id, label_id),
            INDEX idx_customization_id (customization_id),
            INDEX idx_label_id (label_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "Table 'fabric_customization_label_rel' created or already exists.\n";

    // 3. Seed initial default template labels if table is empty
    $count = (int)$db->query("SELECT COUNT(*) FROM customization_labels")->fetchColumn();
    if ($count === 0) {
        $seeds = [
            ['Maternity', 'Feeding', 'Full Sleeve', 'Maternity -> Feeding -> Full Sleeve', 'Full sleeve maternity feeding style with concealed zips', 1],
            ['Maternity', 'Feeding', '3/4th Sleeve', 'Maternity -> Feeding -> 3/4th Sleeve', '3/4th sleeve maternity feeding style with invisible zips', 2],
            ['Maternity', 'Feeding', 'Half Sleeve', 'Maternity -> Feeding -> Half Sleeve', 'Half sleeve maternity feeding style', 3],
            ['Maternity', 'Feeding', 'Sleeveless', 'Maternity -> Feeding -> Sleeveless', 'Sleeveless maternity feeding pattern', 4],
            ['Maternity', 'Non-Feeding', 'Full Sleeve', 'Maternity -> Non-Feeding -> Full Sleeve', 'Regular pregnancy maternity wear with full sleeve', 5],
            ['Maternity', 'Non-Feeding', 'Half Sleeve', 'Maternity -> Non-Feeding -> Half Sleeve', 'Regular pregnancy maternity wear with half sleeve', 6],
            ['Standard', 'Regular Fit', 'Round Neck', 'Standard -> Regular Fit -> Round Neck', 'Standard crew/round neckline tailoring', 7],
            ['Standard', 'Regular Fit', 'V-Neck', 'Standard -> Regular Fit -> V-Neck', 'Standard V-neckline tailoring', 8],
            ['Standard', 'Regular Fit', 'Collar Neck', 'Standard -> Regular Fit -> Collar Neck', 'Mandarin or regular shirt collar tailoring', 9],
            ['Nightwear', 'Front Open', 'Full Button', 'Nightwear -> Front Open -> Full Button', 'Front opening buttons nightwear styling', 10],
        ];

        $stmt = $db->prepare("
            INSERT INTO customization_labels (group_name, sub_group, name, display_label, description, sort_order, status)
            VALUES (?, ?, ?, ?, ?, ?, 'active')
        ");

        foreach ($seeds as $s) {
            $stmt->execute($s);
        }
        echo "Seeded " . count($seeds) . " default customization labels.\n";
    }

    echo "Migration completed successfully!\n";
} catch (\Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
