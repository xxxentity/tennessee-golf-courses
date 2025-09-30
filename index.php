<?php
// Enhanced SEO and performance initialization - UPDATED: Featured Reviews Section
require_once 'includes/init.php';
require_once 'includes/seo.php';

// Set up SEO for homepage
SEO::setupHomepage();

// Get latest 3 news articles dynamically
require_once 'includes/news-data.php';
$latest_articles = array_slice($articles, 0, 3);

// Get latest 3 featured courses
$featured_courses = [
    [
        'name' => 'Fall Creek Falls State Park Golf Course',
        'location' => 'Spencer, TN',
        'description' => 'Joe Lee designed championship golf course in scenic Cumberland Plateau setting',
        'image' => '/images/courses/fall-creek-falls-state-park-golf-course/1.webp',
        'features' => ['18 Holes', 'Par 72', 'Joe Lee'],
        'slug' => 'fall-creek-falls-state-park-golf-course'
    ],
    [
        'name' => 'The Links at Galloway',
        'location' => 'Memphis, TN',
        'description' => 'Historic municipal course featuring challenging greens and classic design',
        'image' => '/images/courses/the-links-at-galloway/1.jpeg',
        'features' => ['18 Holes', 'Par 70', 'Kevin Tucker'],
        'slug' => 'the-links-at-galloway'
    ],
    [
        'name' => 'Sweetens Cove Golf Club',
        'location' => 'South Pittsburg, TN',
        'description' => 'Audacious and unique 9-hole course gaining national recognition',
        'image' => '/images/courses/sweetens-cove-golf-club/1.jpeg',
        'features' => ['9 Holes', 'Par 36', 'King Collins'],
        'slug' => 'sweetens-cove-golf-club'
    ]
];

// Get latest featured review articles dynamically
require_once 'includes/reviews-data.php';
$featured_reviews = array_slice($reviews, 0, 3);

// Calculate dynamic stats
$total_reviews = count($reviews); // Review articles count
$total_tournaments = 0; // Count tournament-related news articles

// Count tournament articles (includes championships, tournaments, opens, cups, etc.)
foreach ($articles as $article) {
    $title_lower = strtolower($article['title']);
    $category_lower = strtolower($article['category'] ?? '');

    // Check if article is tournament-related
    if (strpos($title_lower, 'championship') !== false ||
        strpos($title_lower, 'tournament') !== false ||
        strpos($title_lower, 'open') !== false ||
        strpos($title_lower, 'cup') !== false ||
        strpos($title_lower, 'classic') !== false ||
        strpos($title_lower, 'invitational') !== false ||
        strpos($category_lower, 'tournament') !== false) {
        $total_tournaments++;
    }
}

// Count total golf courses
$courses_dir = __DIR__ . '/courses/';
$total_courses = 0;
if (is_dir($courses_dir)) {
    $course_files = glob($courses_dir . '*.php');
    $total_courses = count($course_files);
}

// Count actual comments/reviews from database
$total_comments = 0;
try {
    // Get database connection
    require_once 'config/database.php';

    // Count course comments (reviews)
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM course_comments WHERE is_approved = TRUE");
    $stmt->execute();
    $course_comments = $stmt->fetch()['count'] ?? 0;

    // Count news/review article comments
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM news_comments WHERE is_approved = TRUE");
    $stmt->execute();
    $news_comments = $stmt->fetch()['count'] ?? 0;

    $total_comments = $course_comments + $news_comments;
} catch (PDOException $e) {
    // Fallback to estimated count if database is unavailable
    $total_comments = $total_courses * 15; // Estimate 15 comments per course
    error_log("Database error counting comments: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php echo SEO::generateMetaTags(); ?>
    <?php echo SEO::generateNewsKeywords(['golf', 'Tennessee', 'courses', 'reviews', 'sports', 'Nashville', 'Memphis']); ?>
    <link rel="stylesheet" href="styles.css?v=10">
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
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9809687352497578"
     crossorigin="anonymous"></script>
    
    <style>
        .search-container {
            position: relative;
            z-index: 99999 !important;
        }
        
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-height: 400px;
            overflow-y: auto;
            z-index: 2147483647 !important; /* Maximum z-index value */
            display: none;
            margin-top: 4px;
        }
        
        .search-result-item {
            padding: 12px 20px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .search-result-item:hover {
            background: #f5f5f5;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        .search-result-name {
            font-weight: 600;
            color: #333;
        }
        
        .search-result-location {
            color: #666;
            font-size: 0.9rem;
        }
        
        .search-no-results {
            padding: 20px;
            text-align: center;
            color: #666;
        }
        
        .hero-content {
            position: relative;
            z-index: 100;
        }
        
        .hero-search {
            position: relative;
            z-index: 200;
        }
        
        /* Ensure stats bar doesn't overlap */
        .stats-bar {
            position: relative;
            z-index: 1;
        }
        
        /* Featured Reviews Styles */
        .featured-reviews {
            padding: 80px 0;
            background: var(--bg-light);
        }
        
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
        
        @media (max-width: 768px) {
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
</head>
<body>

    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Discover Tennessee's Premier Golf Courses</h1>
            <p class="hero-subtitle">From the Great Smoky Mountains to the Mississippi River, explore the finest golf destinations in the Volunteer State</p>
            <div class="hero-search">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search courses by name or city..." class="search-input" id="hero-search-input">
                    <button class="search-btn">Find Courses</button>
                    <div class="search-results" id="hero-search-results"></div>
                </div>
            </div>
        </div>
        <div class="hero-overlay"></div>
    </section>

    <!-- Stats Bar -->
    <section class="stats-bar">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <i class="fas fa-golf-ball"></i>
                    <div class="stat-number"><?php echo $total_courses; ?></div>
                    <div class="stat-label">Golf Courses</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-star"></i>
                    <div class="stat-number"><?php echo number_format($total_comments); ?>+</div>
                    <div class="stat-label">Reviews</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-trophy"></i>
                    <div class="stat-number"><?php echo $total_tournaments; ?>+</div>
                    <div class="stat-label">Tournaments</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-cloud-sun"></i>
                    <div class="stat-number">Live</div>
                    <div class="stat-label">Weather</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section id="courses" class="featured-courses">
        <div class="container">
            <div class="section-header">
                <h2>Featured Golf Courses</h2>
                <p>Discover premium golf destinations across Tennessee</p>
            </div>
            <div class="courses-grid">
                <?php foreach ($featured_courses as $course): ?>
                <div class="course-card">
                    <a href="courses/<?php echo htmlspecialchars($course['slug']); ?>" class="course-image clickable-image" style="text-decoration: none;">
                        <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                    </a>
                    <div class="course-content">
                        <a href="courses/<?php echo htmlspecialchars($course['slug']); ?>" style="text-decoration: none;">
                            <h3 class="clickable-title"><?php echo htmlspecialchars($course['name']); ?></h3>
                        </a>
                        <p class="course-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($course['location']); ?></p>
                        <p class="course-description"><?php echo htmlspecialchars($course['description']); ?></p>
                        <div class="course-features">
                            <?php foreach ($course['features'] as $feature): ?>
                            <span class="feature-tag"><?php echo htmlspecialchars($feature); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <a href="courses/<?php echo htmlspecialchars($course['slug']); ?>" class="btn-primary">View Details</a>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
            <div class="section-footer">
                <a href="courses" class="btn-secondary">View All Courses</a>
            </div>
        </div>
    </section>


    <!-- Latest News -->
    <section id="news" class="latest-news">
        <div class="container">
            <div class="section-header">
                <h2>Latest Golf News</h2>
                <p>Stay updated with the latest happenings in golf worldwide</p>
            </div>
            <div class="news-grid">
                <?php foreach ($latest_articles as $article): ?>
                <article class="news-card">
                    <div class="news-image">
                        <a href="news/<?php echo htmlspecialchars($article['slug']); ?>" style="text-decoration: none;">
                            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                        </a>
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date"><?php echo date('M j, Y', strtotime($article['date'])); ?></span>
                            <span class="news-category"><?php echo htmlspecialchars($article['category']); ?></span>
                        </div>
                        <a href="news/<?php echo htmlspecialchars($article['slug']); ?>" style="text-decoration: none;">
                            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                        </a>
                        <p><?php echo htmlspecialchars($article['excerpt']); ?></p>
                        <a href="news/<?php echo htmlspecialchars($article['slug']); ?>" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div class="section-footer">
                <a href="news" class="btn-secondary">View All News</a>
            </div>
        </div>
    </section>

    <!-- Featured Reviews -->
    <section id="reviews" class="featured-reviews">
        <div class="container">
            <div class="section-header">
                <h2>Featured Reviews</h2>
                <p>In-depth reviews and buying guides for golf equipment and accessories</p>
            </div>
            <!-- Debug info -->
            
            <div class="reviews-grid">
                <?php foreach ($featured_reviews as $review): ?>
                <article class="review-card">
                    <div class="review-image">
                        <a href="/reviews/<?php echo htmlspecialchars($review['slug']); ?>" style="text-decoration: none;">
                            <img src="<?php echo htmlspecialchars($review['image']); ?>" alt="<?php echo htmlspecialchars($review['title']); ?>">
                        </a>
                    </div>
                    <div class="review-content">
                        <div class="review-meta">
                            <span class="review-date"><?php echo date('M j, Y', strtotime($review['date'])); ?></span>
                            <span class="review-category"><?php echo htmlspecialchars($review['category']); ?></span>
                            <?php if (isset($review['read_time'])): ?>
                                <span class="review-time"><?php echo htmlspecialchars($review['read_time']); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="/reviews/<?php echo htmlspecialchars($review['slug']); ?>" style="text-decoration: none;">
                            <h3 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                        </a>
                        <p class="review-excerpt"><?php echo htmlspecialchars($review['excerpt']); ?></p>
                        <?php if (isset($review['author'])): ?>
                            <div class="review-author">
                                <span class="author-name">By <?php echo htmlspecialchars($review['author']); ?></span>
                            </div>
                        <?php endif; ?>
                        <a href="/reviews/<?php echo htmlspecialchars($review['slug']); ?>" class="read-more">Read Full Review <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div class="section-footer">
                <a href="reviews" class="btn-secondary">View All Reviews</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section" style="padding: 80px 0; background: var(--bg-white);">
        <div class="container">
            <div class="section-header">
                <h2>About Tennessee Golf Courses</h2>
                <p>Your trusted guide to golf in the Volunteer State</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; margin-top: 40px;">
                <div style="text-align: center;">
                    <i class="fas fa-map-marked-alt" style="font-size: 48px; color: var(--primary-color); margin-bottom: 20px;"></i>
                    <h3 style="margin-bottom: 15px;">Comprehensive Directory</h3>
                    <p>Discover golf courses across all regions of Tennessee, from Nashville to Memphis, Chattanooga to Knoxville.</p>
                </div>
                <div style="text-align: center;">
                    <i class="fas fa-star" style="font-size: 48px; color: var(--gold-color); margin-bottom: 20px;"></i>
                    <h3 style="margin-bottom: 15px;">Verified Reviews</h3>
                    <p>Read authentic reviews from real golfers to help you choose the perfect course for your game.</p>
                </div>
                <div style="text-align: center;">
                    <i class="fas fa-calendar-check" style="font-size: 48px; color: var(--secondary-color); margin-bottom: 20px;"></i>
                    <h3 style="margin-bottom: 15px;">Easy Booking</h3>
                    <p>Find tee times, contact information, and book your next round at Tennessee's finest courses.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section id="newsletter" class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <div class="newsletter-text">
                    <h2>Stay in the Loop</h2>
                    <p>Get weekly updates on new courses, exclusive deals, and the latest Tennessee golf news delivered to your inbox.</p>
                </div>
                <div class="newsletter-form">
                    <div class="form-group">
                        <input type="email" id="newsletter-email" placeholder="Enter your email address" class="newsletter-input" required>
                        <button type="button" id="newsletter-submit" class="newsletter-btn">Subscribe</button>
                    </div>
                    <div id="newsletter-message" style="margin-top: 12px; padding: 8px; border-radius: 4px; display: none;"></div>
                </div>
            </div>
        </div>
    </section>

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

    <script src="/weather.js?v=6"></script>
    <script src="/script.js?v=6"></script>
    
    <script>
        // Autocomplete functionality for hero search
        const heroSearchInput = document.getElementById('hero-search-input');
        const heroSearchResults = document.getElementById('hero-search-results');
        let searchTimeout;

        heroSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                heroSearchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetchCourses(query);
            }, 300);
        });

        function fetchCourses(query) {
            fetch(`/search-courses.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }

        function displayResults(courses) {
            heroSearchResults.innerHTML = '';
            
            if (courses.length === 0) {
                heroSearchResults.innerHTML = '<div class="search-no-results">No courses found</div>';
                heroSearchResults.style.display = 'block';
                return;
            }
            
            courses.forEach(course => {
                const item = document.createElement('div');
                item.className = 'search-result-item';
                item.innerHTML = `
                    <div>
                        <div class="search-result-name">${course.name}</div>
                        <div class="search-result-location">${course.location}</div>
                    </div>
                    <i class="fas fa-chevron-right" style="color: #ccc;"></i>
                `;
                
                item.addEventListener('click', () => {
                    window.location.href = `/courses/${course.slug}`;
                });
                
                heroSearchResults.appendChild(item);
            });
            
            heroSearchResults.style.display = 'block';
        }

        // Close results when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.search-container')) {
                heroSearchResults.style.display = 'none';
            }
        });

        // Handle search button click
        document.querySelector('.search-btn').addEventListener('click', function() {
            const query = heroSearchInput.value.trim();
            if (query) {
                window.location.href = `/courses?search=${encodeURIComponent(query)}`;
            }
        });

        // Handle Enter key in search input
        heroSearchInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = `/courses?search=${encodeURIComponent(query)}`;
                }
            }
        });
    </script>
    
    <?php echo SEO::generateStructuredData(); ?>
</body>
</html>