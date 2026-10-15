<?php
require_once __DIR__ . '/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $db = Core\Database::getInstance();

    // Find all duplicate attributes grouped by category_id, sub_category_id, and LOWER(name)
    $sql = "
        SELECT category_id, sub_category_id, LOWER(TRIM(name)) as lower_name, COUNT(*) as cnt
        FROM attributes
        GROUP BY category_id, sub_category_id, LOWER(TRIM(name))
        HAVING cnt > 1
    ";
    $duplicates = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    if (empty($duplicates)) {
        echo "No duplicate attributes found in database.\n";
        exit(0);
    }

    echo "Found " . count($duplicates) . " duplicate attribute group(s). Merging...\n";

    foreach ($duplicates as $dup) {
        $catId = $dup['category_id'];
        $subCatId = $dup['sub_category_id'];
        $lowerName = $dup['lower_name'];

        if ($subCatId !== null) {
            $stmt = $db->prepare("
                SELECT * FROM attributes 
                WHERE category_id = ? AND sub_category_id = ? AND LOWER(TRIM(name)) = ?
                ORDER BY id ASC
            ");
            $stmt->execute([$catId, $subCatId, $lowerName]);
        } else {
            $stmt = $db->prepare("
                SELECT * FROM attributes 
                WHERE category_id = ? AND sub_category_id IS NULL AND LOWER(TRIM(name)) = ?
                ORDER BY id ASC
            ");
            $stmt->execute([$catId, $lowerName]);
        }

        $attrs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($attrs) < 2) continue;

        $primary = $attrs[0];
        $primaryId = (int)$primary['id'];
        echo "Merging into Primary Attribute ID {$primaryId} ('{$primary['name']}'):\n";

        // Collect existing values of the primary attribute
        $stmtVals = $db->prepare("SELECT id, LOWER(TRIM(value)) as val FROM attribute_values WHERE attribute_id = ?");
        $stmtVals->execute([$primaryId]);
        $primaryVals = $stmtVals->fetchAll(PDO::FETCH_ASSOC);
        $primaryValLookup = [];
        foreach ($primaryVals as $pv) {
            $primaryValLookup[$pv['val']] = $pv['id'];
        }

        for ($i = 1; $i < count($attrs); $i++) {
            $secondary = $attrs[$i];
            $secondaryId = (int)$secondary['id'];
            echo "  - Merging Duplicate Attribute ID {$secondaryId}...\n";

            // If primary description was empty, inherit from secondary
            if (empty($primary['description']) && !empty($secondary['description'])) {
                $db->prepare("UPDATE attributes SET description = ? WHERE id = ?")->execute([$secondary['description'], $primaryId]);
                $primary['description'] = $secondary['description'];
            }

            // Move or map attribute values
            $secValsStmt = $db->prepare("SELECT * FROM attribute_values WHERE attribute_id = ?");
            $secValsStmt->execute([$secondaryId]);
            $secVals = $secValsStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($secVals as $sv) {
                $svValLower = strtolower(trim($sv['value']));
                if (isset($primaryValLookup[$svValLower])) {
                    // Value already exists in primary: remap any product references
                    $mappedValId = $primaryValLookup[$svValLower];
                    $db->prepare("UPDATE product_attribute_values SET attribute_id = ?, attribute_value_id = ? WHERE attribute_id = ? AND attribute_value_id = ?")
                       ->execute([$primaryId, $mappedValId, $secondaryId, $sv['id']]);
                    // Delete the duplicate value row
                    $db->prepare("DELETE FROM attribute_values WHERE id = ?")->execute([$sv['id']]);
                } else {
                    // Value is unique: reassign to primary attribute
                    $db->prepare("UPDATE attribute_values SET attribute_id = ? WHERE id = ?")->execute([$primaryId, $sv['id']]);
                    $db->prepare("UPDATE product_attribute_values SET attribute_id = ? WHERE attribute_id = ? AND attribute_value_id = ?")
                       ->execute([$primaryId, $secondaryId, $sv['id']]);
                    $primaryValLookup[$svValLower] = $sv['id'];
                }
            }

            // Delete the duplicate attribute record
            $db->prepare("DELETE FROM attributes WHERE id = ?")->execute([$secondaryId]);
            echo "    -> Successfully merged and removed duplicate Attribute ID {$secondaryId}.\n";
        }
    }

    echo "Attribute deduplication complete!\n";
} catch (\Throwable $e) {
    echo "Error during deduplication: " . $e->getMessage() . "\n";
    exit(1);
}
