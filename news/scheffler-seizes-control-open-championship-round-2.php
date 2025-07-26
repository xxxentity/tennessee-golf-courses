<?php
session_start();
require_once '../config/database.php';

$article_slug = 'scheffler-seizes-control-open-championship-round-2';
$article_title = 'Scheffler Seizes Control with Career-Best 64';

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
    <title>Scheffler Seizes Control with Career-Best 64 at Royal Portrush - Tennessee Golf Courses</title>
    <meta name="description" content="World No. 1 Scottie Scheffler fires a stunning 7-under 64 to take the lead at the Open Championship, his lowest round in a major championship.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/tab-logo.png">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/news/open-championship-round-2/scheffler-64.jpg');
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
        
        .leaderboard-entry.leader {
            background: linear-gradient(90deg, #fff3cd, transparent);
            margin: 0 -1rem;
            padding: 0.75rem 1rem;
            border-radius: 5px;
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
        
        .stat-highlight {
            background: #f0f8f0;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
            border: 1px solid #4a7c59;
        }
        
        .stat-highlight h4 {
            color: #2c5234;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Article Hero -->
    <section class="article-hero">
        <div class="hero-content">
            <h1>Scheffler Seizes Control with Career-Best 64</h1>
            <p>World No. 1 delivers masterclass performance to take Open Championship lead at Royal Portrush</p>
        </div>
    </section>

    <!-- Article Content -->
    <div class="article-content">
        <div class="article-meta">
            <div class="article-date">
                <i class="fas fa-calendar-alt"></i>
                <span>July 18, 2025</span>
            </div>
            <span class="article-category">Major Championship</span>
        </div>

        <p>PORTRUSH, Northern Ireland — Scottie Scheffler delivered a masterpiece at Royal Portrush on Friday, firing a career-best 7-under 64 in major championship play to seize control of the 153rd Open Championship and position himself for his fourth major title.</p>

        <div class="stat-highlight">
            <h4><i class="fas fa-chart-line"></i> Round 2 Highlight</h4>
            <p><strong>Scottie Scheffler's 64:</strong> Career-low round in a major championship, just one shot off the Royal Portrush course record. Eight birdies with none closer than 7 feet, including five from 10+ feet and a 35-foot bomb on the 6th hole.</p>
        </div>

        <div class="leaderboard">
            <h3><i class="fas fa-trophy"></i> Halfway Leaderboard</h3>
            <div class="leaderboard-entry leader">
                <span class="player-name">Scottie Scheffler</span>
                <span class="player-score">-10 (68-64)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Matt Fitzpatrick</span>
                <span class="player-score">-9 (67-66)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Brian Harman</span>
                <span class="player-score">-8 (70-64)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Haotong Li</span>
                <span class="player-score">-8 (67-67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Tyrrell Hatton</span>
                <span class="player-score">-5 (71-66)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Bob MacIntyre</span>
                <span class="player-score">-5 (72-65)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Rory McIlroy</span>
                <span class="player-score">-3 (70-69)</span>
            </div>
        </div>

        <p>The world No. 1's round was a thing of beauty, with Scheffler making eight birdies in near-perfect conditions that were a stark contrast to Thursday's challenging weather. What made the performance even more remarkable was the quality of his putting—none of his eight birdies came from closer than seven feet, with five rolling in from the 10-foot range or beyond.</p>

        <br>

        <p>Scheffler's 64 not only established him as the man to beat heading into the weekend but also made history. He became the first world No. 1 to lead The Open Championship at the halfway point since Tiger Woods accomplished the feat in 2006, adding another layer of significance to his commanding position.</p>

        <div class="quote-box">
            "I felt really good with my putting today," Scheffler said after signing his card. "When you're hitting it to 10, 12, 15 feet and making those putts, that's when you shoot really low scores. The conditions were definitely more favorable today, and I was able to take advantage."
        </div>

        <p>Behind Scheffler, England's Matt Fitzpatrick maintained his strong play from the opening round, adding a solid 66 to sit just one shot back at 9-under. Fitzpatrick ignited the back nine with four consecutive birdies, showcasing the type of momentum that could prove dangerous over the weekend.</p>

        <br>

        <p>Brian Harman emerged as a genuine contender with a bogey-free 64 that matched Scheffler's low round of the day. The 2023 Open champion showed he knows how to handle the pressure of major championship golf, moving to 8-under and just two shots off the lead.</p>

        <br>

        <p>China's Haotong Li, who shared the first-round lead, remained in contention with a steady 67 to sit at 8-under alongside Harman. Li's consistent play through two rounds has established him as a dark horse candidate for his first major championship.</p>

        <br>

        <p>While Scheffler soared, some notable names struggled to keep pace. Rory McIlroy, playing in front of adoring home crowds, could only manage a 69 to sit at 3-under, seven shots back and facing an uphill battle. The four-time major winner will need something special over the weekend to get back into contention.</p>

        <br>

        <p>Bryson DeChambeau provided one of the day's most dramatic storylines, bouncing back from an opening-round 78 with a brilliant 65—a 13-shot improvement that epitomized the wild swings possible at major championships. While his comeback may have come too late for a weekend run, it showcased the American's never-say-die attitude.</p>

        <div class="stat-highlight">
            <h4><i class="fas fa-target"></i> Scheffler's Statistical Dominance</h4>
            <p>Through 36 holes, Scheffler ranks second in the field in putting, gaining more than six strokes on the greens. His combination of ball-striking and putting has created a potent formula for major championship success.</p>
        </div>

        <p>The weather forecast for the weekend suggests more challenging conditions may return, which could play into Scheffler's hands. The 28-year-old American has proven time and again that he thrives when conditions get tough, using his superior course management and mental fortitude to separate himself from the field.</p>

        <br>

        <p>Friday's second round also saw the cut line fall at 1-over par, with several big names missing the weekend. The brutal nature of Royal Portrush claimed its victims, but for those who survived, the weekend promises to deliver more drama.</p>

        <br>

        <p>As Scheffler heads into the weekend with his sights set on a fourth major championship, his recent track record provides confidence. He has converted his last nine 54-hole leads into victories, a streak that includes his Masters and PGA Championship wins this year.</p>

        <div class="quote-box">
            "I've been in this position before," Scheffler said when asked about leading a major championship. "I know what it takes, and I'll just focus on executing one shot at a time. There's still a lot of golf left, and this course can humble you quickly if you're not careful."
        </div>

        <p>With Royal Portrush set to test the field once again over the weekend, Saturday's third round will be crucial in determining whether Scheffler can maintain his advantage or if one of the challengers can mount a serious charge for the Claret Jug.</p>
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