<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use App\Models\Review;
use App\Core\CloudinaryUploader;

class ReviewController extends Controller
{
    private $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new Review();
        
        // Skip login requirement for public review helpfulness voting
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($uri, 'vote-helpful') !== false) {
            return;
        }

        // Ensure user is logged in for dashboard review actions
        if (!Session::get('user_id')) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Please login to continue.']);
                exit;
            }
            Session::setFlash('error', 'Please login to access this page.');
            $this->redirect('/login');
        }
    }

    public function index()
    {
        // Re-use the layout for dashboard reviews
        $userId = Session::get('user_id');
        $pendingReviews = $this->reviewModel->getPendingReviews($userId);
        $myReviews = $this->reviewModel->getUserReviews($userId);

        $this->render('storefront/dashboard/reviews', [
            'title' => 'My Reviews',
            'activeTab' => 'reviews',
            'pendingReviews' => $pendingReviews,
            'myReviews' => $myReviews
        ]);
    }

    public function ajaxSubmitReview()
    {
        header('Content-Type: application/json');
        
        $userId = Session::get('user_id');
        $productId = $_POST['product_id'] ?? null;
        $orderId = $_POST['order_id'] ?? null;
        $rating = $_POST['rating'] ?? 0;
        $reviewText = $_POST['review_text'] ?? '';

        if (!$productId || !$orderId || !$rating || $rating < 1 || $rating > 5) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid rating.']);
            return;
        }

        // Handle photo uploads
        $photos = [];
        if (!empty($_FILES['photos']['name'][0])) {
            $uploader = new CloudinaryUploader();
            
            $fileCount = count($_FILES['photos']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['photos']['tmp_name'][$i];
                    $response = $uploader->uploadMedia($tmpName, 'reviews/' . uniqid());
                    if ($response && isset($response['secure_url'])) {
                        $photos[] = $response['secure_url'];
                    }
                }
            }
        }

        try {
            $this->reviewModel->createReview([
                'product_id' => $productId,
                'user_id' => $userId,
                'order_id' => $orderId,
                'rating' => $rating,
                'review_text' => $reviewText,
                'photos' => !empty($photos) ? $photos : null
            ]);
            echo json_encode(['success' => true, 'message' => 'Review submitted successfully!']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function voteHelpful()
    {
        header('Content-Type: application/json');
        $reviewId = (int)($_POST['review_id'] ?? 0);
        $newType = ($_POST['type'] ?? 'yes') === 'no' ? 'no' : 'yes';

        if (!$reviewId) {
            echo json_encode(['success' => false, 'message' => 'Invalid review ID']);
            return;
        }

        $votedReviews = Session::get('voted_reviews') ?: [];
        if (!is_array($votedReviews)) {
            $votedReviews = [];
        }

        $currentVote = $votedReviews[$reviewId] ?? null;
        $db = \Core\Database::getInstance();
        $votedType = null;

        if ($currentVote === $newType) {
            // User clicked the active button again -> Toggle OFF (un-vote)
            $field = ($newType === 'no') ? 'helpful_no' : 'helpful_yes';
            $stmt = $db->prepare("UPDATE reviews SET {$field} = GREATEST(0, {$field} - 1) WHERE id = :id");
            $stmt->execute(['id' => $reviewId]);

            unset($votedReviews[$reviewId]);
            Session::set('voted_reviews', $votedReviews);
            $votedType = null;
        } elseif ($currentVote !== null) {
            // User switched vote (from 'yes' to 'no' OR 'no' to 'yes')
            $oldField = ($currentVote === 'no') ? 'helpful_no' : 'helpful_yes';
            $newField = ($newType === 'no') ? 'helpful_no' : 'helpful_yes';

            $stmt = $db->prepare("UPDATE reviews SET {$oldField} = GREATEST(0, {$oldField} - 1), {$newField} = {$newField} + 1 WHERE id = :id");
            $stmt->execute(['id' => $reviewId]);

            $votedReviews[$reviewId] = $newType;
            Session::set('voted_reviews', $votedReviews);
            $votedType = $newType;
        } else {
            // First time vote on this review
            $field = ($newType === 'no') ? 'helpful_no' : 'helpful_yes';
            $stmt = $db->prepare("UPDATE reviews SET {$field} = {$field} + 1 WHERE id = :id");
            $stmt->execute(['id' => $reviewId]);

            $votedReviews[$reviewId] = $newType;
            Session::set('voted_reviews', $votedReviews);
            $votedType = $newType;
        }

        // Fetch updated counts
        $stmt2 = $db->prepare("SELECT helpful_yes, helpful_no FROM reviews WHERE id = :id");
        $stmt2->execute(['id' => $reviewId]);
        $row = $stmt2->fetch(\PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'yes' => (int)($row['helpful_yes'] ?? 0),
            'no' => (int)($row['helpful_no'] ?? 0),
            'voted_type' => $votedType,
            'message' => $votedType ? 'Vote recorded!' : 'Vote removed.'
        ]);
    }
}
