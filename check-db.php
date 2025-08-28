<!DOCTYPE html>
<html>
<head>
    <title>Database Column Check</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: 0 auto; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Database Structure Check</h2>
    
    <?php
    require_once 'config/database.php';
    
    try {
        echo "<h3>news_comments table structure:</h3>";
        $stmt = $pdo->query("DESCRIBE news_comments");
        $columns = $stmt->fetchAll();
        
        echo "<pre>";
        foreach ($columns as $col) {
            echo $col['Field'] . " - " . $col['Type'] . " - " . $col['Null'] . " - " . $col['Default'] . "\n";
        }
        echo "</pre>";
        
        // Check specifically for parent_id
        $hasParentId = false;
        foreach ($columns as $col) {
            if ($col['Field'] === 'parent_id') {
                $hasParentId = true;
                break;
            }
        }
        
        if ($hasParentId) {
            echo "<p class='success'>✅ parent_id column EXISTS - threaded comments should work</p>";
        } else {
            echo "<p class='error'>❌ parent_id column MISSING - need to run migration</p>";
            echo "<p><a href='/migrate-comments.php'>Run Migration Now</a></p>";
        }
        
    } catch (PDOException $e) {
        echo "<p class='error'>Database error: " . $e->getMessage() . "</p>";
    }
    ?>
    
    <hr>
    <p><a href="/">← Back to Home</a></p>
</body>
</html>