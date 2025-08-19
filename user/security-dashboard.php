<?php
require_once '../includes/init.php';
require_once '../config/database.php';
require_once '../includes/auth-security.php';

// Check if user is logged in
if (!$is_logged_in) {
    header('Location: /login?redirect=' . urlencode('/security-dashboard'));
    exit;
}

// Get user's security status
$securityStatus = AuthSecurity::getAccountSecurityStatus($pdo, $user_id);

// Get recent login attempts
try {
    $stmt = $pdo->prepare("
        SELECT success, ip_address, user_agent, created_at 
        FROM login_attempts 
        WHERE identifier = ? 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $stmt->execute([$email]);
    $recentAttempts = $stmt->fetchAll();
} catch (PDOException $e) {
    $recentAttempts = [];
}

// Get security strength indicators
$strengthColors = [
    'excellent' => '#22c55e',
    'good' => '#3b82f6', 
    'fair' => '#f59e0b',
    'low' => '#ef4444'
];

$strengthColor = $strengthColors[$securityStatus['level']] ?? '#6b7280';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Dashboard - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="../images/logos/tab-logo.webp?v=5">
    <link rel="shortcut icon" href="../images/logos/tab-logo.webp?v=5">
    
    <style>
        /* Weather Bar Styles */
        .weather-bar {
            background: linear-gradient(135deg, #2c5234, #1a3d26);
            color: white;
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .weather-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }
        
        .weather-info {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .current-time, .weather-widget {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .weather-location {
            font-size: 12px;
            opacity: 0.8;
            margin-left: 5px;
        }
        
        .golf-conditions {
            display: flex;
            gap: 1.5rem;
        }
        
        .condition-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
        }
        
        @media (max-width: 768px) {
            .weather-container {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .weather-info, .golf-conditions {
                gap: 1rem;
            }
        }
        
        .security-dashboard {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 2rem;
            margin-top: 100px; /* Account for weather bar */
        }
        
        .dashboard-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .dashboard-header h1 {
            color: #2c5234;
            margin-bottom: 0.5rem;
        }
        
        .security-score {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            position: relative;
        }
        
        .score-circle::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(
                <?php echo $strengthColor; ?> <?php echo $securityStatus['score'] * 3.6; ?>deg,
                #e5e7eb <?php echo $securityStatus['score'] * 3.6; ?>deg
            );
        }
        
        .score-circle span {
            position: relative;
            z-index: 1;
            background: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: <?php echo $strengthColor; ?>;
        }
        
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .security-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .security-card h3 {
            color: #2c5234;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }
        
        .status-good { background: #22c55e; }
        .status-warning { background: #f59e0b; }
        .status-danger { background: #ef4444; }
        
        .recommendations {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
        }
        
        .recommendations h4 {
            color: #92400e;
            margin-bottom: 0.5rem;
        }
        
        .recommendations ul {
            margin: 0;
            padding-left: 1.5rem;
            color: #92400e;
        }
        
        .activity-log {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
            gap: 1rem;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .success-icon {
            background: #dcfce7;
            color: #22c55e;
        }
        
        .failed-icon {
            background: #fee2e2;
            color: #ef4444;
        }
        
        .activity-details {
            flex: 1;
        }
        
        .activity-time {
            color: #6b7280;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Weather Bar -->
    <div class="weather-bar">
        <div class="weather-container">
            <div class="weather-info">
                <div class="current-time">
                    <i class="fas fa-clock"></i>
                    <span id="current-time">Loading...</span>
                </div>
                <div class="weather-widget">
                    <i class="fas fa-cloud-sun"></i>
                    <span id="weather-temp">Perfect Golf Weather</span>
                    <span class="weather-location">Nashville, TN</span>
                </div>
            </div>
            <div class="golf-conditions">
                <div class="condition-item">
                    <i class="fas fa-wind"></i>
                    <span>Light Breeze</span>
                </div>
                <div class="condition-item">
                    <i class="fas fa-eye"></i>
                    <span>Clear</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <main class="security-dashboard">
        <div class="dashboard-header">
            <h1>Security Dashboard</h1>
            <p>Monitor your account security and activity</p>
        </div>

        <!-- Security Score -->
        <div class="security-score">
            <div class="score-circle" style="background: <?php echo $strengthColor; ?>">
                <span><?php echo $securityStatus['score']; ?></span>
            </div>
            <h2>Security Score: <?php echo ucfirst($securityStatus['level']); ?></h2>
            <p>Your account security rating based on various factors</p>
        </div>

        <!-- Security Status Grid -->
        <div class="security-grid">
            <div class="security-card">
                <h3>
                    <i class="fas fa-envelope"></i>
                    Email Verification
                </h3>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="status-indicator <?php echo $securityStatus['email_verified'] ? 'status-good' : 'status-danger'; ?>"></span>
                    <span><?php echo $securityStatus['email_verified'] ? 'Verified' : 'Not Verified'; ?></span>
                </div>
                <?php if (!$securityStatus['email_verified']): ?>
                    <p style="color: #ef4444; margin-top: 0.5rem;">Please verify your email address for enhanced security.</p>
                <?php endif; ?>
            </div>

            <div class="security-card">
                <h3>
                    <i class="fas fa-lock"></i>
                    Account Status
                </h3>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="status-indicator <?php echo $securityStatus['account_locked'] ? 'status-danger' : 'status-good'; ?>"></span>
                    <span><?php echo $securityStatus['account_locked'] ? 'Locked' : 'Active'; ?></span>
                </div>
                <?php if ($securityStatus['account_locked']): ?>
                    <p style="color: #ef4444; margin-top: 0.5rem;">Your account is temporarily locked. Please contact support.</p>
                <?php endif; ?>
            </div>

            <div class="security-card">
                <h3>
                    <i class="fas fa-shield-alt"></i>
                    Failed Login Attempts
                </h3>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="status-indicator <?php echo $securityStatus['recent_failed_attempts'] == 0 ? 'status-good' : ($securityStatus['recent_failed_attempts'] < 3 ? 'status-warning' : 'status-danger'); ?>"></span>
                    <span><?php echo $securityStatus['recent_failed_attempts']; ?> in last 7 days</span>
                </div>
                <?php if ($securityStatus['recent_failed_attempts'] > 0): ?>
                    <p style="color: #f59e0b; margin-top: 0.5rem;">Monitor your account for suspicious activity.</p>
                <?php endif; ?>
            </div>

            <div class="security-card">
                <h3>
                    <i class="fas fa-key"></i>
                    Password Security
                </h3>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="status-indicator status-good"></span>
                    <span>Strong encryption</span>
                </div>
                <p style="color: #6b7280; margin-top: 0.5rem;">Your password is securely hashed using industry standards.</p>
            </div>
        </div>

        <!-- Recommendations -->
        <?php if (!empty($securityStatus['recommendations'])): ?>
        <div class="recommendations">
            <h4><i class="fas fa-lightbulb"></i> Security Recommendations</h4>
            <ul>
                <?php foreach ($securityStatus['recommendations'] as $recommendation): ?>
                    <li><?php echo htmlspecialchars($recommendation); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Recent Activity -->
        <div class="activity-log">
            <h3><i class="fas fa-history"></i> Recent Login Activity</h3>
            <?php if (!empty($recentAttempts)): ?>
                <?php foreach ($recentAttempts as $attempt): ?>
                <div class="activity-item">
                    <div class="activity-icon <?php echo $attempt['success'] ? 'success-icon' : 'failed-icon'; ?>">
                        <i class="fas fa-<?php echo $attempt['success'] ? 'check' : 'times'; ?>"></i>
                    </div>
                    <div class="activity-details">
                        <div><?php echo $attempt['success'] ? 'Successful login' : 'Failed login attempt'; ?></div>
                        <div style="color: #6b7280; font-size: 0.9rem;">
                            IP: <?php echo htmlspecialchars($attempt['ip_address']); ?>
                        </div>
                    </div>
                    <div class="activity-time">
                        <?php echo date('M j, Y g:i A', strtotime($attempt['created_at'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #6b7280; text-align: center; padding: 2rem;">No recent login activity found.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Dynamic Footer -->
    <?php include '../includes/footer.php'; ?>
    
    <script src="../script.js"></script>
    <script src="../weather.js"></script>
    <script>
        // Initialize weather widget
        document.addEventListener('DOMContentLoaded', function() {
            if (window.WeatherWidget) {
                WeatherWidget.init();
            }
        });
    </script>
</body>
</html>