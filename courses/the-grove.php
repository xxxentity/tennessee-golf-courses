<?php
session_start();
require_once '../config/database.php';
require_once '../includes/seo.php';

// Course data for SEO
$course_data = [
    'name' => 'The Grove',
    'location' => 'College Grove, TN',
    'description' => 'Greg Norman Signature Design hosting Korn Ferry Tour and LIV Golf. Championship course in College Grove with tour-level conditioning and 7,438 yards.',
    'image' => '/images/courses/the-grove/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Greg Norman',
    'year_built' => 2010,
    'course_type' => 'Public'
];

SEO::setupCoursePage($course_data);

$course_slug = 'the-grove';
$course_name = 'The Grove';

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
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/the-grove/1.jpeg');
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
            border-bottom: 1px solid #eee;
        }
        
        .spec-label {
            font-weight: 600;
            color: #333;
        }
        
        .spec-value {
            color: #666;
            font-weight: 500;
        }
        
        .amenities-list {
            list-style: none;
            padding: 0;
        }
        
        .amenities-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .amenities-list i {
            color: #2c5234;
            width: 20px;
        }
        
        .location-info {
            margin-bottom: 2rem;
        }
        
        .location-info iframe {
            width: 100%;
            height: 200px;
            border: 0;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .location-details p {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .location-details i {
            color: #2c5234;
            width: 20px;
        }
        
        .course-description {
            background: #f8f9fa;
            padding: 3rem 0;
        }
        
        .description-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .description-content h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        
        .description-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 1.5rem;
        }
        
        .signature-holes {
            padding: 4rem 0;
        }
        
        .holes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .hole-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .hole-number {
            background: #2c5234;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }
        
        .hole-details h4 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .hole-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
        }
        
        .hole-stat {
            text-align: center;
        }
        
        .hole-stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .hole-stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c5234;
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
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .reviews-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .reviews-header h2 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        
        .review-form {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .review-form h3 {
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
            color: #333;
        }
        
        .rating-input {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .rating-input input[type="radio"] {
            display: none;
        }
        
        .rating-input label {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .rating-input label:hover {
            color: #ffd700;
        }
        
        .rating-input label.active {
            color: #ffd700;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }
        
        .form-group textarea:focus {
            border-color: #2c5234;
            outline: none;
        }
        
        .submit-btn {
            background: #2c5234;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .submit-btn:hover {
            background: #1a3020;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .login-prompt a {
            color: #2c5234;
            font-weight: 600;
            text-decoration: none;
        }
        
        .login-prompt a:hover {
            text-decoration: underline;
        }
        
        .comments-list {
            display: grid;
            gap: 2rem;
        }
        
        .comment-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .comment-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .author-name {
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
        
        .comment-content {
            line-height: 1.6;
            color: #555;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .section-header h2 {
            color: #2c5234;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .section-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .course-info-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-hero-content p {
                font-size: 1.1rem;
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
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>The Grove</h1>
            <p>Greg Norman Signature Design • College Grove, Tennessee</p>
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
                            <span class="spec-value">7,438 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">76.1</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">140</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Greg Norman Signature</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">2013</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Private Club Community</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Membership:</span>
                            <span class="spec-value">Private Club</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Tournament Host:</span>
                            <span class="spec-value">Korn Ferry Tour</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">LIV Golf:</span>
                            <span class="spec-value">Professional Venue</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">2022 Award:</span>
                            <span class="spec-value">Tournament of the Year</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Community:</span>
                            <span class="spec-value">1,100 gated acres</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Home Sites:</span>
                            <span class="spec-value">375 luxury lots</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Conditioning:</span>
                            <span class="spec-value">Tour level year-round</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Contact:</span>
                            <span class="spec-value">(615) 368-3883</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div class="location-info">
                        <div class="location-details">
                            <p><i class="fas fa-map-marker-alt"></i> 7165 Nolensville Road, College Grove, TN 37046</p>
                            <p><i class="fas fa-phone"></i> (615) 368-3883</p>
                            <p><i class="fas fa-globe"></i> <a href="https://www.thegrovegolf.com" target="_blank">thegrovegolf.com</a></p>
                            <p><i class="fas fa-directions"></i> <a href="https://maps.google.com/maps?q=7165+Nolensville+Road,+College+Grove,+TN+37046" target="_blank">Get Directions</a></p>
                        </div>
                        <iframe src="https://maps.google.com/maps?q=7165+Nolensville+Road,+College+Grove,+TN+37046&t=&z=15&ie=UTF8&iwloc=&output=embed" 
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
                <h2>Championship Golf at The Grove</h2>
                <p>The Grove represents the pinnacle of modern championship golf in Tennessee, combining Greg Norman's signature design excellence with world-class tournament hosting capabilities. Since opening in 2013, this spectacular 7,438-yard layout has established itself as one of the premier golf destinations in the Nashville area, earning recognition as both a Korn Ferry Tour venue and LIV Golf host.</p>
                
                <p>Built on 1,100 gated acres in the prestigious College Grove community, The Grove showcases Greg Norman's strategic design philosophy with Zoysia fairways, Bermuda greens maintained at tournament speeds, and dramatic elevation changes that utilize the natural Tennessee topography. The course's championship pedigree is evident in every detail, from tour-height fairway cuts to US Open green speeds that challenge the world's best professional golfers.</p>
                
                <p>What truly distinguishes The Grove is its commitment to maintaining tour-level conditioning year-round. The course features the same setup specifications used during professional tournaments, giving members and guests the unique opportunity to experience championship golf at the highest level. This dedication to excellence earned The Grove the prestigious 2022 Korn Ferry Tournament of the Year award.</p>
            </div>
        </div>
    </section>

    <!-- Signature Holes -->
    <section class="signature-holes">
        <div class="container">
            <div class="section-header">
                <h2>Signature Holes</h2>
                <p>Discover the holes that define The Grove's championship character</p>
            </div>
            
            <div class="holes-grid">
                <div class="hole-card">
                    <div class="hole-number">7</div>
                    <div class="hole-details">
                        <h4>Strategic Par-4</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Par</div>
                                <div class="hole-stat-value">4</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">425</div>
                            </div>
                        </div>
                        <p>A demanding par-4 that exemplifies Norman's risk-reward philosophy. Multiple elevation changes and strategic bunkering create numerous decision points for players of all skill levels.</p>
                    </div>
                </div>
                
                <div class="hole-card">
                    <div class="hole-number">12</div>
                    <div class="hole-details">
                        <h4>Dramatic Par-3</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Par</div>
                                <div class="hole-stat-value">3</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">190</div>
                            </div>
                        </div>
                        <p>An elevated tee shot showcasing The Grove's dramatic topography. Water hazards and deep bunkers protect the green, demanding precision under pressure from tournament competitors.</p>
                    </div>
                </div>
                
                <div class="hole-card">
                    <div class="hole-number">18</div>
                    <div class="hole-details">
                        <h4>Tournament Finish</h4>
                        <div class="hole-stats">
                            <div class="hole-stat">
                                <div class="hole-stat-label">Par</div>
                                <div class="hole-stat-value">4</div>
                            </div>
                            <div class="hole-stat">
                                <div class="hole-stat-label">Yards</div>
                                <div class="hole-stat-value">475</div>
                            </div>
                        </div>
                        <p>A championship caliber finishing hole that has decided numerous Korn Ferry Tour events. The approach shot to a multi-tiered green creates dramatic conclusions to professional tournaments.</p>
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
                <p>Experience the beauty of The Grove</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/the-grove/2.jpeg" alt="The Grove College Grove TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-grove/3.jpeg" alt="The Grove Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-grove/4.jpeg" alt="The Grove College Grove TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/the-grove'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out The Grove in Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/the-grove'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out The Grove'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/the-grove'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>


    </section>

    <!-- Full Gallery Modal -->