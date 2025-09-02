<?php
// Start session first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../includes/rate-limiter.php';
require_once '../includes/csrf.php';
require_once '../includes/auth-security.php';
require_once '../includes/input-validation.php';

// Validate CSRF token first
$csrf_token = $_POST['csrf_token'] ?? '';

// Debug information (remove after testing)
if (!CSRFProtection::validateToken($csrf_token)) {
    $session_token = $_SESSION['csrf_token'] ?? 'none';
    $token_time = $_SESSION['csrf_token_time'] ?? 'none';
    $current_time = time();
    $session_id = session_id();
    $age = is_numeric($token_time) ? ($current_time - $token_time) : 'unknown';
    
    $debug_info = "CSRF Validation Failed - " .
                  "Session ID: $session_id | " .
                  "Session token: $session_token | " .
                  "Form token: $csrf_token | " .
                  "Token time: $token_time | " .
                  "Current time: $current_time | " .
                  "Token age: $age seconds | " .
                  "Tokens match: " . ($session_token === $csrf_token ? 'yes' : 'no');
    
    error_log($debug_info);
    
    header('Location: /register?error=' . urlencode('Security token expired. Please refresh the page and try again. Debug: Check error log.'));
    exit;
}

// Rate limiting check
$rateLimiter = new RateLimiter($pdo);
if (!$rateLimiter->isAllowed('registration', 3, 1)) {
    $remaining = $rateLimiter->getRemainingAttempts('registration', 3, 1);
    header('Location: /register?error=' . urlencode('Too many registration attempts. Please try again later.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /register');
    exit;
}

// Get and validate form data using enhanced input validation
$formData = [
    'username' => $_POST['username'] ?? '',
    'email' => $_POST['email'] ?? '',
    'first_name' => $_POST['first_name'] ?? '',
    'last_name' => $_POST['last_name'] ?? ''
];

$validationRules = [
    'username' => ['type' => 'username'],
    'email' => ['type' => 'email', 'check_dns' => false],
    'first_name' => ['type' => 'name', 'name_type' => 'first'],
    'last_name' => ['type' => 'name', 'name_type' => 'last']
];

$validation = InputValidator::validateArray($formData, $validationRules);
$errors = [];

// Extract validated data
$username = $validation['data']['username'];
$email = $validation['data']['email'];
$first_name = $validation['data']['first_name'];
$last_name = $validation['data']['last_name'];
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$agree_terms = isset($_POST['agree_terms']) ? $_POST['agree_terms'] : false;
$newsletter_subscribe = isset($_POST['newsletter_subscribe']) ? $_POST['newsletter_subscribe'] : false;

// Collect validation errors
if (!$validation['valid']) {
    foreach ($validation['errors'] as $field => $fieldErrors) {
        $errors = array_merge($errors, $fieldErrors);
    }
}

// Enhanced password strength validation
$passwordValidation = AuthSecurity::validatePasswordStrength($password);
if (!$passwordValidation['valid']) {
    $errors = array_merge($errors, $passwordValidation['feedback']);
}

if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match";
}

if (!$agree_terms) {
    $errors[] = "You must agree to the Terms of Service and Privacy Policy";
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
    header('Location: /register?error=' . urlencode($error_message));
    exit;
}

// Hash password and insert user
try {
    $password_hash = AuthSecurity::hashPassword($password);
    
    // Generate email verification token
    $email_verification_token = AuthSecurity::generateEmailVerificationToken();
    
    $stmt = $pdo->prepare("INSERT INTO users (username, email, first_name, last_name, password_hash, email_verification_token, email_verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->execute([$username, $email, $first_name, $last_name, $password_hash, $email_verification_token]);
    $user_id = $pdo->lastInsertId();
    
    // Subscribe to newsletter if checkbox is checked
    if ($newsletter_subscribe) {
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
                        <a href='https://tennesseegolfcourses.com/verify-email?token=" . $email_verification_token . "' style='background: #064E3B; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: 600;'>
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
                        <a href='https://tennesseegolfcourses.com/profile' style='background: #064E3B; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin-right: 16px;'>View My Profile</a>
                        <a href='https://tennesseegolfcourses.com/courses' style='background: #EA580C; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;'>Explore Courses</a>
                    </div>
                    
                    <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
                    <p style='font-size: 12px; color: #666;'>
                        You can manage your newsletter subscription in your <a href='https://tennesseegolfcourses.com/profile'>profile settings</a> or 
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
            error_log("Newsletter subscription failed for $email: " . $e->getMessage());
        }
    }
    
    // Success - redirect to registration success page
    header('Location: /registration-success');
    exit;
    
} catch (PDOException $e) {
    header('Location: /register?error=' . urlencode('Registration failed. Please try again.'));
    exit;
}
?>