<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';

$article = [
    'title' => 'Cantlay Charges to Share Lead with Fleetwood After Tour Championship Third Round',
    'slug' => 'tour-championship-2025-round-3-cantlay-fleetwood-tied',
    'date' => '2025-08-23',
    'time' => '8:00 PM',
    'category' => 'Tournament News',
    'excerpt' => 'Patrick Cantlay fires 64 with late surge to tie Tommy Fleetwood at 16-under. Keegan Bradley posts day\'s best 63 while Russell Henley sits two back at East Lake.',
    'image' => '/images/news/tour-championship-2025-round-3-cantlay-fleetwood-tied/main.webp',
    'featured' => true,
    'author' => 'TGC Editorial Team',
    'read_time' => '5 min read'
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
            color: var(--text-gray);
        }
        
        .article-content ul {
            list-style: none;
            padding-left: 0;
        }
        
        .article-content ul li {
            padding-left: 1.5rem;
            position: relative;
            margin-bottom: 0.5rem;
            line-height: 1.8;
        }
        
        .article-content ul li:before {
            content: "•";
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.2rem;
            position: absolute;
            left: 0;
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
        
        .back-to-news {
            display: inline-block;
            margin-bottom: 2rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: gap 0.3s ease;
        }
        
        .back-to-news:hover {
            gap: 0.75rem;
        }
        
        @media (max-width: 768px) {
            .article-container {
                padding: 1rem;
            }
            
            .article-header {
                padding: 2rem;
            }
            
            .article-title {
                font-size: 2rem;
            }
            
            .article-content {
                padding: 2rem;
            }
            
            .article-featured-image {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <a href="/news" class="back-to-news">
                <i class="fas fa-arrow-left"></i>
                Back to News
            </a>

            <article>
                <div class="article-header">
                    <span class="article-category"><?php echo $article['category']; ?></span>
                    <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                    <div class="article-meta">
                        <div class="article-meta-item">
                            <i class="far fa-calendar"></i>
                            <span><?php echo date('F j, Y', strtotime($article['date'])); ?></span>
                        </div>
                        <div class="article-meta-item">
                            <i class="far fa-clock"></i>
                            <span><?php echo $article['time']; ?></span>
                        </div>
                        <div class="article-meta-item">
                            <i class="far fa-user"></i>
                            <span><?php echo $article['author']; ?></span>
                        </div>
                        <div class="article-meta-item">
                            <i class="far fa-eye"></i>
                            <span><?php echo $article['read_time']; ?></span>
                        </div>
                    </div>
                </div>

                <img src="<?php echo $article['image']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-featured-image">

                <div class="article-content">
                    <p><strong>ATLANTA —</strong> Patrick Cantlay delivered a spectacular closing stretch on Saturday at the Tour Championship, carding four birdies in his final five holes to fire a 6-under 64 and join Tommy Fleetwood at the top of the leaderboard at 16-under par heading into Sunday's finale at East Lake Golf Club.</p>

                    <p>The dramatic third round saw momentum shifts throughout the day, with Fleetwood holding a two-shot lead before a costly mistake at the par-3 15th hole opened the door for Cantlay's late charge. With $10 million on the line for the winner, the stage is set for a thrilling conclusion to the PGA Tour season.</p>

                    <h2>Cantlay's Closing Surge</h2>
                    
                    <p>Starting the day three shots behind the leaders, Cantlay methodically worked his way up the leaderboard before catching fire down the stretch. His exceptional finish included birdies at 14, 16, 17, and 18, demonstrating the clutch gene that has served him well throughout his career.</p>

                    <p>The performance puts Cantlay in prime position not only for the FedEx Cup title but also caught the attention of U.S. Ryder Cup captain Keegan Bradley, who is considering his captain's picks for the upcoming matches.</p>

                    <h2>Fleetwood's Rollercoaster Round</h2>
                    
                    <p>Tommy Fleetwood's quest for his first PGA Tour victory hit a significant snag at the treacherous 15th hole. After arriving with a two-shot lead, the Englishman's 6-iron found the water, resulting in a double bogey that temporarily handed the lead to Cantlay.</p>

                    <p>Showing tremendous resilience, Fleetwood bounced back immediately with a brilliant pitching wedge from the bunker to 12 feet for birdie on the 16th hole. He would finish with a 67 for the day, maintaining his share of the lead despite the setback.</p>

                    <blockquote>"This marks the third time this season that I'll enter the final round with at least a share of the lead. Tomorrow is another opportunity to get it done," Fleetwood reflected after his round.</blockquote>

                    <h2>Bradley Posts Low Round</h2>
                    
                    <p>The round of the day belonged to Keegan Bradley, who fired a spectacular 7-under 63 to vault himself into contention. The highlight of Bradley's round came at the 6th hole, where he holed out for eagle, igniting a run that saw him close with three consecutive birdies.</p>

                    <p>Bradley's 63 leaves him in solo fourth place at 13-under par, just three shots off the lead with 18 holes to play. His position as U.S. Ryder Cup captain adds an intriguing subplot as he watches potential picks like Cantlay perform under pressure.</p>

                    <h2>Henley Stays in the Hunt</h2>
                    
                    <p>Russell Henley, who shared the 36-hole lead, couldn't quite match the scoring pace on Saturday but remained firmly in contention. A crucial birdie on the 18th hole salvaged his round and left him alone in third place at 14-under par, just two shots behind the co-leaders.</p>

                    <p>"I didn't have my best stuff today, but finishing with a birdie keeps me right there. Two shots is nothing on Sunday at East Lake," Henley said.</p>

                    <h2>The Treacherous 15th</h2>
                    
                    <p>The par-3 15th hole proved to be the most challenging on the course Saturday, playing well over par as approximately one-third of the field found the water with their tee shots. The hole's difficulty added drama to the leaderboard and proved pivotal in the day's outcome.</p>

                    <h2>Scheffler Lurking</h2>
                    
                    <p>World No. 1 Scottie Scheffler remains within striking distance at 12-under par, four shots off the lead. The Masters champion will need a low round Sunday but has shown the ability to go deep when needed, making him a dangerous presence for the leaders.</p>

                    <h2>Sunday's Finale</h2>
                    
                    <p>The new format for 2025, which sees all players start at even par rather than with staggered scores, has created additional drama heading into the final round. With multiple players within realistic striking distance of the lead, Sunday promises to deliver excitement as the PGA Tour crowns its season-long champion.</p>

                    <p>The final pairing of Fleetwood and Cantlay will tee off at 2:50 p.m. ET, with live coverage on Golf Channel and NBC. With the FedEx Cup title and its $10 million prize on the line, expect fireworks at East Lake.</p>

                    <div class="scoreboard">
                        <h3><i class="fas fa-trophy"></i> Third Round Leaderboard</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item">
                                <span class="player-rank">T1.</span>
                                <span class="player-name">Patrick Cantlay</span>
                                <span class="player-score">-16 (64)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T1.</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">-16 (67)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">3.</span>
                                <span class="player-name">Russell Henley</span>
                                <span class="player-score">-14 (69)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">4.</span>
                                <span class="player-name">Keegan Bradley</span>
                                <span class="player-score">-13 (63)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">5.</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">-12 (67)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T6.</span>
                                <span class="player-name">Cameron Young</span>
                                <span class="player-score">-11 (73)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T6.</span>
                                <span class="player-name">Robert MacIntyre</span>
                                <span class="player-score">-11</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T8.</span>
                                <span class="player-name">Rory McIlroy</span>
                                <span class="player-score">-9</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T8.</span>
                                <span class="player-name">Sam Burns</span>
                                <span class="player-score">-9</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T8.</span>
                                <span class="player-name">Shane Lowry</span>
                                <span class="player-score">-9</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>
</body>
</html>