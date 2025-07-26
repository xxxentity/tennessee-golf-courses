<?php
session_start();
require_once '../config/database.php';

$course_slug = 'two-rivers-golf-course';
$course_name = 'Two Rivers Golf Course';

// Check if user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    $rating = (int)$_POST['rating'];
    $comment_text = trim($_POST['comment_text']);
    $user_id = $_SESSION['user_id'];
    
    if ($rating >= 1 && $rating <= 5 && !empty($comment_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO course_comments (user_id, course_slug, course_name, rating, comment_text) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $course_slug, $course_name, $rating, $comment_text]);
            $success_message = "Your review has been posted successfully!";
        } catch (PDOException $e) {
            $error_message = "Error posting review. Please try again.";
        }
    } else {
        $error_message = "Please provide a valid rating and comment.";
    }
}

// Get existing comments
try {
    $stmt = $pdo->prepare("
        SELECT cc.*, u.username 
        FROM course_comments cc 
        JOIN users u ON cc.user_id = u.id 
        WHERE cc.course_slug = ? 
        ORDER BY cc.created_at DESC
    ");
    $stmt->execute([$course_slug]);
    $comments = $stmt->fetchAll();
    
    // Calculate average rating
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM course_comments WHERE course_slug = ?");
    $stmt->execute([$course_slug]);
    $rating_data = $stmt->fetch();
    $avg_rating = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : null;
    $total_reviews = $rating_data['total_reviews'] ?: 0;
    
} catch (PDOException $e) {
    $comments = [];
    $avg_rating = null;
    $total_reviews = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Rivers Golf Course - Municipal Golf in Nashville | Tennessee Golf Courses</title>
    <meta name="description" content="Two Rivers Golf Course - Nashville's premier municipal 18-hole course since 1973. Located at the confluence of Cumberland and Stones Rivers with Leon Howard design.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/tab-logo.png?v=2">
    <link rel="shortcut icon" href="../images/logos/tab-logo.png?v=2">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Header -->
    <section class="course-header">
        <div class="course-hero">
            <div class="course-hero-content">
                <div class="course-hero-text">
                    <div class="course-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Nashville, Tennessee</span>
                    </div>
                    <h1 class="course-title">Two Rivers Golf Course</h1>
                    <p class="course-subtitle">Nashville's Municipal Gem at the River Confluence</p>
                    
                    <div class="course-meta">
                        <div class="course-rating">
                            <?php if ($avg_rating): ?>
                                <div class="stars">
                                    <?php
                                    $full_stars = floor($avg_rating);
                                    $half_star = ($avg_rating - $full_stars) >= 0.5;
                                    
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $full_stars) {
                                            echo '<i class="fas fa-star"></i>';
                                        } elseif ($i == $full_stars + 1 && $half_star) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <span class="rating-text"><?php echo $avg_rating; ?> (<?php echo $total_reviews; ?> reviews)</span>
                            <?php else: ?>
                                <div class="stars">
                                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                                </div>
                                <span class="rating-text">Be the first to review</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="course-specs">
                            <div class="spec-item">
                                <i class="fas fa-flag"></i>
                                <span>18 Holes</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Par 72</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-ruler"></i>
                                <span>6,595 Yards</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="course-actions">
                        <a href="#contact" class="btn btn-primary">
                            <i class="fas fa-phone"></i>
                            Contact Course
                        </a>
                        <a href="#photos" class="btn btn-secondary">
                            <i class="fas fa-camera"></i>
                            View Photos
                        </a>
                    </div>
                </div>
                
                <div class="course-hero-image">
                    <div class="hero-image-container">
                        <img src="/images/courses/two-rivers-golf-course/1.jpeg" alt="Two Rivers Golf Course" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">Greens Fees</span>
                                <span class="price-amount">Municipal Rates</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Details -->
    <section class="course-details">
        <div class="container">
            <div class="details-grid">
                <!-- About Section -->
                <div class="details-main">
                    <div class="detail-section">
                        <h2>About Two Rivers Golf Course</h2>
                        <p>Built in 1973 and redesigned in 1991, Two Rivers Golf Course stands as Nashville's municipal golf masterpiece, strategically positioned at the historic confluence of the Cumberland and Stones Rivers in the heart of Donelson. This 18-hole, par 72 championship layout measures 6,595 yards from the championship tees, offering a challenging yet enjoyable experience for golfers of all skill levels.</p>
                        
                        <p>Designed by Leon Howard and situated on the former McGavock Plantation grounds, Two Rivers was the last municipal course built after the Metro-Davidson County consolidation. The course takes its name from its unique location where the Cumberland and Stones Rivers converge, creating a stunning natural backdrop that defines the Nashville golf experience.</p>
                        
                        <p>In 2016, the course underwent major improvements with new Ultradwarf Bermuda greens, and in 2005, a new clubhouse was opened featuring a pro shop, concessions, and a patio overlooking the first tee. The eighth hole provides spectacular views of the Nashville skyline, making it one of the most photographed holes in Middle Tennessee.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Course History & Heritage</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1800s</div>
                                <div class="timeline-content">
                                    <h4>McGavock Plantation Era</h4>
                                    <p>Course built on 1,100-acre plantation between Cumberland and Stones Rivers</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1960s</div>
                                <div class="timeline-content">
                                    <h4>Metro Consolidation</h4>
                                    <p>Suburban growth eastward into Donelson area after Metro-Davidson County merger</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1973</div>
                                <div class="timeline-content">
                                    <h4>Course Opening</h4>
                                    <p>Two Rivers opens as Nashville's newest municipal golf facility</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1991</div>
                                <div class="timeline-content">
                                    <h4>Leon Howard Redesign</h4>
                                    <p>Major renovation with new greens and tees design</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2005</div>
                                <div class="timeline-content">
                                    <h4>New Clubhouse</h4>
                                    <p>Modern facility with pro shop, restaurant, and panoramic views</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2016</div>
                                <div class="timeline-content">
                                    <h4>Greens Upgrade</h4>
                                    <p>Installation of new Ultradwarf Bermuda greens</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Course Layout & Features</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-mountain"></i>
                                </div>
                                <h4>Rolling Terrain</h4>
                                <p>Challenging layout through scenic rolling hills with dramatic elevation changes</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-water"></i>
                                </div>
                                <h4>River Views</h4>
                                <p>Spectacular vistas where Cumberland and Stones Rivers converge</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-city"></i>
                                </div>
                                <h4>Skyline Views</h4>
                                <p>Signature 8th hole offers panoramic views of Nashville skyline</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <h4>Natural Setting</h4>
                                <p>Limestone bluffs and wetlands create diverse playing conditions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-card">
                        <h3>Course Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Architect:</strong>
                                <span>Leon Howard (1991 Redesign)</span>
                            </div>
                            <div class="info-item">
                                <strong>Opened:</strong>
                                <span>1973</span>
                            </div>
                            <div class="info-item">
                                <strong>Type:</strong>
                                <span>Municipal</span>
                            </div>
                            <div class="info-item">
                                <strong>Holes:</strong>
                                <span>18</span>
                            </div>
                            <div class="info-item">
                                <strong>Par:</strong>
                                <span>72</span>
                            </div>
                            <div class="info-item">
                                <strong>Yardage:</strong>
                                <span>6,595 yards (Championship)</span>
                            </div>
                            <div class="info-item">
                                <strong>Tees:</strong>
                                <span>4 sets (5,336 to 6,595 yards)</span>
                            </div>
                            <div class="info-item">
                                <strong>Greens:</strong>
                                <span>Ultradwarf Bermuda (2016)</span>
                            </div>
                            <div class="info-item">
                                <strong>Signature Hole:</strong>
                                <span>8th - Nashville Skyline Views</span>
                            </div>
                        </div>
                    </div>

                    <div class="amenities-card">
                        <h3>Amenities & Services</h3>
                        <div class="amenities-list">
                            <div class="amenity-item">
                                <i class="fas fa-store"></i>
                                <span>Pro Shop</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-utensils"></i>
                                <span>Restaurant & Bar</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-car"></i>
                                <span>Golf Cart Rental</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Driving Range</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-bullseye"></i>
                                <span>Putting Green</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-users"></i>
                                <span>Group Events</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Golf Lessons</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-calendar"></i>
                                <span>Tournament Hosting</span>
                            </div>
                        </div>
                    </div>

                    <div class="contact-card" id="contact">
                        <h3>Contact Information</h3>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Address:</strong>
                                    <span>3140 McGavock Pike<br>Nashville, TN 37214</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 889-2675</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://www.nashville.gov/departments/parks/golf-courses/two-rivers-golf-course" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Hours:</strong>
                                    <span>Sunrise to Sunset<br>Year-Round</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery" id="photos">
        <div class="container">
            <h2>Course Photos</h2>
            <div class="gallery-grid">
                <div class="gallery-item" onclick="openModal('/images/courses/two-rivers-golf-course/1.jpeg', 'Two Rivers Golf Course Clubhouse')">
                    <img src="/images/courses/two-rivers-golf-course/1.jpeg" alt="Two Rivers Golf Course Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/two-rivers-golf-course/2.jpeg', 'Signature 8th Hole with Nashville Skyline')">
                    <img src="/images/courses/two-rivers-golf-course/2.jpeg" alt="Signature 8th Hole with Nashville Skyline">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/two-rivers-golf-course/3.jpeg', 'River Views from Course')">
                    <img src="/images/courses/two-rivers-golf-course/3.jpeg" alt="River Views from Course">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/two-rivers-golf-course/4.jpeg', 'Championship Tee Box')">
                    <img src="/images/courses/two-rivers-golf-course/4.jpeg" alt="Championship Tee Box">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/two-rivers-golf-course/5.jpeg', 'Ultradwarf Bermuda Greens')">
                    <img src="/images/courses/two-rivers-golf-course/5.jpeg" alt="Ultradwarf Bermuda Greens">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/two-rivers-golf-course/6.jpeg', 'Driving Range and Practice Facilities')">
                    <img src="/images/courses/two-rivers-golf-course/6.jpeg" alt="Driving Range and Practice Facilities">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <h2>Course Reviews</h2>
            
            <?php if ($is_logged_in): ?>
                <!-- Review Form -->
                <div class="review-form-container">
                    <h3>Share Your Experience</h3>
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-error"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="review-form">
                        <div class="rating-input">
                            <label>Your Rating:</label>
                            <div class="star-rating">
                                <input type="radio" name="rating" value="5" id="star5">
                                <label for="star5"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1"><i class="fas fa-star"></i></label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="comment_text">Your Review:</label>
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience playing Two Rivers Golf Course..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Submit Review
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p><a href="../auth/login.php">Login</a> to write a review</p>
                </div>
            <?php endif; ?>
            
            <!-- Display Reviews -->
            <div class="reviews-list">
                <?php if (empty($comments)): ?>
                    <div class="no-reviews">
                        <i class="fas fa-comments"></i>
                        <p>No reviews yet. Be the first to share your experience!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <strong class="reviewer-name"><?php echo htmlspecialchars($comment['username']); ?></strong>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $comment['rating'] ? 'filled' : 'empty'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="review-date">
                                    <?php echo date('M j, Y', strtotime($comment['created_at'])); ?>
                                </div>
                            </div>
                            <div class="review-content">
                                <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Photo Modal -->
    <div id="photoModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
        <div class="modal-caption" id="modalCaption"></div>
        <div class="modal-nav">
            <button class="modal-nav-btn" id="prevBtn" onclick="changeImage(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="modal-nav-btn" id="nextBtn" onclick="changeImage(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="../images/logos/logo.png" alt="Tennessee Golf Courses" class="footer-logo-image">
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
                        <li><a href="../courses">Golf Courses</a></li>
                        <li><a href="../reviews">Reviews</a></li>
                        <li><a href="../news">News</a></li>
                        <li><a href="../about">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="../courses.php?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="../courses.php?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="../courses.php?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="../courses.php?region=Memphis Area">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="../privacy-policy">Privacy Policy</a></li>
                        <li><a href="../terms-of-service">Terms of Service</a></li>
                        <li><a href="../contact">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../script.js"></script>
</body>
</html>