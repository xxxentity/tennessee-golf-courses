<!DOCTYPE html>
<html>
<head>
    <title>Comment System Migration</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: 0 auto; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        .step { margin: 10px 0; padding: 10px; background: #f5f5f5; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Comment System Migration</h2>
    <p>This will add threaded comment support to your database.</p>
    
    <?php
    require_once 'config/database.php';
    
    echo "<div class='step'>";
    echo "<h3>Step 1: Adding parent_id to news_comments</h3>";
    
    try {
        // Check if parent_id already exists
        $stmt = $pdo->query("SHOW COLUMNS FROM news_comments LIKE 'parent_id'");
        if (!$stmt->fetch()) {
            echo "<p class='info'>Adding parent_id column...</p>";
            $pdo->exec("ALTER TABLE news_comments ADD COLUMN parent_id INT NULL DEFAULT NULL COMMENT 'Parent comment ID for replies'");
            $pdo->exec("ALTER TABLE news_comments ADD INDEX idx_parent_id (parent_id)");
            echo "<p class='success'>✅ Successfully added parent_id to news_comments</p>";
        } else {
            echo "<p class='info'>parent_id already exists in news_comments</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Error with news_comments: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
    
    echo "<div class='step'>";
    echo "<h3>Step 2: Adding parent_id to course_comments (if exists)</h3>";
    
    try {
        // Check if course_comments table exists
        $tables = $pdo->query("SHOW TABLES LIKE 'course_comments'")->fetchAll();
        if ($tables) {
            $stmt = $pdo->query("SHOW COLUMNS FROM course_comments LIKE 'parent_id'");
            if (!$stmt->fetch()) {
                echo "<p class='info'>Adding parent_id column to course_comments...</p>";
                $pdo->exec("ALTER TABLE course_comments ADD COLUMN parent_id INT NULL DEFAULT NULL COMMENT 'Parent comment ID for replies'");
                $pdo->exec("ALTER TABLE course_comments ADD INDEX idx_parent_id (parent_id)");
                echo "<p class='success'>✅ Successfully added parent_id to course_comments</p>";
            } else {
                echo "<p class='info'>parent_id already exists in course_comments</p>";
            }
        } else {
            echo "<p class='info'>course_comments table doesn't exist - skipping</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Error with course_comments: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
    
    echo "<div class='step'>";
    echo "<h3>Step 3: Testing the migration</h3>";
    try {
        $stmt = $pdo->query("DESCRIBE news_comments");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (in_array('parent_id', $columns)) {
            echo "<p class='success'>✅ Migration successful! Threaded comments are now enabled.</p>";
            echo "<p>You can now:</p>";
            echo "<ul>";
            echo "<li>Reply to comments and they will nest properly</li>";
            echo "<li>See threaded discussions with visual indentation</li>";
            echo "<li>Click on usernames to view profiles</li>";
            echo "</ul>";
        } else {
            echo "<p class='error'>❌ Migration may have failed - parent_id column not found</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Error testing migration: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
    ?>
    
    <hr>
    <p><a href="/">← Back to Home</a> | <a href="/news">Test Comments</a></p>
    
</body>
</html>