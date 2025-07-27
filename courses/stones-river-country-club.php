<?php
session_start();
require_once '../config/database.php';

$course_slug = 'stones-river-country-club';
$course_name = 'Stones River Country Club';

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
    <title>Stones River Country Club - Premier Private Club | Tennessee Golf Courses</title>
    <meta name="description" content="Stones River Country Club - Premier private country club in Murfreesboro since 1949. Championship golf, tennis, pool, and dining along the historic Stones River.">
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
                        <span>Murfreesboro, Tennessee</span>
                    </div>
                    <h1 class="course-title">Stones River Country Club</h1>
                    <p class="course-subtitle">Premier Private Club Since 1949</p>
                    
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
                                <span>Par 71</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-ruler"></i>
                                <span>6,880 Yards</span>
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
                        <img src="/images/courses/stones-river-country-club/1.jpeg" alt="Stones River Country Club" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">Since</span>
                                <span class="price-amount">1949</span>
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
                        <h2>About Stones River Country Club</h2>
                        <p>Stones River Country Club stands as Murfreesboro's premier private country club, serving the community with distinction since 1949. Located along the banks of the historic Stones River, this championship 18-hole course has been the centerpiece of social and recreational life for more than 75 years, providing an elegant escape in the heart of Middle Tennessee.</p>
                        
                        <p>The club's 6,880-yard, par-71 championship course features tree-lined fairways that wind gracefully along the meandering Stones River. Designed with strategic bunkering and elevated, undulating greens, the course provides a challenging yet enjoyable experience for golfers of all skill levels while maintaining the natural beauty that makes this location so special.</p>
                        
                        <p>Beyond exceptional golf, Stones River Country Club offers a comprehensive country club experience including tennis courts, swimming facilities, fitness center, and award-winning dining. The club's commitment to family-oriented service and community involvement has made it the premier meeting place in Murfreesboro for multiple generations.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Club History & Heritage</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1947</div>
                                <div class="timeline-content">
                                    <h4>Club Establishment</h4>
                                    <p>Stones River Country Club founded with original 9 holes</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1949</div>
                                <div class="timeline-content">
                                    <h4>Course Opening</h4>
                                    <p>Championship golf course opens for play to members</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1969</div>
                                <div class="timeline-content">
                                    <h4>Course Expansion</h4>
                                    <p>Additional 9 holes added to create full 18-hole layout</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1979</div>
                                <div class="timeline-content">
                                    <h4>Course Completion</h4>
                                    <p>Current championship layout finalized and perfected</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2020s</div>
                                <div class="timeline-content">
                                    <h4>Modern Amenities</h4>
                                    <p>Clubhouse renovations and facility upgrades completed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Championship Golf Course</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-water"></i>
                                </div>
                                <h4>Stones River</h4>
                                <p>Historic river winds through course providing natural beauty and strategic challenge</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-tree"></i>
                                </div>
                                <h4>Tree-Lined Fairways</h4>
                                <p>Mature trees create natural corridors and strategic shot requirements</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-mountain"></i>
                                </div>
                                <h4>Elevated Greens</h4>
                                <p>Mini Verde Bermudagrass greens with challenging undulation</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h4>Championship Design</h4>
                                <p>Strategic layout testing all aspects of the game from multiple tees</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Tournament Heritage</h3>
                        <p>Stones River Country Club has proudly hosted numerous prestigious tournaments throughout its distinguished history. Most notably, the club has been home to the Tennessee PGA Professional Championship, with the 55th edition of this championship recently held on the course. This longstanding relationship with the Tennessee PGA demonstrates the course's championship caliber and the club's commitment to supporting professional golf in the region.</p>
                        
                        <p>The club regularly hosts member tournaments, invitationals, and corporate events, taking advantage of its challenging layout and exceptional facilities. The course's strategic design, featuring tight fairways and elevated greens, provides the perfect venue for competitive golf while maintaining an enjoyable experience for players of all skill levels.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Private Club Excellence</h3>
                        <p>As Murfreesboro's premier private country club, Stones River offers an exclusive membership experience that extends far beyond golf. The club's commitment to family-oriented service is evident in its comprehensive amenities and programming designed to serve multiple generations. From junior golf programs to family pool activities, the club has been a cornerstone of the Murfreesboro community for over seven decades.</p>
                        
                        <p>The newly constructed clubhouse features elegant dining rooms, casual grill areas, and private event spaces, all designed to provide members with exceptional service and amenities. The club's award-winning executive chef creates memorable dining experiences that complement the club's tradition of excellence and hospitality.</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-card">
                        <h3>Course Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Designer:</strong>
                                <span>Bob Renaud</span>
                            </div>
                            <div class="info-item">
                                <strong>Established:</strong>
                                <span>1947</span>
                            </div>
                            <div class="info-item">
                                <strong>Opened:</strong>
                                <span>1949</span>
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
                                <span>71</span>
                            </div>
                            <div class="info-item">
                                <strong>Silver Tees:</strong>
                                <span>6,880 yards (Rating 73.5, Slope 138)</span>
                            </div>
                            <div class="info-item">
                                <strong>White Tees:</strong>
                                <span>6,372 yards (Rating 70.7, Slope 132)</span>
                            </div>
                            <div class="info-item">
                                <strong>Forward Tees:</strong>
                                <span>5,122 yards (Rating 65.2, Slope 111)</span>
                            </div>
                            <div class="info-item">
                                <strong>Greens:</strong>
                                <span>Mini Verde Bermudagrass</span>
                            </div>
                        </div>
                    </div>

                    <div class="amenities-card">
                        <h3>Club Amenities</h3>
                        <div class="amenities-list">
                            <div class="amenity-item">
                                <i class="fas fa-home"></i>
                                <span>Newly Constructed Clubhouse</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-utensils"></i>
                                <span>Award-Winning Executive Chef</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-wine-glass"></i>
                                <span>Family Dining & Sports Bar</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-store"></i>
                                <span>Professional Golf Shop</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Practice Range</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>PGA Professional Instruction</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-table-tennis"></i>
                                <span>Six Tennis/Pickleball Courts</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-swimmer"></i>
                                <span>Swimming Pool & Diving</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-dumbbell"></i>
                                <span>24-Hour Fitness Center</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-shower"></i>
                                <span>Locker Rooms with Amenities</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-calendar"></i>
                                <span>Private Event Hosting</span>
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
                                    <span>1830 NW Broad Street<br>Murfreesboro, TN 37129</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 896-4431</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://www.stonesrivercountryclub.com" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-user-tie"></i>
                                <div>
                                    <strong>Membership Director:</strong>
                                    <span>Caroline Shea<br>cshea@stonesrivercc.org</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-road"></i>
                                <div>
                                    <strong>Distance:</strong>
                                    <span>30-35 minutes from Nashville</span>
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
                <div class="gallery-item" onclick="openModal('/images/courses/stones-river-country-club/1.jpeg', 'Stones River Country Club Clubhouse')">
                    <img src="/images/courses/stones-river-country-club/1.jpeg" alt="Stones River Country Club Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/stones-river-country-club/2.jpeg', 'Championship Golf Course')">
                    <img src="/images/courses/stones-river-country-club/2.jpeg" alt="Championship Golf Course">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/stones-river-country-club/3.jpeg', 'Historic Stones River Views')">
                    <img src="/images/courses/stones-river-country-club/3.jpeg" alt="Historic Stones River Views">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/stones-river-country-club/4.jpeg', 'Tree-Lined Fairways')">
                    <img src="/images/courses/stones-river-country-club/4.jpeg" alt="Tree-Lined Fairways">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/stones-river-country-club/5.jpeg', 'Practice Facilities')">
                    <img src="/images/courses/stones-river-country-club/5.jpeg" alt="Practice Facilities">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/stones-river-country-club/6.jpeg', 'Tennis and Recreation')">
                    <img src="/images/courses/stones-river-country-club/6.jpeg" alt="Tennis and Recreation">
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience at Stones River Country Club..."></textarea>
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