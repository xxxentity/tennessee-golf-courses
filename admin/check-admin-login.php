<?php
require_once '../config/database.php';

// Check admin user details
try {
    $stmt = $pdo->query("SELECT id, username, email, password_hash, is_active FROM admin_users WHERE username = 'admin'");
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<h2>Admin User Found</h2>";
        echo "<p><strong>ID:</strong> " . $admin['id'] . "</p>";
        echo "<p><strong>Username:</strong> " . $admin['username'] . "</p>";
        echo "<p><strong>Email:</strong> " . $admin['email'] . "</p>";
        echo "<p><strong>Active:</strong> " . ($admin['is_active'] ? 'Yes' : 'No') . "</p>";
        echo "<p><strong>Password Hash:</strong> " . substr($admin['password_hash'], 0, 50) . "...</p>";
        echo "<p><strong>Hash Length:</strong> " . strlen($admin['password_hash']) . "</p>";
        
        // Test password verification
        if (isset($_POST['test_password'])) {
            $test_password = $_POST['test_password'];
            $verified = password_verify($test_password, $admin['password_hash']);
            echo "<p><strong>Password Test Result:</strong> " . ($verified ? 'PASS' : 'FAIL') . "</p>";
            
            // Try with different algorithms
            echo "<h3>Hash Algorithm Detection:</h3>";
            $info = password_get_info($admin['password_hash']);
            echo "<p>Algorithm: " . $info['algo'] . "</p>";
            echo "<p>Algorithm Name: " . $info['algoName'] . "</p>";
        }
        
    } else {
        echo "<h2>No admin user found with username 'admin'</h2>";
        
        // Show all admin users
        $stmt = $pdo->query("SELECT id, username, email, is_active FROM admin_users");
        $admins = $stmt->fetchAll();
        
        if ($admins) {
            echo "<h3>Available admin users:</h3>";
            foreach ($admins as $a) {
                echo "<p>ID: {$a['id']}, Username: {$a['username']}, Email: {$a['email']}, Active: " . ($a['is_active'] ? 'Yes' : 'No') . "</p>";
            }
        } else {
            echo "<p>No admin users exist in the database.</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<h2>Database Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}

// Test form
echo '<h3>Test Password</h3>';
echo '<form method="POST">';
echo '<input type="password" name="test_password" placeholder="Enter password to test">';
echo '<button type="submit">Test Password</button>';
echo '</form>';

// Create new admin form
echo '<h3>Create New Admin Password</h3>';
echo '<form method="POST">';
echo '<input type="password" name="new_password" placeholder="New password">';
echo '<button type="submit" name="create_new">Update Admin Password</button>';
echo '</form>';

// Test AdminSecurity authentication
echo '<h3>Test AdminSecurity Authentication</h3>';
if (isset($_POST['test_admin_auth'])) {
    require_once '../includes/admin-security-simple.php';
    
    $test_username = $_POST['test_username'] ?? '';
    $test_password = $_POST['test_password_auth'] ?? '';
    
    if (!empty($test_username) && !empty($test_password)) {
        echo "<p><strong>Testing AdminSecurity::authenticateAdmin()...</strong></p>";
        $result = AdminSecurity::authenticateAdmin($pdo, $test_username, $test_password);
        
        echo "<p><strong>Result:</strong></p>";
        echo "<pre>" . print_r($result, true) . "</pre>";
    }
}

echo '<form method="POST">';
echo '<input type="text" name="test_username" placeholder="Username" value="admin">';
echo '<input type="password" name="test_password_auth" placeholder="Password">';
echo '<button type="submit" name="test_admin_auth">Test AdminSecurity Auth</button>';
echo '</form>';

// Handle new password creation
if (isset($_POST['create_new']) && isset($_POST['new_password'])) {
    $new_password = $_POST['new_password'];
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'admin'");
        $result = $stmt->execute([$new_hash]);
        
        if ($result) {
            echo "<p style='color: green;'>Password updated successfully!</p>";
            echo "<p>New hash: " . substr($new_hash, 0, 50) . "...</p>";
        } else {
            echo "<p style='color: red;'>Failed to update password.</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<style>
body { font-family: Arial, sans-serif; padding: 20px; }
h2, h3 { color: #333; }
p { margin: 5px 0; }
form { margin: 10px 0; }
input { padding: 8px; margin: 5px; }
button { padding: 8px 15px; background: #007cba; color: white; border: none; cursor: pointer; }
</style>