<?php
require_once '../includes/admin-security.php';
require_once '../includes/csrf.php';
require_once '../config/database.php';

// If already logged in, redirect to dashboard
if (AdminSecurity::isValidAdminSession()) {
    header('Location: /admin/dashboard');
    exit;
}

$error_message = $_GET['error'] ?? '';
$require_2fa = false;
$admin_id = null;

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!CSRFProtection::validateToken($csrf_token)) {
        $error_message = 'Security token expired. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $totp_code = trim($_POST['totp_code'] ?? '');
        
        if (!empty($username) && !empty($password)) {
            $result = AdminSecurity::authenticateAdmin($pdo, $username, $password, $totp_code);
            
            if ($result['success']) {
                // Start admin session
                AdminSecurity::startAdminSession($result['admin']);
                
                // Redirect to dashboard or requested page
                $redirect = $_GET['redirect'] ?? '/admin/dashboard';
                header('Location: ' . $redirect);
                exit;
            } else {
                $error_message = $result['error'];
                
                // Handle 2FA requirement
                if ($result['code'] === 'TOTP_REQUIRED') {
                    $require_2fa = true;
                    $admin_id = $result['admin_id'];
                }
            }
        } else {
            $error_message = 'Please enter both username and password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        
        .admin-login-container {
            background: white;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .admin-logo {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .admin-subtitle {
            color: var(--text-gray);
            margin-bottom: 32px;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .admin-btn {
            width: 100%;
            background: var(--primary-color);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .admin-btn:hover {
            background: var(--secondary-color);
        }
        
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .back-link {
            margin-top: 20px;
        }
        
        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .default-creds {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .default-creds strong {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-logo">🏌️ Admin Panel</div>
        <div class="admin-subtitle">Tennessee Golf Courses</div>
        
        <div class="default-creds">
            <strong>Default Login:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>admin123</code><br>
            <small>If login fails, <a href="/admin/create-admin">create admin user</a></small>
        </div>
        
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="admin-btn">Login to Admin Panel</button>
        </form>
        
        <div class="back-link">
            <a href="/">← Back to Website</a>
        </div>
    </div>
</body>
</html>