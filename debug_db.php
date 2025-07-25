<?php
require_once 'config/database.php';

// Check if course_comments table exists
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'course_comments'");
    $table_exists = $stmt->fetch();
    
    if ($table_exists) {
        echo "✅ course_comments table exists\n";
        
        // Check table structure
        $stmt = $pdo->query("DESCRIBE course_comments");
        $columns = $stmt->fetchAll();
        echo "\nTable structure:\n";
        foreach ($columns as $column) {
            echo "- {$column['Field']}: {$column['Type']}\n";
        }
        
        // Check if users table has username column
        $stmt = $pdo->query("DESCRIBE users");
        $user_columns = $stmt->fetchAll();
        echo "\nUsers table columns:\n";
        foreach ($user_columns as $column) {
            echo "- {$column['Field']}: {$column['Type']}\n";
        }
        
    } else {
        echo "❌ course_comments table does not exist\n";
        echo "Need to create it!\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>