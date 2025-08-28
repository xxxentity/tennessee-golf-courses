<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';

$article_slug = '2025-tour-championship-atlanta-predictions';
$article_title = '2025 Tour Championship Atlanta Predictions';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2025 Tour Championship Atlanta Preview: Scottie Scheffler Favored for Historic Back-to-Back Titles - Tennessee Golf Courses</title>
    <meta name="description" content="Complete preview of the 2025 Tour Championship at East Lake Golf Club featuring predictions, betting favorites, and analysis of Scottie Scheffler's chances for historic back-to-back titles.">
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
        
        .predictions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .prediction-card {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 15px;
            border-left: 4px solid var(--primary-color);
        }
        
        .prediction-card h4 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .odds-box {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .odds-box h3 {
            margin-bottom: 1rem;
            color: white;
        }
        
        .favorites-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        
        .favorite-player {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            border: 2px solid var(--border-color);
        }
        
        .favorite-player h4 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .player-odds {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .format-highlight {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            border: 2px solid var(--secondary-color);
            margin: 2rem 0;
        }
        
        .format-highlight h3 {
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
            
            .predictions-grid, .favorites-list {
                grid-template-columns: 1fr;
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
                    <h1 class="article-title">2025 Tour Championship Atlanta Preview: Scottie Scheffler Favored for Historic Back-to-Back Titles</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 20, 2025</span>
                        <span><i class="far fa-clock"></i> 4:15 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/2025-tour-championship-atlanta-predictions/main.webp" alt="2025 Tour Championship at East Lake Golf Club in Atlanta" class="article-image">
                
                <div class="article-content">
                    <p><strong>ATLANTA, Ga.</strong> — The 2025 Tour Championship arrives at East Lake Golf Club this week with unprecedented changes and the world's best golfers competing for the season's most lucrative prize. Scottie Scheffler enters as the overwhelming betting favorite to capture his second consecutive FedEx Cup title, but a revolutionary format change levels the playing field for all 30 qualified players.</p>

                    <div class="format-highlight">
                        <h3><i class="fas fa-trophy"></i> Historic Format Changes for 2025</h3>
                        <p><strong>Major Change:</strong> The 2025 Tour Championship eliminates the "Starting Strokes" format used since 2019. All 30 players will now start at even par for a traditional 72-hole stroke-play championship, creating the most competitive finale in recent memory.</p>
                        <p><strong>Prize Money:</strong> The winner receives $10 million from a $40 million total purse, with the top 8 finishers earning seven-figure payouts.</p>
                    </div>

                    <p>The elimination of staggered starting positions marks the most significant change to the Tour Championship format in six years. Previously, the FedEx Cup leader began with a 10-under advantage, but the 2025 tournament returns to pure stroke play where every shot matters equally from the first tee to the 72nd hole.</p>

                    <h2>Betting Favorites and Expert Predictions</h2>

                    <div class="odds-box">
                        <h3><i class="fas fa-star"></i> Scottie Scheffler: The Clear Favorite</h3>
                        <p>Scheffler enters with +150 odds at major sportsbooks, making him the overwhelming favorite to win his second consecutive FedEx Cup title and become the first back-to-back champion since Tiger Woods.</p>
                    </div>

                    <p>Multiple golf experts and analysts have identified Scheffler as the player to beat after his remarkable 2025 season. The world No. 1 has captured five victories this season, including his stunning comeback at the BMW Championship, and has shown exceptional comfort at East Lake with his 2024 victory.</p>

                    <div class="favorites-list">
                        <div class="favorite-player">
                            <h4>Scottie Scheffler</h4>
                            <p class="player-odds">+150 odds</p>
                            <p>Five wins in 2025, defending champion, exceptional form entering Atlanta</p>
                        </div>
                        <div class="favorite-player">
                            <h4>Rory McIlroy</h4>
                            <p class="player-odds">+800 odds</p>
                            <p>Three-time Tour Championship winner (2016, 2019, 2022), career Grand Slam holder</p>
                        </div>
                        <div class="favorite-player">
                            <h4>Tommy Fleetwood</h4>
                            <p class="player-odds">+1300 odds</p>
                            <p>Excellent recent form with consecutive top-5 finishes in FedEx Cup Playoffs</p>
                        </div>
                        <div class="favorite-player">
                            <h4>Ludvig Åberg</h4>
                            <p class="player-odds">+1600 odds</p>
                            <p>Rising star making second FedEx Cup appearance, strong recent performances</p>
                        </div>
                        <div class="favorite-player">
                            <h4>Russell Henley</h4>
                            <p class="player-odds">+2000 odds</p>
                            <p>Statistically strong at East Lake, superior scoring average over recent rounds</p>
                        </div>
                        <div class="favorite-player">
                            <h4>Justin Thomas</h4>
                            <p class="player-odds">+2200 odds</p>
                            <p>Former champion with proven ability in high-pressure situations</p>
                        </div>
                    </div>

                    <h2>Key Storylines and Predictions</h2>

                    <div class="predictions-grid">
                        <div class="prediction-card">
                            <h4>Scheffler's Historic Bid</h4>
                            <p>Scottie Scheffler aims to join Tiger Woods as the only player to capture five or more wins in consecutive seasons over the past 40 years. His consistency and clutch performance make him the consensus favorite.</p>
                        </div>
                        <div class="prediction-card">
                            <h4>McIlroy's East Lake Mastery</h4>
                            <p>Rory McIlroy has painted the top 10 nine times in 11 Tour Championship starts, including the previous three seasons. His East Lake track record makes him the clear second choice.</p>
                        </div>
                        <div class="prediction-card">
                            <h4>Field Depth and Competition</h4>
                            <p>The format change creates opportunities for all 30 players, with multiple contenders positioned within striking distance of the lead throughout the week.</p>
                        </div>
                        <div class="prediction-card">
                            <h4>International Presence</h4>
                            <p>Tommy Fleetwood represents the strongest international challenge, with his recent playoff form suggesting he could capture his first PGA Tour victory at the biggest moment.</p>
                        </div>
                    </div>

                    <blockquote>
                        "We want the Tour Championship to be the hardest tournament to qualify for and the FedEx Cup trophy the most difficult to win," said player advisory council member Scottie Scheffler about the format changes.
                    </blockquote>

                    <h2>Course Setup and Conditions</h2>

                    <p>East Lake Golf Club will feature adjustments by the PGA Tour Rules Committee designed to encourage more risk/reward moments throughout each round. The changes aim to create heightened drama and ensure the most exciting possible finish to the season.</p>

                    <p>The 7,346-yard, par-70 layout has historically favored accurate drivers and strong iron players, characteristics that align perfectly with Scheffler's skill set. However, the course's challenging greens and strategic layout provide opportunities for any player to make a move with hot putting.</p>

                    <h2>Expert Analysis and Final Predictions</h2>

                    <p>Golf analysts across major sports networks have identified this tournament as potentially setting up the long-awaited Scheffler-McIlroy showdown that fans have anticipated throughout 2025. The duo combined to win three of the four major championships this season and were the only players with three or more tournament victories.</p>

                    <p>The consensus among betting experts suggests Scheffler's combination of current form, East Lake success, and overall dominance in 2025 makes him the overwhelming favorite. However, the format change eliminates the built-in advantage he would have previously enjoyed as the FedEx Cup leader.</p>

                    <p>McIlroy's exceptional East Lake record and proven ability to perform in high-pressure situations positions him as the most likely challenger to Scheffler's bid for consecutive titles. The Northern Irishman's experience in these moments could prove decisive if the tournament comes down to the final holes on Sunday.</p>

                    <p>Dark horse candidates include Tommy Fleetwood, whose recent form suggests he could finally capture his breakthrough PGA Tour victory, and Ludvig Åberg, whose youth and fearless approach could surprise veteran competitors in his second Tour Championship appearance.</p>

                    <p>The 2025 Tour Championship promises to deliver the most competitive and straightforward finish in recent memory, with $40 million in prize money and the FedEx Cup title providing motivation for golf's elite to produce their best performances when it matters most.</p>
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
                                <textarea name="comment_text" class="comment-textarea" placeholder="Share your predictions for the Tour Championship..." required></textarea>
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

    <?php include '../includes/threaded-comments.php'; ?>
    
    <?php include '../includes/footer.php'; ?>
    
    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>