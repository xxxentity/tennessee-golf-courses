<?php
session_start();
require_once '../config/database.php';

$article_slug = 'macintyre-explodes-for-62-leads-bmw-championship';
$article_title = 'MacIntyre Explodes for Career-Low 62 to Lead BMW Championship Opening Round';

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
    <title>MacIntyre Explodes for Career-Low 62 to Lead BMW Championship Opening Round - Tennessee Golf Courses</title>
    <meta name="description" content="Robert MacIntyre fires career-low 62 with six straight closing birdies to take three-shot lead over Tommy Fleetwood after BMW Championship first round.">
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
            color: var(--text-black);
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        
        .article-meta {
            display: flex;
            gap: 2rem;
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        
        .article-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .article-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 3rem;
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
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 600;
            margin: 2.5rem 0 1.5rem 0;
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
        }
        
        .article-content blockquote {
            background: var(--bg-light);
            border-left: 4px solid var(--gold-color);
            margin: 2rem 0;
            padding: 1.5rem;
            border-radius: 10px;
            font-style: italic;
            color: var(--text-dark);
            font-size: 1.05rem;
        }
        
        .scoreboard {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        
        .scoreboard-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .scoreboard-list {
            list-style: none;
            padding: 0;
            margin: 0;
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
        
        .round-highlight {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .round-highlight i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .share-section {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .share-title {
            color: var(--text-black);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .share-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .share-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .share-twitter {
            background: #1da1f2;
            color: white;
        }
        
        .share-facebook {
            background: #4267b2;
            color: white;
        }
        
        .share-linkedin {
            background: #0077b5;
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
        
        .login-prompt a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #047857;
            border: 1px solid #10b981;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #ef4444;
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
                    <h1 class="article-title">MacIntyre Explodes for Career-Low 62 to Lead BMW Championship Opening Round</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 14, 2025</span>
                        <span><i class="far fa-clock"></i> 8:15 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/macintyre-explodes-for-62-leads-bmw-championship/main.webp" alt="Robert MacIntyre BMW Championship First Round" class="article-image">
                
                <div class="article-content">
                    <p><strong>OWINGS MILLS, Md.</strong> — After a rain delay that softened the demanding Caves Valley Golf Club layout, Robert MacIntyre seized the opportunity with both hands, firing a career-low 8-under 62 highlighted by six consecutive closing birdies to grab a commanding three-shot lead after the opening round of the BMW Championship Thursday.</p>
                    
                    <p>The 29-year-old Scotsman's spectacular round marked the longest birdie streak to end a FedEx Cup playoff round since the format's inception in 2007, as MacIntyre turned what started as a solid round into something truly special with his closing flourish on the back nine at the penultimate playoff event.</p>
                    
                    <div class="round-highlight">
                        <i class="fas fa-fire"></i>
                        <h3>CAREER PERFORMANCE</h3>
                        <p>MacIntyre's 62 matches his career low and includes 195 feet of made putts in the best statistical putting round of his career.</p>
                    </div>
                    
                    <blockquote>
                        "When I went back out after the delay, I had a 7-footer for birdie which was going to set the tone for the rest of the afternoon, and I rolled that in nicely," MacIntyre said. "From there, everything just seemed to fall into place. The greens were softer after the rain, and I was able to be more aggressive with my approach shots."
                    </blockquote>
                    
                    <h2>MacIntyre's Magnificent Finish</h2>
                    
                    <p>MacIntyre's round appeared destined for respectability rather than brilliance when he reached the turn at 2-under par, but a bogey on the 12th hole seemed to awaken something in the left-handed Scotsman. Rather than letting the dropped shot deflate his momentum, MacIntyre responded with one of the most impressive closing stretches in recent PGA Tour memory.</p>
                    
                    <p>Beginning on the par-4 13th hole, MacIntyre rolled in putts from everywhere, including bombs of 65 and 40 feet that had the Maryland galleries roaring their approval. The birdie barrage continued through the difficult finishing stretch at Caves Valley, with MacIntyre navigating the water hazards and elevated greens with the precision of a player in complete control of his game.</p>
                    
                    <p>"I've been working really hard on my putting with my coach, and it all came together today," MacIntyre explained. "Those long putts early in the streak gave me so much confidence. When you see a couple go in from distance like that, you start believing every putt has a chance."</p>
                    
                    <p>The performance was particularly sweet for MacIntyre, who credited lessons learned from his runner-up finish at the U.S. Open earlier this season for helping him maintain composure during his hot streak. The round featured not just the six closing birdies, but also crucial par saves that kept his momentum building throughout the afternoon.</p>
                    
                    <div class="scoreboard">
                        <h3 class="scoreboard-title">Leaderboard After First Round</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item">
                                <span class="player-rank">1</span>
                                <span class="player-name">Robert MacIntyre</span>
                                <span class="player-score">-8</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">2</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">-5</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">3</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">-4</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4</span>
                                <span class="player-name">Viktor Hovland</span>
                                <span class="player-score">-3</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4</span>
                                <span class="player-name">Rickie Fowler</span>
                                <span class="player-score">-3</span>
                            </li>
                        </ul>
                    </div>
                    
                    <h2>Fleetwood Bounces Back Strong</h2>
                    
                    <p>Fresh off his heartbreaking near-miss at the FedEx St. Jude Championship, where he led with three holes to play before ultimately finishing second, Tommy Fleetwood showed remarkable resilience by posting a solid 5-under 65 to claim second place after the opening round.</p>
                    
                    <p>The Englishman's round was a study in consistency, featuring six birdies against just one bogey as he navigated the challenging Caves Valley layout with the steady precision that has made him one of the world's most reliable performers. Fleetwood's birdie on the final hole briefly gave him the clubhouse lead before MacIntyre's late heroics.</p>
                    
                    <p>"Obviously last week was disappointing, but that's golf," Fleetwood said. "You have to move on quickly, especially in the playoffs. I felt good about my game today, and there's still a lot of golf to be played this week."</p>
                    
                    <h2>Scheffler Lurks in Third</h2>
                    
                    <p>World No. 1 Scottie Scheffler, the prohibitive favorite entering the week, positioned himself nicely with a 4-under 66 that featured bookend runs of birdies. The reigning Masters champion started hot with birdies on his first two holes before cooling off through the middle portion of his round, only to finish with a flourish by birdieing three of his final four holes.</p>
                    
                    <p>Despite sitting four shots off the lead, Scheffler's first-round performance sent a clear message to the field that he remains the man to beat. The American has been virtually unstoppable in big moments this season, and his closing stretch Thursday demonstrated the kind of clutch play that has defined his remarkable 2025 campaign.</p>
                    
                    <p>"I felt pretty good out there today," Scheffler said. "Got off to a nice start and finished well. There are three more days, and a lot can happen. I'm in a good position to make a move."</p>
                    
                    <h2>Weather Impact and Course Conditions</h2>
                    
                    <p>Thursday's play was interrupted by a two-hour weather delay that ultimately played into the hands of the afternoon wave, as the rain softened the typically firm Caves Valley greens and made scoring conditions more favorable. The delay suspended play at 12:47 p.m. local time before resuming at 4:45 p.m.</p>
                    
                    <p>Several players in the afternoon wave, including MacIntyre and Fleetwood, took advantage of the softer conditions to post low numbers. The first round was eventually suspended due to darkness at 8:12 p.m., with only one player remaining to complete their opening round.</p>
                    
                    <p>The 7,601-yard, par-70 layout at Caves Valley has undergone significant changes since the tour's last visit, with renovations designed to create a more challenging test for the world's best players. Despite the modifications, Thursday's conditions allowed for scoring, setting up what promises to be an action-packed weekend in the Maryland suburbs.</p>
                    
                    <blockquote>
                        "The course played quite a bit different after the rain," noted Viktor Hovland, who shot 3-under 67. "The greens were more receptive, which allowed us to be more aggressive with our approach shots. That definitely helped the scoring today."
                    </blockquote>
                    
                    <h2>Looking Ahead to Friday</h2>
                    
                    <p>With MacIntyre holding a three-shot cushion heading into Friday's second round, the stage is set for an intriguing battle as the field looks to hunt down the Scotsman while positioning themselves for the weekend. The BMW Championship features a stellar field of 70 players competing for a $20 million purse, with only the top 30 in FedEx Cup standings advancing to next week's Tour Championship in Atlanta.</p>
                    
                    <p>For MacIntyre, who sits 23rd in the current FedEx Cup standings, a strong showing this week would virtually guarantee his spot in the season-ending finale. More importantly, it could provide him with his first PGA Tour victory and the momentum needed to make a run at the FedEx Cup title.</p>
                    
                    <p>"It's just one round, and there's a lot of golf left," MacIntyre cautioned. "But I'm in a good position, and I need to keep doing what I'm doing. The key is to stay patient and trust the process."</p>
                    
                    <p>Friday's second round promises to bring its own challenges, with players looking to position themselves for the weekend while navigating what figures to be firmer, faster conditions as the Maryland course dries out from Thursday's weather interruption.</p>
                </div>
                
                <div class="share-section">
                    <h3 class="share-title">Share This Story</h3>
                    <div class="share-buttons">
                        <a href="https://twitter.com/intent/tweet?text=MacIntyre%20Explodes%20for%20Career-Low%2062%20to%20Lead%20BMW%20Championship%20Opening%20Round&url=https://tennesseegolfcourses.com/news/macintyre-explodes-for-62-leads-bmw-championship" class="share-button share-twitter" target="_blank">
                            <i class="fab fa-twitter"></i>
                            Tweet
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/news/macintyre-explodes-for-62-leads-bmw-championship" class="share-button share-facebook" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                            Share
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=https://tennesseegolfcourses.com/news/macintyre-explodes-for-62-leads-bmw-championship" class="share-button share-linkedin" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                            Share
                        </a>
                    </div>
                </div>
            </article>
            
            <section class="comments-section">
                <h2 class="comments-header"><i class="fas fa-comments"></i> Discussion</h2>
                
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <?php if ($is_logged_in): ?>
                    <div class="comment-form">
                        <h3>Join the Discussion</h3>
                        <form method="POST">
                            <textarea name="comment_text" class="comment-textarea" placeholder="Share your thoughts on MacIntyre's incredible opening round..." required></textarea>
                            <button type="submit" class="comment-submit">
                                <i class="fas fa-paper-plane"></i> Post Comment
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt">
                        <p><strong>Join the conversation!</strong> <a href="/login">Login</a> or <a href="/register">create an account</a> to share your thoughts on the BMW Championship.</p>
                    </div>
                <?php endif; ?>
                
                <div class="comments-list">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                    <span class="comment-date"><?php echo date('M j, Y g:i A', strtotime($comment['created_at'])); ?></span>
                                </div>
                                <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: var(--text-gray); font-style: italic;">Be the first to comment on this story!</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>