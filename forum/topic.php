<?php
// Password protection removed - forum is now public
// require_once '../includes/forum-auth.php';

// Include session security for consistent session handling
require_once '../includes/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - user not logged in
}

// Include rate limiting functionality
require_once '../includes/forum-rate-limit.php';
require_once '../includes/csrf.php';

// Get topic ID from URL parameter
$topic_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check if user is logged in using secure session
$is_logged_in = SecureSession::isLoggedIn();
$current_user_id = $is_logged_in ? SecureSession::get('user_id') : null;
$current_user_name = $is_logged_in ? SecureSession::get('username', 'Anonymous') : '';

// Check rate limits for posting
$can_create_post = ForumRateLimit::canCreatePost($current_user_id);
$rate_limits = ForumRateLimit::getRemainingLimits($current_user_id);

// Mock topic data (replace with database queries later)
// Define available topics
$topics_data = [
    1 => [
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
        'author' => 'admin',
        'author_id' => 1,
        'created_at' => '2025-01-20 12:00:00',
        'category_id' => 6,
        'category_name' => 'Site Feedback',
        'view_count' => 25,
        'reply_count' => 0,
        'is_pinned' => true,
        'is_locked' => true
    ],
    2 => [
        'id' => 2,
        'title' => 'Best Golf Courses in Nashville Area',
        'content' => 'Hey everyone! I\'m new to the Nashville area and looking for recommendations on the best golf courses around here. 

I\'ve heard great things about Hermitage Golf Course and Gaylord Springs, but would love to hear your thoughts and experiences.

What are your favorite courses in the Nashville area and why? Looking for courses that offer:
- Good course conditions
- Fair pricing
- Nice practice facilities
- Not too crowded on weekends

Thanks in advance for your recommendations!',
        'author' => 'johngolfer',
        'author_id' => 2,
        'created_at' => '2025-01-20 14:30:00',
        'category_id' => 1,
        'category_name' => 'Course Reviews & Discussions',
        'view_count' => 42,
        'reply_count' => 3,
        'is_pinned' => false,
        'is_locked' => false
    ],
    3 => [
        'id' => 3,
        'title' => 'New Driver Recommendations - 2025 Models',
        'content' => 'Time to upgrade my driver! Currently playing a 5-year-old model and looking at the new 2025 releases.

Has anyone tried the new TaylorMade Qi10 or the Callaway Paradym Ai Smoke? 

My swing speed is around 95-100mph and I tend to have a slight fade. Budget is around $400-600.

Would appreciate any feedback or other recommendations!',
        'author' => 'miketenn',
        'author_id' => 3,
        'created_at' => '2025-01-20 16:45:00',
        'category_id' => 2,
        'category_name' => 'Equipment Talk',
        'view_count' => 28,
        'reply_count' => 5,
        'is_pinned' => false,
        'is_locked' => false
    ]
];

// Get the requested topic
$topic = isset($topics_data[$topic_id]) ? $topics_data[$topic_id] : null;

// If not found in mock data, check session storage
if (!$topic && isset($_SESSION['forum_topics'][$topic_id])) {
    $topic = $_SESSION['forum_topics'][$topic_id];
    // Get category name
    $categories = [
        1 => 'Course Reviews & Discussions',
        2 => 'Equipment Talk',
        3 => 'Tournament & Events',
        4 => 'Tips & Instruction',
        5 => 'General Golf Chat',
        6 => 'Site Feedback'
    ];
    $topic['category_name'] = $categories[$topic['category_id']] ?? 'Unknown Category';
}

// Mock replies data (replace with database queries later)
$replies_data = [
    1 => [], // No replies for forum rules (locked)
    2 => [ // Replies for Nashville golf courses topic
        [
            'id' => 1,
            'topic_id' => 2,
            'author' => 'sarahpro',
            'author_id' => 4,
            'content' => 'Welcome to Nashville! You\'ve already mentioned two great courses. Here are my top recommendations:

1. **Hermitage Golf Course** - Two great 18-hole courses, excellent conditions year-round
2. **Gaylord Springs** - Beautiful links-style course, can be pricey but worth it
3. **Greystone Golf Course** - Great value, well-maintained public course
4. **Nashville National** - Newer course, challenging but fair

For practice facilities, Hermitage has the best range in the area. Let me know if you need more specific info!',
            'created_at' => '2025-01-20 15:15:00',
            'is_edited' => false
        ],
        [
            'id' => 2,
            'topic_id' => 2,
            'author' => 'tomscratch',
            'author_id' => 5,
            'content' => 'I second the Greystone recommendation! Played there last weekend and the greens were in fantastic shape. Very affordable too - around $45 with cart on weekends.

Also check out Ted Rhodes if you want something closer to downtown. Historic course with lots of character.',
            'created_at' => '2025-01-20 17:30:00',
            'is_edited' => false
        ],
        [
            'id' => 3,
            'topic_id' => 2,
            'author' => 'localgolfer',
            'author_id' => 6,
            'content' => 'Don\'t overlook McCabe Golf Course! It\'s a hidden gem - never too crowded and they have great twilight rates. The back nine has some really interesting holes along the river.

For practice, Golf House Tennessee has an amazing facility if you\'re willing to drive to Franklin.',
            'created_at' => '2025-01-20 18:45:00',
            'is_edited' => false
        ]
    ],
    3 => [ // Replies for driver recommendations topic
        [
            'id' => 4,
            'topic_id' => 3,
            'author' => 'proshopguy',
            'author_id' => 7,
            'content' => 'With your swing speed and fade tendency, I\'d definitely look at the Callaway Paradym Ai Smoke. The draw-biased model could help straighten out that fade.

We\'ve been fitting a lot of players in your speed range and seeing great results. The adjustability is excellent too.',
            'created_at' => '2025-01-20 17:00:00',
            'is_edited' => false
        ],
        [
            'id' => 5,
            'topic_id' => 3,
            'author' => 'equipjunkie',
            'author_id' => 8,
            'content' => 'I just switched from the Qi10 to the Paradym and couldn\'t be happier. The Qi10 is longer but less forgiving on mishits.

Have you considered the Ping G430? At your swing speed it might be the best option - super forgiving and still plenty long.',
            'created_at' => '2025-01-20 17:45:00',
            'is_edited' => true
        ]
    ]
];

// Get replies for this topic
$replies = isset($replies_data[$topic_id]) ? $replies_data[$topic_id] : [];

// Check if topic exists
if (!$topic) {
    header('Location: /forum');
    exit;
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in && isset($_POST['reply_content'])) {
    // Validate CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (CSRFProtection::validateToken($csrf_token)) {
        $reply_content = trim($_POST['reply_content']);
        
        if (!empty($reply_content) && !$topic['is_locked']) {
            // Check rate limits
            if ($can_create_post['allowed']) {
                // Check for spam
                $spam_check = ForumRateLimit::isSpamContent($reply_content);
                if (!$spam_check['is_spam']) {
                    // Record the post creation for rate limiting
                    ForumRateLimit::recordPostCreated($current_user_id);
                    
                    // In real implementation, save to database
                    // For now, just redirect to prevent form resubmission
                    header('Location: /forum/topic/' . $topic_id . '#latest');
                    exit;
                }
            }
        }
    }
}

// Increment view count (in real implementation, do this in database)
// $topic['view_count']++;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($topic['title']); ?> - Golf Forum - Tennessee Golf Courses</title>
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($topic['content']), 0, 160)); ?>... - Tennessee Golf Community Forum">
    <link rel="stylesheet" href="/styles.css">
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
        
        .topic-header {
            background: var(--bg-white);
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
        }
        
        .topic-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .topic-status-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }
        
        .topic-status-icon.pinned {
            background: var(--secondary-color);
        }
        
        .topic-status-icon.locked {
            background: var(--text-gray);
        }
        
        .topic-status-icon.normal {
            background: var(--primary-color);
        }
        
        .topic-title h1 {
            font-size: 2.2rem;
            color: var(--primary-color);
            margin: 0;
            font-weight: 700;
        }
        
        .topic-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            color: var(--text-gray);
            font-size: 0.95rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .posts-container {
            margin-bottom: 2rem;
        }
        
        .post {
            background: var(--bg-white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .post.original-post {
            border-left: 4px solid var(--primary-color);
        }
        
        .post-header {
            background: var(--bg-light);
            padding: 1.5rem 2rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .post-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .author-info h4 {
            margin: 0 0 0.25rem 0;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .author-info .post-date {
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        
        .post-number {
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .post-content {
            padding: 2rem;
            line-height: 1.7;
            color: var(--text-dark);
        }
        
        .post-content p {
            margin-bottom: 1rem;
        }
        
        .post-content p:last-child {
            margin-bottom: 0;
        }
        
        .reply-form-container {
            background: var(--bg-white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .reply-form-header {
            margin-bottom: 1.5rem;
        }
        
        .reply-form-header h3 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .reply-form-header p {
            color: var(--text-gray);
            margin: 0;
        }
        
        .reply-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .reply-textarea {
            width: 100%;
            min-height: 150px;
            padding: 1rem;
            border: 2px solid var(--bg-light);
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            line-height: 1.5;
            resize: vertical;
            transition: border-color 0.3s ease;
        }
        
        .reply-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .reply-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .reply-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .reply-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .reply-btn:disabled {
            background: var(--text-gray);
            cursor: not-allowed;
            transform: none;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 10px;
            color: var(--text-gray);
        }
        
        .login-prompt a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-prompt a:hover {
            text-decoration: underline;
        }
        
        .topic-locked-notice {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .topic-title {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .topic-title h1 {
                font-size: 1.8rem;
            }
            
            .topic-meta {
                justify-content: center;
                gap: 1rem;
            }
            
            .post-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .post-content {
                padding: 1.5rem;
            }
            
            .reply-form-container {
                padding: 1.5rem;
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
                <a href="/forum/category/<?php echo $topic['category_id']; ?>"><?php echo htmlspecialchars($topic['category_name']); ?></a>
                <span>›</span>
                <strong><?php echo htmlspecialchars($topic['title']); ?></strong>
            </nav>
            
            <!-- Topic Header -->
            <div class="topic-header">
                <div class="topic-title">
                    <div class="topic-status-icon <?php echo $topic['is_pinned'] ? 'pinned' : ($topic['is_locked'] ? 'locked' : 'normal'); ?>">
                        <?php if ($topic['is_pinned']): ?>
                            <i class="fas fa-thumbtack"></i>
                        <?php elseif ($topic['is_locked']): ?>
                            <i class="fas fa-lock"></i>
                        <?php else: ?>
                            <i class="fas fa-comment"></i>
                        <?php endif; ?>
                    </div>
                    <h1><?php echo htmlspecialchars($topic['title']); ?></h1>
                </div>
                <div class="topic-meta">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        Started by <strong><?php echo htmlspecialchars($topic['author']); ?></strong>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <?php echo date('M j, Y \a\t g:i A', strtotime($topic['created_at'])); ?>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-eye"></i>
                        <?php echo $topic['view_count']; ?> views
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-reply"></i>
                        <?php echo $topic['reply_count']; ?> replies
                    </div>
                </div>
            </div>
            
            <!-- Posts Container -->
            <div class="posts-container">
                <!-- Original Post -->
                <div class="post original-post">
                    <div class="post-header">
                        <div class="post-author">
                            <div class="author-avatar">
                                <?php echo strtoupper(substr($topic['author'], 0, 1)); ?>
                            </div>
                            <div class="author-info">
                                <h4><?php echo htmlspecialchars($topic['author']); ?></h4>
                                <div class="post-date"><?php echo date('M j, Y \a\t g:i A', strtotime($topic['created_at'])); ?></div>
                            </div>
                        </div>
                        <div class="post-number">#1</div>
                    </div>
                    <div class="post-content">
                        <?php echo nl2br(htmlspecialchars($topic['content'])); ?>
                    </div>
                </div>
                
                <!-- Replies -->
                <?php foreach ($replies as $index => $reply): ?>
                <div class="post">
                    <div class="post-header">
                        <div class="post-author">
                            <div class="author-avatar">
                                <?php echo strtoupper(substr($reply['author'], 0, 1)); ?>
                            </div>
                            <div class="author-info">
                                <h4><?php echo htmlspecialchars($reply['author']); ?></h4>
                                <div class="post-date">
                                    <?php echo date('M j, Y \a\t g:i A', strtotime($reply['created_at'])); ?>
                                    <?php if ($reply['is_edited']): ?>
                                        <em>(edited)</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="post-number">#<?php echo $index + 2; ?></div>
                    </div>
                    <div class="post-content">
                        <?php echo nl2br(htmlspecialchars($reply['content'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Reply Form -->
            <?php if ($topic['is_locked']): ?>
                <div class="topic-locked-notice">
                    <i class="fas fa-lock"></i>
                    This topic has been locked by a moderator. No new replies can be posted.
                </div>
            <?php elseif ($is_logged_in): ?>
                <div class="reply-form-container" id="latest">
                    <div class="reply-form-header">
                        <h3><i class="fas fa-reply"></i> Post a Reply</h3>
                        <p>Share your thoughts and join the discussion</p>
                        <div style="background: #f0f9ff; border: 1px solid #0ea5e9; color: #0369a1; padding: 0.75rem; border-radius: 8px; margin-top: 0.5rem;">
                            <small><i class="fas fa-info-circle"></i> 
                            Daily Limits: <?php echo $rate_limits['posts']; ?>/<?php echo $rate_limits['posts_limit']; ?> posts remaining
                            </small>
                        </div>
                    </div>
                    <?php if ($can_create_post['allowed']): ?>
                        <form method="POST" class="reply-form">
                            <?php echo CSRFProtection::getTokenField(); ?>
                            <textarea name="reply_content" class="reply-textarea" placeholder="Write your reply here..." required></textarea>
                            <div class="reply-actions">
                                <div class="form-note">
                                    <small>Be respectful and follow our community guidelines</small>
                                </div>
                                <button type="submit" class="reply-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    Post Reply
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 8px; text-align: center;">
                            <i class="fas fa-clock"></i> <?php echo $can_create_post['reason']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="reply-form-container">
                    <div class="login-prompt">
                        <h3><i class="fas fa-sign-in-alt"></i> Join the Discussion</h3>
                        <p>
                            <a href="/login">Log in</a> or <a href="/register">create an account</a> to post replies and join our golf community.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>