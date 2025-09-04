<?php
// API endpoint to check login status (never cached)
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate, private');
header('Pragma: no-cache');
header('Expires: 0');
header('CF-Cache-Status: BYPASS');

require_once '../includes/session-security.php';

try {
    SecureSession::start();
    $is_logged_in = SecureSession::isLoggedIn();
    
    if ($is_logged_in) {
        echo json_encode([
            'logged_in' => true,
            'username' => SecureSession::get('username', ''),
            'first_name' => SecureSession::get('first_name', '')
        ]);
    } else {
        echo json_encode(['logged_in' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['logged_in' => false, 'error' => $e->getMessage()]);
}
?>