<?php
session_start();
require_once '../config/database.php';

$course_slug = 'the-club-at-gettysvue';
$course_name = 'The Club at Gettysvue';

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
    <title>The Club at Gettysvue - Tennessee Golf Courses</title>
    <meta name="description" content="The Club at Gettysvue - Premier private golf club in the foothills of the Great Smoky Mountains. Bland Pittman championship design with comprehensive amenities in Knoxville, TN.">
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
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/the-club-at-gettysvue/1.jpeg');
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
        }
        
        .hero-stats {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .hero-stat {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.1rem;
        }
        
        .course-info-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .course-info-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 4rem;
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
            gap: 10px;
            font-size: 1.3rem;
        }
        
        .course-info-card h3 i {
            color: #4a7c59;
        }
        
        .course-specs {
            display: grid;
            gap: 1rem;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .spec-item:last-child {
            border-bottom: none;
        }
        
        .spec-label {
            font-weight: 600;
            color: #2c5234;
        }
        
        .spec-value {
            color: #666;
            font-weight: 500;
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
        
        .course-description {
            padding: 4rem 0;
            background: white;
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
            margin-bottom: 2rem;
            color: #555;
        }
        
        .amenities-section {
            padding: 4rem 0;
            background: #f8f9fa;
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
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .amenity-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .amenity-card i {
            font-size: 3rem;
            color: #4a7c59;
            margin-bottom: 1rem;
        }
        
        .amenity-card h4 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .amenity-card p {
            color: #666;
            line-height: 1.6;
        }
        
        .tee-info-section {
            padding: 4rem 0;
            background: white;
        }
        
        .tee-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        
        .tee-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .tee-card h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
        }
        
        .tee-card.black h3 { border-left: 4px solid #000; padding-left: 1rem; }
        .tee-card.players h3 { border-left: 4px solid #007bff; padding-left: 1rem; }
        .tee-card.blue h3 { border-left: 4px solid #0056b3; padding-left: 1rem; }
        .tee-card.white h3 { border-left: 4px solid #6c757d; padding-left: 1rem; }
        .tee-card.gold h3 { border-left: 4px solid #ffc107; padding-left: 1rem; }
        
        .tee-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .tee-stat {
            text-align: center;
        }
        
        .tee-stat-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .tee-stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c5234;
        }
        
        .photo-gallery {
            padding: 4rem 0;
            background: #f8f9fa;
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
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .gallery-button {
            text-align: center;
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .reviews-section {
            background: white;
            padding: 4rem 0;
        }
        
        .comment-form-container {
            background: #f8f9fa;
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
            background: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .login-prompt a {
            color: #2c5234;
            text-decoration: none;
            font-weight: 600;
        }
        
        .comment-card {
            background: #f8f9fa;
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
            padding: 0;
            line-height: 1;
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
            
            .course-info-cards {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .amenities-grid {
                grid-template-columns: 1fr;
            }
            
            .tee-cards {
                grid-template-columns: 1fr;
            }
            
            .hero-stats {
                gap: 15px;
            }
            
            .hero-stat {
                font-size: 1rem;
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
            <h1>The Club at Gettysvue</h1>
            <p>Bland Pittman Design • Great Smoky Mountain Foothills</p>
            <div class="hero-stats">
                <?php if ($avg_rating): ?>
                    <div class="hero-stat">
                        <i class="fas fa-star" style="color: #ffd700;"></i>
                        <span><?php echo $avg_rating; ?> (<?php echo $total_reviews; ?> reviews)</span>
                    </div>
                <?php endif; ?>
                <div class="hero-stat">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Knoxville, TN</span>
                </div>
                <div class="hero-stat">
                    <i class="fas fa-ruler"></i>
                    <span>6,540 yards</span>
                </div>
                <div class="hero-stat">
                    <i class="fas fa-mountain"></i>
                    <span>Smoky Mountain Views</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Information Cards -->
    <section class="course-info-section">
        <div class="container">
            <div class="course-info-cards">
                <div class="course-info-card">
                    <h3><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Bland Pittman</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Holes:</span>
                            <span class="spec-value">18</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Length:</span>
                            <span class="spec-value">6,540 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Private Club</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Fairways:</span>
                            <span class="spec-value">Zoysia Grass</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Setting:</span>
                            <span class="spec-value">Smoky Mountain Foothills</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-users"></i> Membership & Access</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Club Type:</span>
                            <span class="spec-value">Private Country Club</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Access:</span>
                            <span class="spec-value">Members & Guests Only</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Membership:</span>
                            <span class="spec-value">Multiple Categories</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Management:</span>
                            <span class="spec-value">Heritage Golf Group</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Family Focus:</span>
                            <span class="spec-value">Yes</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Instruction:</span>
                            <span class="spec-value">PGA Professionals</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div class="location-info">
                        <div class="location-details">
                            <p><i class="fas fa-map-marker-alt"></i> 9317 Linksvue Drive, Knoxville, TN 37922</p>
                            <p><i class="fas fa-phone"></i> (865) 522-4653</p>
                            <p><i class="fas fa-globe"></i> <a href="https://www.gettysvuecc.com" target="_blank">gettysvuecc.com</a></p>
                            <p><i class="fas fa-directions"></i> <a href="https://maps.google.com/maps?q=9317+Linksvue+Drive+Knoxville+TN+37922" target="_blank">Get Directions</a></p>
                        </div>
                        <iframe src="https://maps.google.com/maps?q=9317+Linksvue+Drive+Knoxville+TN+37922&t=&z=15&ie=UTF8&iwloc=&output=embed" 
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
                <h2>Championship Golf in the Smoky Mountains</h2>
                <p>The Club at Gettysvue offers a premier golf experience sculpted from the rolling foothills of the Great Smoky Mountains. Designed by Bland Pittman, this championship course was meticulously crafted to blend seamlessly with its beautiful natural surroundings, creating a harmonious balance between challenging golf and scenic beauty.</p>
                
                <p>The 18-hole, par-72 layout spans 6,540 yards of pristine Zoysia fairways, featuring notable elevation changes that showcase the dramatic topography of East Tennessee. Each hole presents unique strategic challenges while offering breathtaking views of the surrounding mountain landscape, making every round a memorable experience.</p>
                
                <p>As part of Heritage Golf Group's portfolio, The Club at Gettysvue maintains the highest standards of course conditioning and member services. The club's commitment to excellence extends beyond golf, offering a complete lifestyle experience centered around family-friendly amenities and world-class facilities.</p>
                
                <p>With slope ratings ranging from 113 to 134 across multiple tee options, the course accommodates golfers of all skill levels while providing a genuine championship test from the back tees. The combination of strategic design, mountain setting, and premium conditioning makes The Club at Gettysvue a standout destination in Tennessee golf.</p>
            </div>
        </div>
    </section>

    <!-- Amenities Section -->
    <section class="amenities-section">
        <div class="container">
            <div class="section-header">
                <h2>World-Class Amenities</h2>
                <p>Complete lifestyle experience beyond championship golf</p>
            </div>
            <div class="amenities-grid">
                <div class="amenity-card">
                    <i class="fas fa-golf-ball"></i>
                    <h4>Practice Facilities</h4>
                    <p>Two-tiered driving range with 6 target greens, full short game area, and Trackman Golf Simulator for year-round improvement.</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-building"></i>
                    <h4>English-Style Clubhouse</h4>
                    <p>24,000 sq ft clubhouse featuring 3 private dining rooms, casual bar and grill, and elegant common areas.</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-swimmer"></i>
                    <h4>Aquatic Center</h4>
                    <p>Junior Olympic-size swimming pool providing family recreation and competitive swimming opportunities.</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-table-tennis"></i>
                    <h4>Racquet Sports</h4>
                    <p>5 tennis courts and 3 pickleball courts offering diverse options for racquet sport enthusiasts.</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-dumbbell"></i>
                    <h4>24-Hour Fitness Center</h4>
                    <p>State-of-the-art fitness facility available around the clock for member convenience and wellness.</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-shopping-bag"></i>
                    <h4>Full-Service Golf Shop</h4>
                    <p>Complete pro shop with latest equipment, apparel, and accessories, plus men's and women's locker rooms.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tee Information Section -->
    <section class="tee-info-section">
        <div class="container">
            <div class="section-header">
                <h2>Tee Information</h2>
                <p>Five sets of tees for every skill level</p>
            </div>
            <div class="tee-cards">
                <div class="tee-card black">
                    <h3>Black Tees (Championship)</h3>
                    <div class="tee-stats">
                        <div class="tee-stat">
                            <div class="tee-stat-label">Yardage</div>
                            <div class="tee-stat-value">6,537</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Course Rating</div>
                            <div class="tee-stat-value">71.7</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Slope Rating</div>
                            <div class="tee-stat-value">134</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Par</div>
                            <div class="tee-stat-value">72</div>
                        </div>
                    </div>
                </div>
                
                <div class="tee-card players">
                    <h3>Players Tees</h3>
                    <div class="tee-stats">
                        <div class="tee-stat">
                            <div class="tee-stat-label">Yardage</div>
                            <div class="tee-stat-value">6,262</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Course Rating</div>
                            <div class="tee-stat-value">70.3</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Slope Rating</div>
                            <div class="tee-stat-value">133</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Par</div>
                            <div class="tee-stat-value">72</div>
                        </div>
                    </div>
                </div>
                
                <div class="tee-card blue">
                    <h3>Blue Tees (Regular)</h3>
                    <div class="tee-stats">
                        <div class="tee-stat">
                            <div class="tee-stat-label">Yardage</div>
                            <div class="tee-stat-value">6,040</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Course Rating</div>
                            <div class="tee-stat-value">69.5</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Slope Rating</div>
                            <div class="tee-stat-value">132</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Par</div>
                            <div class="tee-stat-value">72</div>
                        </div>
                    </div>
                </div>
                
                <div class="tee-card white">
                    <h3>White Tees (Women's)</h3>
                    <div class="tee-stats">
                        <div class="tee-stat">
                            <div class="tee-stat-label">Yardage</div>
                            <div class="tee-stat-value">5,404</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Course Rating</div>
                            <div class="tee-stat-value">71.6</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Slope Rating</div>
                            <div class="tee-stat-value">126</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Par</div>
                            <div class="tee-stat-value">72</div>
                        </div>
                    </div>
                </div>
                
                <div class="tee-card gold">
                    <h3>Gold Tees (Forward)</h3>
                    <div class="tee-stats">
                        <div class="tee-stat">
                            <div class="tee-stat-label">Yardage</div>
                            <div class="tee-stat-value">4,544</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Course Rating</div>
                            <div class="tee-stat-value">67.2</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Slope Rating</div>
                            <div class="tee-stat-value">113</div>
                        </div>
                        <div class="tee-stat">
                            <div class="tee-stat-label">Par</div>
                            <div class="tee-stat-value">71</div>
                        </div>
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
                <p>Experience the mountain beauty of The Club at Gettysvue</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/the-club-at-gettysvue/2.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/the-club-at-gettysvue/3.jpeg');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/the-club-at-gettysvue/4.jpeg');"></div>
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
                <h2 class="modal-title">The Club at Gettysvue - Complete Photo Gallery</h2>
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
                <p>Read reviews from golfers who have experienced The Club at Gettysvue</p>
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
                            <div class="star-rating" id="rating-stars">
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
                            <textarea id="comment_text" name="comment_text" rows="4" placeholder="Share your experience at The Club at Gettysvue..." required></textarea>
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
                                    <?php for ($i = 1; $i <= 3; $i++): ?>
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

    <?php include '../includes/footer.php'; ?>

    <script>
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
                galleryItem.style.backgroundImage = `url('../images/courses/the-club-at-gettysvue/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/the-club-at-gettysvue/${i}.jpeg`, '_blank');
                galleryGrid.appendChild(galleryItem);
            }
            
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeGallery() {
            const modal = document.getElementById('galleryModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
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
        
        // Interactive star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const ratingContainer = document.getElementById('rating-stars');
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
