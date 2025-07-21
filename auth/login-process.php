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
    $stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name, password_hash, is_active, login_attempts, account_locked_until FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header('Location: login.php?error=' . urlencode('Invalid username or password'));
        exit;
    }
    
    // Check if account is locked
    if ($user['account_locked_until'] && new DateTime() < new DateTime($user['account_locked_until'])) {
        $lockExpiry = new DateTime($user['account_locked_until']);
        $timeLeft = $lockExpiry->diff(new DateTime())->format('%h hours %i minutes');
        header('Location: login.php?error=' . urlencode('Account is locked due to too many failed login attempts. Try again in ' . $timeLeft . ' or reset your password.'));
        exit;
    }
    
    // Check if account is active
    if (!$user['is_active']) {
        header('Location: login.php?error=' . urlencode('Your account has been deactivated'));
        exit;
    }
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        // Increment login attempts
        $newAttempts = $user['login_attempts'] + 1;
        
        if ($newAttempts >= 5) {
            // Lock account for 1 hour
            $lockUntil = (new DateTime())->add(new DateInterval('PT1H'))->format('Y-m-d H:i:s');
            $stmt = $pdo->prepare("UPDATE users SET login_attempts = ?, account_locked_until = ? WHERE id = ?");
            $stmt->execute([5, $lockUntil, $user['id']]);
            header('Location: login.php?error=' . urlencode('Account locked due to 5 failed login attempts. Please reset your password or try again in 1 hour.'));
        } else {
            $stmt = $pdo->prepare("UPDATE users SET login_attempts = ? WHERE id = ?");
            $stmt->execute([$newAttempts, $user['id']]);
            $remaining = 5 - $newAttempts;
            header('Location: login.php?error=' . urlencode('Invalid username or password. ' . $remaining . ' attempts remaining before account lock.'));
        }
        exit;
    }
    
    // Login successful - reset login attempts and create session
    $stmt = $pdo->prepare("UPDATE users SET login_attempts = 0, account_locked_until = NULL WHERE id = ?");
    $stmt->execute([$user['id']]);
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['logged_in'] = true;
    
    // Redirect to homepage or requested page
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../index.php';
    header('Location: ' . $redirect);
    exit;
    
} catch (PDOException $e) {
    header('Location: login.php?error=' . urlencode('Login failed. Please try again.'));
    exit;
}
?>