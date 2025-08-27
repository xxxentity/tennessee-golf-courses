<!DOCTYPE html>
<html>
<head>
    <title>Database Migration - Add User Title Column</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
    </style>
</head>
<body>
    <h2>Database Migration: Add user_title Column</h2>
    
    <?php
    require_once 'config/database.php';
    
    try {
        // Check if column already exists
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'user_title'");
        $columnExists = $stmt->fetch();
        
        if (!$columnExists) {
            echo "<p class='info'>Adding user_title column to users table...</p>";
            
            // Add the column with default value
            $pdo->exec("ALTER TABLE users ADD COLUMN user_title VARCHAR(50) DEFAULT 'Verified User' COMMENT 'User role/title displayed on profile'");
            
            echo "<p class='success'>✅ Column added successfully!</p>";
            
            // Set specific titles for existing special users (you can customize these)
            echo "<p class='info'>Setting default titles for all users...</p>";
            
            // Set all existing users to have the default title
            $pdo->exec("UPDATE users SET user_title = 'Verified User' WHERE user_title IS NULL");
            
            echo "<p class='success'>✅ Default titles set!</p>";
            echo "<p class='success'><strong>Migration completed!</strong></p>";
            echo "<p>You can now update individual user titles as needed:</p>";
            echo "<ul>";
            echo "<li>Admin accounts → 'Site Administrator' or 'Editor-in-Chief'</li>";
            echo "<li>News editor → 'News Editor'</li>";
            echo "<li>Reviews editor → 'Reviews Editor'</li>";
            echo "<li>Regular users → 'Verified User' (default)</li>";
            echo "</ul>";
            
            // Show column info
            $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'user_title'");
            $column = $stmt->fetch();
            echo "<h3>Column Details:</h3>";
            echo "<pre>" . print_r($column, true) . "</pre>";
            
        } else {
            echo "<p class='info'>Column 'user_title' already exists in users table.</p>";
            echo "<pre>" . print_r($columnExists, true) . "</pre>";
        }
        
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    }
    ?>
    
    <hr>
    <p><a href="admin/dashboard">← Back to Admin Dashboard</a></p>
</body>
</html>