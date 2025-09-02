<?php
/**
 * Secure File Upload Handler
 * Implements comprehensive security checks for file uploads
 */

class SecureUpload {
    
    private $allowedMimeTypes = [];
    private $allowedExtensions = [];
    private $maxFileSize;
    private $uploadDir;
    private $errors = [];
    
    /**
     * Constructor
     * @param array $config Configuration options
     */
    public function __construct($config = []) {
        $this->allowedMimeTypes = $config['allowed_mime_types'] ?? [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp'
        ];
        
        $this->allowedExtensions = $config['allowed_extensions'] ?? [
            'jpg', 'jpeg', 'png', 'gif', 'webp'
        ];
        
        $this->maxFileSize = $config['max_file_size'] ?? (5 * 1024 * 1024); // 5MB default
        $this->uploadDir = $config['upload_dir'] ?? '../uploads/';
    }
    
    /**
     * Handle file upload with comprehensive security checks
     * @param array $file $_FILES array element
     * @param string $prefix Filename prefix
     * @return array Result with success status and file info or errors
     */
    public function handleUpload($file, $prefix = 'upload') {
        $this->errors = [];
        
        // Basic validation
        if (!$this->validateUpload($file)) {
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Get file info
        $originalName = basename($file['name']);
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        
        // Validate extension
        if (!$this->validateExtension($extension)) {
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Validate MIME type (from actual file content, not browser)
        if (!$this->validateMimeType($file['tmp_name'])) {
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Validate file size
        if (!$this->validateFileSize($file['size'])) {
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Validate image properties
        if (!$this->validateImage($file['tmp_name'])) {
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Scan for malicious content
        if (!$this->scanForMaliciousContent($file['tmp_name'])) {
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Generate secure filename
        $newFilename = $this->generateSecureFilename($prefix, $extension);
        $uploadPath = $this->uploadDir . $newFilename;
        
        // Ensure upload directory exists and is writable
        if (!$this->prepareUploadDirectory()) {
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Move the file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $this->errors[] = 'Failed to save uploaded file';
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Set secure permissions
        chmod($uploadPath, 0644);
        
        // Strip EXIF data from images (removes potential malicious metadata)
        $this->stripExifData($uploadPath, $extension);
        
        return [
            'success' => true,
            'filename' => $newFilename,
            'path' => $uploadPath,
            'relative_path' => str_replace('../', '', $this->uploadDir) . $newFilename,
            'size' => filesize($uploadPath),
            'mime_type' => mime_content_type($uploadPath)
        ];
    }
    
    /**
     * Validate basic upload requirements
     */
    private function validateUpload($file) {
        if (!isset($file['error'])) {
            $this->errors[] = 'No file uploaded';
            return false;
        }
        
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->errors[] = 'File exceeds maximum allowed size';
                return false;
            case UPLOAD_ERR_PARTIAL:
                $this->errors[] = 'File was only partially uploaded';
                return false;
            case UPLOAD_ERR_NO_FILE:
                $this->errors[] = 'No file was uploaded';
                return false;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->errors[] = 'Missing temporary folder';
                return false;
            case UPLOAD_ERR_CANT_WRITE:
                $this->errors[] = 'Failed to write file to disk';
                return false;
            default:
                $this->errors[] = 'Unknown upload error';
                return false;
        }
        
        return true;
    }
    
    /**
     * Validate file extension
     */
    private function validateExtension($extension) {
        if (!in_array($extension, $this->allowedExtensions)) {
            $this->errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $this->allowedExtensions);
            return false;
        }
        return true;
    }
    
    /**
     * Validate MIME type from actual file content
     */
    private function validateMimeType($tmpFile) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpFile);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            $this->errors[] = 'Invalid file type detected';
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate file size
     */
    private function validateFileSize($size) {
        if ($size > $this->maxFileSize) {
            $this->errors[] = 'File size exceeds maximum allowed size of ' . $this->formatBytes($this->maxFileSize);
            return false;
        }
        return true;
    }
    
    /**
     * Validate image properties
     */
    private function validateImage($tmpFile) {
        $imageInfo = @getimagesize($tmpFile);
        
        if ($imageInfo === false) {
            $this->errors[] = 'File is not a valid image';
            return false;
        }
        
        // Check dimensions aren't suspiciously large (potential DoS)
        $maxWidth = 10000;
        $maxHeight = 10000;
        
        if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
            $this->errors[] = 'Image dimensions are too large';
            return false;
        }
        
        // Verify image type matches
        $imageType = $imageInfo[2];
        $validTypes = [
            IMAGETYPE_GIF,
            IMAGETYPE_JPEG,
            IMAGETYPE_PNG,
            IMAGETYPE_WEBP
        ];
        
        if (!in_array($imageType, $validTypes)) {
            $this->errors[] = 'Invalid image type';
            return false;
        }
        
        return true;
    }
    
    /**
     * Scan for malicious content in file
     */
    private function scanForMaliciousContent($tmpFile) {
        // Read first 1KB of file
        $handle = fopen($tmpFile, 'rb');
        $fileContent = fread($handle, 1024);
        fclose($handle);
        
        // Check for PHP tags
        $dangerousPatterns = [
            '<?php',
            '<?=',
            '<script',
            '<iframe',
            'eval(',
            'base64_decode',
            'system(',
            'exec(',
            'shell_exec(',
            'passthru(',
            'file_get_contents',
            'file_put_contents',
            'fopen'
        ];
        
        foreach ($dangerousPatterns as $pattern) {
            if (stripos($fileContent, $pattern) !== false) {
                $this->errors[] = 'File contains potentially malicious content';
                error_log("Malicious upload attempt detected: $pattern found in uploaded file");
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Generate secure filename
     */
    private function generateSecureFilename($prefix, $extension) {
        // Generate random filename to prevent enumeration
        $randomBytes = bin2hex(random_bytes(16));
        $timestamp = time();
        return "{$prefix}_{$timestamp}_{$randomBytes}.{$extension}";
    }
    
    /**
     * Prepare upload directory
     */
    private function prepareUploadDirectory() {
        if (!file_exists($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0755, true)) {
                $this->errors[] = 'Failed to create upload directory';
                return false;
            }
        }
        
        if (!is_writable($this->uploadDir)) {
            $this->errors[] = 'Upload directory is not writable';
            return false;
        }
        
        // Create .htaccess to prevent PHP execution
        $htaccessPath = $this->uploadDir . '.htaccess';
        if (!file_exists($htaccessPath)) {
            $htaccessContent = "# Prevent PHP execution\n";
            $htaccessContent .= "<FilesMatch \"\\.php$\">\n";
            $htaccessContent .= "    Order Deny,Allow\n";
            $htaccessContent .= "    Deny from all\n";
            $htaccessContent .= "</FilesMatch>\n\n";
            $htaccessContent .= "# Disable directory browsing\n";
            $htaccessContent .= "Options -Indexes\n";
            
            file_put_contents($htaccessPath, $htaccessContent);
        }
        
        return true;
    }
    
    /**
     * Strip EXIF data from images
     */
    private function stripExifData($filePath, $extension) {
        if (!in_array($extension, ['jpg', 'jpeg'])) {
            return; // Only JPEG files have EXIF data
        }
        
        try {
            $img = imagecreatefromjpeg($filePath);
            if ($img) {
                // Re-save without EXIF data
                imagejpeg($img, $filePath, 85);
                imagedestroy($img);
            }
        } catch (Exception $e) {
            // Log but don't fail the upload
            error_log("Failed to strip EXIF data: " . $e->getMessage());
        }
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Resize image maintaining aspect ratio
     */
    public function resizeImage($sourcePath, $maxWidth, $maxHeight, $quality = 85) {
        $imageInfo = @getimagesize($sourcePath);
        if (!$imageInfo) {
            return false;
        }
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];
        
        // Don't resize if already within limits
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return true;
        }
        
        // Calculate new dimensions
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
        
        // Create source image resource
        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = @imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = @imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $sourceImage = @imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }
        
        if (!$sourceImage) {
            return false;
        }
        
        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG/GIF/WebP
        if (in_array($mime, ['image/png', 'image/gif', 'image/webp'])) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save resized image
        $result = false;
        switch ($mime) {
            case 'image/jpeg':
                $result = imagejpeg($newImage, $sourcePath, $quality);
                break;
            case 'image/png':
                $result = imagepng($newImage, $sourcePath, round($quality / 10));
                break;
            case 'image/gif':
                $result = imagegif($newImage, $sourcePath);
                break;
            case 'image/webp':
                $result = imagewebp($newImage, $sourcePath, $quality);
                break;
        }
        
        // Clean up
        imagedestroy($sourceImage);
        imagedestroy($newImage);
        
        return $result;
    }
}
?>