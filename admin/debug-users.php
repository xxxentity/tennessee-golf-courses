<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting debug...\n";

try {
    echo "1. Including admin security...\n";
    require_once '../includes/admin-security-simple.php';
    echo "2. Admin security included successfully\n";
    
    echo "3. Including database config...\n";
    require_once '../config/database.php';
    echo "4. Database config included successfully\n";
    
    echo "5. Checking admin auth...\n";
    // Comment out auth check for debugging
    // AdminSecurity::requireAdminAuth();
    echo "6. Admin auth bypassed for debugging\n";
    
    echo "7. Testing database connection...\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    echo "8. Database connection works - found $userCount users\n";
    
    echo "9. Testing admin security methods...\n";
    $testAdmin = ['first_name' => 'Test', 'id' => 1];
    echo "10. Test admin created\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "Debug complete.\n";
?>