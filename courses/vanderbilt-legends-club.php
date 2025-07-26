<?php
session_start();
require_once '../config/database.php';

$course_slug = 'vanderbilt-legends-club';
$course_name = 'Vanderbilt Legends Club';

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
    <title>Vanderbilt Legends Club - Premier University Golf Facility | Tennessee Golf Courses</title>
    <meta name="description" content="Vanderbilt Legends Club - 36-hole championship facility in Franklin, TN. Tom Kite & Bob Cupp design, home to Vanderbilt golf teams and Korn Ferry Tour events.">
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
                    <h1 class="course-title">Vanderbilt Legends Club</h1>
                    <p class="course-subtitle">Premier 36-Hole University Golf Facility</p>
                    
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
                                <span>36 Holes</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Two Par 72s</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-ruler"></i>
                                <span>7,190 Yards (North)</span>
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
                        <img src="/images/courses/vanderbilt-legends-club/1.jpeg" alt="Vanderbilt Legends Club" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">University</span>
                                <span class="price-amount">Excellence</span>
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
                        <h2>About Vanderbilt Legends Club</h2>
                        <p>Vanderbilt Legends Club stands as Middle Tennessee's premier golf facility, featuring 36 holes of championship golf designed by World Golf Hall of Famer Tom Kite and renowned architect Bob Cupp. Since opening in 1992, this exceptional facility has served as the home course for Vanderbilt University's prestigious Men's and Women's golf teams while hosting numerous marquee events including LPGA tournaments and NCAA Championships.</p>
                        
                        <p>Located just 20 minutes south of downtown Nashville and 30 minutes from Nashville International Airport, the club offers two distinct 18-hole courses. The North Course, stretching 7,190 yards from the championship tees, represents the pinnacle of strategic design and has been recognized as one of the Southeast's most challenging layouts. The facility's commitment to excellence extends beyond golf, with the completion of a massive Golf House renovation in 2024 through the Vandy United fundraising campaign.</p>
                        
                        <p>As the official home of Vanderbilt athletics and host venue for the Korn Ferry Tour's Simmons Bank Open for the Snedeker Foundation, Vanderbilt Legends Club represents the perfect marriage of collegiate tradition and professional tournament golf, making it a true destination for serious golfers.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Club History & Championships</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1992</div>
                                <div class="timeline-content">
                                    <h4>Grand Opening</h4>
                                    <p>Vanderbilt Legends Club opens with Tom Kite & Bob Cupp design</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1993-96</div>
                                <div class="timeline-content">
                                    <h4>Tennessee State Open</h4>
                                    <p>Hosts prestigious state championship for four consecutive years</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2000-06</div>
                                <div class="timeline-content">
                                    <h4>LPGA Tour Events</h4>
                                    <p>Franklin American Mortgage Championship hosted by Vince Gill & Amy Grant</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2012</div>
                                <div class="timeline-content">
                                    <h4>NCAA Women's Championship</h4>
                                    <p>Hosts NCAA Division I Women's Golf Championship</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2024</div>
                                <div class="timeline-content">
                                    <h4>Korn Ferry Tour Partnership</h4>
                                    <p>Five-year agreement to host Simmons Bank Open for Snedeker Foundation</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2024</div>
                                <div class="timeline-content">
                                    <h4>Golf House Renovation</h4>
                                    <p>Massive clubhouse expansion completed through Vandy United campaign</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Course Design & Architecture</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h4>Championship Design</h4>
                                <p>Tom Kite & Bob Cupp collaboration creating tournament-caliber conditions</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h4>University Home</h4>
                                <p>Official home course for Vanderbilt Men's and Women's golf teams</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-golf-ball"></i>
                                </div>
                                <h4>Practice Facility</h4>
                                <p>19-acre state-of-the-art practice facility with double tees</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <h4>Tournament Venue</h4>
                                <p>Host to professional tours, NCAA events, and Mason Rudolph Championship</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Vanderbilt University Connection</h3>
                        <p>Vanderbilt Legends Club serves as more than just a golf facility—it's an integral part of the Vanderbilt University experience. The club offers special rates to all Vanderbilt faculty, staff, students, and alumni, strengthening the bond between the university community and this world-class facility.</p>
                        
                        <p>The club's association with Vanderbilt University brings a level of academic excellence and tradition that permeates every aspect of the operation. From hosting the annual Mason Rudolph Championship to serving as the training ground for SEC-competing golf teams, Vanderbilt Legends Club embodies the university's commitment to excellence in all endeavors.</p>
                        
                        <p>The 2024 Golf House renovation, funded through the Vandy United campaign, demonstrates the ongoing commitment to maintaining this facility as a crown jewel of both Vanderbilt University and Middle Tennessee golf.</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-card">
                        <h3>Facility Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Architects:</strong>
                                <span>Tom Kite & Bob Cupp</span>
                            </div>
                            <div class="info-item">
                                <strong>Opened:</strong>
                                <span>1992</span>
                            </div>
                            <div class="info-item">
                                <strong>Type:</strong>
                                <span>Private Club</span>
                            </div>
                            <div class="info-item">
                                <strong>Total Holes:</strong>
                                <span>36 (North & South Courses)</span>
                            </div>
                            <div class="info-item">
                                <strong>North Course:</strong>
                                <span>Par 72, 7,190 yards</span>
                            </div>
                            <div class="info-item">
                                <strong>South Course:</strong>
                                <span>Par 72, Championship Layout</span>
                            </div>
                            <div class="info-item">
                                <strong>Practice Facility:</strong>
                                <span>19-acre state-of-the-art complex</span>
                            </div>
                            <div class="info-item">
                                <strong>University Teams:</strong>
                                <span>Vanderbilt Men's & Women's Golf</span>
                            </div>
                            <div class="info-item">
                                <strong>Professional Tours:</strong>
                                <span>Korn Ferry Tour Host</span>
                            </div>
                            <div class="info-item">
                                <strong>Recognition:</strong>
                                <span>Middle Tennessee's Premier Facility</span>
                            </div>
                        </div>
                    </div>

                    <div class="amenities-card">
                        <h3>Club Amenities</h3>
                        <div class="amenities-list">
                            <div class="amenity-item">
                                <i class="fas fa-home"></i>
                                <span>Renovated Golf House (2024)</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-store"></i>
                                <span>Professional Pro Shop</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-utensils"></i>
                                <span>Fine Dining Restaurant</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>19-Acre Practice Facility</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-bullseye"></i>
                                <span>Two Putting Greens</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-hand-paper"></i>
                                <span>Short Game Practice Area</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-mountain"></i>
                                <span>Practice Bunkers</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Professional Instruction</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-calendar"></i>
                                <span>Tournament Operations</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-users"></i>
                                <span>Corporate Events</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-university"></i>
                                <span>Vanderbilt Alumni Benefits</span>
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
                                    <span>1500 Legends Club Lane<br>Franklin, TN 37069</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 791-8100</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://www.vanderbiltlegendsclub.com" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-university"></i>
                                <div>
                                    <strong>University:</strong>
                                    <a href="https://vucommodores.com/facilities/vanderbilt-legends-club/" target="_blank">Vanderbilt Athletics</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-plane"></i>
                                <div>
                                    <strong>Distance:</strong>
                                    <span>20 min to Nashville<br>30 min to Airport</span>
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
                <div class="gallery-item" onclick="openModal('/images/courses/vanderbilt-legends-club/1.jpeg', 'Vanderbilt Legends Club Golf House')">
                    <img src="/images/courses/vanderbilt-legends-club/1.jpeg" alt="Vanderbilt Legends Club Golf House">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/vanderbilt-legends-club/2.jpeg', 'North Course Championship Layout')">
                    <img src="/images/courses/vanderbilt-legends-club/2.jpeg" alt="North Course Championship Layout">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/vanderbilt-legends-club/3.jpeg', '19-Acre Practice Facility')">
                    <img src="/images/courses/vanderbilt-legends-club/3.jpeg" alt="19-Acre Practice Facility">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/vanderbilt-legends-club/4.jpeg', 'Vanderbilt Team Training')">
                    <img src="/images/courses/vanderbilt-legends-club/4.jpeg" alt="Vanderbilt Team Training">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/vanderbilt-legends-club/5.jpeg', 'Tournament-Quality Conditions')">
                    <img src="/images/courses/vanderbilt-legends-club/5.jpeg" alt="Tournament-Quality Conditions">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/vanderbilt-legends-club/6.jpeg', 'Tom Kite & Bob Cupp Design')">
                    <img src="/images/courses/vanderbilt-legends-club/6.jpeg" alt="Tom Kite & Bob Cupp Design">
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience at Vanderbilt Legends Club..."></textarea>
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