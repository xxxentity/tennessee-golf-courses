<?php
// Database fix script for profile uploads
require_once 'config/database.php';

echo "<h2>Profile Upload Database Fix</h2>\n";

try {
    // Check if profile_picture column exists
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Current users table columns:</h3>\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
    if (!in_array('profile_picture', $columns)) {
        echo "<h3>Adding profile_picture column...</h3>\n";
        $pdo->exec("ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL AFTER email");
        echo "✅ profile_picture column added successfully!\n";
    } else {
        echo "<h3>✅ profile_picture column already exists</h3>\n";
    }
    
    // Test user data query
    echo "<h3>Testing user data query...</h3>\n";
    $stmt = $pdo->query("SELECT id, username, email, profile_picture FROM users LIMIT 1");
    $test_user = $stmt->fetch();
    
    if ($test_user) {
        echo "✅ User data query works\n";
        echo "Sample user: " . $test_user['username'] . " (ID: " . $test_user['id'] . ")\n";
        echo "Profile picture: " . ($test_user['profile_picture'] ?: 'None') . "\n";
    } else {
        echo "❌ No users found in database\n";
    }
    
    // Check if uploads directory is writable
    echo "<h3>Upload directory check:</h3>\n";
    $upload_dir = 'uploads/profile_pictures/';
    echo "Directory: $upload_dir\n";
    echo "Exists: " . (is_dir($upload_dir) ? '✅ Yes' : '❌ No') . "\n";
    echo "Writable: " . (is_writable($upload_dir) ? '✅ Yes' : '❌ No') . "\n";
    
    if (is_dir($upload_dir)) {
        $perms = substr(sprintf('%o', fileperms($upload_dir)), -4);
        echo "Permissions: $perms\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n<p><a href='/edit-profile'>← Back to Edit Profile</a></p>\n";
?>