<?php
require_once 'includes/init.php';

// Get latest 3 news articles (same as homepage)
$latest_news = [
    [
        'title' => 'LIV Golf Indianapolis 2025: Complete Tournament Recap and Entertainment Spectacle',
        'slug' => 'liv-golf-indianapolis-2025-complete-tournament-recap-entertainment',
        'date' => '2025-08-18',
        'time' => '7:45 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Complete recap of LIV Golf Indianapolis 2025 featuring Sebastian Munoz\'s maiden victory, Jon Rahm\'s Individual Championship defense, and spectacular entertainment from Riley Green and Jason Derulo.',
        'image' => '/images/news/liv-golf-indianapolis-2025-complete-tournament-recap-entertainment/main.webp'
    ],
    [
        'title' => 'BMW Championship 2025: Complete Tournament Recap and Community Impact',
        'slug' => 'bmw-championship-2025-complete-tournament-recap-community-impact',
        'date' => '2025-08-18',
        'time' => '8:00 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Complete recap of the 2025 BMW Championship featuring Scottie Scheffler\'s miraculous comeback victory and the tournament\'s extraordinary community impact for the Evans Scholars Foundation.',
        'image' => '/images/news/bmw-championship-2025-complete-tournament-recap-community-impact/main.webp'
    ],
    [
        'title' => 'Tennessee\'s Herrington Makes Historic Run to U.S. Amateur Final, Earns Major Championship Invitations',
        'slug' => 'tennessee-herrington-historic-run-125th-us-amateur-runner-up',
        'date' => '2025-08-17',
        'time' => '9:30 PM',
        'category' => 'Tennessee News',
        'excerpt' => 'Dickson native Jackson Herrington becomes first Tennessee golfer since 2013 to reach U.S. Amateur final, earning spots in 2026 Masters and U.S. Open while making family history.',
        'image' => '/images/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up/main.webp'
    ]
];

// Get latest 3 featured reviews
$featured_reviews = [
    [
        'title' => 'Top 5 Best Golf Balls of 2025: Tour-Level Performance Guide',
        'slug' => 'top-5-golf-balls-2025',
        'date' => '2025-08-11',
        'time' => '2:45 PM',
        'category' => 'Equipment Reviews',
        'excerpt' => 'Discover the top 5 highest-rated golf balls of 2025. Based on tour performance, robot testing, and comprehensive analysis of the latest releases.',
        'image' => '/images/reviews/top-5-golf-balls-2025/0.jpeg',
        'author' => 'TGC Editorial Team'
    ],
    [
        'title' => 'Top 10 Best Putters of 2025: Highest Rated Golf Putters',
        'slug' => 'top-10-putters-2025-amazon-guide',
        'date' => '2025-08-06',
        'time' => '4:30 PM',
        'category' => 'Equipment Reviews',
        'excerpt' => 'Discover the top 10 highest-rated golf putters available on Amazon in 2025. Based on customer reviews, professional testing, and performance data.',
        'image' => '/images/reviews/top-10-putters-2025/0.jpeg',
        'author' => 'TGC Editorial Team'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media - Tennessee Golf Courses</title>
    <meta name="description" content="Tennessee Golf Courses Media Hub - Your source for golf news, course reviews, and media content covering golf across the Volunteer State.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php include 'includes/favicon.php'; ?>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .media-page {
            padding-top: 90px;
            min-height: 80vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .media-hero {
            text-align: center;
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            margin-bottom: 80px;
            margin-top: -140px;
        }
        
        .media-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .media-hero p {
            font-size: 1.4rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .media-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .media-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
            margin-bottom: 4rem;
        }
        
        .media-section {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .media-section:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-large);
        }
        
        .media-section .icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
        }
        
        .media-section h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .media-section p {
            color: var(--text-gray);
            line-height: 1.8;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .media-btn {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .media-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .media-btn.secondary {
            background: var(--secondary-color);
        }
        
        .media-btn.secondary:hover {
            background: var(--accent-color);
        }
        
        .stats-section {
            background: var(--bg-light);
            padding: 4rem 3rem;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .stats-section h3 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-gray);
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .featured-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            text-align: center;
        }
        
        .featured-content h3 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .featured-content p {
            color: var(--text-gray);
            margin-bottom: 2rem;
            font-size: 1.1rem;
            line-height: 1.8;
        }
        
        .featured-section {
            margin-bottom: 4rem;
        }
        
        .featured-section h2 {
            text-align: center;
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 3rem;
            font-weight: 600;
        }
        
        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .featured-card {
            background: var(--bg-white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }
        
        .featured-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        
        .featured-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .featured-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .featured-card:hover .featured-image img {
            transform: scale(1.05);
        }
        
        .featured-card-content {
            padding: 1.5rem;
        }
        
        .featured-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            flex-wrap: wrap;
        }
        
        .featured-date {
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .featured-category {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .featured-title {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1.4;
            transition: color 0.3s ease;
        }
        
        .featured-title:hover {
            color: var(--secondary-color);
        }
        
        .featured-excerpt {
            color: var(--text-dark);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .read-more-btn {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .read-more-btn:hover {
            color: var(--primary-color);
            gap: 1rem;
        }
        
        .view-all-btn {
            display: block;
            text-align: center;
            background: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            max-width: 200px;
            margin: 0 auto;
        }
        
        .view-all-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        @media (max-width: 768px) {
            .media-hero h1 {
                font-size: 2.5rem;
            }
            
            .media-hero p {
                font-size: 1.2rem;
            }
            
            .media-sections {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .media-section {
                padding: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="media-page">
        <!-- Dynamic Navigation -->
        <?php include 'includes/navigation.php'; ?>
        
        <!-- Media Hero Section -->
        <div class="media-hero">
            <h1>Tennessee Golf Media Hub</h1>
            <p>Your source for golf news, reviews, and media content</p>
        </div>
        
        <!-- Media Content -->
        <div class="media-content">
            <!-- Featured News Section -->
            <div class="featured-section">
                <h2><i class="fas fa-newspaper" style="margin-right: 1rem;"></i>Latest Golf News</h2>
                <div class="featured-grid">
                    <?php foreach ($latest_news as $article): ?>
                    <article class="featured-card">
                        <div class="featured-image">
                            <img src="<?php echo $article['image']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" loading="lazy">
                        </div>
                        <div class="featured-card-content">
                            <div class="featured-meta">
                                <span class="featured-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('M j, Y', strtotime($article['date'])); ?>
                                </span>
                                <span class="featured-category"><?php echo $article['category']; ?></span>
                            </div>
                            <h3 class="featured-title">
                                <a href="/news/<?php echo $article['slug']; ?>" style="text-decoration: none; color: inherit;">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
                            </h3>
                            <p class="featured-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                            <a href="/news/<?php echo $article['slug']; ?>" class="read-more-btn">
                                Read Full Article <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <a href="/news" class="view-all-btn">View All News</a>
            </div>

            <!-- Featured Reviews Section -->
            <div class="featured-section">
                <h2><i class="fas fa-star" style="margin-right: 1rem;"></i>Featured Reviews</h2>
                <div class="featured-grid">
                    <?php foreach ($featured_reviews as $review): ?>
                    <article class="featured-card">
                        <div class="featured-image">
                            <img src="<?php echo $review['image']; ?>" alt="<?php echo htmlspecialchars($review['title']); ?>" loading="lazy">
                        </div>
                        <div class="featured-card-content">
                            <div class="featured-meta">
                                <span class="featured-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('M j, Y', strtotime($review['date'])); ?>
                                </span>
                                <span class="featured-category"><?php echo $review['category']; ?></span>
                            </div>
                            <h3 class="featured-title">
                                <a href="/reviews/<?php echo $review['slug']; ?>" style="text-decoration: none; color: inherit;">
                                    <?php echo htmlspecialchars($review['title']); ?>
                                </a>
                            </h3>
                            <p class="featured-excerpt"><?php echo htmlspecialchars($review['excerpt']); ?></p>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <a href="/reviews/<?php echo $review['slug']; ?>" class="read-more-btn">
                                    Read Review <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <a href="/reviews" class="view-all-btn">View All Reviews</a>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>