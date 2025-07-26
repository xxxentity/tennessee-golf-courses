<?php
session_start();
require_once '../config/database.php';

$course_slug = 'hermitage-golf-course';
$course_name = 'Hermitage Golf Course';

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
    <title>Hermitage Golf Course - Tennessee Golf Courses</title>
    <meta name="description" content="Hermitage Golf Course - Nashville's premier public golf destination featuring two championship courses: President's Reserve and General's Retreat. Home to former LPGA tour events.">
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
    
    <style>
        .course-hero {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/hermitage-golf-course/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 120px;
        }
        
        .course-hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .course-hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .course-rating {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .rating-stars {
            color: #ffd700;
            font-size: 1.5rem;
        }
        
        .rating-text {
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .course-details {
            padding: 4rem 0;
        }
        
        .course-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 3rem;
            margin-bottom: 4rem;
        }
        
        .course-description {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .course-description h2 {
            color: #2c5234;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        
        .course-description p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 1.5rem;
        }
        
        .course-sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        
        .course-info-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .course-info-card h3 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .course-specs {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .spec-label {
            font-weight: 600;
            color: #333;
        }
        
        .spec-value {
            color: #666;
            font-weight: 500;
        }
        
        .amenities-list {
            list-style: none;
            padding: 0;
        }
        
        .amenities-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .amenities-list i {
            color: #2c5234;
            width: 20px;
        }
        
        .location-details p {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .location-details i {
            color: #2c5234;
            width: 20px;
        }
        
        .dual-courses {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 3rem;
            margin-top: 3rem;
        }
        
        .course-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .course-card h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        
        .course-card .course-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-box {
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c5234;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.25rem;
        }
        
        .course-card p {
            line-height: 1.6;
            color: #555;
        }
        
        .reviews-section {
            padding: 4rem 0;
        }
        
        .reviews-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .reviews-header h2 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        
        .comment-form-container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .comment-form-container h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .star-rating {
            display: flex;
            justify-content: flex-start;
            gap: 5px;
        }
        
        .star-rating input[type="radio"] {
            display: none;
        }
        
        .star-rating label {
            color: #ddd;
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .star-rating label:hover {
            color: #ffd700;
        }
        
        .star-rating label.active {
            color: #ffd700;
        }
        
        .comment-form textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
        }
        
        .comment-form textarea:focus {
            outline: none;
            border-color: #2c5234;
        }
        
        .btn-submit {
            background: #2c5234;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-submit:hover {
            background: #1a3020;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .login-prompt a {
            color: #2c5234;
            text-decoration: none;
            font-weight: 600;
        }
        
        .comments-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .review-card {
            padding: 2rem;
            border-bottom: 1px solid #eee;
        }
        
        .review-card:last-child {
            border-bottom: none;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .reviewer-name {
            font-weight: 600;
            color: #2c5234;
        }
        
        .review-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .rating-stars {
            color: #ffd700;
            margin-bottom: 1rem;
        }
        
        .review-text {
            color: #555;
            line-height: 1.6;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .photo-gallery {
            margin: 4rem 0;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        
        .gallery-item {
            height: 250px;
            background-size: cover;
            background-position: center;
            border-radius: 15px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
        }
        
        .gallery-button {
            text-align: center;
            margin-top: 2rem;
        }
        
        .btn-gallery {
            background: #4a7c59;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-gallery:hover {
            background: #2c5234;
            transform: translateY(-2px);
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
        }
        
        .modal-content {
            margin: 2% auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
            position: relative;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            color: white;
        }
        
        .modal-title {
            font-size: 2rem;
            margin: 0;
        }
        
        .close {
            color: white;
            font-size: 3rem;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
        }
        
        .close:hover {
            color: #ccc;
        }
        
        .full-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .full-gallery-item {
            height: 200px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .full-gallery-item:hover {
            transform: scale(1.05);
        }
        
        @media (max-width: 1024px) {
            .course-layout {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .courses-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-card .course-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Hermitage Golf Course</h1>
            <p>Nashville's Premier Public Golf Destination • Old Hickory, Tennessee</p>
            <div class="course-rating">
                <?php if ($avg_rating !== null && $total_reviews > 0): ?>
                    <div class="rating-stars">
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
                    <span class="rating-text"><?php echo $avg_rating; ?> / 5.0 (<?php echo $total_reviews; ?> review<?php echo $total_reviews !== 1 ? 's' : ''; ?>)</span>
                <?php else: ?>
                    <div class="no-rating">
                        <i class="fas fa-star-o" style="color: #ddd; margin-right: 8px;"></i>
                        <span class="rating-text" style="color: #666;">No ratings yet - Be the first to review!</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Course Details -->
    <section class="course-details">
        <div class="container">
            <div class="course-layout">
                <div class="course-description">
                    <h2>Two Championship Courses, One Premier Destination</h2>
                    <p>Hermitage Golf Course stands as Nashville's premier public golf facility, featuring two distinctly different championship courses that have earned recognition throughout Tennessee and beyond. Located just 30 minutes from downtown Nashville along the scenic Cumberland River, this award-winning facility offers golfers an unparalleled experience in Middle Tennessee.</p>
                    
                    <p>The flagship <strong>President's Reserve</strong>, designed by Denis Griffiths in 2000, has been recognized by Golf Digest as one of the "Top 10 in Tennessee." This challenging 7,200-yard layout winds through 300 acres of natural Tennessee wetlands along the Cumberland River, featuring bentgrass greens and zoysia fairways that provide exceptional playing conditions year-round.</p>
                    
                    <p>The historic <strong>General's Retreat</strong>, designed by Gary Roger Baird in 1986, earned its reputation as the "Best Golf Course in Nashville" while hosting the LPGA Sara Lee Classic from 1988 to 1999. World Golf Hall of Fame members Nancy Lopez, Meg Mallon, and Laura Davies all claimed victories on this 6,773-yard championship layout.</p>
                    
                    <p>Both courses offer stunning views of the Cumberland River and provide challenges suitable for golfers of all skill levels, with multiple tee options ensuring an enjoyable experience whether you're a scratch golfer or weekend warrior. The facility's commitment to excellence extends beyond the courses to include top-tier amenities, professional instruction, and exceptional hospitality.</p>
                </div>
                
                <div class="course-sidebar">
                    <div class="course-info-card">
                        <h3><i class="fas fa-info-circle"></i> Course Information</h3>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">Courses:</span>
                                <span class="spec-value">2 Championship 18-hole</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Type:</span>
                                <span class="spec-value">Public</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Opened:</span>
                                <span class="spec-value">1986 & 2000</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Awards:</span>
                                <span class="spec-value">Golf Digest Top 10 TN</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Tournament Host:</span>
                                <span class="spec-value">LPGA Sara Lee Classic</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Setting:</span>
                                <span class="spec-value">Cumberland River</span>
                            </div>
                        </div>
                    </div>

                    <div class="course-info-card">
                        <h3><i class="fas fa-star"></i> Amenities</h3>
                        <ul class="amenities-list">
                            <li><i class="fas fa-store"></i> Full-Service Pro Shop</li>
                            <li><i class="fas fa-utensils"></i> On-Site Restaurant & Grill</li>
                            <li><i class="fas fa-graduation-cap"></i> Golf Instruction Center</li>
                            <li><i class="fas fa-golf-ball"></i> Driving Range</li>
                            <li><i class="fas fa-flag"></i> Practice Putting Green</li>
                            <li><i class="fas fa-home"></i> 8 On-Site Cottages</li>
                            <li><i class="fas fa-heart"></i> Wedding/Event Venue</li>
                            <li><i class="fas fa-mobile-alt"></i> Mobile App</li>
                            <li><i class="fas fa-child"></i> Junior Golf Programs</li>
                            <li><i class="fas fa-gift"></i> Gift Cards Available</li>
                        </ul>
                    </div>

                    <div class="course-info-card">
                        <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                        <div class="location-details">
                            <p><i class="fas fa-map-marker-alt"></i> 3939 Old Hickory Boulevard, Old Hickory, TN 37138</p>
                            <p><i class="fas fa-phone"></i> (615) 847-4001</p>
                            <p><i class="fas fa-globe"></i> <a href="https://www.hermitagegolf.com" target="_blank">hermitagegolf.com</a></p>
                            <p><i class="fas fa-directions"></i> <a href="https://maps.google.com/maps?q=3939+Old+Hickory+Boulevard,+Old+Hickory,+TN+37138" target="_blank">Get Directions</a></p>
                            <p><i class="fas fa-clock"></i> Daily 7:00 AM - 8:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dual Courses Section -->
    <section class="dual-courses">
        <div class="container">
            <div class="section-header">
                <h2>Two Distinct Championship Experiences</h2>
                <p>Choose your challenge from two award-winning golf courses</p>
            </div>
            
            <div class="courses-grid">
                <div class="course-card">
                    <h3>President's Reserve</h3>
                    <div class="course-stats">
                        <div class="stat-box">
                            <div class="stat-number">7,200</div>
                            <div class="stat-label">Yards</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">72</div>
                            <div class="stat-label">Par</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">74.4</div>
                            <div class="stat-label">Rating</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">132</div>
                            <div class="stat-label">Slope</div>
                        </div>
                    </div>
                    <p><strong>Designer:</strong> Denis Griffiths (2000)</p>
                    <p><strong>Recognition:</strong> Golf Digest "Top 10 in Tennessee"</p>
                    <p>The crown jewel of Hermitage Golf Course, President's Reserve winds through 300 acres of pristine Tennessee wetlands along the Cumberland River. This championship layout features bentgrass greens and zoysia fairways, offering exceptional playing conditions and breathtaking natural beauty. The course demands strategic thinking and precise shot-making while rewarding golfers with stunning river views and wildlife encounters.</p>
                </div>
                
                <div class="course-card">
                    <h3>General's Retreat</h3>
                    <div class="course-stats">
                        <div class="stat-box">
                            <div class="stat-number">6,773</div>
                            <div class="stat-label">Yards</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">72</div>
                            <div class="stat-label">Par</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">72.3</div>
                            <div class="stat-label">Rating</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">131</div>
                            <div class="stat-label">Slope</div>
                        </div>
                    </div>
                    <p><strong>Designer:</strong> Gary Roger Baird (1986)</p>
                    <p><strong>Tournament History:</strong> LPGA Sara Lee Classic (1988-1999)</p>
                    <p>Voted "Best Golf Course in Nashville," General's Retreat boasts an impressive tournament pedigree as the former host of the LPGA Sara Lee Classic. This historic layout features four challenging par 5s, with three offering exciting risk-reward opportunities over water. The course balances generous fairways with strategic challenges, making it enjoyable for all skill levels while maintaining championship standards.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Hermitage Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/hermitage-golf-course/2.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/hermitage-golf-course/3.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/hermitage-golf-course/4.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/hermitage-golf-course/5.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/hermitage-golf-course/6.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/hermitage-golf-course/7.jpeg');"></div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View All Photos (27+)</button>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Hermitage Golf Course - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <div class="reviews-header">
                <h2>Player Reviews</h2>
                <p>Share your experience at Hermitage Golf Course</p>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <!-- Comment Form (Only for logged in users) -->
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container">
                    <h3>Share Your Experience</h3>
                    <form method="POST" class="comment-form">
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <div class="star-rating" id="hermitage-rating-stars">
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label for="star1" title="1 star" data-rating="1"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star2" name="rating" value="2" />
                                <label for="star2" title="2 stars" data-rating="2"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star3" name="rating" value="3" />
                                <label for="star3" title="3 stars" data-rating="3"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star4" name="rating" value="4" />
                                <label for="star4" title="4 stars" data-rating="4"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star5" name="rating" value="5" />
                                <label for="star5" title="5 stars" data-rating="5"><i class="fas fa-star"></i></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comment_text">Your Review:</label>
                            <textarea id="comment_text" name="comment_text" rows="4" placeholder="Share your experience playing at Hermitage Golf Course..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Post Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p><a href="/login">Login</a> or <a href="/register">Register</a> to write a review</p>
                </div>
            <?php endif; ?>

            <div class="comments-container">
                <?php if (empty($comments)): ?>
                    <div class="review-card">
                        <p style="text-align: center; color: #666;">No reviews yet. Be the first to share your experience!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-name"><?php echo htmlspecialchars($comment['username']); ?></div>
                                <div class="review-date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                            </div>
                            <div class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $comment['rating']): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="review-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
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
                        <img src="/images/logos/logo.png" alt="Tennessee Golf Courses" class="footer-logo-image">
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
                        <li><a href="/courses">Golf Courses</a></li>
                        <li><a href="/reviews">Reviews</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/about">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="#">Nashville Area</a></li>
                        <li><a href="#">Chattanooga Area</a></li>
                        <li><a href="#">Knoxville Area</a></li>
                        <li><a href="#">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul>
                        <li><i class="fas fa-envelope"></i> info@tennesseegolfcourses.com</li>
                        <li><i class="fas fa-phone"></i> (615) 555-GOLF</li>
                        <li><i class="fas fa-map-marker-alt"></i> Nashville, TN</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/script.js?v=5"></script>
    <script>
        // Interactive star rating functionality for Hermitage
        document.addEventListener('DOMContentLoaded', function() {
            const ratingContainer = document.getElementById('hermitage-rating-stars');
            if (ratingContainer) {
                const stars = ratingContainer.querySelectorAll('label');
                const radioInputs = ratingContainer.querySelectorAll('input[type="radio"]');
                
                // Handle star hover
                stars.forEach((star, index) => {
                    star.addEventListener('mouseenter', function() {
                        highlightStars(index + 1);
                    });
                    
                    star.addEventListener('click', function() {
                        const rating = parseInt(star.getAttribute('data-rating'));
                        radioInputs[rating - 1].checked = true;
                        setActiveStars(rating);
                    });
                });
                
                // Handle container mouse leave
                ratingContainer.addEventListener('mouseleave', function() {
                    const checkedInput = ratingContainer.querySelector('input[type="radio"]:checked');
                    if (checkedInput) {
                        setActiveStars(parseInt(checkedInput.value));
                    } else {
                        clearStars();
                    }
                });
                
                function highlightStars(rating) {
                    stars.forEach((star, index) => {
                        if (index < rating) {
                            star.classList.add('active');
                        } else {
                            star.classList.remove('active');
                        }
                    });
                }
                
                function setActiveStars(rating) {
                    stars.forEach((star, index) => {
                        if (index < rating) {
                            star.classList.add('active');
                        } else {
                            star.classList.remove('active');
                        }
                    });
                }
                
                function clearStars() {
                    stars.forEach(star => {
                        star.classList.remove('active');
                    });
                }
            }
        });

        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Generate all 28 images (starting from 2.jpeg to 28.jpeg = 27 images)
            for (let i = 2; i <= 28; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/hermitage-golf-course/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/hermitage-golf-course/${i}.jpeg`, '_blank');
                galleryGrid.appendChild(galleryItem);
            }
            
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }
        
        function closeGallery() {
            const modal = document.getElementById('galleryModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore scrolling
        }
        
        // Close modal when clicking outside of it
        document.getElementById('galleryModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeGallery();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeGallery();
            }
        });
    </script>
</body>
</html>