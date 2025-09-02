<?php
/**
 * Enhanced initialization with performance optimization
 * Include this file at the top of any page for session access and performance features
 */

// Start performance monitoring
require_once __DIR__ . '/performance.php';
Performance::start();

// Enable compression for better performance
Performance::enableCompression();

// Initialize session security
require_once __DIR__ . '/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid
    // For most pages, this is okay - user just won't be logged in
    // For protected pages, redirect to login
}

// Initialize database with caching
require_once __DIR__ . '/database-cache.php';
require_once __DIR__ . '/../config/database.php';

// Create cached database instance
$dbCache = new DatabaseCache($pdo);

// Make session data easily accessible
$is_logged_in = SecureSession::isLoggedIn();
$user_id = SecureSession::get('user_id');
$username = SecureSession::get('username');
$email = SecureSession::get('email');
$first_name = SecureSession::get('first_name');
$last_name = SecureSession::get('last_name');

// Performance headers for caching
if (!headers_sent()) {
    Performance::sendPerformanceHeaders();
}
?>