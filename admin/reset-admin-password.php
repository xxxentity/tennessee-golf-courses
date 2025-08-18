<?php
require_once '../config/database.php';

// Set the admin password to TGCadmin666!
$new_password = 'TGCadmin666!';
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

try {
    // Update the admin password
    $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'admin'");
    $result = $stmt->execute([$new_hash]);
    
    if ($result) {
        echo "<h2>✅ Admin Password Reset Successful</h2>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>New Password:</strong> TGCadmin666!</p>";
        echo "<p><strong>Hash:</strong> " . substr($new_hash, 0, 60) . "...</p>";
        
        // Test the password immediately
        $test_verified = password_verify($new_password, $new_hash);
        echo "<p><strong>Password Verification Test:</strong> " . ($test_verified ? '✅ PASS' : '❌ FAIL') . "</p>";
        
        // Test AdminSecurity authentication
        require_once '../includes/admin-security-simple.php';
        $auth_result = AdminSecurity::authenticateAdmin($pdo, 'admin', $new_password);
        
        echo "<h3>AdminSecurity Test Result:</h3>";
        echo "<pre>" . print_r($auth_result, true) . "</pre>";
        
        if ($auth_result['success']) {
            echo "<p style='color: green; font-weight: bold;'>🎉 Admin login should now work!</p>";
            echo "<p><a href='/admin/login' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Login</a></p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ There's still an issue with admin authentication.</p>";
        }
        
    } else {
        echo "<h2>❌ Failed to update admin password</h2>";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Database Error</h2>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    padding: 20px; 
    max-width: 800px; 
    margin: 0 auto; 
    background: #f5f5f5;
}

h2, h3 { 
    color: #333; 
    border-bottom: 2px solid #007cba;
    padding-bottom: 10px;
}

p { 
    margin: 10px 0; 
    padding: 8px;
    background: white;
    border-left: 4px solid #007cba;
}

pre { 
    background: #f8f8f8; 
    padding: 15px; 
    border-radius: 5px; 
    overflow-x: auto;
    border: 1px solid #ddd;
}
</style>