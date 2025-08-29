<?php
// Test init.php step by step
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing init.php Step by Step</h1>";

// Step 1: Performance start
echo "<p>1. Starting performance monitoring...</p>";
try {
    require_once 'includes/performance.php';
    Performance::start();
    echo "<p>✓ Performance monitoring started</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR starting performance: " . $e->getMessage() . "</p>";
    exit;
}

// Step 2: Compression
echo "<p>2. Enabling compression...</p>";
try {
    Performance::enableCompression();
    echo "<p>✓ Compression enabled</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR enabling compression: " . $e->getMessage() . "</p>";
    exit;
}

// Step 3: Session security
echo "<p>3. Loading session security...</p>";
try {
    require_once 'includes/session-security.php';
    echo "<p>✓ Session security loaded</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR loading session security: " . $e->getMessage() . "</p>";
    exit;
}

// Step 4: Start session
echo "<p>4. Starting secure session...</p>";
try {
    SecureSession::start();
    echo "<p>✓ Secure session started</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR starting session (this is usually OK): " . $e->getMessage() . "</p>";
    // Don't exit here - this is expected to sometimes fail
}

// Step 5: Database cache
echo "<p>5. Loading database cache...</p>";
try {
    require_once 'includes/database-cache.php';
    echo "<p>✓ Database cache loaded</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR loading database cache: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

// Step 6: Database connection
echo "<p>6. Loading database connection...</p>";
try {
    require_once 'config/database.php';
    echo "<p>✓ Database connection loaded</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR loading database: " . $e->getMessage() . "</p>";
    exit;
}

// Step 7: Create cached database instance
echo "<p>7. Creating cached database instance...</p>";
try {
    $dbCache = new DatabaseCache($pdo);
    echo "<p>✓ DatabaseCache instance created</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR creating DatabaseCache: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

// Step 8: Session data
echo "<p>8. Getting session data...</p>";
try {
    $is_logged_in = SecureSession::isLoggedIn();
    $user_id = SecureSession::get('user_id');
    $username = SecureSession::get('username');
    echo "<p>✓ Session data retrieved (logged in: " . ($is_logged_in ? 'yes' : 'no') . ")</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR getting session data: " . $e->getMessage() . "</p>";
    exit;
}

// Step 9: Performance headers
echo "<p>9. Testing performance headers...</p>";
try {
    if (!headers_sent()) {
        Performance::sendPerformanceHeaders();
        echo "<p>✓ Performance headers sent</p>";
    } else {
        echo "<p>ⓘ Headers already sent (normal)</p>";
    }
} catch (Throwable $e) {
    echo "<p>✗ ERROR sending headers: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>All init.php steps completed successfully!</h2>";
echo "<p>The issue might be in the specific order of operations or in profile-helpers.php/seo.php</p>";
?>