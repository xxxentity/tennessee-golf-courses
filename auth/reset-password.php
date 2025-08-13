<?php
session_start();
require_once '../config/database.php';

$token = $_GET['token'] ?? '';
$user = null;
$error = '';

if (empty($token)) {
    $error = 'Invalid or missing reset token';
} else {
    try {
        // Check if token is valid and not expired
        $stmt = $pdo->prepare("SELECT id, first_name, email FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $error = 'Invalid or expired reset token';
        }
    } catch (PDOException $e) {
        $error = 'Database error occurred';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($new_password) || empty($confirm_password)) {
        $error = 'Please enter both password fields';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        try {
            // Update password and clear reset token
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, password_reset_token = NULL, password_reset_expires = NULL, login_attempts = 0, account_locked_until = NULL WHERE id = ?");
            $stmt->execute([$password_hash, $user['id']]);
            
            header('Location: login.php?success=' . urlencode('Password reset successfully. You can now login with your new password.'));
            exit;
            
        } catch (PDOException $e) {
            $error = 'Failed to update password. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/tab-logo.webp?v=2">
    <link rel="shortcut icon" href="../images/logos/tab-logo.webp?v=2">
    <style>
        body {
            background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-white) 100%);
            min-height: 100vh;
        }
        
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px 40px;
        }
        
        .auth-container {
            max-width: 480px;
            width: 100%;
            background: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-heavy);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        
        .auth-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            padding: 40px 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .auth-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .auth-header p {
            opacity: 0.9;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }
        
        .auth-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 140px;
            text-align: right;
            line-height: 1.2;
        }
        
        .form-group input {
            width: 300px;
            padding: 18px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: var(--bg-white);
            color: var(--text-dark);
            box-sizing: border-box;
            height: 56px;
            line-height: 1.4;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, 0.1);
            transform: translateY(-1px);
        }
        
        .form-group input:hover {
            border-color: var(--secondary-color);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            padding: 16px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-medium);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-heavy);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 500;
            border: 1px solid;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.2);
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border-color: rgba(34, 197, 94, 0.2);
        }
        
        .auth-footer {
            text-align: center;
            padding: 20px 40px 40px;
            color: var(--text-gray);
        }
        
        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .auth-footer a:hover {
            color: var(--secondary-color);
        }
        
        .user-info {
            background: rgba(6, 78, 59, 0.05);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            color: var(--text-dark);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="../index.php" class="logo">Tennessee Golf Courses</a>
            <ul class="nav-menu">
                <li><a href="../index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </div>
    </nav>

    <main class="auth-page">
        <div class="auth-container">
            <div class="auth-header">
                <h2>Set New Password</h2>
                <p>Enter your new password below</p>
            </div>
            
            <div class="auth-body">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($user): ?>
                    <div class="user-info">
                        <i class="fas fa-user" style="margin-right: 8px;"></i>
                        Resetting password for: <strong><?php echo htmlspecialchars($user['email']); ?></strong>
                    </div>

                    <form method="POST">
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" required minlength="6" placeholder="Enter new password">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Confirm new password">
                        </div>

                        <button type="submit" class="btn-primary">Update Password</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        This password reset link is invalid or has expired. Please request a new one.
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="auth-footer">
                <p><a href="login.php">Back to Login</a> | <a href="forgot-password.php">Request New Reset Link</a></p>
            </div>
        </div>
    </main>

    <script src="../script.js"></script>
</body>
</html>