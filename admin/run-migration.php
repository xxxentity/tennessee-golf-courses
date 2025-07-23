<?php
// IMPORTANT: Remove this file after running the migration!
session_start();
require_once '../config/database.php';

// Simple security check
$expected_key = 'run_migration_' . date('Y-m-d');
$provided_key = $_GET['key'] ?? '';

if ($provided_key !== $expected_key) {
    die("Access denied. Expected key: $expected_key");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migration - Tennessee Golf Courses</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: #065f46; background: #d1fae5; padding: 16px; border-radius: 8px; margin: 16px 0; }
        .error { color: #dc2626; background: #fee2e2; padding: 16px; border-radius: 8px; margin: 16px 0; }
        .warning { color: #92400e; background: #fef3c7; padding: 16px; border-radius: 8px; margin: 16px 0; }
        pre { background: #f3f4f6; padding: 16px; border-radius: 8px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Email Verification Database Migration</h1>
    
    <?php
    try {
        echo "<div class='warning'><strong>⚠️ Important:</strong> This will modify your database structure. Make sure you have a backup!</div>";
        
        // Read the SQL file
        $sql_file = '../database/add_email_verification.sql';
        if (!file_exists($sql_file)) {
            throw new Exception("SQL file not found: $sql_file");
        }
        
        $sql = file_get_contents($sql_file);
        echo "<h3>SQL to execute:</h3>";
        echo "<pre>" . htmlspecialchars($sql) . "</pre>";
        
        // Check if columns already exist
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'email_verification_token'");
        $token_exists = $stmt->rowCount() > 0;
        
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'email_verified'");
        $verified_exists = $stmt->rowCount() > 0;
        
        if ($token_exists && $verified_exists) {
            echo "<div class='success'>✅ Email verification columns already exist! No migration needed.</div>";
        } else {
            // Execute the migration
            $pdo->exec($sql);
            echo "<div class='success'>✅ Migration completed successfully!</div>";
            echo "<p>Added columns:</p>";
            echo "<ul>";
            echo "<li><code>email_verification_token</code> (VARCHAR(255))</li>";
            echo "<li><code>email_verified</code> (BOOLEAN, default 0)</li>";
            echo "<li><code>created_at</code> (TIMESTAMP)</li>";
            echo "<li>Performance indexes</li>";
            echo "</ul>";
        }
        
        // Set existing users as verified
        $stmt = $pdo->prepare("UPDATE users SET email_verified = 1 WHERE email_verified = 0 OR email_verified IS NULL");
        $result = $stmt->execute();
        $affected = $stmt->rowCount();
        
        echo "<div class='success'>✅ Updated $affected existing users to verified status</div>";
        
        // Show current user count and verification status
        $stmt = $pdo->query("SELECT COUNT(*) as total, SUM(email_verified) as verified FROM users");
        $stats = $stmt->fetch();
        
        echo "<h3>Current Status:</h3>";
        echo "<ul>";
        echo "<li>Total users: " . $stats['total'] . "</li>";
        echo "<li>Verified users: " . $stats['verified'] . "</li>";
        echo "<li>Unverified users: " . ($stats['total'] - $stats['verified']) . "</li>";
        echo "</ul>";
        
        echo "<div class='success'>";
        echo "<h3>🔒 Bot Protection Now Active:</h3>";
        echo "<ul>";
        echo "<li>New registrations limited to 3 per IP per hour</li>";
        echo "<li>Email verification required for new accounts</li>";
        echo "<li>Existing users can continue logging in normally</li>";
        echo "<li>Rate limiting protects against spam registrations</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<div class='warning'>";
        echo "<h3>⚠️ Important Next Steps:</h3>";
        echo "<ul>";
        echo "<li><strong>Delete this file</strong> after confirming everything works</li>";
        echo "<li>Test the registration process with a new email</li>";
        echo "<li>Verify that existing users can still login</li>";
        echo "<li>Check that email verification emails are being sent</li>";
        echo "</ul>";
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Migration failed: " . htmlspecialchars($e->getMessage()) . "</div>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
    ?>
    
    <hr style="margin: 40px 0;">
    <p><strong>Access URL for this migration:</strong></p>
    <p><code>https://tennesseegolfcourses.com/admin/run-migration.php?key=<?php echo $expected_key; ?></code></p>
    
    <p><em>Remember to delete this file after the migration is complete!</em></p>
</body>
</html>