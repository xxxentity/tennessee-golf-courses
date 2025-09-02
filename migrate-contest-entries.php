<?php
// Migration script to create contest_entries table
require_once 'config/database.php';

try {
    // Read the SQL file
    $sql = file_get_contents('database/contest_entries.sql');
    
    if ($sql === false) {
        throw new Exception('Could not read contest_entries.sql file');
    }
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "✅ Contest entries table created successfully!\n";
    echo "✅ Sample data inserted for testing\n";
    
} catch (Exception $e) {
    echo "❌ Error creating contest entries table: " . $e->getMessage() . "\n";
}
?>