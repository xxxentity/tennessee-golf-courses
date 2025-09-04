<?php
require_once 'includes/session-security.php';

// Start secure session
try {
    SecureSession::start();
    echo "<h2>✅ Session Started Successfully</h2>";
} catch (Exception $e) {
    echo "<h2>❌ Session Start Failed</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

echo "<h3>🔍 Session Debug Information</h3>";
echo "<ul>";
echo "<li><strong>Session ID:</strong> " . session_id() . "</li>";
echo "<li><strong>Session Name:</strong> " . session_name() . "</li>";
echo "<li><strong>Session Status:</strong> " . (session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Not Active') . "</li>";
echo "</ul>";

echo "<h3>📊 Session Data</h3>";
if (empty($_SESSION)) {
    echo "<p>❌ No session data found</p>";
} else {
    echo "<pre style='background: #f0f0f0; padding: 15px; border-radius: 5px;'>";
    print_r($_SESSION);
    echo "</pre>";
}

echo "<h3>🔐 Login Status Check</h3>";
$is_logged_in = SecureSession::isLoggedIn();
echo "<ul>";
echo "<li><strong>Is Logged In:</strong> " . ($is_logged_in ? '✅ Yes' : '❌ No') . "</li>";
if ($is_logged_in) {
    echo "<li><strong>Username:</strong> " . SecureSession::get('username', 'Not set') . "</li>";
    echo "<li><strong>First Name:</strong> " . SecureSession::get('first_name', 'Not set') . "</li>";
}
echo "</ul>";

echo "<h3>🍪 Cookie Information</h3>";
echo "<ul>";
echo "<li><strong>Domain:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "</li>";
echo "<li><strong>Path:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "</li>";
echo "<li><strong>HTTPS:</strong> " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? '✅ Yes' : '❌ No') . "</li>";
echo "</ul>";

$cookies = session_get_cookie_params();
echo "<h3>📋 Session Cookie Parameters</h3>";
echo "<pre style='background: #f0f0f0; padding: 15px; border-radius: 5px;'>";
print_r($cookies);
echo "</pre>";

echo "<hr>";
echo "<p><a href='login'>← Back to Login</a> | <a href='index.php'>Home Page</a> | <a href='courses.php'>Courses Page</a></p>";
?>