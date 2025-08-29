<?php
// Debug session and CSRF issues
echo "<h1>Session Debug</h1>";

// Check if session starts properly
try {
    require_once 'includes/session-security.php';
    SecureSession::start();
    echo "<p style='color: green;'>✅ Session started successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Session error: " . $e->getMessage() . "</p>";
}

// Check session data
echo "<h2>Session Status:</h2>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session status: " . session_status() . " (1=disabled, 2=active, 3=none)</p>";

// Check CSRF functionality
echo "<h2>CSRF Token Test:</h2>";
if (function_exists('csrf_token')) {
    try {
        $token = csrf_token();
        echo "<p style='color: green;'>✅ CSRF token generated: " . substr($token, 0, 10) . "...</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ CSRF error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ CSRF function not found - checking for other implementations</p>";
}

// Check for CSRF in forms
echo "<h2>Checking Login Form:</h2>";
if (file_exists('login.php')) {
    $login_content = file_get_contents('login.php');
    if (strpos($login_content, 'csrf') !== false) {
        echo "<p>✅ CSRF tokens found in login form</p>";
    } else {
        echo "<p>❌ No CSRF tokens in login form</p>";
    }
} else {
    echo "<p>❌ login.php not found</p>";
}

// Check current session data
echo "<h2>Current Session Data:</h2>";
if (!empty($_SESSION)) {
    echo "<pre>";
    foreach ($_SESSION as $key => $value) {
        if (strpos($key, 'password') === false) { // Don't show passwords
            echo htmlspecialchars($key) . " => " . htmlspecialchars(print_r($value, true)) . "\n";
        }
    }
    echo "</pre>";
} else {
    echo "<p>No session data found</p>";
}

// Server info
echo "<h2>Server Info:</h2>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Server: " . ($_SERVER['SERVER_NAME'] ?? 'unknown') . "</p>";
echo "<p>HTTPS: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'Yes' : 'No') . "</p>";
?>