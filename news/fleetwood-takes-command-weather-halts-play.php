<?php
session_start();
require_once '../config/database.php';

$article_slug = 'fleetwood-takes-command-weather-halts-play';
$article_title = 'Fleetwood Surges to Four-Shot Lead as Weather Halts Play';

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
    <title>Fleetwood Surges to Four-Shot Lead as Weather Halts Play - Tennessee Golf Courses</title>
    <meta name="description" content="Tommy Fleetwood builds commanding lead at FedEx St. Jude Championship before severe weather suspends second round play.">
    <link rel="stylesheet" href="/styles.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logos/tab-logo.webp?v=3">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=3">
    
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
        
        .weather-alert {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .weather-alert i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .weather-alert h3 {
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
                    <h1 class="article-title">Fleetwood Surges to Four-Shot Lead as Weather Halts Play</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 8, 2025</span>
                        <span><i class="far fa-clock"></i> 6:30 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/fleetwood-takes-command-weather-halts-play/main.jpeg" alt="Tommy Fleetwood FedEx St. Jude Championship Second Round" class="article-image">
                
                <div class="article-content">
                    <p><strong>MEMPHIS, Tenn.</strong> — Tommy Fleetwood delivered another masterful performance Friday at TPC Southwind, firing a 6-under 64 to build a commanding four-shot lead at 13-under par before severe weather brought an abrupt halt to second-round action at the FedEx St. Jude Championship.</p>
                    
                    <p>The Englishman, long considered "the best player without a win on the PGA Tour," backed up his opening 63 with another stellar round that featured four consecutive birdies on his closing stretch and established him as the overwhelming favorite heading into the weekend of the FedExCup Playoffs opener.</p>
                    
                    <div class="weather-alert">
                        <i class="fas fa-bolt"></i>
                        <h3>PLAY SUSPENDED</h3>
                        <p>Second round suspended at 6:15 PM ET due to dangerous weather conditions. Play will resume Saturday morning at 7:00 AM CT.</p>
                    </div>
                    
                    <blockquote>
                        "I made three in a row early and then had a pair of key par saves around the turn before going on another run," Fleetwood said. "The course is playing beautifully, and I'm just trying to stay patient and take what it gives me."
                    </blockquote>
                    
                    <h2>Weather Forces Suspension</h2>
                    
                    <p>With thunderstorms moving through the Memphis area bringing heavy rain, lightning, and wind gusts up to 45 mph, tournament officials were forced to suspend play at 6:15 PM ET on Friday evening. Twenty-one of the 69 players in the field still need to complete their second rounds, which will resume Saturday morning at 7:00 AM CT.</p>
                    
                    <p>The National Weather Service had issued a severe thunderstorm warning for Shelby County, prompting the early suspension as player safety remained the top priority. This marked the second weather interruption of the week, following a brief lightning delay during Thursday's opening round.</p>
                    
                    <p>"We're monitoring the conditions very closely," said a PGA Tour official. "The forecast looks better for Saturday morning, and we're confident we can get everyone through their second rounds before starting the third round."</p>
                    
                    <h2>Fleetwood's Commanding Performance</h2>
                    
                    <p>Despite the weather interruption, Fleetwood's dominant play was the story of the day. The 33-year-old from England started fast with three consecutive birdies early in his round, highlighted by a spectacular 30-foot putt from the fringe on the par-3 fourth hole that drew roars from the galleries.</p>
                    
                    <p>After making crucial par saves around the turn to maintain momentum, Fleetwood exploded down the stretch with four straight birdies from holes 13-16. He rolled in 15-foot birdie putts on both the 13th and 14th holes, stuffed a wedge to five feet on the 15th, and reached the par-5 16th in two before two-putting from 30 feet for his fourth consecutive red number.</p>
                    
                    <p>His only blemish came at the 18th hole, where he found the bunker with his approach and couldn't get up-and-down, settling for his lone bogey of the day. Still, the 64 gave him a commanding position entering the weekend.</p>
                    
                    <p>"The finish was spectacular until that last hole, but I'll take it," Fleetwood said with a smile. "I've been in this position before, and I know I need to stay focused and keep doing what I've been doing."</p>
                    
                    <div class="scoreboard">
                        <h3 class="scoreboard-title">Leaderboard After Second Round</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item">
                                <span class="player-rank">1</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">-13</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T2</span>
                                <span class="player-name">Collin Morikawa</span>
                                <span class="player-score">-9</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T2</span>
                                <span class="player-name">Akshay Bhatia</span>
                                <span class="player-score">-9</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T2</span>
                                <span class="player-name">Justin Rose*</span>
                                <span class="player-score">-9</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T5</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">-7</span>
                            </li>
                        </ul>
                        <p style="font-size: 0.9rem; color: var(--text-gray); margin-top: 1rem;">*Denotes player with holes remaining</p>
                    </div>
                    
                    <h2>Scheffler Keeps Pace Despite Frustrations</h2>
                    
                    <p>World No. 1 Scottie Scheffler overcame some mid-round frustrations to post a solid 4-under 66, positioning himself six shots behind Fleetwood but very much in contention for the weekend. The two-time major champion was visibly animated at times, particularly after a series of near-misses on the greens.</p>
                    
                    <p>"Even with a couple bogeys and some ranting and raving at the golf course, I walked off the 18th feeling pretty good about where I'm at," Scheffler said. "I'm playing the best golf I've seen from myself since the start of the season, and Tommy is going to have to deal with some pressure this weekend."</p>
                    
                    <p>Scheffler's round featured six birdies against two bogeys, with his length off the tee proving to be a significant advantage on the longer holes at TPC Southwind. At 7-under total, he sits T7 and remains well within striking distance of his fourth win of the season.</p>
                    
                    <h2>Morikawa Continues Strong Play</h2>
                    
                    <p>Collin Morikawa maintained his excellent form from Thursday with another solid round, posting a 68 to reach 9-under and claim a share of second place alongside overnight leader Akshay Bhatia and England's Justin Rose, who still has two holes to complete Saturday morning.</p>
                    
                    <p>"I'm playing the best golf I've shown all season," Morikawa said. "The iron play has been crisp, and I'm starting to see some putts drop. Four shots isn't insurmountable, especially with how this course can play on the weekend."</p>
                    
                    <p>Bhatia, who shot a career-best 62 in the opening round, cooled off slightly with a 1-over 71 but remains in strong contention. The 22-year-old American struggled with his putting compared to Thursday's hot performance but showed resilience in maintaining his position near the top of the leaderboard.</p>
                    
                    <h2>Bubble Drama Intensifies</h2>
                    
                    <p>While Fleetwood commanded attention at the top, the real drama continued to unfold further down the leaderboard as players battled to secure their spots in the top 50 and advance to next week's BMW Championship.</p>
                    
                    <p>Rickie Fowler, who began the week at a precarious 64th in the FedExCup standings, continued his strong play with a second consecutive round under par. Currently projected to move inside the top 50, Fowler's steady performance has put him in prime position to extend his season and qualify for the 2026 Signature Events.</p>
                    
                    <p>"I knew I had to play well this week, and so far I've done that," Fowler said. "But there's still a lot of golf left, and I can't let up now. This is exactly the position I wanted to be in heading into the weekend."</p>
                    
                    <p>Jordan Spieth, another player fighting for his playoff life, posted a steady round but remained precariously positioned around the projected cut line for the top 50. The three-time major champion, who entered the week 48th in points, will need a strong weekend to secure his advancement.</p>
                    
                    <p>Wyndham Clark, also battling the bubble, posted another solid round to position himself safely inside the projected top 50, while several other notable names continued their fight to extend their seasons.</p>
                    
                    <h2>Rose Poised Despite Incomplete Round</h2>
                    
                    <p>Justin Rose, who still has two holes remaining in his second round, sits in a tie for second place at 9-under par. The Englishman expressed his determination to return to the Tour Championship after a multi-year absence.</p>
                    
                    <blockquote>
                        "It's bugged me a little bit that I haven't been back to East Lake in a few years, so that's definitely a goal of mine," Rose said before play was suspended. "I'm striking it well and the putter is cooperating. Weather or not, I feel good about where my game is at."
                    </blockquote>
                    
                    <h2>Weekend Weather Outlook</h2>
                    
                    <p>Tournament officials expressed optimism about weekend conditions, with Saturday's forecast calling for partly cloudy skies and temperatures in the upper 80s. The severe weather system that disrupted Friday's play is expected to move through overnight, leaving more favorable conditions for the weekend rounds.</p>
                    
                    <p>"We're looking at much better conditions for Saturday and Sunday," said a meteorologist working with the tournament. "The storms should clear out overnight, and we should have good playing conditions for the weekend."</p>
                    
                    <h2>Historical Context</h2>
                    
                    <p>Fleetwood's pursuit of his first PGA Tour victory has been one of golf's most compelling storylines in recent years. The Englishman has recorded multiple runner-up finishes and has consistently ranked among the world's top players, but the elusive first win has remained just out of reach.</p>
                    
                    <p>"A win here would be massive for me," Fleetwood acknowledged. "I've been close so many times, and I know what it means to get that first one. But I can't get ahead of myself. There's still 36 holes left, and this field is incredibly deep."</p>
                    
                    <p>Indeed, with Scheffler lurking just six shots back and several other proven winners within striking distance, Fleetwood will need to maintain his exceptional level of play to finally breakthrough for that maiden PGA Tour victory.</p>
                    
                    <h2>Looking to Saturday</h2>
                    
                    <p>Play will resume Saturday morning at 7:00 AM CT with the 21 players still needing to complete their second rounds. The third round is expected to begin in the early afternoon once all players have finished 36 holes.</p>
                    
                    <p>With the FedExCup Playoffs providing elevated points and only the top 50 advancing to next week's BMW Championship, every shot carries enormous weight. For Fleetwood, it represents perhaps his best opportunity yet to capture that elusive first victory. For others, it's about survival and extending their seasons.</p>
                    
                    <p>As the weekend approaches, one thing is certain: the combination of weather, playoff pressure, and Fleetwood's commanding lead has set the stage for what promises to be a thrilling conclusion to the FedExCup Playoffs opener in Memphis.</p>
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