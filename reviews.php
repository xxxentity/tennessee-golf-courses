<?php
require_once 'includes/init.php';
require_once 'includes/seo.php';

// Set up SEO for reviews page
SEO::setupReviewsPage();

// Get all review articles (in order of newest first)
require_once 'includes/reviews-data-test.php';

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

// Re-index the filtered array to start from 0
$filtered_reviews = array_values($filtered_reviews);

// Get featured reviews for homepage (latest 3)
$featured_reviews = array_slice(array_filter($reviews, function($review) {
    return isset($review['featured']) && $review['featured'];
}), 0, 3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php echo SEO::generateMetaTags(); ?>
    <?php echo SEO::generateNewsKeywords(['golf equipment', 'reviews', 'golf gear', 'putters', 'drivers', 'golf balls', 'equipment guide', 'golf clubs']); ?>
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
            flex-wrap: wrap;
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
        
        .review-time {
            color: var(--text-gray);
            font-size: 0.8rem;
        }
        
        .review-title {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1.4;
            transition: color 0.3s ease;
        }
        
        .review-title:hover {
            color: var(--secondary-color);
        }
        
        .review-excerpt {
            color: var(--text-dark);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .review-author {
            margin-bottom: 1.5rem;
        }
        
        .author-name {
            color: var(--text-gray);
            font-size: 0.9rem;
            font-style: italic;
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
            
            .review-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
    
    <?php echo SEO::generateStructuredData(); ?>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <div class="reviews-page">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">Golf Reviews</h1>
                <p class="page-subtitle">In-depth reviews and guides for golf courses, equipment, and accessories</p>
            </div>
        </section>

        <div class="reviews-container">
            <!-- Search and Filter Bar -->
            <div class="search-filter-bar">
                <form method="GET" class="search-container" id="search-form">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="search-input" 
                           placeholder="Search reviews..." 
                           value="<?php echo htmlspecialchars($search_query); ?>">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_filter); ?>">
                </form>
                
                <form method="GET">
                    <select name="category" class="category-filter" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="Equipment Reviews" <?php echo $category_filter === 'Equipment Reviews' ? 'selected' : ''; ?>>Equipment Reviews</option>
                        <option value="Course Reviews" <?php echo $category_filter === 'Course Reviews' ? 'selected' : ''; ?>>Course Reviews</option>
                    </select>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                </form>
                
                <button type="submit" form="search-form" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>

            <!-- Reviews Grid -->
            <section class="reviews-section">
                <!-- Debug info -->
                <p>Total reviews loaded: <?php echo count($reviews ?? []); ?></p>
                <p>Filtered reviews: <?php echo count($filtered_reviews ?? []); ?></p>
                <?php if (isset($reviews) && count($reviews) > 0): ?>
                    <p>First review title: <?php echo htmlspecialchars($reviews[0]['title'] ?? 'No title'); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($filtered_reviews)): ?>
                    <div class="reviews-grid">
                        <?php 
                        $reviews_per_page = 6;
                        $total_reviews = count($filtered_reviews);
                        $initial_reviews = array_slice($filtered_reviews, 0, $reviews_per_page);
                        ?>
                        <?php foreach ($filtered_reviews as $index => $review): ?>
                            <article class="review-card" data-review-index="<?php echo $index; ?>" style="<?php echo $index >= $reviews_per_page ? 'display: none;' : ''; ?>">
                                <a href="reviews/<?php echo htmlspecialchars($review['slug']); ?>" class="review-image clickable-image" style="text-decoration: none;">
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
                                    <a href="reviews/<?php echo htmlspecialchars($review['slug']); ?>" style="text-decoration: none;">
                                        <h3 class="review-title clickable-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                                    </a>
                                    <p class="review-excerpt"><?php echo htmlspecialchars($review['excerpt']); ?></p>
                                    <?php if (isset($review['author'])): ?>
                                        <div class="review-author">
                                            <span class="author-name">By <?php echo htmlspecialchars($review['author']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <a href="reviews/<?php echo htmlspecialchars($review['slug']); ?>" class="read-more">
                                        Read Full Review <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Load More Button -->
                    <?php if ($total_reviews > $reviews_per_page): ?>
                    <div class="load-more-container" id="loadMoreContainer">
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
        // Search form handling
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('search-form').submit();
                    }
                });
            }
        });

        // Load more functionality
        let currentlyShown = <?php echo isset($reviews_per_page) ? $reviews_per_page : 6; ?>;
        const totalReviews = <?php echo isset($total_reviews) ? $total_reviews : count($filtered_reviews); ?>;
        const reviewsPerLoad = 3;
        
        function loadMoreReviews() {
            const reviews = document.querySelectorAll('.review-card');
            const nextBatch = Math.min(currentlyShown + reviewsPerLoad, totalReviews);
            
            for (let i = currentlyShown; i < nextBatch; i++) {
                if (reviews[i]) {
                    reviews[i].style.display = 'block';
                }
            }
            
            currentlyShown = nextBatch;
            
            // Hide button if all reviews are shown
            if (currentlyShown >= totalReviews) {
                const loadMoreContainer = document.getElementById('loadMoreContainer');
                if (loadMoreContainer) {
                    loadMoreContainer.style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>