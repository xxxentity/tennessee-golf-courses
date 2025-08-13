<?php
session_start();
require_once '../config/database.php';

$article_slug = 'fedex-st-jude-first-round-bhatia-leads';
$article_title = 'Bhatia Blazes to First-Round Lead at FedEx St. Jude Championship';

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
    <title>Bhatia Blazes to First-Round Lead at FedEx St. Jude Championship - Tennessee Golf Courses</title>
    <meta name="description" content="Akshay Bhatia shoots career-best 62 to lead FedEx St. Jude Championship as playoff bubble players battle in Memphis heat.">
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
                    <h1 class="article-title">Bhatia Blazes to First-Round Lead at FedEx St. Jude Championship</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 7, 2025</span>
                        <span><i class="far fa-clock"></i> 7:00 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/fedex-st-jude-first-round-bhatia-leads/main.jpeg" alt="Akshay Bhatia FedEx St. Jude Championship First Round" class="article-image">
                
                <div class="article-content">
                    <p><strong>MEMPHIS, Tenn.</strong> — Akshay Bhatia delivered a stunning performance in sweltering conditions Thursday at TPC Southwind, firing a career-best 8-under 62 to seize the first-round lead at the FedEx St. Jude Championship, the opening event of the FedExCup Playoffs.</p>
                    
                    <p>The 22-year-old American, who entered the week ranked 28th in the FedExCup standings, navigated the challenging Memphis heat and humidity with precision, carding eight birdies against no bogeys in what he called "one of those special days where everything clicked."</p>
                    
                    <blockquote>
                        "I felt really comfortable out there today. The putter was hot and I was hitting it close. Days like this don't come around often, so you have to take advantage when they do," Bhatia said after his round, still beaming from his closing stretch of eagle-birdie-birdie.
                    </blockquote>
                    
                    <h2>Brutal Conditions Test Field</h2>
                    
                    <p>With temperatures soaring into the low 90s and oppressive humidity making it feel even hotter, players faced a stern test of endurance on Thursday. The forecast called for temperatures reaching 93°F with a heat index approaching 100°F, creating what many players described as some of the most challenging weather conditions they've faced all season.</p>
                    
                    <p>Despite the heat, course conditions at the newly renovated TPC Southwind remained immaculate. The firm and fast conditions, a trademark of this venue, added another layer of difficulty as players had to account for extra roll on the fairways and lightning-quick Bermuda greens.</p>
                    
                    <p>"It's no surprise that major winners and multi-time PGA Tour champions have thrived here over the years," noted one Tour official. "This course demands excellence in every aspect of your game, and the heat just amplifies that challenge."</p>
                    
                    <h2>World No. 1 Stays in Hunt</h2>
                    
                    <p>Scottie Scheffler, who entered the playoffs atop the FedExCup standings after a dominant regular season, posted a solid 3-under 67 to stay within striking distance. The world's top-ranked player was bogey-free through 17 holes before a late miscue on his final hole prevented him from posting an even better score.</p>
                    
                    <p>"Today was a good day. I did some solid stuff. Felt like I was close to playing a really great round but ended up with a solid round," Scheffler reflected. The two-time major champion started strong, converting two of his first three birdie opportunities to quickly get into red numbers.</p>
                    
                    <p>Collin Morikawa matched Scheffler's pace with a 4-under 66, rolling in a crucial birdie on the par-4 10th to position himself inside the top five heading into Friday's second round.</p>
                    
                    <div class="scoreboard">
                        <h3 class="scoreboard-title">First Round Leaderboard</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item">
                                <span class="player-rank">1</span>
                                <span class="player-name">Akshay Bhatia</span>
                                <span class="player-score">-8 (62)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T2</span>
                                <span class="player-name">Justin Rose</span>
                                <span class="player-score">-6 (64)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T2</span>
                                <span class="player-name">Harry Hall</span>
                                <span class="player-score">-6 (64)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T2</span>
                                <span class="player-name">Bud Cauley</span>
                                <span class="player-score">-6 (64)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">5</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">-5 (65)</span>
                            </li>
                        </ul>
                    </div>
                    
                    <h2>Bubble Players Battle for Survival</h2>
                    
                    <p>With only the top 50 players in FedExCup points advancing to next week's BMW Championship, Thursday's opening round carried extra significance for those on the playoff bubble. Several notable names fighting for their playoff lives delivered when it mattered most.</p>
                    
                    <p>Rickie Fowler, who entered the week at a precarious 64th in the standings, posted a bogey-free 4-under 66 to project inside the crucial top-50 cutoff. Currently sitting T7 and four shots behind Bhatia, Fowler's steady play has him projected to jump to 48th in the standings if he maintains his position.</p>
                    
                    <p>"I knew coming in this week that I needed to play well," Fowler said. "To go bogey-free in these conditions, I'll definitely take that. But there's still a lot of golf to be played."</p>
                    
                    <p>Jordan Spieth, another player battling to extend his season, recovered from an opening bogey to card a 1-under 69. The three-time major champion, who began the week 48th in points, showed flashes of brilliance with his putter but acknowledged his iron play needs improvement if he's to secure his spot in the BMW Championship.</p>
                    
                    <p>"I drove it great and was solid on the greens," Spieth noted. "The irons just weren't quite there today, but I'm in a decent position heading into tomorrow."</p>
                    
                    <p>Wyndham Clark, another bubble player, posted a solid 3-under 67 with just one bogey on his card. His consistent ball-striking in difficult conditions has him well-positioned to move safely inside the top 50.</p>
                    
                    <h2>Notable Performances and Storylines</h2>
                    
                    <p>Justin Rose continued his strong playoff form with a 6-under 64, expressing his determination to return to East Lake for the Tour Championship. "It's bugged me a little bit that I haven't been back to East Lake in a few years, so that's definitely a goal of mine," the Englishman said after his round.</p>
                    
                    <p>An unusual incident occurred on the par-3 third hole when Germany's Matti Schmid damaged the cup with his approach shot, requiring officials to recut the hole mid-round. The bizarre occurrence didn't seem to rattle the field, though Schmid himself struggled to a 2-over 72.</p>
                    
                    <p>Other notable scores included Russell Henley's 4-under 66, Si Woo Kim's 5-under 65, and solid rounds from Maverick McNealy and Ben Griffin, both at 4-under par.</p>
                    
                    <h2>Looking Ahead</h2>
                    
                    <p>As players prepare for Friday's second round, weather continues to be a major storyline. The forecast calls for similar conditions with temperatures again reaching the low 90s and a 40% chance of afternoon thunderstorms that could potentially disrupt play.</p>
                    
                    <p>For Bhatia, the challenge will be maintaining his momentum after such a spectacular start. History suggests that first-round leaders at TPC Southwind often struggle to maintain their advantage, with the demanding layout typically producing plenty of movement on the leaderboard throughout the week.</p>
                    
                    <p>With $20 million in total purse money and $3.6 million going to the winner, plus crucial FedExCup points that determine who advances in the playoffs, every shot carries tremendous weight. The top 50 players after Sunday's final round will move on to the BMW Championship, while the rest will see their seasons come to an end.</p>
                    
                    <p>For now, though, the spotlight belongs to Akshay Bhatia, whose brilliant opening round has set the stage for what promises to be a thrilling week of playoff golf in Memphis.</p>
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