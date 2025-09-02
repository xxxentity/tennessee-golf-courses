<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

// Get filter parameters
$logType = $_GET['type'] ?? '';
$severity = $_GET['severity'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 50;
$offset = ($page - 1) * $limit;

// Build query conditions
$conditions = [];
$params = [];

if (!empty($logType)) {
    $conditions[] = "log_type = ?";
    $params[] = $logType;
}

if (!empty($severity)) {
    $conditions[] = "severity = ?";
    $params[] = $severity;
}

if (!empty($dateFrom)) {
    $conditions[] = "created_at >= ?";
    $params[] = $dateFrom . ' 00:00:00';
}

if (!empty($dateTo)) {
    $conditions[] = "created_at <= ?";
    $params[] = $dateTo . ' 23:59:59';
}

if (!empty($search)) {
    $conditions[] = "(message LIKE ? OR details LIKE ? OR ip_address LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Create a comprehensive logs view from multiple sources
$logSources = [];

// 1. Login attempts
try {
    $loginSql = "
        SELECT 'login_attempt' as log_type, 
               CASE WHEN status = 'success' THEN 'info' ELSE 'warning' END as severity,
               CONCAT('Login attempt for: ', email) as message,
               JSON_OBJECT('email', email, 'status', status, 'reason', failure_reason) as details,
               ip_address,
               created_at,
               user_id
        FROM login_attempts 
        ORDER BY created_at DESC 
        LIMIT 1000
    ";
    $loginLogs = $pdo->query($loginSql)->fetchAll();
    $logSources = array_merge($logSources, $loginLogs);
} catch (PDOException $e) {
    // Table might not exist
}

// 2. API security logs
try {
    $apiSql = "
        SELECT 'api_security' as log_type,
               severity,
               CONCAT('API Security: ', event_type) as message,
               details,
               ip_address,
               created_at,
               user_id
        FROM api_security_logs 
        ORDER BY created_at DESC 
        LIMIT 1000
    ";
    $apiLogs = $pdo->query($apiSql)->fetchAll();
    $logSources = array_merge($logSources, $apiLogs);
} catch (PDOException $e) {
    // Table might not exist
}

// 3. User registration logs
try {
    $userSql = "
        SELECT 'user_registration' as log_type,
               'info' as severity,
               CONCAT('New user registered: ', username) as message,
               JSON_OBJECT('username', username, 'email', email) as details,
               '0.0.0.0' as ip_address,
               created_at,
               id as user_id
        FROM users 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ORDER BY created_at DESC
    ";
    $userLogs = $pdo->query($userSql)->fetchAll();
    $logSources = array_merge($logSources, $userLogs);
} catch (PDOException $e) {
    // Handle error
}

// 4. Newsletter activities
try {
    $newsletterSql = "
        SELECT 'newsletter' as log_type,
               'info' as severity,
               CONCAT('Newsletter subscription: ', email) as message,
               JSON_OBJECT('email', email, 'confirmed', confirmed) as details,
               COALESCE(ip_address, '0.0.0.0') as ip_address,
               created_at,
               NULL as user_id
        FROM newsletter_subscribers 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ORDER BY created_at DESC
    ";
    $newsletterLogs = $pdo->query($newsletterSql)->fetchAll();
    $logSources = array_merge($logSources, $newsletterLogs);
} catch (PDOException $e) {
    // Handle error
}

// Sort all logs by date
usort($logSources, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

// Apply filters
$filteredLogs = $logSources;

if (!empty($logType)) {
    $filteredLogs = array_filter($filteredLogs, fn($log) => $log['log_type'] === $logType);
}

if (!empty($severity)) {
    $filteredLogs = array_filter($filteredLogs, fn($log) => $log['severity'] === $severity);
}

if (!empty($dateFrom)) {
    $filteredLogs = array_filter($filteredLogs, fn($log) => strtotime($log['created_at']) >= strtotime($dateFrom));
}

if (!empty($dateTo)) {
    $filteredLogs = array_filter($filteredLogs, fn($log) => strtotime($log['created_at']) <= strtotime($dateTo . ' 23:59:59'));
}

if (!empty($search)) {
    $filteredLogs = array_filter($filteredLogs, function($log) use ($search) {
        return stripos($log['message'], $search) !== false || 
               stripos($log['details'], $search) !== false ||
               stripos($log['ip_address'], $search) !== false;
    });
}

$totalLogs = count($filteredLogs);
$totalPages = ceil($totalLogs / $limit);
$paginatedLogs = array_slice($filteredLogs, $offset, $limit);

// Get summary statistics
$logTypeStats = [];
$severityStats = [];

foreach ($logSources as $log) {
    $logTypeStats[$log['log_type']] = ($logTypeStats[$log['log_type']] ?? 0) + 1;
    $severityStats[$log['severity']] = ($severityStats[$log['severity']] ?? 0) + 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - Admin - Tennessee Golf Courses</title>
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .filters-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }
        
        .filter-input,
        .filter-select {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
        }
        
        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
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
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .logs-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            overflow: hidden;
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
        
        .log-type-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .type-login_attempt {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .type-api_security {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .type-user_registration {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .type-newsletter {
            background: #fef3c7;
            color: #d97706;
        }
        
        .severity-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .severity-info {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .severity-warning {
            background: #fef3c7;
            color: #d97706;
        }
        
        .severity-error {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .severity-critical {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .log-details {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .log-details:hover {
            color: #2563eb;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            text-decoration: none;
            color: #374151;
        }
        
        .pagination .current {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                padding: 1rem;
            }
            
            .filters-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-actions {
                justify-content: stretch;
            }
            
            .btn {
                justify-content: center;
            }
            
            .table {
                font-size: 0.75rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <div>
                <h1><i class="fas fa-list-alt"></i> Activity Logs</h1>
                <div class="breadcrumb">
                    <a href="/admin/dashboard" style="color: rgba(255,255,255,0.8);">Dashboard</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Logs</span>
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
        <!-- Statistics Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($totalLogs); ?></div>
                <div class="stat-label">Total Log Entries</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($logTypeStats); ?></div>
                <div class="stat-label">Log Types</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $severityStats['warning'] ?? 0; ?></div>
                <div class="stat-label">Warning Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo ($severityStats['error'] ?? 0) + ($severityStats['critical'] ?? 0); ?></div>
                <div class="stat-label">Error Events</div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters-section">
            <form method="GET">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Log Type</label>
                        <select name="type" class="filter-select">
                            <option value="">All Types</option>
                            <?php foreach (array_keys($logTypeStats) as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo $logType === $type ? 'selected' : ''; ?>>
                                    <?php echo ucwords(str_replace('_', ' ', $type)); ?> (<?php echo $logTypeStats[$type]; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Severity</label>
                        <select name="severity" class="filter-select">
                            <option value="">All Severities</option>
                            <option value="info" <?php echo $severity === 'info' ? 'selected' : ''; ?>>Info</option>
                            <option value="warning" <?php echo $severity === 'warning' ? 'selected' : ''; ?>>Warning</option>
                            <option value="error" <?php echo $severity === 'error' ? 'selected' : ''; ?>>Error</option>
                            <option value="critical" <?php echo $severity === 'critical' ? 'selected' : ''; ?>>Critical</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Date From</label>
                        <input type="date" name="date_from" class="filter-input" value="<?php echo $dateFrom; ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Date To</label>
                        <input type="date" name="date_to" class="filter-input" value="<?php echo $dateTo; ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <input type="text" name="search" class="filter-input" 
                               placeholder="Search logs..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                    <a href="/admin/logs" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Logs Table -->
        <div class="logs-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Severity</th>
                        <th>Message</th>
                        <th>IP Address</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paginatedLogs as $log): ?>
                        <tr>
                            <td><?php echo date('M j, g:i A', strtotime($log['created_at'])); ?></td>
                            <td>
                                <span class="log-type-badge type-<?php echo $log['log_type']; ?>">
                                    <?php echo ucwords(str_replace('_', ' ', $log['log_type'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="severity-badge severity-<?php echo $log['severity']; ?>">
                                    <?php echo ucfirst($log['severity']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($log['message']); ?></td>
                            <td>
                                <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">
                                    <?php echo htmlspecialchars($log['ip_address']); ?>
                                </code>
                            </td>
                            <td>
                                <div class="log-details" 
                                     onclick="alert('<?php echo htmlspecialchars(json_encode(json_decode($log['details'], true), JSON_PRETTY_PRINT)); ?>')"
                                     title="Click to view full details">
                                    <?php 
                                    $details = json_decode($log['details'], true);
                                    if ($details && is_array($details)) {
                                        echo htmlspecialchars(implode(', ', array_slice(array_keys($details), 0, 3)));
                                        if (count($details) > 3) echo '...';
                                    } else {
                                        echo htmlspecialchars(substr($log['details'], 0, 50));
                                        if (strlen($log['details']) > 50) echo '...';
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&<?php echo http_build_query(array_filter($_GET, fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY)); ?>">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&<?php echo http_build_query(array_filter($_GET, fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY)); ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&<?php echo http_build_query(array_filter($_GET, fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY)); ?>">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($paginatedLogs)): ?>
            <div style="text-align: center; padding: 3rem; color: #6b7280;">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <h3>No logs found</h3>
                <p>Try adjusting your filters or check back later.</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>