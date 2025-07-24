<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Free - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        .form-row {
            display: block;
            margin-bottom: 0;
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
        
        .form-icon {
            position: relative;
        }
        
        .form-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-gray);
            pointer-events: none;
            transition: color 0.3s ease;
        }
        
        .form-icon {
            position: relative;
            width: 300px;
        }
        
        .form-icon input {
            width: 100%;
            padding-left: 50px;
            padding-right: 16px;
        }
        
        .form-icon input:focus + i {
            color: var(--primary-color);
        }
        
        .checkbox-group {
            margin-bottom: 32px;
            padding: 20px;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        
        .checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .checkbox-item:last-child {
            margin-bottom: 0;
        }
        
        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin: 0;
            cursor: pointer;
            accent-color: var(--primary-color);
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .checkbox-item label {
            font-size: 14px;
            line-height: 1.5;
            color: var(--text-gray);
            cursor: pointer;
            font-weight: 400;
            text-transform: none;
            letter-spacing: 0;
            min-width: auto;
            text-align: left;
            margin: 0;
        }
        
        .checkbox-item label a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .checkbox-item label a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .auth-page {
                padding: 80px 16px 20px;
            }
            
            .auth-header {
                padding: 30px 24px 20px;
            }
            
            .auth-header h2 {
                font-size: 1.5rem;
            }
            
            .auth-body {
                padding: 24px;
            }
            
            .form-row {
                display: block;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .auth-footer {
                padding: 16px 24px 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <main class="auth-page">
        <div class="auth-container">
            <div class="auth-header">
                <h2>Join the Community</h2>
                <p>Discover Tennessee's premier golf destinations</p>
            </div>
            
            <div class="auth-body">
                <?php
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        ' . htmlspecialchars($_GET['error']) . '
                    </div>';
                }
                if (isset($_GET['success'])) {
                    echo '<div class="alert alert-success">
                        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                        ' . htmlspecialchars($_GET['success']) . '
                    </div>';
                }
                ?>

                <form action="register-process" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="form-icon">
                            <input type="text" id="username" name="username" required maxlength="50" placeholder="Enter username">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="form-icon">
                            <input type="email" id="email" name="email" required maxlength="100" placeholder="your@email.com">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First&nbsp;Name</label>
                            <div class="form-icon">
                                <input type="text" id="first_name" name="first_name" required maxlength="50" placeholder="First name">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last&nbsp;Name</label>
                            <div class="form-icon">
                                <input type="text" id="last_name" name="last_name" required maxlength="50" placeholder="Last name">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="form-icon">
                            <input type="password" id="password" name="password" required minlength="6" placeholder="Enter password">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm<br>Password</label>
                        <div class="form-icon">
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Re-enter password">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="agree_terms" name="agree_terms" required>
                            <label for="agree_terms">
                                I agree to the <a href="/terms-of-service" target="_blank">Terms of Service</a> and <a href="/privacy-policy" target="_blank">Privacy Policy</a>
                            </label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="newsletter_subscribe" name="newsletter_subscribe" checked>
                            <label for="newsletter_subscribe">
                                Subscribe to our newsletter for course updates and golf news
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-golf-ball" style="margin-right: 8px;"></i>
                        Create Account
                    </button>
                </form>
            </div>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="login">Sign in here</a></p>
            </div>
        </div>
    </main>

    <script src="../script.js"></script>
</body>
</html>