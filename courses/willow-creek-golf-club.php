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
    'name' => 'Willow Creek Golf Club',
    'location' => 'Knoxville, TN',
    'description' => 'Championship 18-hole public course in Knoxville. Par 72, 7,211 yards of upscale golf with Champion Ultra Dwarf Bermuda greens.',
    'image' => '/images/courses/willow-creek-golf-club/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Bill Oliphant',
    'year_built' => 2003,
    'course_type' => 'Public'
];

SEO::setupCoursePage($course_data);

$course_slug = 'willow-creek-golf-club';
$course_name = 'Willow Creek Golf Club';

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
    <link rel="canonical" href="https://tennesseegolfcourses.com/courses/willow-creek-golf-club">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=5">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=5">
    
    <!-- Google Analytics -->
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
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/willow-creek-golf-club/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            margin-top: 20px;
        }
        
        .course-hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        
        .course-hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
        }
        
        .course-info {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .course-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 3rem;
            margin-top: 3rem;
        }
        
        .course-details h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2.2rem;
        }
        
        .course-details p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 1.5rem;
        }
        
        .course-sidebar {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: fit-content;
        }
        
        .course-info-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .course-info-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .course-info-card h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .course-specs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .spec-label {
            font-weight: 600;
            color: #2c5234;
        }
        
        .spec-value {
            color: #666;
        }
        
        .single-column {
            grid-template-columns: 1fr;
        }
        
        .location-info {
            text-align: left;
        }
        
        .location-details p {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .location-details i {
            color: #4a7c59;
            width: 16px;
        }
        
        .location-details a {
            color: #4a7c59;
            text-decoration: none;
        }
        
        .location-details a:hover {
            text-decoration: underline;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.8rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .amenity-item i {
            color: #4a7c59;
            font-size: 1.1rem;
        }
        
        .signature-holes {
            padding: 4rem 0;
            background: white;
        }
        
        .holes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .hole-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .hole-number {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #4a7c59;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .hole-details h4 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .hole-stats {
            display: flex;
            gap: 2rem;
            margin: 1rem 0;
        }
        
        .hole-stat {
            text-align: center;
        }
        
        .hole-stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.3rem;
        }
        
        .hole-stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #4a7c59;
        }
        
        .hole-details p {
            color: #555;
            line-height: 1.6;
            margin-top: 1rem;
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
        
        .comment-form-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .comment-form-container h3 {
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
            color: #2c5234;
        }
        
        .star-rating {
            display: flex;
            gap: 5px;
            margin-bottom: 1rem;
        }
        
        .star-rating input[type="radio"] {
            display: none;
        }
        
        .star-rating label {
            cursor: pointer;
            font-size: 1.5rem;
            color: #ddd;
            transition: color 0.3s ease;
        }
        
        .star-rating label:hover,
        .star-rating label.active {
            color: #ffd700;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
        }
        
        .btn-submit {
            background: #4a7c59;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .btn-submit:hover {
            background: #2c5234;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-layout {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .course-info-cards {
                grid-template-columns: 1fr;
                gap: 1.5rem;
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
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Willow Creek Golf Club</h1>
            <p>Upscale Championship Golf in West Knoxville</p>
            <div style="display: flex; gap: 20px; justify-content: center; margin-top: 20px;">
                <?php if ($avg_rating): ?>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="color: #ffd700; font-size: 1.2rem;">★</span>
                        <span style="font-weight: 600;"><?php echo $avg_rating; ?></span>
                        <span>(<?php echo $total_reviews; ?> reviews)</span>
                    </div>
                <?php endif; ?>
                <div style="display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Knoxville, TN</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Information -->
    <section class="course-info">
        <div class="container">
            <div class="course-layout">
                <div class="course-details">
                    <h2>Best Public Course in Knoxville</h2>
                    <p>Willow Creek Golf Club, designed by Bill Oliphant and opened in 1988, represents the pinnacle of public golf in West Knoxville. Nestled in the heart of the region with lots of hills and creeks, this championship 18-hole course offers an upscale country club experience accessible to all golfers.</p>
                    
                    <p>The course features Champion Ultra Dwarf Bermuda grass greens paired with Bermuda tees, fairways, and roughs, providing exceptional playing conditions year-round. Changes in elevation and several water carries create a challenging yet fair test that rewards strategic thinking and precise shot-making.</p>
                    
                    <p>Located at 12003 Kingston Pike, Willow Creek has established itself as the premier public golf destination in the Knoxville area, offering both recreational golfers and serious players an unforgettable championship golf experience in an upscale setting.</p>
                </div>
                
                <div class="course-sidebar">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; text-align: center;">Course Overview</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Bill Oliphant</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Year Built:</span>
                            <span class="spec-value">1988</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">7,211 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">74.7</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">124</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Greens:</span>
                            <span class="spec-value">Champion Ultra Dwarf</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Type:</span>
                            <span class="spec-value">Public</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="course-info-cards">
                <div class="course-info-card">
                    <h3><i class="fas fa-trophy"></i> Recognition</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Recognition:</span>
                            <span class="spec-value">Best Public Course</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Location:</span>
                            <span class="spec-value">Knoxville Area</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Style:</span>
                            <span class="spec-value">Championship Layout</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Terrain:</span>
                            <span class="spec-value">Hills & Water Features</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees & Booking</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Course Type:</span>
                            <span class="spec-value">Public</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Tee Times:</span>
                            <span class="spec-value">Available Online</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Walkable:</span>
                            <span class="spec-value">Cart Recommended</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Phone:</span>
                            <span class="spec-value">(865) 675-0100</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div class="location-info">
                        <div class="location-details">
                            <p><i class="fas fa-map-marker-alt"></i> 12003 Kingston Pike, Knoxville, TN 37934</p>
                            <p><i class="fas fa-phone"></i> (865) 675-0100</p>
                            <p><i class="fas fa-directions"></i> <a href="https://maps.google.com/maps?q=12003+Kingston+Pike,+Knoxville,+TN+37934" target="_blank">Get Directions</a></p>
                        </div>
                        <iframe src="https://maps.google.com/maps?q=12003+Kingston+Pike,+Knoxville,+TN+37934&t=&z=15&ie=UTF8&iwloc=&output=embed" 
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
                <h2>Championship Golf Experience</h2>
                <p>Willow Creek Golf Club delivers an upscale golfing experience that rivals private country clubs while remaining accessible to the public. The Bill Oliphant design makes excellent use of the natural West Knoxville terrain, incorporating elevation changes and creek crossings that demand strategic course management.</p>
                
                <p>The course's Champion Ultra Dwarf Bermuda greens are consistently rated among the finest in the region, providing true rolls and excellent putting surfaces year-round. <br>Water carries and elevation changes create memorable golf holes that challenge players of all skill levels. <br>The layout from the back tees plays over 7,200 yards, while multiple tee boxes ensure an enjoyable experience for golfers of every ability.</p>
                
                <p>Willow Creek's commitment to conditioning and customer service has earned it recognition as the best public course in Knoxville. The facility's comprehensive practice areas and professional instruction make it an ideal destination for both casual rounds and serious game improvement.</p>
            </div>
        </div>
    </section>

    <!-- Amenities -->
    <section style="padding: 4rem 0; background: #f8f9fa;">
        <div class="container">
            <div class="section-header">
                <h2>Course Amenities</h2>
                <p>Complete facilities for the ultimate golf experience</p>
            </div>
            <div class="amenities-grid">
                <div class="amenity-item">
                    <i class="fas fa-golf-ball"></i>
                    <span>Championship Golf Course</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-store"></i>
                    <span>Full-Service Pro Shop</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-utensils"></i>
                    <span>Bar & Grill</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Golf Lessons & Clinics</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-tools"></i>
                    <span>Club Repair Services</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-calendar-check"></i>
                    <span>Tournament Hosting</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-car"></i>
                    <span>Golf Cart Rentals</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-parking"></i>
                    <span>Ample Parking</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Willow Creek Golf Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/willow-creek-golf-club/2.jpeg" alt="Willow Creek Golf Club Knoxville TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/willow-creek-golf-club/3.jpeg" alt="Willow Creek Golf Club Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/willow-creek-golf-club/4.jpeg" alt="Willow Creek Golf Club Knoxville TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/willow-creek-golf-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Willow Creek Golf Club in Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/willow-creek-golf-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Willow Creek Golf Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/willow-creek-golf-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>


    </section>

    <!-- Full Gallery Modal -->