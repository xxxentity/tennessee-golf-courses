<?php
require_once '../config/database.php';
require_once '../includes/rate-limiter.php';

// Rate limiting check
$rateLimiter = new RateLimiter($pdo);
if (!$rateLimiter->isAllowed('registration', 3, 1)) {
    $remaining = $rateLimiter->getRemainingAttempts('registration', 3, 1);
    header('Location: register.php?error=' . urlencode('Too many registration attempts. Please try again later.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Get form data
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validation
$errors = [];

if (empty($username) || strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address";
}

if (empty($first_name) || empty($last_name)) {
    $errors[] = "First and last name are required";
}

if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters long";
}

if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match";
}

// Check if username or email already exists
if (empty($errors)) {
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            $errors[] = "Username or email already exists";
        }
    } catch (PDOException $e) {
        $errors[] = "Database error occurred";
    }
}

// If there are errors, redirect back with error message
if (!empty($errors)) {
    $error_message = implode(". ", $errors);
    header('Location: register.php?error=' . urlencode($error_message));
    exit;
}

// Hash password and insert user
try {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Generate email verification token
    $email_verification_token = bin2hex(random_bytes(32));
    
    $stmt = $pdo->prepare("INSERT INTO users (username, email, first_name, last_name, password_hash, email_verification_token, email_verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->execute([$username, $email, $first_name, $last_name, $password_hash, $email_verification_token]);
    $user_id = $pdo->lastInsertId();
    
    // Auto-subscribe to newsletter
    try {
        // Generate unsubscribe token for newsletter
        $newsletter_token = bin2hex(random_bytes(32));
        
        // Check if already subscribed to newsletter
        $stmt = $pdo->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $stmt->execute([$email]);
        $existing_newsletter = $stmt->fetch();
        
        if (!$existing_newsletter) {
            // Subscribe to newsletter automatically
            $stmt = $pdo->prepare("
                INSERT INTO newsletter_subscribers (email, first_name, unsubscribe_token, ip_address, user_agent, confirmed) 
                VALUES (?, ?, ?, ?, ?, 1)
            ");
            
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt->execute([$email, $first_name, $newsletter_token, $ip_address, $user_agent]);
            
            // Send email verification email
            $subject = "Verify Your Email - Tennessee Golf Courses";
            $message = "
            <html>
            <head><title>Verify Your Email - Tennessee Golf Courses</title></head>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <div style='text-align: center; margin-bottom: 30px;'>
                        <h1 style='color: #064E3B;'>Welcome to Tennessee Golf Courses, " . htmlspecialchars($first_name) . "!</h1>
                    </div>
                    
                    <p>Thank you for creating an account! Please verify your email address to complete your registration and access all features.</p>
                    
                    <div style='text-align: center; margin: 30px 0; padding: 20px; background: #f0f9ff; border-radius: 8px;'>
                        <a href='https://tennesseegolfcourses.com/auth/verify-email?token=" . $email_verification_token . "' style='background: #064E3B; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: 600;'>
                            Verify My Email Address
                        </a>
                    </div>
                    
                    <p><strong>Important:</strong> You must verify your email to:</p>
                    <ul>
                        <li>Login to your account</li>
                        <li>Post comments and reviews</li>
                        <li>Save favorite golf courses</li>
                        <li>Access all website features</li>
                    </ul>
                    
                    <p>Your account has been created with these benefits:</p>
                    
                    <div style='background: #f0f9ff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #064E3B;'>
                        <h3 style='color: #064E3B; margin-top: 0;'>✅ Your Account Benefits:</h3>
                        <ul style='margin: 0; padding-left: 20px;'>
                            <li><strong>Save Favorites:</strong> Bookmark your favorite golf courses</li>
                            <li><strong>Write Reviews:</strong> Share your course experiences</li>
                            <li><strong>Track Visits:</strong> Keep a record of courses you've played</li>
                            <li><strong>Personalized Recommendations:</strong> Get course suggestions based on your preferences</li>
                            <li><strong>Newsletter Updates:</strong> Weekly golf news and course spotlights</li>
                        </ul>
                    </div>
                    
                    <p><strong>Plus, we've automatically subscribed you to our newsletter</strong> so you'll receive:</p>
                    <ul>
                        <li>Weekly updates on new golf courses</li>
                        <li>Exclusive deals and discounts</li>
                        <li>Tournament news and results</li>
                        <li>Tips from Tennessee golf pros</li>
                    </ul>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='https://tennesseegolfcourses.com/user/profile' style='background: #064E3B; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin-right: 16px;'>View My Profile</a>
                        <a href='https://tennesseegolfcourses.com/courses' style='background: #EA580C; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;'>Explore Courses</a>
                    </div>
                    
                    <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
                    <p style='font-size: 12px; color: #666;'>
                        You can manage your newsletter subscription in your <a href='https://tennesseegolfcourses.com/user/profile'>profile settings</a> or 
                        <a href='https://tennesseegolfcourses.com/newsletter-unsubscribe?token=" . $newsletter_token . "'>unsubscribe here</a>.
                    </p>
                </div>
            </body>
            </html>
            ";
            
            // Email headers
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Tennessee Golf Courses <newsletter@tennesseegolfcourses.com>" . "\r\n";
            $headers .= "Reply-To: info@tennesseegolfcourses.com" . "\r\n";
            
            // Send welcome email
            mail($email, $subject, $message, $headers);
        }
    } catch (Exception $e) {
        // Log newsletter subscription error but don't fail registration
        error_log("Auto newsletter subscription failed for $email: " . $e->getMessage());
    }
    
    // Success - redirect to login with verification message
    header('Location: login.php?success=' . urlencode('Account created! Please check your email to verify your account before logging in.'));
    exit;
    
} catch (PDOException $e) {
    header('Location: register.php?error=' . urlencode('Registration failed. Please try again.'));
    exit;
}
?>