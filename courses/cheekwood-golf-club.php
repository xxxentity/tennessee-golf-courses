<?php
session_start();
require_once '../config/database.php';

$course_slug = 'cheekwood-golf-club';
$course_name = 'Cheekwood Golf Club';

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
    <title>Cheekwood Golf Club - Tennessee Golf Courses</title>
    <meta name="description" content="Cheekwood Golf Club - Executive 9-hole golf course in Franklin, TN. Par 33 layout with 4 par-4s, 4 par-3s, and 1 par-5 in beautifully wooded setting.">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/cheekwood-golf-club/1.jpeg');
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
        
        .course-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3rem;
            margin-bottom: 4rem;
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
            font-size: 1.5rem;
        }
        
        .course-specs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .course-specs.single-column {
            grid-template-columns: 1fr;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .spec-label {
            font-weight: 600;
            color: #666;
        }
        
        .spec-value {
            font-weight: 700;
            color: #2c5234;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
            justify-items: center;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .amenity-item i {
            color: #4a7c59;
            font-size: 1.2rem;
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
        
        .reviews-section {
            background: #f8f9fa;
            padding: 4rem 0;
        }
        
        .review-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
        
        .booking-section {
            background: linear-gradient(135deg, #2c5234, #4a7c59);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        
        .booking-content h2 {
            margin-bottom: 1rem;
        }
        
        .booking-content p {
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .booking-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-book {
            background: #ffd700;
            color: #2c5234;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-book:hover {
            background: #ffed4e;
            transform: translateY(-2px);
        }
        
        .btn-contact {
            background: transparent;
            color: white;
            padding: 1rem 2rem;
            border: 2px solid white;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-contact:hover {
            background: white;
            color: #2c5234;
        }
        
        /* Comment System Styles */
        .comment-form-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 3rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .comment-form-container h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
        }
        
        .comment-form .form-group {
            margin-bottom: 1.5rem;
        }
        
        .comment-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c5234;
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
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            background: #1e3f26;
            transform: translateY(-1px);
        }
        
        .login-prompt {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .login-prompt a {
            color: #2c5234;
            font-weight: 600;
            text-decoration: none;
        }
        
        .login-prompt a:hover {
            text-decoration: underline;
        }
        
        .no-comments {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .no-comments i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ddd;
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        /* Responsive Design for Course Info Grid */
        @media (max-width: 1024px) {
            .course-info-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .course-info-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .course-details {
                padding: 2rem 0;
            }
            
            .course-info-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Weather Bar -->
    <div class="weather-bar">
        <div class="weather-container">
            <div class="weather-info">
                <div class="current-time">
                    <i class="fas fa-clock"></i>
                    <span id="current-time">Loading...</span>
                </div>
                <div class="weather-widget">
                    <i class="fas fa-cloud-sun"></i>
                    <span id="weather-temp">Perfect Golf Weather</span>
                    <span class="weather-location">Nashville, TN</span>
                </div>
            </div>
            <div class="golf-conditions">
                <div class="condition-item">
                    <i class="fas fa-wind"></i>
                    <span>Wind: <span id="wind-speed">5 mph</span></span>
                </div>
                <div class="condition-item">
                    <i class="fas fa-eye"></i>
                    <span>Visibility: <span id="visibility">10 mi</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Cheekwood Golf Club</h1>
            <p>Executive 9-Hole Course • Franklin, Tennessee</p>
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
            <div class="course-info-grid">
                <div class="course-info-card">
                    <h3><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Holes:</span>
                            <span class="spec-value">9 (18)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">33 (66)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">2,623</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Bobby Greenwood</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">2015</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Public</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">9 - Holes</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">Weekday (Cart):</span>
                                <span class="spec-value">$30</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekend (Cart):</span>
                                <span class="spec-value">$33.25</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekday Senior (Cart):</span>
                                <span class="spec-value">$27</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekend Senior (Cart):</span>
                                <span class="spec-value">$27</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekday Junior (Cart):</span>
                                <span class="spec-value">$20</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekend Junior (Cart):</span>
                                <span class="spec-value">$25</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">18 - Holes</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">Weekday (Cart):</span>
                                <span class="spec-value">$47</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekend (Cart):</span>
                                <span class="spec-value">$54.15</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekday Senior (Cart):</span>
                                <span class="spec-value">$43</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekend Senior (Cart):</span>
                                <span class="spec-value">$43</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekday Junior (Cart):</span>
                                <span class="spec-value">$35</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekend Junior (Cart):</span>
                                <span class="spec-value">$45</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    285 Spencer Creek Rd<br>
                    Franklin, TN 37069</p>
                    
                    <p><strong>Phone:</strong><br>
                    (615) 794-8223</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://cheekwoodgolfclub.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">cheekwoodgolfclub.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=285+Spencer+Creek+Rd+Franklin+TN+37069&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Cheekwood Golf Club Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=8919+Harrison+Bay+Road,+Harrison,+TN+37341" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               style="font-size: 0.85rem; color: #4a7c59; text-decoration: none; font-weight: 500;">
                                <i class="fas fa-directions"></i> Get Directions
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="course-info-card">
                <h3><i class="fas fa-golf-ball"></i> About Cheekwood Golf Club</h3>
                <p>Cheekwood Golf Club offers an intimate executive golf experience nestled within Franklin's beautifully wooded residential landscape. This thoughtfully designed 9-hole layout features a challenging yet accessible par-33 composition with four par-4s, four par-3s, and one strategic par-5, creating the perfect environment for golfers seeking quality golf in a condensed format.</p>
                
                <br>
                
                <p>Located at 285 Spencer Creek Road, this executive course emphasizes precision and course management over distance, making it an ideal venue for sharpening your short game and developing strategic thinking.</p>
                
                <br>
                
                <p>The wooded setting provides natural beauty and challenge, with mature trees framing each hole and creating defined playing corridors that reward accuracy over distance.</p>
                
                <br>
                
                <p>What sets Cheekwood Golf Club apart is its unique approach to practice, featuring a compact short-game area with a 100-yard target green and an expansive putting surface adjacent to the clubhouse, allowing golfers to fine-tune their skills before tackling the course.<br><br></p>
                
                <p>The facility's commitment to accessibility and player development makes it a valuable addition to Middle Tennessee's diverse golf offerings, providing an excellent venue for both seasoned players and newcomers building confidence.<br><br></p>
                
                <p>Located in Franklin's beautifully wooded residential area, Cheekwood Golf Club provides a serene, park-like setting that showcases Middle Tennessee's natural beauty while offering an enjoyable and approachable golf experience for players of all skill levels.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Course Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-car"></i>
                        <span>Cart Rentals</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Short Practice Area</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-flag"></i>
                        <span>Putting Green</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Food & Beverages</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-user-tie"></i>
                        <span>Golf Instruction</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-golf-club"></i>
                        <span>Club Rentals</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Operation 36 Coaching</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Cheekwood Golf Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/cheekwood-golf-club/2.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/cheekwood-golf-club/3.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/cheekwood-golf-club/4.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/cheekwood-golf-club/5.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/cheekwood-golf-club/6.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/cheekwood-golf-club/7.jpeg');"></div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View All Photos (6+)</button>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Cheekwood Golf Club - Complete Photo Gallery</h2>
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
            <div class="section-header">
                <h2>What Golfers Are Saying</h2>
                <p>Read reviews from golfers who have experienced Bear Trace</p>
            </div>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Comment Form (Only for logged in users) -->
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container">
                    <h3>Share Your Experience</h3>
                    <form method="POST" class="comment-form">
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <div class="star-rating" id="bear-rating-stars">
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
                            <textarea id="comment_text" name="comment_text" rows="4" placeholder="Share your experience playing this course..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Post Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p><a href="../login.php">Login</a> or <a href="../register.php">Register</a> to share your review</p>
                </div>
            <?php endif; ?>
            
            <!-- Display Comments -->
            <div class="comments-container">
                <?php if (empty($comments)): ?>
                    <div class="no-comments">
                        <i class="fas fa-comments"></i>
                        <p>No reviews yet. Be the first to share your experience!</p>
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
                            <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Booking Section -->
    <section class="booking-section">
        <div class="container">
            <div class="booking-content">
                <h2>Ready to Play Bear Trace?</h2>
                <p>Book your tee time today and experience one of Tennessee's premier golf destinations</p>
                <div class="booking-buttons">
                    <a href="#" class="btn-book">Book Tee Time</a>
                    <a href="#" class="btn-contact">Contact Pro Shop</a>
                </div>
            </div>
        </div>
    </section>

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
                        <li><a href="../index.php#courses">Golf Courses</a></li>
                        <li><a href="../index.php#reviews">Reviews</a></li>
                        <li><a href="../index.php#news">News</a></li>
                        <li><a href="../index.php#about">About Us</a></li>
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
        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Generate all 103 images
            for (let i = 1; i <= 103; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/cheekwood-golf-club/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/cheekwood-golf-club/${i}.jpeg`, '_blank');
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
    <script>
        // Interactive star rating functionality for Bear Trace
        document.addEventListener('DOMContentLoaded', function() {
            const ratingContainer = document.getElementById('bear-rating-stars');
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
    </script>
</body>
</html>