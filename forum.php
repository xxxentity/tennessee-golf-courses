<?php
// Password protection removed - forum is now public
// require_once 'includes/forum-auth.php';

session_start();

// Include rate limiting functionality
require_once 'includes/forum-rate-limit.php';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$current_user_id = $is_logged_in ? $_SESSION['user_id'] : null;

// Get rate limits for current user
$rate_limits = ForumRateLimit::getRemainingLimits($current_user_id);

// Forum categories with golf-focused topics
$forum_categories = [
    [
        'id' => 1,
        'name' => 'Course Reviews & Discussions',
        'description' => 'Share your experiences and reviews of Tennessee golf courses',
        'icon' => 'fas fa-golf-ball',
        'color' => '#064e3b',
        'topics' => 0,
        'posts' => 0,
        'last_post' => null
    ],
    [
        'id' => 2,
        'name' => 'Equipment Talk',
        'description' => 'Discuss golf clubs, balls, gear, and equipment reviews',
        'icon' => 'fas fa-golf-club',
        'color' => '#ea580c',
        'topics' => 0,
        'posts' => 0,
        'last_post' => null
    ],
    [
        'id' => 3,
        'name' => 'Tournament & Events',
        'description' => 'Local tournaments, events, and golf meetups in Tennessee',
        'icon' => 'fas fa-trophy',
        'color' => '#7c3aed',
        'topics' => 0,
        'posts' => 0,
        'last_post' => null
    ],
    [
        'id' => 4,
        'name' => 'Tips & Instruction',
        'description' => 'Golf tips, lessons, and technique discussions',
        'icon' => 'fas fa-graduation-cap',
        'color' => '#059669',
        'topics' => 0,
        'posts' => 0,
        'last_post' => null
    ],
    [
        'id' => 5,
        'name' => 'General Golf Chat',
        'description' => 'General golf discussions, news, and casual conversation',
        'icon' => 'fas fa-comments',
        'color' => '#0369a1',
        'topics' => 0,
        'posts' => 0,
        'last_post' => null
    ],
    [
        'id' => 6,
        'name' => 'Site Feedback',
        'description' => 'Suggestions and feedback about Tennessee Golf Courses website',
        'icon' => 'fas fa-comment-dots',
        'color' => '#dc2626',
        'topics' => 1,
        'posts' => 1,
        'last_post' => [
            'author' => 'Admin',
            'date' => 'Jan 20, 2025'
        ]
    ]
];

// Add a sticky forum rules post to Site Feedback category
$forum_rules_post = [
    'id' => 1,
    'title' => '📋 Forum Rules & Community Guidelines - READ FIRST',
    'content' => 'Welcome to the Tennessee Golf Community Forum!

Please read and follow these rules to ensure a positive experience for everyone:

**POSTING LIMITS & SPAM PROTECTION:**
• Maximum 5 new topics per day
• Maximum 10 posts/replies per day
• Minimum 30 seconds between posts
• No excessive posting or spamming

**COMMUNITY GUIDELINES:**
• Be respectful and courteous to all members
• Stay on topic and keep discussions golf-related
• Use appropriate language - no profanity or offensive content
• No personal attacks, harassment, or discrimination
• Help create a welcoming environment for golfers of all skill levels

**CONTENT RULES:**
• No spam, advertising, or self-promotion without permission
• Share accurate information and cite sources when possible
• Use clear, descriptive titles for your topics
• Search existing topics before creating new ones
• No duplicate posts across multiple categories

**PROHIBITED CONTENT:**
• Commercial promotion or advertising
• Off-topic discussions unrelated to golf
• Inappropriate or offensive material
• Copyright infringement
• Personal information sharing

**CONSEQUENCES:**
• First violation: Warning
• Repeated violations: Temporary suspension
• Serious violations: Permanent ban

**REPORTING:**
If you see content that violates these rules, please contact the moderators.

Thank you for helping us maintain a great community for Tennessee golfers!

**Questions?** Contact us through the Site Feedback category.',
    'author' => 'Forum Admin',
    'author_id' => 1,
    'created_at' => '2025-01-20 12:00:00',
    'category_id' => 6,
    'is_pinned' => true,
    'is_locked' => false,
    'view_count' => 0,
    'reply_count' => 0
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golf Forum - Tennessee Golf Courses</title>
    <meta name="description" content="Join the Tennessee Golf Community Forum - discuss courses, equipment, tournaments, and connect with fellow golfers across the Volunteer State.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php include 'includes/favicon.php'; ?>
    
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
        
        .forum-hero {
            text-align: center;
            padding: 60px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            margin-bottom: 60px;
            margin-top: -140px;
        }
        
        .forum-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .forum-hero p {
            font-size: 1.3rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .forum-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .forum-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-gray);
            font-weight: 500;
        }
        
        .categories-grid {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .category-card {
            background: var(--bg-white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }
        
        .category-header {
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border-bottom: 1px solid var(--bg-light);
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }
        
        .category-info {
            flex: 1;
        }
        
        .category-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .category-description {
            color: var(--text-gray);
            line-height: 1.5;
        }
        
        .category-stats {
            padding: 1.5rem 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            gap: 1rem;
            align-items: center;
            background: var(--bg-light);
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-item.last-post {
            text-align: left;
        }
        
        .stat-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }
        
        .stat-item-label {
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        
        .last-post-info {
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        
        .new-topic-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-light);
            margin-bottom: 2rem;
        }
        
        .new-topic-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            color: white;
        }
        
        .forum-rules {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin-bottom: 3rem;
        }
        
        .forum-rules h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .forum-rules ul {
            list-style: none;
            padding: 0;
        }
        
        .forum-rules li {
            padding: 0.5rem 0;
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .forum-rules li i {
            color: var(--secondary-color);
            width: 16px;
        }
        
        @media (max-width: 768px) {
            .forum-hero h1 {
                font-size: 2.5rem;
            }
            
            .forum-hero p {
                font-size: 1.1rem;
            }
            
            .category-header {
                padding: 1.5rem;
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .category-stats {
                grid-template-columns: 1fr;
                gap: 1rem;
                text-align: center;
            }
            
            .stat-item.last-post {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="forum-page">
        <!-- Dynamic Navigation -->
        <?php include 'includes/navigation.php'; ?>
        
        <!-- Forum Hero Section -->
        <div class="forum-hero">
            <h1><i class="fas fa-comments"></i> Golf Community Forum</h1>
            <p>Connect with fellow golfers, share experiences, and discuss everything golf in Tennessee</p>
        </div>
        
        <!-- Forum Content -->
        <div class="forum-content">
            <!-- Forum Stats -->
            <div class="forum-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">0</div>
                    <div class="stat-label">Members</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-number">0</div>
                    <div class="stat-label">Topics</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="stat-number">0</div>
                    <div class="stat-label">Posts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number">New</div>
                    <div class="stat-label">Community</div>
                </div>
            </div>
            
            <!-- New Topic Button -->
            <?php if ($is_logged_in): ?>
                <a href="/forum/new-topic" class="new-topic-btn">
                    <i class="fas fa-plus"></i>
                    Start New Discussion
                    <small style="display: block; font-size: 0.8rem; margin-top: 0.25rem; opacity: 0.8;">
                        (<?php echo $rate_limits['topics']; ?> topics remaining today)
                    </small>
                </a>
            <?php else: ?>
                <a href="/login?redirect=/forum/new-topic" class="new-topic-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login to Start Discussion
                </a>
            <?php endif; ?>
            
            <!-- Forum Categories -->
            <div class="categories-grid">
                <?php foreach ($forum_categories as $category): ?>
                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon" style="background: <?php echo $category['color']; ?>;">
                            <i class="<?php echo $category['icon']; ?>"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-name">
                                <a href="/forum/category/<?php echo $category['id']; ?>" style="text-decoration: none; color: inherit;">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </h3>
                            <p class="category-description"><?php echo htmlspecialchars($category['description']); ?></p>
                        </div>
                    </div>
                    <div class="category-stats">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo $category['topics']; ?></div>
                            <div class="stat-item-label">Topics</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo $category['posts']; ?></div>
                            <div class="stat-item-label">Posts</div>
                        </div>
                        <div class="stat-item last-post">
                            <?php if ($category['last_post']): ?>
                                <div class="last-post-info">
                                    Last post by <?php echo htmlspecialchars($category['last_post']['author']); ?><br>
                                    <small><?php echo $category['last_post']['date']; ?></small>
                                </div>
                            <?php else: ?>
                                <div class="last-post-info">
                                    <em>No posts yet</em><br>
                                    <small>Be the first to start a discussion!</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Forum Rules -->
            <div class="forum-rules">
                <h3><i class="fas fa-shield-alt"></i> Community Guidelines</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Be respectful and courteous to all members</li>
                    <li><i class="fas fa-check"></i> Stay on topic and keep discussions golf-related</li>
                    <li><i class="fas fa-check"></i> No spam, advertising, or self-promotion without permission</li>
                    <li><i class="fas fa-check"></i> Use appropriate language and avoid offensive content</li>
                    <li><i class="fas fa-check"></i> Share accurate information and cite sources when possible</li>
                    <li><i class="fas fa-check"></i> Help create a welcoming environment for golfers of all skill levels</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>