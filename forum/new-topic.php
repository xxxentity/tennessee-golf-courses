<?php
// Password protection removed - forum is now public
// require_once '../includes/forum-auth.php';

// Include session security for consistent session handling
require_once '../includes/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - redirect to login
    header('Location: /login?redirect=' . urlencode('/forum/new-topic'));
    exit;
}

// Include rate limiting functionality
require_once '../includes/forum-rate-limit.php';
require_once '../includes/csrf.php';

// Check if user is logged in using secure session
$is_logged_in = SecureSession::isLoggedIn();

// Redirect to login if not authenticated
if (!$is_logged_in) {
    header('Location: /login?redirect=' . urlencode('/forum/new-topic'));
    exit;
}

$current_user_id = SecureSession::get('user_id');
$current_user_name = SecureSession::get('username', 'Anonymous');

// Check rate limits
$can_create_topic = ForumRateLimit::canCreateTopic($current_user_id);
$rate_limits = ForumRateLimit::getRemainingLimits($current_user_id);

// Get selected category from URL parameter
$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Forum categories
$categories = [
    1 => [
        'id' => 1,
        'name' => 'Course Reviews & Discussions',
        'description' => 'Share your experiences and reviews of Tennessee golf courses'
    ],
    2 => [
        'id' => 2,
        'name' => 'Equipment Talk',
        'description' => 'Discuss golf clubs, balls, gear, and equipment reviews'
    ],
    3 => [
        'id' => 3,
        'name' => 'Tournament & Events',
        'description' => 'Local tournaments, events, and golf meetups in Tennessee'
    ],
    4 => [
        'id' => 4,
        'name' => 'Tips & Instruction',
        'description' => 'Golf tips, lessons, and technique discussions'
    ],
    5 => [
        'id' => 5,
        'name' => 'General Golf Chat',
        'description' => 'General golf discussions, news, and casual conversation'
    ],
    6 => [
        'id' => 6,
        'name' => 'Site Feedback',
        'description' => 'Suggestions and feedback about Tennessee Golf Courses website'
    ]
];

// Handle form submission
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token first
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!CSRFProtection::validateToken($csrf_token)) {
        $errors[] = 'Security token expired or invalid. Please try again.';
    }
    
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    
    // Check rate limits
    if (!$can_create_topic['allowed']) {
        $errors[] = $can_create_topic['reason'];
    }
    
    // Validation
    if (empty($title)) {
        $errors[] = 'Topic title is required.';
    } elseif (strlen($title) < 5) {
        $errors[] = 'Topic title must be at least 5 characters long.';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Topic title must be less than 255 characters.';
    }
    
    if (empty($content)) {
        $errors[] = 'Topic content is required.';
    } elseif (strlen($content) < 10) {
        $errors[] = 'Topic content must be at least 10 characters long.';
    }
    
    if (!isset($categories[$category_id])) {
        $errors[] = 'Please select a valid category.';
    }
    
    // Check for spam content
    $spam_check = ForumRateLimit::isSpamContent($title . ' ' . $content);
    if ($spam_check['is_spam']) {
        $errors[] = 'Content appears to be spam. Please review and try again.';
    }
    
    // If no errors, create the topic (in real implementation, save to database)
    if (empty($errors)) {
        // Record the topic creation for rate limiting
        ForumRateLimit::recordTopicCreated($current_user_id);
        
        // Create a new topic with a unique ID
        $new_topic_id = time() . '_' . $current_user_id; // Temporary ID generation
        
        // Initialize session topics array if not exists
        if (!isset($_SESSION['forum_topics'])) {
            $_SESSION['forum_topics'] = [];
        }
        
        // Add the new topic to session storage
        $_SESSION['forum_topics'][$new_topic_id] = [
            'id' => $new_topic_id,
            'title' => $title,
            'content' => $content,
            'author' => $current_user_name,
            'author_id' => $current_user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'category_id' => $category_id,
            'view_count' => 0,
            'reply_count' => 0,
            'last_reply_at' => null,
            'last_reply_author' => null,
            'is_pinned' => false,
            'is_locked' => false
        ];
        
        $_SESSION['forum_success'] = 'Your topic has been created successfully!';
        
        // Redirect to the category page
        header('Location: /forum/category/' . $category_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Topic - Golf Forum - Tennessee Golf Courses</title>
    <meta name="description" content="Start a new discussion in the Tennessee Golf Community Forum - share your thoughts and connect with fellow golfers.">
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
        
        .new-topic-container {
            background: var(--bg-white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            padding: 2.5rem;
            margin-bottom: 2rem;
        }
        
        .new-topic-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .new-topic-header h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .new-topic-header p {
            color: var(--text-gray);
            font-size: 1.1rem;
        }
        
        .topic-form {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .form-group label i {
            margin-right: 0.5rem;
            width: 16px;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--bg-light);
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, 0.1);
        }
        
        .form-select {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--bg-light);
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            background: white;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        
        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, 0.1);
        }
        
        .form-textarea {
            width: 100%;
            min-height: 250px;
            padding: 1rem;
            border: 2px solid var(--bg-light);
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            line-height: 1.6;
            resize: vertical;
            transition: border-color 0.3s ease;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, 0.1);
        }
        
        .category-option {
            padding: 0.75rem;
            font-size: 1rem;
        }
        
        .character-count {
            text-align: right;
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-top: 0.5rem;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--text-gray);
            padding: 1rem 2.5rem;
            border: 2px solid var(--bg-light);
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .error-message {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .error-message ul {
            margin: 0;
            padding-left: 1.5rem;
        }
        
        .success-message {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            border: 1px solid #86efac;
            color: #166534;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .posting-guidelines {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .posting-guidelines h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .posting-guidelines ul {
            margin: 0;
            padding-left: 1.5rem;
            color: var(--text-gray);
        }
        
        .posting-guidelines li {
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .new-topic-container {
                padding: 1.5rem;
            }
            
            .new-topic-header h1 {
                font-size: 2rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn-primary,
            .btn-secondary {
                padding: 1rem;
                justify-content: center;
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
                <strong>Create New Topic</strong>
            </nav>
            
            <!-- New Topic Container -->
            <div class="new-topic-container">
                <div class="new-topic-header">
                    <h1><i class="fas fa-plus-circle"></i> Start a New Discussion</h1>
                    <p>Share your thoughts and connect with the Tennessee golf community</p>
                    <div style="background: #f0f9ff; border: 1px solid #0ea5e9; color: #0369a1; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                        <small><i class="fas fa-info-circle"></i> 
                        Daily Limits: <?php echo $rate_limits['topics']; ?>/<?php echo $rate_limits['topics_limit']; ?> topics remaining, 
                        <?php echo $rate_limits['posts']; ?>/<?php echo $rate_limits['posts_limit']; ?> posts remaining
                        </small>
                    </div>
                </div>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <strong><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="topic-form">
                    <?php echo CSRFProtection::getTokenField(); ?>
                    <div class="form-group">
                        <label for="category_id">
                            <i class="fas fa-folder"></i>
                            Category
                        </label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">Select a category...</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo ($selected_category == $category['id']) ? 'selected' : ''; ?>
                                        class="category-option">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">
                            <i class="fas fa-heading"></i>
                            Topic Title
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-input" 
                               placeholder="Enter a descriptive title for your topic..."
                               value="<?php echo htmlspecialchars($title ?? ''); ?>"
                               maxlength="255" 
                               required>
                        <div class="character-count">
                            <span id="title-count">0</span>/255 characters
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="content">
                            <i class="fas fa-edit"></i>
                            Content
                        </label>
                        <textarea id="content" 
                                  name="content" 
                                  class="form-textarea" 
                                  placeholder="Share your thoughts, ask questions, or start a discussion..."
                                  required><?php echo htmlspecialchars($content ?? ''); ?></textarea>
                        <div class="character-count">
                            <span id="content-count">0</span> characters
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Create Topic
                        </button>
                        <a href="/forum" class="btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
                
                <!-- Posting Guidelines -->
                <div class="posting-guidelines">
                    <h3><i class="fas fa-info-circle"></i> Posting Guidelines</h3>
                    <ul>
                        <li>Choose a clear, descriptive title that summarizes your topic</li>
                        <li>Select the most appropriate category for your discussion</li>
                        <li>Be respectful and courteous to all community members</li>
                        <li>Stay on topic and keep discussions golf-related</li>
                        <li>Use proper spelling and grammar for better readability</li>
                        <li>Search existing topics before creating a new one</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include '../includes/footer.php'; ?>
    
    <script>
        // Character counters
        const titleInput = document.getElementById('title');
        const titleCount = document.getElementById('title-count');
        const contentTextarea = document.getElementById('content');
        const contentCount = document.getElementById('content-count');
        
        function updateTitleCount() {
            const count = titleInput.value.length;
            titleCount.textContent = count;
            titleCount.style.color = count > 255 ? '#dc2626' : '#6b7280';
        }
        
        function updateContentCount() {
            const count = contentTextarea.value.length;
            contentCount.textContent = count;
        }
        
        titleInput.addEventListener('input', updateTitleCount);
        contentTextarea.addEventListener('input', updateContentCount);
        
        // Initialize counters
        updateTitleCount();
        updateContentCount();
        
        // Category description tooltip (optional enhancement)
        const categorySelect = document.getElementById('category_id');
        const categories = <?php echo json_encode($categories); ?>;
        
        categorySelect.addEventListener('change', function() {
            const selectedId = parseInt(this.value);
            if (categories[selectedId]) {
                // Could show description as tooltip or help text
                console.log('Selected:', categories[selectedId].description);
            }
        });
    </script>
</body>
</html>