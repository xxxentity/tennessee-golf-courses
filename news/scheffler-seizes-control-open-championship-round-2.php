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
                    <h1 class="article-title">Scheffler Seizes Control with Career-Best 64</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> July 18, 2025</span>
                        <span><i class="far fa-clock"></i> 4:30 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/open-championship-round-2/scheffler-64.jpg" alt="Scottie Scheffler Open Championship Round 2" class="article-image">
                
                <div class="article-content">

        <p>PORTRUSH, Northern Ireland — Scottie Scheffler delivered a masterpiece at Royal Portrush on Friday, firing a career-best 7-under 64 in major championship play to seize control of the 153rd Open Championship and position himself for his fourth major title.</p>

        <div class="scoreboard">
            <h3 class="scoreboard-title">Round 2 Highlight</h3>
            <ul class="scoreboard-list">
                <li class="scoreboard-item">
                    <span class="player-name">Scottie Scheffler's 64:</span>
                    <span class="player-score">Career-low in major</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Course Record:</span>
                    <span class="player-score">Just one shot off</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Eight Birdies:</span>
                    <span class="player-score">None closer than 7 feet</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Long Putts:</span>
                    <span class="player-score">Five from 10+ feet</span>
                </li>
            </ul>
        </div>

        <div class="scoreboard">
            <h3 class="scoreboard-title">Halfway Leaderboard</h3>
            <ul class="scoreboard-list">
                <li class="scoreboard-item">
                    <span class="player-rank">1</span>
                    <span class="player-name">Scottie Scheffler</span>
                    <span class="player-score">-10 (68-64)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-rank">2</span>
                    <span class="player-name">Matt Fitzpatrick</span>
                    <span class="player-score">-9 (67-66)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-rank">T3</span>
                    <span class="player-name">Brian Harman</span>
                    <span class="player-score">-8 (70-64)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-rank">T3</span>
                    <span class="player-name">Haotong Li</span>
                    <span class="player-score">-8 (67-67)</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-rank">T5</span>
                    <span class="player-name">Tyrrell Hatton</span>
                    <span class="player-score">-5 (71-66)</span>
                </li>
            </ul>
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

        <div class="scoreboard">
            <h3 class="scoreboard-title">Scheffler's Statistical Dominance</h3>
            <ul class="scoreboard-list">
                <li class="scoreboard-item">
                    <span class="player-name">Putting Rank:</span>
                    <span class="player-score">2nd in field</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Strokes Gained:</span>
                    <span class="player-score">6+ on greens</span>
                </li>
                <li class="scoreboard-item">
                    <span class="player-name">Formula:</span>
                    <span class="player-score">Ball-striking + putting</span>
                </li>
            </ul>
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