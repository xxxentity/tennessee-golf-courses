<?php
session_start();
require_once '../config/database.php';

$course_slug = 'tpc-southwind';
$course_name = 'TPC Southwind';

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
    <title>TPC Southwind - Tennessee Golf Courses</title>
    <meta name="description" content="TPC Southwind - Ron Prichard designed championship golf course in Memphis, TN. Home of the FedEx St. Jude Championship with 7,244 yards of challenging golf.">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/tpc-southwind/1.jpeg');
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
        
        .location-info {
            margin-bottom: 2rem;
        }
        
        .location-info iframe {
            width: 100%;
            height: 200px;
            border: 0;
            border-radius: 8px;
            margin-bottom: 1rem;
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
        
        .course-description {
            background: #f8f9fa;
            padding: 3rem 0;
        }
        
        .description-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .description-content h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        
        .description-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 1.5rem;
        }
        
        .signature-holes {
            padding: 4rem 0;
        }
        
        .holes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .hole-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .hole-number {
            background: #2c5234;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }
        
        .hole-details h4 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .hole-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
        }
        
        .hole-stat {
            text-align: center;
        }
        
        .hole-stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .hole-stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c5234;
        }
        
        .reviews-section {
            padding: 4rem 0;
            background: #f8f9fa;
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
        
        .review-form {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .review-form h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
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
        
        .rating-input {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .rating-input input[type="radio"] {
            display: none;
        }
        
        .rating-input label {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .rating-input label:hover {
            color: #ffd700;
        }
        
        .rating-input label.active {
            color: #ffd700;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }
        
        .form-group textarea:focus {
            border-color: #2c5234;
            outline: none;
        }
        
        .submit-btn {
            background: #2c5234;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .submit-btn:hover {
            background: #1a3020;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: #e3f2fd;
            border-radius: 15px;
            margin-bottom: 3rem;
        }
        
        .login-prompt a {
            color: #2c5234;
            text-decoration: none;
            font-weight: 600;
        }
        
        .comments-list {
            space-y: 2rem;
        }
        
        .comment-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: #2c5234;
        }
        
        .comment-rating {
            color: #ffd700;
        }
        
        .comment-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .comment-text {
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
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-info-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .course-specs {
                grid-template-columns: 1fr;
            }
            
            .holes-grid {
                grid-template-columns: 1fr;
            }
            
            .hole-stats {
                gap: 1rem;
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
            <h1>TPC Southwind</h1>
            <p>Ron Prichard Design • Memphis, Tennessee</p>
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
                            <span class="spec-value">18</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">70</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">7,244 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">74.6</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">136</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Ron Prichard</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">1988</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Private</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Course Type:</span>
                            <span class="spec-value">Private Club</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Members:</span>
                            <span class="spec-value">Included in membership</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Guest Rates:</span>
                            <span class="spec-value">Contact club directly</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Reservations:</span>
                            <span class="spec-value">Members and guests only</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Contact:</span>
                            <span class="spec-value">(901) 748-0330</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div class="location-info">
                        <div class="location-details">
                            <p><i class="fas fa-map-marker-alt"></i> 3325 Club at Southwind, Memphis, TN 38125</p>
                            <p><i class="fas fa-phone"></i> (901) 748-0330</p>
                            <p><i class="fas fa-globe"></i> <a href="https://tpc.com/southwind" target="_blank">tpc.com/southwind</a></p>
                            <p><i class="fas fa-directions"></i> <a href="https://maps.google.com/maps?q=3325+Club+at+Southwind,+Memphis,+TN+38125" target="_blank">Get Directions</a></p>
                        </div>
                        <iframe src="https://maps.google.com/maps?q=3325+Club+at+Southwind,+Memphis,+TN+38125&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                                width="100%" height="200" style="border:0; border-radius: 8px; margin-top: 1rem;">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Description -->
    <section class="course-description">
        <div class="container">
            <div class="description-content">
                <h2>Championship Golf at TPC Southwind</h2>
                <p>TPC Southwind stands as Tennessee's premier championship golf course and the PGA Tour's only private club in the state. Home to the prestigious FedEx St. Jude Championship, this Ron Prichard masterpiece offers 7,244 yards of challenging golf that has tested the world's best players since 1988.</p>
                
                <p>Built on the site of a former dairy farm, TPC Southwind features undulating zoysia fairways, champion Bermuda greens, and numerous lakes, streams, and ponds. <br>With 94 bunkers and 10 water hazards, the course is considered one of the most challenging on the PGA Tour schedule. <br>Water comes into play on more than half the holes, creating a demanding test for golfers of all skill levels.</p>
                
                <p>The course's design philosophy balances playability with strategy, challenge with stunning beauty. Each hole was meticulously crafted to create a compelling test of golf that exhilarates and inspires amidst the unspoiled beauty of the Memphis countryside. Adding charm to the layout are two grain silos and a windmill, remnants of the dairy farm that once occupied the site.</p>
            </div>
        </div>
    </section>

    <!-- Signature Holes -->
    <section class="signature-holes">
        <div class="container">
            <div class="section-header">
                <h2>Signature Holes</h2>
                <p>Discover the holes that define TPC Southwind's championship character</p>
            </div>
            
            <div class="holes-grid">
                <div class="hole-card">
                    <div class="hole-number">11</div>
                    <div class="hole-details">
                        <h4>Island Green Challenge</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Par</div>
                                <div class="hole-stat-value">3</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">165</div>
                            </div>
                        </div>
                        <p>Reminiscent of TPC Sawgrass's famous 17th, this par-3 requires a precise short iron over water to a small island green. A pot bunker guards the front edge, making club selection crucial.</p>
                    </div>
                </div>
                
                <div class="hole-card">
                    <div class="hole-number">14</div>
                    <div class="hole-details">
                        <h4>The Toughest Par-3</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Par</div>
                                <div class="hole-stat-value">3</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">231</div>
                            </div>
                        </div>
                        <p>Consistently ranked as one of the toughest par-3s on the PGA Tour. This elevated tee shot requires a long iron or wood to a narrow, undulating green protected by water along the entire right side.</p>
                    </div>
                </div>
                
                <div class="hole-card">
                    <div class="hole-number">18</div>
                    <div class="hole-details">
                        <h4>Championship Finish</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Par</div>
                                <div class="hole-stat-value">4</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">461</div>
                            </div>
                        </div>
                        <p>A spectacular finishing hole with water protecting the entire left side of the fairway. The approach shot must carry over a lake that fronts the green, creating drama for tournament finishes.</p>
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
                <p>Experience the beauty of TPC Southwind</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/tpc-southwind/2.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/tpc-southwind/3.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/tpc-southwind/4.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/tpc-southwind/5.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/tpc-southwind/6.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/tpc-southwind/7.jpeg');"></div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View All Photos (44+)</button>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">TPC Southwind - Complete Photo Gallery</h2>
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
                <p>Share your experience at TPC Southwind</p>
            </div>

            <?php if ($is_logged_in): ?>
                <div class="review-form">
                    <h3>Write a Review</h3>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-error"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label>Rating</label>
                            <div class="rating-input" id="rating-stars">
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1" data-rating="1">★</label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2" data-rating="2">★</label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3" data-rating="3">★</label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4" data-rating="4">★</label>
                                <input type="radio" name="rating" value="5" id="star5">
                                <label for="star5" data-rating="5">★</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="comment_text">Your Review</label>
                            <textarea name="comment_text" id="comment_text" placeholder="Share your experience at TPC Southwind..." required></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">Submit Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p><a href="/login">Login</a> or <a href="/register">Register</a> to write a review</p>
                </div>
            <?php endif; ?>

            <div class="comments-list">
                <?php if (empty($comments)): ?>
                    <div class="comment-card">
                        <p style="text-align: center; color: #666;">No reviews yet. Be the first to share your experience!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <div class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></div>
                                <div class="comment-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $comment['rating']): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="comment-date"><?php echo date('F j, Y', strtotime($comment['created_at'])); ?></div>
                            <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
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
        // Interactive star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const ratingContainer = document.getElementById('rating-stars');
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
            
            // Generate all 45 images (starting from 2.jpeg to 45.jpeg = 44 images)
            for (let i = 2; i <= 45; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/tpc-southwind/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/tpc-southwind/${i}.jpeg`, '_blank');
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