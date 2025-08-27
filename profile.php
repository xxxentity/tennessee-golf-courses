<?php
require_once 'includes/init.php';

// Get username from URL parameter
$username = $_GET['username'] ?? '';

if (empty($username)) {
    header('HTTP/1.1 404 Not Found');
    include '404.php';
    exit;
}

$error = '';
$user = null;
$review_stats = null;
$comment_stats = null;
$recent_reviews = [];

// Get user details by username
try {
    $stmt = $pdo->prepare("SELECT id, username, first_name, last_name, display_real_name, user_title, profile_picture, created_at FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header('HTTP/1.1 404 Not Found');
        include '404.php';
        exit;
    }
    
    $user_id = $user['id'];
    
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
    
    // Get recent reviews (public only)
    $stmt = $pdo->prepare("
        SELECT r.rating, r.review_text, r.created_at, c.name as course_name, c.slug as course_slug
        FROM reviews r 
        LEFT JOIN courses c ON r.course_id = c.id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC 
        LIMIT 3
    ");
    $stmt->execute([$user_id]);
    $recent_reviews = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Public profile error for username '$username': " . $e->getMessage());
    $error = "Failed to load profile.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['display_real_name'] ? $user['first_name'] . ' ' . $user['last_name'] : $user['username']); ?> - Tennessee Golf Courses</title>
    <meta name="description" content="Profile of <?php echo htmlspecialchars($user['display_real_name'] ? $user['first_name'] . ' ' . $user['last_name'] : $user['username']); ?>, <?php echo htmlspecialchars($user['user_title'] ?? 'Verified User'); ?> at Tennessee Golf Courses.">
    <link rel="stylesheet" href="/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logos/tab-logo.webp?v=2">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=2">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .profile-page {
            padding-top: 140px;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
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
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--gold-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
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
        
        .profile-title {
            opacity: 0.9;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }
        
        .profile-since {
            opacity: 0.8;
            font-size: 0.95rem;
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
        }
        
        .review-item:last-child {
            margin-bottom: 0;
        }
        
        .review-course {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        
        .review-course a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .review-course a:hover {
            text-decoration: underline;
        }
        
        .review-rating {
            color: var(--gold-color);
            margin-bottom: 8px;
        }
        
        .review-text {
            color: var(--text-gray);
            line-height: 1.5;
            margin-bottom: 8px;
        }
        
        .review-date {
            font-size: 0.9rem;
            color: var(--text-gray);
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
<body class="profile-page">
    <?php include 'includes/navigation.php'; ?>

    <main class="profile-container">
        <?php if (!empty($error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin: 2rem; text-align: center;">
                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php else: ?>
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])): ?>
                    <img src="/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
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
            <p class="profile-title"><?php echo htmlspecialchars($user['user_title'] ?? 'Verified User'); ?></p>
            <p class="profile-since">
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
                <div class="stat-label">Comments</div>
            </div>
        </div>

        <?php if (!empty($recent_reviews)): ?>
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                Recent Reviews
            </h2>
            
            <?php foreach ($recent_reviews as $review): ?>
                <div class="review-item">
                    <div class="review-course">
                        <?php if ($review['course_slug']): ?>
                            <a href="/courses/<?php echo htmlspecialchars($review['course_slug']); ?>">
                                <?php echo htmlspecialchars($review['course_name'] ?: 'Course'); ?>
                            </a>
                        <?php else: ?>
                            <?php echo htmlspecialchars($review['course_name'] ?: 'Course'); ?>
                        <?php endif; ?>
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
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </main>

    <script src="/script.js"></script>
</body>
</html>