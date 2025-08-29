<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../includes/seo.php';

// Article data for SEO
$article_data = [
    'title' => 'Tour Championship 2025: Complete Atlanta Tournament Recap and Tommy Fleetwood\'s Historic First Win',
    'description' => 'Complete recap of the 2025 Tour Championship featuring Tommy Fleetwood\'s long-awaited first PGA Tour victory and FedEx Cup triumph at East Lake Golf Club in Atlanta.',
    'image' => '/images/news/tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win/main.webp',
    'type' => 'article',
    'author' => 'Cole Harrington',
    'date' => '2025-08-25',
    'category' => 'Tournament News'
];

SEO::setupArticlePage($article_data);

$article_slug = 'tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win';
$article_title = 'Tour Championship 2025: Complete Atlanta Tournament Recap and Tommy Fleetwood\'s Historic First Win';
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
        
        @media (max-width: 768px) {
            .article-container {
                padding: 1rem;
            }
            
            .article-header, .article-content {
                padding: 1.5rem;
            }
            
            .article-title {
                font-size: 2rem;
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
                    <span class="article-category">Tournament Recap</span>
                    <h1 class="article-title">Tour Championship 2025: Complete Atlanta Tournament Recap and Tommy Fleetwood's Historic First Win</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 25, 2025</span>
                        <span><i class="far fa-clock"></i> 8:00 PM</span>
                        <span><a href="/profile/ColeH" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/cole-harrington.webp" alt="Cole Harrington" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;">Cole Harrington</span></a></span>
                    </div>
                </header>
                
                <img src="/images/news/tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win/main.webp" alt="Tour Championship 2025 Complete Atlanta Recap" class="article-image">
                
                <div class="article-content">
                    <p><strong>ATLANTA, Ga.</strong> — As the sun set over East Lake Golf Club on Sunday evening, Tommy Fleetwood finally had the moment that had eluded him for so long. After 163 PGA Tour starts, 30 top-five finishes, and six runner-up heartbreaks, the Englishman could finally call himself a PGA Tour winner. His victory at the 2025 Tour Championship was more than just a tournament win – it was the culmination of years of perseverance and the crowning achievement of claiming the FedEx Cup and its life-changing $10 million prize.</p>
                    
                    <p>The tournament showcased the dramatic impact of the PGA Tour's format revolution, as the elimination of starting strokes created a true meritocracy where every shot mattered equally. From Russell Henley's spectacular opening 61 to Patrick Cantlay's weekend surge and Scottie Scheffler's putting struggles, the week delivered four days of compelling drama that perfectly embodied the season-ending championship's prestigious status.</p>
                    
                    <h2>Fleetwood's Long-Awaited Breakthrough</h2>
                    
                    <p>Tommy Fleetwood's victory at East Lake represents one of the most emotionally satisfying wins in recent PGA Tour history. The 33-year-old Englishman had established himself as one of the world's most consistent players, with multiple Ryder Cup appearances and worldwide victories, but the elusive PGA Tour win had remained frustratingly out of reach.</p>
                    
                    <p>The breakthrough came at the perfect moment and venue, with Fleetwood's methodical approach and clutch putting proving ideal for East Lake's demanding layout. His ability to recover from disaster on the 15th hole demonstrated the mental fortitude that champions possess in crucial moments.</p>
                </div>
                
            </article>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>