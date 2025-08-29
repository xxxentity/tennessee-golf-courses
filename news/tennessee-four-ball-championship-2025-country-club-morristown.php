<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../includes/seo.php';
require_once '../config/database.php';

// Article data for SEO
$article_data = [
    'title' => '55th Tennessee Four-Ball Championship Underway at The Country Club in Morristown',
    'description' => 'The 55th Tennessee Four-Ball Championship kicks off at The Country Club in Morristown with 36 teams competing for the state title through stroke play qualifying and match play rounds.',
    'image' => '/images/news/tennessee-four-ball-championship-2025-country-club-morristown/main.webp',
    'type' => 'article',
    'author' => 'Cole Harrington',
    'date' => '2025-08-21',
    'category' => 'Tennessee News'
];

SEO::setupArticlePage($article_data);

$article_slug = 'tennessee-four-ball-championship-2025-country-club-morristown';
$article_title = '55th Tennessee Four-Ball Championship Underway at The Country Club in Morristown';

// Check if user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    $comment_text = trim($_POST['comment_text']);
    $user_id = $_SESSION['user_id'];
    
    if (!empty($comment_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO news_comments (user_id, article_slug, article_title, comment_text) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $article_slug, $article_title, $comment_text]);
            $success_message = "Your comment has been posted successfully!";
        } catch (PDOException $e) {
            $error_message = "Error posting comment. Please try again.";
        }
    } else {
        $error_message = "Please write a comment.";
    }
}

// Get existing comments
try {
    $stmt = $pdo->prepare("
        SELECT nc.*, u.username 
        FROM news_comments nc 
        JOIN users u ON nc.user_id = u.id 
        WHERE nc.article_slug = ? AND nc.is_approved = TRUE
        ORDER BY nc.created_at DESC
    ");
    $stmt->execute([$article_slug]);
    $comments = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $comments = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo SEO::generateMetaTags(); ?>
    <link rel="stylesheet" href="/styles.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=5">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=5">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .article-page {
            padding-top: 0px;
            min-height: 100vh;
            background: var(--bg-light);
        }
        
        .article-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .article-header {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
        }
        
        .article-category {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .article-title {
            font-size: 2.2rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .article-image {
            width: 100%;
            max-width: 800px;
            height: auto;
            border-radius: 15px;
            margin: 2rem 0;
            box-shadow: var(--shadow-light);
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
            line-height: 1.8;
            font-size: 1.1rem;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        .article-content h3 {
            color: var(--primary-color);
            margin: 2rem 0 1rem 0;
            font-size: 1.4rem;
        }
        
        .highlight-box {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            border-left: 5px solid var(--primary-color);
            margin: 2rem 0;
        }
        
        .quote {
            font-style: italic;
            font-size: 1.2rem;
            color: var(--primary-color);
            border-left: 4px solid var(--secondary-color);
            padding-left: 1.5rem;
            margin: 2rem 0;
        }
        
        .tournament-schedule {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            border: 2px solid var(--secondary-color);
        }
        
        .tournament-schedule h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .schedule-day {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .schedule-day:last-child {
            border-bottom: none;
        }
        
        .comments-section {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin-top: 2rem;
        }
        
        .comments-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .comment-form {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .comment-form textarea {
            width: 100%;
            min-height: 100px;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
            margin-bottom: 1rem;
        }
        
        .comment-form textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .comment-form button {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .comment-form button:hover {
            background: var(--secondary-color);
        }
        
        .comment {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .comment-date {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
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
        
        .comeback-box {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin: 2rem 0;
        }
        
        .comeback-box h3 {
            margin-bottom: 1rem;
            color: white;
        }
        
        @media screen and (max-width: 768px) {
            .article-title {
                font-size: 1.8rem;
            }
            
            .article-container {
                padding: 1rem;
            }
            
            .article-header, .article-content {
                padding: 2rem;
            }
            
            .schedule-day {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <!-- Article Header -->
            <header class="article-header">
                <div class="article-category">Tennessee News</div>
                <h1 class="article-title">55th Tennessee Four-Ball Championship Underway at The Country Club in Morristown</h1>
                <div class="article-meta">
                    <span><i class="far fa-calendar"></i> August 21, 2025</span>
                    <span><i class="far fa-clock"></i> 2:45 PM</span>
                    <span><a href="/profile?username=cole-harrington" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/cole-harrington.webp" alt="Cole Harrington" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;">Cole Harrington</span></a></span>
                    <span><i class="far fa-map-marker-alt"></i> Morristown, TN</span>
                </div>
            </header>

            <!-- Article Image -->
            <img src="/images/news/tennessee-four-ball-championship-2025-country-club-morristown/main.webp" alt="Players compete in the 55th Tennessee Four-Ball Championship at The Country Club in Morristown" class="article-image">

            <!-- Article Content -->
            <article class="article-content">
                <p><strong>MORRISTOWN, Tenn.</strong> – The 55th Tennessee Four-Ball Championship is currently underway at <a href="/courses/the-country-club-morristown" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">The Country Club</a> in Morristown, marking the fourth time this prestigious venue has hosted the tournament. Thirty-six teams are competing for the state championship title through a combination of stroke play qualifying and match play rounds.</p>

                <p>The tournament format features 18 holes of stroke play qualifying on Monday and Tuesday, with the top 16 teams advancing to match play for the final two days. Wednesday will showcase the Round of 16 and Quarterfinals, while Thursday's competition will determine the semifinalists and ultimately crown the 2025 champions.</p>

                <div class="tournament-schedule">
                    <h4><i class="fas fa-calendar-alt"></i> Tournament Schedule</h4>
                    <div class="schedule-day">
                        <span><strong>Monday-Tuesday:</strong></span>
                        <span>Stroke Play Qualifying (36 teams)</span>
                    </div>
                    <div class="schedule-day">
                        <span><strong>Wednesday:</strong></span>
                        <span>Round of 16 & Quarterfinals</span>
                    </div>
                    <div class="schedule-day">
                        <span><strong>Thursday:</strong></span>
                        <span>Semifinals & Championship Match</span>
                    </div>
                </div>

                <h3>Prestigious Venue Returns</h3>

                <p>The Country Club in Morristown brings significant championship pedigree to this week's competition. The full-service private country club and 18-hole golf course has been providing recreation and activities for families since 1955, establishing itself as one of East Tennessee's premier golf destinations.</p>

                <div class="highlight-box">
                    <p><strong>Championship History:</strong> The Country Club has previously hosted the Tennessee State PGA Championship, the State PGA Four-Ball tournament, and served as a Nationwide Tour Qualifying venue for the Knoxville Open, demonstrating its ability to host elite-level competitions.</p>
                </div>

                <p>This marks the fourth appearance of the Tennessee Four-Ball Championship at the Morristown layout, a testament to the course's quality and the club's organizational capabilities. The venue's championship experience ensures that this week's competitors will face a world-class test of their four-ball skills.</p>

                <h3>Tournament Significance</h3>

                <p>The Tennessee Four-Ball Championship represents one of the state's most prestigious amateur golf competitions, featuring teams from across Tennessee competing in the popular four-ball format. The tournament serves as part of the Tennessee Golf Association's comprehensive championship schedule, which includes 21 different championships throughout 2025.</p>

                <p>Four-ball competition requires teams of two players to work together strategically, with each player playing their own ball throughout the round and the team's score being determined by the lower score of the two players on each hole. This format emphasizes both individual skill and team strategy, creating compelling competition throughout the field.</p>

                <div class="quote">
                    "The Tennessee Four-Ball Championship has grown into one of our signature events, attracting the state's top amateur talent and showcasing the strength of Tennessee golf."
                </div>

                <h3>Competition Format and Field</h3>

                <p>The 36-team field represents golfers from across Tennessee, with qualifying rounds determining which 16 teams will advance to the match play portion of the championship. The stroke play qualifying format ensures that only the most consistent and skilled teams will compete for the title during the final two days.</p>

                <p>Match play brings a different dynamic to the competition, with teams facing direct elimination if they lose their matches. This format often produces dramatic moments and comeback victories, as teams must adapt their strategy based on their opponents' performance rather than simply posting the lowest scores possible.</p>

                <p>The Country Club's layout provides an ideal venue for four-ball competition, with strategic challenges that reward both aggressive play and course management. The course's championship conditioning and well-designed holes will test every aspect of the competitors' games throughout the week.</p>

                <div class="comeback-box">
                    <h3><i class="fas fa-trophy"></i> Championship Conclusion Tomorrow</h3>
                    <p><strong>Come back tomorrow for complete coverage of the final day's competition and learn who will be crowned the 2025 Tennessee Four-Ball Champions!</strong></p>
                    <p>We'll have full results, highlights from the championship match, and analysis of the tournament's most memorable moments.</p>
                </div>

                <p>The 55th Tennessee Four-Ball Championship continues to build on the tournament's rich history while showcasing the current strength of amateur golf in Tennessee. With competitive qualifying rounds setting up an exciting match play bracket, this week's championship at The Country Club promises to deliver the high-quality competition that has made this event a cornerstone of Tennessee golf.</p>
            </article>

            <!-- Comments Section -->
            <section class="comments-section">
                <div class="comments-header">
                    <i class="fas fa-comments"></i>
                    <span>Comments (<?php echo count($comments); ?>)</span>
                </div>

                <?php if (isset($success_message)): ?>
                    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($is_logged_in): ?>
                    <div class="comment-form">
                        <form method="POST">
                            <textarea name="comment_text" placeholder="Share your thoughts about the championship..." required></textarea>
                            <button type="submit">Post Comment</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt">
                        <p><a href="/login">Login</a> or <a href="/register">register</a> to join the conversation and share your thoughts!</p>
                    </div>
                <?php endif; ?>

                <div class="comments-list">
                    <?php if (empty($comments)): ?>
                        <p style="text-align: center; color: var(--text-gray); padding: 2rem;">No comments yet. Be the first to share your thoughts!</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <div class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></div>
                                <div class="comment-date"><?php echo date('M j, Y g:i A', strtotime($comment['created_at'])); ?></div>
                                <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="/images/logos/logo.webp" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61579553544749" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        <a href="https://x.com/TNGolfCourses" target="_blank" rel="noopener noreferrer"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/tennesseegolfcourses/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@TennesseeGolf" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/courses">Golf Courses</a></li>
                        <li><a href="/reviews">Reviews</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/about">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="/courses?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="/courses?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="/courses?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="/courses?region=Memphis Area">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-of-service">Terms of Service</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>