<?php
session_start();
require_once 'config/database.php';

$token = $_GET['token'] ?? '';
$message = '';
$success = false;

if ($token) {
    try {
        // Find subscriber by token
        $stmt = $pdo->prepare("SELECT id, email FROM newsletter_subscribers WHERE unsubscribe_token = ? AND is_active = 1");
        $stmt->execute([$token]);
        $subscriber = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($subscriber) {
            // Deactivate subscription
            $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET is_active = 0 WHERE id = ?");
            $stmt->execute([$subscriber['id']]);
            
            $message = "You have been successfully unsubscribed from our newsletter.";
            $success = true;
        } else {
            $message = "Invalid unsubscribe link or you are already unsubscribed.";
        }
    } catch (PDOException $e) {
        error_log("Unsubscribe error: " . $e->getMessage());
        $message = "There was an error processing your request. Please try again.";
    }
} else {
    $message = "Invalid unsubscribe link.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=4">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=4">
    
    <style>
        body {
            background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-white) 100%);
            min-height: 100vh;
        }
        
        .unsubscribe-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            text-align: center;
        }
        
        .unsubscribe-icon {
            font-size: 48px;
            margin-bottom: 24px;
            color: <?php echo $success ? 'var(--success-color)' : 'var(--error-color)'; ?>;
        }
        
        .unsubscribe-message {
            font-size: 18px;
            margin-bottom: 32px;
            color: var(--text-dark);
        }
        
        .back-home {
            display: inline-block;
            background: var(--primary-color);
            color: var(--text-light);
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            transition: var(--transition);
        }
        
        .back-home:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <?php include 'includes/navigation.php'; ?>
    
    <div class="unsubscribe-container">
        <div class="unsubscribe-icon">
            <?php echo $success ? '✅' : '❌'; ?>
        </div>
        
        <div class="unsubscribe-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
        
        <a href="/" class="back-home">Return to Home</a>
    </div>
</body>
</html>