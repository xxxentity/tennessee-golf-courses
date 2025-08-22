<?php
session_start();
require_once '../config/database.php';

$course_slug = 'windyke-country-club';
$course_name = 'Windyke Country Club';

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
    <title>Windyke Country Club - Tennessee Golf Courses</title>
    <meta name="description" content="Windyke Country Club - Premier private golf facility in Memphis, TN featuring East and West championship courses plus Executive par-3 course. Family-oriented country club since 1962.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=6">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=6">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .course-hero {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/windyke-country-club/1.webp');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 20px;
        }
        
        .course-hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .course-hero-content p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .course-info {
            background: var(--bg-white);
            padding: 4rem 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .course-overview {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            margin-bottom: 4rem;
        }
        
        .course-details h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .course-specs {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            border-left: 4px solid var(--secondary-color);
        }
        
        .spec-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .spec-label {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .spec-value {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .course-description {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-dark);
            margin-bottom: 2rem;
        }
        
        .highlights {
            background: var(--bg-light);
            padding: 3rem 0;
        }
        
        .highlights h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 3rem;
            font-size: 2.5rem;
        }
        
        .highlights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .highlight-card {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .highlight-card:hover {
            transform: translateY(-5px);
        }
        
        .highlight-icon {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }
        
        .highlight-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .course-gallery {
            padding: 4rem 0;
            background: var(--bg-white);
        }
        
        .course-gallery h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 3rem;
            font-size: 2.5rem;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .gallery-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 16/9;
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .contact-info {
            background: var(--primary-color);
            color: white;
            padding: 3rem 0;
            text-align: center;
        }
        
        .contact-info h2 {
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        
        .contact-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        
        .contact-item i {
            font-size: 1.5rem;
        }
        
        .contact-item span {
            font-size: 1.1rem;
        }
        
        .map-section {
            padding: 4rem 0;
            background: var(--bg-light);
        }
        
        .map-container {
            text-align: center;
        }
        
        .map-container h2 {
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        
        .map-frame {
            border: none;
            border-radius: 15px;
            width: 100%;
            height: 400px;
            box-shadow: var(--shadow-medium);
        }
        
        .reviews-section {
            background: var(--bg-white);
            padding: 4rem 0;
        }
        
        .reviews-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .reviews-header h2 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .rating-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stars {
            color: #ffd700;
            font-size: 1.5rem;
        }
        
        .rating-text {
            color: var(--text-dark);
            font-size: 1.1rem;
        }
        
        .review-form {
            max-width: 600px;
            margin: 0 auto 3rem auto;
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
        }
        
        .review-form h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .rating-input {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .star {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .star.active,
        .star:hover {
            color: #ffd700;
        }
        
        .form-group textarea {
            width: 100%;
            min-height: 120px;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
        }
        
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .submit-btn {
            background: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }
        
        .submit-btn:hover {
            background: var(--secondary-color);
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 15px;
            margin: 0 auto;
            max-width: 600px;
        }
        
        .login-btn {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 1rem;
            transition: background 0.3s ease;
        }
        
        .login-btn:hover {
            background: var(--secondary-color);
        }
        
        .comments-list {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .comment {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border-left: 4px solid var(--secondary-color);
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .comment-rating {
            color: #ffd700;
        }
        
        .comment-date {
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        
        .comment-text {
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-overview {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .spec-grid {
                grid-template-columns: 1fr;
            }
            
            .highlights-grid {
                grid-template-columns: 1fr;
            }
            
            .contact-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Windyke Country Club</h1>
            <p>Premier Private Golf Facility • Memphis, Tennessee</p>
        </div>
    </section>

    <!-- Course Information -->
    <section class="course-info">
        <div class="container">
            <div class="course-overview">
                <div class="course-details">
                    <h2>About Windyke Country Club</h2>
                    <div class="course-description">
                        <p>Windyke Country Club stands as Memphis's premier private golf facility, offering a truly exceptional experience since 1962. Founded by Mr. Dykema with the vision of creating a family-oriented country club that was affordable and inclusive rather than expensive and exclusive, Windyke has remained true to this philosophy for over six decades.</p>
                        
                        <p>The club features two championship 18-hole courses - the East Course and West Course - plus an 18-hole Executive par-3 course designed to promote junior golf. With over 1,000 family members, Windyke continues to embody its founding principles as a high-quality, family-oriented recreational facility.</p>
                        
                        <p>Both championship courses feature Champion Bermuda grass greens and Tifway 419 Bermuda grass fairways, providing consistent playing conditions year-round. The East Course, designed by John Frazier and built in 1962, offers a challenging 7,211 yards from the back tees with a course rating of 75.0 and slope of 134. The West Course, also designed by John Frazier with contributions from William Amick, plays to 6,813 yards with a course rating of 72.6 and slope of 130.</p>
                    </div>
                </div>
                
                <div class="course-specs">
                    <h3>Course Specifications</h3>
                    <div class="spec-grid">
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">John Frazier / William Amick</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Year Opened:</span>
                            <span class="spec-value">1962</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Type:</span>
                            <span class="spec-value">Private</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">East Course Par:</span>
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">East Yardage:</span>
                            <span class="spec-value">7,211 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">West Course Par:</span>
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">West Yardage:</span>
                            <span class="spec-value">6,813 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">134 (East) / 130 (West)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">75.0 (East) / 72.6 (West)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Greens:</span>
                            <span class="spec-value">Champion Bermuda</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Highlights -->
    <section class="highlights">
        <div class="container">
            <h2>Club Highlights</h2>
            <div class="highlights-grid">
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-golf-ball"></i>
                    </div>
                    <h3>54 Holes of Golf</h3>
                    <p>Two championship 18-hole courses plus an 18-hole Executive par-3 course perfect for all skill levels and junior golfers.</p>
                </div>
                
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Family-Oriented</h3>
                    <p>Over 1,000 families enjoy regular, executive, or tennis memberships at this inclusive country club.</p>
                </div>
                
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-swimming-pool"></i>
                    </div>
                    <h3>Complete Amenities</h3>
                    <p>Swimming pool, tennis courts, dining facilities, pro shops, and banquet spaces for all occasions.</p>
                </div>
                
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Championship Quality</h3>
                    <p>Two challenging championship courses with Champion Bermuda greens and Tifway 419 Bermuda fairways.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery -->
    <section class="course-gallery">
        <div class="container">
            <h2>Course Gallery</h2>
            <div class="gallery-grid">
                <?php for ($i = 2; $i <= 13; $i++): ?>
                    <div class="gallery-item">
                        <img src="../images/courses/windyke-country-club/<?php echo $i; ?>.webp" alt="Windyke Country Club - Image <?php echo $i; ?>" loading="lazy">
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="contact-info">
        <div class="container">
            <h2>Visit Windyke Country Club</h2>
            <div class="contact-details">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>8535 Winchester Road, Memphis, TN 38125</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>(901) 754-7273</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-globe"></i>
                    <span>www.windyke.com</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Map -->
    <section class="map-section">
        <div class="container">
            <div class="map-container">
                <h2>Location</h2>
                <iframe class="map-frame" 
                    src="https://www.google.com/maps/embed/v1/search?key=AIzaSyBi6ARG0UFKBhRGMh51I1GNNcfIyBJ2VQ8&q=Windyke+Country+Club+Memphis+TN" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <div class="reviews-header">
                <h2>Course Reviews</h2>
                <?php if ($avg_rating): ?>
                    <div class="rating-display">
                        <div class="stars">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= floor($avg_rating)) {
                                    echo '<i class="fas fa-star"></i>';
                                } elseif ($i <= ceil($avg_rating)) {
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <span class="rating-text"><?php echo $avg_rating; ?>/5 based on <?php echo $total_reviews; ?> review<?php echo $total_reviews != 1 ? 's' : ''; ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($is_logged_in): ?>
                <div class="review-form">
                    <h3>Leave a Review</h3>
                    <?php if (isset($success_message)): ?>
                        <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                    <?php endif; ?>
                    <?php if (isset($error_message)): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Your Rating:</label>
                            <div class="rating-input" id="ratingInput">
                                <span class="star" data-rating="1"><i class="far fa-star"></i></span>
                                <span class="star" data-rating="2"><i class="far fa-star"></i></span>
                                <span class="star" data-rating="3"><i class="far fa-star"></i></span>
                                <span class="star" data-rating="4"><i class="far fa-star"></i></span>
                                <span class="star" data-rating="5"><i class="far fa-star"></i></span>
                            </div>
                            <input type="hidden" name="rating" id="rating" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="comment_text">Your Review:</label>
                            <textarea name="comment_text" id="comment_text" placeholder="Share your experience at Windyke Country Club..." required></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">Submit Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p>Please log in to leave a review of Windyke Country Club.</p>
                    <a href="/login" class="login-btn">Log In to Review</a>
                </div>
            <?php endif; ?>

            <!-- Comments Display -->
            <div class="comments-list">
                <?php if (empty($comments)): ?>
                    <p style="text-align: center; color: var(--text-gray); padding: 2rem;">No reviews yet. Be the first to share your experience!</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-header">
                                <div>
                                    <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                    <div class="comment-rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $comment['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <span class="comment-date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></span>
                            </div>
                            <p class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="/images/logos/logo.webp" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61579553544749" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        <a href="https://x.com/TNGolfCourses" target="_blank" rel="noopener noreferrer"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/tennesseegolfcourses/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@TennesseeGolf" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/courses">Courses</a></li>
                        <li><a href="/reviews">Reviews</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/events">Events</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="courses?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="courses?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="courses?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="courses?region=Memphis Area">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-of-service">Terms of Service</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../weather.js?v=4"></script>
    <script src="../script.js?v=4"></script>
    <script>
        // Star rating functionality
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                ratingInput.value = rating;
                
                stars.forEach((s, i) => {
                    const icon = s.querySelector('i');
                    if (i < rating) {
                        icon.className = 'fas fa-star';
                        s.classList.add('active');
                    } else {
                        icon.className = 'far fa-star';
                        s.classList.remove('active');
                    }
                });
            });
            
            star.addEventListener('mouseenter', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                
                stars.forEach((s, i) => {
                    const icon = s.querySelector('i');
                    if (i < rating) {
                        icon.className = 'fas fa-star';
                    } else {
                        icon.className = 'far fa-star';
                    }
                });
            });
        });
        
        document.getElementById('ratingInput').addEventListener('mouseleave', () => {
            const currentRating = parseInt(ratingInput.value) || 0;
            
            stars.forEach((s, i) => {
                const icon = s.querySelector('i');
                if (i < currentRating) {
                    icon.className = 'fas fa-star';
                    s.classList.add('active');
                } else {
                    icon.className = 'far fa-star';
                    s.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>