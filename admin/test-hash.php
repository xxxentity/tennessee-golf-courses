<?php
// Test the specific hash you mentioned
$stored_hash = '$2y$10$Y7JB2/tapU7LRDQydTLWGuvbRsjGLJTyS/TWK5eiRncL1aR6daPWi';
$test_password = 'TGCadmin666!';

echo "<h2>Hash Verification Test</h2>";

echo "<p><strong>Stored Hash:</strong> " . htmlspecialchars($stored_hash) . "</p>";
echo "<p><strong>Test Password:</strong> " . htmlspecialchars($test_password) . "</p>";

$verify_result = password_verify($test_password, $stored_hash);

echo "<h3>Verification Result:</h3>";
echo "<p style='font-size: 18px; padding: 15px; border-radius: 8px; " . 
     ($verify_result ? "background: #d4edda; color: #155724; border: 1px solid #c3e6cb;" : "background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;") . "'>";
echo ($verify_result ? "✅ SUCCESS: Hash matches password 'TGCadmin666!'" : "❌ FAILED: Hash does NOT match password 'TGCadmin666!'");
echo "</p>";

if ($verify_result) {
    echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h4>The hash is correct!</h4>";
    echo "<p>The problem might be:</p>";
    echo "<ul>";
    echo "<li>Username 'admin' doesn't exist in database</li>";
    echo "<li>User is not active (is_active = 0)</li>";
    echo "<li>Database connection issue</li>";
    echo "<li>Different database being used</li>";
    echo "</ul>";
    echo "<p><strong>Try this:</strong> Go to <a href='/admin/fix-login'>/admin/fix-login</a> to see exactly what's in your database.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #fee2e2; border: 1px solid #dc2626; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h4>Hash verification failed!</h4>";
    echo "<p>This hash does not match the password 'TGCadmin666!'</p>";
    echo "<p>Let me generate the correct hash:</p>";
    
    $correct_hash = password_hash($test_password, PASSWORD_DEFAULT);
    echo "<p><strong>Correct hash should be:</strong> " . $correct_hash . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='/admin/fix-login'>← Run Full Diagnostic Tool</a></p>";
?>

<style>
body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; line-height: 1.6; }
h2, h3 { color: #064E3B; }
</style>