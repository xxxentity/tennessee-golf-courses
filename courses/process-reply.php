<?php
require_once '../includes/session-security.php';
require_once '../config/database.php';
require_once '../includes/csrf.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    header('Location: ../login');
    exit();
}

// Check if user is logged in
if (!SecureSession::isLoggedIn()) {
    header('Location: ../login');
    exit();
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug logging
    error_log("Reply submission attempt - POST data: " . print_r($_POST, true));
    error_log("User ID: " . ($_SESSION['user_id'] ?? 'NOT SET'));
    
    // Validate CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!CSRFProtection::validateToken($csrf_token)) {
        error_log("CSRF token validation failed");
        $_SESSION['error'] = 'Security token expired or invalid. Please refresh the page and try again.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
    $parent_comment_id = (int)$_POST['parent_comment_id'];
    $course_slug = $_POST['course_slug'];
    $reply_text = trim($_POST['reply_text']);
    $user_id = $_SESSION['user_id'];
    
    error_log("Processed data - Parent ID: $parent_comment_id, Course: $course_slug, Reply: $reply_text, User: $user_id");
    
    if (!empty($reply_text) && $parent_comment_id > 0) {
        try {
            // Get course name from parent comment
            $stmt = $pdo->prepare("SELECT course_name FROM course_comments WHERE id = ?");
            $stmt->execute([$parent_comment_id]);
            $parent = $stmt->fetch();
            
            error_log("Parent comment found: " . print_r($parent, true));
            
            if ($parent) {
                // Insert reply (no rating for replies)
                $stmt = $pdo->prepare("INSERT INTO course_comments (user_id, course_slug, course_name, comment_text, parent_comment_id) VALUES (?, ?, ?, ?, ?)");
                $result = $stmt->execute([$user_id, $course_slug, $parent['course_name'], $reply_text, $parent_comment_id]);
                
                error_log("Database insert result: " . ($result ? 'SUCCESS' : 'FAILED'));
                error_log("Insert ID: " . $pdo->lastInsertId());
                
                $_SESSION['success'] = "Your reply has been posted successfully!";
            } else {
                error_log("Parent comment not found for ID: $parent_comment_id");
                $_SESSION['error'] = "Parent comment not found.";
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['error'] = "Error posting reply. Please try again. " . $e->getMessage();
        }
    } else {
        error_log("Validation failed - Reply text: '$reply_text', Parent ID: $parent_comment_id");
        $_SESSION['error'] = "Please provide a valid reply.";
    }
}

// Redirect back to the course page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>