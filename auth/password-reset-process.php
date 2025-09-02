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

$email = trim($_POST['email']);

// Basic validation
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /password-reset?error=' . urlencode('Please enter a valid email address.'));
    exit;
}

try {
    // Check if user exists with this email
    $stmt = $pdo->prepare("SELECT id, username, first_name, is_active FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Don't reveal if email exists or not for security
        header('Location: /password-reset?success=' . urlencode('If an account with that email exists, we\'ve sent a password reset link.'));
        exit;
    }
    
    if (!$user['is_active']) {
        header('Location: /password-reset?error=' . urlencode('This account has been deactivated.'));
        exit;
    }
    
    // Generate password reset token
    $resetToken = AuthSecurity::generatePasswordResetToken($pdo, $user['id']);
    
    // Send password reset email
    $subject = "Password Reset Request - Tennessee Golf Courses";
    $message = "
    <html>
    <head><title>Password Reset - Tennessee Golf Courses</title></head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #064E3B;'>Password Reset Request</h1>
            </div>
            
            <p>Hello " . htmlspecialchars($user['first_name']) . ",</p>
            
            <p>We received a request to reset the password for your Tennessee Golf Courses account. If you didn't make this request, you can safely ignore this email.</p>
            
            <div style='text-align: center; margin: 30px 0; padding: 20px; background: #f0f9ff; border-radius: 8px;'>
                <a href='https://tennesseegolfcourses.com/reset-password?token=" . $resetToken . "' style='background: #064E3B; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: 600;'>
                    Reset My Password
                </a>
            </div>
            
            <p><strong>Important Security Information:</strong></p>
            <ul>
                <li>This link will expire in 1 hour for your security</li>
                <li>You can only use this link once</li>
                <li>If you didn't request this reset, please ignore this email</li>
                <li>Never share this link with anyone</li>
            </ul>
            
            <div style='background: #fef3c7; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f59e0b;'>
                <p style='margin: 0; color: #92400e;'><strong>Security Tip:</strong> Always verify that password reset emails come from tennesseegolfcourses.com and never click suspicious links.</p>
            </div>
            
            <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
            <p style='font-size: 12px; color: #666;'>
                If the button doesn't work, copy and paste this link into your browser:<br>
                https://tennesseegolfcourses.com/reset-password?token=" . $resetToken . "
            </p>
            
            <p style='font-size: 12px; color: #666;'>
                This email was sent to " . htmlspecialchars($email) . " because a password reset was requested for your Tennessee Golf Courses account.
            </p>
        </div>
    </body>
    </html>
    ";
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Tennessee Golf Courses <noreply@tennesseegolfcourses.com>" . "\r\n";
    $headers .= "Reply-To: support@tennesseegolfcourses.com" . "\r\n";
    
    // Send the email
    if (mail($email, $subject, $message, $headers)) {
        // Log the password reset request
        error_log("Password reset requested for user ID {$user['id']} (email: $email) from IP: " . AuthSecurity::getClientIP());
        
        header('Location: /password-reset?success=' . urlencode('Password reset link has been sent to your email address. Please check your inbox and spam folder.'));
    } else {
        error_log("Failed to send password reset email to $email");
        header('Location: /password-reset?error=' . urlencode('Failed to send reset email. Please try again later.'));
    }
    
} catch (Exception $e) {
    error_log("Password reset error: " . $e->getMessage());
    header('Location: /password-reset?error=' . urlencode('An error occurred. Please try again later.'));
}

exit;
?>