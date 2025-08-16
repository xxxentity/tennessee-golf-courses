<?php
session_start();
require_once '../config/database.php';

$course_slug = 'signal-mountain-golf-country-club';
$course_name = 'Signal Mountain Golf & Country Club';

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
    <title>Signal Mountain Golf & Country Club - Tennessee Golf Courses</title>
    <meta name="description" content="Signal Mountain Golf & Country Club - Historic Donald Ross design from 1922 in Signal Mountain, TN. Private club with championship 6,148-yard layout and spectacular mountain views.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=3">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=3">
    
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/signal-mountain-golf-country-club/1.webp');
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
            font-size: 1.25rem;
            opacity: 0.95;
        }
        
        .course-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .course-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }
        
        .rating-stars {
            display: flex;
            gap: 0.25rem;
            color: #ffd700;
            font-size: 1.25rem;
        }
        
        .rating-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .rating-number {
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .rating-count {
            color: #666;
        }
        
        .quick-info {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            font-size: 1rem;
            color: #666;
        }
        
        .quick-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .course-sections {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .course-info-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .course-info-card h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .course-specs {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .spec-label {
            font-weight: 600;
            color: #2c5234;
        }
        
        .spec-value {
            color: #666;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .amenity-item i {
            color: #4a7c59;
            font-size: 1.25rem;
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
        
        /* Comments Section */
        .comments-section {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .comments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .comments-header h2 {
            color: #2c5234;
            font-size: 2rem;
            margin: 0;
        }
        
        .review-summary {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .review-rating {
            font-size: 3rem;
            font-weight: 700;
            color: #2c5234;
        }
        
        .review-stars {
            display: flex;
            gap: 0.25rem;
            color: #ffd700;
            font-size: 1.5rem;
        }
        
        .review-count {
            color: #666;
            margin-top: 0.5rem;
        }
        
        .comment-item {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .comment-item:last-child {
            border-bottom: none;
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
        
        .comment-date {
            color: #666;
            font-size: 0.875rem;
        }
        
        .comment-rating {
            display: flex;
            gap: 0.25rem;
            color: #ffd700;
            margin-bottom: 0.5rem;
        }
        
        .comment-text {
            color: #333;
            line-height: 1.6;
        }
        
        /* Comment Form */
        .comment-form-container {
            background: #f8f9fa;
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
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-sections {
                grid-template-columns: 1fr;
            }
            
            .amenities-grid {
                grid-template-columns: 1fr;
            }
            
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            
            .full-gallery-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>
    
    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Signal Mountain Golf & Country Club</h1>
            <p>Historic Mountain Golf Since 1922</p>
        </div>
    </section>
    
    <!-- Course Information -->
    <section class="course-details">
        <div class="course-container">
            <!-- Course Header -->
            <div class="course-header">
                <div class="rating-info">
                    <?php if ($avg_rating): ?>
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                                <?php if ($i <= floor($avg_rating)): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif ($i - 0.5 <= $avg_rating): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-number"><?php echo $avg_rating; ?></span>
                        <span class="rating-count">(<?php echo $total_reviews; ?> reviews)</span>
                    <?php else: ?>
                        <span class="rating-count">No reviews yet</span>
                    <?php endif; ?>
                </div>
                
                <div class="quick-info">
                    <div class="quick-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Signal Mountain, TN</span>
                    </div>
                    <div class="quick-info-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>18 Holes</span>
                    </div>
                    <div class="quick-info-item">
                        <i class="fas fa-ruler"></i>
                        <span>6,148 Yards</span>
                    </div>
                    <div class="quick-info-item">
                        <i class="fas fa-flag"></i>
                        <span>Par 71</span>
                    </div>
                </div>
            </div>
            
            <!-- Course Sections Grid -->
            <div class="course-sections">
                <div class="course-info-card">
                    <h3><i class="fas fa-info-circle"></i> Course Details</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">71</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">6,148 (Gold Tees)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">70.1</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">132</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Donald Ross</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">1922</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Private Club</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-users"></i> Membership</h3>
                    <div style="background: linear-gradient(135deg, #8B4513, #A0522D); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; margin: 1rem 0;">
                        <h4 style="margin-bottom: 0.5rem; font-size: 1.2rem;">Private Members Only</h4>
                        <p style="margin: 0; opacity: 0.9;">Exclusive club membership required</p>
                    </div>
                    <p style="text-align: center; color: #666; margin-top: 1rem;">
                        Signal Mountain Golf & Country Club operates as an exclusive private club serving Chattanooga families since 1922. 
                        Contact the club directly for membership information and guest policies.
                    </p>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    809 James Boulevard<br>
                    Signal Mountain, TN 37377</p>
                    
                    <p><strong>Phone:</strong><br>
                    (423) 886-5767</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://smgcc.org" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">smgcc.org</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=809+James+Boulevard,+Signal+Mountain,+TN+37377&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Signal Mountain Golf & Country Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=809+James+Boulevard,+Signal+Mountain,+TN+37377" 
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
                <h3><i class="fas fa-golf-ball"></i> About Signal Mountain Golf & Country Club</h3>
                <p>Signal Mountain Golf & Country Club stands as one of Tennessee's most historic and cherished golf venues, designed by the legendary Donald Ross in 1922. This private mountain retreat, located less than 20 minutes from downtown Chattanooga, has served as a recreational haven for generations of golfers, maintaining its classic design principles while adapting to modern standards of excellence.</p>
                
                <br>
                
                <p>The championship layout stretches to 6,148 yards from the gold tees, but don't let the modest yardage deceive you – this Donald Ross gem is famously described as "short in length but stout in challenge." The course features bent grass greens and natural mountain slopes, creating a playing experience that demands precision over power. The layout remains largely free of residential development, preserving the pure golf experience that Ross envisioned over a century ago.</p>
                
                <br>
                
                <p>Signal Mountain's most notorious test comes at the par-3 7th hole, widely regarded as "the hardest par 3 in Chattanooga." The course showcases Ross's genius through strategic bunkering, tiered greens, and sloping fairways that reward thoughtful shot placement. With course ratings ranging from 66.0 to 70.1 and slope ratings from 117 to 132, the layout provides appropriate challenges for golfers of all skill levels across its four tee options.</p>
                
                <br>
                
                <p>The back nine offers spectacular mountain views, particularly at the 16th hole, which provides "the prettiest view on the golf course." The closing stretch tests every aspect of your game, with the 18th hole standing as the longest par 4 on the course, playing significantly uphill to a well-protected green. Throughout the round, elevation changes require careful club selection and local knowledge to score well.</p>
                
                <br>
                
                <p>As host of the prestigious Signal Mountain Invitational, the club maintains its position as one of the region's premier competitive venues. This PGA-ranked amateur tournament attracts some of the South's best collegiate and amateur players, showcasing the course's championship pedigree. The club has also hosted numerous USGA qualifiers and regional championships throughout its storied history.</p>
                
                <br>
                
                <p>Beyond golf, Signal Mountain Golf & Country Club offers a complete family-oriented recreational experience. The facility features a heated swimming pool open from Memorial Day through September, multiple dining options, and extensive banquet facilities. With membership requiring sponsorship from two existing members, the club maintains its intimate, neighborhood atmosphere while providing amenities that rival much larger facilities, creating a true mountain retreat for its fortunate members.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Club Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Championship Golf Course</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-swimming-pool"></i>
                        <span>Heated Pool</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Multiple Dining Options</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Event Facilities</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-mountain"></i>
                        <span>Mountain Views</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-trophy"></i>
                        <span>Tournament Host</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-leaf"></i>
                        <span>Bent Grass Greens</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-users"></i>
                        <span>Family Activities</span>
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
                <p>Experience the beauty of Signal Mountain Golf & Country Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/signal-mountain-golf-country-club/2.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/signal-mountain-golf-country-club/3.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/signal-mountain-golf-country-club/4.webp');"></div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Signal Mountain Golf & Country Club - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
            </div>
        </div>
    </div>
    
    <!-- Comments Section -->
    <section class="comments-section-wrapper">
        <div class="container">
            <div class="comments-section">
                <div class="comments-header">
                    <h2>Course Reviews</h2>
                </div>
                
                <?php if (!empty($comments)): ?>
                    <div class="review-summary">
                        <div>
                            <div class="review-rating"><?php echo $avg_rating; ?></div>
                            <div class="review-stars">
                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                    <?php if ($i <= floor($avg_rating)): ?>
                                        <i class="fas fa-star"></i>
                                    <?php elseif ($i - 0.5 <= $avg_rating): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="review-count"><?php echo $total_reviews; ?> Reviews</div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($is_logged_in): ?>
                    <div class="comment-form-container">
                        <h3>Write a Review</h3>
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-error"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" class="comment-form">
                            <div class="form-group">
                                <label>Rating</label>
                                <div class="star-rating">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                                        <label for="star<?php echo $i; ?>" class="star">★</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="comment_text">Your Review</label>
                                <textarea id="comment_text" name="comment_text" rows="4" placeholder="Share your experience at this course..." required></textarea>
                            </div>
                            
                            <button type="submit" class="btn-submit">Post Review</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt">
                        <p><a href="/login">Login</a> or <a href="/register">Register</a> to leave a review</p>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($comments)): ?>
                    <div class="comments-list">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item">
                                <div class="comment-header">
                                    <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                    <span class="comment-date"><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></span>
                                </div>
                                <div class="comment-rating">
                                    <?php for ($i = 1; $i <= 3; $i++): ?>
                                        <?php if ($i <= $comment['rating']): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="comment-text">
                                    <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-comments">
                        <p>No reviews yet. Be the first to review this course!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
    
    <script>
        // Gallery functionality
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const grid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            grid.innerHTML = '';
            
            // Load all 25 images
            for (let i = 1; i <= 25; i++) {
                const item = document.createElement('div');
                item.className = 'full-gallery-item';
                item.style.backgroundImage = `url('../images/courses/signal-mountain-golf-country-club/${i}.webp')`;
                grid.appendChild(item);
            }
            
            modal.style.display = 'block';
        }
        
        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('galleryModal');
            if (event.target == modal) {
                closeGallery();
            }
        }
        
        // Star rating functionality
        document.querySelectorAll('.star-rating label').forEach((star, index, stars) => {
            star.addEventListener('click', () => {
                stars.forEach((s, i) => {
                    if (i >= stars.length - index - 1) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
