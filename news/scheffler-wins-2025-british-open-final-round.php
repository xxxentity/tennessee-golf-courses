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
    <link rel="stylesheet" href="/styles.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=4">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=4">
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
            font-size: 2.8rem;
            color: var(--text-black);
            margin-bottom: 1.5rem;
            line-height: 1.2;
            font-weight: 700;
        }
        
        .article-meta {
            display: flex;
            gap: 2rem;
            color: var(--text-gray);
            font-size: 0.95rem;
            flex-wrap: wrap;
        }
        
        .article-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .article-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-light);
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
        }
        
        .article-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }
        
        .article-content h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin: 2.5rem 0 1.5rem;
            font-weight: 600;
        }
        
        .article-content h3 {
            font-size: 1.5rem;
            color: var(--text-black);
            margin: 2rem 0 1rem;
            font-weight: 600;
        }
        
        .article-content blockquote {
            background: var(--bg-light);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            border-radius: 8px;
        }
        
        .article-content ul, .article-content ol {
            margin: 1.5rem 0;
            padding-left: 2rem;
        }
        
        .article-content li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
        
        .scoreboard {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        
        .scoreboard-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .scoreboard-list {
            list-style: none;
            padding: 0;
        }
        
        .scoreboard-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: white;
            margin-bottom: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .scoreboard-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-light);
        }
        
        .player-rank {
            font-weight: 600;
            color: var(--text-gray);
            margin-right: 1rem;
            min-width: 30px;
        }
        
        .player-name {
            flex: 1;
            font-weight: 500;
            color: var(--text-black);
        }
        
        .player-score {
            font-weight: 600;
            color: var(--primary-color);
            margin-left: 1rem;
        }
        
        .share-section {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .share-title {
            font-size: 1.3rem;
            color: var(--text-black);
            margin-bottom: 1rem;
        }
        
        .share-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .share-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .share-button.facebook {
            background: #1877f2;
            color: white;
        }
        
        .share-button.twitter {
            background: #000000;
            color: white;
        }
        
        .share-button.email {
            background: var(--primary-color);
            color: white;
        }
        
        .share-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .comments-section {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
        }
        
        .comments-header {
            font-size: 1.8rem;
            color: var(--text-black);
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .comment-form {
            margin-bottom: 3rem;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 15px;
        }
        
        .comment-form h3 {
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        .comment-textarea {
            width: 100%;
            min-height: 120px;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            resize: vertical;
            transition: border-color 0.3s ease;
        }
        
        .comment-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .comment-submit {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .comment-submit:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .comment {
            padding: 1.5rem;
            background: var(--bg-light);
            border-radius: 15px;
            margin-bottom: 1rem;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .comment-date {
            font-size: 0.85rem;
            color: var(--text-gray);
        }
        
        .comment-text {
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 15px;
        }
        
        .login-prompt p {
            margin-bottom: 1rem;
            color: var(--text-gray);
        }
        
        .login-button {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-button:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .success-message, .error-message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 500;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .article-container {
                padding: 1rem;
            }
            
            .article-header, .article-content, .comments-section {
                padding: 1.5rem;
            }
            
            .article-title {
                font-size: 2rem;
            }
            
            .article-image {
                height: 300px;
            }
            
            .share-buttons {
                flex-direction: column;
            }
            
            .share-button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <article>
                <header class="article-header">
                    <span class="article-category">Major Championship</span>
                    <h1 class="article-title">Scheffler Captures First Claret Jug with Dominant Victory</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> July 21, 2025</span>
                        <span><i class="far fa-clock"></i> 8:30 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/open-championship-final-2025/scottie-final-round.png" alt="Scottie Scheffler Open Championship Final Round" class="article-image">
                
                <div class="article-content">

        <p>PORTRUSH, Northern Ireland — Scottie Scheffler lifted the iconic Claret Jug for the first time in his career on Sunday evening, capturing The 153rd Open Championship with a commanding four-shot victory at Royal Portrush. The world No. 1's final-round 3-under 68 capped off a masterful week-long performance that saw him finish at 17-under par, the third-lowest 72-hole total in Open Championship history.</p>

        <div class="champion-box">
            <h4><i class="fas fa-trophy"></i> Champion Golfer of the Year</h4>
            <p>Scottie Scheffler wins his first Open Championship and fourth major title with a dominant display at Royal Portrush, earning $3.1 million and joining golf's elite company.</p>
        </div>

        <div class="scoreboard">
            <h3 class="scoreboard-title">Final Leaderboard</h3>
            <ul class="scoreboard-list">
                <li class="scoreboard-item">
                    <span class="player-rank">1</span>
                    <span class="player-name">Scottie Scheffler (USA)</span>
                    <span class="player-score">-17 (68-64-67-68)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-rank">2</span>
                    <span class="player-name">Harris English (USA)</span>
                    <span class="player-score">-13 (Four shots back)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-rank">3</span>
                    <span class="player-name">Cole Gotterup (USA)</span>
                    <span class="player-score">-12 (Five shots back)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-rank">T4</span>
                    <span class="player-name">Will Clark (ENG)</span>
                    <span class="player-score">-11 (Six shots back)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-rank">T4</span>
                    <span class="player-name">Matt Fitzpatrick (ENG)</span>
                    <span class="player-score">-11 (Six shots back)</span>
                </li>
            </ul>
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

        <div class="scoreboard">
            <h3 class="scoreboard-title">DeChambeau's Historic Comeback</h3>
            <ul class="scoreboard-list">
                <li class="scoreboard-item">
                    <span class="player-name">Opening Round:</span>
                    <span class="player-score">7-over 78 (T144th place)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Final Three Rounds:</span>
                    <span class="player-score">65-68-64 (16-under par)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Final Position:</span>
                    <span class="player-score">T10 at 9-under par</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Final Round 64:</span>
                    <span class="player-score">Tied for best round</span>
                </li>
            </ul>
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

        <div class="scoreboard">
            <h3 class="scoreboard-title">Championship by the Numbers</h3>
            <ul class="scoreboard-list">
                <li class="scoreboard-item">
                    <span class="player-name">Final Score:</span>
                    <span class="player-score">17-under par (267 total)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Margin of Victory:</span>
                    <span class="player-score">Four shots over Harris English</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Perfect Record:</span>
                    <span class="player-score">10-0 when holding 54-hole lead</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Prize Money:</span>
                    <span class="player-score">$3.1 million</span>
                </li>
            </ul>
        </div>

        <p>For Royal Portrush, hosting The Open for just the second time since 1951, the week provided a spectacular showcase of Northern Ireland's golfing heritage. The course's demanding layout and unpredictable conditions created the perfect stage for championship drama, with Scheffler ultimately proving himself worthy of joining the illustrious list of Open champions.</p>

        <br>

        <p>As Scheffler begins his reign as Champion Golfer of the Year, the golf world can only marvel at his sustained excellence. With the FedEx Cup playoffs on the horizon and the career Grand Slam within reach, the 28-year-old American shows no signs of slowing down in what has already been a season for the ages.</p>
        
        <br>

        <p>The 153rd Open Championship will be remembered as the moment Scottie Scheffler announced himself among golf's immortals, capturing his first Claret Jug with a performance that exemplified everything great about major championship golf. At Royal Portrush, in front of passionate crowds and on one of the sport's grandest stages, the world's best player delivered when it mattered most.</p>
                </div>

                
                <div class="share-section">
                    <h3 class="share-title">Share This Article</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article_slug); ?>" target="_blank" class="share-button facebook">
                            <i class="fab fa-facebook-f"></i> Share on Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article_title); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article_slug); ?>" target="_blank" class="share-button twitter">
                            <strong>𝕏</strong> Share on X
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode($article_title); ?>&body=<?php echo urlencode('Check out this article: https://tennesseegolfcourses.com/news/' . $article_slug); ?>" class="share-button email">
                            <i class="far fa-envelope"></i> Share via Email
                        </a>
                    </div>
                </div>

                
                <div class="comments-section">
                    <h2 class="comments-header">Comments</h2>
                    
                    <?php if ($is_logged_in): ?>
                        <div class="comment-form">
                            <h3>Leave a Comment</h3>
                            <?php if (isset($success_message)): ?>
                                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                            <?php endif; ?>
                            <?php if (isset($error_message)): ?>
                                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>
                            <form method="POST" action="">
                                <textarea name="comment_text" class="comment-textarea" placeholder="Share your thoughts..." required></textarea>
                                <button type="submit" class="comment-submit">Post Comment</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="login-prompt">
                            <p>Please log in to leave a comment.</p>
                            <a href="/login" class="login-button">Log In</a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="comments-list">
                        <?php if (empty($comments)): ?>
                            <p style="text-align: center; color: var(--text-gray); padding: 2rem;">No comments yet. Be the first to share your thoughts!</p>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment">
                                    <div class="comment-header">
                                        <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                        <span class="comment-date"><?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])); ?></span>
                                    </div>
                                    <p class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        </div>
    </div>


    <?php include '../includes/footer.php'; ?>
    
    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>