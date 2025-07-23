<?php
require_once 'config/database.php';

echo "Running email verification database migration...\n";

try {
    // Read the SQL file
    $sql = file_get_contents('database/add_email_verification.sql');
    
    // Execute the migration
    $pdo->exec($sql);
    
    echo "✅ Migration completed successfully!\n";
    echo "Email verification columns added to users table:\n";
    echo "- email_verification_token (VARCHAR(255))\n";
    echo "- email_verified (BOOLEAN, default 0)\n";
    echo "- created_at (TIMESTAMP)\n";
    echo "- Indexes added for performance\n";
    
    // Set existing users as verified so they can continue using the site
    $stmt = $pdo->prepare("UPDATE users SET email_verified = 1 WHERE email_verified = 0");
    $result = $stmt->execute();
    $affected = $stmt->rowCount();
    
    echo "\n✅ Updated $affected existing users to verified status\n";
    echo "\nBot protection is now active:\n";
    echo "- New registrations limited to 3 per IP per hour\n";
    echo "- Email verification required for new accounts\n";
    echo "- Existing users can continue logging in normally\n";
    
} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    echo "Error details: " . $e->getTraceAsString() . "\n";
}
?>