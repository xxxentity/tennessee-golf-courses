<?php
// Complete admin login diagnostic and fix tool
require_once '../config/database.php';

echo "<h2>🔧 Admin Login Diagnostic & Fix Tool</h2>";

echo "<h3>Step 1: Check if admin_users table exists</h3>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'admin_users'");
    $table_exists = $stmt->rowCount() > 0;
    
    if ($table_exists) {
        echo "✅ admin_users table exists<br>";
    } else {
        echo "❌ admin_users table does NOT exist<br>";
        echo "<strong>Creating admin_users table...</strong><br>";
        
        $create_table = "
        CREATE TABLE admin_users (
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
        )";
        
        $pdo->exec($create_table);
        echo "✅ admin_users table created<br>";
    }
} catch (PDOException $e) {
    echo "❌ Error checking/creating table: " . $e->getMessage() . "<br>";
}

echo "<h3>Step 2: Check current admin users</h3>";
try {
    $stmt = $pdo->query("SELECT id, username, email, is_active FROM admin_users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "❌ No admin users found<br>";
    } else {
        echo "📋 Current admin users:<br>";
        foreach ($users as $user) {
            echo "- ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}, Active: " . ($user['is_active'] ? 'Yes' : 'No') . "<br>";
        }
    }
} catch (PDOException $e) {
    echo "❌ Error reading users: " . $e->getMessage() . "<br>";
}

echo "<h3>Step 3: Create/Reset admin user</h3>";
try {
    // Delete any existing admin user
    $stmt = $pdo->prepare("DELETE FROM admin_users WHERE username = 'admin'");
    $stmt->execute();
    echo "🗑️ Cleared any existing admin user<br>";
    
    // Create fresh admin user
    $username = 'admin';
    $password = 'TGCadmin666!';
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        INSERT INTO admin_users (username, email, password_hash, first_name, last_name, is_active) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        $username,
        'admin@tennesseegolfcourses.com',
        $password_hash,
        'Admin',
        'User',
        1
    ]);
    
    if ($result) {
        echo "✅ Admin user created successfully<br>";
        echo "<strong>Username:</strong> admin<br>";
        echo "<strong>Password:</strong> TGCadmin666!<br>";
        echo "<strong>Hash:</strong> " . substr($password_hash, 0, 20) . "...<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error creating admin user: " . $e->getMessage() . "<br>";
}

echo "<h3>Step 4: Test password verification</h3>";
try {
    $stmt = $pdo->prepare("SELECT username, password_hash FROM admin_users WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        $test_password = 'TGCadmin666!';
        $verify_result = password_verify($test_password, $admin['password_hash']);
        
        echo "🔍 Testing password 'TGCadmin666!' against stored hash...<br>";
        echo ($verify_result ? "✅ Password verification WORKS!" : "❌ Password verification FAILED!") . "<br>";
        
        if ($verify_result) {
            echo "<div style='background: #d4edda; border: 1px solid #155724; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h4>🎉 LOGIN SHOULD NOW WORK!</h4>";
            echo "<p><strong>Go to:</strong> <a href='/admin/login'>/admin/login</a></p>";
            echo "<p><strong>Username:</strong> admin</p>";
            echo "<p><strong>Password:</strong> TGCadmin666!</p>";
            echo "</div>";
        }
    } else {
        echo "❌ Could not find admin user after creation<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error testing verification: " . $e->getMessage() . "<br>";
}

echo "<h3>Step 5: Database connection info</h3>";
echo "✅ Database connection working<br>";
echo "📊 Database name: " . ($pdo->query("SELECT DATABASE()")->fetchColumn()) . "<br>";

?>

<style>
body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; line-height: 1.6; }
h2, h3 { color: #064E3B; }
div { margin: 10px 0; }
</style>