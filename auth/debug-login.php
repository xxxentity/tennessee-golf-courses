<?php
// Debug login process
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

echo "<h1>Debug Login Process</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>POST Data Received:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h2>Testing Database Connection:</h2>";
    try {
        require_once '../config/database.php';
        echo "✅ Database connection successful<br>";
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        echo "<h2>Looking up user:</h2>";
        echo "Username/Email: " . htmlspecialchars($username) . "<br>";
        
        $stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name, password_hash, is_active, login_attempts, account_locked_until, email_verified FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo "❌ User not found in database<br>";
            
            // Show all users for debugging
            echo "<h3>All users in database:</h3>";
            $stmt = $pdo->query("SELECT id, username, email, email_verified, is_active FROM users");
            $all_users = $stmt->fetchAll();
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Email Verified</th><th>Active</th></tr>";
            foreach ($all_users as $u) {
                echo "<tr>";
                echo "<td>" . $u['id'] . "</td>";
                echo "<td>" . htmlspecialchars($u['username']) . "</td>";
                echo "<td>" . htmlspecialchars($u['email']) . "</td>";
                echo "<td>" . ($u['email_verified'] ? 'Yes' : 'No') . "</td>";
                echo "<td>" . ($u['is_active'] ? 'Yes' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "✅ User found:<br>";
            echo "ID: " . $user['id'] . "<br>";
            echo "Username: " . htmlspecialchars($user['username']) . "<br>";
            echo "Email: " . htmlspecialchars($user['email']) . "<br>";
            echo "Email verified: " . ($user['email_verified'] ? 'Yes' : 'No') . "<br>";
            echo "Active: " . ($user['is_active'] ? 'Yes' : 'No') . "<br>";
            echo "Login attempts: " . $user['login_attempts'] . "<br>";
            echo "Account locked until: " . ($user['account_locked_until'] ?: 'Not locked') . "<br>";
            
            echo "<h3>Password verification:</h3>";
            if (password_verify($password, $user['password_hash'])) {
                echo "✅ Password is correct<br>";
                
                if (!$user['email_verified']) {
                    echo "❌ Email not verified - login would fail<br>";
                } else if (!$user['is_active']) {
                    echo "❌ Account not active - login would fail<br>";
                } else {
                    echo "✅ All checks passed - login should succeed<br>";
                    
                    echo "<h3>Setting session variables:</h3>";
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['logged_in'] = true;
                    
                    echo "Session set. Current session:<br>";
                    echo "<pre>";
                    print_r($_SESSION);
                    echo "</pre>";
                }
            } else {
                echo "❌ Password is incorrect<br>";
                echo "Password hash in DB: " . substr($user['password_hash'], 0, 50) . "...<br>";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "<p>Send POST data to test login process</p>";
}
?>

<form method="POST">
    <h3>Test Login Form</h3>
    <p>Username/Email: <input type="text" name="username" value="" required></p>
    <p>Password: <input type="password" name="password" value="" required></p>
    <p><button type="submit">Test Login</button></p>
</form>