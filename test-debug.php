<?php
// Minimal test file to identify the issue
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Test</h1>";
echo "<p>PHP is working</p>";

// Test each include one by one
echo "<h2>Testing includes:</h2>";

// Test 1: Session security
echo "<p>1. Testing session-security.php...</p>";
try {
    require_once 'includes/session-security.php';
    echo "<p>✓ session-security.php loaded</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR in session-security.php: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

// Test 2: Database
echo "<p>2. Testing database.php...</p>";
try {
    require_once 'config/database.php';
    echo "<p>✓ database.php loaded</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR in database.php: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

// Test 3: Performance
echo "<p>3. Testing performance.php...</p>";
try {
    require_once 'includes/performance.php';
    echo "<p>✓ performance.php loaded</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR in performance.php: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

// Test 4: Cache
echo "<p>4. Testing cache.php...</p>";
try {
    require_once 'includes/cache.php';
    echo "<p>✓ cache.php loaded</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR in cache.php: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

echo "<h2>All basic files loaded successfully!</h2>";
echo "<p>The issue is likely in init.php or one of its dependencies.</p>";
?>