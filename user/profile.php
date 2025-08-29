<?php
require_once '../includes/session-security.php';
require_once '../includes/csrf.php';
require_once '../config/database.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - redirect to login
    header('Location: /login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Check if user is logged in using secure session
if (!SecureSession::isLoggedIn()) {
    header('Location: /login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$user_id = SecureSession::get('user_id');
$error = '';
$user = null;
$review_stats = null;
$comment_stats = null;
$recent_reviews = [];

// Get user details
try {
    // First, get the user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $error = "Failed to load user data.";
    } else {
        // Only proceed with other queries if user exists
        
        // Get user's reviews count
        $stmt = $pdo->prepare("SELECT COUNT(*) as review_count FROM reviews WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $review_stats = $stmt->fetch();
        if (!$review_stats) $review_stats = ['review_count' => 0];
        
        // Get user's comments count  
        $stmt = $pdo->prepare("SELECT COUNT(*) as comment_count FROM comments WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $comment_stats = $stmt->fetch();
        if (!$comment_stats) $comment_stats = ['comment_count' => 0];
        
        // Get recent reviews
        $stmt = $pdo->prepare("
            SELECT r.*, c.name as course_name 
            FROM reviews r 
            LEFT JOIN courses c ON r.course_id = c.id 
            WHERE r.user_id = ? 
            ORDER BY r.created_at DESC 
            LIMIT 5
        ");
        $stmt->execute([$user_id]);
        $recent_reviews = $stmt->fetchAll();
    }
    
} catch (PDOException $e) {
    error_log("Profile page database error for user_id $user_id: " . $e->getMessage());
    $error = "Failed to load user data.";
    $user = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/tab-logo.webp?v=2">
    <link rel="shortcut icon" href="../images/logos/tab-logo.webp?v=2">
    <style>
        /* Weather bar and navbar use default positioning from styles.css */
        
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
            margin-top: 140px;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            border-radius: var(--border-radius);
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: var(--shadow-medium);
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--gold-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px;
            border: 4px solid rgba(255,255,255,0.2);
            overflow: hidden;
            position: relative;
        }
        
        .profile-picture {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .profile-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .profile-email {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: var(--bg-white);
            padding: 24px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            text-align: center;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        
        .stat-label {
            color: var(--text-gray);
            font-weight: 500;
        }
        
        .stat-icon {
            font-size: 24px;
            color: var(--gold-color);
            margin-bottom: 12px;
        }
        
        .profile-section {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .review-item {
            padding: 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 16px;
            transition: var(--transition);
        }
        
        .review-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-light);
        }
        
        .review-course {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        
        .review-rating {
            color: var(--gold-color);
            margin-bottom: 8px;
        }
        
        .review-text {
            color: var(--text-gray);
            line-height: 1.5;
        }
        
        .review-date {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-top: 8px;
        }
        
        .btn-edit {
            background: var(--gold-color);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-edit:hover {
            background: #d97706;
            transform: translateY(-1px);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--text-gray);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--border-color);
            margin-bottom: 16px;
        }
        
        @media (max-width: 768px) {
            .profile-header {
                padding: 24px;
            }
            
            .profile-name {
                font-size: 1.5rem;
            }
            
            .profile-stats {
                grid-template-columns: 1fr;
            }
            
            .stat-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <main class="profile-container">
        <?php if (!empty($error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin: 2rem; text-align: center;">
                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php elseif (!$user || empty($user)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin: 2rem; text-align: center;">
                <i class="fas fa-exclamation-triangle"></i> Failed to load user data.
                <br><small>Debug: User ID = <?php echo $user_id; ?>, User exists = <?php echo $user ? 'Yes' : 'No'; ?></small>
            </div>
        <?php else: ?>
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if (!empty($user['profile_picture']) && file_exists('../' . $user['profile_picture'])): ?>
                    <img src="../<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
            </div>
            <h1 class="profile-name">
                <?php if ($user['display_real_name']): ?>
                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                <?php else: ?>
                    <?php echo htmlspecialchars($user['username']); ?>
                <?php endif; ?>
            </h1>
            <p class="profile-email"><?php echo htmlspecialchars($user['user_title'] ?? 'Verified User'); ?></p>
            <p style="opacity: 0.8; margin-top: 12px;">
                Member since <?php echo date('F Y', strtotime($user['created_at'])); ?>
            </p>
        </div>

        <div class="profile-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-number"><?php echo $review_stats['review_count']; ?></div>
                <div class="stat-label">Course Reviews</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-number"><?php echo $comment_stats['comment_count']; ?></div>
                <div class="stat-label">News Comments</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-number"><?php echo count(array_filter($recent_reviews, function($r) { return date('Y-m', strtotime($r['created_at'])) === date('Y-m'); })); ?></div>
                <div class="stat-label">This Month</div>
            </div>
        </div>

        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                Recent Reviews
            </h2>
            
            <?php if (empty($recent_reviews)): ?>
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <h3>No reviews yet</h3>
                    <p>Start exploring Tennessee golf courses and share your experiences!</p>
                </div>
            <?php else: ?>
                <?php foreach ($recent_reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-course">
                            <?php echo htmlspecialchars($review['course_name'] ?: 'Course'); ?>
                        </div>
                        <div class="review-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?php echo $i <= $review['rating'] ? '' : '-o'; ?>"></i>
                            <?php endfor; ?>
                            (<?php echo $review['rating']; ?>/5)
                        </div>
                        <div class="review-text">
                            <?php echo htmlspecialchars(substr($review['review_text'], 0, 150)) . (strlen($review['review_text']) > 150 ? '...' : ''); ?>
                        </div>
                        <div class="review-date">
                            <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-cog"></i>
                Account Settings
            </h2>
            <p style="margin-bottom: 20px; color: var(--text-gray);">
                Manage your account information and preferences.
            </p>
            <a href="edit-profile.php" class="btn-edit">
                <i class="fas fa-edit"></i>
                Edit Profile
            </a>
        </div>
        <?php endif; ?>
    </main>

    <!-- Weather scripts now loaded centrally via navigation.php -->
</body>
</html>