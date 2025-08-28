<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';

$article_slug = 'tour-championship-2025-round-2-fleetwood-henley-share-lead';
$article_title = 'Fleetwood and Henley Share Tour Championship Lead After Second Round';

$article = [
    'title' => 'Fleetwood and Henley Share Tour Championship Lead After Second Round',
    'slug' => 'tour-championship-2025-round-2-fleetwood-henley-share-lead',
    'date' => '2025-08-22',
    'time' => '7:30 PM',
    'category' => 'Tournament News',
    'excerpt' => 'Tommy Fleetwood fires 63 while Russell Henley posts 66 to tie for the lead at 13-under at East Lake Golf Club. Cameron Young shoots low round of 62 to move into third place.',
    'image' => '/images/news/tour-championship-2025-round-2-fleetwood-henley-share-lead/main.webp',
    'featured' => true,
    'author' => 'Cole Harrington',
    'read_time' => '4 min read'
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
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-light);
            line-height: 1.8;
        }
        
        .article-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 2rem 0;
        }
        
        .article-content h2 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin: 2rem 0 1rem 0;
        }
        
        .article-content h3 {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin: 1.5rem 0 1rem 0;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }
        
        .leaderboard-box {
            background: var(--bg-light);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 8px;
        }
        
        .leaderboard-box h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
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
        
        @media screen and (max-width: 768px) {
            .article-title {
                font-size: 2rem;
            }
            
            .article-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
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
                <span class="article-category"><?php echo htmlspecialchars($article['category']); ?></span>
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                <div class="article-meta">
                    <div class="article-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?php echo date('F j, Y', strtotime($article['date'])); ?> • <?php echo $article['time']; ?></span>
                    </div>
                    <div class="article-meta-item">
                        <a href="/profile?username=cole-harrington" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/cole-harrington.webp" alt="Cole Harrington" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;">Cole Harrington</span></a>
                    </div>
                    <div class="article-meta-item">
                        <i class="fas fa-clock"></i>
                        <span><?php echo $article['read_time']; ?></span>
                    </div>
                </div>
                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-featured-image">
            </div>

            <!-- Article Content -->
            <div class="article-content">
            <p><strong>ATLANTA, GA</strong> – Tommy Fleetwood and Russell Henley shared the lead at 13-under par after the second round of the 2025 Tour Championship at East Lake Golf Club on Thursday, setting up an exciting weekend battle for both the tournament title and the $25 million FedExCup prize.</p>

            <p>Fleetwood, still seeking his maiden PGA Tour victory, fired a bogey-free 7-under 63 in the second round, featuring eight birdies including back-to-back scores on the 17th and 18th holes. The Englishman has endured recent heartbreak, losing a one-shot lead at the Travelers Championship and being two ahead with three holes to play in Memphis before missing a playoff by one shot.</p>

            <div class="scoreboard">
                <h3><i class="fas fa-trophy"></i> Leaderboard After Round 2</h3>
                <ul class="scoreboard-list">
                    <li class="scoreboard-item">
                        <span class="player-rank">T1.</span>
                        <span class="player-name">Tommy Fleetwood</span>
                        <span class="player-score">-13 (63)</span>
                    </li>
                    <li class="scoreboard-item">
                        <span class="player-rank">T1.</span>
                        <span class="player-name">Russell Henley</span>
                        <span class="player-score">-13 (66)</span>
                    </li>
                    <li class="scoreboard-item">
                        <span class="player-rank">3.</span>
                        <span class="player-name">Cameron Young</span>
                        <span class="player-score">-11 (62)</span>
                    </li>
                    <li class="scoreboard-item">
                        <span class="player-rank">T4.</span>
                        <span class="player-name">Patrick Cantlay</span>
                        <span class="player-score">-8</span>
                    </li>
                    <li class="scoreboard-item">
                        <span class="player-rank">T4.</span>
                        <span class="player-name">Scottie Scheffler</span>
                        <span class="player-score">-8</span>
                    </li>
                    <li class="scoreboard-item">
                        <span class="player-rank">T6.</span>
                        <span class="player-name">Shane Lowry</span>
                        <span class="player-score">-7 (63)</span>
                    </li>
                    <li class="scoreboard-item">
                        <span class="player-rank">T6.</span>
                        <span class="player-name">Chris Gotterup</span>
                        <span class="player-score">-7 (63)</span>
                    </li>
                </ul>
            </div>

            <h2>Cameron Young Posts Low Round of the Day</h2>
            
            <p>Cameron Young stole the spotlight with the lowest round of the tournament so far, carding an 8-under 62 that included 15 one-putt greens. The recent Wyndham Championship winner birdied five of six holes and stuck seven approach shots within 7 feet in his final eight holes.</p>

            <p>"Just hit my wedges great, controlled my distance really well, and the greens were just soft enough that it's not bouncing much of anywhere, but nothing is really spinning much either," Young said after his round.</p>

            <h2>Henley Maintains Lead Despite Slower Start</h2>
            
            <p>Russell Henley, who opened with a spectacular 9-under 61 on Wednesday that included 207 feet of putts made – the most in his PGA Tour career – managed to stay tied for the lead despite a more subdued 4-under 66. The American closed with birdies on the 17th and 18th holes to maintain his position atop the leaderboard.</p>

            <h2>Scheffler Struggles in Second Round</h2>
            
            <p>World No. 1 Scottie Scheffler, who opened with a 7-under 63 to sit just one shot back, stumbled in the second round and fell to 8-under par, five shots behind the leaders. Patrick Cantlay made a late charge with a back nine 30, including a birdie-birdie-eagle finish to also reach 8-under.</p>

            <h2>Course Conditions and Format</h2>
            
            <p>Heavy rain earlier in the week left East Lake Golf Club playing soft, with preferred lies in place. The scoring average through two rounds was 67, with 13 players at 7-under 133 or better, indicating an extremely competitive leaderboard heading into the weekend.</p>

            <p>Tee times for the second round were moved up due to anticipated weather, with play beginning at 8 a.m. local time. The tournament features a new format for 2025, with all 30 FedExCup Playoffs qualifiers starting at even par in a straight 72-hole stroke-play event.</p>

            <h2>Weekend Outlook</h2>
            
            <p>With soft conditions expected to continue through the weekend, low scoring should persist at East Lake. Both Fleetwood and Henley are seeking their first PGA Tour victories, while Young continues to build his case for a U.S. Ryder Cup team selection with another strong performance.</p>

            <p>The winner of the Tour Championship will earn $4 million from the tournament purse, while the FedExCup champion receives an additional $25 million bonus, making this one of the richest prizes in professional golf.</p>
            </div>
        </div>
    </div>

    <?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>
</body>
</html>