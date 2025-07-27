<?php
session_start();
require_once '../config/database.php';

$course_slug = 'the-legacy-golf-course';
$course_name = 'The Legacy Golf Course';

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
    <title>The Legacy Golf Course - Raymond Floyd Design | Tennessee Golf Courses</title>
    <meta name="description" content="The Legacy Golf Course - Raymond Floyd designed championship course in Springfield, TN. Ranked #2 public course in Tennessee with 6,755 yards of challenging golf.">
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
                        <span>Springfield, Tennessee</span>
                    </div>
                    <h1 class="course-title">The Legacy Golf Course</h1>
                    <p class="course-subtitle">Raymond Floyd Championship Design</p>
                    
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
                                <span>6,755 Yards</span>
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
                        <img src="/images/courses/the-legacy-golf-course/1.jpeg" alt="The Legacy Golf Course" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">#2 Ranked</span>
                                <span class="price-amount">Public Course TN</span>
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
                        <h2>About The Legacy Golf Course</h2>
                        <p>The Legacy Golf Course stands as one of Tennessee's premier public golf destinations, masterfully designed by PGA Hall of Famer Raymond Floyd and opened in 1996. Located just 30 minutes north of Nashville in Springfield, this 6,755-yard championship course has earned recognition as the #2 ranked public course in Tennessee by Golf Advisor.</p>
                        
                        <p>Floyd's design philosophy emphasized "target golf at its best," creating a layout that rewards accuracy over raw distance. The course winds through rolling hills, woodlands, and scenic Tennessee countryside, featuring bentgrass greens with significant undulation and strategic bunkering that challenges golfers of all skill levels.</p>
                        
                        <p>Beyond its championship golf, The Legacy is certified as an Audubon Cooperative Sanctuary, demonstrating its commitment to environmental stewardship while providing wildlife habitats and conserving natural resources. This combination of exceptional golf and environmental consciousness makes The Legacy a true destination for discerning golfers.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Raymond Floyd Design Legacy</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1996</div>
                                <div class="timeline-content">
                                    <h4>Course Opening</h4>
                                    <p>The Legacy Golf Course opens as Raymond Floyd's Tennessee design</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2000s</div>
                                <div class="timeline-content">
                                    <h4>Recognition & Awards</h4>
                                    <p>Earns ranking as one of Tennessee's top public courses</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2010s</div>
                                <div class="timeline-content">
                                    <h4>Audubon Certification</h4>
                                    <p>Achieves Cooperative Sanctuary status for environmental stewardship</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2024</div>
                                <div class="timeline-content">
                                    <h4>Current Recognition</h4>
                                    <p>Ranked #6 in Golf Advisor's Top Courses in Tennessee</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Course Design & Challenge</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <h4>Target Golf</h4>
                                <p>Raymond Floyd's "target golf at its best" philosophy emphasizing precision</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h4>Elevation Changes</h4>
                                <p>Rolling hills and blind shots create strategic challenges throughout</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <h4>Natural Setting</h4>
                                <p>Integrated with Tennessee countryside and mature woodlands</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <h4>Bentgrass Greens</h4>
                                <p>Fast, undulating putting surfaces with strategic pin positions</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Raymond Floyd: Hall of Fame Designer</h3>
                        <p>Raymond Floyd's credentials as both player and designer are unmatched in modern golf. As a four-time major champion and World Golf Hall of Fame inductee (1989), Floyd brought decades of competitive experience to The Legacy's design. His unique perspective, gained from being one of only two players to win official PGA Tour events in four different decades, is evident in every strategic element of the course.</p>
                        
                        <p>Floyd's design portfolio includes prestigious courses like the "Blue Monster" at Doral, TPC Las Vegas, and Turnberry Isle. At The Legacy, he created a course where no single hole dominates, instead providing consistent challenge and beauty throughout the entire round. His philosophy of accuracy over distance makes the course accessible to amateur golfers while maintaining championship-level challenge from the back tees.</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-card">
                        <h3>Course Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Architect:</strong>
                                <span>Raymond Floyd</span>
                            </div>
                            <div class="info-item">
                                <strong>Opened:</strong>
                                <span>1996</span>
                            </div>
                            <div class="info-item">
                                <strong>Type:</strong>
                                <span>Public</span>
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
                                <strong>Floyd Tees:</strong>
                                <span>6,755 yards (Rating 73.0, Slope 134)</span>
                            </div>
                            <div class="info-item">
                                <strong>Tournament Tees:</strong>
                                <span>6,133 yards (Rating 69.8, Slope 128)</span>
                            </div>
                            <div class="info-item">
                                <strong>Forward Tees:</strong>
                                <span>4,860 yards (Rating 68.2, Slope 118)</span>
                            </div>
                            <div class="info-item">
                                <strong>Green Type:</strong>
                                <span>Bentgrass</span>
                            </div>
                            <div class="info-item">
                                <strong>Certification:</strong>
                                <span>Audubon Cooperative Sanctuary</span>
                            </div>
                        </div>
                    </div>

                    <div class="amenities-card">
                        <h3>Amenities & Services</h3>
                        <div class="amenities-list">
                            <div class="amenity-item">
                                <i class="fas fa-home"></i>
                                <span>Full Clubhouse with Course Views</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-store"></i>
                                <span>Fully Stocked Pro Shop</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-utensils"></i>
                                <span>The Legacy Grill Restaurant</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Full Driving Range</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-bullseye"></i>
                                <span>Four-Level Putting Green</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-hand-paper"></i>
                                <span>Short Game Complex</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-mountain"></i>
                                <span>Practice Bunker</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>PGA Professional Instruction</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-tools"></i>
                                <span>Club Repair & Custom Fitting</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-calendar"></i>
                                <span>Tournament Hosting</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-credit-card"></i>
                                <span>USGA Handicap Services</span>
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
                                    <span>100 Ray Floyd Drive<br>Springfield, TN 37172</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 384-4653</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://www.golfthelegacy.com" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-road"></i>
                                <div>
                                    <strong>Distance:</strong>
                                    <span>30 minutes from Nashville</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Practice Hours:</strong>
                                    <span>Sunrise to 30 minutes before sunset</span>
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
                <div class="gallery-item" onclick="openModal('/images/courses/the-legacy-golf-course/1.jpeg', 'The Legacy Golf Course Clubhouse')">
                    <img src="/images/courses/the-legacy-golf-course/1.jpeg" alt="The Legacy Golf Course Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-legacy-golf-course/2.jpeg', 'Raymond Floyd Championship Design')">
                    <img src="/images/courses/the-legacy-golf-course/2.jpeg" alt="Raymond Floyd Championship Design">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-legacy-golf-course/3.jpeg', 'Scenic Tennessee Countryside')">
                    <img src="/images/courses/the-legacy-golf-course/3.jpeg" alt="Scenic Tennessee Countryside">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-legacy-golf-course/4.jpeg', 'Four-Level Putting Green')">
                    <img src="/images/courses/the-legacy-golf-course/4.jpeg" alt="Four-Level Putting Green">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-legacy-golf-course/5.jpeg', 'Target Golf Challenge')">
                    <img src="/images/courses/the-legacy-golf-course/5.jpeg" alt="Target Golf Challenge">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-legacy-golf-course/6.jpeg', 'Practice Range Complex')">
                    <img src="/images/courses/the-legacy-golf-course/6.jpeg" alt="Practice Range Complex">
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience playing The Legacy Golf Course..."></textarea>
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