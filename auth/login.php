<?php
require_once '../includes/session-security.php';
require_once '../includes/csrf.php';

// Start secure session for CSRF protection
try {
    SecureSession::start();
} catch (Exception $e) {
    // If session fails, continue but log the error
    error_log("Session start failed in login.php: " . $e->getMessage());
}

// Generate CSRF token
$csrf_token = CSRFProtection::generateToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="../images/logos/tab-logo.webp?v=3">
    <link rel="shortcut icon" href="../images/logos/tab-logo.webp?v=3">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
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
        
        .auth-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>');
            pointer-events: none;
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
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <main class="auth-page">
        <div class="auth-container">
            <div class="auth-header">
                <h2>Welcome Back</h2>
                <p>Sign in to your Tennessee Golf Courses account</p>
            </div>
            
            <div class="auth-body">
                <?php
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-error">' . htmlspecialchars($_GET['error']) . '</div>';
                }
                if (isset($_GET['success'])) {
                    echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
                }
                ?>

                <form action="login-process" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required placeholder="Enter username or email">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Enter password">
                    </div>

                    <button type="submit" class="btn-primary">Sign In</button>
                </form>
            </div>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="register">Create one here</a></p>
                <p style="margin-top: 10px;"><a href="forgot-password">Forgot your password?</a></p>
            </div>
        </div>
    </main>

    <script src="../script.js"></script>
</body>
</html>