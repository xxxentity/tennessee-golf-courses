<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../includes/seo.php';

// Article data for SEO
$article_data = [
    'title' => 'Tommy Fleetwood Captures First PGA Tour Victory in Emotional Tour Championship Triumph',
    'description' => 'Tommy Fleetwood wins Tour Championship and FedEx Cup with final round 68 for 18-under total. Englishman claims first PGA Tour victory and $10 million prize at East Lake.',
    'image' => '/images/news/tour-championship-2025-final-round-fleetwood-historic-win/main.webp',
    'type' => 'article',
    'author' => 'Cole Harrington',
    'date' => '2025-08-24',
    'category' => 'Tournament News'
];

SEO::setupArticlePage($article_data);

$article_slug = 'tour-championship-2025-final-round-fleetwood-historic-win';
$article_title = 'Tommy Fleetwood Captures First PGA Tour Victory in Emotional Tour Championship Triumph';

$article = [
    'title' => 'Fleetwood Captures Historic First PGA Tour Win at Tour Championship',
    'slug' => 'tour-championship-2025-final-round-fleetwood-historic-win',
    'date' => '2025-08-24',
    'time' => '10:00 PM',
    'category' => 'Tournament News',
    'excerpt' => 'Tommy Fleetwood wins Tour Championship and FedEx Cup with final round 68 for 18-under total. Englishman claims first PGA Tour victory and $10 million prize at East Lake.',
    'image' => '/images/news/tour-championship-2025-final-round-fleetwood-historic-win/main.webp',
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
                            <a href="/profile/ColeH" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/cole-harrington.webp" alt="Cole Harrington" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;">Cole Harrington</span></a>
                        </div>
                        <div class="article-meta-item">
                            <i class="far fa-eye"></i>
                            <span><?php echo $article['read_time']; ?></span>
                        </div>
                    </div>
                </div>

                <img src="<?php echo $article['image']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-featured-image">

                <div class="article-content">
                    <p><strong>ATLANTA —</strong> Tommy Fleetwood ended years of heartbreak with a historic victory Sunday at the Tour Championship, closing with a 2-under 68 to capture his first PGA Tour title and the FedEx Cup championship at East Lake Golf Club. The 34-year-old Englishman finished at 18-under 262, three shots clear of Patrick Cantlay and Russell Henley.</p>

                    <p>The emotional victory came with two prestigious trophies — the FedEx Cup and the "Calamity Jane" replica putter for the Tour Championship — along with a $10 million prize. It marked the end of an agonizing drought for Fleetwood, whose 30 top-five finishes without a victory were the most on the PGA Tour in the past 100 years.</p>

                    <h2>Early Drama Sets the Tone</h2>
                    
                    <p>Fleetwood began the final round tied with Cantlay at 16-under par, but the 2021 FedEx Cup champion quickly fell behind with a disastrous start. Cantlay made bogey on the first hole, then three-putted for double bogey on the second, suddenly finding himself four shots behind the leader.</p>

                    <p>World No. 1 Scottie Scheffler, attempting to become the first player to win back-to-back FedEx Cup titles, also struggled early. His tee shot on the opening hole went out of bounds, leading to a bogey that dropped him five shots off the pace. His hopes ended at the par-3 15th when he hit his tee shot into the water for a double-bogey 5.</p>

                    <h2>Cantlay's Charge Falls Short</h2>
                    
                    <p>Despite his poor start, Cantlay showed remarkable resilience and mounted a comeback. A two-shot swing on the 10th hole — where Fleetwood made bogey from the left rough while Cantlay sank a 5-foot birdie putt — narrowed the gap to just one shot.</p>

                    <p>However, Cantlay's momentum stalled at the crucial par-3 11th. Unable to reach the green from a bunker, he made bogey while Fleetwood responded with back-to-back birdies at the 12th and 13th holes, hitting wedges to within 6 feet on both occasions.</p>

                    <h2>Conquering the 15th</h2>
                    
                    <p>The 218-yard par-3 15th hole had been Fleetwood's nemesis throughout the week. On Saturday, he found the water and made double bogey at the peninsula green. This time, facing the same treacherous shot with his lead on the line, Fleetwood managed a bogey and maintained his advantage.</p>

                    <blockquote>"It's easy for anybody to say that they are resilient, that they bounce back, that they have fight. It's different when you actually have to prove it," Fleetwood reflected after his victory.</blockquote>

                    <h2>A Long Journey to Victory</h2>
                    
                    <p>Fleetwood's path to this moment included numerous near-misses in 2025 alone. At the Travelers Championship in June, he led by one shot entering the final hole but three-putted to lose to Keegan Bradley. Two weeks ago at the FedEx St. Jude Championship, he held a two-shot lead with three holes to play before finishing third behind Justin Rose.</p>

                    <p>Despite seven victories on the DP World Tour and three other international wins, Fleetwood had never broken through on the PGA Tour in 164 career starts. His $33.4 million in career earnings were the most for any player without a tour victory.</p>

                    <h2>Emotional Scene at 18</h2>
                    
                    <p>As Fleetwood walked up the 18th fairway with victory assured, thousands of fans surrounded the green, chanting "Tommy! Tommy! Tommy!" The Englishman removed his cap, looked to the cloudy Atlanta sky, and let out a triumphant yell as his long locks flowed in the breeze.</p>

                    <blockquote>"I've been a PGA Tour winner for a long time, it's just always been in my mind," an emotional Fleetwood said.</blockquote>

                    <h2>Strong Finishes Throughout</h2>
                    
                    <p>Russell Henley, who opened the tournament with a spectacular 61, closed with a 69 to share second place with Cantlay at 15-under par. Both players earned significant FedEx Cup bonus money but fell just short of catching the inspired Fleetwood.</p>

                    <p>Corey Conners posted the round of the day with an 8-under 62, vaulting into a tie for fourth place at 14-under alongside Scheffler and Cameron Young, who shot 68 and 66 respectively. Keegan Bradley, serving dual duty as U.S. Ryder Cup captain and competitor, finished in seventh place after his Saturday 63.</p>

                    <h2>New Format Delivers Drama</h2>
                    
                    <p>The 2025 Tour Championship featured a new format, with all 30 players starting at even par rather than with staggered scores based on FedEx Cup standings. The change created a more traditional tournament feel and delivered drama throughout the week, culminating in Fleetwood's emotional breakthrough.</p>

                    <p>For Fleetwood, the victory represents not just the end of a drought but validation of his persistence through years of close calls. His name now joins the list of FedEx Cup champions, and more importantly for him, he can finally call himself a PGA Tour winner.</p>

                    <div class="scoreboard">
                        <h3><i class="fas fa-trophy"></i> Final Leaderboard</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item">
                                <span class="player-rank">1.</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">-18 (68)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T2.</span>
                                <span class="player-name">Russell Henley</span>
                                <span class="player-score">-15 (69)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T2.</span>
                                <span class="player-name">Patrick Cantlay</span>
                                <span class="player-score">-15 (71)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4.</span>
                                <span class="player-name">Corey Conners</span>
                                <span class="player-score">-14 (62)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4.</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">-14 (68)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4.</span>
                                <span class="player-name">Cameron Young</span>
                                <span class="player-score">-14 (66)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">7.</span>
                                <span class="player-name">Keegan Bradley</span>
                                <span class="player-score">-13 (70)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T8.</span>
                                <span class="player-name">Robert MacIntyre</span>
                                <span class="player-score">-11</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T8.</span>
                                <span class="player-name">Shane Lowry</span>
                                <span class="player-score">-11</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T8.</span>
                                <span class="player-name">Rory McIlroy</span>
                                <span class="player-score">-11</span>
                            </li>
                        </ul>
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