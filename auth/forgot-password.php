<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Tennessee Golf Courses</title>
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
        
        .info-text {
            background: rgba(6, 78, 59, 0.05);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            color: var(--text-dark);
            font-size: 14px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="/" class="logo">Tennessee Golf Courses</a>
            <ul class="nav-menu">
                <li><a href="/">Home</a></li>
                <li><a href="login" class="active">Login</a></li>
                <li><a href="register">Register</a></li>
            </ul>
        </div>
    </nav>

    <main class="auth-page">
        <div class="auth-container">
            <div class="auth-header">
                <h2>Reset Password</h2>
                <p>Enter your email to reset your password</p>
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

                <div class="info-text">
                    <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                    Enter your email address and we'll send you a link to reset your password. This will also unlock your account if it was locked due to failed login attempts.
                </div>

                <form action="forgot-password-process" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="Enter your email address">
                    </div>

                    <button type="submit" class="btn-primary">Send Reset Link</button>
                </form>
            </div>
            
            <div class="auth-footer">
                <p>Remember your password? <a href="login">Sign in here</a></p>
            </div>
        </div>
    </main>

    <script src="../script.js"></script>
</body>
</html>