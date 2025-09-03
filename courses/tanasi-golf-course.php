<?php
require_once '../includes/session-security.php';
require_once '../config/database.php';
require_once '../includes/csrf.php';
require_once '../includes/seo.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - user not logged in
}

// Course data for SEO
$course_data = [
    'name' => 'Tanasi Golf Course',
    'location' => 'Tellico Village, TN',
    'description' => 'Ault, Clark & Associates design at Tellico Village, TN. Championship 6,715-yard layout with Tellico Lake views and challenging water carries.',
    'image' => '/images/courses/tanasi-golf-course/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Ault, Clark & Associates',
    'year_built' => 1986,
    'course_type' => 'Resort'
];

SEO::setupCoursePage($course_data);

$course_slug = 'tanasi-golf-course';
$course_name = 'Tanasi Golf Course';

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
    <?php echo SEO::generateMetaTags(); ?>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=5">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=5">
    
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/tanasi-golf-course/1.webp');
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
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Tanasi Golf Course</h1>
            <p>Ault, Clark & Associates Design • Tellico Village, Tennessee</p>
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
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">6,715 (Black Tees)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">72.6</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">138</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Ault, Clark & Associates</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">1996</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Resort/Private</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">2025 Daily Fee Rates</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">18-Hole w/Lease Cart:</span>
                                <span class="spec-value">$51.25</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">18-Hole w/Private Cart:</span>
                                <span class="spec-value">$47.50</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole w/Lease Cart:</span>
                                <span class="spec-value">$31.00</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole w/Private Cart:</span>
                                <span class="spec-value">$28.75</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">2025 Member Rates</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">18-Hole w/Lease Cart:</span>
                                <span class="spec-value">$48.75</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">18-Hole w/Private Cart:</span>
                                <span class="spec-value">$45.25</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole w/Lease Cart:</span>
                                <span class="spec-value">$29.00</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole w/Private Cart:</span>
                                <span class="spec-value">$27.75</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">Guest Rates</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">18-Hole with Member:</span>
                                <span class="spec-value">$62.00</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole with Member:</span>
                                <span class="spec-value">$38.25</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">18-Hole without Member:</span>
                                <span class="spec-value">$82.50</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole without Member:</span>
                                <span class="spec-value">$46.50</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                        <p style="margin: 0; font-size: 0.9rem; color: #666;"><strong>PrePaid packages available with near 5% savings</strong><br>
                        Reservations: Chelsea Reservations<br>
                        Pro Shop: (865) 458-4707</p>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    450 Clubhouse Point<br>
                    Loudon, TN 37774</p>
                    
                    <p><strong>Pro Shop:</strong><br>
                    (865) 458-4707</p>
                    
                    <p><strong>Clubhouse:</strong><br>
                    (865) 458-9392</p>
                    
                    <p><strong>Head Professional:</strong><br>
                    Lucas Forstrom</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://golftellicovillage.com/tanasi-golf-course/" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">golftellicovillage.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=450+Clubhouse+Point,+Loudon,+TN+37774&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Tanasi Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=450+Clubhouse+Point,+Loudon,+TN+37774" 
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
                <h3><i class="fas fa-golf-ball"></i> About Tanasi Golf Course</h3>
                <p>Tanasi Golf Course is a distinguished championship layout designed by the renowned architectural firm Ault, Clark & Associates that opened in 1996 as the second of three exceptional courses at Tellico Village in Loudon, Tennessee. This prestigious design firm, known for creating America's greatest courses including Congressional Country Club (Blue), Tropicana Country Club, and Sawgrass Country Club, brought their championship pedigree to the scenic Tennessee mountains.</p>
                
                <br>
                
                <p>The challenging 6,715-yard par-72 course from the black tees features a dramatic design philosophy that differs significantly from Tellico Village's other courses, with a more open layout complemented by expansive greens and strategic water features. The course showcases Bermuda fairways and premium bent grass greens, providing year-round playability and consistent championship conditions throughout the seasons.</p>
                
                <br>
                
                <p>What sets Tanasi Golf Course apart is its spectacular integration with Tellico Lake, particularly on the signature stretch of holes 8, 9, and 10, where the lake comes into play directly off the tee. These holes offer some of the most scenic and challenging golf experiences in East Tennessee, with dramatic water carries that test both courage and precision while rewarding successful execution with breathtaking views of the surrounding mountain landscape.</p>
                
                <br>
                
                <p>The course design masterfully utilizes seven different tee options ranging from 6,715 yards down to 4,486 yards, ensuring an enjoyable and appropriately challenging experience for golfers of all skill levels. The wide fairways and large landing areas provide strategic options for course management, while the undulating greens demand precise approach shots and thoughtful putting to score well.</p>
                
                <br>
                
                <p>Tanasi Golf Course winds elegantly through the upscale Tanasi neighborhood within Tellico Village, offering players a resort-quality golf experience enhanced by the community's comprehensive amenities. The course's clubhouse features a full-service restaurant and wrap-around porch that provides stunning panoramic views of both the golf course and Tellico Lake, creating an ideal setting for post-round dining and relaxation.</p>
                
                <br>
                
                <p>As part of the world-class Tellico Village golf complex, Tanasi Golf Course represents the pinnacle of mountain golf design and conditioning. The facility's commitment to excellence extends beyond the course itself, with professional staff led by Head Golf Professional Lucas Forstrom and Director of Golf Maintenance Wells McClure ensuring every aspect of the golf experience meets the highest standards expected at one of Tennessee's premier residential golf communities.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Course Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-bullseye"></i>
                        <span>Expansive Putting Green</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-home"></i>
                        <span>Full Service Clubhouse</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Clubhouse Restaurant</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-water"></i>
                        <span>Lake Views</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-building-columns"></i>
                        <span>Wrap-Around Porch</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-user-tie"></i>
                        <span>PGA Professional</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Online Reservations</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-car"></i>
                        <span>Cart Included</span>
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
                <p>Experience the beauty of Tanasi Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/tanasi-golf-course/2.webp" alt="Tanasi Golf Course Tellico Village TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/tanasi-golf-course/3.webp" alt="Tanasi Golf Course Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/tanasi-golf-course/4.webp" alt="Tanasi Golf Course Tellico Village TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>    <!-- Share This Course Section -->
    <section class="share-course-section" style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="share-section" style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center;">
                <h3 class="share-title" style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Course</h3>
                <div class="share-buttons" style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/tanasi-golf-course'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Tanasi Golf Course in Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/tanasi-golf-course'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Tanasi Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/tanasi-golf-course'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>


    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Tanasi Golf Course - Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div id="fullGalleryGrid" class="full-gallery-grid">
                <!-- Gallery items will be dynamically generated -->
            </div>
        </div>
    </div>

    <script>
        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Alt text patterns for different image types
            function getAltText(imageIndex) {
                const courseName = 'Tanasi Golf Course';
                const location = 'Tellico Village, TN';
                const locationShort = 'Tellico Village TN';
                
                if (imageIndex <= 5) {
                    // Course overview shots
                    const overviewTexts = [
                        `${courseName} ${location} - Aerial view of championship 18-hole golf course showing signature holes and clubhouse facilities`,
                        `${courseName} ${locationShort} - Panoramic fairway view hole 12 with strategic bunkers and mature trees`,
                        `${courseName} Tennessee - Championship golf course layout showing championship layout and natural terrain`,
                        `${courseName} ${locationShort} - Championship golf course entrance with professional landscaping and signage`,
                        `${courseName} ${location} - Golf course overview showing scenic terrain and championship facilities`
                    ];
                    return overviewTexts[imageIndex - 1];
                } else if (imageIndex <= 10) {
                    // Signature holes
                    const holes = [6, 8, 12, 15, 18];
                    const holeIndex = imageIndex - 6;
                    const holeNum = holes[holeIndex];
                    const signatures = [
                        `${courseName} Tennessee golf course - Signature par 3 hole ${holeNum} with water hazard and bentgrass green`,
                        `${courseName} ${locationShort} - Challenging par 4 hole ${holeNum} with scenic views and strategic bunkering`,
                        `${courseName} Tennessee - Par 5 hole ${holeNum} with risk-reward layout and elevated green complex`,
                        `${courseName} ${location} - Signature hole ${holeNum} featuring championship design and natural beauty`,
                        `${courseName} Tennessee - Finishing hole ${holeNum} with dramatic approach shot and clubhouse backdrop`
                    ];
                    return signatures[holeIndex];
                } else if (imageIndex <= 15) {
                    // Greens and approaches
                    return `${courseName} ${locationShort} - Undulating putting green with championship pin positions and bentgrass surface - Image ${imageIndex}`;
                } else if (imageIndex <= 20) {
                    // Course features
                    const features = [
                        'Practice facility driving range and putting green area',
                        'Golf cart fleet and maintenance facilities',
                        'Professional golf instruction area and practice tees',
                        'Course landscaping with native Tennessee flora and water features',
                        'Golf course pro shop and equipment rental facilities'
                    ];
                    return `${courseName} Tennessee - ${features[(imageIndex - 16) % features.length]}`;
                } else {
                    // Clubhouse and amenities
                    const amenities = [
                        'Golf course clubhouse pro shop and restaurant facilities',
                        'Clubhouse dining room with scenic Tennessee views',
                        'Golf course event space and meeting facilities',
                        'Professional locker room and amenities',
                        'Golf course entrance and parking facilities'
                    ];
                    return `${courseName} ${location} - ${amenities[(imageIndex - 21) % amenities.length]}`;
                }
            }
            
            // Generate all 25 images
            for (let i = 1; i <= 25; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.innerHTML = `<img src="../images/courses/tanasi-golf-course/${i}.webp" alt="${getAltText(i)}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">`;
                galleryItem.onclick = () => window.open(`../images/courses/tanasi-golf-course/${i}.webp`, '_blank');
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
        window.onclick = function(event) {
            const modal = document.getElementById('galleryModal');
            if (event.target === modal) {
                closeGallery();
            }
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeGallery();
            }
        });
    </script>