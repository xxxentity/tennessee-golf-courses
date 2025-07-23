<?php
// Simple script to create admin user with proper password hash
require_once '../config/database.php';

// Generate password hash for 'admin123'
$password = 'admin123';
$password_hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Admin User Creator</h2>";

try {
    // First, delete any existing admin user
    $stmt = $pdo->prepare("DELETE FROM admin_users WHERE username = 'admin'");
    $stmt->execute();
    
    // Insert new admin user
    $stmt = $pdo->prepare("
        INSERT INTO admin_users (username, email, password_hash, first_name, last_name) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        'admin',
        'admin@tennesseegolfcourses.com',
        $password_hash,
        'Admin',
        'User'
    ]);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Admin user created successfully!</p>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><strong>Generated Hash:</strong> " . $password_hash . "</p>";
        echo "<p><a href='/admin/login'>Go to Admin Login →</a></p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create admin user</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
    
    // If table doesn't exist, show the SQL to create it
    if (strpos($e->getMessage(), "doesn't exist") !== false) {
        echo "<h3>Create the admin_users table first:</h3>";
        echo "<pre style='background: #f5f5f5; padding: 15px; border-radius: 5px;'>";
        echo "CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_username (username),
    INDEX idx_email (email)
);";
        echo "</pre>";
    }
}
?>

<style>
body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
pre { overflow-x: auto; }
</style>