<?php
session_start();

$article = [
    'title' => 'Low Scoring Dominates Friday at Tour Championship as Fleetwood, Henley Share Lead',
    'slug' => 'tour-championship-2025-round-2-low-scoring-friday',
    'date' => '2025-08-23',
    'time' => '8:00 PM',
    'category' => 'Tournament News',
    'excerpt' => 'Tommy Fleetwood fires brilliant 63 to match Russell Henley at 13-under par. Cameron Young posts day\'s best 62 while Scottie Scheffler struggles to 69 in soft conditions at East Lake.',
    'image' => '/images/news/tour-championship-2025-round-2-low-scoring-friday/main.webp',
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
        
        .player-score {
            background: var(--bg-light);
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            border-left: 4px solid var(--primary-color);
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
                    <p><strong>ATLANTA —</strong> Perfect scoring conditions led to a barrage of low scores in Friday's second round of the Tour Championship at East Lake Golf Club, with Tommy Fleetwood's spectacular 63 matching Russell Henley's 36-hole total of 13-under par to share the lead heading into the weekend.</p>

                    <p>The Englishman's bogey-free round featured eight birdies, including a closing flourish with gains on both 17 and 18, demonstrating the kind of ball-striking and putting that has made him one of the tour's most consistent performers. Fleetwood carded six birdies on the back nine alone, taking full advantage of the soft conditions created by earlier weather.</p>

                    <h2>Cameron Young Posts Day's Best</h2>
                    
                    <p>While Fleetwood grabbed headlines, it was Cameron Young who signed for the day's lowest score, an exceptional 8-under 62 that vaulted him into solo third place at 11-under par. The 28-year-old, fresh off his maiden PGA Tour victory at the Wyndham Championship, continued his hot streak with six birdies on the back nine at East Lake.</p>

                    <p>"My game definitely feels sharp right now," Young said after his round. The performance puts him just two shots off the lead and firmly in contention for both the Tour Championship title and a potential Ryder Cup captain's pick.</p>

                    <h2>Henley Maintains Position</h2>
                    
                    <p>First-round leader Russell Henley couldn't match his opening 61 but did enough with a solid 66 to remain tied at the top. The Georgia native made crucial birdies on his final three holes, including gains at 16, 17, and 18, to ensure he kept pace with the charging Fleetwood.</p>

                    <h2>Scheffler Struggles on Moving Day</h2>
                    
                    <p>The day's biggest disappointment came from world No. 1 Scottie Scheffler, who could only manage a 1-under 69 after entering the day in second place. The Masters champion struggled with his typically reliable driving, losing 0.77 strokes to the field off the tee after leading that category on Thursday.</p>

                    <blockquote>"My game definitely doesn't feel off, but you look at 18, end up a foot off the fairway. 17, I have to chip out sideways. 14, I felt like I hit a good shot in there and it kicked right into the bunker," Scheffler explained.</blockquote>

                    <p>Despite a closing birdie on the par-5 18th, Scheffler finds himself five shots adrift at 8-under par, facing an uphill battle over the weekend.</p>

                    <h2>Other Notable Scores</h2>
                    
                    <p>The soft conditions and preferred lies led to a scoring fest, with multiple players taking advantage:</p>

                    <div class="player-score">
                        <strong>Shane Lowry and Chris Gotterup:</strong> Both fired impressive 63s to move to 7-under par
                    </div>
                    
                    <div class="player-score">
                        <strong>Patrick Cantlay:</strong> Recovered from a slow start with an eagle and two birdies over his final three holes
                    </div>

                    <div class="player-score">
                        <strong>Keegan Bradley and Harry Hall:</strong> Both shot 65s to reach 5-under for the tournament
                    </div>

                    <h2>Course Conditions Impact Scoring</h2>
                    
                    <p>The combination of heavy rains earlier in the week and Friday's calm conditions created ideal scoring opportunities. With lift-and-place rules in effect on all fairways, players were able to attack pins with confidence. The course averaged 3.20 strokes under par for the round, significantly lower than its typical Tour Championship setup.</p>

                    <p>Tournament officials had moved up tee times earlier in the day due to anticipated weather, with the first group teeing off at 8 a.m. local time. The early start allowed the entire field to complete their rounds before any significant weather arrived.</p>

                    <h2>Weekend Outlook</h2>
                    
                    <p>With 36 holes remaining in the PGA Tour's season finale, the leaderboard remains tightly bunched. Thirteen players sit within five shots of the lead, setting up what promises to be a thrilling weekend at East Lake. The new format, which sees all players starting at even par rather than with staggered scores, has added an extra layer of excitement to the championship.</p>

                    <p>Saturday's third round will see Fleetwood and Henley paired together in the final group, with Cameron Young joining Robert MacIntyre in the penultimate pairing. With $18 million on the line for the winner and the FedEx Cup title at stake, expect more fireworks as the tour's best battle for the season's ultimate prize.</p>

                    <h3>Second Round Leaderboard</h3>
                    <ul>
                        <li><strong>T1. Tommy Fleetwood:</strong> -13 (64-63)</li>
                        <li><strong>T1. Russell Henley:</strong> -13 (61-66)</li>
                        <li><strong>3. Cameron Young:</strong> -11 (67-62)</li>
                        <li><strong>T4. Robert MacIntyre:</strong> -9</li>
                        <li><strong>T4. Patrick Cantlay:</strong> -9</li>
                        <li><strong>6. Scottie Scheffler:</strong> -8 (66-69)</li>
                        <li><strong>T7. Shane Lowry:</strong> -7 (69-63)</li>
                        <li><strong>T7. Chris Gotterup:</strong> -7 (69-63)</li>
                        <li><strong>T7. Rory McIlroy:</strong> -7</li>
                        <li><strong>T7. Sam Burns:</strong> -7</li>
                    </ul>
                </div>
            </article>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>