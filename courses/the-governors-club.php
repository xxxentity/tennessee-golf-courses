<?php
session_start();
require_once '../config/database.php';

$course_slug = 'the-governors-club';
$course_name = 'The Governors Club';

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
    <title>The Governors Club - Arnold Palmer Signature Design | Tennessee Golf Courses</title>
    <meta name="description" content="The Governors Club - Arnold Palmer Signature Design in exclusive Brentwood on historic Winstead farmland. Luxury private club with 7,031-yard championship course.">
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
                        <span>Brentwood, Tennessee</span>
                    </div>
                    <h1 class="course-title">The Governors Club</h1>
                    <p class="course-subtitle">Arnold Palmer Signature Design</p>
                    
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
                                <span>7,031 Yards</span>
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
                        <img src="/images/courses/the-governors-club/1.jpeg" alt="The Governors Club" class="hero-image">
                        <div class="image-overlay">
                            <div class="price-badge">
                                <span class="price-label">Historic</span>
                                <span class="price-amount">Since 1799</span>
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
                        <h2>About The Governors Club</h2>
                        <p>The Governors Club represents the pinnacle of private golf and luxury living in Middle Tennessee, featuring an Arnold Palmer Signature Design on 617 acres of historic Winstead family farmland dating to 1799. Located in prestigious Brentwood, this exclusive community combines championship golf with luxury residential living, creating one of Tennessee's most desirable destinations.</p>
                        
                        <p>Arnold Palmer personally supervised the creation of this masterpiece, utilizing the property's natural rolling hills, historic stone walls, spring-fed creeks, and scenic waterfalls to create breathtaking views at every turn. Palmer himself declared: "I am very proud of the people who created this masterpiece," highlighting the exceptional quality and attention to detail that defines this 7,031-yard championship layout.</p>
                        
                        <p>The club's setting in Brentwood, ranked as the seventh-wealthiest small town in the United States, provides an exclusive atmosphere befitting its prestigious membership. With 425 luxury homes in the gated community and 75% of homeowners maintaining club memberships, The Governors Club epitomizes the harmony between exceptional golf and luxury lifestyle.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Historic Winstead Farmland Heritage</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">1799</div>
                                <div class="timeline-content">
                                    <h4>Winstead Family Settlement</h4>
                                    <p>Colonel John Winstead and family take possession of rich Tennessee farmland</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1799-1997</div>
                                <div class="timeline-content">
                                    <h4>Family Heritage</h4>
                                    <p>Winstead family maintains ownership for nearly two centuries</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1997</div>
                                <div class="timeline-content">
                                    <h4>The Governors Club Founded</h4>
                                    <p>617 acres purchased for development of exclusive golf community</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">1999-2000</div>
                                <div class="timeline-content">
                                    <h4>Arnold Palmer Course Opens</h4>
                                    <p>Championship golf course opens as Palmer Signature Design</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">Present</div>
                                <div class="timeline-content">
                                    <h4>Luxury Community</h4>
                                    <p>425 luxury homes with 75% membership participation rate</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Arnold Palmer Design Legacy</h3>
                        <div class="layout-grid">
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <h4>Signature Design</h4>
                                <p>True Arnold Palmer Signature course with personal supervision by "The King"</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-water"></i>
                                </div>
                                <h4>Natural Features</h4>
                                <p>Spring-fed creeks, waterfalls, and stone walls integrated into design</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-mountain"></i>
                                </div>
                                <h4>Rolling Hills</h4>
                                <p>Natural terrain with dramatic elevation changes and scenic vistas</p>
                            </div>
                            <div class="layout-item">
                                <div class="layout-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h4>Championship Layout</h4>
                                <p>7,031 yards of challenging golf for players of all skill levels</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Exclusive Brentwood Location</h3>
                        <p>The Governors Club's location in Brentwood places it in one of America's most affluent communities. Brentwood ranks as the seventh-wealthiest small town in the United States, with a median household income of $181,576 and median home prices of $1.14 million. This exclusive Nashville suburb combines luxury living with top-rated schools and low crime rates.</p>
                        
                        <p>The community attracts high-profile residents including country music legends, professional athletes, and business leaders. Notable residents have included Alan Jackson (since 1997), former NFL quarterback Kerry Collins, and actress Nicole Kidman, reflecting the area's appeal to celebrities and successful professionals.</p>
                        
                        <p>With 98.4% of adult residents holding high school diplomas and 68.4% possessing bachelor's degrees or higher, Brentwood represents an educated, successful community that values excellence in all aspects of life, making The Governors Club a perfect fit for this prestigious environment.</p>
                    </div>

                    <div class="detail-section">
                        <h3>Celebrity Heritage & Notable Members</h3>
                        <p>The Governors Club has been home to many celebrities and high-profile individuals who have chosen this exclusive community for its privacy, luxury, and championship golf. Country music legend Alan Jackson has been a resident since 1997, representing the club's appeal to Nashville's entertainment industry elite.</p>
                        
                        <p>Former NFL quarterback Kerry Collins and actress Nicole Kidman have also called The Governors Club home, demonstrating its attraction to athletes and entertainers who demand the highest standards of privacy and luxury. The club's 24-hour security gate and gated community design provide the discretion that high-profile members require.</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-card">
                        <h3>Club Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Designer:</strong>
                                <span>Arnold Palmer Signature</span>
                            </div>
                            <div class="info-item">
                                <strong>Established:</strong>
                                <span>1997</span>
                            </div>
                            <div class="info-item">
                                <strong>Course Opened:</strong>
                                <span>1999-2000</span>
                            </div>
                            <div class="info-item">
                                <strong>Type:</strong>
                                <span>Private Golf Community</span>
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
                                <strong>Palmer Tee:</strong>
                                <span>7,031 yards (Rating 73.8, Slope 139)</span>
                            </div>
                            <div class="info-item">
                                <strong>Championship Tee:</strong>
                                <span>6,547 yards (Rating 71.4, Slope 133)</span>
                            </div>
                            <div class="info-item">
                                <strong>Ladies Tee:</strong>
                                <span>5,064 yards (Rating 69.8, Slope 122)</span>
                            </div>
                            <div class="info-item">
                                <strong>Historic Land:</strong>
                                <span>Winstead Farmland since 1799</span>
                            </div>
                        </div>
                    </div>

                    <div class="amenities-card">
                        <h3>Club Amenities</h3>
                        <div class="amenities-list">
                            <div class="amenity-item">
                                <i class="fas fa-home"></i>
                                <span>Luxury Clubhouse</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-utensils"></i>
                                <span>Palmer Room Fine Dining</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-wine-glass"></i>
                                <span>Palmer Dining Room</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-swimmer"></i>
                                <span>Swimming Pools</span>
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
                                <span>Practice Facilities</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-ring"></i>
                                <span>Pleasant Hill Mansion Weddings</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>24-Hour Gated Security</span>
                            </div>
                            <div class="amenity-item">
                                <i class="fas fa-home"></i>
                                <span>425 Luxury Homes</span>
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
                                    <span>18 Governors Way<br>Brentwood, TN 37027</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Membership:</strong>
                                    <span>(615) 776-4404</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-golf-ball"></i>
                                <div>
                                    <strong>Golf Shop:</strong>
                                    <span>(615) 776-4311</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Website:</strong>
                                    <a href="https://thegovernorsclub.com" target="_blank">Visit Website</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-star"></i>
                                <div>
                                    <strong>Golf Digest:</strong>
                                    <span>4.3/5 Rating</span>
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
                <div class="gallery-item" onclick="openModal('/images/courses/the-governors-club/1.jpeg', 'The Governors Club Clubhouse')">
                    <img src="/images/courses/the-governors-club/1.jpeg" alt="The Governors Club Clubhouse">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-governors-club/2.jpeg', 'Arnold Palmer Signature Design')">
                    <img src="/images/courses/the-governors-club/2.jpeg" alt="Arnold Palmer Signature Design">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-governors-club/3.jpeg', 'Historic Winstead Farmland')">
                    <img src="/images/courses/the-governors-club/3.jpeg" alt="Historic Winstead Farmland">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-governors-club/4.jpeg', 'Spring-Fed Creeks and Waterfalls')">
                    <img src="/images/courses/the-governors-club/4.jpeg" alt="Spring-Fed Creeks and Waterfalls">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-governors-club/5.jpeg', 'Rolling Hills Championship Layout')">
                    <img src="/images/courses/the-governors-club/5.jpeg" alt="Rolling Hills Championship Layout">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="gallery-item" onclick="openModal('/images/courses/the-governors-club/6.jpeg', 'Exclusive Brentwood Community')">
                    <img src="/images/courses/the-governors-club/6.jpeg" alt="Exclusive Brentwood Community">
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
                            <textarea name="comment_text" id="comment_text" rows="4" placeholder="Share your experience at The Governors Club..."></textarea>
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