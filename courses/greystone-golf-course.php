<?php
session_start();
require_once '../config/database.php';

$course_slug = 'greystone-golf-course';
$course_name = 'Greystone Golf Course';

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
    <title>Greystone Golf Course - Mark McCumber Design | Tennessee Golf Courses</title>
    <meta name="description" content="Experience Greystone Golf Course, a Mark McCumber masterpiece in Dickson. Championship golf with 10-time Tennessee State Open hosting, 7,046 yards of challenge.">
    <link rel="canonical" href="https://tennesseegolfcourses.com/courses/greystone-golf-course">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/greystone-golf-course/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
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
            <h1>Greystone Golf Course</h1>
            <p>Mark McCumber Championship Design in Historic Dickson</p>
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
                    <span>Dickson, TN</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Information -->
    <section class="course-info">
        <div class="container">
            <div class="course-layout">
                <div class="course-details">
                    <h2>10-Time PGA Tour Winner's Masterpiece</h2>
                    <p>Greystone Golf Course in historic Dickson, Tennessee, represents the design mastery of Mark McCumber, a 10-time PGA Tour winner who has created a championship layout that challenges and inspires golfers of all skill levels. Located less than 40 miles west of Nashville, this daily-fee facility offers an exceptional golf experience within easy reach of Middle Tennessee.</p>
                    
                    <p>The course features stunning elevation changes and plays among numerous mounds that create uneven lies and strategic challenges throughout the round. Greystone's terrific layout showcases many scenic views and interesting native stone formations that give the course its distinctive character and natural beauty.</p>
                    
                    <p>As a 10-time host of the Tennessee State Open and venue for the upcoming 2025 Tennessee State Junior Amateur, Greystone has established itself as a premier tournament destination. The course has also hosted PGA Tour Q-school and numerous Tennessee State Opens, cementing its reputation as a championship-caliber facility that maintains tournament-quality conditions year-round.</p>
                </div>
                
                <div class="course-sidebar">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; text-align: center;">Course Overview</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Mark McCumber</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">PGA Tour Wins:</span>
                            <span class="spec-value">10-Time Winner</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">Championship</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">7,046 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par 3 Average:</span>
                            <span class="spec-value">196 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Type:</span>
                            <span class="spec-value">Daily Fee</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Fairways:</span>
                            <span class="spec-value">Zoysia</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Terrain:</span>
                            <span class="spec-value">Elevation & Mounds</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="course-info-cards">
                <div class="course-info-card">
                    <h3><i class="fas fa-trophy"></i> Tournament History</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">TN State Open:</span>
                            <span class="spec-value">10-Time Host</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">2025 Host:</span>
                            <span class="spec-value">TN State Junior Amateur</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">PGA Tour:</span>
                            <span class="spec-value">Q-School Host</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">OVC Championship:</span>
                            <span class="spec-value">2010-13 Host</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Course Access</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Course Type:</span>
                            <span class="spec-value">Daily Fee Public</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Distance from Nashville:</span>
                            <span class="spec-value">Less than 40 miles</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Condition:</span>
                            <span class="spec-value">Tournament Quality</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Accessibility:</span>
                            <span class="spec-value">All Middle Tennessee</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div class="location-info">
                        <div class="location-details">
                            <p><i class="fas fa-map-marker-alt"></i> Historic Dickson, TN</p>
                            <p><i class="fas fa-globe"></i> <a href="https://www.greystonegc.com" target="_blank">greystonegc.com</a></p>
                            <p><i class="fas fa-directions"></i> <a href="https://maps.google.com/maps?q=Greystone+Golf+Club+Dickson+TN" target="_blank">Get Directions</a></p>
                        </div>
                        <iframe src="https://maps.google.com/maps?q=Greystone+Golf+Club+Dickson+TN&t=&z=15&ie=UTF8&iwloc=&output=embed" 
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
                <h2>Championship Design with Natural Beauty</h2>
                <p>Greystone Golf Course combines Mark McCumber's championship design expertise with the natural beauty of Tennessee's rolling landscape. The course features plenty of elevation changes that create dramatic golf experiences while showcasing the scenic vistas that make this region so special.</p>
                
                <p>The layout plays among numerous mounds that often leave players with uneven lies, demanding creativity and skill from golfers at every level. <br>The course features beautiful Zoysia fairway grass that provides excellent playing conditions throughout the season. <br>Native stone formations add character and challenge while creating one of the most visually striking golf experiences in Middle Tennessee.</p>
                
                <p>With its combination of championship design, tournament pedigree, and exceptional conditioning, Greystone offers golfers the opportunity to play where Tennessee's best amateur and professional golfers have competed. The course's reputation for hosting major state tournaments while maintaining its accessibility to daily-fee players makes it a true gem in the Tennessee golf landscape.</p>
            </div>
        </div>
    </section>

    <!-- Signature Features -->
    <section class="signature-holes">
        <div class="container">
            <div class="section-header">
                <h2>Championship Features</h2>
                <p>Experience the design elements that make Greystone special</p>
            </div>
            
            <div class="holes-grid">
                <div class="hole-card">
                    <div class="hole-number">Design</div>
                    <div class="hole-details">
                        <h4>Elevation & Mounds</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Total</div>
                                <div class="hole-stat-value">7,046</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">Par 3</div>
                            </div>
                        </div>
                        <p>The course features numerous mounds and elevation changes that create uneven lies and strategic challenges. Par 3 holes average 196 yards, demanding precision and course management skills.</p>
                    </div>
                </div>
                
                <div class="hole-card">
                    <div class="hole-number">Stone</div>
                    <div class="hole-details">
                        <h4>Native Formations</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Natural</div>
                                <div class="hole-stat-value">Stone</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Features</div>
                                <div class="hole-stat-value">Views</div>
                            </div>
                        </div>
                        <p>Interesting native stone formations throughout the course create both strategic challenges and scenic beauty, showcasing the natural landscape of historic Dickson, Tennessee.</p>
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
                <p>Championship facilities with complete services</p>
            </div>
            <div class="amenities-grid">
                <div class="amenity-item">
                    <i class="fas fa-golf-ball"></i>
                    <span>Championship Golf Course</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-trophy"></i>
                    <span>Tournament Hosting</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Professional Instruction</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-tools"></i>
                    <span>Expert Course Maintenance</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-mountain"></i>
                    <span>Scenic Mountain Views</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-leaf"></i>
                    <span>Zoysia Fairways</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-calendar-check"></i>
                    <span>Event Facilities</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-car"></i>
                    <span>Convenient Access</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Greystone Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/greystone-golf-course/2.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/greystone-golf-course/3.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/greystone-golf-course/4.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/greystone-golf-course/5.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/greystone-golf-course/6.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/greystone-golf-course/7.jpeg');"></div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View All Photos (24+)</button>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Greystone Golf Course - Complete Photo Gallery</h2>
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
            <div class="section-header">
                <h2>What Golfers Are Saying</h2>
                <p>Read reviews from golfers who have experienced Greystone</p>
            </div>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Comment Form (Only for logged in users) -->
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container">
                    <h3>Share Your Experience</h3>
                    <form method="POST" class="comment-form">
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <div class="star-rating" id="greystone-rating-stars">
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
                            <textarea id="comment_text" name="comment_text" rows="4" placeholder="Share your experience playing at Greystone Golf Course..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Post Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p><a href="../login.php">Login</a> or <a href="../register.php">Register</a> to share your review</p>
                </div>
            <?php endif; ?>
            
            <!-- Display Comments -->
            <div class="comments-container">
                <?php if (empty($comments)): ?>
                    <div class="comment-card">
                        <p style="text-align: center; color: #666;">No reviews yet. Be the first to share your experience!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <div class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></div>
                                <div class="comment-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $comment['rating']): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="comment-date"><?php echo date('F j, Y', strtotime($comment['created_at'])); ?></div>
                            <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
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
                        <img src="../images/logos/logo.png" alt="Tennessee Golf Courses" class="footer-logo-image">
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
                        <li><a href="../courses/greystone-golf-course">Greystone</a></li>
                        <li><a href="../courses/harpeth-hills-golf-course">Harpeth Hills</a></li>
                        <li><a href="../courses/hermitage-golf-course">Hermitage</a></li>
                        <li><a href="../courses/holston-hills-country-club">Holston Hills</a></li>
                        <li><a href="../courses/island-pointe-golf-club">Island Pointe</a></li>
                        <li><a href="../courses/tpc-southwind">TPC Southwind</a></li>
                        <li><a href="../courses/willow-creek-golf-club">Willow Creek</a></li>
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
                galleryItem.style.backgroundImage = `url('../images/courses/greystone-golf-course/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/greystone-golf-course/${i}.jpeg`, '_blank');
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
    <script>
        // Interactive star rating functionality for Greystone
        document.addEventListener('DOMContentLoaded', function() {
            const ratingContainer = document.getElementById('greystone-rating-stars');
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
    </script>
</body>
</html>