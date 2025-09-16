<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../includes/seo.php';

// Article data for SEO
$article_data = [
    'title' => 'Russell Henley Fires Spectacular 61 to Lead Tour Championship Opening Round',
    'description' => 'Russell Henley shoots spectacular 9-under 61 to take two-stroke lead over Scottie Scheffler in opening round of 2025 Tour Championship at East Lake Golf Club in Atlanta.',
    'image' => '/images/news/tour-championship-2025-first-round-henley-leads/main.webp',
    'type' => 'article',
    'author' => 'Cole Harrington',
    'date' => '2025-08-22',
    'category' => 'Tournament News'
];

SEO::setupArticlePage($article_data);

$article_slug = 'tour-championship-2025-first-round-henley-leads';
$article_title = 'Russell Henley Fires Spectacular 61 to Lead Tour Championship Opening Round';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo SEO::generateMetaTags(); ?>
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
        
        .round-highlight {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .round-highlight h3 {
            margin-bottom: 1rem;
            color: white;
        }
        
        .putting-stats {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            border: 2px solid var(--secondary-color);
            margin: 2rem 0;
        }
        
        .putting-stats h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
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
            
            .scoreboard-item {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
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
                    <span class="article-category">Tournament News</span>
                    <h1 class="article-title">Russell Henley Fires Spectacular 61 to Lead Tour Championship Opening Round</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 21, 2025</span>
                        <span><i class="far fa-clock"></i> 8:45 PM</span>
                        <span><a href="/profile/ColeH" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/cole-harrington.webp" alt="Cole Harrington" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;">Cole Harrington</span></a></span>
                    </div>
                </header>
                
                <img src="/images/news/tour-championship-2025-first-round-henley-leads/main.webp" alt="Russell Henley celebrates during his spectacular 61 in the first round of the 2025 Tour Championship" class="article-image">
                
                <div class="article-content">
                    <p><strong>ATLANTA, Ga.</strong> — Russell Henley delivered a putting masterclass Thursday at East Lake Golf Club, firing a spectacular 9-under 61 to seize a two-stroke lead over world No. 1 Scottie Scheffler after the opening round of the 2025 Tour Championship. The American's brilliant performance featured an incredible 207 feet of made putts, including conversions from 42, 45, and 37 feet that left spectators in awe.</p>

                    <div class="round-highlight">
                        <h3><i class="fas fa-fire"></i> Henley's Record-Breaking Putting Display</h3>
                        <p>Russell Henley gained over five strokes with his putter, making an astounding 207 feet of putts to shoot the low round of the day and take the early lead in the season finale.</p>
                    </div>

                    <p>Henley's round of seven birdies and one eagle showcased the kind of precision that wins major championships. The veteran golfer appeared completely dialed in from the start, describing his mental state as being "at peace if I missed" and feeling "clear on my reads" throughout the round.</p>

                    <h2>First Round Leaderboard</h2>

                    <div class="scoreboard">
                        <h3 class="scoreboard-title"><i class="fas fa-trophy"></i> After Round 1</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item">
                                <span class="player-rank">1.</span>
                                <span class="player-name">Russell Henley</span>
                                <span class="player-score">61 (-9)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">2.</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">63 (-7)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T3.</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">64 (-6)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T3.</span>
                                <span class="player-name">Robert MacIntyre</span>
                                <span class="player-score">64 (-6)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T3.</span>
                                <span class="player-name">Justin Thomas</span>
                                <span class="player-score">64 (-6)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T3.</span>
                                <span class="player-name">Patrick Cantlay</span>
                                <span class="player-score">64 (-6)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T3.</span>
                                <span class="player-name">Collin Morikawa</span>
                                <span class="player-score">64 (-6)</span>
                            </li>
                        </ul>
                    </div>

                    <h2>Scheffler Maintains Remarkable Consistency</h2>

                    <p>Despite finishing two strokes behind Henley, Scottie Scheffler's 7-under 63 extended his remarkable streak to 18 consecutive rounds in the 60s. The defending FedEx Cup champion showed no signs of pressure as he pursues back-to-back titles, demonstrating the consistency that has made him the world's top-ranked player.</p>

                    <div class="putting-stats">
                        <h4><i class="fas fa-golf-ball"></i> Putting Performance Breakdown</h4>
                        <p><strong>Henley's Magic:</strong> Made putts of 42, 45, and 37 feet during his round, totaling 207 feet of made putts and gaining over five strokes on the field with his putter.</p>
                        <p><strong>Mental Approach:</strong> Henley credited his success to feeling "at peace if I missed" and being "clear on my reads" throughout the round.</p>
                    </div>

                    <p>Scheffler's round featured the kind of steady excellence that has defined his 2025 season, which already includes five victories. His ability to stay within striking distance while others struggled with East Lake's challenging layout demonstrates why he remains the overwhelming favorite despite trailing after the opening round.</p>

                    <h2>Competitive Chasing Pack</h2>

                    <p>Five players find themselves tied for third place at 6-under 64, creating an intriguing battle heading into Friday's second round. Tommy Fleetwood, Robert MacIntyre, Justin Thomas, Patrick Cantlay, and Collin Morikawa all posted solid opening rounds that keep them well within striking distance of the leaders.</p>

                    <p>Fleetwood's strong start continues his excellent form from the FedEx Cup Playoffs, where he posted consecutive top-5 finishes. The Englishman remains in search of his first PGA Tour victory and appears well-positioned to contend for the season's biggest prize.</p>

                    <blockquote>
                        "I felt at peace if I missed and was clear on my reads," Henley said after his spectacular round. "When everything clicks like that, you just try to stay in the moment and keep rolling."
                    </blockquote>

                    <h2>Course Conditions and Format Impact</h2>

                    <p>The 2025 Tour Championship's new format, featuring all players starting at even par rather than the previous staggered start system, has created the most competitive opening round in recent memory. East Lake Golf Club's challenging 7,440-yard, par-70 layout tested every aspect of the players' games, with scoring conditions proving favorable for those who could capitalize on birdie opportunities.</p>

                    <p>The absence of starting stroke advantages has clearly energized the field, with multiple players posting aggressive scores that would have been unnecessary under the previous format. This change has delivered exactly the kind of straightforward competition that players and fans requested.</p>

                    <h2>Looking Ahead to Round Two</h2>

                    <p>With three rounds remaining and a $10 million winner's prize on the line, Friday's second round promises to be crucial in determining which players can maintain their momentum and which might fall back in the pack. Henley will need to maintain his putting magic while managing the pressure of holding the lead in golf's richest tournament.</p>

                    <p>For Scheffler, trailing by two strokes represents a familiar position from which he has proven capable of mounting successful challenges throughout his dominant 2025 season. The world No. 1's consistency suggests he will remain in contention regardless of what happens in the second round.</p>

                    <p>The competitive nature of the opening round, with seven players within three strokes of the lead, sets up what could be one of the most exciting finishes in Tour Championship history. With the new format removing any built-in advantages, every shot will matter equally as golf's biggest stars compete for the season's ultimate prize.</p>
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
                
                </article>
        </div>
    </div>

    
    
    <?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>
    <!-- Scripts -->
    <script src="/script.js"></script>
</body>
</html>