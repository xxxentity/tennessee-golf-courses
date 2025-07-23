<?php
session_start();
require_once '../config/database.php';

// Admin authentication check
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /admin/login');
    exit;
}

echo "<h2>Admin Password Debug</h2>";

try {
    // Get current admin user info
    $stmt = $pdo->prepare("SELECT username, password_hash FROM admin_users WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "<p><strong>Username:</strong> " . htmlspecialchars($admin['username']) . "</p>";
        echo "<p><strong>Stored Hash:</strong> " . htmlspecialchars($admin['password_hash']) . "</p>";
        
        // Test password verification
        $test_password = 'admin123';
        $verify_result = password_verify($test_password, $admin['password_hash']);
        
        echo "<h3>Password Test:</h3>";
        echo "<p><strong>Testing password:</strong> " . $test_password . "</p>";
        echo "<p><strong>Verification result:</strong> " . ($verify_result ? '✅ SUCCESS' : '❌ FAILED') . "</p>";
        
        // Generate fresh hash for comparison
        $fresh_hash = password_hash($test_password, PASSWORD_DEFAULT);
        echo "<p><strong>Fresh hash for 'admin123':</strong> " . $fresh_hash . "</p>";
        
        if (!$verify_result) {
            echo "<div style='background: #fee2e2; border: 1px solid #dc2626; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h4>❌ Password verification failed!</h4>";
            echo "<p>This means the stored hash doesn't match 'admin123'.</p>";
            echo "<p><strong>Solution:</strong> Let me reset your password.</p>";
            
            // Reset password
            $new_hash = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
            $result = $stmt->execute([$new_hash, $_SESSION['admin_id']]);
            
            if ($result) {
                echo "<p style='color: green;'>✅ Password has been reset to 'admin123'</p>";
                echo "<p><a href='/admin/change-password'>Try changing your password now →</a></p>";
            } else {
                echo "<p style='color: red;'>❌ Failed to reset password</p>";
            }
            echo "</div>";
        } else {
            echo "<div style='background: #d4edda; border: 1px solid #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h4>✅ Password verification works!</h4>";
            echo "<p>The password 'admin123' should work in the change password form.</p>";
            echo "</div>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Admin user not found</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
code { background: #f5f5f5; padding: 2px 4px; border-radius: 3px; }
</style>

<p><a href="/admin/newsletter">← Back to Admin Panel</a></p>