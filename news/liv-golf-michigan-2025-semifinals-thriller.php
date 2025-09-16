<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../includes/seo.php';

// Article data for SEO
$article_data = [
    'title' => 'LIV Golf Michigan Team Championship Semifinals: Legion XIII, Crushers, Stinger Advance in Playoff Drama',
    'description' => 'Three teams survived intense semifinals action at The Cardinal at Saint John\'s in Plymouth, Michigan, setting up Sunday\'s stroke-play finale for the $14 million prize.',
    'image' => '/images/news/liv-golf-michigan-2025-semifinals-thriller/main.webp',
    'type' => 'article',
    'author' => 'Cole Harrington',
    'date' => '2025-08-23',
    'category' => 'Tournament News'
];

SEO::setupArticlePage($article_data);

$article_slug = 'liv-golf-michigan-2025-semifinals-thriller';
$article_title = 'LIV Golf Michigan Team Championship Semifinals: Legion XIII, Crushers, Stinger Advance in Playoff Drama';

$article = [
    'title' => 'LIV Golf Michigan Team Championship Semifinals: Legion XIII, Crushers, Stinger Advance in Playoff Drama',
    'slug' => 'liv-golf-michigan-2025-semifinals-thriller',
    'date' => '2025-08-23',
    'time' => '7:00 PM',
    'category' => 'Tournament News',
    'excerpt' => 'Three teams survived intense semifinals action at The Cardinal at Saint John\'s in Plymouth, Michigan, setting up Sunday\'s stroke-play finale for the $14 million prize.',
    'image' => '/images/news/liv-golf-michigan-2025-semifinals-thriller/main.webp',
    'featured' => true,
    'author' => 'Cole Harrington',
    'read_time' => '5 min read'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo SEO::generateMetaTags(); ?>
    <meta name="description" content="<?php echo $article['excerpt']; ?>">
    <meta name="keywords" content="LIV Golf, Michigan Team Championship, Legion XIII, Jon Rahm, Bryson DeChambeau, golf tournaments, Plymouth Michigan">
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
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-7VPNPCDTBP');
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
                <img src="<?php echo $article['image']; ?>" alt="LIV Golf Michigan Team Championship semifinals action" loading="lazy">
            </div>

            <!-- Article Content -->
            <article class="article-content">
                <p class="lead">
                    PLYMOUTH, Mich. — In a day of dramatic match-play action at The Cardinal at Saint John's, three teams emerged victorious from the semifinals of the LIV Golf Michigan Team Championship, setting up Sunday's stroke-play finale for the $14 million prize.
                </p>

                <p>
                    Legion XIII, Crushers GC, and Stinger GC each secured 2-1 victories in Saturday's semifinals, with all but one of the nine Championship bracket matches reaching at least the 17th hole in what proved to be one of the most competitive days in LIV Golf's team championship format.
                </p>

                <h2>Top Seed Survives Mickelson Challenge</h2>

                <p>
                    Jon Rahm's Legion XIII, the tournament's top seed, survived a stern test from Phil Mickelson's HyFlyers GC. The match proved decisive when Rahm squared off against the six-time major champion in singles play.
                </p>

                <blockquote>
                    "All square through 11, Rahm won two of the next three holes in what proved to be the decisive match," according to tournament officials.
                </blockquote>

                <p>
                    Cameron Tringale continued his perfect LIV Golf match-play record, improving to 7-0 with a 2&1 victory over Tyrrell Hatton. However, it was the young duo of Tom McKibbin and Caleb Surratt who sealed Legion XIII's advancement with a commanding 3&1 foursomes victory over Andy Ogletree and Brendan Steele.
                </p>

                <h2>DeChambeau's Bold Gamble Pays Off</h2>

                <p>
                    The day's most intriguing storyline centered around Bryson DeChambeau's tactical decision to face Talor Gooch rather than Brooks Koepka in the Crushers GC versus Smash GC semifinal.
                </p>

                <p>
                    DeChambeau's choice drew scrutiny, especially after Koepka's Friday comment: "I don't know what [DeChambeau] was afraid of." However, the five-time major champion's decision proved masterful as he defeated Gooch 1-up in a birdie-fest that saw DeChambeau make eight birdies to Gooch's six.
                </p>

                <blockquote>
                    "Look, I could have gone up against him," DeChambeau said of Koepka. "Brooks is a great fighter, and I would have loved to have played against him, but I felt like from a matchup perspective, Talor was going to be a more difficult force today."
                </blockquote>

                <p>
                    The Crushers secured their finals berth despite Koepka defeating Anirban Lahiri 1-up and the duo of Paul Casey and Charles Howell III clinching with a 2-up foursomes victory.
                </p>

                <h3>Semifinal Results Scoreboard</h3>

                <div class="scoreboard">
                    <div class="match-result">
                        <h4>Legion XIII def. HyFlyers GC, 2-1</h4>
                        <ul class="results-list">
                            <li>Jon Rahm def. Phil Mickelson, 2&1</li>
                            <li>Cameron Tringale def. Tyrrell Hatton, 2&1</li>
                            <li>McKibbin/Surratt def. Ogletree/Steele, 3&1 (Foursomes)</li>
                        </ul>
                    </div>
                    
                    <div class="match-result">
                        <h4>Crushers GC def. Smash GC, 2-1</h4>
                        <ul class="results-list">
                            <li>Bryson DeChambeau def. Talor Gooch, 1-up</li>
                            <li>Brooks Koepka def. Anirban Lahiri, 1-up</li>
                            <li>Casey/Howell III def. Kokrak/McDowell, 2-up (Foursomes)</li>
                        </ul>
                    </div>
                    
                    <div class="match-result">
                        <h4>Stinger GC def. Torque GC, 2-1</h4>
                        <ul class="results-list">
                            <li>Louis Oosthuizen/Charl Schwartzel def. Sebastian Munoz/Carlos Ortiz, 2&1</li>
                            <li>Dean Burmester contribution sealed victory</li>
                        </ul>
                    </div>
                </div>

                <h2>Dark Horse Stinger Continues Upset Run</h2>

                <p>
                    Perhaps the most surprising storyline has been Stinger GC's remarkable run to the finals. Led by Louis Oosthuizen, the team has been the tournament's dark horse after consecutive upsets of Ripper GC and Torque GC in match play.
                </p>

                <p>
                    Oosthuizen was brimming with confidence heading into Sunday's finale, noting that while the team had endured a rough season, they made a significant turnaround in Chicago and felt comfortable going into Michigan.
                </p>

                <h2>Sunday's Championship Format</h2>

                <p>
                    After two days of intense match-play competition, the format shifts dramatically for Sunday's finale. All twelve players from the three remaining teams will tee off from the first hole, with all four scores counting toward each team's cumulative total — identical to the regular season format.
                </p>

                <p>
                    The stroke-play finale promises to deliver a thrilling conclusion to the 2025 LIV Golf season, with $14 million in prize money on the line at The Cardinal at Saint John's in Plymouth, Michigan.
                </p>

                <div class="article-footer">
                    <p class="disclaimer">
                        <em>Results compiled from official LIV Golf sources, Fox Sports, Golf News Net, and tournament coverage from The Cardinal at Saint John's in Plymouth, Michigan.</em>
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

    
    
    <?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="/script.js"></script>
</body>
</html>