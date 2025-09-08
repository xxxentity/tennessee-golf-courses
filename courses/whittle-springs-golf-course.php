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
    'name' => 'Whittle Springs Golf Course',
    'location' => 'Knoxville, TN',
    'description' => 'Knoxville\'s historic municipal golf course since 1932. Experience the unique bunker-free layout designed by Morton and Sweetser.',
    'image' => '/images/courses/whittle-springs-golf-course/1.webp',
    'holes' => 18,
    'par' => 71,
    'designer' => 'Morton & Sweetser',
    'year_built' => 1932,
    'course_type' => 'Public'
];

SEO::setupCoursePage($course_data);

$course_slug = 'whittle-springs-golf-course';
$course_name = 'Whittle Springs Golf Course';

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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/whittle-springs-golf-course/1.jpeg');
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
            color: #999;
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
            color: #999;
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
            <h1>Whittle Springs Golf Course</h1>
            <p>Knoxville's First Public Course • Since 1932</p>
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
                        <i class="fas fa-star-o" style="color: #999; margin-right: 8px;"></i>
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
                            <span class="spec-value">5,729</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Morton & Sweetser</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">1932</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Municipal Public</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">Weekday:</span>
                                <span class="spec-value">$34</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekend:</span>
                                <span class="spec-value">$39</span>
                            </div>
                        </div>
                    </div>
                    <p style="text-align: center; color: #666; margin-top: 1rem;">
                        <i class="fas fa-phone" style="color: #4a7c59; margin-right: 0.5rem;"></i>
                        Call (865) 525-1022 for tee times and current rates
                    </p>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    3113 Valley View Dr<br>
                    Knoxville, TN 37917</p>
                    
                    <p><strong>Phone:</strong><br>
                    (865) 525-1022</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://www.golfwhittlesprings.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">golfwhittlesprings.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=3113+Valley+View+Dr,+Knoxville,+TN+37917&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Whittle Springs Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=3113+Valley+View+Dr,+Knoxville,+TN+37917" 
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
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Whittle Springs Golf Course</h3>
                <p>Whittle Springs Golf Course holds the distinguished honor of being Knoxville's first public golf course, opening its doors in 1932. Designed by Morton and Sweetser, this historic municipal course has evolved over 90+ years while maintaining its unique character as a bunker-free layout that challenges golfers with strategic play.</p>
                
                <br>
                
                <p>What makes Whittle Springs truly distinctive is its wide-open design philosophy with no sand bunkers. At 5,729 yards and par 70, the course may appear forgiving, but the challenge lies in the small, fast greens that demand precision and exceptional putting skills from golfers of all abilities.</p>
                
                <br>
                
                <p>The course's storied history includes its origins as part of a historic resort featuring a grand hotel and swimming pool. During its golden era, Whittle Springs hosted events with golf legends including Ben Hogan, Byron Nelson, and Dow Finsterwald, securing its place in Tennessee golf history.</p>
                
                <br>
                
                <p>Today, Whittle Springs continues to serve golfers as Knoxville's historic municipal course, home to the City Amateur Championship since the 1930s. The course offers affordable public golf while preserving its historic character and maintaining its tradition as Tennessee's golf pioneer.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Club Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>18-Hole Course</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Golf Lessons</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Restaurant</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Tournaments</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-users"></i>
                        <span>Public Access</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
                <h2 style="color: #2c5234; font-size: 2.5rem; margin-bottom: 1rem;">Course Gallery</h2>
                <p style="color: #666; font-size: 1.1rem;">Experience the historic beauty of Whittle Springs Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/whittle-springs-golf-course/2.jpeg" alt="Whittle Springs Golf Course Knoxville TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/whittle-springs-golf-course/3.jpeg" alt="Whittle Springs Golf Course Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/whittle-springs-golf-course/4.jpeg" alt="Whittle Springs Golf Course Knoxville TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/whittle-springs-golf-course'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Whittle Springs Golf Course in Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/whittle-springs-golf-course'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Whittle Springs Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/whittle-springs-golf-course'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Whittle Springs Golf Course - Knoxville, TN</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid">
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/1.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/1.jpeg', '_blank')" title="Whittle Springs Golf Course - Course Overview"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/2.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/2.jpeg', '_blank')" title="Whittle Springs Golf Course - Fairway View"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/3.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/3.jpeg', '_blank')" title="Whittle Springs Golf Course - Course Layout"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/4.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/4.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 4"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/5.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/5.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 5"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/6.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/6.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 6"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/7.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/7.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 7"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/8.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/8.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 8"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/9.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/9.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 9"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/10.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/10.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 10"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/11.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/11.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 11"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/12.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/12.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 12"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/13.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/13.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 13"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/14.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/14.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 14"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/15.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/15.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 15"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/16.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/16.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 16"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/17.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/17.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 17"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/18.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/18.jpeg', '_blank')" title="Whittle Springs Golf Course - Hole 18"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/19.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/19.jpeg', '_blank')" title="Whittle Springs Golf Course - Clubhouse"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/20.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/20.jpeg', '_blank')" title="Whittle Springs Golf Course - Practice Facilities"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/21.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/21.jpeg', '_blank')" title="Whittle Springs Golf Course - Pro Shop"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/22.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/22.jpeg', '_blank')" title="Whittle Springs Golf Course - Driving Range"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/23.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/23.jpeg', '_blank')" title="Whittle Springs Golf Course - Putting Green"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/24.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/24.jpeg', '_blank')" title="Whittle Springs Golf Course - Course Scenic View"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/whittle-springs-golf-course/25.jpeg')" onclick="window.open('../images/courses/whittle-springs-golf-course/25.jpeg', '_blank')" title="Whittle Springs Golf Course - Course Panoramic View"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script>
        function openGallery() {
            document.getElementById('galleryModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the content
        window.onclick = function(event) {
            var modal = document.getElementById('galleryModal');
            if (event.target == modal) {
                closeGallery();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeGallery();
            }
        });

        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating label');
            const inputs = document.querySelectorAll('.star-rating input');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = inputs.length - index;
                    inputs[inputs.length - 1 - index].checked = true;
                    
                    // Update visual state
                    stars.forEach((s, i) => {
                        if (i >= inputs.length - rating) {
                            s.style.color = '#ffd700';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
                
                star.addEventListener('mouseenter', function() {
                    const rating = inputs.length - index;
                    stars.forEach((s, i) => {
                        if (i >= inputs.length - rating) {
                            s.style.color = '#ffd700';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
            });
            
            document.querySelector('.star-rating').addEventListener('mouseleave', function() {
                const checkedInput = document.querySelector('.star-rating input:checked');
                if (checkedInput) {
                    const rating = parseInt(checkedInput.value);
                    stars.forEach((s, i) => {
                        if (i >= inputs.length - rating) {
                            s.style.color = '#ffd700';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                } else {
                    stars.forEach(s => s.style.color = '#ddd');
                }
            });
        });
    </script>
</body>
</html>