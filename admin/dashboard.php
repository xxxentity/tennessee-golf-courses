<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

// Get some basic stats
try {
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $totalUsers = $stmt->fetchColumn();
    
    // Users this month
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    $newUsers = $stmt->fetchColumn();
    
    // Newsletter subscribers
    $stmt = $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers WHERE confirmed = 1");
    $newsletterSubs = $stmt->fetchColumn();
    
    // Contest entries
    $stmt = $pdo->query("SELECT COUNT(*) FROM contest_entries");
    $contestEntries = $stmt->fetchColumn();
    
    // Pending contest entries
    $stmt = $pdo->query("SELECT COUNT(*) FROM contest_entries WHERE status = 'pending'");
    $pendingEntries = $stmt->fetchColumn();
    
    // Recent user registrations
    $stmt = $pdo->query("SELECT username, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
    $recentUsers = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $totalUsers = 0;
    $newUsers = 0;
    $newsletterSubs = 0;
    $contestEntries = 0;
    $pendingEntries = 0;
    $recentUsers = [];
    error_log("Dashboard stats error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php include '../includes/favicon.php'; ?>
    
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
        
        .admin-title h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .admin-title p {
            margin: 0.25rem 0 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .admin-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .admin-user span {
            font-weight: 500;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .admin-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: inherit;
        }
        
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            margin: 0;
        }
        
        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .icon-users { background: linear-gradient(135deg, #3b82f6, #1e40af); }
        .icon-security { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .icon-logs { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .icon-settings { background: linear-gradient(135deg, #6b7280, #4b5563); }
        .icon-newsletter { background: linear-gradient(135deg, #10b981, #059669); }
        .icon-performance { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .icon-seo { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .icon-contests { background: linear-gradient(135deg, #f59e0b, #d97706); }
        
        .card-description {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        .card-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }
        
        .action-text {
            font-weight: 500;
            color: #374151;
        }
        
        .action-arrow {
            color: #9ca3af;
            font-size: 1.2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
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
        
        .recent-activity {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .activity-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .activity-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #374151;
            margin: 0;
        }
        
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 1rem;
            font-size: 0.9rem;
        }
        
        .activity-details {
            flex: 1;
        }
        
        .activity-name {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.25rem;
        }
        
        .activity-info {
            font-size: 0.85rem;
            color: #6b7280;
        }
        
        .activity-time {
            color: #9ca3af;
            font-size: 0.8rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <div class="admin-title">
                <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
                <p>Tennessee Golf Courses Management</p>
            </div>
            <div class="admin-user">
                <span>Welcome, <?php echo htmlspecialchars($currentAdmin['first_name'] ?? 'Admin'); ?>!</span>
                <a href="/admin/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>
    
    <main class="dashboard-container">
        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($totalUsers); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($newUsers); ?></div>
                <div class="stat-label">New This Month</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($newsletterSubs); ?></div>
                <div class="stat-label">Newsletter Subscribers</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($contestEntries); ?></div>
                <div class="stat-label">Contest Entries</div>
            </div>
        </div>
        
        <!-- Admin Actions -->
        <div class="dashboard-grid">
            <a href="/admin/users" class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">User Management</h3>
                    <div class="card-icon icon-users">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <p class="card-description">
                    View, edit, and manage user accounts, registrations, and user permissions.
                </p>
                <div class="card-action">
                    <span class="action-text">Manage Users</span>
                    <i class="fas fa-arrow-right action-arrow"></i>
                </div>
            </a>
            
            <a href="/admin/security" class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Security Monitor</h3>
                    <div class="card-icon icon-security">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <p class="card-description">
                    Monitor security events, failed login attempts, and system security status.
                </p>
                <div class="card-action">
                    <span class="action-text">View Security</span>
                    <i class="fas fa-arrow-right action-arrow"></i>
                </div>
            </a>
            
            <a href="/admin/logs" class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Activity Logs</h3>
                    <div class="card-icon icon-logs">
                        <i class="fas fa-list-alt"></i>
                    </div>
                </div>
                <p class="card-description">
                    View system logs, user activity, and audit trails for compliance.
                </p>
                <div class="card-action">
                    <span class="action-text">View Logs</span>
                    <i class="fas fa-arrow-right action-arrow"></i>
                </div>
            </a>
            
            <a href="/admin/settings" class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">System Settings</h3>
                    <div class="card-icon icon-settings">
                        <i class="fas fa-cogs"></i>
                    </div>
                </div>
                <p class="card-description">
                    Configure system settings, security options, and application preferences.
                </p>
                <div class="card-action">
                    <span class="action-text">Configure</span>
                    <i class="fas fa-arrow-right action-arrow"></i>
                </div>
            </a>
            
            <a href="/admin/newsletter" class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Newsletter Admin</h3>
                    <div class="card-icon icon-newsletter">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <p class="card-description">
                    Manage newsletter subscribers, send campaigns, and view email analytics.
                </p>
                <div class="card-action">
                    <span class="action-text">Manage Newsletter</span>
                    <i class="fas fa-arrow-right action-arrow"></i>
                </div>
            </a>
            
            <a href="/admin/performance-monitor" class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Performance Monitor</h3>
                    <div class="card-icon icon-performance">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                </div>
                <p class="card-description">
                    Monitor site performance, cache statistics, database queries, and execution metrics.
                </p>
                <div class="card-action">
                    <span class="action-text">View Performance</span>
                    <i class="fas fa-arrow-right action-arrow"></i>
                </div>
            </a>
            
            <a href="/admin/contest-entries" class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Contest Entries</h3>
                    <div class="card-icon icon-contests">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
                <p class="card-description">
                    View and manage contest entries, approve winners, and track submissions.
                </p>
                <div class="card-action">
                    <span class="action-text">Manage Contests</span>
                    <i class="fas fa-arrow-right action-arrow"></i>
                </div>
            </a>
            
            <a href="/admin/seo-manager" class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">SEO Manager</h3>
                    <div class="card-icon icon-seo">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <p class="card-description">
                    Manage SEO optimization, meta tags, sitemaps, and search engine visibility.
                </p>
                <div class="card-action">
                    <span class="action-text">Manage SEO</span>
                    <i class="fas fa-arrow-right action-arrow"></i>
                </div>
            </a>
        </div>
        
        <!-- Recent Activity -->
        <div class="recent-activity">
            <div class="activity-header">
                <h3 class="activity-title">Recent User Registrations</h3>
                <a href="/admin/users" style="color: #3b82f6; text-decoration: none; font-size: 0.9rem;">View All</a>
            </div>
            
            <?php if (!empty($recentUsers)): ?>
                <ul class="activity-list">
                    <?php foreach ($recentUsers as $user): ?>
                        <li class="activity-item">
                            <div class="activity-avatar">
                                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                            </div>
                            <div class="activity-details">
                                <div class="activity-name"><?php echo htmlspecialchars($user['username']); ?></div>
                                <div class="activity-info"><?php echo htmlspecialchars($user['email']); ?></div>
                            </div>
                            <div class="activity-time">
                                <?php echo date('M j, g:i A', strtotime($user['created_at'])); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="color: #6b7280; text-align: center; padding: 2rem;">
                    No recent user registrations found.
                </p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>