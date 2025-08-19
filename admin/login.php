<?php
require_once '../includes/admin-security-simple.php';
require_once '../includes/csrf.php';
require_once '../config/database.php';

// If already logged in, redirect to dashboard
if (AdminSecurity::isValidAdminSession()) {
    header('Location: /admin/dashboard');
    exit;
}

$error_message = $_GET['error'] ?? '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!CSRFProtection::validateToken($csrf_token)) {
        $error_message = 'Security token expired. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (!empty($username) && !empty($password)) {
            $result = AdminSecurity::authenticateAdmin($pdo, $username, $password);
            
            if ($result['success']) {
                // Start admin session
                AdminSecurity::startAdminSession($result['admin']);
                
                // Redirect to dashboard or requested page
                $redirect = $_GET['redirect'] ?? '/admin/dashboard';
                header('Location: ' . $redirect);
                exit;
            } else {
                $error_message = $result['error'];
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
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="../images/logos/tab-logo.webp?v=5">
    <link rel="shortcut icon" href="../images/logos/tab-logo.webp?v=5">
    
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
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            width: 100%;
            max-width: 450px;
            position: relative;
        }
        
        .admin-login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #2c5234, #ea580c);
            border-radius: 20px 20px 0 0;
        }
        
        .admin-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .admin-header h1 {
            color: #1f2937;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .admin-header .subtitle {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .security-badge {
            display: inline-flex;
            align-items: center;
            background: #f0fdf4;
            color: #15803d;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 1rem;
            border: 1px solid #bbf7d0;
        }
        
        .security-badge i {
            margin-right: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2c5234;
            box-shadow: 0 0 0 3px rgba(44, 82, 52, 0.1);
        }
        
        .totp-input {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5rem;
            font-family: 'Courier New', monospace;
        }
        
        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #2c5234, #1f3a26);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .login-btn:hover {
            background: linear-gradient(135deg, #1f3a26, #163020);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(44, 82, 52, 0.3);
        }
        
        .login-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid #fecaca;
            font-weight: 500;
        }
        
        .error-message i {
            margin-right: 0.5rem;
        }
        
        .security-info {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-top: 2rem;
        }
        
        .security-info h4 {
            color: #374151;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .security-info ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.85rem;
            color: #6b7280;
        }
        
        .security-info li {
            padding: 0.25rem 0;
            display: flex;
            align-items: center;
        }
        
        .security-info li i {
            margin-right: 0.5rem;
            color: #22c55e;
            width: 12px;
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-link a {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .back-link a:hover {
            color: #2c5234;
        }
        
        .totp-help {
            background: #fef3c7;
            color: #92400e;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            border: 1px solid #fde68a;
        }
        
        @media (max-width: 768px) {
            .admin-login-container {
                margin: 1rem;
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-header">
            <h1><i class="fas fa-shield-alt"></i> Admin Portal</h1>
            <p class="subtitle">Tennessee Golf Courses</p>
            <div class="security-badge">
                <i class="fas fa-lock"></i>
                Secure Admin Access
            </div>
        </div>
        
        <?php if ($error_message): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <?php echo CSRFProtection::getTokenField(); ?>
            
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i>
                    Username
                </label>
                <input type="text" id="username" name="username" 
                       required autocomplete="username"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Password
                </label>
                <input type="password" id="password" name="password" 
                       required autocomplete="current-password">
            </div>
            
            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i>
                Secure Login
            </button>
        </form>
        
        <div class="security-info">
            <h4><i class="fas fa-info-circle"></i> Security Features</h4>
            <ul>
                <li><i class="fas fa-check"></i> Encrypted connections (HTTPS)</li>
                <li><i class="fas fa-check"></i> Session security</li>
                <li><i class="fas fa-check"></i> Access logging</li>
                <li><i class="fas fa-check"></i> CSRF protection</li>
            </ul>
        </div>
        
        <div class="back-link">
            <a href="/">
                <i class="fas fa-arrow-left"></i>
                Back to Main Site
            </a>
        </div>
    </div>
    
    <script>
        // Auto-focus on username field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
        
        // Disable form submission during processing
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.querySelector('.login-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
        });
    </script>
</body>
</html>