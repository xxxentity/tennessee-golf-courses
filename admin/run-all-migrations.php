<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

// Track migration results
$results = [];
$hasErrors = false;

// Function to run SQL file
function runSQLFile($pdo, $filename, $description) {
    global $results, $hasErrors;
    
    $filepath = __DIR__ . '/../database/' . $filename;
    
    if (!file_exists($filepath)) {
        $results[] = ['file' => $filename, 'status' => 'error', 'message' => 'File not found'];
        $hasErrors = true;
        return false;
    }
    
    try {
        $sql = file_get_contents($filepath);
        
        // Split by semicolons but be careful with procedures/functions
        $statements = array_filter(array_map('trim', preg_split('/;\s*$/m', $sql)));
        
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        foreach ($statements as $statement) {
            if (empty($statement)) continue;
            
            try {
                $pdo->exec($statement);
                $successCount++;
            } catch (PDOException $e) {
                $errorCount++;
                $errors[] = substr($e->getMessage(), 0, 200);
            }
        }
        
        if ($errorCount === 0) {
            $results[] = [
                'file' => $filename,
                'status' => 'success',
                'message' => "$description - $successCount statements executed successfully"
            ];
            return true;
        } else {
            $results[] = [
                'file' => $filename,
                'status' => 'partial',
                'message' => "$description - $successCount succeeded, $errorCount failed",
                'errors' => $errors
            ];
            $hasErrors = true;
            return false;
        }
        
    } catch (Exception $e) {
        $results[] = [
            'file' => $filename,
            'status' => 'error',
            'message' => $description . ' - Error: ' . $e->getMessage()
        ];
        $hasErrors = true;
        return false;
    }
}

// Define migrations to run in order
$migrations = [
    ['file' => 'enhance_auth_security.sql', 'description' => 'Enhanced Authentication Security'],
    ['file' => 'fix-admin-security-schema.sql', 'description' => 'Admin Security Tables (Fixed)'],
    ['file' => 'api-security-schema.sql', 'description' => 'API Security Tables'],
    ['file' => 'add_login_security.sql', 'description' => 'Login Security Features'],
    ['file' => 'add_email_verification.sql', 'description' => 'Email Verification System']
];

// Run migrations if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_migrations'])) {
    foreach ($migrations as $migration) {
        runSQLFile($pdo, $migration['file'], $migration['description']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Run Database Migrations - Admin - Tennessee Golf Courses</title>
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
        
        .migration-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .migration-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .migration-description {
            color: #6b7280;
            margin-bottom: 2rem;
        }
        
        .migration-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem 0;
        }
        
        .migration-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        
        .migration-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
        }
        
        .icon-pending { background: #f3f4f6; color: #6b7280; }
        .icon-success { background: #dcfce7; color: #16a34a; }
        .icon-error { background: #fee2e2; color: #dc2626; }
        .icon-partial { background: #fef3c7; color: #d97706; }
        
        .migration-info {
            flex: 1;
        }
        
        .migration-name {
            font-weight: 600;
            color: #1f2937;
        }
        
        .migration-status {
            font-size: 0.875rem;
            color: #6b7280;
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
        
        .btn-primary:hover {
            background: #1d4ed8;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .results-section {
            margin-top: 2rem;
        }
        
        .result-item {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: start;
            gap: 1rem;
        }
        
        .result-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
        }
        
        .result-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
        }
        
        .result-partial {
            background: #fef3c7;
            border: 1px solid #fde68a;
        }
        
        .error-details {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: rgba(0,0,0,0.05);
            border-radius: 4px;
            font-size: 0.875rem;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <div>
                <h1><i class="fas fa-database"></i> Database Migrations</h1>
                <div style="font-size: 0.9rem; opacity: 0.8;">
                    <a href="/admin/dashboard" style="color: rgba(255,255,255,0.8);">Dashboard</a>
                    <i class="fas fa-chevron-right" style="margin: 0 0.5rem;"></i>
                    <span>Migrations</span>
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
        <div class="migration-card">
            <h2 class="migration-title">Security Database Migrations</h2>
            <p class="migration-description">
                Run the necessary database migrations to enable all security features. 
                These migrations will create tables for API security, enhanced authentication, 
                and security logging.
            </p>
            
            <?php if (!empty($results)): ?>
                <div class="results-section">
                    <h3 style="margin-bottom: 1rem;">Migration Results:</h3>
                    <?php foreach ($results as $result): ?>
                        <div class="result-item result-<?php echo $result['status']; ?>">
                            <i class="fas fa-<?php echo $result['status'] === 'success' ? 'check-circle' : ($result['status'] === 'error' ? 'times-circle' : 'exclamation-triangle'); ?>"></i>
                            <div>
                                <strong><?php echo htmlspecialchars($result['file']); ?></strong><br>
                                <?php echo htmlspecialchars($result['message']); ?>
                                <?php if (!empty($result['errors'])): ?>
                                    <div class="error-details">
                                        <?php foreach ($result['errors'] as $error): ?>
                                            <?php echo htmlspecialchars($error); ?><br>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (!$hasErrors): ?>
                    <div class="alert alert-success" style="margin-top: 1rem;">
                        <i class="fas fa-check-circle"></i>
                        All migrations completed successfully! Your security system is now fully activated.
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Important:</strong> This will modify your database structure. Make sure you have a backup before proceeding.
                </div>
                
                <ul class="migration-list">
                    <?php foreach ($migrations as $migration): ?>
                        <li class="migration-item">
                            <div class="migration-icon icon-pending">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="migration-info">
                                <div class="migration-name"><?php echo $migration['description']; ?></div>
                                <div class="migration-status"><?php echo $migration['file']; ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <form method="POST" onsubmit="return confirm('Are you sure you want to run these migrations? This will modify your database.');">
                    <button type="submit" name="run_migrations" value="1" class="btn btn-primary">
                        <i class="fas fa-play"></i>
                        Run All Migrations
                    </button>
                    <a href="/admin/dashboard" class="btn btn-secondary" style="margin-left: 1rem;">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>