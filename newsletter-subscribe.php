<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING) ?? '';

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id, is_active FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        if ($existing['is_active']) {
            echo json_encode(['success' => false, 'message' => 'You are already subscribed to our newsletter!']);
        } else {
            // Reactivate subscription
            $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET is_active = 1, subscribed_at = CURRENT_TIMESTAMP WHERE email = ?");
            $stmt->execute([$email]);
            echo json_encode(['success' => true, 'message' => 'Welcome back! Your subscription has been reactivated.']);
        }
        exit;
    }
    
    // Generate unsubscribe token
    $unsubscribe_token = bin2hex(random_bytes(32));
    
    // Insert new subscriber
    $stmt = $pdo->prepare("
        INSERT INTO newsletter_subscribers (email, first_name, unsubscribe_token, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $stmt->execute([$email, $first_name, $unsubscribe_token, $ip_address, $user_agent]);
    
    // Send welcome email
    $subject = "Welcome to Tennessee Golf Courses Newsletter!";
    $message = "
    <html>
    <head><title>Welcome to Tennessee Golf Courses</title></head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #064E3B;'>Welcome to Tennessee Golf Courses!</h1>
            </div>
            
            <p>Hello" . ($first_name ? " " . htmlspecialchars($first_name) : "") . ",</p>
            
            <p>Thank you for subscribing to our newsletter! You'll now receive:</p>
            <ul>
                <li>Weekly updates on new golf courses</li>
                <li>Exclusive deals and discounts</li>
                <li>Tournament news and results</li>
                <li>Tips and insights from Tennessee golf pros</li>
            </ul>
            
            <p>We're excited to help you discover the best golf experiences Tennessee has to offer!</p>
            
            <div style='text-align: center; margin: 30px 0;'>
                <a href='https://tennesseegolfcourses.com' style='background: #064E3B; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;'>Explore Golf Courses</a>
            </div>
            
            <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
            <p style='font-size: 12px; color: #666;'>
                You can unsubscribe at any time by <a href='https://tennesseegolfcourses.com/newsletter-unsubscribe?token=" . $unsubscribe_token . "'>clicking here</a>.
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
    
    // Send email
    $email_sent = mail($email, $subject, $message, $headers);
    
    if ($email_sent) {
        echo json_encode(['success' => true, 'message' => 'Thank you for subscribing! Check your email for a welcome message.']);
    } else {
        echo json_encode(['success' => true, 'message' => 'Successfully subscribed! Welcome to our newsletter.']);
    }
    
} catch (PDOException $e) {
    error_log("Newsletter subscription error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'There was an error processing your subscription. Please try again.']);
}
?>