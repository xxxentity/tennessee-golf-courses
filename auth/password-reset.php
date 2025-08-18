<?php
require_once '../includes/init.php';
require_once '../includes/csrf.php';


// If user is already logged in, redirect to homepage
if ($is_logged_in) {
    header('Location: /');
    exit;
}

$error_message = $_GET['error'] ?? '';
$success_message = $_GET['success'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .reset-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 2rem 1rem;
        }
        
        .reset-card {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        
        .reset-header {
            margin-bottom: 2rem;
        }
        
        .reset-header h1 {
            color: #2c5234;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2c5234;
        }
        
        .submit-btn {
            width: 100%;
            background: #2c5234;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .submit-btn:hover {
            background: #1f3a26;
        }
        
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid #fecaca;
        }
        
        .success-message {
            background: #dcfce7;
            color: #16a34a;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid #bbf7d0;
        }
        
        .back-link {
            color: #2c5234;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <h1>Reset Password</h1>
                <p>Enter your email address and we'll send you a link to reset your password.</p>
            </div>
            
            <?php if ($error_message): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <form action="/password-reset-process" method="POST">
                <?php echo CSRFProtection::getTokenField(); ?>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Enter your email address">
                </div>
                
                <button type="submit" class="submit-btn">Send Reset Link</button>
            </form>
            
            <p>
                <a href="/login" class="back-link">← Back to Login</a>
            </p>
        </div>
    </div>
</body>
</html>