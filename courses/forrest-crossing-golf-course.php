<?php
session_start();
require_once '../config/database.php';

$course_slug = 'forrest-crossing-golf-course';
$course_name = 'Forrest Crossing Golf Course';

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
    <title>Forrest Crossing Golf Course - Championship Golf in Franklin | Tennessee Golf Courses</title>
    <meta name="description" content="Forrest Crossing Golf Course - Gary Roger Baird championship design in Franklin, TN. 6,968-yard course featuring Harpeth River views and island green signature hole.">
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
                        <span>Franklin, Tennessee</span>
                    </div>
                    <h1 class="course-title">Forrest Crossing Golf Course</h1>
                    <p class="course-subtitle">Championship Golf Along the Harpeth River</p>
                    
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
                                <span>6,968 Yards</span>
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
                        <img src="/images/courses/forrest-crossing-golf-course/1.jpeg" alt="Forrest Crossing Golf Course" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">Championship</span>
                                <span class="price-amount">Course</span>
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
                        <h2>About Forrest Crossing Golf Course</h2>
                        <p>Forrest Crossing Golf Course stands as one of Nashville's premier golf destinations, masterfully designed by Gary Roger Baird and carved from historic farmland in Franklin, Tennessee. This championship 6,968-yard, par 72 layout showcases the natural beauty of Williamson County while providing golfers with a challenging yet fair test of skill.</p>
                        
                        <p>The course's crown jewel is the spectacular par-4 ninth hole, featuring an island green that has become the signature challenge of the layout. This unique hole, along with the acclaimed 16th and 18th holes, has earned recognition from local media as featuring some of the best and most challenging holes in Tennessee.</p>
                        
                        <p>What truly sets Forrest Crossing apart is its intimate relationship with the Harpeth River, which winds alongside 15 of the course's 18 holes. This links-style design provides a tranquil respite from the bustling growth of Williamson County, offering golfers stunning water views and strategic challenges throughout their round.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Course History & Evolution</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1987</div>
                                <div class="timeline-content">
                                    <h4>Course Opening</h4>
                                    <p>Forrest Crossing Golf Course opens as Gary Roger Baird design</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1988</div>
                                <div class="timeline-content">
                                    <h4>Franklin Bridge Era</h4>
                                    <p>Course operates under Franklin Bridge Golf Club name</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1990s</div>
                                <div class="timeline-content">
                                    <h4>Regional Recognition</h4>
                                    <p>Establishes reputation as one of Nashville area's top courses</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2000s</div>
                                <div class="timeline-content">
                                    <h4>Tournament Hosting</h4>
                                    <p>Becomes premier venue for tournaments and special events</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">Present</div>
                                <div class="timeline-content">
                                    <h4>Forrest Crossing Legacy</h4>
                                    <p>Continues operation as premier Franklin golf destination</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Course Design & Features</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-water"></i>
                                </div>
                                <h4>Harpeth River</h4>
                                <p>River features on 15 of 18 holes providing natural beauty and strategic challenges</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-island-tropical"></i>
                                </div>
                                <h4>Island Green</h4>
                                <p>Signature par-4 9th hole with challenging island green</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <h4>Historic Farmland</h4>
                                <p>Built on old farmland creating natural, links-style conditions</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h4>Championship Design</h4>
                                <p>Gary Roger Baird layout with 6,968 yards of championship golf</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Signature Holes</h3>
                        <div class="holes-showcase">
                            <div class="hole-highlight">
                                <h4>9th Hole - "Island Challenge"</h4>
                                <p><strong>Par 4</strong> | The course's most famous hole features a dramatic island green that demands precision and nerve. This signature hole has become synonymous with Forrest Crossing and provides both beauty and challenge in equal measure.</p>
                            </div>
                            <div class="hole-highlight">
                                <h4>16th Hole - "River's Edge"</h4>
                                <p><strong>Championship Hole</strong> | Recognized as one of Tennessee's most challenging holes, this layout demands strategic thinking and precise execution with the Harpeth River in play.</p>
                            </div>
                            <div class="hole-highlight">
                                <h4>18th Hole - "Grand Finale"</h4>
                                <p><strong>Finishing Hole</strong> | A fitting climax to your round, this hole combines strategic elements with scenic beauty to create a memorable conclusion to your Forrest Crossing experience.</p>
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
                                <span>Gary Roger Baird, ASGCA</span>
                            </div>
                            <div class="info-item">
                                <strong>Opened:</strong>
                                <span>1987</span>
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
                                <span>6,968 yards (Championship)</span>
                            </div>
                            <div class="info-item">
                                <strong>Course Rating:</strong>
                                <span>77.8</span>
                            </div>
                            <div class="info-item">
                                <strong>Slope Rating:</strong>
                                <span>135</span>
                            </div>
                            <div class="info-item">
                                <strong>Turf:</strong>
                                <span>Bermuda Grass</span>
                            </div>
                            <div class="info-item">
                                <strong>Signature Feature:</strong>
                                <span>Island Green 9th Hole</span>
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
                                <span>Grill Room & Bar</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>8-Acre Driving Range</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-bullseye"></i>
                                <span>Putting Greens</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-hand-paper"></i>
                                <span>Chipping Green</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-mountain"></i>
                                <span>Practice Sand Bunker</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>20-Station Grass Tee Facility</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-calendar"></i>
                                <span>Tournament Hosting</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-heart"></i>
                                <span>Wedding Venue</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-users"></i>
                                <span>Special Events</span>
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
                                    <span>750 Riverview Drive<br>Franklin, TN 37064</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 794-9400</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://franklinbridgegolf.com" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Hours:</strong>
                                    <span>Dawn to Dusk<br>Year-Round</span>
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
                <div class="gallery-item" onclick="openModal('/images/courses/forrest-crossing-golf-course/1.jpeg', 'Forrest Crossing Golf Course Clubhouse')">
                    <img src="/images/courses/forrest-crossing-golf-course/1.jpeg" alt="Forrest Crossing Golf Course Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/forrest-crossing-golf-course/2.jpeg', 'Signature 9th Hole Island Green')">
                    <img src="/images/courses/forrest-crossing-golf-course/2.jpeg" alt="Signature 9th Hole Island Green">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/forrest-crossing-golf-course/3.jpeg', 'Harpeth River Views')">
                    <img src="/images/courses/forrest-crossing-golf-course/3.jpeg" alt="Harpeth River Views">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/forrest-crossing-golf-course/4.jpeg', 'Championship Tees')">
                    <img src="/images/courses/forrest-crossing-golf-course/4.jpeg" alt="Championship Tees">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/forrest-crossing-golf-course/5.jpeg', '8-Acre Driving Range')">
                    <img src="/images/courses/forrest-crossing-golf-course/5.jpeg" alt="8-Acre Driving Range">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/forrest-crossing-golf-course/6.jpeg', 'Historic Franklin Bridge')">
                    <img src="/images/courses/forrest-crossing-golf-course/6.jpeg" alt="Historic Franklin Bridge">
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience playing Forrest Crossing Golf Course..."></textarea>
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