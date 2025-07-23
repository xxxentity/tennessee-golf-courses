<?php
// Debug registration process
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Registration Process</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>POST Data Received:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h2>Testing Database Connection:</h2>";
    try {
        require_once '../config/database.php';
        echo "✅ Database connection successful<br>";
        
        // Test rate limiter
        echo "<h2>Testing Rate Limiter:</h2>";
        require_once '../includes/rate-limiter.php';
        $rateLimiter = new RateLimiter($pdo);
        $allowed = $rateLimiter->isAllowed('registration', 3, 1);
        echo "Rate limit check: " . ($allowed ? "✅ Allowed" : "❌ Blocked") . "<br>";
        
        // Test user data
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $password = $_POST['password'] ?? '';
        
        echo "<h2>Validation:</h2>";
        echo "Username: " . htmlspecialchars($username) . " (length: " . strlen($username) . ")<br>";
        echo "Email: " . htmlspecialchars($email) . " (valid: " . (filter_var($email, FILTER_VALIDATE_EMAIL) ? "✅" : "❌") . ")<br>";
        echo "First name: " . htmlspecialchars($first_name) . "<br>";
        echo "Last name: " . htmlspecialchars($last_name) . "<br>";
        echo "Password length: " . strlen($password) . "<br>";
        
        // Check for existing user
        echo "<h2>Checking for existing user:</h2>";
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($existing = $stmt->fetch()) {
            echo "❌ User already exists with ID: " . $existing['id'] . "<br>";
        } else {
            echo "✅ Username and email are available<br>";
            
            // Test password hashing
            echo "<h2>Testing password hash:</h2>";
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            echo "Password hash generated: " . substr($password_hash, 0, 20) . "...<br>";
            
            // Test email verification token
            echo "<h2>Testing verification token:</h2>";
            $email_verification_token = bin2hex(random_bytes(32));
            echo "Verification token: " . substr($email_verification_token, 0, 20) . "...<br>";
            
            echo "<h2>Would insert user with these values:</h2>";
            echo "username: " . htmlspecialchars($username) . "<br>";
            echo "email: " . htmlspecialchars($email) . "<br>";
            echo "first_name: " . htmlspecialchars($first_name) . "<br>";
            echo "last_name: " . htmlspecialchars($last_name) . "<br>";
            echo "password_hash: " . substr($password_hash, 0, 30) . "...<br>";
            echo "email_verification_token: " . substr($email_verification_token, 0, 30) . "...<br>";
            echo "email_verified: 0<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "<p>Send POST data to test registration process</p>";
}
?>

<form method="POST">
    <h3>Test Registration Form</h3>
    <p>Username: <input type="text" name="username" value="testuser" required></p>
    <p>Email: <input type="email" name="email" value="test@example.com" required></p>
    <p>First Name: <input type="text" name="first_name" value="Test" required></p>
    <p>Last Name: <input type="text" name="last_name" value="User" required></p>
    <p>Password: <input type="password" name="password" value="password123" required></p>
    <p>Confirm Password: <input type="password" name="confirm_password" value="password123" required></p>
    <p><button type="submit">Test Registration</button></p>
</form>