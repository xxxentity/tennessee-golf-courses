<?php
session_start();
require_once '../config/database.php';

$course_slug = 'nashville-national-golf-links';
$course_name = 'Nashville National Golf Links';

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
    <title>Nashville National Golf Links - Natural Beauty Golf | Tennessee Golf Courses</title>
    <meta name="description" content="Nashville National Golf Links - 18-hole championship course in Joelton featuring limestone bluffs, Sycamore Creek, and natural Tennessee beauty. Formerly Highland Rim Golf Course.">
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
                        <span>Joelton, Tennessee</span>
                    </div>
                    <h1 class="course-title">Nashville National Golf Links</h1>
                    <p class="course-subtitle">Golf in Natural Tennessee Beauty</p>
                    
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
                                <span>6,217 Yards</span>
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
                        <img src="/images/courses/nashville-national-golf-links/1.jpeg" alt="Nashville National Golf Links" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">Experience</span>
                                <span class="price-amount">Natural Beauty</span>
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
                        <h2>About Nashville National Golf Links</h2>
                        <p>Nashville National Golf Links, formerly Highland Rim Golf Course, stands as a testament to golf played in its most natural setting. Established in 1999 and now under family ownership, this 18-hole championship course is carved among limestone bluffs with scenic elevation changes and the soothing sounds of Sycamore Creek flowing throughout the property.</p>
                        
                        <p>Located just minutes from downtown Nashville at 1725 New Hope Road in Joelton, the course offers a unique promise: not a single home fronts the property, and never will. This commitment to preserving the natural landscape ensures that golfers experience the game as it was intended - away from the stresses of daily life in a pristine Tennessee setting.</p>
                        
                        <p>The course stretches 6,217 yards through diverse terrain featuring rolling hills, mature trees, and strategic water features. Under new ownership, Nashville National has undergone continuous upgrades to both the course conditions and amenities, creating a welcoming atmosphere where golfers feel truly at home.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Course Evolution & Heritage</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1999</div>
                                <div class="timeline-content">
                                    <h4>Highland Rim Golf Course Opens</h4>
                                    <p>Original course established in the natural Tennessee landscape</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2010s</div>
                                <div class="timeline-content">
                                    <h4>Course Maturation</h4>
                                    <p>Trees and landscaping develop, creating challenging conditions</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2020</div>
                                <div class="timeline-content">
                                    <h4>Ownership Change</h4>
                                    <p>New family ownership takes over operations</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2021</div>
                                <div class="timeline-content">
                                    <h4>Rebranding to Nashville National</h4>
                                    <p>Course renamed to reflect commitment to natural golf experience</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2022-2025</div>
                                <div class="timeline-content">
                                    <h4>Continuous Improvements</h4>
                                    <p>Ongoing course and facility upgrades under family ownership</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Natural Features & Layout</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-mountain"></i>
                                </div>
                                <h4>Limestone Bluffs</h4>
                                <p>Dramatic rock formations create stunning backdrops and strategic challenges</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-water"></i>
                                </div>
                                <h4>Sycamore Creek</h4>
                                <p>Natural creek winds through the course, providing both beauty and hazard</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h4>Elevation Changes</h4>
                                <p>Rolling terrain offers varied shot requirements and scenic vistas</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-tree"></i>
                                </div>
                                <h4>Mature Landscape</h4>
                                <p>No residential development - pure natural Tennessee environment</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Family Ownership Philosophy</h3>
                        <p>Nashville National Golf Links is proudly family owned and operated, with a philosophy centered on providing golfers with an exceptional experience. The ownership team is constantly upgrading both the course conditions and amenities, ensuring that every visitor feels welcomed and valued.</p>
                        
                        <p>This commitment to hospitality, combined with the course's pristine natural setting, creates an atmosphere where golfers can truly escape and enjoy the game in its purest form. The family's dedication to maintaining the property's natural integrity while providing modern amenities sets Nashville National apart from other golf experiences in the Nashville area.</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-card">
                        <h3>Course Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Established:</strong>
                                <span>1999 (as Highland Rim)</span>
                            </div>
                            <div class="info-item">
                                <strong>Rebranded:</strong>
                                <span>2021 (Nashville National)</span>
                            </div>
                            <div class="info-item">
                                <strong>Type:</strong>
                                <span>Semi-Private</span>
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
                                <span>6,217 yards</span>
                            </div>
                            <div class="info-item">
                                <strong>Terrain:</strong>
                                <span>Rolling Hills</span>
                            </div>
                            <div class="info-item">
                                <strong>Water Features:</strong>
                                <span>Sycamore Creek</span>
                            </div>
                            <div class="info-item">
                                <strong>Unique Feature:</strong>
                                <span>No residential development</span>
                            </div>
                            <div class="info-item">
                                <strong>Setting:</strong>
                                <span>Limestone bluffs & natural beauty</span>
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
                                <span>Clubhouse Restaurant</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-car"></i>
                                <span>Golf Cart Rental</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Covered Driving Range</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-bullseye"></i>
                                <span>Practice Putting Green</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-users"></i>
                                <span>Group Events</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Golf Instruction</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-calendar"></i>
                                <span>Tournament Hosting</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-parking"></i>
                                <span>Ample Parking</span>
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
                                    <span>1725 New Hope Road<br>Nashville, TN 37080</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 876-4653</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://nashvillenational.golf" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Hours:</strong>
                                    <span>Dawn to Dusk<br>Weather Permitting</span>
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
                <div class="gallery-item" onclick="openModal('/images/courses/nashville-national-golf-links/1.jpeg', 'Nashville National Golf Links Clubhouse')">
                    <img src="/images/courses/nashville-national-golf-links/1.jpeg" alt="Nashville National Golf Links Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/nashville-national-golf-links/2.jpeg', 'Limestone Bluffs and Natural Beauty')">
                    <img src="/images/courses/nashville-national-golf-links/2.jpeg" alt="Limestone Bluffs and Natural Beauty">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/nashville-national-golf-links/3.jpeg', 'Sycamore Creek Water Feature')">
                    <img src="/images/courses/nashville-national-golf-links/3.jpeg" alt="Sycamore Creek Water Feature">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/nashville-national-golf-links/4.jpeg', 'Scenic Elevation Changes')">
                    <img src="/images/courses/nashville-national-golf-links/4.jpeg" alt="Scenic Elevation Changes">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/nashville-national-golf-links/5.jpeg', 'Covered Driving Range')">
                    <img src="/images/courses/nashville-national-golf-links/5.jpeg" alt="Covered Driving Range">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/nashville-national-golf-links/6.jpeg', 'Natural Tennessee Landscape')">
                    <img src="/images/courses/nashville-national-golf-links/6.jpeg" alt="Natural Tennessee Landscape">
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience playing Nashville National Golf Links..."></textarea>
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