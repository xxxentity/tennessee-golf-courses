<?php
// Simple utility to clear rate limits for testing
require_once '../includes/session-security.php';
require_once '../includes/forum-rate-limit.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid
}

// Check if user is logged in
$is_logged_in = SecureSession::isLoggedIn();
$current_user_id = $is_logged_in ? SecureSession::get('user_id') : null;

if ($is_logged_in) {
    // Clear rate limits
    ForumRateLimit::clearRateLimits($current_user_id);
    $message = "Rate limits cleared! You can now post immediately.";
} else {
    $message = "Please log in first.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear Rate Limits - Forum</title>
    <link rel="stylesheet" href="/styles.css">
    <style>
        .utility-page {
            padding: 200px 20px;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }
        .message {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .btn {
            background: var(--primary-color);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin: 8px;
        }
    </style>
</head>
<body>
    <div class="utility-page">
        <h1>Rate Limit Utility</h1>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <p>This page clears your forum posting rate limits for testing purposes.</p>
        <a href="/forum" class="btn">Back to Forum</a>
        <a href="/forum/new-topic" class="btn">Create New Topic</a>
    </div>
</body>
</html>