<?php
header('Content-Type: application/json');
require_once '../includes/session-security.php';
require_once '../includes/csrf.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // If secure session fails, fallback to regular session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    error_log("Session start failed in refresh-csrf-token.php: " . $e->getMessage());
}

// Generate a new CSRF token
$token = CSRFProtection::generateToken();

// Return the new token as JSON
echo json_encode([
    'success' => true,
    'token' => $token
]);
?>