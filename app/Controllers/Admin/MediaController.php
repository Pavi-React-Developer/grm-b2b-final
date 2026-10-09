<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\MediaUpload;

class MediaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $role = Session::get('user_role');
        if (!$role) {
            Session::setFlash('error', 'Unauthorized access.');
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $this->requirePermission('media_manager', 'view');
        $role = Session::get('user_role');
        $isVendor = ($role === 'vendor');
        $vendorId = $isVendor ? (int)Session::get('user_id') : (!empty($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : null);
        $scope = $_GET['scope'] ?? ($isVendor ? 'vendor' : 'admin');
        
        $mediaModel = new MediaUpload();
        $mediaFiles = $mediaModel->getAll($vendorId, $isVendor ? null : $scope);

        $vendors = [];
        if (!$isVendor && is_vendor_module_enabled()) {
            $userModel = new \App\Models\User();
            $vendors = $userModel->getActiveVendors();
        }
        
        $this->render('admin/media/index', [
            'title' => $isVendor ? 'My Store Media Library' : 'Media Manager',
            'mediaFiles' => $mediaFiles,
            'isVendor' => $isVendor,
            'vendors' => $vendors,
            'currentVendorId' => $vendorId,
            'currentScope' => $scope
        ], 'admin');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/media');
        }

        if (!$this->hasPermission('media_manager', 'delete')) {
            Session::setFlash('error', 'Unauthorized action. You do not have permission to delete media.');
            $this->redirect('/admin/media');
        }

        $id = (int)($_POST['id'] ?? 0);
        
        if ($id > 0) {
            $mediaModel = new MediaUpload();
            $media = $mediaModel->findById($id);
            
            if ($media) {
                $isVendor = (Session::get('user_role') === 'vendor');
                if ($isVendor && (int)($media['uploaded_by'] ?? 0) !== (int)Session::get('user_id')) {
                    Session::setFlash('error', 'Unauthorized: You can only delete your own uploaded media.');
                    $this->redirect('/admin/media');
                    return;
                }

                if (!empty($media['public_id'])) {
                    require_once BASE_PATH . '/app/Core/CloudinaryUploader.php';
                    $uploader = new \App\Core\CloudinaryUploader();
                    $resourceType = ($media['file_type'] === 'video') ? 'video' : 'image';
                    $uploader->deleteMedia($media['public_id'], $resourceType);
                }
                
                $mediaModel->delete($id);
                Session::setFlash('success', 'Media deleted successfully.');
            }
        }

        $this->redirect('/admin/media');
    }

    public function upload()
    {
        $this->requirePermission('media_manager', 'create');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Invalid upload request.']);
                exit;
            }
            Session::setFlash('error', 'Invalid upload request.');
            $this->redirect('/admin/media');
        }

        // Normalize files into a uniform array of files
        $filesToProcess = [];
        
        if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
            // Multiple files from the Media Manager UI
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $filesToProcess[] = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error' => $_FILES['images']['error'][$i],
                        'size' => $_FILES['images']['size'][$i],
                    ];
                }
            }
        } elseif (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Single file (e.g. from Ajax uploads)
            $filesToProcess[] = $_FILES['image'];
        }

        if (empty($filesToProcess)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'No valid files uploaded.']);
                exit;
            }
            Session::setFlash('error', 'No valid files uploaded.');
            $this->redirect('/admin/media');
        }

        require_once BASE_PATH . '/app/Core/CloudinaryUploader.php';
        $uploader = new \App\Core\CloudinaryUploader();
        $mediaModel = new MediaUpload();
        
        $successCount = 0;
        $failCount = 0;
        $lastSuccessResponse = null;
        $lastError = null;

        foreach ($filesToProcess as $file) {
            $tmpName = $file['tmp_name'];
            $fileHash = md5_file($tmpName);
            
            $existingMedia = $mediaModel->findByHash($fileHash);
            if ($existingMedia) {
                // If it's a single AJAX request, we can just return the existing URL
                if ($isAjax && count($filesToProcess) === 1) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'url' => $existingMedia['cloudinary_url'], 'public_id' => $existingMedia['public_id']]);
                    exit;
                }
                $failCount++;
                $lastError = 'File already exists in media library.';
                continue;
            }

            $cloudinaryResponse = $uploader->uploadMedia($tmpName, null, $file['name']);
            
            if ($cloudinaryResponse && isset($cloudinaryResponse['secure_url'])) {
                $mediaModel->create([
                    'original_filename' => basename($file['name']),
                    'file_hash' => $fileHash,
                    'cloudinary_url' => $cloudinaryResponse['secure_url'],
                    'public_id' => $cloudinaryResponse['public_id'],
                    'file_size' => filesize($tmpName),
                    'uploaded_by' => Session::get('user_id')
                ]);
                $successCount++;
                $lastSuccessResponse = $cloudinaryResponse;
            } else {
                // Fallback to local storage
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) ?: 'jpg';
                $safeFilename = 'media_' . (Session::get('user_id') ?? '0') . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $uploadSubdir = (Session::get('user_role') === 'vendor') ? 'uploads/vendors/' : 'uploads/media/';
                $targetDirPublic = BASE_PATH . '/public/' . $uploadSubdir;
                $targetDirRoot   = BASE_PATH . '/' . $uploadSubdir;
                if (!file_exists($targetDirPublic)) {
                    @mkdir($targetDirPublic, 0755, true);
                }
                if (!file_exists($targetDirRoot)) {
                    @mkdir($targetDirRoot, 0755, true);
                }
                $targetPathPublic = $targetDirPublic . $safeFilename;
                $targetPathRoot   = $targetDirRoot . $safeFilename;
                if (move_uploaded_file($tmpName, $targetPathPublic) || copy($tmpName, $targetPathPublic)) {
                    @copy($targetPathPublic, $targetPathRoot);
                    $relUrl = $uploadSubdir . $safeFilename;
                    $mediaModel->create([
                        'original_filename' => basename($file['name']),
                        'file_hash' => $fileHash,
                        'cloudinary_url' => $relUrl,
                        'public_id' => pathinfo($safeFilename, PATHINFO_FILENAME),
                        'file_size' => filesize($targetPathPublic),
                        'uploaded_by' => Session::get('user_id')
                    ]);
                    $successCount++;
                    $lastSuccessResponse = ['secure_url' => $relUrl, 'public_id' => pathinfo($safeFilename, PATHINFO_FILENAME)];
                } else {
                    $failCount++;
                    $lastError = 'Failed to save media upload.';
                    error_log('[MediaController] Local fallback failed for ' . $file['name']);
                }
            }
        }

        if ($isAjax) {
            if (ob_get_length()) ob_clean();
            if ($successCount > 0 && $lastSuccessResponse) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'url' => $lastSuccessResponse['secure_url'], 'public_id' => $lastSuccessResponse['public_id']]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $lastError ?? 'Failed to upload image.']);
            }
            exit;
        }

        if ($successCount > 0 && $failCount === 0) {
            Session::setFlash('success', "$successCount media file(s) uploaded successfully.");
        } elseif ($successCount > 0 && $failCount > 0) {
            Session::setFlash('success', "$successCount media file(s) uploaded. $failCount file(s) failed or already exist.");
        } else {
            Session::setFlash('error', $lastError ?? 'Failed to upload media.');
        }
        
        $this->redirect('/admin/media');
    }

    public function ajaxGet()
    {
        header('Content-Type: application/json');
        
        $role = Session::get('user_role');
        $isVendor = ($role === 'vendor');
        $vendorId = $isVendor ? (int)Session::get('user_id') : (!empty($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : null);
        $scope = $_GET['scope'] ?? ($isVendor ? 'vendor' : 'admin');
        
        $mediaModel = new MediaUpload();
        $mediaFiles = $mediaModel->getAll($vendorId, $isVendor ? null : $scope);
        
        echo json_encode(['success' => true, 'data' => $mediaFiles]);
        exit;
    }
}
