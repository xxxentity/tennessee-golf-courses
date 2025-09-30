<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../includes/seo.php';

// Article data for SEO
$article_data = [
    'title' => 'Europe Survives Epic American Comeback to Win 2025 Ryder Cup 15-13 at Bethpage Black',
    'description' => 'Team Europe holds off dramatic US rally in Sunday singles to win 2025 Ryder Cup 15-13 at Bethpage Black, first victory on American soil since 2012 despite losing Hovland to injury.',
    'image' => '/images/news/ryder-cup-2025-europe-survives-american-comeback-15-13/main.webp',
    'type' => 'article',
    'author' => 'Cole Harrington',
    'date' => '2025-09-28',
    'category' => 'Tournament News'
];

SEO::setupArticlePage($article_data);

$article_slug = 'ryder-cup-2025-europe-survives-american-comeback-15-13';
$article_title = 'Europe Survives Epic American Comeback to Win 2025 Ryder Cup 15-13 at Bethpage Black';

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
        /* News Article Styles */
        .news-article {
            padding-top: 100px;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .news-article .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .back-to-news {
            margin-bottom: 2rem;
        }

        .back-link {
            color: #2e7d32;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #1b5e20;
        }

        .article-header {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .category-badge.tournament-news {
            background-color: #2e7d32;
            color: white;
        }

        .category-badge.tennessee-news {
            background-color: #1565c0;
            color: white;
        }

        .category-badge.equipment-news {
            background-color: #f57c00;
            color: white;
        }

        .article-title {
            font-size: 2.5rem;
            color: #1a1a1a;
            line-height: 1.2;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            color: #666;
            font-size: 0.95rem;
        }

        .meta-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .author {
            font-weight: 500;
            color: #333;
        }

        .separator {
            color: #999;
        }

        .read-time {
            color: #666;
        }

        .featured-image {
            margin-bottom: 2rem;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .featured-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .article-content {
            font-size: 1.125rem;
            line-height: 1.7;
            color: #333;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content .lead {
            font-size: 1.25rem;
            line-height: 1.6;
            font-weight: 500;
            color: #1a1a1a;
            margin-bottom: 2rem;
        }

        .article-content h2 {
            font-size: 1.875rem;
            color: #1a1a1a;
            margin-top: 2.5rem;
            margin-bottom: 1.25rem;
            font-weight: 600;
        }

        .article-content h3 {
            font-size: 1.5rem;
            color: #333;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .article-content blockquote {
            border-left: 4px solid #2e7d32;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #555;
        }

        .scoreboard {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .match-result {
            margin-bottom: 1.5rem;
        }

        .match-result:last-child {
            margin-bottom: 0;
        }

        .match-result h4 {
            color: #2e7d32;
            font-size: 1.125rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .results-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .results-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
        }

        .results-list li:last-child {
            border-bottom: none;
        }

        .article-footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
        }

        .disclaimer {
            font-size: 0.875rem;
            color: #666;
            font-style: italic;
        }

        .share-buttons {
            margin-top: 3rem;
            padding: 2rem;
            background: #f9f9f9;
            border-radius: 10px;
            text-align: center;
        }

        .share-buttons h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .social-share {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .social-share a {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .share-twitter {
            background: #1DA1F2;
            color: white;
        }

        .share-twitter:hover {
            background: #0d8bd9;
            transform: translateY(-2px);
        }

        .share-facebook {
            background: #1877F2;
            color: white;
        }

        .share-facebook:hover {
            background: #0e63d9;
            transform: translateY(-2px);
        }

        .share-linkedin {
            background: #0077B5;
            color: white;
        }

        .share-linkedin:hover {
            background: #005885;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .news-article .container {
                padding: 1rem;
            }

            .article-title {
                font-size: 1.875rem;
            }

            .article-content {
                font-size: 1rem;
            }

            .article-content .lead {
                font-size: 1.125rem;
            }

            .social-share {
                flex-direction: column;
            }

            .social-share a {
                width: 100%;
            }
        }
    </style>


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
                    <h1 class="article-title">Europe Survives Epic American Comeback to Win 2025 Ryder Cup 15-13 at Bethpage Black</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> September 28, 2025</span>
                        <span><i class="far fa-clock"></i> 8:42 PM</span>
                        <span><a href="/profile/ColeH" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/cole-harrington.webp" alt="Cole Harrington" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;">Cole Harrington</span></a></span>
                    </div>
                </header>

                <img src="/images/news/ryder-cup-2025-europe-survives-american-comeback-15-13/main.webp" alt="Team Europe celebrates their dramatic 15-13 Ryder Cup victory at Bethpage Black as Shane Lowry secures the crucial point" class="article-image">

                <div class="article-content">
                    <p><strong>FARMINGDALE, N.Y.</strong> — Team Europe survived one of the most dramatic comebacks in Ryder Cup history Sunday at Bethpage Black, holding off a furious American rally to win the 45th Ryder Cup 15-13 and claim their first victory on U.S. soil since the "Miracle at Medinah" in 2012. Despite entering the final day with a record-setting seven-point lead, Europe needed every ounce of their experience and resilience to withstand an American surge that nearly completed the greatest comeback in the event's storied history.</p>

                    <div class="round-highlight">
                        <h3><i class="fas fa-trophy"></i> Historic European Victory</h3>
                        <p>Europe wins 15-13 after surviving epic American comeback attempt, claiming first Ryder Cup victory on U.S. soil since 2012 with dramatic final-day heroics.</p>
                    </div>

                    <p>Shane Lowry delivered the decisive moment with a clutch birdie putt on the 18th hole of his match against Russell Henley, securing the half-point that guaranteed Europe would retain the Ryder Cup at 14 points. Tyrrell Hatton later sealed the outright victory by halving his match with Collin Morikawa, triggering wild celebrations among the European team and their traveling supporters who had witnessed the most thrilling conclusion to a Ryder Cup in over a decade.</p>

                    <h2>Sunday Singles Final Results</h2>

                    <div class="scoreboard">
                        <h3 class="scoreboard-title"><i class="fas fa-golf-ball"></i> Sunday Singles Session (USA 6-1-5)</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item">
                                <span class="player-rank">🇺🇸</span>
                                <span class="player-name">Thomas beat Fleetwood</span>
                                <span class="player-score">1-up</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">½</span>
                                <span class="player-name">DeChambeau tied Fitzpatrick</span>
                                <span class="player-score">Halved</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">🇺🇸</span>
                                <span class="player-name">Scheffler beat McIlroy</span>
                                <span class="player-score">1-up</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">🇪🇺</span>
                                <span class="player-name">Åberg beat Cantlay</span>
                                <span class="player-score">2&1</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">🇺🇸</span>
                                <span class="player-name">Schauffele beat Rahm</span>
                                <span class="player-score">4&3</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">🇺🇸</span>
                                <span class="player-name">Spaun beat Straka</span>
                                <span class="player-score">2&1</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">½</span>
                                <span class="player-name">Henley tied Lowry</span>
                                <span class="player-score">Halved</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">🇺🇸</span>
                                <span class="player-name">Griffin beat Højgaard</span>
                                <span class="player-score">1-up</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">½</span>
                                <span class="player-name">Morikawa tied Hatton</span>
                                <span class="player-score">Halved</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">½</span>
                                <span class="player-name">Burns tied MacIntyre</span>
                                <span class="player-score">Halved</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">½</span>
                                <span class="player-name">English tied Hovland (WD)</span>
                                <span class="player-score">Halved</span>
                            </li>
                        </ul>
                    </div>

                    <h2>American Rally Falls Just Short</h2>

                    <p>The United States mounted the most impressive comeback attempt in modern Ryder Cup history, winning 6 matches outright while halving 5 others to score 8.5 points in the singles session. The Americans needed to win at least 9.5 points to complete the comeback, and for several hours on Sunday afternoon, that impossible task seemed within reach as red flooded the scoreboards throughout Bethpage Black.</p>

                    <div class="putting-stats">
                        <h4><i class="fas fa-fire"></i> American Comeback Statistics</h4>
                        <p><strong>Starting Deficit:</strong> USA trailed 11.5-4.5, the largest deficit in Ryder Cup history since 1979.</p>
                        <p><strong>Sunday Performance:</strong> Americans went 6-1-5 in singles, scoring 8.5 points to tie the record for most points in a singles session.</p>
                        <p><strong>Key Victories:</strong> Scheffler over McIlroy (1-up), Schauffele over Rahm (4&3), Thomas over Fleetwood (1-up).</p>
                        <p><strong>Crucial Halves:</strong> Five tied matches, including Lowry's birdie to halve with Henley and retain the Cup for Europe.</p>
                    </div>

                    <p>Scottie Scheffler finally broke through with his first point of the week, defeating Rory McIlroy 1-up in the marquee battle between the world's top two players. The victory was redemptive for Scheffler, who had suffered through an unprecedented 0-4-0 start to become the first world No. 1 to begin a Ryder Cup so poorly in the event's history.</p>

                    <h2>Hovland Withdrawal Adds Drama</h2>

                    <p>The final day began with unexpected drama as Viktor Hovland was forced to withdraw from his singles match against Harris English due to a neck injury that had been troubling him since Saturday. The Norwegian underwent an MRI that revealed a flare-up of a previous disc bulge, leaving him unable to rotate his neck properly for competitive golf.</p>

                    <blockquote>
                        "I woke up this morning and I couldn't move my neck," Hovland explained after the withdrawal. "The MRI confirmed what we suspected - the disc bulge had flared up again. I tried everything possible to play, but I couldn't put my team at a disadvantage."
                    </blockquote>

                    <p>Under Ryder Cup rules, Hovland's match with English was declared a tie, giving each team half a point. The withdrawal meant Europe started the day with 12 points, needing just 2.5 more from the remaining 11 matches to secure victory, but the American response made even that modest target seem challenging to achieve.</p>

                    <h2>Clutch European Performances</h2>

                    <p>While the Americans dominated the early matches, several Europeans delivered crucial performances when their team needed them most. Ludvig Åberg provided Europe's only outright singles victory, defeating Patrick Cantlay 2&1 in a composed display that demonstrated why he was considered one of the rookies of the year on the DP World Tour.</p>

                    <p>Shane Lowry's dramatic birdie on the 18th hole to halve his match with Russell Henley will be remembered as one of the defining moments of the 2025 Ryder Cup. The Irishman's clutch putting under immense pressure exemplified the European resilience that ultimately proved decisive in retaining the Cup.</p>

                    <h2>Fleetwood Earns Special Recognition</h2>

                    <p>Despite losing his singles match to Justin Thomas 1-up, Tommy Fleetwood was awarded the Nicklaus-Jacklin Award for best representing the spirit of the Ryder Cup throughout the week. The Englishman finished with a remarkable 4-1-0 record, contributing four critical points to Europe's victory while maintaining the sportsmanship and integrity that defines the event's highest ideals.</p>

                    <p>Fleetwood's partnership with Rory McIlroy proved unstoppable in team play, as the "Fleetwood Mac" duo went undefeated in foursomes competition. His ability to perform under pressure while embodying the values of the game made him a deserving recipient of the prestigious award named for Jack Nicklaus and Tony Jacklin.</p>

                    <h2>Historic Achievement for European Golf</h2>

                    <p>Europe's victory represents their ninth Ryder Cup triumph in the last 12 competitions, continuing their remarkable dominance over American golf in team competition. The win was particularly significant as their first on U.S. soil since the legendary "Miracle at Medinah" in 2012, proving their ability to perform in the most hostile environments.</p>

                    <p>Captain Luke Donald's tactical brilliance throughout the week was vindicated by the final result, as his pairings and leadership guided Europe through their most challenging moments. The captain's calm demeanor and strategic acumen proved crucial in managing the pressure of defending a large lead against a desperate American comeback attempt.</p>

                    <p>For American golf, the narrow defeat will sting particularly because of how close they came to achieving the impossible. The performance demonstrated the depth and talent of U.S. golf while highlighting the mental toughness required to close out major championships and team competitions at the highest level.</p>

                    <p>As the celebrations continued long into the New York evening, Europe could reflect on a Ryder Cup victory that required every ounce of their experience, skill, and determination. The 15-13 final score told only part of the story of a weekend that will be remembered as one of the most thrilling in the event's distinguished history.</p>
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