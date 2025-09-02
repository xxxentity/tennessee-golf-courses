<?php
require_once 'includes/session-security.php';
require_once 'includes/csrf.php';

// Start session
try {
    SecureSession::start();
    $sessionStarted = true;
    $sessionError = null;
} catch (Exception $e) {
    $sessionStarted = false;
    $sessionError = $e->getMessage();
}

// Get CSRF token
try {
    $csrfToken = CSRFProtection::getToken();
    $tokenGenerated = true;
    $tokenError = null;
} catch (Exception $e) {
    $tokenGenerated = false;
    $tokenError = $e->getMessage();
    $csrfToken = null;
}

// Check session data
$sessionData = [];
if (isset($_SESSION)) {
    $sessionData = $_SESSION;
}

// Check current time vs token time
$currentTime = time();
$tokenAge = isset($_SESSION['csrf_token_time']) ? $currentTime - $_SESSION['csrf_token_time'] : 'N/A';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Session Debug - Tennessee Golf Courses</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Session & CSRF Debug Information</h1>
    
    <div class="status <?php echo $sessionStarted ? 'success' : 'error'; ?>">
        <strong>Session Status:</strong> <?php echo $sessionStarted ? 'Started Successfully' : 'Failed - ' . $sessionError; ?>
    </div>
    
    <div class="status <?php echo $tokenGenerated ? 'success' : 'error'; ?>">
        <strong>CSRF Token:</strong> <?php echo $tokenGenerated ? 'Generated Successfully' : 'Failed - ' . $tokenError; ?>
    </div>
    
    <h2>Current Session Data</h2>
    <table>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
        <?php foreach ($sessionData as $key => $value): ?>
        <tr>
            <td><?php echo htmlspecialchars($key); ?></td>
            <td><?php echo htmlspecialchars(is_array($value) ? json_encode($value) : $value); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>Token Information</h2>
    <table>
        <tr>
            <th>Property</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Current CSRF Token</td>
            <td><?php echo $csrfToken ? substr($csrfToken, 0, 20) . '...' : 'None'; ?></td>
        </tr>
        <tr>
            <td>Token Age (seconds)</td>
            <td><?php echo $tokenAge; ?></td>
        </tr>
        <tr>
            <td>Token Max Age</td>
            <td>7200 seconds (2 hours)</td>
        </tr>
        <tr>
            <td>Token Valid?</td>
            <td><?php echo ($tokenAge !== 'N/A' && $tokenAge < 7200) ? 'Yes' : 'No'; ?></td>
        </tr>
    </table>
    
    <h2>Session Configuration</h2>
    <table>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Session Name</td>
            <td><?php echo session_name(); ?></td>
        </tr>
        <tr>
            <td>Session ID</td>
            <td><?php echo session_id(); ?></td>
        </tr>
        <tr>
            <td>Session Status</td>
            <td><?php echo session_status(); ?> (2=Active)</td>
        </tr>
        <tr>
            <td>Current Time</td>
            <td><?php echo date('Y-m-d H:i:s', $currentTime); ?></td>
        </tr>
    </table>
    
    <h2>Test CSRF Validation</h2>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken ?? ''); ?>">
        <button type="submit" name="test_csrf">Test Current Token</button>
    </form>
    
    <?php
    if (isset($_POST['test_csrf'])) {
        $testToken = $_POST['csrf_token'] ?? '';
        $isValid = CSRFProtection::validateToken($testToken);
        echo '<div class="status ' . ($isValid ? 'success' : 'error') . '">';
        echo '<strong>CSRF Test Result:</strong> ' . ($isValid ? 'Valid' : 'Invalid');
        echo '</div>';
    }
    ?>
    
    <p><a href="/login">← Back to Login</a></p>
</body>
</html>