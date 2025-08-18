<?php
require_once '../config/database.php';
require_once '../includes/csrf.php';
require_once '../includes/auth-security.php';


$token = $_GET['token'] ?? '';
$error_message = $_GET['error'] ?? '';
$success_message = $_GET['success'] ?? '';

// Validate token
if (empty($token)) {
    header('Location: /password-reset?error=' . urlencode('Invalid or missing reset token.'));
    exit;
}

// Check if token is valid
$resetData = AuthSecurity::validatePasswordResetToken($pdo, $token);
if (!$resetData) {
    header('Location: /password-reset?error=' . urlencode('Invalid or expired reset token. Please request a new password reset.'));
    exit;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
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
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <main class="auth-page">
        <div class="auth-container">
            <div class="auth-header">
                <h2>Set New Password</h2>
                <p>Create a strong, secure password for your account</p>
            </div>
            
            <div class="auth-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <div class="user-info">
                    <i class="fas fa-user" style="margin-right: 8px;"></i>
                    Resetting password for: <strong><?php echo htmlspecialchars($resetData['email']); ?></strong>
                </div>

                <form action="/reset-password-process" method="POST" id="resetForm">
                    <?php echo CSRFProtection::getTokenField(); ?>
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter your new password">
                        
                        <div id="passwordStrength" class="password-strength" style="display: none; margin-top: 10px; padding: 10px; border-radius: 8px; font-size: 0.9rem;">
                            <div id="strengthText"></div>
                            <ul id="requirementsList" style="margin: 5px 0 0 0; padding-left: 0; list-style: none; font-size: 0.85rem;">
                                <li id="req-length">At least 8 characters</li>
                                <li id="req-uppercase">One uppercase letter</li>
                                <li id="req-lowercase">One lowercase letter</li>
                                <li id="req-number">One number</li>
                                <li id="req-special">One special character</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               placeholder="Confirm your new password">
                    </div>

                    <button type="submit" id="submitBtn" class="btn-primary" disabled>Update Password</button>
                </form>
            </div>
            
            <div class="auth-footer">
                <p><a href="/login">Back to Login</a> | <a href="/password-reset">Request New Reset Link</a></p>
            </div>
        </div>
    </main>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const strengthDiv = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');
        const submitBtn = document.getElementById('submitBtn');
        
        const requirements = {
            length: { element: document.getElementById('req-length'), regex: /.{8,}/ },
            uppercase: { element: document.getElementById('req-uppercase'), regex: /[A-Z]/ },
            lowercase: { element: document.getElementById('req-lowercase'), regex: /[a-z]/ },
            number: { element: document.getElementById('req-number'), regex: /[0-9]/ },
            special: { element: document.getElementById('req-special'), regex: /[^A-Za-z0-9]/ }
        };
        
        function checkPasswordStrength() {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            
            if (password.length === 0) {
                strengthDiv.style.display = 'none';
                submitBtn.disabled = true;
                return;
            }
            
            strengthDiv.style.display = 'block';
            
            let score = 0;
            let metRequirements = 0;
            
            for (const [key, req] of Object.entries(requirements)) {
                if (req.regex.test(password)) {
                    req.element.style.color = '#16a34a';
                    req.element.innerHTML = '✓ ' + req.element.textContent.replace('✓ ', '').replace('✗ ', '');
                    score += 20;
                    metRequirements++;
                } else {
                    req.element.style.color = '#dc2626';
                    req.element.innerHTML = '✗ ' + req.element.textContent.replace('✓ ', '').replace('✗ ', '');
                }
            }
            
            // Additional scoring
            if (password.length >= 12) score += 10;
            
            let strengthLevel = 'weak';
            let strengthClass = 'rgba(239, 68, 68, 0.1)';
            let textColor = '#dc2626';
            
            if (score >= 90) {
                strengthLevel = 'very strong';
                strengthClass = 'rgba(34, 197, 94, 0.1)';
                textColor = '#16a34a';
            } else if (score >= 70) {
                strengthLevel = 'strong';
                strengthClass = 'rgba(34, 197, 94, 0.1)';
                textColor = '#16a34a';
            } else if (score >= 50) {
                strengthLevel = 'medium';
                strengthClass = 'rgba(245, 158, 11, 0.1)';
                textColor = '#d97706';
            }
            
            strengthDiv.style.background = strengthClass;
            strengthDiv.style.color = textColor;
            strengthDiv.style.border = `1px solid ${strengthClass}`;
            strengthText.textContent = `Password strength: ${strengthLevel} (${score}/100)`;
            
            // Enable submit button only if password is strong enough and passwords match
            const passwordsMatch = password === confirmPassword && confirmPassword.length > 0;
            const strongEnough = score >= 80;
            
            submitBtn.disabled = !(strongEnough && passwordsMatch);
        }
        
        passwordInput.addEventListener('input', checkPasswordStrength);
        confirmInput.addEventListener('input', checkPasswordStrength);
        
        // Form submission validation
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please check your passwords and try again.');
                return;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                return;
            }
        });
    </script>
    <script src="../script.js"></script>
</body>
</html>