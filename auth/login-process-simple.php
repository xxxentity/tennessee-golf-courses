<?php
require_once '../config/database.php';
require_once '../includes/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    header('Location: /login?error=' . urlencode('Session error. Please try again.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login');
    exit;
}

// Get form data
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header('Location: /login?error=' . urlencode('Please enter both username and password'));
    exit;
}

try {
    // Authenticate user
    $stmt = $pdo->prepare("SELECT id, username, email, password_hash, first_name, last_name, is_active FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password_hash'])) {
        // Login user using SecureSession
        SecureSession::login($user['id'], $user['username']);
        
        // Set additional user data
        SecureSession::set('email', $user['email']);
        SecureSession::set('first_name', $user['first_name']);
        SecureSession::set('last_name', $user['last_name']);
        
        // Check for redirect parameter
        $redirect = $_GET['redirect'] ?? '/';
        header('Location: ' . $redirect);
        exit;
    } else {
        header('Location: /login?error=' . urlencode('Invalid username/email or password'));
        exit;
    }
    
} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    header('Location: /login?error=' . urlencode('Login system error. Please try again.'));
    exit;
}
?>