<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /forgot-password');
    exit;
}

$email = trim($_POST['email']);

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /forgot-password?error=' . urlencode('Please enter a valid email address'));
    exit;
}

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, first_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = (new DateTime())->add(new DateInterval('PT1H'))->format('Y-m-d H:i:s');
        
        // Save token to database and unlock account
        $stmt = $pdo->prepare("UPDATE users SET password_reset_token = ?, password_reset_expires = ?, login_attempts = 0, account_locked_until = NULL WHERE id = ?");
        $stmt->execute([$token, $expires, $user['id']]);
        
        // Create reset link
        $resetLink = "https://tennesseegolfcourses.com/reset-password?token=" . urlencode($token);
        
        // Email content
        $subject = "Password Reset - Tennessee Golf Courses";
        $message = "Hello " . htmlspecialchars($user['first_name']) . ",\n\n";
        $message .= "You requested a password reset for your Tennessee Golf Courses account.\n\n";
        $message .= "Click the link below to reset your password:\n";
        $message .= $resetLink . "\n\n";
        $message .= "This link will expire in 1 hour.\n\n";
        $message .= "If you didn't request this reset, please ignore this email.\n\n";
        $message .= "Best regards,\nTennessee Golf Courses Team";
        
        $headers = "From: noreply@tennesseegolfcourses.com\r\n";
        $headers .= "Reply-To: support@tennesseegolfcourses.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email
        if (mail($email, $subject, $message, $headers)) {
            header('Location: /forgot-password?success=' . urlencode('Password reset link sent to your email address. Please check your inbox.'));
        } else {
            header('Location: /forgot-password?error=' . urlencode('Failed to send email. Please try again later.'));
        }
    } else {
        // Always show success message for security (don't reveal if email exists)
        header('Location: /forgot-password?success=' . urlencode('If that email address is in our system, you will receive a password reset link.'));
    }
    
} catch (PDOException $e) {
    header('Location: /forgot-password?error=' . urlencode('An error occurred. Please try again.'));
}

exit;
?>