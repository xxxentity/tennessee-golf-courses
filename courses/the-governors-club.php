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
    'name' => 'The Governors Club',
    'location' => 'Brentwood, TN',
    'description' => 'Arnold Palmer Signature Design in exclusive Brentwood on historic Winstead farmland. Luxury private club with 7,031-yard championship course.',
    'image' => '/images/courses/the-governors-club/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Arnold Palmer',
    'year_built' => 1991,
    'course_type' => 'Private'
];

SEO::setupCoursePage($course_data);

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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/the-governors-club/1.jpeg');
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
            <h1>The Governors Club</h1>
            <p>Arnold Palmer Signature Design • Brentwood, Tennessee</p>
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
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">7,031 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">73.4</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">129</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Arnold Palmer</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">1993</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Private Club</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-crown"></i> Private Club Excellence</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Membership:</span>
                            <span class="spec-value">By Invitation</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Location:</span>
                            <span class="spec-value">Exclusive Brentwood</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Historic Estate:</span>
                            <span class="spec-value">1860s Winstead Farm</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Clubhouse:</span>
                            <span class="spec-value">Antebellum Mansion</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Dining:</span>
                            <span class="spec-value">Fine Cuisine</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Tennis:</span>
                            <span class="spec-value">Championship Courts</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Events:</span>
                            <span class="spec-value">Private Functions</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Contact:</span>
                            <span class="spec-value">(615) 370-0707</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    1500 Governors Club Drive<br>
                    Brentwood, TN 37027</p>
                    
                    <p><strong>Phone:</strong><br>
                    (615) 370-0707</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://www.thegovernorsclub.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">thegovernorsclub.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=1500+Governors+Club+Drive,+Brentwood,+TN+37027&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="The Governors Club Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=1500+Governors+Club+Drive,+Brentwood,+TN+37027" 
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
                <h3><i class="fas fa-golf-ball"></i> About The Governors Club</h3>
                <p>The Governors Club represents the pinnacle of luxury golf in the Nashville area, combining Arnold Palmer's legendary design expertise with the historic charm of the 1860s Winstead farmland. This exclusive Brentwood private club showcases Palmer's signature style across 7,031 masterfully crafted yards, where strategic shot-making and natural beauty converge to create an unforgettable golf experience.</p>
                
                <br>
                
                <p>Built on the grounds of a historic antebellum estate, The Governors Club seamlessly blends championship golf with Southern elegance. Palmer's design philosophy shines through in every hole, featuring generous fairways that reward accuracy, strategically placed hazards that demand precision, and immaculate greens that provide the ultimate test of putting skill.</p>
                
                <br>
                
                <p>The course's natural topography and mature trees create a park-like setting that enhances both the challenge and beauty of the layout. Each hole flows naturally through the rolling Tennessee terrain, offering members a diverse and engaging golf experience that has made The Governors Club a coveted destination for discerning golfers.</p>
                
                <br>
                
                <p>The club's centerpiece antebellum mansion serves as a stunning clubhouse, offering members and guests a glimpse into Tennessee's rich history while providing modern luxury amenities. From championship tennis courts to fine dining experiences, The Governors Club delivers an unparalleled private club experience that honors both Arnold Palmer's golf legacy and the timeless elegance of Southern hospitality.</p>
                
                <br>
                
                <p>As one of the most prestigious private clubs in the region, The Governors Club maintains an atmosphere of exclusivity and refinement. The membership enjoys access to world-class golf facilities, exceptional dining, and a rich social calendar that celebrates the traditions of private club life in one of Tennessee's most desirable communities.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Private Club Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Championship Golf</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-home"></i>
                        <span>Antebellum Clubhouse</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Fine Dining</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-wine-glass"></i>
                        <span>Members Bar</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-tennis-ball"></i>
                        <span>Tennis Courts</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-swimming-pool"></i>
                        <span>Pool & Aquatic</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-dumbbell"></i>
                        <span>Fitness Center</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Private Events</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-concierge-bell"></i>
                        <span>Concierge Service</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-user-tie"></i>
                        <span>Golf Instruction</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-car"></i>
                        <span>Valet Parking</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-users"></i>
                        <span>Social Events</span>
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
                <p>Experience the elegance of The Governors Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/the-governors-club/2.jpeg" alt="The Governors Club Brentwood TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-governors-club/3.jpeg" alt="The Governors Club Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-governors-club/4.jpeg" alt="The Governors Club Brentwood TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/the-governors-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out The Governors Club in Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/the-governors-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out The Governors Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/the-governors-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">The Governors Club Gallery</h2>
                <span class="close" onclick="closeGallery()">&times;</span>
            </div>
            <div class="full-gallery-grid">
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/1.jpeg');" alt="The Governors Club Brentwood TN - Signature hole with scenic water features and championship golf course design"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/2.jpeg');" alt="The Governors Club Brentwood TN - Panoramic fairway view with strategic bunkers and mature trees"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/3.jpeg');" alt="The Governors Club Brentwood TN - Championship golf course layout showing natural terrain"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/4.jpeg');" alt="The Governors Club Brentwood TN - Golf course entrance with professional landscaping and signage"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/5.jpeg');" alt="The Governors Club Brentwood TN - Challenging par 3 hole with water hazard and elevated green"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/6.jpeg');" alt="The Governors Club Brentwood TN - Scenic tee box view overlooking rolling fairway and forest"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/7.jpeg');" alt="The Governors Club Brentwood TN - Well-maintained putting green with flag and surrounding bunkers"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/8.jpeg');" alt="The Governors Club Brentwood TN - Dramatic dogleg hole with creek running alongside fairway"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/9.jpeg');" alt="The Governors Club Brentwood TN - Elevated tee shot over valley to distant green"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/10.jpeg');" alt="The Governors Club Brentwood TN - Pristine fairway bunkers and sand traps with raked patterns"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/11.jpeg');" alt="The Governors Club Brentwood TN - Golf course clubhouse exterior with architectural details and landscaping"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/12.jpeg');" alt="The Governors Club Brentwood TN - Multiple tee boxes showing course setup and yardage options"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/13.jpeg');" alt="The Governors Club Brentwood TN - Water feature and fountain near clubhouse entrance"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/14.jpeg');" alt="The Governors Club Brentwood TN - Long par 4 hole with tree-lined fairway and strategic bunkering"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/15.jpeg');" alt="The Governors Club Brentwood TN - Golf cart path winding through natural Tennessee landscape"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/16.jpeg');" alt="The Governors Club Brentwood TN - Practice facility with driving range and target greens"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/17.jpeg');" alt="The Governors Club Brentwood TN - Scenic bridge crossing over water hazard on golf course"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/18.jpeg');" alt="The Governors Club Brentwood TN - Championship finishing hole with gallery and spectator areas"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/19.jpeg');" alt="The Governors Club Brentwood TN - Golf course maintenance equipment and perfectly manicured grounds"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/20.jpeg');" alt="The Governors Club Brentwood TN - Early morning mist over fairway creating dramatic golf course atmosphere"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/21.jpeg');" alt="The Governors Club Brentwood TN - Golf course pro shop interior with merchandise and equipment displays"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/22.jpeg');" alt="The Governors Club Brentwood TN - Tournament setup with grandstands and professional golf event atmosphere"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/23.jpeg');" alt="The Governors Club Brentwood TN - Sunset view over golf course with golden hour lighting on greens"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/24.jpeg');" alt="The Governors Club Brentwood TN - Wildlife and natural habitat preserved throughout the golf course grounds"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/the-governors-club/25.jpeg');" alt="The Governors Club Brentwood TN - Award ceremony and trophy presentation at championship golf tournament"></div>
            </div>
        </div>
    </div>

    <script>
        // Gallery modal functionality
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

        // Star rating functionality for comment form
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating input[type="radio"]');
            const labels = document.querySelectorAll('.star-rating label');
            
            function updateStars(rating) {
                labels.forEach((label, index) => {
                    if (index < rating) {
                        label.classList.add('active');
                    } else {
                        label.classList.remove('active');
                    }
                });
            }
            
            labels.forEach((label, index) => {
                label.addEventListener('mouseover', () => updateStars(index + 1));
                label.addEventListener('click', () => {
                    stars[index].checked = true;
                    updateStars(index + 1);
                });
            });
            
            document.querySelector('.star-rating').addEventListener('mouseleave', () => {
                const checkedStar = document.querySelector('.star-rating input[type="radio"]:checked');
                if (checkedStar) {
                    const rating = Array.from(stars).indexOf(checkedStar) + 1;
                    updateStars(rating);
                } else {
                    updateStars(0);
                }
            });
        });
    </script>

    <?php include '../includes/footer.php'; ?>