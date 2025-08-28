<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';

$article_slug = 'liv-golf-michigan-team-championship-2025-quarterfinals';
$article_title = 'Legion XIII and Crushers GC Advance as LIV Golf Michigan Team Championship Begins';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legion XIII and Crushers GC Advance as LIV Golf Michigan Team Championship Begins - Tennessee Golf Courses</title>
    <meta name="description" content="Six teams advance to semifinals at LIV Golf Michigan Team Championship after dramatic quarterfinal matches. HyFlyers GC stuns 4Aces in biggest upset of the day.">
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
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 2rem;
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .article-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .article-featured-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            object-position: center;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-light);
            line-height: 1.8;
        }
        
        .article-content h2 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin: 2.5rem 0 1.5rem 0;
        }
        
        .article-content h3 {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin: 2rem 0 1rem 0;
        }
        
        .article-content h4 {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin: 1.5rem 0 1rem 0;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }
        
        .article-content blockquote {
            background: var(--bg-light);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: var(--text-dark);
        }
        
        .scoreboard {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        
        .scoreboard h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        
        .team-name {
            flex: 1;
            font-weight: 500;
            color: var(--text-black);
        }
        
        .match-result {
            font-weight: 600;
            color: var(--primary-color);
            margin-left: 1rem;
        }
        
        .match-details {
            background: #f0f8ff;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1.5rem 0;
            border-left: 4px solid var(--secondary-color);
        }
        
        .match-details h4 {
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }
        
        .match-details ul {
            list-style: none;
            padding: 0;
        }
        
        .match-details li {
            padding: 0.5rem 0;
            display: flex;
            justify-content: space-between;
        }
        
        .share-section {
            background: var(--bg-white);
            padding: 2rem 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .share-title {
            font-size: 1.5rem;
            color: var(--text-black);
            margin-bottom: 1.5rem;
        }
        
        .share-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .share-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
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
        }
        
        .comment-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .comment-submit {
            margin-top: 1rem;
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .comment-submit:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 15px;
        }
        
        .login-button {
            display: inline-block;
            margin-top: 1rem;
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .login-button:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .comment {
            padding: 1.5rem;
            background: var(--bg-light);
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .comment-date {
            color: var(--text-gray);
        }
        
        .comment-text {
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        @media screen and (max-width: 768px) {
            .article-title {
                font-size: 2rem;
            }
            
            .article-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .article-content {
                padding: 2rem 1.5rem;
            }
            
            .share-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <!-- Article Header -->
            <div class="article-header">
                <span class="article-category">Tournament News</span>
                <h1 class="article-title">Legion XIII and Crushers GC Advance as LIV Golf Michigan Team Championship Begins</h1>
                <div class="article-meta">
                    <div class="article-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>August 22, 2025 • 8:15 PM</span>
                    </div>
                    <div class="article-meta-item">
                        <i class="fas fa-user"></i>
                        <span>By TGC Editorial Team</span>
                    </div>
                    <div class="article-meta-item">
                        <i class="fas fa-clock"></i>
                        <span>5 min read</span>
                    </div>
                </div>
                <img src="/images/news/liv-golf-michigan-team-championship-2025-quarterfinals/main.webp" alt="LIV Golf Michigan Team Championship 2025" class="article-featured-image">
            </div>

            <!-- Article Content -->
            <div class="article-content">
                <p><strong>PLYMOUTH, MI</strong> – The LIV Golf Michigan Team Championship burst into action with an intense day of quarterfinal matches at The Cardinal at Saint John's, as six teams punched their tickets to Saturday's semifinals in the season-ending event worth $50 million in total prize money.</p>

                <p>Top-seeded Legion XIII, led by Jon Rahm, and Bryson DeChambeau's second-seeded Crushers GC both advanced as expected, but the day's biggest story came from the HyFlyers GC, who stunned Dustin Johnson's 4Aces GC in a dramatic upset that featured Phil Mickelson winning on the 19th hole.</p>

                <div class="scoreboard">
                    <h3><i class="fas fa-trophy"></i> Quarterfinal Results</h3>
                    <ul class="scoreboard-list">
                        <li class="scoreboard-item">
                            <span class="team-name"><strong>Legion XIII</strong> def. Cleeks GC</span>
                            <span class="match-result">2-1</span>
                        </li>
                        <li class="scoreboard-item">
                            <span class="team-name"><strong>Crushers GC</strong> def. Majesticks GC</span>
                            <span class="match-result">2-1</span>
                        </li>
                        <li class="scoreboard-item">
                            <span class="team-name"><strong>Torque GC</strong> def. RangeGoats GC</span>
                            <span class="match-result">2-1</span>
                        </li>
                        <li class="scoreboard-item">
                            <span class="team-name"><strong>HyFlyers GC</strong> def. 4Aces GC</span>
                            <span class="match-result">2-1</span>
                        </li>
                        <li class="scoreboard-item">
                            <span class="team-name"><strong>Stinger GC</strong> def. Ripper GC</span>
                            <span class="match-result">2-1</span>
                        </li>
                        <li class="scoreboard-item">
                            <span class="team-name"><strong>Smash GC</strong> def. Fireballs GC</span>
                            <span class="match-result">2-1</span>
                        </li>
                    </ul>
                </div>

                <h2>HyFlyers Pull Off Stunning Upset</h2>

                <p>The biggest surprise of the day belonged to the HyFlyers GC, who eliminated the star-studded 4Aces team in dramatic fashion. Phil Mickelson secured the winning point with a clutch victory on the 19th hole against David Puig, while Cameron Tringale dominated Abraham Ancer 3&1 to improve his singles record on LIV Golf to an impressive 6-0.</p>

                <p>"This is what team golf is all about," Mickelson said after his dramatic extra-hole victory. "To come through for my teammates in that situation, there's nothing better in golf."</p>

                <h2>Legion XIII Survives Despite Rahm Loss</h2>

                <p>Despite entering as the top seed with four tournament wins this season, Legion XIII faced a stern test from Cleeks GC. Jon Rahm surprisingly fell to Adrian Meronk 2&1 in singles play, putting pressure on his teammates to deliver.</p>

                <div class="match-details">
                    <h4>Legion XIII vs. Cleeks GC Match Details</h4>
                    <ul>
                        <li>
                            <span>Jon Rahm vs. Adrian Meronk</span>
                            <span>Meronk wins 2&1</span>
                        </li>
                        <li>
                            <span>Tyrrell Hatton vs. Richard Bland</span>
                            <span>Hatton wins in extra holes</span>
                        </li>
                        <li>
                            <span>McKibbin/Surratt vs. Kaymer/Kjettrup</span>
                            <span>Legion XIII wins 2&1</span>
                        </li>
                    </ul>
                </div>

                <p>Tom McKibbin and Caleb Surratt stepped up with a crucial 2&1 victory over Cleeks captain Martin Kaymer and Frederik Kjettrup in foursomes, while Tyrrell Hatton defeated Richard Bland in a thrilling match that went to extra holes.</p>

                <h2>Torque GC Carries Indianapolis Momentum</h2>

                <p>Fresh off their record-breaking 64-under performance at LIV Golf Indianapolis last week, Torque GC continued their hot streak by eliminating Bubba Watson's RangeGoats GC. Captain Joaquin Niemann, who has won five individual titles this season, set the tone with a 2&1 victory over Watson.</p>

                <p>Carlos Ortiz and Sebastian Munoz sealed the deal for Torque with a 2&1 foursomes victory over Peter Uihlein and Matthew Wolff, showcasing the team chemistry that has made them one of the most formidable units in LIV Golf.</p>

                <h2>DeChambeau's Crushers Advance</h2>

                <p>Bryson DeChambeau's Crushers GC, the second seed, handled business against Lee Westwood's Majesticks GC. DeChambeau avenged his 2022 WGC Match Play loss to Westwood, leading his team to a 2-1 victory and keeping their championship hopes alive.</p>

                <p>The Crushers have been one of the most consistent teams throughout the 2025 season, and DeChambeau's power game appears perfectly suited for The Cardinal's challenging layout.</p>

                <h2>Tournament Format and What's Next</h2>

                <p>The 2025 LIV Golf Team Championship features a revamped format designed to maximize drama and competition. After Wednesday's play-in match that saw Majesticks GC defeat Iron Heads GC, today's quarterfinals featured three matches per tie: two singles matches and one foursomes match, with the first team to earn two points advancing.</p>

                <p>Saturday's semifinals will follow the same format, with the three winning teams advancing to Sunday's stroke-play finale. The eliminated teams will also compete in positional matches throughout the weekend, ensuring all 48 players remain in action.</p>

                <blockquote>
                    "The new format has created exactly the kind of intensity we were looking for," said LIV Golf Commissioner Greg Norman. "Every match matters, and we're seeing incredible drama unfold."
                </blockquote>

                <h2>Semifinal Matchups Set</h2>

                <p>With the quarterfinals complete, Saturday's semifinal matchups are set to deliver more thrilling team golf action. The advancing teams will battle for spots in Sunday's championship finale, where $14 million awaits the winning team.</p>

                <p>The Cardinal at Saint John's, hosting its first LIV Golf event, has proven to be an excellent venue for match play, with its challenging layout creating numerous momentum swings throughout today's matches.</p>

                <p>Coverage continues Saturday at 1:00 PM ET on FOX, with all six teams vying for a chance at LIV Golf's biggest team prize of the season.</p>
            </div>
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
                </article><?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>
</body>
</html>