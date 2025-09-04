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
                
                // Wrap content in mobile-responsive template
                $email_html = "<!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>$subject</title>
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
                
                if (mail($subscriber['email'], $subject, $email_html, $headers)) {
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
            <div id="newsletter-form">
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

<h3>🏌️ Featured Course of the Week</h3>
<p>This week we're highlighting <strong>Bear Trace at Harrison Bay</strong> - a stunning Jack Nicklaus Signature Design with breathtaking lakefront views on Chickamauga Lake.</p>
<p><a href="https://tennesseegolfcourses.com/courses/bear-trace-harrison-bay" class="button">View Course Details</a></p>

<h3>📰 Latest Golf News</h3>
<ul>
    <li>Tennessee PGA announces 2025 tournament schedule</li>
    <li>New course renovations at TPC Southwind</li>
    <li>Local junior golfers excel in state championships</li>
</ul>
<p><a href="https://tennesseegolfcourses.com/news" class="button">Read All News</a></p>

<h3>🎯 Pro Tip of the Week</h3>
<p>Improve your short game by practicing 40-60 yard wedge shots. These "scoring zone" shots can save you 3-5 strokes per round!</p>

<h3>🏆 Upcoming Events</h3>
<ul>
    <li><strong>March 15-17:</strong> Tennessee State Amateur Qualifier</li>
    <li><strong>March 22:</strong> Spring Scramble at Gaylord Springs</li>
</ul>

<p>Have a great week on the course!</p>
<p><em>- The Tennessee Golf Courses Team</em></p>
                    </textarea>
                </div>
                
                <button type="button" id="send-newsletter-btn" class="btn" onclick="sendNewsletter()">
                    Send to <?php echo $total_active ?? 0; ?> Subscribers
                </button>
                
                <div id="sending-progress" style="display: none; margin-top: 20px;">
                    <div style="background: #f0f9ff; padding: 20px; border-radius: 8px; border-left: 4px solid #059669;">
                        <h3 style="color: #059669; margin-top: 0;">📧 Sending Newsletter...</h3>
                        <div id="progress-bar" style="width: 100%; height: 20px; background: #e5e5e5; border-radius: 10px; overflow: hidden;">
                            <div id="progress-fill" style="height: 100%; background: linear-gradient(90deg, #059669, #10b981); width: 0%; transition: width 0.3s ease;"></div>
                        </div>
                        <div id="progress-text" style="margin-top: 10px; font-weight: 600;">Starting...</div>
                        <div id="progress-details" style="margin-top: 8px; color: #666; font-size: 14px;">Preparing to send emails...</div>
                    </div>
                </div>
                
                <div id="sending-complete" style="display: none; margin-top: 20px;">
                    <div style="background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                        <h3 style="color: #28a745; margin-top: 0;">✅ Newsletter Sent Successfully!</h3>
                        <div id="complete-message"></div>
                    </div>
                </div>
            </div>
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

    <script>
    function sendNewsletter() {
        const subject = document.getElementById('subject').value.trim();
        const content = document.getElementById('content').value.trim();
        
        if (!subject || !content) {
            alert('Please fill in both subject and content fields.');
            return;
        }
        
        if (!confirm('Send newsletter to all active subscribers? This will send emails in batches to avoid timeouts.')) {
            return;
        }
        
        // Hide form and show progress
        document.getElementById('newsletter-form').style.display = 'none';
        document.getElementById('sending-progress').style.display = 'block';
        document.getElementById('sending-complete').style.display = 'none';
        
        // Start the batch sending process
        startBatchSending(subject, content);
    }
    
    function startBatchSending(subject, content) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/newsletter-send-batch.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        handleBatchResponse(response, subject, content);
                    } catch (e) {
                        showError('Error parsing server response: ' + e.message);
                    }
                } else {
                    showError('Server error: HTTP ' + xhr.status);
                }
            }
        };
        
        const params = 'subject=' + encodeURIComponent(subject) + 
                      '&content=' + encodeURIComponent(content);
        xhr.send(params);
        
        updateProgress(0, 'Creating campaign...', 'Initializing newsletter send process...');
    }
    
    function handleBatchResponse(response, subject, content) {
        if (response.error) {
            showError(response.error);
            return;
        }
        
        if (response.status === 'started') {
            // Campaign created, continue with first batch
            continueBatchSending(response.campaign_id, response.offset, response.total);
        } else if (response.status === 'sending') {
            // Continue with next batch
            const sent = response.sent || 0;
            const total = getTotalFromProgress() || 100; // Fallback
            const percentage = Math.round((response.offset / total) * 100);
            
            updateProgress(percentage, `Sent ${response.offset} emails...`, `Successfully sent ${sent} emails in this batch`);
            
            // Continue with next batch
            setTimeout(() => {
                continueBatchSending(response.campaign_id, response.offset, total);
            }, 500); // Small delay between batches
            
        } else if (response.status === 'complete') {
            // All done!
            showComplete(response.message);
        }
    }
    
    function continueBatchSending(campaignId, offset, total) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/newsletter-send-batch.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        handleBatchResponse(response);
                    } catch (e) {
                        showError('Error parsing server response: ' + e.message);
                    }
                } else {
                    showError('Server error: HTTP ' + xhr.status);
                }
            }
        };
        
        const params = 'campaign_id=' + campaignId + '&offset=' + offset;
        xhr.send(params);
        
        const percentage = Math.round((offset / total) * 100);
        updateProgress(percentage, `Sending batch (${offset}/${total})...`, 'Processing next batch of subscribers...');
    }
    
    function updateProgress(percentage, text, details) {
        document.getElementById('progress-fill').style.width = percentage + '%';
        document.getElementById('progress-text').textContent = text;
        document.getElementById('progress-details').textContent = details;
    }
    
    function showComplete(message) {
        document.getElementById('sending-progress').style.display = 'none';
        document.getElementById('sending-complete').style.display = 'block';
        document.getElementById('complete-message').textContent = message;
        
        // Reset form for next use
        setTimeout(() => {
            document.getElementById('newsletter-form').style.display = 'block';
            document.getElementById('sending-complete').style.display = 'none';
            document.getElementById('subject').value = '';
            document.getElementById('content').value = document.getElementById('content').defaultValue;
        }, 5000);
    }
    
    function showError(errorMessage) {
        document.getElementById('sending-progress').style.display = 'none';
        document.getElementById('newsletter-form').style.display = 'block';
        alert('Error sending newsletter: ' + errorMessage);
    }
    
    function getTotalFromProgress() {
        const text = document.getElementById('progress-text').textContent;
        const match = text.match(/\/(\d+)/);
        return match ? parseInt(match[1]) : null;
    }
    </script>
</body>
</html>