<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

// Get security statistics
try {
    // Recent login attempts (last 24 hours)
    $stmt = $pdo->query("
        SELECT COUNT(*) as total_attempts,
               SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful,
               SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
        FROM login_attempts 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ");
    $loginStats = $stmt->fetch() ?: ['total_attempts' => 0, 'successful' => 0, 'failed' => 0];
    
    // Active sessions
    $stmt = $pdo->query("SELECT COUNT(DISTINCT user_id) as active_sessions FROM user_sessions WHERE expires_at > NOW()");
    $activeSessions = $stmt->fetchColumn() ?: 0;
    
    // Recent security events (if API security logs exist)
    $securityEvents = [];
    try {
        $stmt = $pdo->query("
            SELECT event_type, severity, COUNT(*) as count 
            FROM api_security_logs 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY event_type, severity 
            ORDER BY count DESC 
            LIMIT 10
        ");
        $securityEvents = $stmt->fetchAll();
    } catch (PDOException $e) {
        // API security logs table might not exist yet
    }
    
    // Recent failed login attempts
    $stmt = $pdo->query("
        SELECT email, ip_address, created_at, failure_reason
        FROM login_attempts 
        WHERE status = 'failed' 
        ORDER BY created_at DESC 
        LIMIT 20
    ");
    $recentFailures = $stmt->fetchAll();
    
    // User account security issues
    $stmt = $pdo->query("
        SELECT username, email, created_at, last_login
        FROM users 
        WHERE (last_login IS NULL AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY))
           OR (last_login < DATE_SUB(NOW(), INTERVAL 90 DAY))
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $suspiciousAccounts = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $loginStats = ['total_attempts' => 0, 'successful' => 0, 'failed' => 0];
    $activeSessions = 0;
    $recentFailures = [];
    $suspiciousAccounts = [];
    error_log("Security dashboard error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Monitor - Admin - Tennessee Golf Courses</title>
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
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .security-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .security-card.critical {
            border-left: 4px solid #dc2626;
        }
        
        .security-card.warning {
            border-left: 4px solid #f59e0b;
        }
        
        .security-card.success {
            border-left: 4px solid #10b981;
        }
        
        .security-card.info {
            border-left: 4px solid #3b82f6;
        }
        
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }
        
        .icon-success { background: #10b981; }
        .icon-warning { background: #f59e0b; }
        .icon-critical { background: #dc2626; }
        .icon-info { background: #3b82f6; }
        
        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .card-description {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .data-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }
        
        .table tbody tr:hover {
            background: #f9fafb;
        }
        
        .severity-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .severity-critical {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .severity-warning {
            background: #fef3c7;
            color: #d97706;
        }
        
        .severity-info {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .security-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .action-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .action-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .action-description {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
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
        
        .refresh-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 1.25rem;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
            transition: all 0.3s ease;
        }
        
        .refresh-btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .admin-container {
                padding: 1rem;
            }
            
            .security-grid {
                grid-template-columns: 1fr;
            }
            
            .security-actions {
                grid-template-columns: 1fr;
            }
            
            .table {
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <div>
                <h1><i class="fas fa-shield-alt"></i> Security Monitor</h1>
                <div class="breadcrumb">
                    <a href="/admin/dashboard" style="color: rgba(255,255,255,0.8);">Dashboard</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Security</span>
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
        <!-- Security Overview -->
        <div class="security-grid">
            <div class="security-card <?php echo $loginStats['failed'] > 10 ? 'critical' : ($loginStats['failed'] > 5 ? 'warning' : 'success'); ?>">
                <div class="card-header">
                    <div class="card-title">Login Attempts (24h)</div>
                    <div class="card-icon <?php echo $loginStats['failed'] > 10 ? 'icon-critical' : ($loginStats['failed'] > 5 ? 'icon-warning' : 'icon-success'); ?>">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                </div>
                <div class="card-value"><?php echo number_format($loginStats['total_attempts']); ?></div>
                <div class="card-description">
                    <?php echo $loginStats['successful']; ?> successful, <?php echo $loginStats['failed']; ?> failed
                </div>
            </div>
            
            <div class="security-card info">
                <div class="card-header">
                    <div class="card-title">Active Sessions</div>
                    <div class="card-icon icon-info">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="card-value"><?php echo number_format($activeSessions); ?></div>
                <div class="card-description">Currently logged in users</div>
            </div>
            
            <div class="security-card <?php echo count($suspiciousAccounts) > 5 ? 'warning' : 'success'; ?>">
                <div class="card-header">
                    <div class="card-title">Suspicious Accounts</div>
                    <div class="card-icon <?php echo count($suspiciousAccounts) > 5 ? 'icon-warning' : 'icon-success'; ?>">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="card-value"><?php echo count($suspiciousAccounts); ?></div>
                <div class="card-description">Inactive or dormant accounts</div>
            </div>
            
            <div class="security-card info">
                <div class="card-header">
                    <div class="card-title">Security Events</div>
                    <div class="card-icon icon-info">
                        <i class="fas fa-list-alt"></i>
                    </div>
                </div>
                <div class="card-value"><?php echo count($securityEvents); ?></div>
                <div class="card-description">Event types in last 24h</div>
            </div>
        </div>
        
        <!-- Recent Failed Login Attempts -->
        <?php if (!empty($recentFailures)): ?>
            <h2 class="section-title">
                <i class="fas fa-exclamation-circle" style="color: #dc2626;"></i>
                Recent Failed Login Attempts
            </h2>
            <div class="data-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>IP Address</th>
                            <th>Reason</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($recentFailures, 0, 10) as $failure): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($failure['email']); ?></td>
                                <td>
                                    <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem;">
                                        <?php echo htmlspecialchars($failure['ip_address']); ?>
                                    </code>
                                </td>
                                <td><?php echo htmlspecialchars($failure['failure_reason'] ?? 'Invalid credentials'); ?></td>
                                <td><?php echo date('M j, g:i A', strtotime($failure['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <!-- Security Events -->
        <?php if (!empty($securityEvents)): ?>
            <h2 class="section-title">
                <i class="fas fa-chart-bar" style="color: #3b82f6;"></i>
                Security Events (24h)
            </h2>
            <div class="data-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event Type</th>
                            <th>Severity</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($securityEvents as $event): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(str_replace('_', ' ', ucwords($event['event_type'], '_'))); ?></td>
                                <td>
                                    <span class="severity-badge severity-<?php echo $event['severity']; ?>">
                                        <?php echo ucfirst($event['severity']); ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($event['count']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <!-- Suspicious Accounts -->
        <?php if (!empty($suspiciousAccounts)): ?>
            <h2 class="section-title">
                <i class="fas fa-user-clock" style="color: #f59e0b;"></i>
                Suspicious Accounts
            </h2>
            <div class="data-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Last Login</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suspiciousAccounts as $account): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($account['username']); ?></td>
                                <td><?php echo htmlspecialchars($account['email']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($account['created_at'])); ?></td>
                                <td>
                                    <?php if ($account['last_login']): ?>
                                        <?php echo date('M j, Y', strtotime($account['last_login'])); ?>
                                    <?php else: ?>
                                        <span style="color: #dc2626; font-weight: 600;">Never</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!$account['last_login']): ?>
                                        <span class="severity-badge severity-critical">Never Logged In</span>
                                    <?php else: ?>
                                        <span class="severity-badge severity-warning">Dormant</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <!-- Security Actions -->
        <div class="security-actions">
            <div class="action-card">
                <div class="action-title">View Activity Logs</div>
                <div class="action-description">
                    Review detailed system logs and user activity for comprehensive security monitoring.
                </div>
                <a href="/admin/logs" class="btn btn-primary">
                    <i class="fas fa-list-alt"></i> View Logs
                </a>
            </div>
            
            <div class="action-card">
                <div class="action-title">Security Settings</div>
                <div class="action-description">
                    Configure security policies, password requirements, and authentication settings.
                </div>
                <a href="/admin/settings" class="btn btn-primary">
                    <i class="fas fa-cogs"></i> Security Settings
                </a>
            </div>
            
            <div class="action-card">
                <div class="action-title">User Management</div>
                <div class="action-description">
                    Manage user accounts, permissions, and handle security incidents.
                </div>
                <a href="/admin/users" class="btn btn-primary">
                    <i class="fas fa-users"></i> Manage Users
                </a>
            </div>
        </div>
    </main>
    
    <!-- Auto-refresh button -->
    <button class="refresh-btn" onclick="location.reload()" title="Refresh Security Data">
        <i class="fas fa-sync-alt"></i>
    </button>
    
    <script>
        // Auto-refresh every 5 minutes
        setTimeout(() => {
            location.reload();
        }, 300000);
        
        // Add loading state to refresh button
        document.querySelector('.refresh-btn').addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        });
    </script>
</body>
</html>