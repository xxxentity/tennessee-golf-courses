<?php
require_once '../config/database.php';
require_once '../includes/csrf.php';
require_once '../includes/auth-security.php';

// Validate CSRF token
$csrf_token = $_POST['csrf_token'] ?? '';
if (!CSRFProtection::validateToken($csrf_token)) {
    header('Location: /password-reset?error=' . urlencode('Security token expired or invalid. Please try again.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /password-reset');
    exit;
}

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate token
if (empty($token)) {
    header('Location: /password-reset?error=' . urlencode('Invalid or missing reset token.'));
    exit;
}

$resetData = AuthSecurity::validatePasswordResetToken($pdo, $token);
if (!$resetData) {
    header('Location: /password-reset?error=' . urlencode('Invalid or expired reset token. Please request a new password reset.'));
    exit;
}

// Validate passwords
if (empty($password) || empty($confirm_password)) {
    header('Location: /reset-password?token=' . urlencode($token) . '&error=' . urlencode('Please enter both password fields.'));
    exit;
}

if ($password !== $confirm_password) {
    header('Location: /reset-password?token=' . urlencode($token) . '&error=' . urlencode('Passwords do not match.'));
    exit;
}

// Validate password strength
$passwordValidation = AuthSecurity::validatePasswordStrength($password);
if (!$passwordValidation['valid']) {
    $error_message = implode('. ', $passwordValidation['feedback']);
    header('Location: /reset-password?token=' . urlencode($token) . '&error=' . urlencode($error_message));
    exit;
}

try {
    // Hash the new password
    $password_hash = AuthSecurity::hashPassword($password);
    
    // Update user's password and reset login attempts
    $stmt = $pdo->prepare("
        UPDATE users 
        SET password_hash = ?, 
            password_changed_at = NOW(),
            login_attempts = 0, 
            account_locked_until = NULL 
        WHERE id = ?
    ");
    $stmt->execute([$password_hash, $resetData['user_id']]);
    
    // Mark the reset token as used
    AuthSecurity::markTokenAsUsed($pdo, $token);
    
    // Log the password reset for security audit
    error_log("Password reset completed for user ID {$resetData['user_id']} (email: {$resetData['email']}) from IP: " . AuthSecurity::getClientIP());
    
    // Redirect to login with success message
    header('Location: /login?success=' . urlencode('Password reset successfully! You can now login with your new password.'));
    exit;
    
} catch (PDOException $e) {
    error_log("Password reset failed for user ID {$resetData['user_id']}: " . $e->getMessage());
    header('Location: /reset-password?token=' . urlencode($token) . '&error=' . urlencode('Failed to update password. Please try again.'));
    exit;
}
?>