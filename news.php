<?php
session_start();

// Get all news articles (in order of newest first)
$articles = [
    [
        'title' => 'Scheffler Captures First Claret Jug with Dominant Victory',
        'slug' => 'scheffler-wins-2025-british-open-final-round',
        'date' => '2025-07-21',
        'time' => '8:30 PM',
        'category' => 'Major Championship',
        'excerpt' => 'World No. 1 completes commanding four-shot triumph at Royal Portrush to claim his fourth major championship...',
        'image' => '/images/news/open-championship-final-2025/scottie-final-round.png',
        'featured' => true
    ],
    [
        'title' => 'Scheffler Extends Lead to Four Shots with Bogey-Free 67',
        'slug' => 'scheffler-extends-lead-open-championship-round-3',
        'date' => '2025-07-19',
        'time' => '6:45 PM',
        'category' => 'Major Championship',
        'excerpt' => 'World No. 1 Scottie Scheffler fires a bogey-free 67 in Round 3 at Royal Portrush to extend his lead to four shots...',
        'image' => '/images/news/open-championship-round-3/scheffler-family.jpg',
        'featured' => true
    ],
    [
        'title' => 'Scheffler Seizes Control with Career-Best 64',
        'slug' => 'scheffler-seizes-control-open-championship-round-2',
        'date' => '2025-07-18',
        'time' => '7:20 PM',
        'category' => 'Major Championship',
        'excerpt' => 'World No. 1 delivers masterclass performance with 7-under 64 to take Open Championship lead at Royal Portrush...',
        'image' => '/images/news/open-championship-round-2/scheffler-64.jpg',
        'featured' => true
    ],
    [
        'title' => 'Five Players Share Lead as Royal Portrush Shows Its Teeth',
        'slug' => 'open-championship-2025-round-1-royal-portrush',
        'date' => '2025-07-17',
        'time' => '8:15 PM',
        'category' => 'Major Championship',
        'excerpt' => 'Challenging conditions and ever-changing weather define opening day at the 153rd Open Championship...',
        'image' => '/images/news/open-championship-2025-round-1/royal-portrush-round1.jpg',
        'featured' => false
    ]
];

// Get search query if provided
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Filter articles based on search and category
$filtered_articles = $articles;
if (!empty($search_query)) {
    $filtered_articles = array_filter($filtered_articles, function($article) use ($search_query) {
        return stripos($article['title'], $search_query) !== false || 
               stripos($article['excerpt'], $search_query) !== false;
    });
}
if (!empty($category_filter)) {
    $filtered_articles = array_filter($filtered_articles, function($article) use ($category_filter) {
        return $article['category'] === $category_filter;
    });
}

// Get featured articles for carousel (latest 3)
$featured_articles = array_slice(array_filter($articles, function($article) {
    return $article['featured'];
}), 0, 3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golf News - Tennessee Golf Courses</title>
    <meta name="description" content="Stay updated with the latest golf news, tournament coverage, and insights from around the world of golf.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logos/tab-logo.png?v=2">
    <link rel="shortcut icon" href="/images/logos/tab-logo.png?v=2">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .news-page {
            padding-top: 90px;
            min-height: 40vh;
            background: var(--bg-light);
        }
        
        .page-header {
            background: var(--bg-white);
            padding: 2rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .page-title {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-gray);
        }
        
        .news-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        /* Featured Carousel */
        .featured-carousel {
            background: var(--bg-white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin-bottom: 3rem;
            overflow: hidden;
        }
        
        .carousel-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .carousel-title {
            font-size: 1.3rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .carousel-container {
            position: relative;
            height: 300px;
            overflow: hidden;
        }
        
        .carousel-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: end;
        }
        
        .carousel-slide.active {
            opacity: 1;
        }
        
        .slide-content {
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            width: 100%;
            padding: 2rem;
            color: white;
        }
        
        .slide-category {
            background: var(--secondary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        
        .slide-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        
        .slide-excerpt {
            opacity: 0.9;
            margin-bottom: 1rem;
        }
        
        .slide-meta {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.9);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .carousel-nav:hover {
            background: white;
            box-shadow: var(--shadow-medium);
        }
        
        .carousel-prev {
            left: 1rem;
        }
        
        .carousel-next {
            right: 1rem;
        }
        
        .carousel-dots {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
        }
        
        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .carousel-dot.active {
            background: white;
        }
        
        /* Search and Filter Bar */
        .search-filter-bar {
            background: var(--bg-white);
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .search-container {
            flex: 1;
            min-width: 300px;
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .search-input:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-gray);
        }
        
        .category-filter {
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: white;
            min-width: 150px;
        }
        
        .search-btn {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }
        
        /* News Grid */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .news-card {
            background: var(--bg-white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        
        .news-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .news-card:hover .news-image img {
            transform: scale(1.05);
        }
        
        .news-content {
            padding: 1.5rem;
        }
        
        .news-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .news-date {
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .news-category {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .news-title {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        
        .news-excerpt {
            color: var(--text-dark);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .read-more {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .read-more:hover {
            color: var(--primary-color);
            gap: 0.75rem;
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--text-gray);
        }
        
        .load-more-container {
            text-align: center;
            margin-top: 3rem;
        }
        
        .load-more-btn {
            background: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .load-more-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        @media screen and (max-width: 768px) {
            .search-filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-container {
                min-width: auto;
            }
            
            .news-grid {
                grid-template-columns: 1fr;
            }
            
            .carousel-nav {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <div class="news-page">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">Golf News</h1>
                <p class="page-subtitle">Stay updated with the latest happenings in golf worldwide</p>
            </div>
        </section>

        <div class="news-container">
            <!-- Featured Carousel -->
            <?php if (!empty($featured_articles)): ?>
            <section class="featured-carousel">
                <div class="carousel-header">
                    <h2 class="carousel-title">
                        <i class="fas fa-fire"></i>
                        Featured Stories
                    </h2>
                </div>
                <div class="carousel-container">
                    <?php foreach ($featured_articles as $index => $article): ?>
                    <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                         style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url('<?php echo htmlspecialchars($article['image']); ?>');">
                        <div class="slide-content">
                            <span class="slide-category"><?php echo htmlspecialchars($article['category']); ?></span>
                            <h3 class="slide-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                            <p class="slide-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                            <div class="slide-meta">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo date('M j, Y', strtotime($article['date'])); ?> • <?php echo $article['time']; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <button class="carousel-nav carousel-prev" onclick="changeSlide(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="carousel-nav carousel-next" onclick="changeSlide(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <div class="carousel-dots">
                        <?php foreach ($featured_articles as $index => $article): ?>
                        <span class="carousel-dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $index; ?>)"></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Search and Filter Bar -->
            <div class="search-filter-bar">
                <form method="GET" class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="search-input" 
                           placeholder="Search articles..." 
                           value="<?php echo htmlspecialchars($search_query); ?>">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_filter); ?>">
                </form>
                
                <form method="GET">
                    <select name="category" class="category-filter" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="Major Championship" <?php echo $category_filter === 'Major Championship' ? 'selected' : ''; ?>>Major Championships</option>
                        <option value="PGA Tour" <?php echo $category_filter === 'PGA Tour' ? 'selected' : ''; ?>>PGA Tour</option>
                        <option value="Course News" <?php echo $category_filter === 'Course News' ? 'selected' : ''; ?>>Course News</option>
                        <option value="Equipment" <?php echo $category_filter === 'Equipment' ? 'selected' : ''; ?>>Equipment</option>
                    </select>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                </form>
                
                <button type="submit" form="search-form" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>

            <!-- News Grid -->
            <section class="news-section">
                <?php if (!empty($filtered_articles)): ?>
                    <div class="news-grid">
                        <?php foreach ($filtered_articles as $article): ?>
                            <article class="news-card">
                                <div class="news-image">
                                    <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                </div>
                                <div class="news-content">
                                    <div class="news-meta">
                                        <span class="news-date">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo date('M j, Y', strtotime($article['date'])); ?>
                                        </span>
                                        <span class="news-category"><?php echo htmlspecialchars($article['category']); ?></span>
                                    </div>
                                    <h3 class="news-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                                    <p class="news-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                                    <a href="news/<?php echo htmlspecialchars($article['slug']); ?>" class="read-more">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Load More Button (when we have more articles) -->
                    <?php if (count($filtered_articles) >= 9): ?>
                    <div class="load-more-container">
                        <button class="load-more-btn" onclick="loadMoreArticles()">
                            <i class="fas fa-plus"></i> Load More Articles
                        </button>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; color: var(--text-gray);"></i>
                        <h3>No articles found</h3>
                        <p>Try adjusting your search terms or category filter.</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="/images/logos/logo.png" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="courses">Golf Courses</a></li>
                        <li><a href="reviews">Reviews</a></li>
                        <li><a href="news">News</a></li>
                        <li><a href="about">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="courses?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="courses?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="courses?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="courses?region=Memphis Area">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-of-service">Terms of Service</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
    <script>
        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
            currentSlide = index;
        }

        function changeSlide(direction) {
            currentSlide += direction;
            if (currentSlide >= totalSlides) currentSlide = 0;
            if (currentSlide < 0) currentSlide = totalSlides - 1;
            showSlide(currentSlide);
        }

        function goToSlide(index) {
            showSlide(index);
        }

        // Auto-advance carousel
        if (totalSlides > 1) {
            setInterval(() => {
                changeSlide(1);
            }, 5000);
        }

        // Search form handling
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.querySelector('.search-container form');
            if (searchForm) {
                searchForm.id = 'search-form';
            }
        });

        // Load more functionality (placeholder)
        function loadMoreArticles() {
            // This would load more articles via AJAX in a real implementation
            alert('Loading more articles... (feature coming soon!)');
        }
    </script>
</body>
</html>