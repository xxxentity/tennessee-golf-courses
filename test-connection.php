<?php
// Test database connection
require_once 'config/database.php';

echo "<h2>Database Connection Test</h2>";

try {
    // Test the connection
    $stmt = $pdo->query("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = 'entityadmin_tgc_main'");
    $result = $stmt->fetch();
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    echo "<p>Found " . $result['table_count'] . " tables in database.</p>";
    
    // List the tables we created
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Your Tables:</h3><ul>";
    foreach($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Connection failed: " . $e->getMessage() . "</p>";
}
?>