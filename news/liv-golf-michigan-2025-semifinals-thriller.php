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
    
    <!-- Google Analytics -->
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
            font-weight: 500;
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

        .article-content blockquote {
            border-left: 4px solid #2e7d32;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <article>
                <header class="article-header">
                    <div class="article-category">Tournament News</div>
                    <h1 class="article-title"><?php echo $article_data['title']; ?></h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> <?php echo date('F j, Y', strtotime($article_data['date'])); ?></span>
                        <span><i class="far fa-clock"></i> 7:00 PM</span>
                        <span><i class="far fa-user"></i> <?php echo $article_data['author']; ?></span>
                    </div>
                </header>

                <img src="<?php echo $article_data['image']; ?>" alt="LIV Golf Michigan Team Championship semifinals action" class="article-image">

                <div class="article-content">
                <p><strong>PLYMOUTH, Mich.</strong> — In a day of dramatic match-play action at The Cardinal at Saint John's, three teams emerged victorious from the semifinals of the LIV Golf Michigan Team Championship, setting up Sunday's stroke-play finale for the $14 million prize.</p>

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

                <p style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e0e0e0; font-size: 0.875rem; color: #666; font-style: italic;">
                    Results compiled from official LIV Golf sources, Fox Sports, Golf News Net, and tournament coverage from The Cardinal at Saint John's in Plymouth, Michigan.
                </p>
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