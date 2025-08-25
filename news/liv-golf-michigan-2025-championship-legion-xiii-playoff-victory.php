<?php
session_start();

// Article information
$article = [
    'title' => 'Legion XIII Captures LIV Golf Team Championship in Thrilling Playoff Victory Over Crushers GC',
    'slug' => 'liv-golf-michigan-2025-championship-legion-xiii-playoff-victory',
    'date' => '2025-08-24',
    'time' => '11:00 PM',
    'category' => 'Tournament News',
    'excerpt' => 'Jon Rahm\'s Legion XIII defeated Bryson DeChambeau\'s Crushers GC in the first team championship playoff, claiming the $14 million prize at The Cardinal at Saint John\'s in Plymouth, Michigan.',
    'image' => '/images/news/liv-golf-michigan-2025-championship-legion-xiii-playoff-victory/main.webp',
    'featured' => true,
    'author' => 'TGC Editorial Team',
    'read_time' => '6 min read'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['title']; ?> | Tennessee Golf Courses</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo $article['excerpt']; ?>">
    <meta name="keywords" content="LIV Golf, Team Championship, Legion XIII, Jon Rahm, Bryson DeChambeau, Crushers GC, Michigan golf, playoff">
    <meta name="author" content="<?php echo $article['author']; ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $article['title']; ?>">
    <meta property="og:description" content="<?php echo $article['excerpt']; ?>">
    <meta property="og:image" content="https://tennesseegolfcourses.com<?php echo $article['image']; ?>">
    <meta property="og:url" content="https://tennesseegolfcourses.com/news/<?php echo $article['slug']; ?>">
    <meta property="og:type" content="article">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $article['title']; ?>">
    <meta name="twitter:description" content="<?php echo $article['excerpt']; ?>">
    <meta name="twitter:image" content="https://tennesseegolfcourses.com<?php echo $article['image']; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/news.css">
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
    </script>
</head>

<body>
    <?php include '../includes/navigation.php'; ?>

    <main class="news-article">
        <div class="container">
            <!-- Back to News -->
            <div class="back-to-news">
                <a href="/news" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Back to News
                </a>
            </div>

            <!-- Article Header -->
            <header class="article-header">
                <div class="category-badge tournament-news">Tournament News</div>
                <h1 class="article-title"><?php echo $article['title']; ?></h1>
                
                <div class="article-meta">
                    <div class="meta-left">
                        <span class="author"><?php echo $article['author']; ?></span>
                        <span class="separator">•</span>
                        <time datetime="<?php echo $article['date']; ?>T<?php echo date('H:i', strtotime($article['time'])); ?>">
                            <?php echo date('F j, Y', strtotime($article['date'])); ?> at <?php echo $article['time']; ?>
                        </time>
                    </div>
                    <div class="meta-right">
                        <span class="read-time"><?php echo $article['read_time']; ?></span>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <div class="featured-image">
                <img src="<?php echo $article['image']; ?>" alt="Legion XIII celebrates LIV Golf Team Championship victory" loading="lazy">
            </div>

            <!-- Article Content -->
            <article class="article-content">
                <p class="lead">
                    PLYMOUTH, Mich. — Jon Rahm's Legion XIII captured their first LIV Golf Team Championship in dramatic fashion Sunday, defeating Bryson DeChambeau's Crushers GC in the first playoff in team championship history to claim the $14 million prize at The Cardinal at Saint John's.
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
                    "Legion XIII rallied to match the Crushers at 20 under, with Rahm and Tyrrell Hatton each birdying the final two holes of regulation," according to tournament reports.
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

                <div class="article-footer">
                    <p class="disclaimer">
                        <em>Results compiled from official LIV Golf sources, Fox Sports, Golf Digest, Detroit News, and tournament coverage from The Cardinal at Saint John's in Plymouth, Michigan.</em>
                    </p>
                </div>
            </article>

            <!-- Share Buttons -->
            <div class="share-buttons">
                <h3>Share this article</h3>
                <div class="social-share">
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article['title']); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article['slug']); ?>" target="_blank" class="share-twitter">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article['slug']); ?>" target="_blank" class="share-facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article['slug']); ?>" target="_blank" class="share-linkedin">
                        <i class="fab fa-linkedin-in"></i> LinkedIn
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="/js/main.js"></script>
</body>
</html>