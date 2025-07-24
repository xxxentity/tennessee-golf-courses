<?php
session_start();
require_once '../config/database.php';

$token = $_GET['token'] ?? '';
$message = '';
$success = false;

if ($token) {
    try {
        // Find user by verification token
        $stmt = $pdo->prepare("SELECT id, username, email, first_name FROM users WHERE email_verification_token = ? AND email_verified = 0");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verify the email
            $stmt = $pdo->prepare("UPDATE users SET email_verified = 1, email_verification_token = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            $message = "Email verified successfully! You can now login to your account.";
            $success = true;
            
            // Auto-login the user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['is_logged_in'] = true;
            
        } else {
            $message = "Invalid verification link or email already verified.";
        }
    } catch (PDOException $e) {
        error_log("Email verification error: " . $e->getMessage());
        $message = "There was an error verifying your email. Please try again.";
    }
} else {
    $message = "Invalid verification link.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="/styles.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-white) 100%);
            min-height: 100vh;
        }
        
        .verification-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            text-align: center;
        }
        
        .verification-icon {
            font-size: 48px;
            margin-bottom: 24px;
            color: <?php echo $success ? 'var(--success-color)' : 'var(--error-color)'; ?>;
        }
        
        .verification-message {
            font-size: 18px;
            margin-bottom: 32px;
            color: var(--text-dark);
        }
        
        .action-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            background: var(--primary-color);
            color: var(--text-light);
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            transition: var(--transition);
        }
        
        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: var(--gold-color);
            color: var(--text-dark);
        }
        
        .btn-secondary:hover {
            background: #c49c2f;
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>
    
    <div class="verification-container">
        <div class="verification-icon">
            <?php echo $success ? '✅' : '❌'; ?>
        </div>
        
        <div class="verification-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
        
        <div class="action-buttons">
            <?php if ($success): ?>
                <a href="/profile" class="btn">View My Profile</a>
                <a href="/courses" class="btn-secondary btn">Explore Courses</a>
            <?php else: ?>
                <a href="/login" class="btn">Try Login</a>
                <a href="/register" class="btn-secondary btn">Register Again</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>