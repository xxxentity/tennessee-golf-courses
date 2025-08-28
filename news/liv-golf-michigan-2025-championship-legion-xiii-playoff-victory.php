<?php
session_start();

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
    <title><?php echo htmlspecialchars($article['title']); ?> - Tennessee Golf Courses</title>
    <meta name="description" content="<?php echo htmlspecialchars($article['excerpt']); ?>">
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
                        <i class="fas fa-user"></i>
                        <span><?php echo htmlspecialchars($article['author']); ?></span>
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
            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="LIV Golf Michigan Team Championship" class="article-featured-image">

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
</body>
</html>