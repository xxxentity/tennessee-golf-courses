<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../includes/seo.php';

// Article data for SEO
$article_data = [
    'title' => 'Legion XIII Captures LIV Golf Team Championship in Thrilling Playoff Victory Over Crushers GC',
    'description' => 'Jon Rahm\'s Legion XIII defeated Bryson DeChambeau\'s Crushers GC in the first team championship playoff, claiming the $14 million prize at The Cardinal at Saint John\'s in Plymouth, Michigan.',
    'image' => '/images/news/liv-golf-michigan-2025-championship-legion-xiii-playoff-victory/main.webp',
    'type' => 'article',
    'author' => 'Cole Harrington',
    'date' => '2025-08-24',
    'category' => 'Tournament News'
];

SEO::setupArticlePage($article_data);

$article_slug = 'liv-golf-michigan-2025-championship-legion-xiii-playoff-victory';
$article_title = 'Legion XIII Captures LIV Golf Team Championship in Thrilling Playoff Victory Over Crushers GC';

$article = [
    'title' => 'Legion XIII Captures LIV Golf Team Championship in Thrilling Playoff Victory Over Crushers GC',
    'slug' => 'liv-golf-michigan-2025-championship-legion-xiii-playoff-victory',
    'date' => '2025-08-24',
    'time' => '11:00 PM',
    'category' => 'Tournament News',
    'excerpt' => 'Jon Rahm\'s Legion XIII defeated Bryson DeChambeau\'s Crushers GC in the first team championship playoff, claiming the $14 million prize at The Cardinal at Saint John\'s in Plymouth, Michigan.',
    'image' => '/images/news/liv-golf-michigan-2025-championship-legion-xiii-playoff-victory/main.webp',
    'featured' => true,
    'author' => 'Cole Harrington',
    'read_time' => '6 min read'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo SEO::generateMetaTags(); ?>
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php include '../includes/favicon.php'; ?>
    
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
            object-position: top;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-small);
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
        }
        
        .article-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            color: var(--text-gray);
        }
        
        .article-content h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin: 2.5rem 0 1.5rem;
        }
        
        .article-content h3 {
            font-size: 1.5rem;
            color: var(--text-dark);
            margin: 2rem 0 1rem;
        }
        
        .article-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            font-size: 1.2rem;
            color: var(--text-dark);
        }
        
        .scoreboard {
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 2rem;
            margin: 2rem 0;
        }
        
        .team-result {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }
        
        .team-result.champion {
            border-left-color: #FFD700;
            background: #FFFBF0;
        }
        
        .team-result.runner-up {
            border-left-color: #C0C0C0;
            background: #F8F8F8;
        }
        
        .team-result.third-place {
            border-left-color: #CD7F32;
            background: #FFF8F0;
        }
        
        .team-result h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .player-scores {
            list-style: none;
            padding: 0;
        }
        
        .player-scores li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
            font-size: 1rem;
        }
        
        .player-scores li:last-child {
            border-bottom: none;
        }
        
        .back-to-news {
            margin-bottom: 2rem;
        }
        
        .back-to-news a {
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-to-news a:hover {
            color: var(--secondary-color);
            gap: 0.75rem;
        }
        
        @media screen and (max-width: 768px) {
            .article-title {
                font-size: 2rem;
            }
            
            .article-header {
                padding: 2rem;
            }
            
            .article-content {
                padding: 2rem;
            }
            
            .article-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <!-- Back to News -->
            <div class="back-to-news">
                <a href="/news">
                    <i class="fas fa-arrow-left"></i>
                    Back to News
                </a>
            </div>

            <!-- Article Header -->
            <div class="article-header">
                <span class="article-category"><?php echo htmlspecialchars($article['category']); ?></span>
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                
                <div class="article-meta">
                    <div class="article-meta-item">
                        <a href="/profile/ColeH" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/cole-harrington.webp" alt="Cole Harrington" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;">Cole Harrington</span></a>
                    </div>
                    <div class="article-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?php echo date('F j, Y', strtotime($article['date'])); ?> at <?php echo $article['time']; ?></span>
                    </div>
                    <div class="article-meta-item">
                        <i class="fas fa-clock"></i>
                        <span><?php echo $article['read_time']; ?></span>
                    </div>
                </div>
                
                <p style="font-size: 1.2rem; color: var(--text-dark); margin: 0;">
                    <?php echo htmlspecialchars($article['excerpt']); ?>
                </p>
            </div>

            <!-- Featured Image -->
            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Jon Rahm Legion XIII defeats Bryson DeChambeau Crushers GC LIV Golf Michigan Team Championship playoff" class="article-featured-image">

            <!-- Article Content -->
            <div class="article-content">
                <p>
                    <strong>PLYMOUTH, Mich.</strong> — Jon Rahm's Legion XIII captured their first LIV Golf Team Championship in dramatic fashion Sunday, defeating Bryson DeChambeau's Crushers GC in the first playoff in team championship history to claim the $14 million prize at The Cardinal at Saint John's.
                </p>

                <p>
                    The tournament concluded the 2025 LIV Golf season with over 40,000 fans witnessing the most high-profile team golf competition in Michigan since the 2004 Ryder Cup at Oakland Hills. Both Legion XIII and Crushers GC finished regulation at 20-under par, forcing sudden-death extra holes to determine the champion.
                </p>

                <h2>Championship Sunday Final Leaderboard</h2>

                <div class="scoreboard">
                    <div class="team-result champion">
                        <h4>1. Legion XIII - 20-under (Won in Playoff) - $14 million</h4>
                        <ul class="player-scores">
                            <li>Caleb Surratt: 64 (-8)</li>
                            <li>Jon Rahm: 65 (-7)</li>
                            <li>Tom McKibbin: 65 (-7)</li>
                            <li>Tyrrell Hatton: 66 (-6)</li>
                        </ul>
                    </div>
                    
                    <div class="team-result runner-up">
                        <h4>2. Crushers GC - 20-under (Lost in Playoff) - $8 million</h4>
                        <ul class="player-scores">
                            <li>Bryson DeChambeau: 62 (-10)</li>
                            <li>Paul Casey: 65 (-7)</li>
                            <li>Anirban Lahiri: 65 (-7)</li>
                            <li>Charles Howell III: 68 (-4)</li>
                        </ul>
                    </div>
                    
                    <div class="team-result third-place">
                        <h4>3. Stinger GC - 12-under - $6 million</h4>
                        <ul class="player-scores">
                            <li>Dean Burmester: 65 (-7)</li>
                            <li>Charl Schwartzel: 66 (-6)</li>
                            <li>Branden Grace: 67 (-5)</li>
                            <li>Louis Oosthuizen: 70 (-2)</li>
                        </ul>
                    </div>
                </div>

                <h2>DeChambeau's Heroics Force Playoff</h2>

                <p>
                    DeChambeau appeared to single-handedly will his team to victory with a spectacular 8-under 62, the lowest score of the championship Sunday. The 2024 U.S. Open champion made crucial birdies at the 17th and 18th holes to erase what had been a five-shot Legion XIII lead early in the round.
                </p>

                <p>
                    However, Legion XIII responded with clutch play from their two stars. Tyrrell Hatton matched DeChambeau's heroics with birdies at 17 and 18, followed immediately by Rahm doing the same to force the playoff at 20-under par.
                </p>

                <blockquote>
                    "Legion XIII rallied to match the Crushers at 20 under, with Rahm and Tyrrell Hatton each birdying the final two holes of regulation."
                </blockquote>

                <h2>Historic Playoff Drama</h2>

                <p>
                    In the first team championship playoff in LIV Golf history, team captains selected their playing partners for the sudden-death format. Rahm chose Hatton for Legion XIII, while DeChambeau paired with Paul Casey for the Crushers.
                </p>

                <p>
                    The first playoff hole on the par-4 18th saw both teams make matching scores — one birdie and one par each — sending the championship to a second extra hole. On the decisive second playoff hole, precision proved paramount.
                </p>

                <p>
                    Both Rahm and Hatton knocked their wedge approaches tight to the pin, while the Crushers faltered under pressure. Casey's approach shot didn't carry the slope and rolled away from the flag, leaving him a lengthy birdie attempt. DeChambeau's wedge shot caught the rough between the bunker and green, forcing him to chip just to save par.
                </p>

                <p>
                    Rahm calmly sank a 6-footer while Hatton had a short putt for the deciding birdies, clinching Legion XIII's first team championship.
                </p>

                <h2>Record-Breaking Prize Money and Tournament Significance</h2>

                <p>
                    The $50 million total purse marked the largest prize money event of the 2025 LIV Golf season. Legion XIII's four players split the $14 million first-place prize, with each player receiving $1.4 million (10% each) and the remaining 60% going to the franchise.
                </p>

                <p>
                    Legion XIII becomes the fourth different team to capture the LIV Golf Team Championship, following 4Aces GC (2022), Crushers GC (2023), and Ripper GC (2024). The victory completes a dominant season for Rahm, who also won his second consecutive Individual Championship last week in Indianapolis.
                </p>

                <h2>Michigan's Golf Spectacle</h2>

                <p>
                    The three-day championship attracted over 40,000 fans to The Cardinal at Saint John's, marking LIV Golf's inaugural event in Michigan. DeChambeau provided entertainment throughout the weekend, frequently high-fiving fans and celebrating with the galleries.
                </p>

                <p>
                    The tournament represented the 50th event in LIV Golf history since the league's debut in June 2022, concluding the 2025 season with its most dramatic finish yet.
                </p>
            </div>
        </div>
    </div>

    <?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>
    <!-- Scripts -->
    <script src="/script.js"></script>
</body>
</html>