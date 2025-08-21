<?php
session_start();

// Get all review articles (in order of newest first)
$reviews = [
    [
        'title' => 'Top 5 Best Golf Drivers of 2025: Maximum Distance and Forgiveness',
        'slug' => 'top-5-golf-drivers-2025',
        'date' => '2025-08-21',
        'time' => '3:15 PM',
        'category' => 'Equipment Reviews',
        'excerpt' => 'Discover the top 5 highest-rated golf drivers of 2025. Based on comprehensive testing, tour performance, and expert reviews from leading golf publications.',
        'image' => '/images/reviews/top-5-golf-drivers-2025/0.jpeg',
        'featured' => true,
        'author' => 'TGC Editorial Team',
        'read_time' => '8 min read'
    ],
    [
        'title' => 'Top 5 Best Golf Courses Near Knoxville, Tennessee 2025',
        'slug' => 'top-5-golf-courses-near-knoxville-tn',
        'date' => '2025-08-18',
        'time' => '2:15 PM',
        'category' => 'Course Reviews',
        'excerpt' => 'Discover the top 5 highest-rated golf courses near Knoxville, Tennessee. Based on expert rankings, player reviews, and championship pedigree from leading golf publications.',
        'image' => '/images/reviews/top-5-golf-courses-near-knoxville-tn/0.webp',
        'featured' => true,
        'author' => 'TGC Editorial Team',
        'read_time' => '8 min read'
    ],
    [
        'title' => 'Top 5 Best Golf Courses in Tennessee 2025: Championship Destinations',
        'slug' => 'top-5-golf-courses-tennessee',
        'date' => '2025-08-15',
        'time' => '4:30 PM',
        'category' => 'Course Reviews',
        'excerpt' => 'Discover the top 5 highest-rated golf courses in Tennessee. Based on expert rankings, championship pedigree, and architectural excellence from leading golf publications.',
        'image' => '/images/reviews/top-5-golf-courses-tennessee/0.webp',
        'featured' => true,
        'author' => 'TGC Editorial Team',
        'read_time' => '8 min read'
    ],
    [
        'title' => 'Top 5 Best Golf Balls of 2025: Tour-Level Performance Guide',
        'slug' => 'top-5-golf-balls-2025',
        'date' => '2025-08-11',
        'time' => '2:45 PM',
        'category' => 'Equipment Reviews',
        'excerpt' => 'Discover the top 5 highest-rated golf balls of 2025. Based on tour performance, robot testing, and comprehensive analysis of the latest releases.',
        'image' => '/images/reviews/top-5-golf-balls-2025/0.jpeg',
        'featured' => true,
        'author' => 'TGC Editorial Team',
        'read_time' => '8 min read'
    ],
    [
        'title' => 'Top 10 Best Putters of 2025: Highest Rated Golf Putters',
        'slug' => 'top-10-putters-2025-amazon-guide',
        'date' => '2025-08-06',
        'time' => '4:30 PM',
        'category' => 'Equipment Reviews',
        'excerpt' => 'Discover the top 10 highest-rated golf putters available on Amazon in 2025. Based on customer reviews, professional testing, and performance data.',
        'image' => '/images/reviews/top-10-putters-2025/0.jpeg',
        'featured' => true,
        'author' => 'TGC Editorial Team',
        'read_time' => '8 min read'
    ]
];

// Get search query if provided
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Filter reviews based on search and category
$filtered_reviews = $reviews;
if (!empty($search_query)) {
    $filtered_reviews = array_filter($filtered_reviews, function($review) use ($search_query) {
        return stripos($review['title'], $search_query) !== false || 
               stripos($review['excerpt'], $search_query) !== false;
    });
}
if (!empty($category_filter)) {
    $filtered_reviews = array_filter($filtered_reviews, function($review) use ($category_filter) {
        return $review['category'] === $category_filter;
    });
}

// Get featured reviews for carousel (latest 3)
$featured_reviews = array_slice(array_filter($reviews, function($review) {
    return $review['featured'];
}), 0, 3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golf Equipment Reviews - Tennessee Golf Courses</title>
    <meta name="description" content="In-depth reviews and buying guides for golf equipment, putters, clubs, and accessories. Expert analysis and recommendations.">
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
        .reviews-page {
            padding-top: 0px;
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
        
        .reviews-container {
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
            padding: 2rem 5rem;
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
        
        /* Reviews Grid */
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .review-card {
            background: var(--bg-white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }
        
        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        
        .review-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .review-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .review-card:hover .review-image img {
            transform: scale(1.05);
        }
        
        .review-content {
            padding: 1.5rem;
        }
        
        .review-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .review-date {
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .review-category {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .review-title {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        
        .review-excerpt {
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
        
        /* Clickable image and title styles */
        .clickable-image {
            display: block;
            cursor: pointer;
        }
        
        .clickable-image:hover img {
            transform: scale(1.05);
        }
        
        .clickable-title {
            color: var(--primary-color);
            transition: color 0.3s ease;
            cursor: pointer;
        }
        
        .clickable-title:hover {
            color: var(--secondary-color);
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
            
            .reviews-grid {
                grid-template-columns: 1fr;
            }
            
            .carousel-nav {
                display: none;
            }
            
            .slide-content {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <div class="reviews-page">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">Golf Equipment Reviews</h1>
                <p class="page-subtitle">In-depth reviews and buying guides for golf equipment and accessories</p>
            </div>
        </section>

        <div class="reviews-container">
            <!-- Featured Carousel -->
            <?php if (!empty($featured_reviews)): ?>
            <section class="featured-carousel">
                <div class="carousel-header">
                    <h2 class="carousel-title">
                        <i class="fas fa-star"></i>
                        Featured Reviews
                    </h2>
                </div>
                <div class="carousel-container">
                    <?php foreach ($featured_reviews as $index => $review): ?>
                    <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                         style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url('<?php echo htmlspecialchars($review['image']); ?>');">
                        <div class="slide-content">
                            <span class="slide-category"><?php echo htmlspecialchars($review['category']); ?></span>
                            <h3 class="slide-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                            <p class="slide-excerpt"><?php echo htmlspecialchars($review['excerpt']); ?></p>
                            <div class="slide-meta">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo date('M j, Y', strtotime($review['date'])); ?> • <?php echo $review['read_time']; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (count($featured_reviews) > 1): ?>
                    <button class="carousel-nav carousel-prev" onclick="changeSlide(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="carousel-nav carousel-next" onclick="changeSlide(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <div class="carousel-dots">
                        <?php foreach ($featured_reviews as $index => $review): ?>
                        <span class="carousel-dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $index; ?>)"></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Search and Filter Bar -->
            <div class="search-filter-bar">
                <form method="GET" class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="search-input" 
                           placeholder="Search reviews..." 
                           value="<?php echo htmlspecialchars($search_query); ?>">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_filter); ?>">
                </form>
                
                <form method="GET">
                    <select name="category" class="category-filter" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="Equipment Reviews" <?php echo $category_filter === 'Equipment Reviews' ? 'selected' : ''; ?>>Equipment</option>
                        <option value="Putters" <?php echo $category_filter === 'Putters' ? 'selected' : ''; ?>>Putters</option>
                        <option value="Drivers" <?php echo $category_filter === 'Drivers' ? 'selected' : ''; ?>>Drivers</option>
                        <option value="Irons" <?php echo $category_filter === 'Irons' ? 'selected' : ''; ?>>Irons</option>
                        <option value="Accessories" <?php echo $category_filter === 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                    </select>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                </form>
                
                <button type="submit" form="search-form" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>

            <!-- Reviews Grid -->
            <section class="reviews-section">
                <?php if (!empty($filtered_reviews)): ?>
                    <div class="reviews-grid">
                        <?php foreach ($filtered_reviews as $index => $review): ?>
                            <article class="review-card">
                                <a href="/reviews/<?php echo htmlspecialchars($review['slug']); ?>" class="review-image clickable-image" style="text-decoration: none;">
                                    <img src="<?php echo htmlspecialchars($review['image']); ?>" alt="<?php echo htmlspecialchars($review['title']); ?>">
                                </a>
                                <div class="review-content">
                                    <div class="review-meta">
                                        <span class="review-date">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo date('M j, Y', strtotime($review['date'])); ?>
                                        </span>
                                        <span class="review-category"><?php echo htmlspecialchars($review['category']); ?></span>
                                    </div>
                                    <a href="/reviews/<?php echo htmlspecialchars($review['slug']); ?>" style="text-decoration: none;">
                                        <h3 class="review-title clickable-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                                    </a>
                                    <p class="review-excerpt"><?php echo htmlspecialchars($review['excerpt']); ?></p>
                                    <a href="/reviews/<?php echo htmlspecialchars($review['slug']); ?>" class="read-more">
                                        Read Full Review <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Load More Button (when we have more reviews) -->
                    <?php if (count($filtered_reviews) >= 9): ?>
                    <div class="load-more-container">
                        <button class="load-more-btn" onclick="loadMoreReviews()">
                            <i class="fas fa-plus"></i> Load More Reviews
                        </button>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; color: var(--text-gray);"></i>
                        <h3>No reviews found</h3>
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
                        <img src="/images/logos/logo.webp" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61579553544749" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        <a href="https://x.com/TNGolfCourses" target="_blank" rel="noopener noreferrer"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/tennesseegolfcourses/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@TennesseeGolf" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/courses">Courses</a></li>
                        <li><a href="/reviews">Reviews</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/events">Events</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="/courses?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="/courses?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="/courses?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="/courses?region=Memphis Area">Memphis Area</a></li>
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
        function loadMoreReviews() {
            // This would load more reviews via AJAX in a real implementation
            alert('Loading more reviews... (feature coming soon!)');
        }
    </script>
</body>
</html>