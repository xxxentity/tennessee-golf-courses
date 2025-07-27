<?php
session_start();
require_once '../config/database.php';

$course_slug = 'cedar-crest-golf-club';
$course_name = 'Cedar Crest Golf Club';

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
    <title>Cedar Crest Golf Club - Quality Public Golf in Murfreesboro | Tennessee Golf Courses</title>
    <meta name="description" content="Cedar Crest Golf Club - John Floyd designed public course in Murfreesboro since 1999. 6,828 yards of challenging golf on rolling farmland with bentgrass greens.">
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
                    <h1 class="course-title">Cedar Crest Golf Club</h1>
                    <p class="course-subtitle">Quality Public Golf Since 1999</p>
                    
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
                                <span>6,828 Yards</span>
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
                        <img src="/images/courses/cedar-crest-golf-club/1.jpeg" alt="Cedar Crest Golf Club" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">Most Improved</span>
                                <span class="price-amount">2016 Award</span>
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
                        <h2>About Cedar Crest Golf Club</h2>
                        <p>Cedar Crest Golf Club represents the finest in public golf accessibility and quality, serving the Murfreesboro community since 1999. Designed by John Floyd and built on 175 acres of rolling former farmland, this championship course offers golfers an exceptional experience that combines challenging play with stunning natural beauty.</p>
                        
                        <p>The course gained national recognition in 2016 when Golf Advisor named Cedar Crest #18 on their Top 25 Most Improved U.S. Golf Courses, acknowledging the continuous improvements and commitment to excellence that define this facility. The 6,828-yard layout features pristine bentgrass greens and tree-lined fairways that showcase the natural Tennessee countryside.</p>
                        
                        <p>What sets Cedar Crest apart is its player-friendly design philosophy combined with strategic challenges. The front nine offers generous fairways with wide landing areas, while the back nine narrows down with tree-lined corridors that demand more precision. This thoughtful progression makes the course enjoyable for beginners while providing ample challenge for experienced golfers.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Course Development & Recognition</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1999</div>
                                <div class="timeline-content">
                                    <h4>Course Opening</h4>
                                    <p>Cedar Crest Golf Club opens as John Floyd design on former farmland</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2012</div>
                                <div class="timeline-content">
                                    <h4>BBB Accreditation</h4>
                                    <p>Achieves Better Business Bureau accreditation for service excellence</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2016</div>
                                <div class="timeline-content">
                                    <h4>National Recognition</h4>
                                    <p>Golf Advisor ranks Cedar Crest #18 Top 25 Most Improved U.S. Golf Courses</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">Present</div>
                                <div class="timeline-content">
                                    <h4>Continued Excellence</h4>
                                    <p>Maintains 3.9/5 star rating with 85.1% golfer recommendation rate</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>John Floyd Design Philosophy</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <h4>Natural Integration</h4>
                                <p>Course built to work with existing farmland topography and mature trees</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h4>Progressive Challenge</h4>
                                <p>Front nine offers forgiveness, back nine demands precision and strategy</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <h4>Strategic Bunkering</h4>
                                <p>Minimal but well-placed sand traps emphasize course management</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <h4>Bentgrass Greens</h4>
                                <p>Fast, true putting surfaces that reward precise approach shots</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Signature Holes & Layout</h3>
                        <div class="holes-showcase">
                            <div class="hole-highlight">
                                <h4>18th Hole - "Championship Finish"</h4>
                                <p><strong>Par 5, 580 yards</strong> | The course's longest and most demanding hole provides a dramatic finish. This challenging par-5 requires strategic positioning and precise execution to navigate successfully.</p>
                            </div>
                            <div class="hole-highlight">
                                <h4>9th Hole - "The Test"</h4>
                                <p><strong>Par 4, 445 yards (#1 Handicap)</strong> | The course's most challenging hole demands both distance and accuracy. Strategic bunkering and tree placement make this a true test of championship golf.</p>
                            </div>
                            <div class="hole-highlight">
                                <h4>3rd Hole - "Short Game Precision"</h4>
                                <p><strong>Par 3, 150 yards</strong> | The shortest hole on the course emphasizes accuracy and green-reading skills, featuring one of the course's most challenging putting surfaces.</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Course Recognition & Reviews</h3>
                        <p>Cedar Crest Golf Club has earned recognition both nationally and locally for its commitment to providing quality public golf. The course maintains an impressive 3.9 out of 5-star rating based on over 500 golfer reviews, with 85.1% of players recommending the course to others.</p>
                        
                        <p>Golfers consistently praise the course for its excellent layout design (4.3/5 stars) and exceptional friendliness of staff (4.2/5 stars). The course's player-friendly design makes it accessible to golfers of all skill levels, while the strategic elements and course conditions provide ample challenge for more experienced players.</p>
                        
                        <p>As one of Murfreesboro's premier public golf facilities, Cedar Crest stands out for its combination of scenic beauty, strategic design, and value proposition, making it a must-play destination for golfers throughout Middle Tennessee.</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-card">
                        <h3>Course Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Architect:</strong>
                                <span>John Floyd</span>
                            </div>
                            <div class="info-item">
                                <strong>Opened:</strong>
                                <span>1999</span>
                            </div>
                            <div class="info-item">
                                <strong>Type:</strong>
                                <span>Public</span>
                            </div>
                            <div class="info-item">
                                <strong>Total Acreage:</strong>
                                <span>175 acres</span>
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
                                <strong>Blue Tees:</strong>
                                <span>6,828 yards (Rating 72.7, Slope 131)</span>
                            </div>
                            <div class="info-item">
                                <strong>White Tees:</strong>
                                <span>6,407 yards (Rating 70.5, Slope 129)</span>
                            </div>
                            <div class="info-item">
                                <strong>Gold Tees:</strong>
                                <span>5,869 yards (Rating 68.1, Slope 121)</span>
                            </div>
                            <div class="info-item">
                                <strong>Ladies Tees:</strong>
                                <span>5,317 yards (Rating 66.4, Slope 118)</span>
                            </div>
                        </div>
                    </div>

                    <div class="amenities-card">
                        <h3>Amenities & Services</h3>
                        <div class="amenities-list">
                            <div class="amenity-item">
                                <i class="fas fa-home"></i>
                                <span>Log Cabin Clubhouse</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-utensils"></i>
                                <span>Restaurant & Bar</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-store"></i>
                                <span>Pro Shop & Equipment</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Full Driving Range</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-bullseye"></i>
                                <span>Two-Tiered Putting Green</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-hand-paper"></i>
                                <span>Chipping & Short Game Area</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Professional Instruction</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-car"></i>
                                <span>Golf Cart Rental</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-calendar"></i>
                                <span>Tournament Hosting</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-users"></i>
                                <span>Banquet Facilities</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-laptop"></i>
                                <span>Online Tee Times</span>
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
                                    <span>7972 Mona Road<br>Murfreesboro, TN 37129</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 849-7837</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://www.cedarcrestgolfclub.com" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong>Email:</strong>
                                    <span>info@cedarcrestgolfclub.com</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-dollar-sign"></i>
                                <div>
                                    <strong>Green Fees:</strong>
                                    <span>Weekday $28, Weekend $32</span>
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
                <div class="gallery-item" onclick="openModal('/images/courses/cedar-crest-golf-club/1.jpeg', 'Cedar Crest Golf Club Clubhouse')">
                    <img src="/images/courses/cedar-crest-golf-club/1.jpeg" alt="Cedar Crest Golf Club Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/cedar-crest-golf-club/2.jpeg', 'John Floyd Championship Design')">
                    <img src="/images/courses/cedar-crest-golf-club/2.jpeg" alt="John Floyd Championship Design">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/cedar-crest-golf-club/3.jpeg', 'Rolling Farmland Layout')">
                    <img src="/images/courses/cedar-crest-golf-club/3.jpeg" alt="Rolling Farmland Layout">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/cedar-crest-golf-club/4.jpeg', 'Bentgrass Greens')">
                    <img src="/images/courses/cedar-crest-golf-club/4.jpeg" alt="Bentgrass Greens">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/cedar-crest-golf-club/5.jpeg', 'Practice Facilities')">
                    <img src="/images/courses/cedar-crest-golf-club/5.jpeg" alt="Practice Facilities">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/cedar-crest-golf-club/6.jpeg', 'Tree-Lined Back Nine')">
                    <img src="/images/courses/cedar-crest-golf-club/6.jpeg" alt="Tree-Lined Back Nine">
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience playing Cedar Crest Golf Club..."></textarea>
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