<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once '../config/database.php';
    
    echo "Checking users table structure:\n\n";
    
    // Get table structure
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    
    echo "Columns in users table:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
    echo "\nTotal columns: " . count($columns) . "\n";
    
    // Check if last_login exists
    $hasLastLogin = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'last_login') {
            $hasLastLogin = true;
            break;
        }
    }
    
    echo "\nHas last_login column: " . ($hasLastLogin ? 'YES' : 'NO') . "\n";
    
    // Get sample user data
    echo "\nSample user data:\n";
    $stmt = $pdo->query("SELECT * FROM users LIMIT 1");
    $sampleUser = $stmt->fetch();
    
    if ($sampleUser) {
        foreach ($sampleUser as $key => $value) {
            if (!is_numeric($key)) {
                echo "- $key: " . (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) . "\n";
            }
        }
    } else {
        echo "No users found in table.\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>