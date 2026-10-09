<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;

class NotificationController extends Controller
{
    public function __construct()
    {
        if (!Session::get('user_id')) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    }

    public function getAjax()
    {
        $userId = Session::get('user_id');
        $db = \Core\Database::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM user_notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
        $stmt->execute([$userId]);
        $notifications = $stmt->fetchAll();

        $unreadStmt = $db->prepare("SELECT COUNT(*) FROM user_notifications WHERE user_id = ? AND is_read = 0");
        $unreadStmt->execute([$userId]);
        $unreadCount = $unreadStmt->fetchColumn();

        $this->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => (int)$unreadCount
        ]);
    }

    public function markAsRead()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid method'], 405);
        }

        $userId = Session::get('user_id');
        $data = json_decode(file_get_contents('php://input'), true);
        $notificationId = $data['id'] ?? null;

        $db = \Core\Database::getInstance();

        if ($notificationId === 'all') {
            $stmt = $db->prepare("UPDATE user_notifications SET is_read = 1 WHERE user_id = ?");
            $stmt->execute([$userId]);
        } elseif (is_numeric($notificationId)) {
            $stmt = $db->prepare("UPDATE user_notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$notificationId, $userId]);
        } else {
            $this->json(['success' => false, 'message' => 'Invalid ID']);
        }

        $this->json(['success' => true]);
    }
}
