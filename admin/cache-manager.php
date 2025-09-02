<?php
/**
 * Cache Management Interface
 * Simple interface to monitor and clear cache
 */

require_once '../includes/init.php';
require_once '../includes/cache.php';

// Check if user is logged in as admin (basic security)
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied. Admin login required.');
}

// Handle cache operations
$message = '';
$error = '';

if ($_POST) {
    if (isset($_POST['clear_cache'])) {
        $cleared = Cache::clear();
        $message = "Cleared {$cleared} cache files.";
    } elseif (isset($_POST['disable_cache'])) {
        Cache::setEnabled(false);
        $message = "Caching disabled globally.";
    } elseif (isset($_POST['enable_cache'])) {
        Cache::setEnabled(true);
        $message = "Caching enabled globally.";
    } elseif (isset($_POST['cleanup_cache'])) {
        $cleaned = Cache::cleanup();
        $message = "Cleaned up {$cleaned} expired cache files.";
    }
}

$stats = Cache::getStats();
$isEnabled = Cache::isEnabled();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cache Manager - Tennessee Golf Courses</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c5234; border-bottom: 2px solid #4a7c59; padding-bottom: 10px; }
        .status { padding: 15px; border-radius: 5px; margin: 15px 0; }
        .enabled { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .disabled { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; }
        .stat-value { font-size: 2em; font-weight: bold; color: #4a7c59; }
        .stat-label { color: #666; font-size: 0.9em; }
        .buttons { display: flex; gap: 10px; flex-wrap: wrap; margin: 20px 0; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-success { background: #28a745; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        .btn:hover { opacity: 0.8; }
        .emergency { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .emergency h3 { color: #856404; margin-top: 0; }
        .emergency code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ Cache Manager</h1>
        
        <?php if ($message): ?>
            <div class="status message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="status disabled"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="status <?= $isEnabled ? 'enabled' : 'disabled' ?>">
            <strong>Cache Status:</strong> <?= $isEnabled ? '✅ ENABLED' : '❌ DISABLED' ?>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_files'] ?? 0 ?></div>
                <div class="stat-label">Total Files</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['valid_files'] ?? 0 ?></div>
                <div class="stat-label">Valid Files</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['expired_files'] ?? 0 ?></div>
                <div class="stat-label">Expired Files</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_size_mb'] ?? 0 ?>MB</div>
                <div class="stat-label">Total Size</div>
            </div>
        </div>
        
        <form method="post" style="display: inline;">
            <div class="buttons">
                <button type="submit" name="cleanup_cache" class="btn btn-info">🧹 Clean Expired</button>
                <button type="submit" name="clear_cache" class="btn btn-warning" onclick="return confirm('Clear all cache files?')">🗑️ Clear All Cache</button>
                
                <?php if ($isEnabled): ?>
                    <button type="submit" name="disable_cache" class="btn btn-danger" onclick="return confirm('Disable caching globally?')">🛑 Disable Caching</button>
                <?php else: ?>
                    <button type="submit" name="enable_cache" class="btn btn-success">▶️ Enable Caching</button>
                <?php endif; ?>
                
                <a href="/admin/" class="btn btn-info">← Back to Admin</a>
            </div>
        </form>
        
        <div class="emergency">
            <h3>🚨 Emergency Cache Disable</h3>
            <p>If caching causes issues, you can instantly disable it by editing:</p>
            <code>/includes/cache.php</code>
            <p>Uncomment this line at the bottom:</p>
            <code>// Cache::setEnabled(false);</code>
            <p>Or add this line to any problematic page:</p>
            <code>Cache::setEnabled(false);</code>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; color: #666; font-size: 0.9em;">
            <strong>How it works:</strong>
            <ul>
                <li>Course listings are cached for 30 minutes</li>
                <li>Individual course pages can be cached for 2 hours</li>
                <li>Database query results are automatically cached</li>
                <li>Cache automatically expires and regenerates</li>
                <li>All caching can be instantly disabled if needed</li>
            </ul>
        </div>
    </div>
</body>
</html>