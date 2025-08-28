<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';

$article_slug = 'open-championship-2025-round-1-royal-portrush';
$article_title = 'Five Players Share Lead as Royal Portrush Shows Its Teeth in Opening Round';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Five Players Share Lead as Royal Portrush Shows Its Teeth in Opening Round - Tennessee Golf Courses</title>
    <meta name="description" content="Matt Fitzpatrick among five tied for the lead after a challenging first round at the 2025 Open Championship, while Scottie Scheffler trails by one shot.">
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
        
        .leaderboard {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        
        .leaderboard h3 {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .leaderboard-entry {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: white;
            margin-bottom: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .leaderboard-entry:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-light);
        }
        
        .player-name {
            font-weight: 500;
            color: var(--text-black);
        }
        
        .player-score {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .quote-box {
            background: var(--bg-light);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            border-radius: 8px;
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
                    <h1 class="article-title">Five Players Share Lead as Royal Portrush Shows Its Teeth</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> July 17, 2025</span>
                        <span><i class="far fa-clock"></i> 6:00 PM</span>
                        <span><a href="/profile?username=cole-harrington" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/cole-harrington.webp" alt="Cole Harrington" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;">Cole Harrington</span></a></span>
                    </div>
                </header>
                
                <img src="/images/news/open-championship-2025-round-1/royal-portrush-round1.jpg" alt="Open Championship Royal Portrush Round 1" class="article-image">
                
                <div class="article-content">

                    <p><strong>PORTRUSH, Northern Ireland</strong> — Royal Portrush Golf Club reminded the world's best golfers why it's one of the most challenging venues in championship golf, as ever-changing weather conditions and demanding hole locations produced a bunched leaderboard after the first round of the 153rd Open Championship.</p>

                    <div class="leaderboard">
            <h3><i class="fas fa-trophy"></i> First Round Leaderboard</h3>
            <div class="leaderboard-entry">
                <span class="player-name">Matt Fitzpatrick</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Harris English</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Christiaan Bezuidenhout</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Haotong Li</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Jacob Skov Olesen</span>
                <span class="player-score">-4 (67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Scottie Scheffler</span>
                <span class="player-score">-3 (68)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Rory McIlroy</span>
                <span class="player-score">-1 (70)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Jon Rahm</span>
                <span class="player-score">-1 (70)</span>
            </div>
        </div>

        <p>Five players emerged from a grueling opening day to share the lead at 4-under par, with England's Matt Fitzpatrick headlining a diverse group that includes Harris English, Christiaan Bezuidenhout, China's Haotong Li, and Denmark's Jacob Skov Olesen. The quintet managed to navigate Royal Portrush's notorious challenges while 31 players total reached red figures, though no one could take the course particularly low.</p>

        <br>

        <p>World No. 1 Scottie Scheffler positioned himself perfectly just one shot back despite hitting only three fairways in his round of 68. The reigning PGA Championship winner showed his championship mettle with a late flurry of birdies after struggling with accuracy throughout much of his round.</p>

        <div class="quote-box">
            "When it's raining sideways, believe it or not, it's not that easy to get the ball in the fairway," Scheffler said with characteristic understatement after his round. "I was just trying to plot my way around and finish strong."
        </div>

        <p>The day's story was as much about survival as scoring, with Royal Portrush delivering everything from sunshine to sideways rain and gusting winds that challenged even the most seasoned major championship veterans. The Northern Ireland links course, hosting The Open for just the second time since 1951, reminded players why it's considered one of the toughest tests in golf.</p>

        <br>

        <p>Local favorite Rory McIlroy, playing in front of passionate home crowds, managed a respectable 1-under 70 despite the enormous pressure and expectations. The four-time major winner found himself three shots off the pace but well within striking distance heading into Friday's second round.</p>

        <br>

        <p>Former world No. 1 Jon Rahm also sits at 1-under alongside McIlroy, while defending champion Xander Schauffele faced a tougher day, struggling to find his rhythm in the challenging conditions.</p>
        
        <br>

        <p>"This course gives you everything," said Fitzpatrick, who leads the field alongside four others. "You've got to be so precise with your iron play, and when the weather changes every hole like it did today, you're constantly adjusting. I'm pleased to be where I am, but there's a long way to go."</p>
        
        <br>

        <p>The weather forecast suggests more of the same for Friday's second round, with gusty winds and intermittent rain expected to continue testing the 156-player field. With the cut line traditionally falling around even par at The Open, many big names will need to improve significantly to make the weekend.</p>

        <br>

        <p>Harris English, sharing the lead after his bogey-free 67, emphasized the importance of patience at Royal Portrush. "You can't force anything out here," he said. "The course will punish you if you get too aggressive. I just tried to stay in the present and take what the course gave me."</p>

        <br>

        <p>As the 153rd Open Championship heads into its second round, the stage is set for another dramatic day at one of golf's most challenging venues. With the weather continuing to play a major factor and Royal Portrush showing its formidable defenses, Friday promises to be equally demanding for the world's best golfers.</p>
        
        <br>

                    <p>The cut will be made after Friday's second round, with the top 70 players and ties, plus those within 10 shots of the lead, advancing to the weekend. With such tight scoring and challenging conditions, every shot will matter as players battle both the course and the elements in pursuit of golf's oldest major championship.</p>
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
</body>
</html>