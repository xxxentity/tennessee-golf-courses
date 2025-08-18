<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

echo "<h1>Regular User Login Diagnostic</h1>";

// Check if username is provided
$check_username = $_GET['username'] ?? $_POST['username'] ?? 'admin';

try {
    // Check user details
    $stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name, password_hash, is_active, login_attempts, account_locked_until, email_verified, created_at FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$check_username, $check_username]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<h2>✅ User Found: {$user['username']}</h2>";
        echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<p><strong>ID:</strong> {$user['id']}</p>";
        echo "<p><strong>Username:</strong> {$user['username']}</p>";
        echo "<p><strong>Email:</strong> {$user['email']}</p>";
        echo "<p><strong>Name:</strong> {$user['first_name']} {$user['last_name']}</p>";
        echo "<p><strong>Active:</strong> " . ($user['is_active'] ? '✅ Yes' : '❌ No') . "</p>";
        echo "<p><strong>Email Verified:</strong> " . ($user['email_verified'] ? '✅ Yes' : '❌ No') . "</p>";
        echo "<p><strong>Login Attempts:</strong> {$user['login_attempts']}</p>";
        echo "<p><strong>Account Locked Until:</strong> " . ($user['account_locked_until'] ?: 'Not locked') . "</p>";
        echo "<p><strong>Created:</strong> {$user['created_at']}</p>";
        echo "</div>";
        
        // Check login blocking conditions
        echo "<h3>Login Requirements Check</h3>";
        echo "<div style='background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin: 10px 0;'>";
        
        $blockers = [];
        
        if (!$user['is_active']) {
            $blockers[] = "❌ Account is not active";
        } else {
            echo "<p>✅ Account is active</p>";
        }
        
        if (!$user['email_verified']) {
            $blockers[] = "❌ Email is not verified";
        } else {
            echo "<p>✅ Email is verified</p>";
        }
        
        if ($user['account_locked_until'] && new DateTime() < new DateTime($user['account_locked_until'])) {
            $lockExpiry = new DateTime($user['account_locked_until']);
            $timeLeft = $lockExpiry->diff(new DateTime())->format('%h hours %i minutes');
            $blockers[] = "❌ Account is locked for {$timeLeft}";
        } else {
            echo "<p>✅ Account is not locked</p>";
        }
        
        if (empty($blockers)) {
            echo "<p style='color: green; font-weight: bold;'>🎉 All login requirements met!</p>";
        } else {
            echo "<h4 style='color: red;'>Login Blockers:</h4>";
            foreach ($blockers as $blocker) {
                echo "<p style='color: red;'>{$blocker}</p>";
            }
        }
        echo "</div>";
        
        // Fix options
        if (!empty($blockers)) {
            echo "<h3>Quick Fixes</h3>";
            echo "<div style='background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
            
            if (!$user['is_active']) {
                echo "<form method='POST' style='display: inline-block; margin: 5px;'>";
                echo "<input type='hidden' name='user_id' value='{$user['id']}'>";
                echo "<button type='submit' name='activate_user' style='background: #4caf50; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;'>Activate Account</button>";
                echo "</form>";
            }
            
            if (!$user['email_verified']) {
                echo "<form method='POST' style='display: inline-block; margin: 5px;'>";
                echo "<input type='hidden' name='user_id' value='{$user['id']}'>";
                echo "<button type='submit' name='verify_email' style='background: #2196f3; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;'>Mark Email Verified</button>";
                echo "</form>";
            }
            
            if ($user['account_locked_until']) {
                echo "<form method='POST' style='display: inline-block; margin: 5px;'>";
                echo "<input type='hidden' name='user_id' value='{$user['id']}'>";
                echo "<button type='submit' name='unlock_account' style='background: #ff9800; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;'>Unlock Account</button>";
                echo "</form>";
            }
            echo "</div>";
        }
        
        // Password test
        echo "<h3>Test Password</h3>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='username' value='{$check_username}'>";
        echo "<input type='password' name='test_password' placeholder='Enter password to test' style='padding: 8px; margin: 5px;'>";
        echo "<button type='submit' name='test_login' style='background: #673ab7; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;'>Test Password</button>";
        echo "</form>";
        
        if (isset($_POST['test_login']) && isset($_POST['test_password'])) {
            $test_password = $_POST['test_password'];
            $verified = password_verify($test_password, $user['password_hash']);
            echo "<p><strong>Password Test:</strong> " . ($verified ? '✅ CORRECT' : '❌ INCORRECT') . "</p>";
        }
        
    } else {
        echo "<h2>❌ No user found with username/email: {$check_username}</h2>";
        
        // Show all users
        $stmt = $pdo->query("SELECT id, username, email, is_active, email_verified FROM users ORDER BY created_at DESC LIMIT 10");
        $users = $stmt->fetchAll();
        
        if ($users) {
            echo "<h3>Recent Users:</h3>";
            echo "<table style='border-collapse: collapse; width: 100%;'>";
            echo "<tr style='background: #f5f5f5;'><th style='border: 1px solid #ddd; padding: 8px;'>ID</th><th style='border: 1px solid #ddd; padding: 8px;'>Username</th><th style='border: 1px solid #ddd; padding: 8px;'>Email</th><th style='border: 1px solid #ddd; padding: 8px;'>Active</th><th style='border: 1px solid #ddd; padding: 8px;'>Verified</th></tr>";
            foreach ($users as $u) {
                echo "<tr>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$u['id']}</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='?username={$u['username']}'>{$u['username']}</a></td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$u['email']}</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . ($u['is_active'] ? '✅' : '❌') . "</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . ($u['email_verified'] ? '✅' : '❌') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Database Error</h2>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}

// Handle fix actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    
    if ($user_id) {
        try {
            if (isset($_POST['activate_user'])) {
                $stmt = $pdo->prepare("UPDATE users SET is_active = 1 WHERE id = ?");
                $stmt->execute([$user_id]);
                echo "<p style='color: green; font-weight: bold;'>✅ User activated!</p>";
            }
            
            if (isset($_POST['verify_email'])) {
                $stmt = $pdo->prepare("UPDATE users SET email_verified = 1 WHERE id = ?");
                $stmt->execute([$user_id]);
                echo "<p style='color: green; font-weight: bold;'>✅ Email marked as verified!</p>";
            }
            
            if (isset($_POST['unlock_account'])) {
                $stmt = $pdo->prepare("UPDATE users SET account_locked_until = NULL, login_attempts = 0 WHERE id = ?");
                $stmt->execute([$user_id]);
                echo "<p style='color: green; font-weight: bold;'>✅ Account unlocked!</p>";
            }
            
            // Refresh page to show updated status
            if (isset($_POST['activate_user']) || isset($_POST['verify_email']) || isset($_POST['unlock_account'])) {
                echo "<script>setTimeout(function(){ window.location.reload(); }, 1500);</script>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }
}

// Search form
echo "<h3>Check Different User</h3>";
echo "<form method='GET'>";
echo "<input type='text' name='username' placeholder='Username or email' value='{$check_username}' style='padding: 8px; margin: 5px;'>";
echo "<button type='submit' style='background: #007cba; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;'>Check User</button>";
echo "</form>";

echo "<p><a href='/admin/dashboard' style='color: #007cba;'>← Back to Admin Dashboard</a></p>";
?>

<style>
body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; margin: 0 auto; }
h1, h2, h3 { color: #333; }
table { margin: 10px 0; }
form { margin: 10px 0; }
</style>