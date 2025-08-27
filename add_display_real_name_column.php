<!DOCTYPE html>
<html>
<head>
    <title>Database Migration - Add Display Real Name Column</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
    </style>
</head>
<body>
    <h2>Database Migration: Add display_real_name Column</h2>
    
    <?php
    // Add missing display_real_name column to users table
    require_once 'config/database.php';
    
    try {
        // Check if column already exists
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'display_real_name'");
        $columnExists = $stmt->fetch();
        
        if (!$columnExists) {
            echo "<p class='info'>Adding display_real_name column to users table...</p>";
            
            // Add the column
            $pdo->exec("ALTER TABLE users ADD COLUMN display_real_name BOOLEAN DEFAULT 0 COMMENT 'Whether to show real name on profile'");
            
            echo "<p class='success'>✅ Column added successfully!</p>";
            echo "<p class='info'>Setting default value (0 = private, 1 = show real name) for existing users...</p>";
            
            // Set default to 0 (private) for all existing users
            $pdo->exec("UPDATE users SET display_real_name = 0 WHERE display_real_name IS NULL");
            
            echo "<p class='success'>✅ Default values set successfully!</p>";
            echo "<p class='success'><strong>Migration completed!</strong> You can now use the privacy checkbox in account settings.</p>";
            
            // Show column info
            $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'display_real_name'");
            $column = $stmt->fetch();
            echo "<h3>Column Details:</h3>";
            echo "<pre>" . print_r($column, true) . "</pre>";
            
        } else {
            echo "<p class='info'>Column 'display_real_name' already exists in users table.</p>";
            echo "<pre>" . print_r($columnExists, true) . "</pre>";
        }
        
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    }
    ?>
    
    <hr>
    <p><a href="user/edit-profile">← Back to Edit Profile</a></p>
</body>
</html>