<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_picture'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'No file uploaded']);
    exit;
}

$user_id = $_SESSION['user_id'];
$upload_dir = '../uploads/profile_pictures/';
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
$max_size = 5 * 1024 * 1024; // 5MB

// Create upload directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$file = $_FILES['profile_picture'];
$file_name = $file['name'];
$file_tmp = $file['tmp_name'];
$file_size = $file['size'];
$file_type = $file['type'];
$file_error = $file['error'];

// Validate upload
if ($file_error !== UPLOAD_ERR_OK) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Upload failed']);
    exit;
}

if ($file_size > $max_size) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'File too large. Maximum size is 5MB']);
    exit;
}

if (!in_array($file_type, $allowed_types)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, and GIF allowed']);
    exit;
}

// Verify it's actually an image
$image_info = getimagesize($file_tmp);
if ($image_info === false) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid image file']);
    exit;
}

// Generate unique filename
$file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
$new_filename = 'profile_' . $user_id . '_' . uniqid() . '.' . $file_extension;
$upload_path = $upload_dir . $new_filename;

try {
    // Get current profile picture to delete old one
    $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    // Move uploaded file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Resize image to reasonable size (300x300)
        $resized = resizeImage($upload_path, 300, 300);
        
        if ($resized) {
            // Update database
            $relative_path = 'uploads/profile_pictures/' . $new_filename;
            $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            $stmt->execute([$relative_path, $user_id]);
            
            // Delete old profile picture if exists
            if ($user['profile_picture'] && file_exists('../' . $user['profile_picture'])) {
                unlink('../' . $user['profile_picture']);
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Profile picture updated successfully',
                'image_url' => $relative_path
            ]);
        } else {
            // If resize failed, still use original
            $relative_path = 'uploads/profile_pictures/' . $new_filename;
            $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            $stmt->execute([$relative_path, $user_id]);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Profile picture updated successfully',
                'image_url' => $relative_path
            ]);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Failed to save file']);
    }
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database error']);
}

function resizeImage($source, $max_width, $max_height) {
    $image_info = getimagesize($source);
    if (!$image_info) return false;
    
    $width = $image_info[0];
    $height = $image_info[1];
    $mime = $image_info['mime'];
    
    // Don't resize if already small enough
    if ($width <= $max_width && $height <= $max_height) {
        return true;
    }
    
    // Calculate new dimensions maintaining aspect ratio
    $ratio = min($max_width / $width, $max_height / $height);
    $new_width = round($width * $ratio);
    $new_height = round($height * $ratio);
    
    // Create source image
    switch ($mime) {
        case 'image/jpeg':
            $source_image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $source_image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $source_image = imagecreatefromgif($source);
            break;
        default:
            return false;
    }
    
    if (!$source_image) return false;
    
    // Create new image
    $new_image = imagecreatetruecolor($new_width, $new_height);
    
    // Preserve transparency for PNG and GIF
    if ($mime == 'image/png' || $mime == 'image/gif') {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
        imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
    }
    
    // Resize
    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    // Save resized image
    $result = false;
    switch ($mime) {
        case 'image/jpeg':
            $result = imagejpeg($new_image, $source, 85);
            break;
        case 'image/png':
            $result = imagepng($new_image, $source, 8);
            break;
        case 'image/gif':
            $result = imagegif($new_image, $source);
            break;
    }
    
    // Clean up
    imagedestroy($source_image);
    imagedestroy($new_image);
    
    return $result;
}
?>