<?php
session_start();

// Include rate limiting functionality
require_once '../includes/forum-rate-limit.php';

// Get category ID from URL parameter
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$current_user_id = $is_logged_in ? $_SESSION['user_id'] : null;

// Get rate limits for current user
$rate_limits = ForumRateLimit::getRemainingLimits($current_user_id);

// Mock category data (replace with database queries later)
$categories = [
    1 => [
        'id' => 1,
        'name' => 'Course Reviews & Discussions',
        'description' => 'Share your experiences and reviews of Tennessee golf courses',
        'icon' => 'fas fa-golf-ball',
        'color' => '#064e3b'
    ],
    2 => [
        'id' => 2,
        'name' => 'Equipment Talk',
        'description' => 'Discuss golf clubs, balls, gear, and equipment reviews',
        'icon' => 'fas fa-golf-club',
        'color' => '#ea580c'
    ],
    3 => [
        'id' => 3,
        'name' => 'Tournament & Events',
        'description' => 'Local tournaments, events, and golf meetups in Tennessee',
        'icon' => 'fas fa-trophy',
        'color' => '#7c3aed'
    ],
    4 => [
        'id' => 4,
        'name' => 'Tips & Instruction',
        'description' => 'Golf tips, lessons, and technique discussions',
        'icon' => 'fas fa-graduation-cap',
        'color' => '#059669'
    ],
    5 => [
        'id' => 5,
        'name' => 'General Golf Chat',
        'description' => 'General golf discussions, news, and casual conversation',
        'icon' => 'fas fa-comments',
        'color' => '#0369a1'
    ],
    6 => [
        'id' => 6,
        'name' => 'Site Feedback',
        'description' => 'Suggestions and feedback about Tennessee Golf Courses website',
        'icon' => 'fas fa-comment-dots',
        'color' => '#dc2626'
    ]
];

// Check if category exists
if (!isset($categories[$category_id])) {
    header('Location: /forum');
    exit;
}

$category = $categories[$category_id];

// Mock topics data (replace with database queries later)
$topics = [];

// Add forum rules post to Site Feedback category
if ($category_id == 6) {
    $topics[] = [
        'id' => 1,
        'title' => '📋 Forum Rules & Community Guidelines - READ FIRST',
        'author' => 'Forum Admin',
        'author_id' => 1,
        'created_at' => '2025-01-20 12:00:00',
        'reply_count' => 0,
        'view_count' => 25,
        'last_reply_at' => null,
        'last_reply_author' => null,
        'is_pinned' => true,
        'is_locked' => true
    ];
}

// Filter topics by category (in real implementation, this would be a database query)
$category_topics = $topics;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - Golf Forum - Tennessee Golf Courses</title>
    <meta name="description" content="<?php echo htmlspecialchars($category['description']); ?> - Tennessee Golf Community Forum">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php include '../includes/favicon.php'; ?>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .forum-page {
            padding-top: 90px;
            min-height: 80vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .category-header {
            background: var(--bg-white);
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .category-icon {
            width: 80px;
            height: 80px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
        }
        
        .category-info h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .category-info p {
            color: var(--text-gray);
            font-size: 1.1rem;
            margin: 0;
        }
        
        .breadcrumb {
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .breadcrumb span {
            color: var(--text-gray);
            margin: 0 0.5rem;
        }
        
        .topics-container {
            background: var(--bg-white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            overflow: hidden;
        }
        
        .topics-header {
            background: var(--bg-light);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .new-topic-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .new-topic-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            color: white;
        }
        
        .topic-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .topic-item {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--bg-light);
            transition: background 0.3s ease;
        }
        
        .topic-item:hover {
            background: var(--bg-light);
        }
        
        .topic-item:last-child {
            border-bottom: none;
        }
        
        .topic-row {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 2rem;
            align-items: center;
        }
        
        .topic-main {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .topic-status {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: var(--bg-light);
            color: var(--text-gray);
        }
        
        .topic-status.pinned {
            background: var(--secondary-color);
            color: white;
        }
        
        .topic-status.locked {
            background: var(--text-gray);
            color: white;
        }
        
        .topic-content h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .topic-content h3 a {
            text-decoration: none;
            color: inherit;
        }
        
        .topic-content h3 a:hover {
            color: var(--secondary-color);
        }
        
        .topic-meta {
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        
        .topic-stats {
            text-align: center;
            font-size: 0.9rem;
        }
        
        .stat-number {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .stat-label {
            color: var(--text-gray);
            font-size: 0.8rem;
        }
        
        .topic-last-post {
            text-align: right;
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        
        .no-topics {
            padding: 3rem 2rem;
            text-align: center;
            color: var(--text-gray);
        }
        
        .no-topics i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--bg-light);
        }
        
        @media (max-width: 768px) {
            .category-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .category-info h1 {
                font-size: 2rem;
            }
            
            .topic-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .topic-stats,
            .topic-last-post {
                text-align: left;
            }
            
            .topics-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="forum-page">
        <!-- Dynamic Navigation -->
        <?php include '../includes/navigation.php'; ?>
        
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="/forum"><i class="fas fa-comments"></i> Forum</a>
                <span>›</span>
                <strong><?php echo htmlspecialchars($category['name']); ?></strong>
            </nav>
            
            <!-- Category Header -->
            <div class="category-header">
                <div class="category-icon" style="background: <?php echo $category['color']; ?>;">
                    <i class="<?php echo $category['icon']; ?>"></i>
                </div>
                <div class="category-info">
                    <h1><?php echo htmlspecialchars($category['name']); ?></h1>
                    <p><?php echo htmlspecialchars($category['description']); ?></p>
                </div>
            </div>
            
            <!-- Topics Container -->
            <div class="topics-container" style="background: white; border: 1px solid #ddd; min-height: 200px;">
                <div class="topics-header">
                    <h2>Discussion Topics</h2>
                    <?php if ($is_logged_in): ?>
                        <?php $can_create = ForumRateLimit::canCreateTopic($current_user_id); ?>
                        <?php if ($can_create['allowed']): ?>
                            <a href="/forum/new-topic?category=<?php echo $category_id; ?>" class="new-topic-btn">
                                <i class="fas fa-plus"></i>
                                New Topic
                                <small style="display: block; font-size: 0.8rem; margin-top: 0.25rem; opacity: 0.8;">
                                    (<?php echo $rate_limits['topics']; ?> remaining today)
                                </small>
                            </a>
                        <?php else: ?>
                            <span class="new-topic-btn" style="background: #9ca3af; cursor: not-allowed;" title="<?php echo $can_create['reason']; ?>">
                                <i class="fas fa-clock"></i>
                                Topic Limit Reached
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/login?redirect=/forum/new-topic?category=<?php echo $category_id; ?>" class="new-topic-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            Login to Post
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Debug: Category ID = <?php echo $category_id; ?>, Topics count = <?php echo count($category_topics); ?> -->
                <?php if (empty($category_topics)): ?>
                    <div class="no-topics">
                        <i class="fas fa-comments"></i>
                        <h3>No topics yet</h3>
                        <p>Be the first to start a discussion in this category!</p>
                        <a href="/forum/new-topic?category=<?php echo $category_id; ?>" class="new-topic-btn" style="margin-top: 1rem;">
                            <i class="fas fa-plus"></i>
                            Start the Conversation
                        </a>
                    </div>
                <?php else: ?>
                    <ul class="topic-list">
                        <?php foreach ($category_topics as $topic): ?>
                        <li class="topic-item">
                            <div class="topic-row">
                                <div class="topic-main">
                                    <div class="topic-status <?php echo $topic['is_pinned'] ? 'pinned' : ($topic['is_locked'] ? 'locked' : ''); ?>">
                                        <?php if ($topic['is_pinned']): ?>
                                            <i class="fas fa-thumbtack"></i>
                                        <?php elseif ($topic['is_locked']): ?>
                                            <i class="fas fa-lock"></i>
                                        <?php else: ?>
                                            <i class="fas fa-comment"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="topic-content">
                                        <h3>
                                            <a href="/forum/topic/<?php echo $topic['id']; ?>">
                                                <?php echo htmlspecialchars($topic['title']); ?>
                                            </a>
                                        </h3>
                                        <div class="topic-meta">
                                            Started by <strong><?php echo htmlspecialchars($topic['author']); ?></strong>
                                            on <?php echo date('M j, Y \a\t g:i A', strtotime($topic['created_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="topic-stats">
                                    <div class="stat-number"><?php echo $topic['reply_count']; ?></div>
                                    <div class="stat-label">Replies</div>
                                    <div class="stat-number"><?php echo $topic['view_count']; ?></div>
                                    <div class="stat-label">Views</div>
                                </div>
                                <div class="topic-last-post">
                                    <?php if ($topic['last_reply_at']): ?>
                                        <strong><?php echo htmlspecialchars($topic['last_reply_author']); ?></strong><br>
                                        <small><?php echo date('M j, Y \a\t g:i A', strtotime($topic['last_reply_at'])); ?></small>
                                    <?php else: ?>
                                        <em>No replies yet</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>