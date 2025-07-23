<?php
session_start();
require_once '../config/database.php';

// Admin authentication check
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /admin/login');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $error = 'New passwords do not match';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        try {
            // Get current admin user
            $stmt = $pdo->prepare("SELECT password_hash FROM admin_users WHERE id = ?");
            $stmt->execute([$_SESSION['admin_id']]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin && password_verify($current_password, $admin['password_hash'])) {
                // Update password
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
                $stmt->execute([$new_hash, $_SESSION['admin_id']]);
                
                $message = 'Password changed successfully!';
            } else {
                $error = 'Current password is incorrect';
            }
        } catch (PDOException $e) {
            $error = 'Failed to update password. Please try again.';
            error_log("Password change error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Admin Password - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body { background: var(--bg-light); min-height: 100vh; padding-top: 180px; }
        .admin-container { max-width: 600px; margin: 40px auto; padding: 20px; }
        .admin-section { background: var(--bg-white); padding: 32px; border-radius: 12px; box-shadow: var(--shadow-light); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; }
        .btn { background: var(--primary-color); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: var(--secondary-color); }
        .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb; }
        .back-link { margin-top: 20px; text-align: center; }
        .back-link a { color: var(--primary-color); text-decoration: none; }
        .info-box { background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 16px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>
    
    <div class="admin-container">
        <div class="admin-section">
            <h1>Change Admin Password</h1>
            
            <div class="info-box">
                <strong>Admin Account Info:</strong><br>
                Username: <?php echo htmlspecialchars($_SESSION['admin_username']); ?><br>
                Email: <?php echo htmlspecialchars($_SESSION['admin_email']); ?><br>
                <small>This is separate from regular user accounts</small>
            </div>
            
            <?php if ($message): ?>
                <div class="alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required placeholder="Enter current password (admin123)">
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required placeholder="Enter new password (min 6 chars)">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm new password">
                </div>
                
                <button type="submit" class="btn">Change Password</button>
            </form>
            
            <div class="back-link">
                <a href="/admin/newsletter">← Back to Newsletter Admin</a>
            </div>
        </div>
    </div>
</body>
</html>