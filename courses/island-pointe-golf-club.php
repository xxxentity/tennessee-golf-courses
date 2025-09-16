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
    'name' => 'Island Pointe Golf Club',
    'location' => 'Kodak, TN',
    'description' => 'Experience Island Pointe Golf Club, an Arthur Hills masterpiece in Kodak. Championship links-style golf with French Broad River island holes and Smoky Mountain views.',
    'image' => '/images/courses/island-pointe-golf-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Arthur Hills',
    'year_built' => 2000,
    'course_type' => 'Public'
];

SEO::setupCoursePage($course_data);

$course_slug = 'island-pointe-golf-club';
$course_name = 'Island Pointe Golf Club';

// Calculate rating data for header display
try {
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM course_comments WHERE course_slug = ? AND parent_comment_id IS NULL AND rating IS NOT NULL");
    $stmt->execute([$course_slug]);
    $rating_data = $stmt->fetch();
    $avg_rating = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : null;
    $total_reviews = $rating_data['total_reviews'] ?: 0;
} catch (PDOException $e) {
    $avg_rating = null;
    $total_reviews = 0;
}

// Check if user is logged in using secure session
$is_logged_in = SecureSession::isLoggedIn();

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    // Validate CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!CSRFToken::validateToken($csrf_token)) {
        $error_message = 'Security token expired or invalid. Please refresh the page and try again.';
    } else {
        $rating = (float)$_POST['rating'];
        $comment_text = trim($_POST['comment_text']);
        $user_id = SecureSession::get('user_id');

        if ($rating >= 1 && $rating <= 5 && !empty($comment_text)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO course_comments (user_id, course_slug, course_name, rating, comment_text) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $course_slug, $course_name, $rating, $comment_text]);
                header("Location: " . $_SERVER['REQUEST_URI'] . "?success=1");
                exit;
            } catch (PDOException $e) {
                $error_message = "Error posting review. Please try again.";
            }
        } else {
            $error_message = "Please provide a valid rating and comment.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo SEO::generateMetaTags(); ?>
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
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/island-pointe-golf-club/1.jpeg');
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
            <h1>Island Pointe Golf Club</h1>
            <p>Championship Links Golf on the French Broad River</p>
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
                    <span>Kodak, TN</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Information -->
    <section class="course-info">
        <div class="container">
            <div class="course-layout">
                <div class="course-details">
                    <h2>Arthur Hills Links Masterpiece</h2>
                    <p>Island Pointe Golf Club, formerly known as River Islands, is an Arthur Hills masterpiece that opened in 1991. Spectacularly set on over 175 acres between Knoxville and the Great Smoky Mountains, this championship layout offers the most challenging public golf experience in East Tennessee.</p>
                    
                    <p>The course features a true links-style design that travels along the edge and over the French Broad River, with three unique island holes in the middle of the river providing unparalleled golf experiences. The layout showcases beautiful Zoysia fairways, often regarded as some of the best in the area, paired with smooth mini-verdi Bermuda greens.</p>
                    
                    <p>With breathtaking views and over seven and a half miles of cart paths, Island Pointe combines natural beauty with championship golf design. The course plays 7,001 yards from the back tees with a challenging 146 slope rating, while offering a more manageable 6,300 yards from the regular tees for recreational golfers.</p>
                </div>
                
                <div class="course-sidebar">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; text-align: center;">Course Overview</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Arthur Hills</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Year Built:</span>
                            <span class="spec-value">1991</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">Championship</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">7,001 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">74.3</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">146</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Fairways:</span>
                            <span class="spec-value">Zoysia</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Greens:</span>
                            <span class="spec-value">Mini-Verdi Bermuda</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="course-info-cards">
                <div class="course-info-card">
                    <h3><i class="fas fa-trophy"></i> Recognition</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Ranking:</span>
                            <span class="spec-value">10th in TN Public/Private</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Challenge Level:</span>
                            <span class="spec-value">Most Challenging in East TN</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Style:</span>
                            <span class="spec-value">True Links Design</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Unique Feature:</span>
                            <span class="spec-value">3 Island Holes</span>
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
                            <span class="spec-label">Weekday Rates:</span>
                            <span class="spec-value">Under $50</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Cart Paths:</span>
                            <span class="spec-value">7.5+ Miles</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Dress Code:</span>
                            <span class="spec-value">Proper Golf Attire</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div class="location-info">
                        <div class="location-details">
                            <p><i class="fas fa-map-marker-alt"></i> 9610 Kodak Road, Kodak, TN 37764</p>
                            <p><i class="fas fa-phone"></i> (865) 933-4653</p>
                            <p><i class="fas fa-globe"></i> <a href="https://www.islandpointegc.com" target="_blank">islandpointegc.com</a></p>
                            <p><i class="fas fa-directions"></i> <a href="https://maps.google.com/maps?q=9610+Kodak+Road,+Kodak,+TN+37764" target="_blank">Get Directions</a></p>
                        </div>
                        <iframe src="https://maps.google.com/maps?q=9610+Kodak+Road,+Kodak,+TN+37764&t=&z=15&ie=UTF8&iwloc=&output=embed" 
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
                <h2>Championship Links Experience</h2>
                <p>Island Pointe Golf Club stands as East Tennessee's most challenging public golf course, offering a true links-style experience rarely found in the region. The Arthur Hills design takes full advantage of the spectacular French Broad River setting, creating a course that is both visually stunning and strategically demanding.</p>
                
                <p>The course's signature feature is its three island holes that literally play over and around islands in the French Broad River, creating unforgettable golf moments. <br>The Zoysia fairways are consistently rated among the finest in Tennessee, providing exceptional lies and playing conditions. <br>From the championship tees at 7,001 yards with a 146 slope rating, the course presents a formidable challenge for even the most accomplished golfers.</p>
                
                <p>Ideally positioned between Knoxville and the Great Smoky Mountains, Island Pointe offers not just exceptional golf but breathtaking scenery. The course's extensive practice facilities and quality PGA instruction make it an ideal destination for golfers seeking to improve their game while experiencing championship-caliber golf.</p>
            </div>
        </div>
    </section>

    <!-- Signature Holes -->
    <section class="signature-holes">
        <div class="container">
            <div class="section-header">
                <h2>Signature Island Holes</h2>
                <p>Experience golf's ultimate risk-reward challenges over the French Broad River</p>
            </div>
            
            <div class="holes-grid">
                <div class="hole-card">
                    <div class="hole-number">Island</div>
                    <div class="hole-details">
                        <h4>River Island Challenge</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Unique</div>
                                <div class="hole-stat-value">3</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Islands</div>
                                <div class="hole-stat-value">River</div>
                            </div>
                        </div>
                        <p>Three spectacular island holes in the middle of the French Broad River create the most unique golf experience in Tennessee. These holes demand precision and nerve as golfers navigate water carries to reach greens situated on actual river islands.</p>
                    </div>
                </div>
                
                <div class="hole-card">
                    <div class="hole-number">Links</div>
                    <div class="hole-details">
                        <h4>True Links Design</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Style</div>
                                <div class="hole-stat-value">Links</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Acres</div>
                                <div class="hole-stat-value">175+</div>
                            </div>
                        </div>
                        <p>The course features authentic links-style design elements rarely found in Tennessee, with strategic bunkering, firm playing conditions, and wind factors that create an ever-changing challenge for golfers of all skill levels.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Amenities -->
    <section style="padding: 4rem 0; background: #f8f9fa;">
        <div class="container">
            <div class="section-header">
                <h2>Course Amenities</h2>
                <p>Comprehensive facilities for the complete golf experience</p>
            </div>
            <div class="amenities-grid">
                <div class="amenity-item">
                    <i class="fas fa-golf-ball"></i>
                    <span>Championship Golf Course</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Extensive Practice Facilities</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Quality PGA Instruction</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-utensils"></i>
                    <span>Newly Remodeled Bar & Grill</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-road"></i>
                    <span>7.5+ Miles of Cart Paths</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-water"></i>
                    <span>French Broad River Views</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-mountain"></i>
                    <span>Smoky Mountain Proximity</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-calendar-check"></i>
                    <span>Tournament Hosting</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Island Pointe Golf Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/island-pointe-golf-club/2.jpeg" alt="Island Pointe Golf Club Kodak TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/island-pointe-golf-club/3.jpeg" alt="Island Pointe Golf Club Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/island-pointe-golf-club/4.jpeg" alt="Island Pointe Golf Club Kodak TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/island-pointe-golf-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Island Pointe Golf Club in Kodak, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/island-pointe-golf-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Island Pointe Golf Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/island-pointe-golf-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Island Pointe Golf Club - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Reviews Section - Centralized System -->
    <?php include '../includes/course-reviews-fixed.php'; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="../images/logos/logo.webp" alt="Tennessee Golf Courses" class="footer-logo-image">
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
                        <li><a href="../courses">Golf Courses</a></li>
                        <li><a href="../about">About Us</a></li>
                        <li><a href="../contact">Contact</a></li>
                        <li><a href="../privacy-policy">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Golf Courses</h4>
                    <ul>
                        <li><a href="../courses/bear-trace-harrison-bay">Bear Trace</a></li>
                        <li><a href="../courses/gaylord-springs-golf-links">Gaylord Springs</a></li>
                        <li><a href="../courses/hermitage-golf-course">Hermitage</a></li>
                        <li><a href="../courses/holston-hills-country-club">Holston Hills</a></li>
                        <li><a href="../courses/island-pointe-golf-club">Island Pointe</a></li>
                        <li><a href="../courses/tpc-southwind">TPC Southwind</a></li>
                        <li><a href="../courses/willow-creek-golf-club">Willow Creek</a></li>
                    </ul>
                </div>
                <div class="footer-section">    
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-of-service">Terms of Service</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../script.js?v=5"></script>
    <script>
        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Generate all 25 images (starting from 2.jpeg to 25.jpeg = 24 images)
            for (let i = 2; i <= 25; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/island-pointe-golf-club/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/island-pointe-golf-club/${i}.jpeg`, '_blank');
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
