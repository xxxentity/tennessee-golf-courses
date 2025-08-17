<?php
require_once '../includes/init.php';
require_once '../config/database.php';
require_once '../includes/csrf.php';
require_once '../includes/secure-upload.php';

// Check if user is logged in
if (!$is_logged_in) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

// Validate CSRF token for AJAX request
$csrf_token = $_POST['csrf_token'] ?? '';
if (!CSRFProtection::validateToken($csrf_token)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Security token expired or invalid. Please refresh the page.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_picture'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'No file uploaded']);
    exit;
}

// Configure secure upload handler for profile pictures
$uploadConfig = [
    'allowed_mime_types' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    'max_file_size' => 5 * 1024 * 1024, // 5MB
    'upload_dir' => '../uploads/profile_pictures/'
];

$uploader = new SecureUpload($uploadConfig);

// Handle the upload
$result = $uploader->handleUpload($_FILES['profile_picture'], 'profile_' . $user_id);

if (!$result['success']) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'error' => implode('. ', $result['errors'])
    ]);
    exit;
}

// Resize image to reasonable size (300x300 for profile pictures)
$uploader->resizeImage($result['path'], 300, 300, 85);

try {
    // Get current profile picture to delete old one
    $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    // Update database with new profile picture
    $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
    $stmt->execute([$result['relative_path'], $user_id]);
    
    // Delete old profile picture if exists
    if ($user['profile_picture'] && file_exists('../' . $user['profile_picture'])) {
        @unlink('../' . $user['profile_picture']);
    }
    
    // Log successful upload
    error_log("Profile picture uploaded successfully for user ID: $user_id");
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Profile picture updated successfully',
        'image_url' => '/' . $result['relative_path'],
        'filename' => $result['filename']
    ]);
    
} catch (PDOException $e) {
    // Delete uploaded file on database error
    @unlink($result['path']);
    
    error_log("Database error during profile picture upload: " . $e->getMessage());
    
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database error occurred']);
}
?>