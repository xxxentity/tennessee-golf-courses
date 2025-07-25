<?php
// Database setup script to ensure course_comments table exists
require_once 'config/database.php';

echo "<h2>Database Setup for Course Comments</h2>\n";

try {
    // Check if course_comments table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'course_comments'");
    $table_exists = $stmt->fetch();
    
    if (!$table_exists) {
        echo "<p>❌ course_comments table does not exist. Creating it...</p>\n";
        
        // Create the table
        $sql = "
        CREATE TABLE course_comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            course_slug VARCHAR(100) NOT NULL,
            course_name VARCHAR(255) NOT NULL,
            rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
            comment_text TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            is_approved BOOLEAN DEFAULT TRUE,
            
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_course_slug (course_slug),
            INDEX idx_user_id (user_id),
            INDEX idx_created_at (created_at)
        )";
        
        $pdo->exec($sql);
        echo "<p>✅ course_comments table created successfully!</p>\n";
    } else {
        echo "<p>✅ course_comments table already exists.</p>\n";
    }
    
    // Check table structure
    $stmt = $pdo->query("DESCRIBE course_comments");
    $columns = $stmt->fetchAll();
    echo "<h3>Table Structure:</h3>\n<ul>\n";
    foreach ($columns as $column) {
        echo "<li><strong>{$column['Field']}</strong>: {$column['Type']} {$column['Null']} {$column['Key']} {$column['Default']}</li>\n";
    }
    echo "</ul>\n";
    
    // Check if users table has username column
    $stmt = $pdo->query("DESCRIBE users");
    $user_columns = $stmt->fetchAll();
    $has_username = false;
    foreach ($user_columns as $column) {
        if ($column['Field'] === 'username') {
            $has_username = true;
            break;
        }
    }
    
    if ($has_username) {
        echo "<p>✅ Users table has username column.</p>\n";
    } else {
        echo "<p>❌ Users table missing username column!</p>\n";
    }
    
    echo "<h3>Recent Course Comments:</h3>\n";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM course_comments");
    $count = $stmt->fetch();
    echo "<p>Total course comments: {$count['total']}</p>\n";
    
    if ($count['total'] > 0) {
        $stmt = $pdo->query("
            SELECT cc.course_name, cc.rating, cc.comment_text, u.username, cc.created_at 
            FROM course_comments cc 
            JOIN users u ON cc.user_id = u.id 
            ORDER BY cc.created_at DESC 
            LIMIT 5
        ");
        $recent_comments = $stmt->fetchAll();
        echo "<ul>\n";
        foreach ($recent_comments as $comment) {
            echo "<li><strong>{$comment['username']}</strong> rated {$comment['course_name']}: {$comment['rating']}/5 - " . substr($comment['comment_text'], 0, 50) . "... <em>({$comment['created_at']})</em></li>\n";
        }
        echo "</ul>\n";
    }
    
    echo "<p style='color: green;'><strong>Database setup completed successfully!</strong></p>\n";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Database error:</strong> " . $e->getMessage() . "</p>\n";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; }
h2, h3 { color: #2c5234; }
p { margin: 10px 0; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
</style>