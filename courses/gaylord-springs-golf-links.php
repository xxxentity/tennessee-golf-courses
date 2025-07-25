<?php
session_start();
require_once '../config/database.php';

$course_slug = 'gaylord-springs-golf-links';
$course_name = 'Gaylord Springs Golf Links';

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
    <title>Gaylord Springs Golf Links - Tennessee Golf Courses</title>
    <meta name="description" content="Gaylord Springs Golf Links - Larry Nelson designed Scottish links-style championship course in Nashville. Located at Gaylord Opryland Resort with award-winning facilities.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/gaylord-springs-golf-links/1.jpeg');
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
        
        .signature-holes {
            padding: 4rem 0;
            background: #f8f9fa;
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
            
            .holes-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
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
            <h1>Gaylord Springs Golf Links</h1>
            <p>Larry Nelson Design • Scottish Links Style • Nashville, Tennessee</p>
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
                    <h2>Scottish Links Golf in the Heart of Nashville</h2>
                    <p>Gaylord Springs Golf Links stands as one of Tennessee's most distinctive and celebrated golf courses, carved from the scenic banks of the Cumberland River. This Scottish links-style masterpiece, designed by former U.S. Open and PGA Champion Larry Nelson in 1991, offers an authentic links experience unlike any other in the Southeast.</p>
                    
                    <p>The course's unique design features limestone bluffs, federally protected wetlands, and rolling grass mounds that create a challenging yet fair test of golf. Nelson's vision was to bring the spirit of Scottish golf to Nashville, incorporating deep bunkers, water hazards, and undulating MiniVerde Bermuda grass greens that demand both creativity and precision.</p>
                    
                    <p>Located just minutes from Nashville International Airport and adjacent to the iconic Gaylord Opryland Resort, this championship layout has earned recognition from both <strong>Golf Digest</strong> and <strong>GolfWeek</strong> as one of the "Best Courses You Can Play." The course offers five sets of tees ranging from 5,179 to 6,842 yards, ensuring an enjoyable experience for golfers of all skill levels.</p>
                    
                    <p>The 43,000-square-foot antebellum-style clubhouse houses one of America's top golf shops and features a restaurant with spectacular course views. Combined with the extensive practice facilities and The Golf Institute's cutting-edge instruction technology, Gaylord Springs provides a complete world-class golf experience in Music City.</p>
                </div>
                
                <div class="course-sidebar">
                    <div class="course-info-card">
                        <h3><i class="fas fa-info-circle"></i> Course Information</h3>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">Holes:</span>
                                <span class="spec-value">18</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Par:</span>
                                <span class="spec-value">72</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Yardage:</span>
                                <span class="spec-value">6,842 yards</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Course Rating:</span>
                                <span class="spec-value">73.1</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Slope Rating:</span>
                                <span class="spec-value">133</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Designer:</span>
                                <span class="spec-value">Larry Nelson</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Opened:</span>
                                <span class="spec-value">1991</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Style:</span>
                                <span class="spec-value">Scottish Links</span>
                            </div>
                        </div>
                    </div>

                    <div class="course-info-card">
                        <h3><i class="fas fa-star"></i> Amenities</h3>
                        <ul class="amenities-list">
                            <li><i class="fas fa-building"></i> 43,000 sq ft Clubhouse</li>
                            <li><i class="fas fa-store"></i> Award-Winning Pro Shop</li>
                            <li><i class="fas fa-utensils"></i> Full-Service Restaurant</li>
                            <li><i class="fas fa-graduation-cap"></i> The Golf Institute</li>
                            <li><i class="fas fa-golf-ball"></i> 350-Yard Driving Range</li>
                            <li><i class="fas fa-flag"></i> Two Putting Greens</li>
                            <li><i class="fas fa-bullseye"></i> Two Chipping Greens</li>
                            <li><i class="fas fa-tools"></i> Club Fitting & Repair</li>
                            <li><i class="fas fa-camera"></i> SwingLab Technology</li>
                            <li><i class="fas fa-chart-line"></i> FlightScope Launch Monitor</li>
                            <li><i class="fas fa-users"></i> Event & Conference Space</li>
                            <li><i class="fas fa-bath"></i> State-of-the-Art Locker Room</li>
                        </ul>
                    </div>

                    <div class="course-info-card">
                        <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                        <div class="location-details">
                            <p><i class="fas fa-map-marker-alt"></i> 18 Springhouse Lane, Nashville, TN 37214</p>
                            <p><i class="fas fa-phone"></i> (615) 458-1730</p>
                            <p><i class="fas fa-globe"></i> <a href="https://www.gaylordsprings.com" target="_blank">gaylordsprings.com</a></p>
                            <p><i class="fas fa-directions"></i> <a href="https://maps.google.com/maps?q=18+Springhouse+Lane,+Nashville,+TN+37214" target="_blank">Get Directions</a></p>
                            <p><i class="fas fa-clock"></i> Daily 6:30 AM - 6:30 PM</p>
                            <p><i class="fas fa-credit-card"></i> Cashless Facility</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Signature Holes Section -->
    <section class="signature-holes">
        <div class="container">
            <div class="section-header">
                <h2>Signature Holes</h2>
                <p>Experience the most memorable holes on this Scottish links masterpiece</p>
            </div>
            
            <div class="holes-grid">
                <div class="hole-card">
                    <div class="hole-number">4</div>
                    <div class="hole-details">
                        <h4>The Springhouse</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Par</div>
                                <div class="hole-stat-value">4</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">400</div>
                            </div>
                        </div>
                        <p>The signature hole features a century-old springhouse from which the club derived its name. This strategic par-4 works around a picturesque lake, demanding precise placement off the tee and a carefully crafted approach to a well-protected green.</p>
                    </div>
                </div>
                
                <div class="hole-card">
                    <div class="hole-number">5</div>
                    <div class="hole-details">
                        <h4>The Challenge</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Par</div>
                                <div class="hole-stat-value">4</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">454</div>
                            </div>
                        </div>
                        <p>One of the course's most demanding holes, requiring a precise carry over a large lake off the tee, followed by a long approach shot into a heavily bunkered green. Strategic thinking and execution are essential for success.</p>
                    </div>
                </div>
                
                <div class="hole-card">
                    <div class="hole-number">18</div>
                    <div class="hole-details">
                        <h4>Cumberland Finale</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Par</div>
                                <div class="hole-stat-value">4</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">453</div>
                            </div>
                        </div>
                        <p>A dramatic finishing hole with the Cumberland River as backdrop. Water runs the entire right side, creating tension and excitement as players navigate this challenging closer with the magnificent clubhouse in view.</p>
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
                <p>Experience the beauty of Gaylord Springs Golf Links</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/gaylord-springs-golf-links/2.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/gaylord-springs-golf-links/3.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/gaylord-springs-golf-links/4.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/gaylord-springs-golf-links/5.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/gaylord-springs-golf-links/6.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/gaylord-springs-golf-links/7.jpeg');"></div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View All Photos (25+)</button>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Gaylord Springs Golf Links - Complete Photo Gallery</h2>
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
                <p>Share your experience at Gaylord Springs Golf Links</p>
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
                            <div class="star-rating" id="gaylord-rating-stars">
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
                            <textarea id="comment_text" name="comment_text" rows="4" placeholder="Share your experience playing at Gaylord Springs Golf Links..." required></textarea>
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
        // Interactive star rating functionality for Gaylord Springs
        document.addEventListener('DOMContentLoaded', function() {
            const ratingContainer = document.getElementById('gaylord-rating-stars');
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
            
            // Generate all 26 images (starting from 2.jpeg to 26.jpeg = 25 images)
            for (let i = 2; i <= 26; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/gaylord-springs-golf-links/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/gaylord-springs-golf-links/${i}.jpeg`, '_blank');
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