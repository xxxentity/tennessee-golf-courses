<?php
// Debug CSRF tokens
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/csrf.php';

echo "<h2>CSRF Debug Information</h2>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Session started:</strong> " . (session_status() === PHP_SESSION_ACTIVE ? 'Yes' : 'No') . "</p>";
echo "<p><strong>Current session token:</strong> " . ($_SESSION['csrf_token'] ?? 'None') . "</p>";
echo "<p><strong>Token timestamp:</strong> " . ($_SESSION['csrf_token_time'] ?? 'None') . "</p>";
echo "<p><strong>Current time:</strong> " . time() . "</p>";

if (isset($_SESSION['csrf_token_time'])) {
    $age = time() - $_SESSION['csrf_token_time'];
    echo "<p><strong>Token age:</strong> " . $age . " seconds</p>";
    echo "<p><strong>Token expired:</strong> " . ($age > 14400 ? 'Yes' : 'No') . " (limit: 14400 seconds)</p>";
}

$newToken = CSRFProtection::getToken();
echo "<p><strong>Generated token:</strong> " . $newToken . "</p>";

// Test form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    echo "<h3>Form Submission Test</h3>";
    echo "<p><strong>Submitted token:</strong> " . $submittedToken . "</p>";
    echo "<p><strong>Validation result:</strong> " . (CSRFProtection::validateToken($submittedToken) ? 'VALID' : 'INVALID') . "</p>";
}
?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($newToken); ?>">
    <button type="submit">Test CSRF Token</button>
</form>

<hr>
<p><a href="/register">Go to Registration Form</a></p>
<p><em>Note: Use clean URLs without /auth/ prefix - the server redirects these automatically.</em></p>