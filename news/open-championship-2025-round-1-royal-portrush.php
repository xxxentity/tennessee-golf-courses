<?php
session_start();
require_once '../config/database.php';

$article_slug = 'open-championship-2025-round-1-royal-portrush';
$article_title = 'Five Players Share Lead as Royal Portrush Shows Its Teeth';

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
    <title>Five Players Share Lead as Royal Portrush Shows Its Teeth in Opening Round - Tennessee Golf Courses</title>
    <meta name="description" content="Matt Fitzpatrick among five tied for the lead after a challenging first round at the 2025 Open Championship, while Scottie Scheffler trails by one shot.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/tab-logo.png?v=2">
    <link rel="shortcut icon" href="../images/logos/tab-logo.png?v=2">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    <style>
        /* Logo image styling */
        .logo-image {
            height: 100px;
            width: auto;
            object-fit: contain;
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            max-height: 100px;
        }

        .nav-logo:hover .logo-image {
            transform: scale(1.05);
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
        }

        /* Navigation auth section on the right */
        .nav-auth {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 6px;
            margin-left: auto;
            margin-right: 8px;
            flex-wrap: nowrap;
            max-width: 400px;
        }

        .login-btn {
            background: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color) !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            padding: 8px 16px !important;
            font-size: 16px;
            font-weight: 500;
            width: auto;
            text-align: center;
            line-height: 1.2;
            white-space: nowrap;
        }

        .login-btn:hover {
            background: var(--primary-color);
            color: var(--text-light) !important;
            transform: translateY(-1px);
        }

        .register-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light) !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-light);
            padding: 8px 16px !important;
            font-size: 16px;
            font-weight: 500;
            width: auto;
            text-align: center;
            line-height: 1.2;
            white-space: nowrap;
        }

        .register-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            transform: translateY(-1px);
            box-shadow: var(--shadow-medium);
        }

        .article-hero {
            height: 50vh;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/news/open-championship-2025-round-1/royal-portrush-round1.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 120px;
        }
        
        .article-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 3rem 2rem;
            line-height: 1.7;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        .article-date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .article-category {
            background: #4a7c59;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .leaderboard {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin: 2rem 0;
        }
        
        .leaderboard h3 {
            color: #2c5234;
            margin-bottom: 1rem;
        }
        
        .leaderboard-entry {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .leaderboard-entry:last-child {
            border-bottom: none;
        }
        
        .player-name {
            font-weight: 600;
            color: #2c5234;
        }
        
        .player-score {
            font-weight: 700;
            color: #d32f2f;
        }
        
        .quote-box {
            background: #e8f5e8;
            border-left: 4px solid #4a7c59;
            padding: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Article Hero -->
    <section class="article-hero">
        <div class="hero-content">
            <h1>Five Players Share Lead as Royal Portrush Shows Its Teeth</h1>
            <p>Challenging conditions and ever-changing weather define opening day at the 153rd Open Championship</p>
        </div>
    </section>

    <!-- Article Content -->
    <div class="article-content">
        <div class="article-meta">
            <div class="article-date">
                <i class="fas fa-calendar-alt"></i>
                <span>July 17, 2025</span>
            </div>
            <span class="article-category">Major Championship</span>
        </div>

        <p>PORTRUSH, Northern Ireland — Royal Portrush Golf Club reminded the world's best golfers why it's one of the most challenging venues in championship golf, as ever-changing weather conditions and demanding hole locations produced a bunched leaderboard after the first round of the 153rd Open Championship.</p>

        <div class="leaderboard">
            <h3><i class="fas fa-trophy"></i> First Round Leaderboard</h3>
            <div class="leaderboard-entry">
                <span class="player-name">Matt Fitzpatrick</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Harris English</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Christiaan Bezuidenhout</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Haotong Li</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Jacob Skov Olesen</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Scottie Scheffler</span>
                <span class="player-score">-3 (68)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Rory McIlroy</span>
                <span class="player-score">-1 (70)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Jon Rahm</span>
                <span class="player-score">-1 (70)</span>
            </div>
        </div>

        <p>Five players emerged from a grueling opening day to share the lead at 4-under par, with England's Matt Fitzpatrick headlining a diverse group that includes Harris English, Christiaan Bezuidenhout, China's Haotong Li, and Denmark's Jacob Skov Olesen. The quintet managed to navigate Royal Portrush's notorious challenges while 31 players total reached red figures, though no one could take the course particularly low.</p>

        <br>

        <p>World No. 1 Scottie Scheffler positioned himself perfectly just one shot back despite hitting only three fairways in his round of 68. The reigning PGA Championship winner showed his championship mettle with a late flurry of birdies after struggling with accuracy throughout much of his round.</p>

        <div class="quote-box">
            "When it's raining sideways, believe it or not, it's not that easy to get the ball in the fairway," Scheffler said with characteristic understatement after his round. "I was just trying to plot my way around and finish strong."
        </div>

        <p>The day's story was as much about survival as scoring, with Royal Portrush delivering everything from sunshine to sideways rain and gusting winds that challenged even the most seasoned major championship veterans. The Northern Ireland links course, hosting The Open for just the second time since 1951, reminded players why it's considered one of the toughest tests in golf.</p>

        <br>

        <p>Local favorite Rory McIlroy, playing in front of passionate home crowds, managed a respectable 1-under 70 despite the enormous pressure and expectations. The four-time major winner found himself three shots off the pace but well within striking distance heading into Friday's second round.</p>

        <br>

        <p>Former world No. 1 Jon Rahm also sits at 1-under alongside McIlroy, while defending champion Xander Schauffele faced a tougher day, struggling to find his rhythm in the challenging conditions.</p>
        
        <br>

        <p>"This course gives you everything," said Fitzpatrick, who leads the field alongside four others. "You've got to be so precise with your iron play, and when the weather changes every hole like it did today, you're constantly adjusting. I'm pleased to be where I am, but there's a long way to go."</p>
        
        <br>

        <p>The weather forecast suggests more of the same for Friday's second round, with gusty winds and intermittent rain expected to continue testing the 156-player field. With the cut line traditionally falling around even par at The Open, many big names will need to improve significantly to make the weekend.</p>

        <br>

        <p>Harris English, sharing the lead after his bogey-free 67, emphasized the importance of patience at Royal Portrush. "You can't force anything out here," he said. "The course will punish you if you get too aggressive. I just tried to stay in the present and take what the course gave me."</p>

        <br>

        <p>As the 153rd Open Championship heads into its second round, the stage is set for another dramatic day at one of golf's most challenging venues. With the weather continuing to play a major factor and Royal Portrush showing its formidable defenses, Friday promises to be equally demanding for the world's best golfers.</p>
        
        <br>

        <p>The cut will be made after Friday's second round, with the top 70 players and ties, plus those within 10 shots of the lead, advancing to the weekend. With such tight scoring and challenging conditions, every shot will matter as players battle both the course and the elements in pursuit of golf's oldest major championship.</p>
    </div>

    <!-- Comments Section -->
    <section class="comments-section" style="background: #f8f9fa; padding: 4rem 0;">
        <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 2rem;">
            <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
                <h2 style="color: #2c5234; margin-bottom: 1rem;">Discussion</h2>
                <p>Share your thoughts on this article</p>
            </div>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success" style="background: rgba(34, 197, 94, 0.1); color: #16a34a; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid rgba(34, 197, 94, 0.2);">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid rgba(239, 68, 68, 0.2);">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Comment Form (Only for logged in users) -->
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 3rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Leave a Comment</h3>
                    <form method="POST" class="comment-form">
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="comment_text" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Comment:</label>
                            <textarea id="comment_text" name="comment_text" rows="4" placeholder="Share your thoughts on this article..." required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit; font-size: 14px; resize: vertical; min-height: 100px;"></textarea>
                        </div>
                        <button type="submit" style="background: #2c5234; color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Post Comment</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt" style="background: white; padding: 2rem; border-radius: 15px; text-align: center; margin-bottom: 3rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <p><a href="/login" style="color: #2c5234; font-weight: 600; text-decoration: none;">Login</a> or <a href="/register" style="color: #2c5234; font-weight: 600; text-decoration: none;">Register</a> to join the discussion</p>
                </div>
            <?php endif; ?>
            
            <!-- Display Comments -->
            <div class="comments-container">
                <?php if (empty($comments)): ?>
                    <div class="no-comments" style="text-align: center; padding: 3rem; color: #666;">
                        <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; color: #ddd;"></i>
                        <p>No comments yet. Be the first to share your thoughts!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-card" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <div class="comment-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <div class="commenter-name" style="font-weight: 600; color: #2c5234;"><?php echo htmlspecialchars($comment['username']); ?></div>
                                <div class="comment-date" style="color: #666; font-size: 0.9rem;"><?php echo date('M j, Y • g:i A', strtotime($comment['created_at'])); ?></div>
                            </div>
                            <p style="line-height: 1.6; color: #333;"><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="../images/logos/logo.png" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/#courses">Golf Courses</a></li>
                        <li><a href="/#reviews">Reviews</a></li>
                        <li><a href="/#news">News</a></li>
                        <li><a href="/#about">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="#">Nashville Area</a></li>
                        <li><a href="#">Chattanooga Area</a></li>
                        <li><a href="#">Knoxville Area</a></li>
                        <li><a href="#">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul>
                        <li><i class="fas fa-envelope"></i> info@tennesseegolfcourses.com</li>
                        <li><i class="fas fa-phone"></i> (615) 555-GOLF</li>
                        <li><i class="fas fa-map-marker-alt"></i> Nashville, TN</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../script.js"></script>
</body>
</html>