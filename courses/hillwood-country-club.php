<?php
session_start();
require_once '../config/database.php';

$course_slug = 'hillwood-country-club';
$course_name = 'Hillwood Country Club';

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
    <title>Hillwood Country Club - Historic Dick Wilson Design | Tennessee Golf Courses</title>
    <meta name="description" content="Hillwood Country Club - Elite Nashville private club since 1957 with Dick Wilson design. Ranked 14th in Tennessee with championship course and rich tradition.">
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
                    <h1 class="course-title">Hillwood Country Club</h1>
                    <p class="course-subtitle">Historic Dick Wilson Design Since 1957</p>
                    
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
                                <span>7,059 Yards</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="course-actions">
                        <a href="#contact" class="btn btn-primary">
                            <i class="fas fa-phone"></i>
                            Contact Club
                        </a>
                        <a href="#photos" class="btn btn-secondary">
                            <i class="fas fa-camera"></i>
                            View Photos
                        </a>
                    </div>
                </div>
                
                <div class="course-hero-image">
                    <div class="hero-image-container">
                        <img src="/images/courses/hillwood-country-club/1.jpeg" alt="Hillwood Country Club" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">Ranked</span>
                                <span class="price-amount">#14 TN</span>
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
                        <h2>About Hillwood Country Club</h2>
                        <p>Hillwood Country Club stands as one of Nashville's most prestigious and historically significant private golf clubs, representing 67 years of excellence in championship golf and southern hospitality. Founded in 1957 with a masterful Dick Wilson design, Hillwood has earned recognition as the 14th-ranked golf course in Tennessee by Golf Digest, cementing its status among the state's elite golf destinations.</p>
                        
                        <p>The collaboration between renowned architect Dick Wilson and local expertise from Eldridge "Bubber" Johnson created a layout that perfectly captures the essence of challenging, strategic golf. Set on rolling Nashville terrain, the 7,059-yard championship course features Wilson's signature deep fairway and greenside hazards, multiple creek crossings, and extensive bunkering that demands precision and course management from every player.</p>
                        
                        <p>What truly distinguishes Hillwood is its reputation for having the finest greens in Nashville, a testament to decades of meticulous maintenance and the club's unwavering commitment to championship playing conditions. The course's strategic design philosophy rewards thoughtful play while punishing wayward shots, creating an engaging test for golfers of all skill levels.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Dick Wilson Design Heritage</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1957</div>
                                <div class="timeline-content">
                                    <h4>Original Dick Wilson Design</h4>
                                    <p>Course opens with Dick Wilson architecture featuring signature hazards and strategic bunkering</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2003</div>
                                <div class="timeline-content">
                                    <h4>First Renaissance Golf Renovation</h4>
                                    <p>Bruce Hepner leads major renovation to modernize while preserving Wilson's design intent</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2011</div>
                                <div class="timeline-content">
                                    <h4>Second Renaissance Golf Enhancement</h4>
                                    <p>Additional improvements by Bruce Hepner further refine course conditioning and playability</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">Present</div>
                                <div class="timeline-content">
                                    <h4>Golf Digest Recognition</h4>
                                    <p>Ranked 14th in Tennessee, recognized for championship conditions and design excellence</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Championship Course Features</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-water"></i>
                                </div>
                                <h4>Strategic Water Features</h4>
                                <p>Multiple creek crossings and water hazards integrated into Wilson's strategic design</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <h4>Signature Bunkering</h4>
                                <p>Deep fairway and greenside bunkers characteristic of Dick Wilson's design philosophy</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <h4>Elite Greens</h4>
                                <p>Recognized as having the best greens in Nashville with championship speed and conditions</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h4>Tournament Heritage</h4>
                                <p>Host to multiple Tennessee championships and major amateur competitions</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Dick Wilson Design Philosophy</h3>
                        <p>Dick Wilson, one of America's most influential golf course architects of the mid-20th century, designed Hillwood during the peak of his career. Wilson's philosophy emphasized strategic shot placement over pure length, creating courses that rewarded precision and punished careless play. His signature style featured generous fairways that narrowed near the greens, deep bunkers positioned to catch wayward shots, and complex green sites that demanded accurate approach play.</p>
                        
                        <p>At Hillwood, Wilson masterfully utilized the natural Nashville topography to create a layout that feels both challenging and fair. The course's numerous creek crossings and elevation changes add visual appeal while creating strategic decision points throughout the round. Wilson's collaboration with local expert Eldridge "Bubber" Johnson ensured the design perfectly suited the Tennessee landscape and climate.</p>
                        
                        <p>The 2003 and 2011 renovations by Bruce Hepner and Renaissance Golf were carefully planned to honor Wilson's original design intent while incorporating modern maintenance practices and player expectations. These enhancements have preserved the course's championship character while ensuring it remains relevant for today's game.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Tournament Tradition & Recognition</h3>
                        <p>Hillwood Country Club has established itself as a premier tournament venue in Tennessee, hosting multiple state championships and significant amateur competitions. The course's challenging layout and exceptional conditioning make it a preferred choice for high-level competitive golf, with its strategic design testing the skills of Tennessee's finest amateur players.</p>
                        
                        <p>The club's tournament heritage includes hosting several Tennessee State Championships and a notable US Women's Senior Amateur, demonstrating the course's ability to challenge golfers at the highest level. The combination of Dick Wilson's strategic design and the club's commitment to championship conditions creates an ideal setting for competitive golf.</p>
                        
                        <p>Golf Digest's recognition of Hillwood as the 14th-ranked course in Tennessee reflects both the architectural significance of the Dick Wilson design and the club's unwavering commitment to excellence in course conditioning and member experience.</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-card">
                        <h3>Club Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Founded:</strong>
                                <span>1957</span>
                            </div>
                            <div class="info-item">
                                <strong>Designer:</strong>
                                <span>Dick Wilson & Eldridge Johnson</span>
                            </div>
                            <div class="info-item">
                                <strong>Renovations:</strong>
                                <span>Bruce Hepner (2003, 2011)</span>
                            </div>
                            <div class="info-item">
                                <strong>Type:</strong>
                                <span>Private Country Club</span>
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
                                <strong>Total Yardage:</strong>
                                <span>7,059 yards</span>
                            </div>
                            <div class="info-item">
                                <strong>Course Rating:</strong>
                                <span>73.2</span>
                            </div>
                            <div class="info-item">
                                <strong>Slope Rating:</strong>
                                <span>133</span>
                            </div>
                            <div class="info-item">
                                <strong>Golf Digest Ranking:</strong>
                                <span>14th in Tennessee</span>
                            </div>
                        </div>
                    </div>

                    <div class="amenities-card">
                        <h3>Club Amenities</h3>
                        <div class="amenities-list">
                            <div class="amenity-item">
                                <i class="fas fa-home"></i>
                                <span>Historic Clubhouse</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-utensils"></i>
                                <span>Fine Dining Restaurant</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-wine-glass"></i>
                                <span>Private Dining Rooms</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-swimmer"></i>
                                <span>Swimming Pool</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-table-tennis"></i>
                                <span>Tennis Courts</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-dumbbell"></i>
                                <span>Fitness Center</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-store"></i>
                                <span>Professional Golf Shop</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Championship Practice Facilities</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>PGA Professional Instruction</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-calendar"></i>
                                <span>Tournament & Event Hosting</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-users"></i>
                                <span>Member Events & Activities</span>
                            </div>
                        </div>
                    </div>

                    <div class="contact-card" id="contact">
                        <h3>Membership Information</h3>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Address:</strong>
                                    <span>6201 Hickory Valley Road<br>Nashville, TN 37205</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 352-0950</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-crown"></i>
                                <div>
                                    <strong>Membership:</strong>
                                    <span>Private Club by Invitation</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-trophy"></i>
                                <div>
                                    <strong>Golf Digest:</strong>
                                    <span>14th in Tennessee</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-star"></i>
                                <div>
                                    <strong>Recognition:</strong>
                                    <span>Best Greens in Nashville</span>
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
            <h2>Club Photos</h2>
            <div class="gallery-grid">
                <div class="gallery-item" onclick="openModal('/images/courses/hillwood-country-club/1.jpeg', 'Hillwood Country Club Clubhouse')">
                    <img src="/images/courses/hillwood-country-club/1.jpeg" alt="Hillwood Country Club Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/hillwood-country-club/2.jpeg', 'Dick Wilson Design Features')">
                    <img src="/images/courses/hillwood-country-club/2.jpeg" alt="Dick Wilson Design Features">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/hillwood-country-club/3.jpeg', 'Championship Greens')">
                    <img src="/images/courses/hillwood-country-club/3.jpeg" alt="Championship Greens">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/hillwood-country-club/4.jpeg', 'Strategic Bunkering')">
                    <img src="/images/courses/hillwood-country-club/4.jpeg" alt="Strategic Bunkering">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/hillwood-country-club/5.jpeg', 'Creek Crossings')">
                    <img src="/images/courses/hillwood-country-club/5.jpeg" alt="Creek Crossings">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/hillwood-country-club/6.jpeg', 'Nashville Elite Golf')">
                    <img src="/images/courses/hillwood-country-club/6.jpeg" alt="Nashville Elite Golf">
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
            <h2>Club Reviews</h2>
            
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience at Hillwood Country Club..."></textarea>
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