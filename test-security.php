<?php
/**
 * Quick security test to verify implementations
 */

echo "<h2>Security Implementation Test</h2>\n\n";

// Test 1: Environment Variables
echo "<h3>1. Testing Environment Variables</h3>\n";
try {
    require_once 'config/env.php';
    
    $db_host = env('DB_HOST');
    $db_user = env('DB_USERNAME');
    $db_name = env('DB_DATABASE');
    
    if ($db_host && $db_user && $db_name) {
        echo "✅ Environment variables loaded successfully<br>\n";
        echo "- DB_HOST: " . htmlspecialchars($db_host) . "<br>\n";
        echo "- DB_USERNAME: " . htmlspecialchars($db_user) . "<br>\n";
        echo "- DB_DATABASE: " . htmlspecialchars($db_name) . "<br>\n";
    } else {
        echo "❌ Environment variables not loading properly<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Error loading environment: " . htmlspecialchars($e->getMessage()) . "<br>\n";
}

// Test 2: Database Connection
echo "<h3>2. Testing Database Connection</h3>\n";
try {
    require_once 'config/database.php';
    
    if (isset($pdo) && $pdo instanceof PDO) {
        echo "✅ Database connection successful<br>\n";
        
        // Test a simple query
        $stmt = $pdo->query("SELECT 1 as test");
        $result = $stmt->fetch();
        if ($result && $result['test'] == 1) {
            echo "✅ Database query test successful<br>\n";
        }
    } else {
        echo "❌ Database connection failed<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . htmlspecialchars($e->getMessage()) . "<br>\n";
}

// Test 3: CSRF Protection
echo "<h3>3. Testing CSRF Protection</h3>\n";
try {
    require_once 'includes/csrf.php';
    
    // Generate token
    $token = CSRFProtection::generateToken();
    if ($token && strlen($token) == 64) { // 32 bytes = 64 hex chars
        echo "✅ CSRF token generation successful<br>\n";
        echo "- Token length: " . strlen($token) . " characters<br>\n";
        
        // Test validation
        if (CSRFProtection::validateToken($token)) {
            echo "✅ CSRF token validation successful<br>\n";
        } else {
            echo "❌ CSRF token validation failed<br>\n";
        }
        
        // Test token field generation
        $field = CSRFProtection::getTokenField();
        if (strpos($field, 'csrf_token') !== false && strpos($field, $token) !== false) {
            echo "✅ CSRF token field generation successful<br>\n";
        } else {
            echo "❌ CSRF token field generation failed<br>\n";
        }
        
        // Test invalid token
        if (!CSRFProtection::validateToken('invalid_token')) {
            echo "✅ CSRF properly rejects invalid tokens<br>\n";
        } else {
            echo "❌ CSRF failed to reject invalid token<br>\n";
        }
    } else {
        echo "❌ CSRF token generation failed<br>\n";
    }
} catch (Exception $e) {
    echo "❌ CSRF error: " . htmlspecialchars($e->getMessage()) . "<br>\n";
}

// Test 4: Security Headers
echo "<h3>4. Testing Security Headers</h3>\n";
echo "Security headers should be applied automatically. Check browser developer tools to verify:<br>\n";
echo "- X-Frame-Options: DENY<br>\n";
echo "- X-Content-Type-Options: nosniff<br>\n";
echo "- X-XSS-Protection: 1; mode=block<br>\n";
echo "- Content-Security-Policy: (should be present)<br>\n";

echo "<br><h3>Test Complete!</h3>\n";
echo "You can delete this file after testing: <code>rm test-security.php</code>\n";
?>