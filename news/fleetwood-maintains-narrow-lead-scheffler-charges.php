<?php
session_start();
require_once '../config/database.php';

$article_slug = 'fleetwood-maintains-narrow-lead-scheffler-charges';
$article_title = 'Fleetwood Maintains Narrow Lead as Scheffler Charges into Contention';

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
    <title>Fleetwood Maintains Narrow Lead as Scheffler Charges into Contention - Tennessee Golf Courses</title>
    <meta name="description" content="Tommy Fleetwood holds one-shot lead entering final round as Scottie Scheffler fires 65 to surge into contention at FedEx St. Jude Championship.">
    <link rel="stylesheet" href="/styles.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logos/tab-logo.png?v=2">
    <link rel="shortcut icon" href="/images/logos/tab-logo.png?v=2">
    
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
            padding-top: 140px;
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
        
        .moving-day-alert {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .moving-day-alert i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .moving-day-alert h3 {
            margin: 0.5rem 0;
            font-size: 1.3rem;
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
                    <span class="article-category">Tournament Coverage</span>
                    <h1 class="article-title">Fleetwood Maintains Narrow Lead as Scheffler Charges into Contention</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 9, 2025</span>
                        <span><i class="far fa-clock"></i> 7:45 PM</span>
                        <span><i class="far fa-user"></i> TGC Staff</span>
                    </div>
                </header>
                
                <img src="/images/news/fleetwood-maintains-narrow-lead-scheffler-charges/main.jpeg" alt="Tommy Fleetwood FedEx St. Jude Championship Third Round" class="article-image">
                
                <div class="article-content">
                    <p><strong>MEMPHIS, Tenn.</strong> — Tommy Fleetwood weathered a rocky Moving Day Saturday at TPC Southwind to maintain his grasp on the lead heading into Sunday's final round of the FedEx St. Jude Championship, but the Englishman's margin for error has evaporated with World No. 1 Scottie Scheffler breathing down his neck after a masterful 65.</p>
                    
                    <p>Fleetwood, still seeking his first PGA Tour victory in his 162nd career start, posted a hard-fought 1-under 69 in the third round to remain atop the leaderboard at 14-under par. However, his once-comfortable lead has shrunk to just one shot over fellow countryman Justin Rose, with Scheffler lurking ominously two shots back after producing the day's most impressive round.</p>
                    
                    <div class="moving-day-alert">
                        <i class="fas fa-chart-line"></i>
                        <h3>MOVING DAY SHUFFLE</h3>
                        <p>Leaderboard tightens dramatically as multiple major champions position themselves for Sunday's finale.</p>
                    </div>
                    
                    <blockquote>
                        "It wasn't the smoothest day out there, but I'm still in the lead going into tomorrow," Fleetwood said after his round. "I know what's at stake, and I know what I need to do. The guys behind me are world-class players, so I'll need to bring my best golf tomorrow."
                    </blockquote>
                    
                    <h2>Fleetwood's Resilient Fight</h2>
                    
                    <p>Saturday's third round began disastrously for Fleetwood when he made a double bogey 7 on the par-5 third hole, immediately cutting his four-shot overnight lead down to just one stroke. The early stumble sent shockwaves through the Memphis galleries and raised questions about whether the 33-year-old Englishman could handle the pressure of leading on the weekend.</p>
                    
                    <p>However, Fleetwood demonstrated the mental fortitude that has carried him to 42 career top-10 finishes and six runner-up showings on the PGA Tour. Rather than unravel, he steadied himself with a series of crucial par saves and timely birdies, including a momentum-shifting birdie on the par-5 16th hole that helped restore some breathing room.</p>
                    
                    <p>"The double bogey early was obviously not ideal, but I stayed patient and tried to trust my game," Fleetwood reflected. "I made some good saves when I needed to, and that birdie on 16 felt really important at the time."</p>
                    
                    <p>The round wasn't without its late drama, as Fleetwood bogeyed the 18th hole while playing partner Justin Rose made birdie, cutting the lead to its current one-shot margin. Still, Fleetwood's ability to maintain the lead through adversity showcased the championship composure he'll need to finally capture that elusive first PGA Tour victory.</p>
                    
                    <div class="scoreboard">
                        <h3 class="scoreboard-title">Leaderboard After Third Round</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item">
                                <span class="player-rank">1</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">-14</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">2</span>
                                <span class="player-name">Justin Rose</span>
                                <span class="player-score">-13</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">3</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">-12</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4</span>
                                <span class="player-name">J.J. Spaun</span>
                                <span class="player-score">-11</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4</span>
                                <span class="player-name">Andrew Novak</span>
                                <span class="player-score">-11</span>
                            </li>
                        </ul>
                    </div>
                    
                    <h2>Scheffler's Commanding Charge</h2>
                    
                    <p>While Fleetwood battled to stay afloat, Scottie Scheffler reminded everyone why he's the world's top-ranked player with a brilliant third-round performance. The two-time major champion fired six birdies against just one bogey, capping his 65 with a clutch birdie on the 18th hole that electrified the crowd and positioned him perfectly for Sunday's finale.</p>
                    
                    <p>"I felt really good about my game today," Scheffler said. "I hit it just half the fairways and 11 greens, but I made some key par saves and got the putter working when I needed to. That birdie on 18 was a nice way to finish."</p>
                    
                    <p>Scheffler's round was particularly impressive given his struggles off the tee, hitting just seven of 14 fairways. However, his exceptional scrambling ability and clutch putting limited him to just one bogey all day, showcasing the well-rounded game that has made him the sport's most dominant force.</p>
                    
                    <p>The 28-year-old American enters Sunday's final round having won four times already in 2025, including major championships at the Masters and PGA Championship. A victory on Sunday would be his fifth win of the season and further cement his position atop the FedExCup standings.</p>
                    
                    <h2>Rose Stays Patient in Hunt</h2>
                    
                    <p>Justin Rose continued his steady pursuit of a return to the Tour Championship, posting a solid round to remain in second place at 13-under par. The 43-year-old Englishman has been remarkably consistent through three rounds, positioning himself for his first PGA Tour victory since the 2019 Farmers Insurance Open.</p>
                    
                    <p>"I'm in a great position heading into tomorrow," Rose said. "Tommy and I know each other's games well, and with Scottie right there too, it's going to be a great final group. I need to stay aggressive and trust my game."</p>
                    
                    <p>Rose's experience in big moments could prove crucial on Sunday, as the former world No. 1 has captured 10 PGA Tour titles and a major championship throughout his distinguished career. His presence in the final group adds another layer of intrigue to what promises to be a thrilling conclusion.</p>
                    
                    <h2>Major Champions Lurking</h2>
                    
                    <p>J.J. Spaun, the reigning U.S. Open champion, fired his second consecutive 65 to move into a tie for fourth place at 11-under par. Spaun's round could have been even better if not for a double bogey on the par-4 10th hole, but his ability to bounce back with multiple birdies showcased the resilience that carried him to major championship glory earlier this year.</p>
                    
                    <p>"I'm playing really solid golf right now," Spaun said. "That double on 10 was frustrating, but I stayed patient and made some good birdies coming in. I'm only three back, so there's definitely a chance tomorrow if I can get off to a good start."</p>
                    
                    <p>Andrew Novak joined Spaun at 11-under with his own strong showing, while several other players remained within striking distance of the lead heading into the final round.</p>
                    
                    <h2>Bubble Drama Intensifies</h2>
                    
                    <p>While the battle for the title commanded most of the attention, the fight for survival in the FedExCup Playoffs continued to provide compelling drama throughout Saturday's third round. With only the top 50 players advancing to next week's BMW Championship, every shot carried enormous weight for those on the bubble.</p>
                    
                    <p>Rickie Fowler, who entered the week at a precarious 51st in the FedExCup standings, continued his strong play with another solid round. The veteran American's consistent performance has him projected to move into the crucial top 50, extending his season and securing his spot in the 2026 Signature Events.</p>
                    
                    <p>"I knew I needed a good week, and so far I've given myself a chance," Fowler said. "But there's still 18 holes left, and I can't let up now. The guys behind me are fighting for their seasons too."</p>
                    
                    <p>Jordan Spieth made a crucial birdie at the par-5 16th hole to keep his playoff hopes alive, but the three-time major champion remained in a precarious position. His final-round performance on Sunday will likely determine whether he advances to the BMW Championship or sees his season come to an end.</p>
                    
                    <p>Meanwhile, Tony Finau saw his chances of extending his season take a hit with a disappointing showing that left him outside the projected cut line. The powerful American, who has made the Tour Championship in recent years, faces an uphill battle to save his season on Sunday.</p>
                    
                    <h2>Sunday Setup</h2>
                    
                    <p>The stage is set for a thrilling conclusion to the FedEx St. Jude Championship, with Fleetwood, Rose, and Scheffler forming a star-studded final group that tees off Sunday afternoon. The trio will be chased by a pack of hungry contenders, including major champions Spaun and several players still fighting for their playoff lives.</p>
                    
                    <p>Weather conditions are expected to be favorable for Sunday's finale, with partly cloudy skies and temperatures in the mid-80s providing ideal scoring conditions. The lack of wind could lead to low numbers, meaning the leaders will need to stay aggressive to maintain their positions.</p>
                    
                    <p>For Fleetwood, Sunday represents perhaps his best opportunity yet to capture that breakthrough victory. With 42 top-10 finishes and six runner-up showings already on his resume, the talented Englishman has knocked on the door numerous times without breaking through.</p>
                    
                    <blockquote>
                        "I've been in this position before, and I know what it means to finally get that first win," Fleetwood said. "But I can't get ahead of myself. I need to go out there and play my game, and hopefully, that will be enough."
                    </blockquote>
                    
                    <h2>Historical Context</h2>
                    
                    <p>Fleetwood's pursuit of his first PGA Tour victory has been one of golf's most compelling storylines in recent years. Despite his success on the European Tour, where he has won multiple times, the American breakthrough has remained elusive. His six runner-up finishes include heartbreaking defeats at some of the game's biggest events.</p>
                    
                    <p>For Scheffler, Sunday's final round represents an opportunity to further extend his dominance over professional golf. The world No. 1 has been virtually unstoppable when in contention, converting his last 10 tournaments where he held the 54-hole lead. His presence just two shots off the pace should send a clear message to the field.</p>
                    
                    <h2>Looking to Sunday</h2>
                    
                    <p>As the sun set over Memphis on Saturday evening, the championship picture had crystallized into one of the most compelling setups in recent memory. Two Englishmen at the top of the leaderboard, with the world's best player lurking just two shots behind, all while playoff bubble drama swirls throughout the field.</p>
                    
                    <p>The final round will determine not only who claims the $3.6 million winner's check and valuable FedExCup points, but also which 50 players advance to next week's BMW Championship. For Fleetwood, it's a chance to finally break through for his first PGA Tour victory. For Scheffler, it's an opportunity to add another trophy to his already impressive 2025 collection.</p>
                    
                    <p>One thing is certain: Sunday's finale promises to deliver the drama and excitement that have made the FedExCup Playoffs must-watch television, with championship dreams and season-ending nightmares hanging in the balance at TPC Southwind.</p>
                </div>
                
                <div class="share-section">
                    <h3 class="share-title">Share This Article</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article_slug); ?>" target="_blank" class="share-button facebook">
                            <i class="fab fa-facebook-f"></i> Share on Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article_title); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article_slug); ?>" target="_blank" class="share-button twitter">
                            <i class="fab fa-x-twitter"></i> Share on X
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