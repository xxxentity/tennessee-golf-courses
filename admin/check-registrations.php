<?php
session_start();
require_once '../config/database.php';

// Admin authentication check
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /admin/login');
    exit;
}

echo "<h2>🤖 Registration Analysis & Bot Detection</h2>";

try {
    // Recent registrations analysis
    echo "<h3>Recent User Registrations (Last 24 Hours)</h3>";
    $stmt = $pdo->prepare("
        SELECT id, username, email, first_name, last_name, created_at,
               INET_NTOA(INET_ATON(SUBSTRING_INDEX(SUBSTRING_INDEX(ip_address, ',', 1), ' ', -1))) as clean_ip
        FROM users 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    $recent_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($recent_users)) {
        echo "<p>✅ No registrations in the last 24 hours</p>";
    } else {
        echo "<p>📊 <strong>" . count($recent_users) . "</strong> registrations in last 24 hours</p>";
        
        // Analyze patterns
        $suspicious_patterns = [];
        $ip_counts = [];
        $email_domains = [];
        
        foreach ($recent_users as $user) {
            // IP analysis
            $ip = $user['clean_ip'] ?? 'unknown';
            $ip_counts[$ip] = ($ip_counts[$ip] ?? 0) + 1;
            
            // Email domain analysis
            $email_domain = substr(strrchr($user['email'], '@'), 1);
            $email_domains[$email_domain] = ($email_domains[$email_domain] ?? 0) + 1;
            
            // Check for suspicious patterns
            if (strlen($user['username']) > 20 || preg_match('/\d{3,}/', $user['username'])) {
                $suspicious_patterns[] = "Suspicious username: " . $user['username'];
            }
            
            if (empty($user['first_name']) || empty($user['last_name'])) {
                $suspicious_patterns[] = "Empty name fields: " . $user['email'];
            }
        }
        
        // Show suspicious IPs (more than 3 registrations)
        echo "<h4>🚨 Suspicious IP Addresses:</h4>";
        $found_suspicious = false;
        foreach ($ip_counts as $ip => $count) {
            if ($count > 3) {
                echo "<p style='color: red;'>❌ IP <strong>$ip</strong>: $count registrations</p>";
                $found_suspicious = true;
            }
        }
        if (!$found_suspicious) {
            echo "<p style='color: green;'>✅ No suspicious IP patterns detected</p>";
        }
        
        // Show suspicious email domains
        echo "<h4>📧 Email Domain Analysis:</h4>";
        arsort($email_domains);
        foreach (array_slice($email_domains, 0, 10) as $domain => $count) {
            $color = $count > 5 ? 'red' : ($count > 2 ? 'orange' : 'green');
            echo "<p style='color: $color;'>$domain: $count registrations</p>";
        }
        
        // Show recent registrations table
        echo "<h4>📋 Recent Registrations:</h4>";
        echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
        echo "<tr style='background: #f5f5f5;'>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Time</th>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Username</th>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Email</th>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Name</th>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>IP</th>";
        echo "</tr>";
        
        foreach ($recent_users as $user) {
            $suspicious = (strlen($user['username']) > 20 || 
                          preg_match('/\d{3,}/', $user['username']) || 
                          empty($user['first_name']));
            $row_color = $suspicious ? '#fee2e2' : '#ffffff';
            
            echo "<tr style='background: $row_color;'>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . date('M j, H:i', strtotime($user['created_at'])) . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($user['clean_ip'] ?? 'unknown') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Show recommendations
        if (!empty($suspicious_patterns) || $found_suspicious) {
            echo "<div style='background: #fee2e2; border: 1px solid #dc2626; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h4>⚠️ Recommendations:</h4>";
            echo "<ul>";
            echo "<li>Add CAPTCHA to registration form</li>";
            echo "<li>Add rate limiting (max 3 registrations per IP per hour)</li>";
            echo "<li>Add email verification requirement</li>";
            echo "<li>Consider cleaning up suspicious accounts</li>";
            echo "</ul>";
            echo "<p><strong>Want me to add these protections?</strong></p>";
            echo "</div>";
        }
    }
    
    // Newsletter subscribers analysis
    echo "<hr><h3>Newsletter Subscribers (Last 24 Hours)</h3>";
    $stmt = $pdo->prepare("
        SELECT email, ip_address, subscribed_at
        FROM newsletter_subscribers 
        WHERE subscribed_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ORDER BY subscribed_at DESC
    ");
    $stmt->execute();
    $recent_subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>📊 <strong>" . count($recent_subscribers) . "</strong> newsletter subscriptions in last 24 hours</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; line-height: 1.6; }
h2, h3, h4 { color: #064E3B; }
table { font-size: 14px; }
</style>

<p><a href="/admin/newsletter">← Back to Admin Panel</a></p>