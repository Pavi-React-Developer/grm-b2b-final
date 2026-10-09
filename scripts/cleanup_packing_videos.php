<?php
/**
 * Cron / Maintenance script to clean up expired packing videos (30 days after delivery)
 *
 * Usage:
 * php scripts/cleanup_packing_videos.php
 */

require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $db = Core\Database::getInstance();
    echo "[" . date('Y-m-d H:i:s') . "] Starting packing videos cleanup check...\n";

    // Find orders where packing_video_expires_at is past and videos are still recorded
    $stmt = $db->query("
        SELECT id, order_number, packing_video_1, packing_video_2, delivered_at, packing_video_expires_at
        FROM orders
        WHERE (packing_video_1 IS NOT NULL OR packing_video_2 IS NOT NULL)
          AND (
            (packing_video_expires_at IS NOT NULL AND packing_video_expires_at < NOW())
            OR (delivered_at IS NOT NULL AND delivered_at < DATE_SUB(NOW(), INTERVAL 30 DAY))
          )
    ");
    $expiredOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($expiredOrders) . " orders with expired packing videos.\n";

    $updateStmt = $db->prepare("UPDATE orders SET packing_video_1 = NULL, packing_video_2 = NULL WHERE id = ?");

    foreach ($expiredOrders as $ord) {
        echo "Processing Order #{$ord['order_number']}...\n";

        // Remove video 1 file
        if (!empty($ord['packing_video_1'])) {
            $filePath1 = PUBLIC_PATH . '/' . ltrim($ord['packing_video_1'], '/');
            if (file_exists($filePath1)) {
                @unlink($filePath1);
                echo " - Deleted video 1 file: {$ord['packing_video_1']}\n";
            }
        }

        // Remove video 2 file
        if (!empty($ord['packing_video_2'])) {
            $filePath2 = PUBLIC_PATH . '/' . ltrim($ord['packing_video_2'], '/');
            if (file_exists($filePath2)) {
                @unlink($filePath2);
                echo " - Deleted video 2 file: {$ord['packing_video_2']}\n";
            }
        }

        $updateStmt->execute([$ord['id']]);
        echo " - Cleared video records in database for Order #{$ord['order_number']}.\n";
    }

    echo "[" . date('Y-m-d H:i:s') . "] Cleanup completed successfully.\n";
} catch (\Exception $e) {
    echo "Error during cleanup: " . $e->getMessage() . "\n";
    exit(1);
}
