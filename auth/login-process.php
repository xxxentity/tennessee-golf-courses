<?php
require_once '../includes/session-security.php';
require_once '../config/database.php';
require_once '../includes/csrf.php';
require_once '../includes/auth-security.php';
require_once '../includes/input-validation.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - redirect to login
    header('Location: /login?error=' . urlencode($e->getMessage()));
    exit;
}

// Validate CSRF token first
$csrf_token = $_POST['csrf_token'] ?? '';
if (!CSRFProtection::validateToken($csrf_token)) {
    header('Location: /login?error=' . urlencode('Security token expired or invalid. Please try again.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login');
    exit;
}

// Get and validate form data
$username = InputValidator::sanitizeString($_POST['username'] ?? '', ['max_length' => 254]);
$password = $_POST['password'] ?? '';
$clientIP = AuthSecurity::getClientIP();

// Basic validation
if (empty($username) || empty($password)) {
    header('Location: /login?error=' . urlencode('Please enter both username and password'));
    exit;
}

// Additional security checks
if (InputValidator::containsXSS($username) || InputValidator::containsSQLInjection($username)) {
    // Log suspicious login attempt
    error_log("Suspicious login attempt from IP: $clientIP with username: " . substr($username, 0, 50));
    header('Location: /login?error=' . urlencode('Invalid login credentials'));
    exit;
}

// Check for suspicious activity from this IP
$suspiciousActivity = AuthSecurity::analyzeSuspiciousActivity($pdo, $clientIP);
if ($suspiciousActivity['should_block']) {
    AuthSecurity::recordLoginAttempt($pdo, $username, false, $clientIP);
    error_log("Blocked login attempt from suspicious IP: $clientIP");
    header('Location: /login?error=' . urlencode('Too many failed login attempts. Please try again later.'));
    exit;
}

try {
    // Check if user exists (username or email)
    $stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name, password_hash, is_active, login_attempts, account_locked_until, email_verified FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header('Location: /login?error=' . urlencode('Invalid username or password'));
        exit;
    }
    
    // Check if account is locked
    if ($user['account_locked_until'] && new DateTime() < new DateTime($user['account_locked_until'])) {
        $lockExpiry = new DateTime($user['account_locked_until']);
        $timeLeft = $lockExpiry->diff(new DateTime())->format('%h hours %i minutes');
        header('Location: /login?error=' . urlencode('Account is locked due to too many failed login attempts. Try again in ' . $timeLeft . ' or reset your password.'));
        exit;
    }
    
    // Check if account is active
    if (!$user['is_active']) {
        header('Location: /login?error=' . urlencode('Your account has been deactivated'));
        exit;
    }
    
    // Check if email is verified
    if (!$user['email_verified']) {
        header('Location: /login?error=' . urlencode('Please verify your email address before logging in. Check your inbox for the verification email.'));
        exit;
    }
    
    // Verify password using enhanced authentication
    if (!AuthSecurity::verifyPassword($password, $user['password_hash'])) {
        // Record failed login attempt
        AuthSecurity::recordLoginAttempt($pdo, $username, false, $clientIP);
        
        // Increment login attempts
        $newAttempts = $user['login_attempts'] + 1;
        
        if ($newAttempts >= AuthSecurity::MAX_LOGIN_ATTEMPTS) {
            // Lock account
            $lockUntil = (new DateTime())->add(new DateInterval('PT' . AuthSecurity::LOCKOUT_DURATION . 'S'))->format('Y-m-d H:i:s');
            $stmt = $pdo->prepare("UPDATE users SET login_attempts = ?, account_locked_until = ? WHERE id = ?");
            $stmt->execute([$newAttempts, $lockUntil, $user['id']]);
            
            error_log("Account locked for user ID {$user['id']} due to failed login attempts from IP: $clientIP");
            header('Location: /login?error=' . urlencode('Account locked due to too many failed login attempts. Please reset your password or try again later.'));
        } else {
            $stmt = $pdo->prepare("UPDATE users SET login_attempts = ? WHERE id = ?");
            $stmt->execute([$newAttempts, $user['id']]);
            $remaining = AuthSecurity::MAX_LOGIN_ATTEMPTS - $newAttempts;
            header('Location: /login?error=' . urlencode('Invalid username or password. ' . $remaining . ' attempts remaining before account lock.'));
        }
        exit;
    }
    
    // Check if password needs rehashing (security upgrade)
    if (AuthSecurity::needsRehash($user['password_hash'])) {
        $newHash = AuthSecurity::hashPassword($password);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newHash, $user['id']]);
    }
    
    // Login successful - reset login attempts (remove last_login since column may not exist)
    $stmt = $pdo->prepare("UPDATE users SET login_attempts = 0, account_locked_until = NULL WHERE id = ?");
    $stmt->execute([$user['id']]);
    
    // Record successful login attempt
    AuthSecurity::recordLoginAttempt($pdo, $username, true, $clientIP);
    
    // Use secure session login method
    SecureSession::login($user['id'], $user['username']);
    
    // Store additional user data
    SecureSession::set('email', $user['email']);
    SecureSession::set('first_name', $user['first_name']);
    SecureSession::set('last_name', $user['last_name']);
    
    // Log successful login for security audit
    error_log("Successful login for user ID {$user['id']} from IP: $clientIP");
    
    // Redirect to homepage or requested page
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/';
    header('Location: ' . $redirect);
    exit;
    
} catch (PDOException $e) {
    header('Location: /login?error=' . urlencode('Login failed. Please try again.'));
    exit;
}
?>