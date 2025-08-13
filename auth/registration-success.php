<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - Tennessee Golf Courses</title>
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
        
        
        .success-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px 40px;
        }
        
        .success-container {
            max-width: 600px;
            width: 100%;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-heavy);
            text-align: center;
            padding: 60px 40px;
            border: 1px solid rgba(6, 78, 59, 0.1);
        }
        
        .success-icon {
            font-size: 72px;
            color: #10b981;
            margin-bottom: 32px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 16px;
        }
        
        .success-subtitle {
            font-size: 1.2rem;
            color: var(--text-gray);
            margin-bottom: 32px;
        }
        
        .success-message {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            position: relative;
        }
        
        
        .success-message h3 {
            color: var(--primary-color);
            margin-bottom: 16px;
            font-size: 1.3rem;
        }
        
        .success-message p {
            color: var(--text-dark);
            line-height: 1.6;
            margin-bottom: 12px;
        }
        
        .checklist {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            text-align: left;
        }
        
        .checklist li {
            padding: 8px 0;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .checklist li::before {
            content: '✅';
            font-size: 16px;
        }
        
        .action-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 32px;
        }
        
        .btn {
            display: inline-block;
            padding: 14px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .btn-secondary {
            background: var(--gold-color);
            color: var(--text-dark);
        }
        
        .btn-secondary:hover {
            background: #c49c2f;
            transform: translateY(-2px);
        }
        
        .email-check {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
        }
        
        .email-check p {
            margin: 0;
            color: #92400e;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .success-page {
                padding: 60px 16px 20px;
            }
            
            .success-container {
                padding: 40px 24px;
            }
            
            .success-title {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <main class="success-page">
        <div class="success-container">
            
            <h1 class="success-title">Welcome to Tennessee Golf Courses!</h1>
            <p class="success-subtitle">Your account has been created successfully</p>
            
            <div class="success-message">
                <h3>Check Your Email to Get Started</h3>
                <p><strong>We've sent a verification email to your inbox.</strong> Please check your email and click the verification link to activate your account.</p>
                
                <ul class="checklist">
                    <li>Account created with your information</li>
                    <li>Automatically subscribed to our newsletter</li>
                    <li>Verification email sent</li>
                    <li>Ready to explore Tennessee golf courses</li>
                </ul>
            </div>
            
            <div class="email-check">
                <p><strong>📬 Don't see the email?</strong> Check your spam/junk folder. Emails are sent from newsletter@tennesseegolfcourses.com</p>
            </div>
            
            <div class="action-buttons">
                <a href="/courses" class="btn btn-primary">
                    <i class="fas fa-golf-ball" style="margin-right: 8px;"></i>
                    Explore Courses
                </a>
                <a href="/" class="btn btn-secondary">
                    <i class="fas fa-home" style="margin-right: 8px;"></i>
                    Back to Home
                </a>
            </div>
            
            <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid var(--border-color);">
                <p style="color: var(--text-gray); font-size: 14px;">
                    <strong>What happens next?</strong><br>
                    After verifying your email, you'll be able to save favorite courses, write reviews, and access exclusive member features.
                </p>
            </div>
        </div>
    </main>

    <script src="../script.js"></script>
</body>
</html>