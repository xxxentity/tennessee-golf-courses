<?php
require_once '../includes/admin-security-simple.php';
require_once '../includes/performance.php';
require_once '../includes/cache.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

// Handle actions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear_cache'])) {
        $deleted = Cache::clear();
        $message = "Cache cleared! Removed {$deleted} files.";
    } elseif (isset($_POST['cleanup_cache'])) {
        $deleted = Cache::cleanup();
        $message = "Cache cleanup completed! Removed {$deleted} expired files.";
    }
}

// Get performance metrics
$metrics = Performance::getMetrics();
$cacheStats = Cache::getStats();
$queryStats = Performance::getQueryStats();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Monitor - Admin - Tennessee Golf Courses</title>
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
        
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .metric-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
        }
        
        .metric-card h3 {
            color: #374151;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        
        .metric-unit {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .performance-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        
        .btn-danger {
            background: #dc2626;
            color: white;
        }
        
        .btn-warning {
            background: #d97706;
            color: white;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        .query-log {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        
        .query-item {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            font-family: monospace;
            font-size: 0.875rem;
        }
        
        .query-item:last-child {
            border-bottom: none;
        }
        
        .query-time {
            color: #dc2626;
            font-weight: 600;
        }
        
        .query-fast {
            color: #16a34a;
        }
        
        .query-slow {
            color: #dc2626;
        }
        
        .cache-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        
        .status-good { background: #16a34a; }
        .status-warning { background: #d97706; }
        .status-danger { background: #dc2626; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <div>
                <h1><i class="fas fa-tachometer-alt"></i> Performance Monitor</h1>
                <div style="font-size: 0.9rem; opacity: 0.8;">
                    <a href="/admin/dashboard" style="color: rgba(255,255,255,0.8);">Dashboard</a>
                    <i class="fas fa-chevron-right" style="margin: 0 0.5rem;"></i>
                    <span>Performance</span>
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
        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Performance Metrics -->
        <div class="metrics-grid">
            <div class="metric-card">
                <h3><i class="fas fa-clock"></i> Execution Time</h3>
                <div class="metric-value"><?php echo round($metrics['execution_time'] * 1000, 2); ?></div>
                <div class="metric-unit">milliseconds</div>
            </div>
            
            <div class="metric-card">
                <h3><i class="fas fa-memory"></i> Memory Usage</h3>
                <div class="metric-value"><?php echo $metrics['memory_current_mb']; ?></div>
                <div class="metric-unit">MB current</div>
            </div>
            
            <div class="metric-card">
                <h3><i class="fas fa-chart-line"></i> Peak Memory</h3>
                <div class="metric-value"><?php echo $metrics['memory_peak_mb']; ?></div>
                <div class="metric-unit">MB peak</div>
            </div>
            
            <div class="metric-card">
                <h3><i class="fas fa-database"></i> Cache Files</h3>
                <div class="metric-value"><?php echo $cacheStats['total_files']; ?></div>
                <div class="metric-unit"><?php echo $cacheStats['total_size_mb']; ?> MB total</div>
            </div>
        </div>
        
        <!-- Cache Management -->
        <div class="performance-section">
            <h2 class="section-title">
                <i class="fas fa-hdd"></i> Cache Management
            </h2>
            
            <div class="metrics-grid">
                <div class="metric-card">
                    <h3>Valid Cache Files</h3>
                    <div class="metric-value" style="color: #16a34a;"><?php echo $cacheStats['valid_files']; ?></div>
                </div>
                
                <div class="metric-card">
                    <h3>Expired Files</h3>
                    <div class="metric-value" style="color: #dc2626;"><?php echo $cacheStats['expired_files']; ?></div>
                </div>
                
                <div class="metric-card">
                    <h3>Total Size</h3>
                    <div class="metric-value"><?php echo $cacheStats['total_size_mb']; ?></div>
                    <div class="metric-unit">MB</div>
                </div>
                
                <div class="metric-card">
                    <h3>Cache Status</h3>
                    <div style="display: flex; align-items: center; margin-top: 0.5rem;">
                        <?php 
                        $cacheHealth = $cacheStats['expired_files'] / max($cacheStats['total_files'], 1);
                        if ($cacheHealth < 0.1): 
                        ?>
                            <span class="status-indicator status-good"></span> Healthy
                        <?php elseif ($cacheHealth < 0.3): ?>
                            <span class="status-indicator status-warning"></span> Needs Cleanup
                        <?php else: ?>
                            <span class="status-indicator status-danger"></span> Poor
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="cache-actions">
                <form method="POST" style="display: inline;">
                    <button type="submit" name="cleanup_cache" class="btn btn-warning">
                        <i class="fas fa-broom"></i> Cleanup Expired
                    </button>
                </form>
                
                <form method="POST" style="display: inline;" onsubmit="return confirm('This will clear ALL cache files. Continue?')">
                    <button type="submit" name="clear_cache" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Clear All Cache
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Database Query Performance -->
        <?php if ($queryStats): ?>
        <div class="performance-section">
            <h2 class="section-title">
                <i class="fas fa-database"></i> Database Performance
            </h2>
            
            <div class="metrics-grid">
                <div class="metric-card">
                    <h3>Total Queries</h3>
                    <div class="metric-value"><?php echo $queryStats['total_queries']; ?></div>
                </div>
                
                <div class="metric-card">
                    <h3>Total Time</h3>
                    <div class="metric-value"><?php echo $queryStats['total_time']; ?></div>
                    <div class="metric-unit">seconds</div>
                </div>
                
                <div class="metric-card">
                    <h3>Average Time</h3>
                    <div class="metric-value"><?php echo $queryStats['avg_time']; ?></div>
                    <div class="metric-unit">seconds</div>
                </div>
                
                <div class="metric-card">
                    <h3>Slowest Query</h3>
                    <div class="metric-value" style="color: #dc2626;"><?php echo $queryStats['slowest_time']; ?></div>
                    <div class="metric-unit">seconds</div>
                </div>
            </div>
            
            <h3 style="margin: 1.5rem 0 1rem 0;">Recent Queries</h3>
            <div class="query-log">
                <?php foreach (array_reverse($queryStats['queries']) as $query): ?>
                    <div class="query-item">
                        <div style="display: flex; justify-content: between; margin-bottom: 0.5rem;">
                            <span class="query-time <?php echo $query['time'] > 0.1 ? 'query-slow' : 'query-fast'; ?>">
                                <?php echo round($query['time'], 4); ?>s
                            </span>
                            <?php if ($query['results'] !== null): ?>
                                <span style="color: #6b7280; margin-left: auto;">
                                    <?php echo $query['results']; ?> results
                                </span>
                            <?php endif; ?>
                        </div>
                        <div style="color: #374151;">
                            <?php echo htmlspecialchars($query['query']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="/admin/dashboard" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
            
            <button onclick="window.location.reload();" class="btn btn-primary" style="margin-left: 1rem;">
                <i class="fas fa-sync-alt"></i>
                Refresh Metrics
            </button>
        </div>
    </main>
    
    <!-- Auto-refresh every 30 seconds -->
    <script>
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>