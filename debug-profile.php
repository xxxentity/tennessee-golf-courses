<?php
// Debug script to check profile loading issues
require_once 'includes/session-security.php';
require_once 'config/database.php';

echo "<h2>Profile Debug Information</h2>\n";

// Check session status
try {
    SecureSession::start();
    echo "✅ SecureSession started successfully<br>\n";
    
    if (SecureSession::isLoggedIn()) {
        echo "✅ User is logged in<br>\n";
        $user_id = SecureSession::get('user_id');
        echo "User ID: " . $user_id . "<br>\n";
        echo "Username: " . SecureSession::get('username') . "<br>\n";
        
        // Test database query
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            if ($user) {
                echo "✅ User found in database<br>\n";
                echo "Username from DB: " . $user['username'] . "<br>\n";
                echo "Email: " . $user['email'] . "<br>\n";
                echo "Profile picture: " . ($user['profile_picture'] ?: 'None') . "<br>\n";
                echo "Created: " . $user['created_at'] . "<br>\n";
            } else {
                echo "❌ User NOT found in database<br>\n";
                echo "Query executed with user_id: " . $user_id . "<br>\n";
            }
        } catch (PDOException $e) {
            echo "❌ Database error: " . $e->getMessage() . "<br>\n";
        }
        
    } else {
        echo "❌ User is NOT logged in<br>\n";
    }
    
} catch (Exception $e) {
    echo "❌ Session error: " . $e->getMessage() . "<br>\n";
}

// Check database connection
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "<br>✅ Database connection working<br>\n";
    echo "Total users in database: " . $count . "<br>\n";
} catch (PDOException $e) {
    echo "❌ Database connection error: " . $e->getMessage() . "<br>\n";
}

echo "<br><a href='/profile'>← Back to Profile</a>\n";
?>