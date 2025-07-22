<?php
session_start();
require_once '../config/database.php';

$article_slug = 'scheffler-extends-lead-open-championship-round-3';
$article_title = 'Scheffler Extends Lead to Four Shots with Bogey-Free 67';

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
        SELECT nc.*, u.first_name, u.last_name 
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
    <title>Scheffler Extends Lead to Four Shots with Bogey-Free 67 at Royal Portrush - Tennessee Golf Courses</title>
    <meta name="description" content="Scottie Scheffler fires a bogey-free 67 in Round 3 at Royal Portrush to extend his lead to four shots, while Rory McIlroy charges with eagle on 12th hole.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/news/open-championship-round-3/scheffler-family.jpg');
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
        
        .eagle-box {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
            text-align: center;
            color: #2c5234;
        }
        
        .eagle-box h4 {
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
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
                    <span>Wind: <span id="wind-speed">5 mph</span></span>
                </div>
                <div class="condition-item">
                    <i class="fas fa-eye"></i>
                    <span>Visibility: <span id="visibility">10 mi</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <nav class="nav">
            <div class="nav-container">
                <a href="../index.php" class="nav-logo">
                    <img src="../images/logos/logo.png" alt="Tennessee Golf Courses" class="logo-image">
                </a>
                
                <ul class="nav-menu" id="nav-menu">
                    <li><a href="../index.php" class="nav-link">Home</a></li>
                    <li><a href="../index.php#courses" class="nav-link">Courses</a></li>
                    <li><a href="../index.php#reviews" class="nav-link">Reviews</a></li>
                    <li><a href="../index.php#news" class="nav-link">News</a></li>
                    <li><a href="../index.php#about" class="nav-link">About</a></li>
                    <li><a href="../index.php#contact" class="nav-link">Contact</a></li>
                </ul>
                
                <div class="nav-auth">
                    <a href="../auth/login.php" class="nav-link login-btn">Login</a>
                    <a href="../auth/register.php" class="nav-link register-btn">Join Free</a>
                </div>
                
                <div class="nav-toggle" id="nav-toggle">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Article Hero -->
    <section class="article-hero">
        <div class="hero-content">
            <h1>Scheffler Extends Lead to Four Shots with Bogey-Free 67</h1>
            <p>World No. 1 maintains commanding position heading into Sunday's final round at Royal Portrush</p>
        </div>
    </section>

    <!-- Article Content -->
    <div class="article-content">
        <div class="article-meta">
            <div class="article-date">
                <i class="fas fa-calendar-alt"></i>
                <span>July 19, 2025</span>
            </div>
            <span class="article-category">Major Championship</span>
        </div>

        <p>PORTRUSH, Northern Ireland — Scottie Scheffler delivered another clinical display of championship golf on Saturday, firing a bogey-free 4-under 67 to extend his lead to four shots heading into Sunday's final round of the 153rd Open Championship. The world No. 1's third-round masterpiece included an eagle-birdie sequence on holes 7 and 8 that effectively put the Claret Jug within his grasp.</p>

        <div class="stat-highlight">
            <h4><i class="fas fa-chart-line"></i> Scheffler's Saturday Dominance</h4>
            <p><strong>Bogey-Free 67:</strong> Only player in the final eight groups to avoid a bogey. Made eagle on 7th, birdie on 8th, and birdie on the treacherous 16th "Calamity Corner" for the third consecutive day.</p>
        </div>

        <div class="leaderboard">
            <h3><i class="fas fa-trophy"></i> 54-Hole Leaderboard</h3>
            <div class="leaderboard-entry leader">
                <span class="player-name">Scottie Scheffler</span>
                <span class="player-score">-14 (68-64-67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Haotong Li</span>
                <span class="player-score">-10 (67-67-69)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Matt Fitzpatrick</span>
                <span class="player-score">-9 (67-66-71)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Rory McIlroy</span>
                <span class="player-score">-8 (70-69-66)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Brian Harman</span>
                <span class="player-score">-6 (70-64-70)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Tyrrell Hatton</span>
                <span class="player-score">-5 (71-66-68)</span>
            </div>
        </div>

        <p>Playing with the poise of a seasoned major champion, Scheffler navigated Royal Portrush's challenges with remarkable precision. His eagle on the par-5 seventh hole came courtesy of a perfectly struck approach that set up a 10-foot putt, which he rolled home with confidence. Rather than let up, he immediately followed with a 15-foot birdie putt on the eighth, a sequence that showcased the killer instinct that has made him golf's most dominant player.</p>

        <div class="eagle-box">
            <h4><i class="fas fa-trophy"></i> Eagle-Birdie Punch</h4>
            <p>Holes 7-8: Eagle (10-foot putt) followed immediately by birdie (15-foot putt) - a devastating two-hole stretch that effectively put Scheffler in control of the championship.</p>
        </div>

        <p>Perhaps most impressive was Scheffler's performance on the 16th hole, the notorious "Calamity Corner" that has claimed countless victims over the years. For the third consecutive day, he conquered the demanding par-3, hitting a perfectly judged 3-iron to 15 feet below the cup before rolling in yet another birdie putt.</p>

        <div class="quote-box">
            "I need to stay patient and I know what I need to do tomorrow," Scheffler said after his round. "It's just about doing it. Sunday will be a fun and challenging day. It would be fun to win this but that's not what I will be thinking about when I go to sleep tonight or step on the first tee tomorrow. I will just be thinking about trying to execute."
        </div>

        <p>While Scheffler was extending his lead, the day's most electrifying moment belonged to Rory McIlroy. Playing in front of his passionate home supporters, the Northern Irishman provided a moment of magic on the par-5 12th hole, holing a long eagle putt that sent shockwaves through Royal Portrush. The roar from the galleries was deafening as McIlroy pumped his fist, temporarily igniting hopes of a hometown champion.</p>

        <br>

        <p>McIlroy's third-round 66 was a thing of beauty, featuring three early birdies and that memorable eagle. However, even after his impressive charge, he still trails Scheffler by six shots heading into Sunday. "The eagle on 12 was special," McIlroy said. "You could feel the energy from the crowd. I just need to keep pushing tomorrow and see what happens."</p>

        <br>

        <p>China's Haotong Li maintained his position as Scheffler's closest challenger, sitting four shots back after a solid 69. The 29-year-old has shown remarkable composure throughout the week and will tee off alongside Scheffler in Sunday's final group, representing Asia's best hope for a first major championship victory.</p>

        <br>

        <p>Matt Fitzpatrick, the 2022 U.S. Open champion, struggled to keep pace with the leaders, shooting 71 to fall five shots behind. Despite the disappointing round, the Englishman remains within striking distance and knows that Royal Portrush can produce dramatic swings in fortune.</p>

        <div class="stat-highlight">
            <h4><i class="fas fa-history"></i> Historical Context</h4>
            <p>Scheffler enters Sunday with a perfect 9-0 record when holding outright leads after three rounds on the PGA Tour. A victory would give him his first Open Championship and fourth major title, joining elite company before his 30th birthday.</p>
        </div>

        <p>The weather conditions on Saturday were markedly improved from the challenging first two days, with overcast skies giving way to calmer winds and dry conditions. This allowed for better scoring opportunities, though Scheffler was the only player in the late groups to take full advantage with a bogey-free round.</p>

        <br>

        <p>Defending champion Brian Harman had a more challenging day, shooting even-par 71 to fall eight shots behind. The American, who memorably captured his first major at Royal Liverpool last year, will need something extraordinary on Sunday to mount a defense of his title.</p>

        <br>

        <p>As the final round approaches, all eyes will be on Scheffler's pursuit of his first Claret Jug. With his recent track record of converting 54-hole leads and his obvious comfort level at Royal Portrush, the 28-year-old appears destined for glory. However, as Saturday's action proved, this championship still has the potential for dramatic twists and turns.</p>
        
         <br>       

        <p>"Tomorrow is going to be about staying in my process," Scheffler concluded. "I've been in this position before, and I know what it takes. But you still have to go out there and execute. Royal Portrush demands respect, and I'll give it that tomorrow."</p>

        <br>

        <p>Sunday's final round promises to be a compelling conclusion to the 153rd Open Championship, with Scheffler seeking to join the pantheon of Open champions while a talented field behind him hopes to produce one final surge along Northern Ireland's spectacular Causeway Coast.</p>
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
                    <p><a href="../auth/login.php" style="color: #2c5234; font-weight: 600; text-decoration: none;">Login</a> or <a href="../auth/register.php" style="color: #2c5234; font-weight: 600; text-decoration: none;">Register</a> to join the discussion</p>
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
                                <div class="commenter-name" style="font-weight: 600; color: #2c5234;"><?php echo htmlspecialchars($comment['first_name'] . ' ' . substr($comment['last_name'], 0, 1) . '.'); ?></div>
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
                        <li><a href="../index.php#courses">Golf Courses</a></li>
                        <li><a href="../index.php#reviews">Reviews</a></li>
                        <li><a href="../index.php#news">News</a></li>
                        <li><a href="../index.php#about">About Us</a></li>
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