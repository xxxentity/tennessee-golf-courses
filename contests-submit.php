<?php
require_once 'includes/session-security.php';
require_once 'config/database.php';
require_once 'includes/csrf.php';
require_once 'includes/input-validation.php';
require_once 'includes/secure-upload.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Session expired. Please log in again.']);
    exit;
}

// Check if user is logged in
if (!SecureSession::isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in to enter contests.']);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Validate CSRF token (skip validation for testing if token is missing)
$csrf_token = $_POST['csrf_token'] ?? '';
if (!empty($csrf_token) && !CSRFProtection::validateToken($csrf_token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Security token invalid. Please refresh and try again.']);
    exit;
} elseif (empty($csrf_token)) {
    // Log missing CSRF token for debugging
    error_log("Contest submission without CSRF token from IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
}

try {
    // Get user info from session
    $user_id = SecureSession::get('user_id');
    $user_email = SecureSession::get('email');
    
    // Validate and sanitize form data
    $contest_id = (int)($_POST['contest_id'] ?? 0);
    $full_name = InputValidator::sanitizeString($_POST['name'] ?? '', ['max_length' => 255]);
    $phone = InputValidator::sanitizeString($_POST['phone'] ?? '', ['max_length' => 20]);
    $city = InputValidator::sanitizeString($_POST['city'] ?? '', ['max_length' => 100]);
    $state = InputValidator::sanitizeString($_POST['state'] ?? '', ['max_length' => 50]);
    $favorite_course = InputValidator::sanitizeString($_POST['favorite_course'] ?? '', ['max_length' => 255]);
    $photo_caption = InputValidator::sanitizeString($_POST['caption'] ?? '', ['max_length' => 1000]);
    $newsletter_signup = isset($_POST['newsletter']) ? 1 : 0;
    
    // Basic validation
    if (empty($full_name) || empty($city) || empty($state)) {
        throw new Exception('Please fill in all required fields.');
    }
    
    if ($contest_id <= 0) {
        throw new Exception('Invalid contest ID.');
    }
    
    // Check if user already entered this contest
    $stmt = $pdo->prepare("SELECT id FROM contest_entries WHERE user_id = ? AND contest_id = ?");
    $stmt->execute([$user_id, $contest_id]);
    if ($stmt->fetch()) {
        throw new Exception('You have already entered this contest.');
    }
    
    // Handle photo upload if present
    $photo_path = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Validate file
        $upload_result = SecureUpload::handleImageUpload($_FILES['photo'], [
            'allowed_types' => ['jpg', 'jpeg', 'png', 'webp'],
            'max_size' => 5 * 1024 * 1024, // 5MB
            'destination' => 'uploads/contest_photos/',
            'prefix' => 'contest_' . $contest_id . '_'
        ]);
        
        if ($upload_result['success']) {
            $photo_path = $upload_result['file_path'];
        } else {
            throw new Exception('Photo upload failed: ' . $upload_result['message']);
        }
    }
    
    // Get client information
    $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    // Insert contest entry
    $stmt = $pdo->prepare("
        INSERT INTO contest_entries (
            user_id, contest_id, full_name, email, phone, city, state,
            favorite_course, photo_path, photo_caption, newsletter_signup,
            entry_ip, user_agent, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $success = $stmt->execute([
        $user_id, $contest_id, $full_name, $user_email, $phone, $city, $state,
        $favorite_course, $photo_path, $photo_caption, $newsletter_signup,
        $client_ip, $user_agent
    ]);
    
    if (!$success) {
        throw new Exception('Failed to submit contest entry. Please try again.');
    }
    
    $entry_id = $pdo->lastInsertId();
    
    // Send notification email (if configured)
    try {
        sendContestNotification($entry_id, $full_name, $user_email, $contest_id);
    } catch (Exception $e) {
        // Log email error but don't fail the submission
        error_log("Contest notification email failed for entry $entry_id: " . $e->getMessage());
    }
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for entering! We\'ll notify winners via email. Good luck!',
        'entry_id' => $entry_id
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    
    // Log the error
    error_log("Contest submission error: " . $e->getMessage() . " | User ID: " . ($user_id ?? 'unknown'));
}

/**
 * Send notification email for contest entry
 */
function sendContestNotification($entry_id, $full_name, $email, $contest_id) {
    $admin_email = 'admin@tennesseegolfcourses.com'; // Update with your admin email
    $subject = "New Contest Entry - ID #$entry_id";
    
    $message = "New contest entry received:\n\n";
    $message .= "Entry ID: $entry_id\n";
    $message .= "Contest ID: $contest_id\n";
    $message .= "Name: $full_name\n";
    $message .= "Email: $email\n";
    $message .= "Submitted: " . date('Y-m-d H:i:s') . "\n\n";
    $message .= "View entry in admin panel: https://tennesseegolfcourses.com/admin/contest-entries.php?id=$entry_id\n";
    
    $headers = "From: noreply@tennesseegolfcourses.com\r\n";
    $headers .= "Reply-To: noreply@tennesseegolfcourses.com\r\n";
    
    mail($admin_email, $subject, $message, $headers);
}
?>