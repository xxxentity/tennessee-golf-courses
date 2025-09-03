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
    header('Location: /login?error=' . urlencode($e->getMessage()));
    exit;
}

// Validate CSRF token
$csrf_token = $_POST['csrf_token'] ?? '';
if (!CSRFProtection::validateToken($csrf_token)) {
    header('Location: /login?error=' . urlencode('Security token expired. Please try again.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login');
    exit;
}

$username_or_email = InputValidator::sanitizeString($_POST['username'] ?? '', ['max_length' => 254]);

if (empty($username_or_email)) {
    header('Location: /login?error=' . urlencode('Please enter your username or email'));
    exit;
}

try {
    // Find the unverified user
    $stmt = $pdo->prepare("SELECT id, username, email, first_name, email_verification_token, email_verified FROM users WHERE (username = ? OR email = ?) AND email_verified = 0");
    $stmt->execute([$username_or_email, $username_or_email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header('Location: /login?error=' . urlencode('User not found or already verified'));
        exit;
    }
    
    // Generate new verification token if needed
    $verification_token = $user['email_verification_token'];
    if (empty($verification_token)) {
        $verification_token = AuthSecurity::generateEmailVerificationToken();
        $stmt = $pdo->prepare("UPDATE users SET email_verification_token = ? WHERE id = ?");
        $stmt->execute([$verification_token, $user['id']]);
    }
    
    // Send verification email
    $subject = "Verify Your Email - Tennessee Golf Courses";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Verify Your Email - Tennessee Golf Courses</title>
    </head>
    <body style='font-family: Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden;'>
            <div style='background: linear-gradient(135deg, #065f46, #059669); padding: 30px; text-align: center;'>
                <img src='https://tennesseegolfcourses.com/images/logos/tab-logo.webp' alt='Tennessee Golf Courses' style='max-width: 120px; height: auto; margin-bottom: 15px;'>
                <h1 style='color: white; margin: 0; font-size: 28px; font-weight: bold;'>Email Verification</h1>
            </div>
            
            <div style='padding: 40px 30px;'>
                <h2 style='color: #1f2937; margin-bottom: 20px; font-size: 24px;'>Hi " . htmlspecialchars($user['first_name']) . "!</h2>
                
                <p style='color: #4b5563; font-size: 16px; line-height: 1.6; margin-bottom: 25px;'>
                    Please verify your email address to complete your registration and access all features.
                </p>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='https://tennesseegolfcourses.com/verify-email?token=" . $verification_token . "' style='background: #064E3B; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: 600; display: inline-block;'>
                        Verify My Email Address
                    </a>
                </div>
                
                <p style='color: #4b5563; font-size: 14px; line-height: 1.5; margin-top: 30px;'>
                    <strong>Important:</strong> You must verify your email to:
                </p>
                
                <ul style='color: #4b5563; font-size: 14px; line-height: 1.5; margin-left: 20px;'>
                    <li>Access your account dashboard</li>
                    <li>Submit course reviews and ratings</li>
                    <li>Receive our newsletter with golf tips and course updates</li>
                    <li>Participate in community discussions</li>
                </ul>
                
                <div style='margin-top: 30px; padding: 20px; background-color: #f9fafb; border-radius: 8px; border-left: 4px solid #10b981;'>
                    <p style='color: #065f46; font-size: 14px; margin: 0; font-weight: 600;'>
                        🎯 Tennessee Golf Pro Tip: Check out our course reviews to find hidden gems across the state!
                    </p>
                </div>
                
                <p style='color: #6b7280; font-size: 12px; line-height: 1.4; margin-top: 30px;'>
                    If you didn't create this account, you can safely ignore this email. The verification link will expire in 24 hours.
                    <br><br>
                    If the button doesn't work, copy and paste this link into your browser:<br>
                    <span style='word-break: break-all;'>https://tennesseegolfcourses.com/verify-email?token=" . $verification_token . "</span>
                </p>
                
                <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;'>
                    <p style='color: #9ca3af; font-size: 12px; margin: 0;'>
                        Tennessee Golf Courses - Your Ultimate Guide to Golf in Tennessee
                        <br>
                        <a href='https://tennesseegolfcourses.com' style='color: #059669; text-decoration: none;'>tennesseegolfcourses.com</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Tennessee Golf Courses <newsletter@tennesseegolfcourses.com>" . "\r\n";
    $headers .= "Reply-To: info@tennesseegolfcourses.com" . "\r\n";
    
    // Send email
    if (mail($user['email'], $subject, $message, $headers)) {
        header('Location: /login?success=' . urlencode('Verification email sent! Please check your inbox and spam folder.'));
    } else {
        header('Location: /login?error=' . urlencode('Failed to send verification email. Please try again later.'));
    }
    
} catch (Exception $e) {
    error_log("Resend verification error: " . $e->getMessage());
    header('Location: /login?error=' . urlencode('An error occurred. Please try again later.'));
}
?>