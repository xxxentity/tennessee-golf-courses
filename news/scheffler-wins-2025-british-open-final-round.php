<?php
session_start();
require_once '../config/database.php';

$article_slug = 'scheffler-wins-2025-british-open-final-round';
$article_title = 'Scheffler Captures First Claret Jug with Dominant Victory';

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
    <title>Scheffler Captures First Claret Jug with Dominant Victory at Royal Portrush - Tennessee Golf Courses</title>
    <meta name="description" content="Scottie Scheffler wins his first British Open title with a commanding four-shot victory at Royal Portrush, completing the third leg of his career Grand Slam.">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/news/open-championship-final-2025/scottie-final-round.png');
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
        
        .final-leaderboard {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin: 2rem 0;
        }
        
        .final-leaderboard h3 {
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
        
        .leaderboard-entry.champion {
            background: linear-gradient(90deg, #ffd700, transparent);
            margin: 0 -1rem;
            padding: 0.75rem 1rem;
            border-radius: 5px;
            font-weight: 700;
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
        
        .champion-box {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
            color: #2c5234;
            border: 2px solid #e6c200;
        }
        
        .champion-box h4 {
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
            font-weight: 700;
        }
        
        .trophy-ceremony {
            background: linear-gradient(135deg, #2c5234, #4a7c59);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .trophy-ceremony h4 {
            margin-bottom: 1rem;
            color: #ffd700;
        }
        
        .double-bogey-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
        }
        
        .double-bogey-box h4 {
            color: #856404;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Article Hero -->
    <section class="article-hero">
        <div class="hero-content">
            <h1>Scheffler Captures First Claret Jug with Dominant Victory</h1>
            <p>World No. 1 completes commanding four-shot triumph at Royal Portrush to claim his fourth major championship</p>
        </div>
    </section>

    <!-- Article Content -->
    <div class="article-content">
        <div class="article-meta">
            <div class="article-date">
                <i class="fas fa-calendar-alt"></i>
                <span>July 21, 2025 - 8:30 PM</span>
            </div>
            <span class="article-category">Major Championship</span>
        </div>

        <p>PORTRUSH, Northern Ireland — Scottie Scheffler lifted the iconic Claret Jug for the first time in his career on Sunday evening, capturing The 153rd Open Championship with a commanding four-shot victory at Royal Portrush. The world No. 1's final-round 3-under 68 capped off a masterful week-long performance that saw him finish at 17-under par, the third-lowest 72-hole total in Open Championship history.</p>

        <div class="champion-box">
            <h4><i class="fas fa-trophy"></i> Champion Golfer of the Year</h4>
            <p>Scottie Scheffler wins his first Open Championship and fourth major title with a dominant display at Royal Portrush, earning $3.1 million and joining golf's elite company.</p>
        </div>

        <div class="final-leaderboard">
            <h3><i class="fas fa-trophy"></i> Final Leaderboard</h3>
            <div class="leaderboard-entry champion">
                <span class="player-name">Scottie Scheffler (USA)</span>
                <span class="player-score">-17 (68-64-67-68)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Harris English (USA)</span>
                <span class="player-score">-13 (Four shots back)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Cole Gotterup (USA)</span>
                <span class="player-score">-12 (Five shots back)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Will Clark (ENG)</span>
                <span class="player-score">-11 (Six shots back)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Matt Fitzpatrick (ENG)</span>
                <span class="player-score">-11 (Six shots back)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Rory McIlroy (NIR)</span>
                <span class="player-score">-10 (T7, Seven shots back)</span>
            </div>
        </div>

        <p>Starting the final round with a four-shot advantage, Scheffler quickly extended his lead to seven shots through the opening holes before encountering his only significant challenge of the week. The 28-year-old American demonstrated remarkable resilience and championship composure, weathering a mid-round storm to secure his first British Open title in emphatic fashion.</p>

        <br>

        <p>Scheffler opened his final round in commanding style, birdieing three of his first seven holes to build what appeared to be an insurmountable lead. Playing with the poise that has defined his remarkable season, he struck his approach shots with precision and rolled in putts with confidence, seemingly cruising toward his fourth major championship.</p>

        <div class="double-bogey-box">
            <h4><i class="fas fa-exclamation-triangle"></i> The Eighth Hole Setback</h4>
            <p>Scheffler's only wobble came at the par-4 eighth, where he pushed his drive into a right fairway bunker. After failing to escape cleanly on his second attempt, he required four more shots to find the hole for a double bogey that briefly cut his lead to four shots.</p>
        </div>

        <p>However, any concerns about a potential collapse were immediately quashed. Scheffler answered back with characteristic determination on the ninth hole, wedging his approach to five feet and converting the birdie putt. The immediate response showcased the mental fortitude that has made him golf's most dominant force, demonstrating why he has now converted his last 10 tournaments where he held the 54-hole lead.</p>

        <br>

        <p>The double bogey at the eighth represented Scheffler's fourth hole over par for the entire week—a statistic that underscores the clinical nature of his performance. Rather than dwelling on the mistake, he methodically rebuilt his advantage, adding another birdie at the 12th hole to restore his five-shot cushion heading into the final stretch.</p>

        <div class="stat-highlight">
            <h4><i class="fas fa-chart-line"></i> Historic Achievement</h4>
            <p>At 28 years old, Scheffler joins Tiger Woods, Jack Nicklaus, and Gary Player as the only players to win the Masters, PGA Championship, and Open Championship before age 30. He now needs only a U.S. Open to complete the career Grand Slam.</p>
        </div>

        <p>While Scheffler was authoring another major championship masterpiece, the day's most emotional storylines unfolded behind him. Rory McIlroy, the Northern Irish hero playing in front of his passionate home supporters, provided the gallery with moments of pure magic despite falling short of victory. The four-time major winner closed with a 2-under 69 to finish tied for seventh, seven shots behind Scheffler.</p>

        <br>

        <p>McIlroy's final walk up the 18th fairway became one of the tournament's most poignant moments. As the hometown favorite approached the green, tens of thousands of fans rose to their feet in unison, delivering a thunderous ovation that echoed across the Causeway Coast. The emotional reception served as both recognition of his efforts throughout the week and appreciation for his advocacy of Northern Ireland on the global stage.</p>

        <div class="quote-box">
            "There's a lot of gratitude, and yeah, a lot of pride," McIlroy reflected afterward. "A lot of pride that I am from these shores. For me to be in front of everyone here at home and to get that reception up the last—absolutely incredible. I've gotten everything I wanted out of this week apart from a Claret Jug."
        </div>

        <p>The contrast between McIlroy's redemptive week and his devastating 2019 performance at Portrush could not have been more stark. Six years ago, the then-world No. 2 missed the cut in his home Open, leaving Royal Portrush in tears. This time, he departed with his head held high and the adoration of an entire nation ringing in his ears.</p>

        <br>

        <p>Harris English emerged as Scheffler's closest challenger, finishing second at 13-under par after a solid final round. The American veteran's consistent play throughout the week earned him his best major championship finish since the 2013 U.S. Open, where he also claimed runner-up honors.</p>

        <br>

        <p>Cole Gotterup continued his breakthrough season with a third-place finish at 12-under, while England's Will Clark and Matt Fitzpatrick shared fourth place at 11-under. Fitzpatrick, the 2022 U.S. Open champion, struggled to match Scheffler's pace over the weekend but remained within range throughout the final round.</p>

        <br>

        <p>Among the week's most remarkable storylines was Bryson DeChambeau's incredible comeback performance. After opening with a disastrous 7-over 78 that left him tied for 144th and seemingly out of contention, the 2024 U.S. Open champion mounted one of the most impressive turnarounds in major championship history. DeChambeau responded with three consecutive outstanding rounds of 65-68-64, playing his final 54 holes in 16-under par—the second-lowest such score in the 153-year history of The Open.</p>

        <div class="stat-highlight">
            <h4><i class="fas fa-chart-bar"></i> DeChambeau's Historic Comeback</h4>
            <p><strong>Opening Round:</strong> 7-over 78 (T144th place)<br>
            <strong>Final Three Rounds:</strong> 65-68-64 (16-under par)<br>
            <strong>Final Position:</strong> T10 at 9-under par<br>
            <strong>Final Round 64:</strong> Tied for best round of the tournament</p>
        </div>

        <p>DeChambeau's final-round 64 tied for the tournament's lowest score and matched his best round in 124 career major championship rounds. The performance likely secured his spot on the U.S. Ryder Cup team, with Captain Keegan Bradley having left an inspirational message in his locker during the week. "Normally I'd be super pissed and frustrated, which I was rightfully so, because I thought I played pretty well and shot 7 over," DeChambeau reflected. "I said to myself, I'm going to do something different this time... I'm going to give it everything I have tomorrow, no matter what happens."</p>

        <div class="trophy-ceremony">
            <h4><i class="fas fa-star"></i> A Family Celebration</h4>
            <p>As R&A CEO Mark Darbon announced "The Champion Golfer of the Year, Scottie Scheffler," the newly crowned champion's attention was captured by his young son attempting to climb the small hill beside the 18th green. Scheffler's face lit up as he set down the Claret Jug and lifted his son instead, creating an unforgettable family moment.</p>
        </div>

        <p>The victory represented far more than just another major championship for Scheffler. With his triumph at Royal Portrush, he joins an exclusive group of players to capture three different majors before their 30th birthday. Only Tiger Woods, Jack Nicklaus, and Gary Player had previously achieved this remarkable feat, placing Scheffler in the company of golf's all-time greats.</p>

        <br>

        <p>Scheffler's 2025 season has been nothing short of extraordinary. With victories at the Masters and PGA Championship already secured, his Open triumph solidifies his position as the sport's premier player. The $3.1 million winner's check brings his season earnings to record-breaking levels, while the Claret Jug represents the most coveted prize in professional golf.</p>

        <div class="quote-box">
            "This is what makes him happy," said Scheffler's father, Scott, watching his son embrace his child beside the 18th green. The comment perfectly encapsulated a champion who has never lost sight of what truly matters, even as he continues to rewrite golf's record books.
        </div>

        <p>The weather conditions on Sunday provided a fitting backdrop for championship golf, with overcast skies giving way to calmer winds as the day progressed. The improved conditions allowed for more aggressive play, though few were able to capitalize as effectively as Scheffler, who demonstrated why he has become virtually unbeatable when holding a major championship lead.</p>

        <br>

        <p>Defending champion Brian Harman faced a challenging day, unable to mount a serious defense of his title. The 2024 champion will pass the Claret Jug to Scheffler, who now faces the unique pressure of defending his first Open Championship when the tournament returns to Royal Birkdale in 2026.</p>

        <br>

        <p>As the sun set over Royal Portrush and the celebrations began in earnest, Scheffler's dominance over professional golf reached new heights. With three major championships in 2025 and just the U.S. Open standing between him and the career Grand Slam, the Texan has established himself as the defining player of his generation.</p>

        <div class="stat-highlight">
            <h4><i class="fas fa-history"></i> Championship by the Numbers</h4>
            <p><strong>Final Score:</strong> 17-under par (267 total) - Third-lowest 72-hole total in Open Championship history<br>
            <strong>Margin of Victory:</strong> Four shots over Harris English<br>
            <strong>Perfect Record:</strong> 10-0 when holding the 54-hole lead in tournaments<br>
            <strong>Prize Money:</strong> $3.1 million</p>
        </div>

        <p>For Royal Portrush, hosting The Open for just the second time since 1951, the week provided a spectacular showcase of Northern Ireland's golfing heritage. The course's demanding layout and unpredictable conditions created the perfect stage for championship drama, with Scheffler ultimately proving himself worthy of joining the illustrious list of Open champions.</p>

        <br>

        <p>As Scheffler begins his reign as Champion Golfer of the Year, the golf world can only marvel at his sustained excellence. With the FedEx Cup playoffs on the horizon and the career Grand Slam within reach, the 28-year-old American shows no signs of slowing down in what has already been a season for the ages.</p>
        
        <br>

        <p>The 153rd Open Championship will be remembered as the moment Scottie Scheffler announced himself among golf's immortals, capturing his first Claret Jug with a performance that exemplified everything great about major championship golf. At Royal Portrush, in front of passionate crowds and on one of the sport's grandest stages, the world's best player delivered when it mattered most.</p>
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