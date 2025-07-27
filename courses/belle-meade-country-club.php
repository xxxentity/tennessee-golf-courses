<?php
session_start();
require_once '../config/database.php';

$course_slug = 'belle-meade-country-club';
$course_name = 'Belle Meade Country Club';

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
    <title>Belle Meade Country Club - Historic Donald Ross Design | Tennessee Golf Courses</title>
    <meta name="description" content="Belle Meade Country Club - Historic Donald Ross design since 1921 in exclusive Belle Meade. One of Nashville's most prestigious private clubs with championship golf.">
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
                        <span>Belle Meade, Tennessee</span>
                    </div>
                    <h1 class="course-title">Belle Meade Country Club</h1>
                    <p class="course-subtitle">Historic Donald Ross Design Since 1921</p>
                    
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
                                <span>6,885 Yards</span>
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
                        <img src="/images/courses/belle-meade-country-club/1.jpeg" alt="Belle Meade Country Club" class="hero-image">
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
                        <h2>About Belle Meade Country Club</h2>
                        <p>Belle Meade Country Club stands as Nashville's most historic and prestigious golf destination, representing over 120 years of tradition and excellence. Founded in 1901 as Nashville Golf and Country Club, the club relocated to its current Belle Meade location in 1916 and was renamed in 1921, establishing itself in one of America's most exclusive neighborhoods.</p>
                        
                        <p>The course showcases the timeless genius of Donald Ross, who completed Herbert H. Barker's original design between 1917-1921. Ross's naturalistic approach created a challenging yet fair layout that has hosted the inaugural U.S. Senior Amateur Championship in 1955 and will host both the U.S. Senior Women's Amateur (2028) and U.S. Senior Amateur (2036).</p>
                        
                        <p>Located in Belle Meade, where per capita income ranks among the highest in the nation, the club embodies Southern hospitality and golf tradition while maintaining its commitment to preserving Donald Ross's architectural legacy through careful renovations by Robert Trent Jones Sr., Gary Roger Baird, and Rees Jones.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Historic Timeline & Heritage</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1901</div>
                                <div class="timeline-content">
                                    <h4>Club Foundation</h4>
                                    <p>Nashville Golf and Country Club founded in Whitworth area</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1914</div>
                                <div class="timeline-content">
                                    <h4>Herbert Barker Design</h4>
                                    <p>Herbert H. Barker contracted to design course at Belle Meade location</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1917-1921</div>
                                <div class="timeline-content">
                                    <h4>Donald Ross Completion</h4>
                                    <p>Donald Ross hired to complete course, implementing signature design elements</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1921</div>
                                <div class="timeline-content">
                                    <h4>Belle Meade Country Club</h4>
                                    <p>Club renamed Belle Meade Country Club, establishing current identity</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1955</div>
                                <div class="timeline-content">
                                    <h4>Inaugural U.S. Senior Amateur</h4>
                                    <p>Hosted first-ever U.S. Senior Amateur Championship</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2003-2004</div>
                                <div class="timeline-content">
                                    <h4>Rees Jones Renovation</h4>
                                    <p>Major renovation preserving Ross character while modernizing course</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Donald Ross Design Legacy</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-drafting-compass"></i>
                                </div>
                                <h4>Naturalistic Design</h4>
                                <p>Ross's philosophy of minimal earth moving creating challenging natural golf</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <h4>Strategic Bunkering</h4>
                                <p>Signature Ross bunker placement and crowned "turtleback" greens</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-water"></i>
                                </div>
                                <h4>Richland Creek</h4>
                                <p>Historic creek comes into play at seven holes adding natural challenge</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-tree"></i>
                                </div>
                                <h4>Tree-Lined Fairways</h4>
                                <p>Mature trees frame fairways creating strategic shot requirements</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Tournament Heritage & Future</h3>
                        <p>Belle Meade Country Club's tournament pedigree spans seven decades, beginning with hosting the inaugural U.S. Senior Amateur Championship in 1955, where John Wood Platt defeated George Studinger 5 and 4. This historic event established Belle Meade as a venue capable of hosting USGA championships at the highest level.</p>
                        
                        <p>The club's commitment to championship golf continues with upcoming USGA events. Belle Meade will host the U.S. Senior Women's Amateur in 2028 and return to hosting the U.S. Senior Amateur in 2036, demonstrating the USGA's confidence in the course's championship caliber and the club's operational excellence.</p>
                        
                        <p>Notable members include Katherine Graham, a USGA volunteer and chair of the USGA Women's Committee who captained the 1990 World Amateur Team Championship, and Sarah LeBrun Ingram, three-time U.S. Women's Mid-Amateur champion, reflecting the club's tradition of developing and supporting championship-level amateur golf.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Belle Meade: America's Most Exclusive Neighborhood</h3>
                        <p>Belle Meade Country Club's location in Belle Meade places it in one of America's most prestigious neighborhoods. Originally established as a plantation in 1807, Belle Meade (French for "beautiful meadow") became famous for thoroughbred horse breeding, with more than two-thirds of all Kentucky Derby winners tracing their lineage to Belle Meade's prize stallion Bonnie Scotland.</p>
                        
                        <p>Today, Belle Meade remains an independent city within Nashville with its own mayor and police force, featuring grand estates with Colonial, Georgian, and Tudor architecture dating to the 1920s. With a median annual income of $195,208, the neighborhood represents the pinnacle of Nashville society and provides the perfect setting for the city's most historic golf club.</p>
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
                                <strong>Architects:</strong>
                                <span>Herbert Barker & Donald Ross</span>
                            </div>
                            <div class="info-item">
                                <strong>Belle Meade Location:</strong>
                                <span>1916 (Renamed 1921)</span>
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
                                <strong>Black Tees:</strong>
                                <span>6,885 yards (Rating 73.7, Slope 133)</span>
                            </div>
                            <div class="info-item">
                                <strong>Blue Tees:</strong>
                                <span>6,574 yards (Rating 72.3, Slope 130)</span>
                            </div>
                            <div class="info-item">
                                <strong>White Tees:</strong>
                                <span>6,229 yards (Rating 70.6, Slope 127)</span>
                            </div>
                            <div class="info-item">
                                <strong>Ladies Tees:</strong>
                                <span>5,091 yards (Rating 70.2, Slope 122)</span>
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
                                <span>Traditional Club Dining</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-wine-glass"></i>
                                <span>Member Lounge & Bar</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-store"></i>
                                <span>Professional Pro Shop</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-golf-ball"></i>
                                <span>Practice Facilities</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Professional Instruction</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-table-tennis"></i>
                                <span>Tennis Courts</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-swimmer"></i>
                                <span>Swimming Pool</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-dumbbell"></i>
                                <span>Fitness Center</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-calendar"></i>
                                <span>Private Event Hosting</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-trophy"></i>
                                <span>USGA Championship Venue</span>
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
                                    <span>815 Belle Meade Blvd<br>Nashville, TN 37205</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <span>(615) 385-0150</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://bellemeadecc.org" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong>Email:</strong>
                                    <span>jyork@bellemeadecc.org</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-crown"></i>
                                <div>
                                    <strong>Neighborhood:</strong>
                                    <span>Exclusive Belle Meade</span>
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
                <div class="gallery-item" onclick="openModal('/images/courses/belle-meade-country-club/1.jpeg', 'Belle Meade Country Club Clubhouse')">
                    <img src="/images/courses/belle-meade-country-club/1.jpeg" alt="Belle Meade Country Club Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/belle-meade-country-club/2.jpeg', 'Donald Ross Championship Design')">
                    <img src="/images/courses/belle-meade-country-club/2.jpeg" alt="Donald Ross Championship Design">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/belle-meade-country-club/3.jpeg', 'Historic 16th Hole Peninsula Green')">
                    <img src="/images/courses/belle-meade-country-club/3.jpeg" alt="Historic 16th Hole Peninsula Green">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/belle-meade-country-club/4.jpeg', 'Richland Creek Water Feature')">
                    <img src="/images/courses/belle-meade-country-club/4.jpeg" alt="Richland Creek Water Feature">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/belle-meade-country-club/5.jpeg', 'Tree-Lined Fairways')">
                    <img src="/images/courses/belle-meade-country-club/5.jpeg" alt="Tree-Lined Fairways">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/belle-meade-country-club/6.jpeg', 'Practice Facilities')">
                    <img src="/images/courses/belle-meade-country-club/6.jpeg" alt="Practice Facilities">
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience at Belle Meade Country Club..."></textarea>
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