<?php
session_start();
require_once '../config/database.php';

$course_slug = 'richland-country-club';
$course_name = 'Richland Country Club';

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
    <title>Richland Country Club - Historic Jack Nicklaus Design | Tennessee Golf Courses</title>
    <meta name="description" content="Richland Country Club - Historic Nashville club since 1901 with Jack Nicklaus Signature design. Byron Nelson and Ben Hogan tournament victories. Bill Bergin renovation 2021.">
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
                    <h1 class="course-title">Richland Country Club</h1>
                    <p class="course-subtitle">Historic Club Since 1901 - Jack Nicklaus Design</p>
                    
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
                        <img src="/images/courses/richland-country-club/1.jpeg" alt="Richland Country Club" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">Since</span>
                                <span class="price-amount">1901</span>
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
                        <h2>About Richland Country Club</h2>
                        <p>Richland Country Club stands as Nashville's most storied private golf institution, combining 122 years of rich tradition with championship-caliber golf. Founded in 1901 as the Nashville Golf Club, Richland has evolved from its original Donald Ross design heritage to its current Jack Nicklaus Signature layout, enhanced by Bill Bergin's acclaimed 2021 renovation.</p>
                        
                        <p>The club's tournament legacy is unmatched, having hosted historic victories by golf legends Byron Nelson (1944), Ben Hogan (1945), and Johnny Palmer (1946) at the Nashville PGA Invitational Tournament. These champions are honored today in dedicated meeting rooms within the club's 33,000 square foot clubhouse, commemorating Richland's place in professional golf history.</p>
                        
                        <p>Located on historically significant Civil War battlefield land in Nashville's prestigious south side, Richland's current Jack Nicklaus design seamlessly integrates Tennessee's rolling hill country with strategic golf architecture. The 2021 Bill Bergin renovation earned recognition as Golf Inc Magazine's Runner-up Best Renovation and Golf Digest's 3rd Best Transformation, cementing Richland's status as a top-five Tennessee golf course.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Historic Timeline & Evolution</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1901</div>
                                <div class="timeline-content">
                                    <h4>Nashville Golf Club Founded</h4>
                                    <p>Club chartered as one of Tennessee's oldest golf institutions</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1921</div>
                                <div class="timeline-content">
                                    <h4>Donald Ross Design Opens</h4>
                                    <p>Legendary architect Donald Ross designs original 18-hole course</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1944-1946</div>
                                <div class="timeline-content">
                                    <h4>Golf Legends Era</h4>
                                    <p>Byron Nelson (1944), Ben Hogan (1945), Johnny Palmer (1946) win tournaments</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1988</div>
                                <div class="timeline-content">
                                    <h4>Jack Nicklaus Signature Course</h4>
                                    <p>Club relocates to current site with new Nicklaus design</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2019</div>
                                <div class="timeline-content">
                                    <h4>Clubhouse Renovation</h4>
                                    <p>Extensive remodel of 33,000 square foot clubhouse completed</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2021</div>
                                <div class="timeline-content">
                                    <h4>Bill Bergin Transformation</h4>
                                    <p>Award-winning renovation earns national recognition</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Jack Nicklaus Design Legacy</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h4>Signature Design</h4>
                                <p>True Jack Nicklaus Signature course crafted into Tennessee hill country</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-mountain"></i>
                                </div>
                                <h4>Strategic Terrain</h4>
                                <p>Elevation changes and natural features create challenging shot requirements</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <h4>Multiple Tees</h4>
                                <p>Five sets of tees accommodate golfers of all skill levels</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-award"></i>
                                </div>
                                <h4>Award-Winning Renovation</h4>
                                <p>2021 Bill Bergin renovation honored as top transformation nationally</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Tournament Heritage & Golf Legends</h3>
                        <p>Richland Country Club's tournament pedigree from the 1940s remains unmatched in Tennessee golf history. The club hosted three consecutive Nashville PGA Invitational Tournaments that featured some of golf's greatest champions during their prime years.</p>
                        
                        <p><strong>Byron Nelson's 1944 victory</strong> came during his legendary year when he won 13 tournaments. <strong>Ben Hogan's 1945 triumph</strong> marked his first post-World War II PGA tournament victory, beginning his return to dominance. <strong>Johnny Palmer's 1946 win</strong> continued the tradition of champions competing at Richland.</p>
                        
                        <p>Today, these legends are honored in the club's Byron Nelson, Ben Hogan, and Johnny Palmer meeting rooms, adjacent to the main Richland Room. This permanent tribute reflects the club's commitment to preserving its unique place in professional golf history and its role in hosting champions during golf's golden era.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Civil War Heritage & Historic Setting</h3>
                        <p>Richland Country Club's current location sits on part of the core battlefield from the 1864 Battle of Nashville, adding profound historical significance to the golf experience. The course's routing and design acknowledge this heritage, with tee markers crafted as replicas of Civil War bullets discovered on the property.</p>
                        
                        <p>Artifacts including silverware and bullet fragments have been uncovered during course maintenance, connecting members to the area's Civil War past. The preservation of historic elements including stacked stone walls and a "witness" tree demonstrates the club's commitment to honoring both its golf heritage and the land's deeper American history.</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-card">
                        <h3>Club Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Founded:</strong>
                                <span>1901</span>
                            </div>
                            <div class="info-item">
                                <strong>Current Designer:</strong>
                                <span>Jack Nicklaus Signature</span>
                            </div>
                            <div class="info-item">
                                <strong>Recent Renovation:</strong>
                                <span>Bill Bergin (2021)</span>
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
                                <span>6,968 yards</span>
                            </div>
                            <div class="info-item">
                                <strong>Course Rating:</strong>
                                <span>71.5-73.7</span>
                            </div>
                            <div class="info-item">
                                <strong>Slope Rating:</strong>
                                <span>139</span>
                            </div>
                            <div class="info-item">
                                <strong>Grass Type:</strong>
                                <span>Zoysia fairways, Bent greens</span>
                            </div>
                        </div>
                    </div>

                    <div class="amenities-card">
                        <h3>Club Amenities</h3>
                        <div class="amenities-list">
                            <div class="amenity-item">
                                <i class="fas fa-home"></i>
                                <span>33,000 sq ft Clubhouse (2019)</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-utensils"></i>
                                <span>Waxo Fine Dining Restaurant</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-swimmer"></i>
                                <span>Resort-Style Pool Complex</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-dumbbell"></i>
                                <span>State-of-the-Art Fitness Center</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-table-tennis"></i>
                                <span>Tennis Center (12 Courts)</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-store"></i>
                                <span>Professional Golf Shop</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Practice Facilities</span>
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
                                <span>Champions Meeting Rooms</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-child"></i>
                                <span>Family Programs & Childcare</span>
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
                                    <span>1 Club Drive<br>Nashville, TN 37215</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 370-0030</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://www.richlandcc.com" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-crown"></i>
                                <div>
                                    <strong>Membership:</strong>
                                    <span>Exclusive Private Club</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-history"></i>
                                <div>
                                    <strong>Heritage:</strong>
                                    <span>122 Years of Tradition</span>
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
                <div class="gallery-item" onclick="openModal('/images/courses/richland-country-club/1.jpeg', 'Richland Country Club Clubhouse')">
                    <img src="/images/courses/richland-country-club/1.jpeg" alt="Richland Country Club Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/richland-country-club/2.jpeg', 'Jack Nicklaus Signature Design')">
                    <img src="/images/courses/richland-country-club/2.jpeg" alt="Jack Nicklaus Signature Design">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/richland-country-club/3.jpeg', 'Tennessee Hill Country Layout')">
                    <img src="/images/courses/richland-country-club/3.jpeg" alt="Tennessee Hill Country Layout">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/richland-country-club/4.jpeg', 'Award-Winning Bill Bergin Renovation')">
                    <img src="/images/courses/richland-country-club/4.jpeg" alt="Award-Winning Bill Bergin Renovation">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/richland-country-club/5.jpeg', 'Practice Facilities')">
                    <img src="/images/courses/richland-country-club/5.jpeg" alt="Practice Facilities">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/richland-country-club/6.jpeg', 'Champions Heritage')">
                    <img src="/images/courses/richland-country-club/6.jpeg" alt="Champions Heritage">
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience at Richland Country Club..."></textarea>
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