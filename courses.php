<?php
session_start();
require_once 'config/database.php';

// Get filters from URL parameters
$region_filter = isset($_GET['region']) ? $_GET['region'] : '';
$price_filter = isset($_GET['price']) ? $_GET['price'] : '';
$difficulty_filter = isset($_GET['difficulty']) ? $_GET['difficulty'] : '';
$amenities_filter = isset($_GET['amenities']) ? $_GET['amenities'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'rating';

// Static course data with real ratings from database
$courses_static = [
    [
        'id' => 1,
        'name' => 'Bear Trace at Harrison Bay',
        'location' => 'Harrison, TN',
        'region' => 'Chattanooga Area',
        'description' => 'Jack Nicklaus Signature Design with stunning lakefront views',
        'image' => '/images/courses/bear-trace-harrison-bay/1.jpeg',
        'price_range' => '$50-75',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Jack Nicklaus',
        'amenities' => ['Pro Shop', 'Restaurant', 'Driving Range', 'Putting Green'],
        'slug' => 'bear-trace-harrison-bay'
    ],
    [
        'id' => 2,
        'name' => 'TPC Southwind',
        'location' => 'Memphis, TN',
        'region' => 'Memphis Area',
        'description' => 'Championship PGA Tour venue with challenging water hazards',
        'image' => '/images/courses/tpc-southwind/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 70,
        'designer' => 'Ron Prichard',
        'amenities' => ['Pro Shop', 'Fine Dining', 'Tennis Courts', 'Swimming Pool', 'Fitness Center'],
        'slug' => 'tpc-southwind'
    ],
    [
        'id' => 3,
        'name' => 'Hermitage Golf Course',
        'location' => 'Old Hickory, TN',
        'region' => 'Nashville Area',
        'description' => 'Two championship courses including Golf Digest Top 10 President\'s Reserve',
        'image' => '/images/courses/hermitage-golf-course/1.jpeg',
        'price_range' => '$38-59',
        'difficulty' => 'Intermediate',
        'holes' => 36,
        'par' => 72,
        'designer' => 'Denis Griffiths & Gary Roger Baird',
        'amenities' => ['Pro Shop', 'Restaurant', 'Golf Instruction', 'Driving Range', 'Practice Greens', 'Cottages', 'Event Venue'],
        'slug' => 'hermitage-golf-course'
    ],
    [
        'id' => 4,
        'name' => 'Gaylord Springs Golf Links',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Scottish links-style course by Larry Nelson at Gaylord Opryland Resort',
        'image' => '/images/courses/gaylord-springs-golf-links/1.jpeg',
        'price_range' => 'Dynamic Pricing',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Larry Nelson',
        'amenities' => ['43,000 sq ft Clubhouse', 'Pro Shop', 'Restaurant', 'Golf Institute', 'Driving Range', 'Practice Facilities', 'Event Space'],
        'slug' => 'gaylord-springs-golf-links'
    ]
];

// Get real ratings from database for each course
$courses = [];
foreach ($courses_static as $course) {
    try {
        $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM course_comments WHERE course_slug = ?");
        $stmt->execute([$course['slug']]);
        $rating_data = $stmt->fetch();
        
        $course['avg_rating'] = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : null;
        $course['review_count'] = $rating_data['total_reviews'] ?: 0;
        
    } catch (PDOException $e) {
        $course['avg_rating'] = null;
        $course['review_count'] = 0;
    }
    
    $courses[] = $course;
}

// Get top 2 rated courses
$featured_courses = array_slice($courses, 0, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golf Courses in Tennessee - Find Your Perfect Course</title>
    <meta name="description" content="Discover the best golf courses in Tennessee. Browse by location, price, difficulty and amenities. Read reviews and book tee times.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .courses-page {
            padding-top: 140px;
            min-height: 100vh;
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
        
        .courses-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .filters-sidebar {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            height: fit-content;
            position: sticky;
            top: 160px;
        }
        
        .filter-group {
            margin-bottom: 2rem;
        }
        
        .filter-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .filter-option {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .filter-option input {
            margin-right: 0.5rem;
        }
        
        .filter-option label {
            font-size: 0.9rem;
            color: var(--text-dark);
            cursor: pointer;
        }
        
        .sort-section {
            margin-bottom: 2rem;
        }
        
        .sort-select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        .courses-content {
            background: var(--bg-white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            overflow: hidden;
        }
        
        .featured-section {
            padding: 2rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .featured-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .all-courses-section {
            padding: 2rem;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .course-card {
            background: var(--bg-white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-color: var(--primary-color);
        }
        
        .course-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .course-card:hover .course-image img {
            transform: scale(1.05);
        }
        
        .course-rating {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.95);
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 600;
            color: var(--gold-color);
        }
        
        .course-content {
            padding: 1.5rem;
        }
        
        .course-content h3 {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .course-location {
            color: var(--text-gray);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .course-description {
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .course-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .feature-tag {
            background: var(--bg-light);
            color: var(--primary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .course-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .amenity-icon {
            color: var(--secondary-color);
            font-size: 1.1rem;
            padding: 0.25rem;
        }
        
        .course-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        
        .course-price {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 1.1rem;
        }
        
        .view-course-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .view-course-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-medium);
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--text-gray);
        }
        
        @media screen and (max-width: 1024px) {
            .courses-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .filters-sidebar {
                position: static;
                margin-bottom: 1rem;
            }
        }
        
        @media screen and (max-width: 768px) {
            .courses-grid {
                grid-template-columns: 1fr;
            }
            
            .course-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <div class="courses-page">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">Tennessee Golf Courses</h1>
                <p class="page-subtitle">Discover premier golf destinations across the Volunteer State</p>
            </div>
        </section>

        <!-- Main Content -->
        <div class="courses-container">
            <!-- Filters Sidebar -->
            <aside class="filters-sidebar">
                <form method="GET" id="filters-form">
                    <!-- Sort Options -->
                    <div class="sort-section">
                        <div class="filter-title">Sort By</div>
                        <select class="sort-select" name="sort" onchange="this.form.submit()">
                            <option value="rating" <?php echo $sort_by === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                            <option value="price_low" <?php echo $sort_by === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo $sort_by === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="name" <?php echo $sort_by === 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                        </select>
                    </div>

                    <!-- Region Filter -->
                    <div class="filter-group">
                        <div class="filter-title">Region</div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="" id="region_all" <?php echo $region_filter === '' ? 'checked' : ''; ?>>
                            <label for="region_all">All Regions</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="Nashville Area" id="region_nash" <?php echo $region_filter === 'Nashville Area' ? 'checked' : ''; ?>>
                            <label for="region_nash">Nashville Area</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="Chattanooga Area" id="region_chat" <?php echo $region_filter === 'Chattanooga Area' ? 'checked' : ''; ?>>
                            <label for="region_chat">Chattanooga Area</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="Knoxville Area" id="region_knox" <?php echo $region_filter === 'Knoxville Area' ? 'checked' : ''; ?>>
                            <label for="region_knox">Knoxville Area</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="Memphis Area" id="region_mem" <?php echo $region_filter === 'Memphis Area' ? 'checked' : ''; ?>>
                            <label for="region_mem">Memphis Area</label>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="filter-group">
                        <div class="filter-title">Price Range</div>
                        <div class="filter-option">
                            <input type="radio" name="price" value="" id="price_all" <?php echo $price_filter === '' ? 'checked' : ''; ?>>
                            <label for="price_all">All Prices</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="price" value="budget" id="price_budget" <?php echo $price_filter === 'budget' ? 'checked' : ''; ?>>
                            <label for="price_budget">Under $50</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="price" value="moderate" id="price_mod" <?php echo $price_filter === 'moderate' ? 'checked' : ''; ?>>
                            <label for="price_mod">$50 - $100</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="price" value="premium" id="price_prem" <?php echo $price_filter === 'premium' ? 'checked' : ''; ?>>
                            <label for="price_prem">$100+</label>
                        </div>
                    </div>

                    <!-- Difficulty Filter -->
                    <div class="filter-group">
                        <div class="filter-title">Difficulty</div>
                        <div class="filter-option">
                            <input type="radio" name="difficulty" value="" id="diff_all" <?php echo $difficulty_filter === '' ? 'checked' : ''; ?>>
                            <label for="diff_all">All Levels</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="difficulty" value="Beginner" id="diff_beg" <?php echo $difficulty_filter === 'Beginner' ? 'checked' : ''; ?>>
                            <label for="diff_beg">Beginner</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="difficulty" value="Intermediate" id="diff_int" <?php echo $difficulty_filter === 'Intermediate' ? 'checked' : ''; ?>>
                            <label for="diff_int">Intermediate</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="difficulty" value="Advanced" id="diff_adv" <?php echo $difficulty_filter === 'Advanced' ? 'checked' : ''; ?>>
                            <label for="diff_adv">Advanced</label>
                        </div>
                    </div>

                    <!-- Amenities Filter -->
                    <div class="filter-group">
                        <div class="filter-title">Amenities</div>
                        <div class="filter-option">
                            <input type="checkbox" name="amenities[]" value="Pro Shop" id="amenity_pro" <?php echo strpos($amenities_filter, 'Pro Shop') !== false ? 'checked' : ''; ?>>
                            <label for="amenity_pro">Pro Shop</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" name="amenities[]" value="Restaurant" id="amenity_rest" <?php echo strpos($amenities_filter, 'Restaurant') !== false ? 'checked' : ''; ?>>
                            <label for="amenity_rest">Restaurant</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" name="amenities[]" value="Driving Range" id="amenity_range" <?php echo strpos($amenities_filter, 'Driving Range') !== false ? 'checked' : ''; ?>>
                            <label for="amenity_range">Driving Range</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" name="amenities[]" value="Putting Green" id="amenity_putting" <?php echo strpos($amenities_filter, 'Putting Green') !== false ? 'checked' : ''; ?>>
                            <label for="amenity_putting">Putting Green</label>
                        </div>
                    </div>
                </form>
            </aside>

            <!-- Courses Content -->
            <main class="courses-content">
                <!-- Featured Courses -->
                <section class="featured-section">
                    <h2 class="featured-title">
                        <i class="fas fa-star"></i>
                        Top Rated Courses
                    </h2>
                    <div class="courses-grid">
                        <?php foreach ($featured_courses as $course): ?>
                            <div class="course-card">
                                <div class="course-image">
                                    <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                                    <div class="course-rating">
                                        <?php if ($course['avg_rating'] !== null): ?>
                                            <i class="fas fa-star"></i>
                                            <?php echo number_format($course['avg_rating'], 1); ?>
                                            <span style="color: var(--text-gray); font-size: 0.9rem;">(<?php echo $course['review_count']; ?>)</span>
                                        <?php else: ?>
                                            <i class="fas fa-star" style="color: #ddd;"></i>
                                            <span style="color: #666;">No ratings yet</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="course-content">
                                    <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                                    <p class="course-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo htmlspecialchars($course['location']); ?>
                                    </p>
                                    <p class="course-description"><?php echo htmlspecialchars($course['description']); ?></p>
                                    <div class="course-features">
                                        <span class="feature-tag"><?php echo $course['holes']; ?> Holes</span>
                                        <span class="feature-tag">Par <?php echo $course['par']; ?></span>
                                        <span class="feature-tag"><?php echo htmlspecialchars($course['designer']); ?> Design</span>
                                    </div>
                                    <div class="course-amenities">
                                        <?php foreach ($course['amenities'] as $amenity): ?>
                                            <i class="fas fa-check amenity-icon" title="<?php echo htmlspecialchars($amenity); ?>"></i>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="course-footer">
                                        <span class="course-price"><?php echo htmlspecialchars($course['price_range']); ?></span>
                                        <a href="courses/<?php echo htmlspecialchars($course['slug']); ?>.php" class="view-course-btn">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- All Courses -->
                <section class="all-courses-section">
                    <h2 class="section-title">All Tennessee Golf Courses</h2>
                    <div class="courses-grid">
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $course): ?>
                                <div class="course-card">
                                    <div class="course-image">
                                        <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                                        <div class="course-rating">
                                            <i class="fas fa-star"></i>
                                            <?php echo number_format($course['avg_rating'], 1); ?>
                                            <span style="color: var(--text-gray); font-size: 0.9rem;">(<?php echo $course['review_count']; ?>)</span>
                                        </div>
                                    </div>
                                    <div class="course-content">
                                        <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                                        <p class="course-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($course['location']); ?>
                                        </p>
                                        <p class="course-description"><?php echo htmlspecialchars($course['description']); ?></p>
                                        <div class="course-features">
                                            <span class="feature-tag"><?php echo $course['holes']; ?> Holes</span>
                                            <span class="feature-tag">Par <?php echo $course['par']; ?></span>
                                            <span class="feature-tag"><?php echo htmlspecialchars($course['designer']); ?> Design</span>
                                        </div>
                                        <div class="course-amenities">
                                            <?php foreach ($course['amenities'] as $amenity): ?>
                                                <i class="fas fa-check amenity-icon" title="<?php echo htmlspecialchars($amenity); ?>"></i>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="course-footer">
                                            <span class="course-price"><?php echo htmlspecialchars($course['price_range']); ?></span>
                                            <a href="courses/<?php echo htmlspecialchars($course['slug']); ?>.php" class="view-course-btn">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-results">
                                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; color: var(--text-gray);"></i>
                                <h3>No courses found</h3>
                                <p>Try adjusting your filters to see more results.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </main>
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
                        <li><a href="courses.php">Golf Courses</a></li>
                        <li><a href="reviews.php">Reviews</a></li>
                        <li><a href="news.php">News</a></li>
                        <li><a href="about.php">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="courses.php?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="courses.php?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="courses.php?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="courses.php?region=Memphis Area">Memphis Area</a></li>
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

    <script src="/script.js?v=4"></script>
    <script>
        // Auto-submit form when filters change
        document.addEventListener('DOMContentLoaded', function() {
            const filterInputs = document.querySelectorAll('input[type="radio"], input[type="checkbox"]');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    document.getElementById('filters-form').submit();
                });
            });
        });
    </script>
</body>
</html>