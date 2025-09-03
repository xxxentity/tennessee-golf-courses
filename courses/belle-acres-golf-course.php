<?php
session_start();
require_once '../config/database.php';
require_once '../includes/csrf.php';
require_once '../includes/seo.php';

// Course data for SEO
$course_data = [
    'name' => 'Belle Acres Golf Course',
    'location' => 'Cookeville, TN',
    'description' => 'Cookeville\'s oldest golf course since 1930. Dr. J.P. Terry designed 9-hole par 35 course with bent grass greens and largest practice facility in the area.',
    'image' => '/images/courses/belle-acres-golf-course/1.jpeg',
    'holes' => 9,
    'par' => 35,
    'designer' => 'Dr. J.P. Terry',
    'year_built' => 1930,
    'course_type' => 'Public'
];

SEO::setupCoursePage($course_data);

$course_slug = 'belle-acres-golf-course';
$course_name = 'Belle Acres Golf Course';

// Check if user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    // Validate CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!CSRFProtection::validateToken($csrf_token)) {
        $error_message = 'Security token expired or invalid. Please refresh the page and try again.';
    } else {
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
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero" style="
        height: 60vh; 
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/belle-acres-golf-course/1.jpeg'); 
        background-size: cover; 
        background-position: center; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        text-align: center; 
        color: white;
        margin-top: 20px;
    ">
        <div class="course-hero-content" style="max-width: 800px; padding: 2rem;">
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Belle Acres Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Dr. J.P. Terry Design • Cookeville, Tennessee</p>
            <div class="course-rating" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
                <?php if ($avg_rating !== null && $total_reviews > 0): ?>
                    <div class="rating-stars" style="color: #ffd700; font-size: 1.5rem;">
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
                    <span class="rating-text" style="font-size: 1.2rem; font-weight: 600;"><?php echo $avg_rating; ?> / 5.0 (<?php echo $total_reviews; ?> review<?php echo $total_reviews !== 1 ? 's' : ''; ?>)</span>
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
    <section class="course-details" style="padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="course-info-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem; margin-bottom: 4rem;">
                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs single-column" style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Holes:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">9</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Par:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">35</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Yardage:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">2,934</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Designer:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Dr. J.P. Terry</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Opened:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">1930</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Type:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Public</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div class="pricing-section">
                        <div class="pricing-grid" style="display: grid; gap: 1.5rem;">
                            <div class="pricing-category" style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #2c5234;">
                                <h4 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">Weekdays (Mon-Fri)</h4>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #e5e7eb;">
                                    <span>9 Holes w/ Cart</span>
                                    <span>$21.00</span>
                                </div>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #e5e7eb;">
                                    <span>9 Holes Walking</span>
                                    <span>$10.50</span>
                                </div>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #e5e7eb;">
                                    <span>18 Holes w/ Cart</span>
                                    <span>$29.40</span>
                                </div>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0;">
                                    <span>18 Holes Walking</span>
                                    <span>$16.80</span>
                                </div>
                            </div>
                            
                            <div class="pricing-category" style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #2c5234;">
                                <h4 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">Weekends & Holidays</h4>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #e5e7eb;">
                                    <span>9 Holes w/ Cart</span>
                                    <span>$23.10</span>
                                </div>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #e5e7eb;">
                                    <span>9 Holes Walking</span>
                                    <span>$12.60</span>
                                </div>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #e5e7eb;">
                                    <span>18 Holes w/ Cart</span>
                                    <span>$32.55</span>
                                </div>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0;">
                                    <span>18 Holes Walking</span>
                                    <span>$19.95</span>
                                </div>
                            </div>
                        </div>
                        <div class="pricing-note" style="font-style: italic; color: #666; font-size: 0.9rem; margin-top: 1rem; text-align: center;">
                            Senior (55+), Military & Tech discounts available • Call (931) 310-0645
                        </div>
                    </div>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div class="course-specs single-column" style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Address:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">901 E Broad St</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">City:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Cookeville, TN</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Phone:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">(931) 310-0645</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Website:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;"><a href="https://belleacresgolf.com" target="_blank" style="color: #2c5234;">Visit Site</a></span>
                        </div>
                    </div>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=901+E+Broad+St,+Cookeville,+TN&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Belle Acres Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=901+E+Broad+St,+Cookeville,+TN" 
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
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Belle Acres Golf Course</h3>
                <p>Belle Acres Golf Course holds the distinction of being Cookeville's oldest golf course, having served the community since May 1, 1930. Built by Dr. J.P. Terry with assistance from Arnold Mears, a professional from Richland Golf Course in Nashville, this historic 9-hole course has evolved significantly from its humble beginnings with sand greens to become a modern golfing facility with bent grass greens.</p>
                
                <br>
                
                <p>The course originally opened with just 7 holes featuring sand greens, but within a year was expanded to 9 holes. Over the decades, the playing surfaces have undergone several transformations - from sand greens to cotton-seed hulls, then to Bermuda grass (which presented crabgrass challenges), and finally to the current bent grass greens that provide excellent playing conditions year-round.</p>
                
                <br>
                
                <p>Today's Belle Acres offers golfers a challenging yet accessible 9-hole, par-35 experience stretching 2,934 yards from the championship tees. The course features three different tee options to accommodate players of all skill levels, with slope ratings ranging from 109 to 111. What truly sets Belle Acres apart is its practice facilities - boasting the largest practice facility in the Cookeville area, making it an ideal destination for both casual rounds and serious practice sessions.</p>
                
                <br>
                
                <p>The course maintains a strong community focus with its popular weekly A-B-C-D Scramble every Tuesday at 5:00 PM, where golfers can sign up in person by 4:30 PM and compete for prizes. This tradition exemplifies Belle Acres' commitment to fostering local golf culture while providing an affordable, quality golf experience in the heart of Tennessee.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Course Amenities</h3>
                <div class="amenities-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; justify-items: center;">
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>9-Hole Course</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-bullseye" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Largest Practice Facility</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-leaf" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Bent Grass Greens</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-shopping-cart" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Cart Rentals</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-users" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Weekly Scrambles</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-history" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Historic Course (1930)</span>
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
                <p>Experience the beauty of Belle Acres Golf Course</p>
            </div>
            <div class="gallery-grid">
                <img src="../images/courses/belle-acres-golf-course/2.jpeg" alt="Belle Acres Golf Course Cookeville, TN - Championship 9-hole golf course fairway with manicured Bermuda grass designed by Dr. J.P. Terry, Tennessee public golf course" class="gallery-item">
                <img src="../images/courses/belle-acres-golf-course/3.jpeg" alt="Belle Acres Golf Course Cookeville, TN - Pristine putting green with strategic bunkers and mature landscaping, Public 9-hole Tennessee golf course" class="gallery-item">
                <img src="../images/courses/belle-acres-golf-course/4.jpeg" alt="Belle Acres Golf Course Cookeville, TN - Scenic golf course view featuring Dr. J.P. Terry architectural design, premium Tennessee public golf course" class="gallery-item">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/belle-acres-golf-course'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Belle Acres Golf Course'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/belle-acres-golf-course'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Belle Acres Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/belle-acres-golf-course'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section" style="background: #f8f9fa; padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <h2 style="text-align: center; margin-bottom: 3rem; color: #2c5234;">Course Reviews</h2>
            
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 3rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Share Your Experience</h3>
                    
                    <?php if (isset($success_message)): ?>
                        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #c3e6cb;"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #f5c6cb;"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="comment-form">
                        <?php echo CSRFProtection::getTokenField(); ?>
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Rating:</label>
                            <div class="star-rating" style="display: flex; gap: 5px;">
                                <input type="radio" name="rating" value="5" id="star5" style="display: none;">
                                <label for="star5" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                                <input type="radio" name="rating" value="4" id="star4" style="display: none;">
                                <label for="star4" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                                <input type="radio" name="rating" value="3" id="star3" style="display: none;">
                                <label for="star3" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                                <input type="radio" name="rating" value="2" id="star2" style="display: none;">
                                <label for="star2" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                                <input type="radio" name="rating" value="1" id="star1" style="display: none;">
                                <label for="star1" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                            </div>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Review:</label>
                            <textarea name="comment_text" placeholder="Share your thoughts about Belle Acres Golf Course..." required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit; resize: vertical; min-height: 100px;"></textarea>
                        </div>
                        <button type="submit" style="background: #2c5234; color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Post Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 15px; text-align: center; margin-bottom: 3rem;">
                    <p><a href="../login.php" style="color: #2c5234; font-weight: 600; text-decoration: none;">Log in</a> to share your review of Belle Acres Golf Course</p>
                </div>
            <?php endif; ?>
            
            <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                    <div style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <div style="font-weight: 600; color: #2c5234;"><?php echo htmlspecialchars($comment['username']); ?></div>
                            <div style="color: #666; font-size: 0.9rem;"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                        </div>
                        <div style="color: #ffd700; margin-bottom: 1rem;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star" style="color: <?php echo $i <= $comment['rating'] ? '#ffd700' : '#ddd'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <h3>No reviews yet</h3>
                    <p>Be the first to share your experience at Belle Acres Golf Course!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Belle Acres Golf Course - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Dynamic Footer -->
    <?php include '../includes/footer.php'; ?>
    
    <script>
        // Star rating functionality
        document.querySelectorAll('.star-rating input[type="radio"]').forEach((radio) => {
            radio.addEventListener('change', function() {
                const stars = document.querySelectorAll('.star-rating label');
                stars.forEach((star, starIndex) => {
                    if (starIndex >= (5 - this.value)) {
                        star.style.color = '#ffd700';
                    } else {
                        star.style.color = '#ddd';
                    }
                });
            });
        });
        
        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Generate all 25 images
            for (let i = 1; i <= 25; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/belle-acres-golf-course/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/belle-acres-golf-course/${i}.jpeg`, '_blank');
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