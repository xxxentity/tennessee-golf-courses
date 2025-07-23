<?php
// Simple migration script - DELETE AFTER USE
require_once 'config/database.php';

// Simple security
if (!isset($_GET['go']) || $_GET['go'] !== 'yes') {
    die('Add ?go=yes to run migration');
}

echo "<h1>Database Migration</h1>";

try {
    // Check if columns exist
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'email_verification_token'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Migration already completed!</p>";
    } else {
        // Run migration
        $pdo->exec("ALTER TABLE users ADD COLUMN email_verification_token VARCHAR(255) NULL");
        $pdo->exec("ALTER TABLE users ADD COLUMN email_verified BOOLEAN DEFAULT 0");
        $pdo->exec("ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $pdo->exec("ALTER TABLE users ADD INDEX idx_email_token (email_verification_token)");
        $pdo->exec("ALTER TABLE users ADD INDEX idx_email_verified (email_verified)");
        echo "<p>✅ Migration completed!</p>";
    }
    
    // Update existing users
    $stmt = $pdo->prepare("UPDATE users SET email_verified = 1 WHERE email_verified = 0 OR email_verified IS NULL");
    $stmt->execute();
    $affected = $stmt->rowCount();
    
    echo "<p>✅ Updated $affected existing users to verified status</p>";
    echo "<p>🔒 Bot protection is now active!</p>";
    echo "<p><strong>DELETE THIS FILE NOW!</strong></p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>