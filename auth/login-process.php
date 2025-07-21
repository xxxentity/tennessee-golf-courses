<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Get form data
$username = trim($_POST['username']);
$password = $_POST['password'];

// Validation
if (empty($username) || empty($password)) {
    header('Location: login.php?error=' . urlencode('Please enter both username and password'));
    exit;
}

try {
    // Check if user exists (username or email)
    $stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name, password_hash, is_active FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header('Location: login.php?error=' . urlencode('Invalid username or password'));
        exit;
    }
    
    // Check if account is active
    if (!$user['is_active']) {
        header('Location: login.php?error=' . urlencode('Your account has been deactivated'));
        exit;
    }
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        header('Location: login.php?error=' . urlencode('Invalid username or password'));
        exit;
    }
    
    // Login successful - create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['logged_in'] = true;
    
    // Redirect to homepage or requested page
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../index.html';
    header('Location: ' . $redirect);
    exit;
    
} catch (PDOException $e) {
    header('Location: login.php?error=' . urlencode('Login failed. Please try again.'));
    exit;
}
?>