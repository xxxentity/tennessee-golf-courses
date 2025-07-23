<?php
session_start();
require_once '../config/database.php';

// Admin authentication check
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /admin/login');
    exit;
}

// Handle form submissions
if ($_POST['action'] ?? '' === 'send_newsletter') {
    $subject = $_POST['subject'] ?? '';
    $content = $_POST['content'] ?? '';
    
    if ($subject && $content) {
        try {
            // Get active subscribers
            $stmt = $pdo->prepare("SELECT email, first_name FROM newsletter_subscribers WHERE is_active = 1");
            $stmt->execute();
            $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $sent_count = 0;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Tennessee Golf Courses <newsletter@tennesseegolfcourses.com>" . "\r\n";
            
            // Create campaign record
            $stmt = $pdo->prepare("INSERT INTO newsletter_campaigns (subject, content, total_sent, created_by, status) VALUES (?, ?, ?, ?, 'sending')");
            $stmt->execute([$subject, $content, count($subscribers), $_SESSION['first_name'] ?? 'Admin']);
            $campaign_id = $pdo->lastInsertId();
            
            foreach ($subscribers as $subscriber) {
                $personalized_content = str_replace(
                    ['{{first_name}}', '{{email}}'],
                    [$subscriber['first_name'] ?: 'Golf Enthusiast', $subscriber['email']],
                    $content
                );
                
                if (mail($subscriber['email'], $subject, $personalized_content, $headers)) {
                    $sent_count++;
                }
                
                // Prevent timeout and server overload
                usleep(100000); // 0.1 second delay between emails
            }
            
            // Update campaign status
            $stmt = $pdo->prepare("UPDATE newsletter_campaigns SET status = 'sent', total_sent = ? WHERE id = ?");
            $stmt->execute([$sent_count, $campaign_id]);
            
            $success_message = "Newsletter sent to $sent_count subscribers!";
        } catch (Exception $e) {
            $error_message = "Error sending newsletter: " . $e->getMessage();
        }
    }
}

// Get subscriber stats
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_active FROM newsletter_subscribers WHERE is_active = 1");
    $stmt->execute();
    $total_active = $stmt->fetch(PDO::FETCH_ASSOC)['total_active'];
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_subscribers FROM newsletter_subscribers");
    $stmt->execute();
    $total_subscribers = $stmt->fetch(PDO::FETCH_ASSOC)['total_subscribers'];
    
    // Recent subscribers
    $stmt = $pdo->prepare("SELECT email, first_name, subscribed_at FROM newsletter_subscribers WHERE is_active = 1 ORDER BY subscribed_at DESC LIMIT 10");
    $stmt->execute();
    $recent_subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent campaigns
    $stmt = $pdo->prepare("SELECT * FROM newsletter_campaigns ORDER BY sent_at DESC LIMIT 5");
    $stmt->execute();
    $recent_campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Admin - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        /* Hide weather bar on admin pages */
        .weather-bar { display: none !important; }
        
        /* Make header scroll with page and position normally */
        .header { 
            position: relative !important; 
            top: 0 !important; 
            margin-top: 0 !important;
        }
        
        body { 
            background: var(--bg-light); 
            min-height: 100vh; 
            padding-top: 0 !important; 
        }
        
        .admin-container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        .admin-header { background: var(--bg-white); padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: var(--shadow-light); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px; }
        .stat-card { background: var(--bg-white); padding: 20px; border-radius: 12px; text-align: center; box-shadow: var(--shadow-light); }
        .stat-number { font-size: 2rem; font-weight: 700; color: var(--primary-color); }
        .stat-label { color: var(--text-gray); margin-top: 8px; }
        .admin-section { background: var(--bg-white); padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: var(--shadow-light); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; }
        .form-group textarea { height: 300px; resize: vertical; }
        .btn { background: var(--primary-color); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: var(--secondary-color); }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .subscribers-list { max-height: 400px; overflow-y: auto; }
        .subscriber-item { padding: 12px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; }
        .campaign-item { padding: 12px; border-bottom: 1px solid #eee; }
        .campaign-subject { font-weight: 600; color: var(--primary-color); }
        .campaign-meta { font-size: 14px; color: var(--text-gray); margin-top: 4px; }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>
    
    <div class="admin-container">
        <div class="admin-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Newsletter Administration</h1>
                    <p>Manage your newsletter subscribers and send campaigns</p>
                </div>
                <div>
                    <span style="color: var(--text-gray); margin-right: 16px;">
                        Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?>
                    </span>
                    <a href="/admin/change-password" style="background: #059669; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; margin-right: 8px;">
                        Change Password
                    </a>
                    <a href="/admin/logout" style="background: #dc2626; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                        Logout
                    </a>
                </div>
            </div>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($total_active ?? 0); ?></div>
                <div class="stat-label">Active Subscribers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($total_subscribers ?? 0); ?></div>
                <div class="stat-label">Total Subscribers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($recent_campaigns ?? []); ?></div>
                <div class="stat-label">Campaigns Sent</div>
            </div>
        </div>
        
        <div class="admin-section">
            <h2>Send Newsletter</h2>
            <form method="POST">
                <input type="hidden" name="action" value="send_newsletter">
                
                <div class="form-group">
                    <label for="subject">Subject Line</label>
                    <input type="text" id="subject" name="subject" required placeholder="e.g., Weekly Golf Course Updates">
                </div>
                
                <div class="form-group">
                    <label for="content">Newsletter Content (HTML)</label>
                    <textarea id="content" name="content" required placeholder="Use {{first_name}} for personalization. HTML formatting supported.">
<h2>This Week in Tennessee Golf</h2>
<p>Hello {{first_name}},</p>

<p>Here's what's happening in Tennessee golf this week:</p>

<h3>🏌️ Featured Course</h3>
<p>Discover Bear Trace at Harrison Bay - Jack Nicklaus design with stunning lakefront views.</p>

<h3>📰 Latest News</h3>
<p>Tournament updates and local golf news...</p>

<h3>💡 Pro Tip</h3>
<p>This week's tip from our golf professionals...</p>

<p>Happy golfing!</p>
<p>The Tennessee Golf Courses Team</p>
                    </textarea>
                </div>
                
                <button type="submit" class="btn" onclick="return confirm('Send newsletter to all active subscribers?')">
                    Send to <?php echo $total_active ?? 0; ?> Subscribers
                </button>
            </form>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="admin-section">
                <h2>Recent Subscribers</h2>
                <div class="subscribers-list">
                    <?php if (!empty($recent_subscribers)): ?>
                        <?php foreach ($recent_subscribers as $subscriber): ?>
                            <div class="subscriber-item">
                                <div>
                                    <strong><?php echo htmlspecialchars($subscriber['email']); ?></strong>
                                    <?php if ($subscriber['first_name']): ?>
                                        <br><small><?php echo htmlspecialchars($subscriber['first_name']); ?></small>
                                    <?php endif; ?>
                                </div>
                                <small><?php echo date('M j, Y', strtotime($subscriber['subscribed_at'])); ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No subscribers yet.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="admin-section">
                <h2>Recent Campaigns</h2>
                <div>
                    <?php if (!empty($recent_campaigns)): ?>
                        <?php foreach ($recent_campaigns as $campaign): ?>
                            <div class="campaign-item">
                                <div class="campaign-subject"><?php echo htmlspecialchars($campaign['subject']); ?></div>
                                <div class="campaign-meta">
                                    Sent to <?php echo $campaign['total_sent']; ?> subscribers • 
                                    <?php echo date('M j, Y g:i A', strtotime($campaign['sent_at'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No campaigns sent yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>