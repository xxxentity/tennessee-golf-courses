<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

$testResults = [];

// Test 1: CSRF Token Generation
try {
    require_once '../includes/csrf.php';
    $token = CSRFProtection::generateToken();
    $testResults['csrf_generation'] = !empty($token);
} catch (Exception $e) {
    $testResults['csrf_generation'] = false;
    $testResults['csrf_error'] = $e->getMessage();
}

// Test 2: SecureSession compatibility
try {
    require_once '../includes/session-security.php';
    SecureSession::start();
    $testResults['secure_session'] = true;
} catch (Exception $e) {
    $testResults['secure_session'] = false;
    $testResults['session_error'] = $e->getMessage();
}

// Test 3: AuthSecurity functions
try {
    require_once '../includes/auth-security.php';
    $testResults['auth_security_class'] = class_exists('AuthSecurity');
    $testResults['auth_methods'] = [
        'hashPassword' => method_exists('AuthSecurity', 'hashPassword'),
        'verifyPassword' => method_exists('AuthSecurity', 'verifyPassword'),
        'recordLoginAttempt' => method_exists('AuthSecurity', 'recordLoginAttempt'),
        'getClientIP' => method_exists('AuthSecurity', 'getClientIP')
    ];
} catch (Exception $e) {
    $testResults['auth_security_error'] = $e->getMessage();
}

// Test 4: Database user table structure
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $testResults['user_columns'] = $columns;
    $testResults['required_columns'] = [
        'login_attempts' => in_array('login_attempts', $columns),
        'account_locked_until' => in_array('account_locked_until', $columns),
        'password_reset_token' => in_array('password_reset_token', $columns),
        'email_verified' => in_array('email_verified', $columns)
    ];
} catch (Exception $e) {
    $testResults['db_error'] = $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System Test - Admin - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            padding: 2rem;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }
        
        .test-section {
            margin-bottom: 2rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        
        .test-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .test-result {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .pass { color: #16a34a; }
        .fail { color: #dc2626; }
        
        pre {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 0.875rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem 0.5rem 0 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login System Compatibility Test</h1>
        <p>Testing the enhanced login system components for compatibility.</p>
        
        <div class="test-section">
            <div class="test-title">CSRF Protection</div>
            <div class="test-result">
                <span>Token Generation:</span>
                <span class="<?php echo $testResults['csrf_generation'] ? 'pass' : 'fail'; ?>">
                    <?php echo $testResults['csrf_generation'] ? 'PASS' : 'FAIL'; ?>
                </span>
            </div>
            <?php if (isset($testResults['csrf_error'])): ?>
                <div style="color: #dc2626; font-size: 0.875rem;">
                    Error: <?php echo htmlspecialchars($testResults['csrf_error']); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="test-section">
            <div class="test-title">SecureSession</div>
            <div class="test-result">
                <span>Session Start:</span>
                <span class="<?php echo $testResults['secure_session'] ? 'pass' : 'fail'; ?>">
                    <?php echo $testResults['secure_session'] ? 'PASS' : 'FAIL'; ?>
                </span>
            </div>
            <?php if (isset($testResults['session_error'])): ?>
                <div style="color: #dc2626; font-size: 0.875rem;">
                    Error: <?php echo htmlspecialchars($testResults['session_error']); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="test-section">
            <div class="test-title">Authentication Security</div>
            <?php if (isset($testResults['auth_security_class'])): ?>
                <div class="test-result">
                    <span>AuthSecurity Class:</span>
                    <span class="<?php echo $testResults['auth_security_class'] ? 'pass' : 'fail'; ?>">
                        <?php echo $testResults['auth_security_class'] ? 'PASS' : 'FAIL'; ?>
                    </span>
                </div>
                <?php foreach ($testResults['auth_methods'] as $method => $exists): ?>
                    <div class="test-result">
                        <span><?php echo $method; ?>():</span>
                        <span class="<?php echo $exists ? 'pass' : 'fail'; ?>">
                            <?php echo $exists ? 'PASS' : 'FAIL'; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (isset($testResults['auth_security_error'])): ?>
                <div style="color: #dc2626; font-size: 0.875rem;">
                    Error: <?php echo htmlspecialchars($testResults['auth_security_error']); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="test-section">
            <div class="test-title">Database User Table</div>
            <?php if (isset($testResults['required_columns'])): ?>
                <?php foreach ($testResults['required_columns'] as $column => $exists): ?>
                    <div class="test-result">
                        <span><?php echo $column; ?> column:</span>
                        <span class="<?php echo $exists ? 'pass' : 'fail'; ?>">
                            <?php echo $exists ? 'PASS' : 'FAIL'; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (isset($testResults['db_error'])): ?>
                <div style="color: #dc2626; font-size: 0.875rem;">
                    Error: <?php echo htmlspecialchars($testResults['db_error']); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (isset($testResults['user_columns'])): ?>
            <div class="test-section">
                <div class="test-title">User Table Structure</div>
                <pre><?php echo implode("\n", $testResults['user_columns']); ?></pre>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 2rem;">
            <a href="/login" class="btn">Test Login Page</a>
            <a href="/admin/test-security" class="btn">Security Test</a>
            <a href="/admin/dashboard" class="btn" style="background: #6b7280;">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>