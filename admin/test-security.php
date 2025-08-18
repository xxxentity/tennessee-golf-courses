<?php
require_once '../includes/admin-security-simple.php';
require_once '../includes/session-security.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

// Test results
$tests = [];
$allPassed = true;

// Function to test database table existence
function testTableExists($pdo, $tableName) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$tableName'");
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}

// Function to test if column exists
function testColumnExists($pdo, $tableName, $columnName) {
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM $tableName LIKE '$columnName'");
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}

// Function to test if class/method exists
function testClassMethod($className, $methodName) {
    return class_exists($className) && method_exists($className, $methodName);
}

// Test 1: Database Tables
$tests[] = [
    'name' => 'Admin Security Tables',
    'tests' => [
        'admin_login_attempts' => testTableExists($pdo, 'admin_login_attempts'),
        'admin_security_log' => testTableExists($pdo, 'admin_security_log'),
        'admin_activity_log' => testTableExists($pdo, 'admin_activity_log'),
        'admin_ip_whitelist' => testTableExists($pdo, 'admin_ip_whitelist'),
        'admin_sessions' => testTableExists($pdo, 'admin_sessions'),
        'admin_settings' => testTableExists($pdo, 'admin_settings'),
        'admin_notifications' => testTableExists($pdo, 'admin_notifications'),
        'system_security_settings' => testTableExists($pdo, 'system_security_settings')
    ]
];

// Test 2: API Security Tables
$tests[] = [
    'name' => 'API Security Tables',
    'tests' => [
        'api_tokens' => testTableExists($pdo, 'api_tokens'),
        'api_rate_limits' => testTableExists($pdo, 'api_rate_limits'),
        'api_requests' => testTableExists($pdo, 'api_requests'),
        'api_blacklist' => testTableExists($pdo, 'api_blacklist')
    ]
];

// Test 3: User Security Columns
$tests[] = [
    'name' => 'User Security Columns',
    'tests' => [
        'login_attempts' => testColumnExists($pdo, 'users', 'login_attempts'),
        'account_locked_until' => testColumnExists($pdo, 'users', 'account_locked_until'),
        'password_reset_token' => testColumnExists($pdo, 'users', 'password_reset_token'),
        'password_reset_expires' => testColumnExists($pdo, 'users', 'password_reset_expires'),
        'email_verification_token' => testColumnExists($pdo, 'users', 'email_verification_token'),
        'email_verified' => testColumnExists($pdo, 'users', 'email_verified')
    ]
];

// Test 4: Security Classes
$tests[] = [
    'name' => 'Security Classes & Methods',
    'tests' => [
        'AdminSecurity::authenticateAdmin' => testClassMethod('AdminSecurity', 'authenticateAdmin'),
        'AdminSecurity::requireAdminAuth' => testClassMethod('AdminSecurity', 'requireAdminAuth'),
        'AdminSecurity::getCurrentAdmin' => testClassMethod('AdminSecurity', 'getCurrentAdmin'),
        'SecureSession::start' => testClassMethod('SecureSession', 'start'),
        'SecureSession::login' => testClassMethod('SecureSession', 'login'),
        'SecureSession::logout' => testClassMethod('SecureSession', 'logout')
    ]
];

// Test 5: File Existence
$securityFiles = [
    '../includes/admin-security-simple.php',
    '../includes/auth-security.php',
    '../includes/session-security.php',
    '../includes/api-security.php',
    '../includes/input-validation.php',
    '../includes/output-security.php'
];

$fileTests = [];
foreach ($securityFiles as $file) {
    $fileName = basename($file);
    $fileTests[$fileName] = file_exists($file);
}

$tests[] = [
    'name' => 'Security Files',
    'tests' => $fileTests
];

// Test 6: System Security Settings
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM system_security_settings");
    $settingsCount = $stmt->fetch()['count'];
    
    $tests[] = [
        'name' => 'System Security Settings',
        'tests' => [
            'settings_populated' => $settingsCount >= 9,
            'settings_count' => $settingsCount
        ]
    ];
} catch (Exception $e) {
    $tests[] = [
        'name' => 'System Security Settings',
        'tests' => [
            'settings_accessible' => false,
            'error' => $e->getMessage()
        ]
    ];
}

// Calculate overall results
foreach ($tests as $testGroup) {
    foreach ($testGroup['tests'] as $result) {
        if ($result === false) {
            $allPassed = false;
            break 2;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security System Test - Admin - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .admin-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .test-results {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .test-header {
            padding: 2rem;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }
        
        .test-header.failed {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        
        .test-body {
            padding: 2rem;
        }
        
        .test-group {
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .test-group-header {
            background: #f9fafb;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
        }
        
        .test-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .test-item:last-child {
            border-bottom: none;
        }
        
        .test-name {
            font-family: monospace;
            color: #374151;
        }
        
        .test-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }
        
        .status-pass {
            color: #059669;
        }
        
        .status-fail {
            color: #dc2626;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-success {
            background: #059669;
            color: white;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <div>
                <h1><i class="fas fa-shield-alt"></i> Security System Test</h1>
                <div style="font-size: 0.9rem; opacity: 0.8;">
                    <a href="/admin/dashboard" style="color: rgba(255,255,255,0.8);">Dashboard</a>
                    <i class="fas fa-chevron-right" style="margin: 0 0.5rem;"></i>
                    <span>Security Test</span>
                </div>
            </div>
            <div>
                <span>Welcome, <?php echo htmlspecialchars($currentAdmin['first_name'] ?? 'Admin'); ?>!</span>
                <a href="/admin/logout" style="color: rgba(255,255,255,0.8); margin-left: 1rem;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>
    
    <main class="admin-container">
        <div class="summary-stats">
            <div class="stat-card">
                <div class="stat-number <?php echo $allPassed ? 'status-pass' : 'status-fail'; ?>">
                    <?php echo $allPassed ? 'PASS' : 'FAIL'; ?>
                </div>
                <div class="stat-label">Overall Status</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($tests); ?></div>
                <div class="stat-label">Test Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $totalTests = 0;
                    foreach ($tests as $testGroup) {
                        $totalTests += count($testGroup['tests']);
                    }
                    echo $totalTests;
                    ?>
                </div>
                <div class="stat-label">Total Tests</div>
            </div>
        </div>
        
        <div class="test-results">
            <div class="test-header <?php echo $allPassed ? '' : 'failed'; ?>">
                <h2>
                    <i class="fas fa-<?php echo $allPassed ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                    Security System <?php echo $allPassed ? 'Validation Passed' : 'Issues Detected'; ?>
                </h2>
                <p>
                    <?php if ($allPassed): ?>
                        All security components are properly installed and functional.
                    <?php else: ?>
                        Some security components need attention. Review the details below.
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="test-body">
                <?php foreach ($tests as $testGroup): ?>
                    <div class="test-group">
                        <div class="test-group-header">
                            <i class="fas fa-cog"></i> <?php echo $testGroup['name']; ?>
                        </div>
                        
                        <?php foreach ($testGroup['tests'] as $testName => $result): ?>
                            <div class="test-item">
                                <span class="test-name"><?php echo htmlspecialchars($testName); ?></span>
                                <span class="test-status <?php echo $result ? 'status-pass' : 'status-fail'; ?>">
                                    <i class="fas fa-<?php echo $result ? 'check' : 'times'; ?>"></i>
                                    <?php echo $result ? 'PASS' : 'FAIL'; ?>
                                    <?php if (is_numeric($result)): ?>
                                        (<?php echo $result; ?>)
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                
                <div style="text-align: center; margin-top: 2rem;">
                    <a href="/admin/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                    
                    <a href="?refresh=1" class="btn btn-primary" style="margin-left: 1rem;">
                        <i class="fas fa-sync-alt"></i>
                        Refresh Tests
                    </a>
                    
                    <?php if ($allPassed): ?>
                        <a href="/admin/security" class="btn btn-success" style="margin-left: 1rem;">
                            <i class="fas fa-shield-alt"></i>
                            Security Dashboard
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>