<?php
session_start();
require_once '../config/database.php';

$course_slug = 'windtree-golf-course';
$course_name = 'Windtree Golf Course';

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
    <title>Windtree Golf Course - Tennessee Golf Courses (Permanently Closed)</title>
    <meta name="description" content="Windtree Golf Course - John LaFoy designed course from 1991 in Mount Juliet, TN. Course permanently closed in 2017 but information preserved for historical reference.">
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
        /* Course Closure Modal Styles */
        .course-modal {
            display: flex;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            text-align: center;
            position: relative;
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 2rem;
            color: #999;
            cursor: pointer;
            font-weight: bold;
        }
        
        .close-modal:hover {
            color: #333;
        }
        
        .modal-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        
        .modal-title {
            font-size: 1.8rem;
            color: #dc3545;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .modal-text {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .modal-button {
            background: #dc3545;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .modal-button:hover {
            background: #c82333;
        }
        
        /* Course page styling modifications for closed course */
        .course-hero {
            position: relative;
        }
        
        .course-hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }
        
        .course-hero-content {
            position: relative;
            z-index: 2;
        }
        
        .closed-banner {
            background: #dc3545;
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            position: relative;
            z-index: 3;
        }
        
        /* Gallery Styles */
        .photo-gallery {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .photo-gallery .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-header h2 {
            font-size: 2.5rem;
            color: #2c5234;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .section-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .gallery-item {
            height: 250px;
            background-size: cover;
            background-position: center;
            border-radius: 15px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
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
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-gallery:hover {
            background: #2c5234;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Gallery Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            overflow-y: auto;
        }
        
        .modal .modal-content {
            background-color: white;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 15px;
            max-width: 1200px;
            width: 95%;
            position: relative;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
        }
        
        .modal-header .modal-title {
            font-size: 1.8rem;
            color: #2c5234;
            margin: 0;
            font-weight: 700;
        }
        
        .close {
            background: none;
            border: none;
            font-size: 2rem;
            font-weight: bold;
            color: #999;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }
        
        .close:hover {
            color: #333;
        }
        
        .full-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .full-gallery-item {
            height: 200px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .full-gallery-item:hover {
            transform: scale(1.03);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .gallery-item {
                height: 200px;
            }
            
            .full-gallery-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 0.5rem;
            }
            
            .full-gallery-item {
                height: 150px;
            }
            
            .modal .modal-content {
                margin: 1rem auto;
                padding: 1rem;
                width: 98%;
            }
            
            .modal-header .modal-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <!-- Course Closure Modal -->
    <div id="closureModal" class="course-modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <div class="modal-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2 class="modal-title">Course Permanently Closed</h2>
            <p class="modal-text">
                Windtree Golf Course permanently closed in November 2017 after a failed residential development proposal. 
                This page is maintained for historical reference and to preserve the memory of this course that served the Mount Juliet golf community for 27 years.
            </p>
            <button class="modal-button" onclick="closeModal()">Continue to Historical Information</button>
        </div>
    </div>

    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Closure Banner -->
    <div class="closed-banner">
        <i class="fas fa-exclamation-triangle"></i> 
        PERMANENTLY CLOSED - This page is maintained for historical reference only
    </div>

    <!-- Course Hero Section -->
    <section class="course-hero" style="
        height: 60vh; 
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/windtree-golf-course/1.jpeg'); 
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Windtree Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">John LaFoy Design • Mount Juliet, Tennessee</p>
            <div style="background: rgba(220, 53, 69, 0.9); padding: 1rem; border-radius: 10px; margin-top: 2rem;">
                <p style="margin: 0; font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-times-circle"></i> Permanently Closed 2017
                </p>
                <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; opacity: 0.9;">
                    Historical Information Preserved (1991-2017)
                </p>
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
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">18</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Par:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">71</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Yardage:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">6,557</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Designer:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">John LaFoy</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Opened:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">1991</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Status:</span>
                            <span class="spec-value" style="font-weight: 700; color: #dc3545;">Closed 2017</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-times-circle"></i> Closure Information</h3>
                    <div style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; margin: 1rem 0;">
                        <h4 style="margin-bottom: 0.5rem; font-size: 1.2rem;">Permanently Closed</h4>
                        <p style="margin: 0; opacity: 0.9;">As of 2017</p>
                    </div>
                    <p style="text-align: center; color: #666; margin-top: 1rem;">
                        Windtree Golf Course closed permanently in November 2017 following a failed residential development proposal. 
                        The course served the Mount Juliet community for 27 years before economic challenges and development pressures led to its closure.
                    </p>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Historical Location</h3>
                    <div class="course-specs single-column" style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Address:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">810 Nonaville Rd</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">City:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Mount Juliet, TN 37122</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Phone:</span>
                            <span class="spec-value" style="font-weight: 700; color: #dc3545;">(615) 754-4653 (Disconnected)</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Slope Rating:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">122</span>
                        </div>
                    </div>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=810+Nonaville+Rd,+Mount+Juliet,+TN+37122&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Former Windtree Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=810+Nonaville+Rd,+Mount+Juliet,+TN+37122" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               style="font-size: 0.85rem; color: #4a7c59; text-decoration: none; font-weight: 500;">
                                <i class="fas fa-directions"></i> View Former Location
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Windtree Golf Course (Historical)</h3>
                <p>Windtree Golf Course served the Mount Juliet and Nashville area golf community from 1991 to 2017, offering 27 years of championship golf on a well-designed John LaFoy layout. Located on Nonaville Road, the course provided an accessible daily-fee golf experience that challenged players with its strategic design and well-maintained playing conditions.</p>
                
                <br>
                
                <p>The 18-hole, par-71 championship course stretched 6,557 yards and featured a slope rating of 122, indicating a course that demanded precision and strategic thinking from golfers of all skill levels. LaFoy's design incorporated the natural terrain of Middle Tennessee, creating a layout that was both challenging and fair, with multiple tee options to accommodate players of varying abilities.</p>
                
                <br>
                
                <p>During its operational years, Windtree Golf Course was known for its commitment to providing quality golf at reasonable rates, making it a popular choice for both casual golfers and those seeking to improve their game. The course featured traditional amenities including a pro shop, practice facilities, and food service, creating a complete golf experience for its patrons.</p>
                
                <br>
                
                <p>The course permanently closed in November 2017 following economic challenges and a failed residential development proposal. A plan to build 351 single-family homes on the property (Windtree Pines) was voted down by the Mt. Juliet City Commission, citing traffic concerns. The closure reflected broader industry trends, as the saturation of golf courses built in the 1990s led to numerous course closures nationwide due to operational costs and insufficient growth in new golfers.</p>
            </div>

            <!-- Former Amenities -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-history"></i> Former Amenities</h3>
                <div class="amenities-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; justify-items: center;">
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; opacity: 0.7;">
                        <i class="fas fa-golf-ball" style="color: #666; font-size: 1.2rem;"></i>
                        <span>Championship Golf</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; opacity: 0.7;">
                        <i class="fas fa-store" style="color: #666; font-size: 1.2rem;"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; opacity: 0.7;">
                        <i class="fas fa-bullseye" style="color: #666; font-size: 1.2rem;"></i>
                        <span>Driving Range</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; opacity: 0.7;">
                        <i class="fas fa-circle" style="color: #666; font-size: 1.2rem;"></i>
                        <span>Putting Green</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; opacity: 0.7;">
                        <i class="fas fa-utensils" style="color: #666; font-size: 1.2rem;"></i>
                        <span>Food Service</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; opacity: 0.7;">
                        <i class="fas fa-calendar-alt" style="color: #666; font-size: 1.2rem;"></i>
                        <span>Tournament Hosting</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; opacity: 0.7;">
                        <i class="fas fa-graduation-cap" style="color: #666; font-size: 1.2rem;"></i>
                        <span>Golf Instruction</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; opacity: 0.7;">
                        <i class="fas fa-parking" style="color: #666; font-size: 1.2rem;"></i>
                        <span>Parking</span>
                    </div>
                </div>
            </div>

            
    </section>
    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Windtree Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/windtree-golf-course/1.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/windtree-golf-course/2.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/windtree-golf-course/3.webp');"></div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section" style="background: #f8f9fa; padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <h2 style="text-align: center; margin-bottom: 3rem; color: #2c5234;">Historical Reviews & Memories</h2>
            
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 3rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Share Your Memories</h3>
                    <p style="color: #666; margin-bottom: 1.5rem;">Did you play Windtree Golf Course during its operational years? Share your memories and experiences from this historic course.</p>
                    
                    <?php if (isset($success_message)): ?>
                        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #c3e6cb;"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #f5c6cb;"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="comment-form">
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Rating (Historical):</label>
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
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Memories:</label>
                            <textarea name="comment_text" placeholder="Share your memories and experiences from Windtree Golf Course..." required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit; resize: vertical; min-height: 100px;"></textarea>
                        </div>
                        <button type="submit" style="background: #2c5234; color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Share Memory</button>
                    </form>
                </div>
            <?php else: ?>
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 15px; text-align: center; margin-bottom: 3rem;">
                    <p><a href="../login.php" style="color: #2c5234; font-weight: 600; text-decoration: none;">Log in</a> to share your memories of Windtree Golf Course</p>
                </div>
            <?php endif; ?>
            
            <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                    <div style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <div style="font-weight: 600; color: #2c5234;"><?php echo htmlspecialchars($comment['username']); ?> <span style="font-size: 0.9rem; color: #666; font-weight: normal;">(Historical Review)</span></div>
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
                    <i class="fas fa-history" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <h3>No memories shared yet</h3>
                    <p>Be the first to share your memories of Windtree Golf Course!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Windtree Golf Course - Complete Photo Gallery</h2>
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
        // Show closure modal on page load
        window.addEventListener('load', function() {
            document.getElementById('closureModal').style.display = 'flex';
        });
        
        function closeModal() {
            document.getElementById('closureModal').style.display = 'none';
        }
        
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
                galleryItem.style.backgroundImage = `url('../images/courses/windtree-golf-course/${i}.webp')`;
                galleryItem.onclick = () => window.open(`../images/courses/windtree-golf-course/${i}.webp`, '_blank');
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
