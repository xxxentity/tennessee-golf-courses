<?php
// Direct password reset to TGCadmin666!
require_once '../config/database.php';

echo "<h2>Setting New Admin Password</h2>";

try {
    // Generate hash for the new password
    $new_password = 'TGCadmin666!';
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update admin password
    $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'admin'");
    $result = $stmt->execute([$password_hash]);
    
    if ($result) {
        echo "<div style='background: #d4edda; border: 1px solid #155724; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>✅ Password Successfully Changed!</h3>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>New Password:</strong> TGCadmin666!</p>";
        echo "<p><strong>Generated Hash:</strong> " . $password_hash . "</p>";
        echo "</div>";
        
        // Test the new password
        $verify_test = password_verify($new_password, $password_hash);
        echo "<p><strong>Verification Test:</strong> " . ($verify_test ? '✅ WORKS' : '❌ FAILED') . "</p>";
        
        echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h4>Next Steps:</h4>";
        echo "<ol>";
        echo "<li>Go to <a href='/admin/login'>/admin/login</a></li>";
        echo "<li>Login with: <strong>admin</strong> / <strong>TGCadmin666!</strong></li>";
        echo "<li>Access your newsletter admin panel</li>";
        echo "</ol>";
        echo "</div>";
        
    } else {
        echo "<p style='color: red;'>❌ Failed to update password</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
h2, h3 { color: #064E3B; }
</style>