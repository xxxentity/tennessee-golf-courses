<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';
require_once '../includes/session-security.php';
require_once '../includes/auth-security.php';
require_once '../includes/input-validation.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

echo "<h1>Debug User Login Process</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['debug_login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    echo "<h2>🔍 Step-by-Step Login Debug for: {$username}</h2>";
    
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    
    // Step 1: Input validation
    echo "<h3>Step 1: Input Validation</h3>";
    if (empty($username) || empty($password)) {
        echo "<p style='color: red;'>❌ Empty username or password</p>";
    } else {
        echo "<p style='color: green;'>✅ Username and password provided</p>";
    }
    
    // Step 2: XSS/SQL injection check
    echo "<h3>Step 2: Security Checks</h3>";
    if (InputValidator::containsXSS($username)) {
        echo "<p style='color: red;'>❌ Username contains XSS patterns</p>";
    } else {
        echo "<p style='color: green;'>✅ No XSS detected in username</p>";
    }
    
    if (InputValidator::containsSQLInjection($username)) {
        echo "<p style='color: red;'>❌ Username contains SQL injection patterns</p>";
    } else {
        echo "<p style='color: green;'>✅ No SQL injection detected in username</p>";
    }
    
    // Step 3: Database lookup
    echo "<h3>Step 3: Database Lookup</h3>";
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name, password_hash, is_active, login_attempts, account_locked_until, email_verified FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user) {
            echo "<p style='color: green;'>✅ User found in database</p>";
            echo "<p><strong>Found user:</strong> {$user['username']} (ID: {$user['id']})</p>";
        } else {
            echo "<p style='color: red;'>❌ No user found with username/email: {$username}</p>";
            echo "</div>";
            return;
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
        echo "</div>";
        return;
    }
    
    // Step 4: Account status checks
    echo "<h3>Step 4: Account Status Checks</h3>";
    
    // Account locked check
    if ($user['account_locked_until'] && new DateTime() < new DateTime($user['account_locked_until'])) {
        $lockExpiry = new DateTime($user['account_locked_until']);
        $timeLeft = $lockExpiry->diff(new DateTime())->format('%h hours %i minutes');
        echo "<p style='color: red;'>❌ Account is locked for {$timeLeft}</p>";
    } else {
        echo "<p style='color: green;'>✅ Account is not locked</p>";
    }
    
    // Active check
    if (!$user['is_active']) {
        echo "<p style='color: red;'>❌ Account is not active</p>";
    } else {
        echo "<p style='color: green;'>✅ Account is active</p>";
    }
    
    // Email verified check
    if (!$user['email_verified']) {
        echo "<p style='color: red;'>❌ Email is not verified</p>";
    } else {
        echo "<p style='color: green;'>✅ Email is verified</p>";
    }
    
    // Step 5: Password verification
    echo "<h3>Step 5: Password Verification</h3>";
    echo "<p><strong>Stored hash:</strong> " . substr($user['password_hash'], 0, 60) . "...</p>";
    echo "<p><strong>Hash length:</strong> " . strlen($user['password_hash']) . "</p>";
    
    // Test with PHP's password_verify
    $phpVerify = password_verify($password, $user['password_hash']);
    echo "<p><strong>PHP password_verify():</strong> " . ($phpVerify ? '✅ PASS' : '❌ FAIL') . "</p>";
    
    // Test with AuthSecurity::verifyPassword
    try {
        $authVerify = AuthSecurity::verifyPassword($password, $user['password_hash']);
        echo "<p><strong>AuthSecurity::verifyPassword():</strong> " . ($authVerify ? '✅ PASS' : '❌ FAIL') . "</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ AuthSecurity::verifyPassword() error: " . $e->getMessage() . "</p>";
    }
    
    // Test hash algorithm info
    $hashInfo = password_get_info($user['password_hash']);
    echo "<p><strong>Hash algorithm:</strong> {$hashInfo['algoName']} (ID: {$hashInfo['algo']})</p>";
    
    // Step 6: IP and Rate limiting
    echo "<h3>Step 6: IP and Rate Limiting</h3>";
    $clientIP = AuthSecurity::getClientIP();
    echo "<p><strong>Client IP:</strong> {$clientIP}</p>";
    
    try {
        $suspiciousActivity = AuthSecurity::analyzeSuspiciousActivity($pdo, $clientIP);
        echo "<p><strong>Suspicious activity check:</strong> " . ($suspiciousActivity['should_block'] ? '❌ BLOCKED' : '✅ ALLOWED') . "</p>";
        if ($suspiciousActivity['should_block']) {
            echo "<p style='color: red;'>Reason: " . print_r($suspiciousActivity, true) . "</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ Suspicious activity check error: " . $e->getMessage() . "</p>";
    }
    
    // Step 7: Overall verdict
    echo "<h3>Step 7: Login Verdict</h3>";
    $canLogin = $user && 
                (!$user['account_locked_until'] || new DateTime() >= new DateTime($user['account_locked_until'])) &&
                $user['is_active'] && 
                $user['email_verified'] && 
                $phpVerify;
    
    if ($canLogin) {
        echo "<p style='color: green; font-weight: bold; font-size: 1.2em;'>🎉 LOGIN SHOULD SUCCEED</p>";
        echo "<p>All checks passed. If login still fails, there may be a session or CSRF issue.</p>";
    } else {
        echo "<p style='color: red; font-weight: bold; font-size: 1.2em;'>❌ LOGIN WILL FAIL</p>";
        echo "<p>One or more checks failed above.</p>";
    }
    
    echo "</div>";
}

// Debug form
echo "<h3>Test Login Process</h3>";
echo "<form method='POST'>";
echo "<div style='margin: 10px 0;'>";
echo "<label>Username/Email:</label><br>";
echo "<input type='text' name='username' placeholder='Enter username or email' style='padding: 8px; width: 300px;' value='" . ($_POST['username'] ?? '') . "'>";
echo "</div>";
echo "<div style='margin: 10px 0;'>";
echo "<label>Password:</label><br>";
echo "<input type='password' name='password' placeholder='Enter password' style='padding: 8px; width: 300px;'>";
echo "</div>";
echo "<button type='submit' name='debug_login' style='background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;'>Debug Login Process</button>";
echo "</form>";

echo "<p><a href='/admin/dashboard' style='color: #007cba;'>← Back to Admin Dashboard</a></p>";
?>

<style>
body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; margin: 0 auto; }
h1, h2, h3 { color: #333; }
form { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0; }
</style>