<?php
require_once '../includes/session-security.php';

// Start secure session to access it
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session already invalid, just redirect
    header('Location: /');
    exit;
}

// Destroy session securely
SecureSession::logout();

// Redirect to homepage
header('Location: /');
exit;
?>