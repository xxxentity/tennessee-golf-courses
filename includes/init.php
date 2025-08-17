<?php
/**
 * Initialize secure session for pages
 * Include this file at the top of any page that needs session access
 */

require_once __DIR__ . '/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid
    // For most pages, this is okay - user just won't be logged in
    // For protected pages, redirect to login
}

// Make session data easily accessible
$is_logged_in = SecureSession::isLoggedIn();
$user_id = SecureSession::get('user_id');
$username = SecureSession::get('username');
$email = SecureSession::get('email');
$first_name = SecureSession::get('first_name');
$last_name = SecureSession::get('last_name');
?>