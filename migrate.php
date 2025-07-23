<?php
// Simple migration script - DELETE AFTER USE
require_once 'config/database.php';

// Simple security
if (!isset($_GET['go']) || $_GET['go'] !== 'yes') {
    die('Add ?go=yes to run migration');
}

echo "<h1>Database Migration</h1>";

try {
    // Check each column individually and add only if missing
    $columns_added = 0;
    
    // Check email_verification_token
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'email_verification_token'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN email_verification_token VARCHAR(255) NULL");
        echo "<p>✅ Added email_verification_token column</p>";
        $columns_added++;
    } else {
        echo "<p>ℹ️ email_verification_token column already exists</p>";
    }
    
    // Check email_verified
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'email_verified'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN email_verified BOOLEAN DEFAULT 0");
        echo "<p>✅ Added email_verified column</p>";
        $columns_added++;
    } else {
        echo "<p>ℹ️ email_verified column already exists</p>";
    }
    
    // Check created_at (skip if exists since it already exists)
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'created_at'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        echo "<p>✅ Added created_at column</p>";
        $columns_added++;
    } else {
        echo "<p>ℹ️ created_at column already exists</p>";
    }
    
    // Add indexes if we added columns
    if ($columns_added > 0) {
        try {
            $pdo->exec("ALTER TABLE users ADD INDEX idx_email_token (email_verification_token)");
            echo "<p>✅ Added email_verification_token index</p>";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                throw $e;
            }
            echo "<p>ℹ️ email_verification_token index already exists</p>";
        }
        
        try {
            $pdo->exec("ALTER TABLE users ADD INDEX idx_email_verified (email_verified)");
            echo "<p>✅ Added email_verified index</p>";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                throw $e;
            }
            echo "<p>ℹ️ email_verified index already exists</p>";
        }
    }
    
    // Update existing users to verified status
    $stmt = $pdo->prepare("UPDATE users SET email_verified = 1 WHERE email_verified = 0 OR email_verified IS NULL");
    $stmt->execute();
    $affected = $stmt->rowCount();
    
    echo "<p>✅ Updated $affected existing users to verified status</p>";
    
    // Show final status
    $stmt = $pdo->query("SELECT COUNT(*) as total, SUM(email_verified) as verified FROM users");
    $stats = $stmt->fetch();
    
    echo "<h3>Final Status:</h3>";
    echo "<ul>";
    echo "<li>Total users: " . $stats['total'] . "</li>";
    echo "<li>Verified users: " . $stats['verified'] . "</li>";
    echo "<li>Unverified users: " . ($stats['total'] - $stats['verified']) . "</li>";
    echo "</ul>";
    
    echo "<p>🔒 <strong>Bot protection is now active!</strong></p>";
    echo "<ul>";
    echo "<li>Rate limiting: Max 3 registrations per IP per hour</li>";
    echo "<li>Email verification required for new accounts</li>";
    echo "<li>Existing users can login normally</li>";
    echo "</ul>";
    
    echo "<p style='color: red; font-weight: bold;'>⚠️ DELETE THIS FILE NOW!</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>