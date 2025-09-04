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
    'name' => 'Windyke Country Club',
    'location' => 'Memphis, TN',
    'description' => 'Premier private golf facility in Memphis, TN featuring East and West championship courses plus Executive par-3 course. Family-oriented country club since 1962.',
    'image' => '/images/courses/windyke-country-club/1.webp',
    'holes' => 36,
    'par' => 72,
    'designer' => 'Classic Design',
    'year_built' => 1962,
    'course_type' => 'Private'
];

SEO::setupCoursePage($course_data);

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
            background: #f8f9fa;
            padding: 4rem 0;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-header h2 {
            color: #2c5234;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .section-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        
        .gallery-item {
            height: 250px;
            width: 100%;
            object-fit: cover;
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
            background: #ffffff;
            padding: 4rem 0;
        }
        
        .review-form {
            max-width: 600px;
            margin: 0 auto 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .review-form h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
        }
        
        .rating-input {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .star {
            font-size: 2rem;
            color: #999;
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
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
        }
        
        .form-group textarea:focus {
            outline: none;
            border-color: #4a7c59;
        }
        
        .submit-btn {
            background: #4a7c59;
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
            background: #2c5234;
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
            background: white;
            border-radius: 15px;
            margin: 0 auto;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .login-btn {
            display: inline-block;
            background: #4a7c59;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 1rem;
            transition: background 0.3s ease;
        }
        
        .login-btn:hover {
            background: #2c5234;
        }
        
        .comments-list {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .comment {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
            color: #333;
            line-height: 1.6;
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
        }
    </style>
</head>

<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Windyke Country Club</h1>
            <p>Premier Private Golf Facility • Memphis, Tennessee</p>
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
                            <span class="spec-label">Total Holes:</span>
                            <span class="spec-value">54</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">East Course:</span>
                            <span class="spec-value">Par 72, 7,211 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">West Course:</span>
                            <span class="spec-value">Par 72, 6,813 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Executive Course:</span>
                            <span class="spec-value">18-hole Par-3</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">John Frazier / William Amick</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">1962</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Private</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <p style="color: #666; margin-bottom: 1.5rem;">Guest fees and rates for play at Windyke Country Club. Private club membership available for full access.</p>
                    
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Weekend Guest Fee:</span>
                            <span class="spec-value">$91.53</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">9-Hole Weekday Guest Fee:</span>
                            <span class="spec-value">$49.43</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">18-Hole Weekday Guest Fee:</span>
                            <span class="spec-value">$75.00</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Cart Fee (18 Holes):</span>
                            <span class="spec-value">$20.00</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Cart Fee (9 Holes):</span>
                            <span class="spec-value">$12.00</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Range Ball Fee:</span>
                            <span class="spec-value">$7.50</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    8535 Winchester Road<br>
                    Memphis, TN 38125</p>
                    
                    <p><strong>Phone:</strong><br>
                    (901) 754-7273</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://www.windyke.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">windyke.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=8535+Winchester+Road,+Memphis,+TN+38125&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Windyke Country Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=8535+Winchester+Road,+Memphis,+TN+38125" 
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
            <div class="course-info-card" style="margin-bottom: 4rem;">
                <h3><i class="fas fa-golf-ball"></i> About Windyke Country Club</h3>
                
                <p>Windyke Country Club stands as Memphis's premier private golf facility, offering a truly exceptional experience since 1962. Founded by Mr. Dykema with the vision of creating a family-oriented country club that was affordable and inclusive rather than expensive and exclusive, Windyke has remained true to this philosophy for over six decades.</p>
                
                <br>
                
                <p>The club features two championship 18-hole courses - the East Course and West Course - plus an 18-hole Executive par-3 course designed to promote junior golf. Both championship courses feature Champion Bermuda grass greens and Tifway 419 Bermuda grass fairways, providing consistent playing conditions year-round.</p>
                
                <br>
                
                <p>The East Course offers a challenging 7,211 yards from the back tees with a course rating of 75.0 and slope rating of 134, making it one of Memphis's most challenging tests of golf. The West Course plays to 6,813 yards with a course rating of 72.6 and slope rating of 130, providing an excellent balance of challenge and playability for golfers of all skill levels.</p>
                
                <br>
                
                <p>What truly sets Windyke apart is its unique 54-hole layout and family-first atmosphere. The 18-hole Executive par-3 course serves as an ideal training ground for junior golfers and a perfect option for beginners or those seeking a quicker round. This thoughtful design reflects the club's commitment to developing the next generation of golfers.<br><br></p>
                
                <p>Originally designed by John Frazier with later enhancements by William Amick, the courses showcase mature tree-lined fairways and well-maintained playing surfaces that have hosted countless memorable rounds over the decades. The club's dedication to course conditioning and member satisfaction has made it a cornerstone of Memphis golf.<br><br></p>
                
                <p>Beyond golf, Windyke offers a complete country club experience with swimming facilities, tennis courts, fine dining, and a vibrant social calendar. With over 1,000 family members, the club maintains its founding principles of inclusivity and community while providing world-class amenities and service that rival any private club in the Mid-South region.</p>

                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>54 Holes of Golf</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-users"></i>
                        <span>Family-Oriented Club</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-swimming-pool"></i>
                        <span>Swimming Pool</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-table-tennis"></i>
                        <span>Tennis Courts</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Dining Facilities</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-store"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-trophy"></i>
                        <span>Championship Courses</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-child"></i>
                        <span>Junior-Friendly</span>
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
                <p>Experience the beauty of Windyke Country Club</p>
            </div>
            <div class="gallery-grid">
                <img src="../images/courses/windyke-country-club/2.webp" alt="Windyke Country Club Memphis, TN - Championship 36-hole golf course fairway with manicured Bermuda grass designed by Classic Design, Tennessee private golf course" class="gallery-item">
                <img src="../images/courses/windyke-country-club/3.webp" alt="Windyke Country Club Memphis, TN - Pristine putting green with strategic bunkers and mature landscaping, Private 36-hole Tennessee golf course" class="gallery-item">
                <img src="../images/courses/windyke-country-club/4.webp" alt="Windyke Country Club Memphis, TN - Scenic golf course view featuring Classic Design architectural design, premium Tennessee private golf course" class="gallery-item">
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>
    <!-- Share This Course Section -->
    <section class="share-course-section" style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="share-section" style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center;">
                <h3 class="share-title" style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Course</h3>
                <div class="share-buttons" style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/windyke-country-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Windyke Country Club in Memphis, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/windyke-country-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Windyke Country Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/windyke-country-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Windyke Country Club - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid">
                <?php for ($i = 1; $i <= 25; $i++): ?>
                    <div class="full-gallery-item" style="background-image: url('../images/courses/windyke-country-club/<?php echo $i; ?>.webp');"></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <div class="section-header">
                <h2>What Golfers Are Saying</h2>
                <p>Read reviews from golfers who have experienced Windyke Country Club</p>
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
                    <p style="text-align: center; color: #666; padding: 2rem;">No reviews yet. Be the first to share your experience!</p>
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

    <!-- Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Windyke Country Club Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid">
                <?php for ($i = 1; $i <= 25; $i++): ?>
                    <div class="full-gallery-item" style="background-image: url('../images/courses/windyke-country-club/<?php echo $i; ?>.webp');"></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

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

        // Gallery modal functions
        function openGallery() {
            document.getElementById('galleryModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('galleryModal');
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
    </script>
</body>
</html>