<?php
// Debug CSRF login issue
require_once 'includes/session-security.php';
require_once 'includes/csrf.php';

try {
    SecureSession::start();
} catch (Exception $e) {
    echo "Session error: " . $e->getMessage();
    exit;
}

echo "<h1>Login CSRF Debug</h1>";

// Check current CSRF status
echo "<h2>Current Session CSRF:</h2>";
echo "Session token: " . ($_SESSION['csrf_token'] ?? 'NONE') . "<br>";
echo "Session token time: " . ($_SESSION['csrf_token_time'] ?? 'NONE') . "<br>";
echo "Current time: " . time() . "<br>";

if (isset($_SESSION['csrf_token_time'])) {
    $age = time() - $_SESSION['csrf_token_time'];
    echo "Token age: " . $age . " seconds (" . round($age/60, 1) . " minutes)<br>";
    echo "Token valid: " . ($age < 7200 ? 'YES' : 'NO (expired)') . "<br>";
}

// Test generating new token
echo "<h2>Generate Fresh Token:</h2>";
$new_token = CSRFProtection::generateToken();
echo "New token: " . $new_token . "<br>";

// Test validation
echo "<h2>Token Validation Test:</h2>";
$test_valid = CSRFProtection::validateToken($new_token);
echo "New token validates: " . ($test_valid ? 'YES' : 'NO') . "<br>";

// Test login form token generation
echo "<h2>Login Form Token:</h2>";
echo "<form method='post' action='/auth/login-process.php'>";
echo "Username: <input type='text' name='username' value='test'><br>";
echo "Password: <input type='password' name='password' value='test'><br>";
$form_token = CSRFProtection::getToken();
echo "<input type='hidden' name='csrf_token' value='" . htmlspecialchars($form_token) . "'>";
echo "Form token: " . $form_token . "<br>";
echo "Form token same as session: " . ($form_token === $_SESSION['csrf_token'] ? 'YES' : 'NO') . "<br>";
echo "<input type='submit' value='Test Submit (don't actually click)'>";
echo "</form>";

// Cloudflare info
echo "<h2>Server/Cloudflare Info:</h2>";
echo "CF-Ray: " . ($_SERVER['HTTP_CF_RAY'] ?? 'Not found') . "<br>";
echo "CF-IPCountry: " . ($_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'Not found') . "<br>";
echo "X-Forwarded-For: " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'Not found') . "<br>";
echo "Real IP: " . ($_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "<br>";
echo "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "<br>";
?>