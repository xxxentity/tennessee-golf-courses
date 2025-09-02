<?php
header('Content-Type: application/json');

// Start session first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/csrf.php';

// Generate a new CSRF token
$token = CSRFProtection::generateToken();

// Return the new token as JSON
echo json_encode([
    'success' => true,
    'token' => $token
]);
?>