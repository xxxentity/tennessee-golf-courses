<?php
session_start();
require_once '../config/database.php';

// Admin authentication check
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Set JSON header
header('Content-Type: application/json');

// Increase time limit for email sending
set_time_limit(300);

// Get parameters
$campaign_id = $_POST['campaign_id'] ?? 0;
$batch_size = 10; // Send 10 emails per batch to avoid timeout
$offset = intval($_POST['offset'] ?? 0);

if (!$campaign_id) {
    // Create new campaign
    $subject = $_POST['subject'] ?? '';
    $content = $_POST['content'] ?? '';
    
    if (!$subject || !$content) {
        echo json_encode(['error' => 'Missing subject or content']);
        exit;
    }
    
    // Get total count of active subscribers
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM newsletter_subscribers WHERE is_active = 1");
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Create campaign record
    $stmt = $pdo->prepare("INSERT INTO newsletter_campaigns (subject, content, total_sent, created_by, status) VALUES (?, ?, ?, ?, 'sending')");
    $stmt->execute([$subject, $content, 0, $_SESSION['first_name'] ?? 'Admin']);
    $campaign_id = $pdo->lastInsertId();
    
    echo json_encode([
        'campaign_id' => $campaign_id,
        'total' => $total,
        'offset' => 0,
        'sent' => 0,
        'status' => 'started'
    ]);
    exit;
}

// Get campaign details
$stmt = $pdo->prepare("SELECT subject, content FROM newsletter_campaigns WHERE id = ?");
$stmt->execute([$campaign_id]);
$campaign = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$campaign) {
    echo json_encode(['error' => 'Campaign not found']);
    exit;
}

// Get batch of subscribers
$stmt = $pdo->prepare("SELECT email, first_name FROM newsletter_subscribers WHERE is_active = 1 LIMIT ? OFFSET ?");
$stmt->execute([$batch_size, $offset]);
$subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($subscribers)) {
    // No more subscribers, mark campaign as complete
    $stmt = $pdo->prepare("UPDATE newsletter_campaigns SET status = 'sent' WHERE id = ?");
    $stmt->execute([$campaign_id]);
    
    echo json_encode([
        'status' => 'complete',
        'message' => 'All emails sent successfully!'
    ]);
    exit;
}

// Email headers
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: Tennessee Golf Courses <newsletter@tennesseegolfcourses.com>" . "\r\n";
$headers .= "Reply-To: info@tennesseegolfcourses.com" . "\r\n";

$sent_count = 0;

foreach ($subscribers as $subscriber) {
    $personalized_content = str_replace(
        ['{{first_name}}', '{{email}}'],
        [$subscriber['first_name'] ?: 'Golf Enthusiast', $subscriber['email']],
        $campaign['content']
    );
    
    // Wrap content in mobile-responsive template
    $email_html = "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>{$campaign['subject']}</title>
        <style>
            body { margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .email-container { max-width: 600px; margin: 0 auto; }
            .email-header { background: linear-gradient(135deg, #064e3b, #059669); padding: 20px; text-align: center; }
            .email-header h1 { color: white; margin: 0; font-size: 24px; }
            .email-content { padding: 20px; background: white; }
            .email-footer { background: #f8f8f8; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            img { max-width: 100%; height: auto; }
            h2 { color: #064e3b; }
            h3 { color: #059669; }
            a { color: #059669; }
            .button { display: inline-block; padding: 12px 24px; background: #064e3b; color: white !important; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
            @media only screen and (max-width: 600px) {
                .email-container { width: 100% !important; }
                .email-content { padding: 15px !important; }
                h1 { font-size: 20px !important; }
                h2 { font-size: 18px !important; }
                h3 { font-size: 16px !important; }
                .button { display: block !important; width: 90% !important; margin: 10px auto !important; text-align: center !important; }
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                <h1>Tennessee Golf Courses</h1>
            </div>
            <div class='email-content'>
                $personalized_content
            </div>
            <div class='email-footer'>
                <p>© " . date('Y') . " Tennessee Golf Courses | <a href='https://tennesseegolfcourses.com'>Visit Our Website</a></p>
                <p><a href='https://tennesseegolfcourses.com/unsubscribe?email=" . urlencode($subscriber['email']) . "'>Unsubscribe</a> | <a href='https://tennesseegolfcourses.com/profile'>Update Preferences</a></p>
            </div>
        </div>
    </body>
    </html>";
    
    if (@mail($subscriber['email'], $campaign['subject'], $email_html, $headers)) {
        $sent_count++;
    }
    
    // Small delay to prevent server overload
    usleep(100000); // 0.1 second
}

// Update campaign sent count
$stmt = $pdo->prepare("UPDATE newsletter_campaigns SET total_sent = total_sent + ? WHERE id = ?");
$stmt->execute([$sent_count, $campaign_id]);

// Return progress
echo json_encode([
    'status' => 'sending',
    'offset' => $offset + $batch_size,
    'sent' => $sent_count,
    'campaign_id' => $campaign_id
]);
?>