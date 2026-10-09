<?php
namespace App\Core;

class CloudinaryUploader
{
    private $cloudName;
    private $apiKey;
    private $apiSecret;

    public function __construct()
    {
        if (defined('CLOUDINARY_CLOUD_NAME') && CLOUDINARY_CLOUD_NAME !== '') {
            $this->cloudName = CLOUDINARY_CLOUD_NAME;
            $this->apiKey    = CLOUDINARY_API_KEY;
            $this->apiSecret = CLOUDINARY_API_SECRET;
            return;
        }

        $envPath = dirname(__DIR__, 2) . '/.env';
        $envVars = [];
        if (file_exists($envPath)) {
            $parsed = @parse_ini_file($envPath);
            if ($parsed !== false && is_array($parsed) && !empty($parsed)) {
                $envVars = $parsed;
            } else {
                $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if ($lines) {
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';') || str_starts_with($line, '//')) {
                            continue;
                        }
                        if (strpos($line, '=') !== false) {
                            list($k, $v) = explode('=', $line, 2);
                            $envVars[trim($k)] = trim(trim($v), "'\"");
                        }
                    }
                }
            }
        }
        
        $this->cloudName = trim($envVars['CLOUDINARY_CLOUD_NAME'] ?? '');
        $this->apiKey    = trim($envVars['CLOUDINARY_API_KEY'] ?? '');
        $this->apiSecret = trim($envVars['CLOUDINARY_API_SECRET'] ?? '');
    }

    /**
     * Alias for uploadMedia specifically for images.
     */
    public function uploadImage(string $filePath, ?string $publicId = null, ?string $originalFilename = null)
    {
        return $this->uploadMedia($filePath, $publicId, $originalFilename);
    }

    /**
     * Upload an image or video to Cloudinary. Images are forced to WebP.
     * 
     * @param string $filePath The local file path to upload
     * @param string|null $publicId Optional custom public ID
     * @return array|false The JSON response array on success, or false on failure
     */
    public function uploadMedia(string $filePath, ?string $publicId = null, ?string $originalFilename = null)
    {
        if (empty($this->cloudName) || empty($this->apiKey) || empty($this->apiSecret)) {
            error_log("Cloudinary credentials are missing.");
            return false;
        }

        $mime = mime_content_type($filePath);
        $isVideo = strpos($mime, 'video/') === 0;
        
        // Fallback to extension check if mime_content_type fails
        if (!$isVideo && $originalFilename) {
            $isVideo = preg_match('/\.(mp4|webm|ogg|mov|avi|mkv)$/i', $originalFilename) === 1;
        }

        $resourceType = $isVideo ? 'video' : 'image';
        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/{$resourceType}/upload";
        
        $timestamp = time();

        // Build the params to be SIGNED (must exactly match what is sent, excluding file and api_key)
        $signedParams = [
            'timestamp' => $timestamp,
        ];

        if ($publicId) {
            $signedParams['overwrite'] = 'true';
            $signedParams['public_id'] = $publicId;
        }

        // Generate Signature from EXACTLY the signedParams set
        ksort($signedParams);
        $strToSign = "";
        foreach ($signedParams as $k => $v) {
            $strToSign .= $k . "=" . $v . "&";
        }
        $strToSign = rtrim($strToSign, "&");
        $signature = sha1($strToSign . $this->apiSecret);

        // Build POST fields using signedParams + file + api_key + signature
        $postFields = $signedParams;
        $postFields['file']      = new \CURLFile($filePath);
        $postFields['api_key']   = $this->apiKey;
        $postFields['signature'] = $signature;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            error_log("Cloudinary CURL Error: " . $curlError);
            return ['error' => ['message' => 'Network error: ' . $curlError]];
        }

        $result = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300 && isset($result['secure_url'])) {
            return $result;
        } else {
            $errorMsg = $result['error']['message'] ?? "HTTP $httpCode: $response";
            error_log("Cloudinary Upload Error ($httpCode): " . $response);
            return $result ?? false;
        }
    }

    /**
     * Delete an image or video from Cloudinary.
     * 
     * @param string $publicId The Cloudinary public ID
     * @param string $resourceType 'image' or 'video'
     * @return bool True on success, false on failure
     */
    public function deleteMedia(string $publicId, string $resourceType = 'image')
    {
        if (empty($this->cloudName) || empty($this->apiKey) || empty($this->apiSecret)) {
            error_log("Cloudinary credentials are missing.");
            return false;
        }

        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/{$resourceType}/destroy";
        
        $timestamp = time();
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];
        
        ksort($params);
        $strToSign = "";
        foreach ($params as $k => $v) {
            $strToSign .= $k . "=" . $v . "&";
        }
        $strToSign = rtrim($strToSign, "&");
        $signature = sha1($strToSign . $this->apiSecret);
        
        $postFields = [
            'public_id' => $publicId,
            'api_key' => $this->apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        } else {
            error_log("Cloudinary Delete Error: " . $response);
            return false;
        }
    }
}
