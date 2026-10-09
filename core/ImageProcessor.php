<?php
namespace Core;

class ImageProcessor
{
    /**
     * Processes an uploaded image by optionally resizing it and converting it to WebP.
     * 
     * @param string $sourcePath The temporary uploaded file path (e.g. $_FILES['image']['tmp_name'])
     * @param string $targetDir The directory to save the processed image
     * @param string $originalName The original name of the file
     * @param int $maxWidth The maximum width of the image (0 for no resize limit)
     * @param int $quality The quality of the WebP image (0-100)
     * @return string|false The final relative path to the image, or false on failure
     */
    public static function processAndSave(string $sourcePath, string $targetDir, string $originalName, int $maxWidth = 1200, int $quality = 80)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Fallback if the GD library is not enabled in php.ini
        if (!extension_loaded('gd')) {
            $fileName = time() . '_' . basename($originalName);
            $targetPath = rtrim($targetDir, '/') . '/' . $fileName;
            if (move_uploaded_file($sourcePath, $targetPath)) {
                return $fileName;
            }
            return false;
        }

        $info = getimagesize($sourcePath);
        if ($info === false) {
            return false;
        }

        $mime = $info['mime'];
        $width = $info[0];
        $height = $info[1];

        // Load image based on mime type
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                // Unsupported type, just move the file as is
                $fileName = time() . '_' . basename($originalName);
                $targetPath = rtrim($targetDir, '/') . '/' . $fileName;
                if (move_uploaded_file($sourcePath, $targetPath)) {
                    return $fileName;
                }
                return false;
        }

        if (!$image) {
            return false;
        }

        // Calculate new dimensions if resizing is needed
        $newWidth = $width;
        $newHeight = $height;

        if ($maxWidth > 0 && $width > $maxWidth) {
            $ratio = $maxWidth / $width;
            $newWidth = $maxWidth;
            $newHeight = (int)($height * $ratio);
        }

        // Create new true color image and copy/resize
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and WebP
        if ($mime == 'image/png' || $mime == 'image/webp' || $mime == 'image/gif') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Generate new filename with .webp extension
        $fileNameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
        // Clean filename
        $fileNameWithoutExt = preg_replace('/[^a-zA-Z0-9_-]/', '-', $fileNameWithoutExt);
        
        $newFileName = time() . '_' . $fileNameWithoutExt . '.webp';
        $targetPath = rtrim($targetDir, '/') . '/' . $newFileName;

        // Save as WebP
        $success = imagewebp($newImage, $targetPath, $quality);

        // Free up memory
        imagedestroy($image);
        imagedestroy($newImage);

        if ($success) {
            return $newFileName;
        }

        return false;
    }
}
