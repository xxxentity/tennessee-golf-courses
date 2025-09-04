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
    'name' => 'The Links at Kahite',
    'location' => 'Tellico Village, TN',
    'description' => 'Ault, Clark & Associates championship links design from 2002 at Tellico Village, TN. True links-style course with 7,170-yard layout and 93 bunkers.',
    'image' => '/images/courses/the-links-at-kahite/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Ault, Clark & Associates',
    'year_built' => 2002,
    'course_type' => 'Resort'
];

SEO::setupCoursePage($course_data);

$course_slug = 'the-links-at-kahite';
$course_name = 'The Links at Kahite';

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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/the-links-at-kahite/1.webp');
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
            <h1>The Links at Kahite</h1>
            <p>Championship Links Golf at Tellico Village</p>
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
                        <span>Vonore, TN</span>
                    </div>
                    <div class="quick-info-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>18 Holes</span>
                    </div>
                    <div class="quick-info-item">
                        <i class="fas fa-ruler"></i>
                        <span>7,170 Yards</span>
                    </div>
                    <div class="quick-info-item">
                        <i class="fas fa-flag"></i>
                        <span>Par 72</span>
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
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">7,170 (Black Tees)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">74.9</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">139</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Ault, Clark & Associates</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">2002</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Resort/Private</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">2025 Daily Fee Rates</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">18-Hole w/Lease Cart:</span>
                                <span class="spec-value">$51.25</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">18-Hole w/Private Cart:</span>
                                <span class="spec-value">$47.50</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole w/Lease Cart:</span>
                                <span class="spec-value">$31.00</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole w/Private Cart:</span>
                                <span class="spec-value">$28.75</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">2025 Member Rates</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">18-Hole w/Lease Cart:</span>
                                <span class="spec-value">$48.75</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">18-Hole w/Private Cart:</span>
                                <span class="spec-value">$45.25</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole w/Lease Cart:</span>
                                <span class="spec-value">$29.00</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole w/Private Cart:</span>
                                <span class="spec-value">$27.75</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">Guest Rates</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">18-Hole with Member:</span>
                                <span class="spec-value">$62.00</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole with Member:</span>
                                <span class="spec-value">$38.25</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">18-Hole without Member:</span>
                                <span class="spec-value">$82.50</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole without Member:</span>
                                <span class="spec-value">$46.50</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                        <p style="margin: 0; font-size: 0.9rem; color: #666;"><strong>PrePaid packages available with near 5% savings</strong><br>
                        Uniform pricing across all three Tellico Village courses<br>
                        Head Professional: Charles Roffers</p>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    400 Kahite Trail<br>
                    Vonore, TN 37885</p>
                    
                    <p><strong>Head Professional:</strong><br>
                    Charles Roffers</p>
                    
                    <p><strong>Course Superintendent:</strong><br>
                    Jordan Clark</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://golftellicovillage.com/the-links-at-kahite/" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">golftellicovillage.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=400+Kahite+Trail,+Vonore,+TN+37885&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="The Links at Kahite Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=400+Kahite+Trail,+Vonore,+TN+37885" 
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
                <h3><i class="fas fa-golf-ball"></i> About The Links at Kahite</h3>
                <p>The Links at Kahite stands as the crowning achievement of Tellico Village's golf offerings, opening in 2002 as the third and most spectacular championship course designed by the prestigious architectural firm Ault, Clark & Associates. Nominated by Golf Digest as the "best new private course in 2003," this true links-style layout represents one of the most beautiful golf settings in the American South, surrounded by the pristine waters of Tellico Lake with panoramic views of the Great Smoky Mountains.</p>
                
                <br>
                
                <p>The championship course stretches across over 200 acres of dramatic terrain, offering six distinct tee options ranging from 7,170 yards down to 4,435 yards, ensuring an appropriate challenge for every skill level. The black tees present a formidable test with a course rating of 74.9 and slope rating of 139, while the expertly designed shorter tees maintain the strategic integrity of the layout. The course features bentgrass putting greens and Bermudagrass playing surfaces, providing consistent conditions year-round.</p>
                
                <br>
                
                <p>What truly distinguishes The Links at Kahite is its authentic links-style design philosophy, rare in American golf. The layout incorporates 93 strategically placed bunkers throughout the course, with the spectacular 18th hole alone featuring 12 bunkers that guard the approach to the green. The course's most famous feature is the legendary 9th hole, an extraordinary 808-yard par 6 that presents one of golf's most unique and challenging experiences, demanding strategic decision-making and course management skills.</p>
                
                <br>
                
                <p>The course has established itself as a premier tournament venue, hosting multiple Tennessee Golf Association events and serving as host for PGA Tour Nationwide Open Qualifiers in 2009 and 2010. This tournament pedigree demonstrates the layout's championship credentials while showcasing its ability to test the games of professional and amateur golfers alike. The Links at Kahite was recently honored as Tennessee's Private Course of the Year by the Tennessee Turfgrass Association in December 2022.</p>
                
                <br>
                
                <p>Beyond the golf course itself, The Links at Kahite provides a comprehensive championship experience with state-of-the-art practice facilities including a driving range, practice putting green, and short-game areas. The course is supported by an exceptional staff led by Head Golf Professional Charles Roffers and Head Golf Course Superintendent Jordan Clark, ensuring that every aspect of the golf experience meets the highest standards of excellence.</p>
                
                <br>
                
                <p>The clubhouse and community amenities complement the golf experience with a full-service clubhouse, fitness center, outdoor pool, and restaurant with outdoor patio overlooking the course and lake. As the flagship course of Tellico Village's three championship layouts, The Links at Kahite represents the pinnacle of links-style golf design in Tennessee, offering a truly world-class golf experience in one of the most spectacular natural settings in the southeastern United States.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Course Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>True Links-Style Design</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-flag"></i>
                        <span>Par 6 9th Hole (808 yards)</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-mountain"></i>
                        <span>Smoky Mountain Views</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-water"></i>
                        <span>Tellico Lake Surrounds</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-bullseye"></i>
                        <span>Driving Range</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Practice Putting Green</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-dumbbell"></i>
                        <span>Fitness Center</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-swimming-pool"></i>
                        <span>Outdoor Pool</span>
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
                <p>Experience the beauty of The Links at Kahite</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/the-links-at-kahite/2.webp" alt="The Links at Kahite Tellico Village TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-links-at-kahite/3.webp" alt="The Links at Kahite Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-links-at-kahite/4.webp" alt="The Links at Kahite Tellico Village TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/the-links-at-kahite'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out The Links At Kahite in Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/the-links-at-kahite'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out The Links At Kahite'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/the-links-at-kahite'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">The Links at Kahite - Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div id="fullGalleryGrid" class="full-gallery-grid">
                <!-- Gallery items will be dynamically generated -->
            </div>
        </div>
    </div>

    <script>
        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Alt text patterns for different image types
            function getAltText(imageIndex) {
                const courseName = 'The Links at Kahite';
                const location = 'Tellico Village, TN';
                const locationShort = 'Tellico Village TN';
                
                if (imageIndex <= 5) {
                    // Course overview shots
                    const overviewTexts = [
                        `${courseName} ${location} - Aerial view of championship 18-hole golf course showing signature holes and clubhouse facilities`,
                        `${courseName} ${locationShort} - Panoramic fairway view hole 12 with strategic bunkers and mature trees`,
                        `${courseName} Tennessee - Championship golf course layout showing championship layout and natural terrain`,
                        `${courseName} ${locationShort} - Championship golf course entrance with professional landscaping and signage`,
                        `${courseName} ${location} - Golf course overview showing scenic terrain and championship facilities`
                    ];
                    return overviewTexts[imageIndex - 1];
                } else if (imageIndex <= 10) {
                    // Signature holes
                    const holes = [6, 8, 9, 15, 18];
                    const holeIndex = imageIndex - 6;
                    const holeNum = holes[holeIndex];
                    const signatures = [
                        `${courseName} Tennessee golf course - Signature par 3 hole ${holeNum} with water hazard and bentgrass green`,
                        `${courseName} ${locationShort} - Challenging par 4 hole ${holeNum} with scenic views and strategic bunkering`,
                        `${courseName} Tennessee - Famous par 6 hole ${holeNum} (808 yards) with unique golf challenge and lake views`,
                        `${courseName} ${location} - Signature hole ${holeNum} featuring championship design and natural beauty`,
                        `${courseName} Tennessee - Finishing hole ${holeNum} with dramatic approach shot and clubhouse backdrop`
                    ];
                    return signatures[holeIndex];
                } else if (imageIndex <= 15) {
                    // Greens and approaches
                    return `${courseName} ${locationShort} - Undulating putting green with championship pin positions and bentgrass surface - Image ${imageIndex}`;
                } else if (imageIndex <= 20) {
                    // Course features
                    const features = [
                        'Practice facility driving range and putting green area',
                        'Golf cart fleet and maintenance facilities',
                        'Professional golf instruction area and practice tees',
                        'Course landscaping with native Tennessee flora and water features',
                        'Golf course pro shop and equipment rental facilities'
                    ];
                    return `${courseName} Tennessee - ${features[(imageIndex - 16) % features.length]}`;
                } else {
                    // Clubhouse and amenities
                    const amenities = [
                        'Golf course clubhouse pro shop and restaurant facilities',
                        'Clubhouse dining room with scenic Tennessee views',
                        'Golf course event space and meeting facilities',
                        'Professional locker room and amenities',
                        'Golf course entrance and parking facilities'
                    ];
                    return `${courseName} ${location} - ${amenities[(imageIndex - 21) % amenities.length]}`;
                }
            }
            
            // Generate all 25 images
            for (let i = 1; i <= 25; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.innerHTML = `<img src="../images/courses/the-links-at-kahite/${i}.webp" alt="${getAltText(i)}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">`;
                galleryItem.onclick = () => window.open(`../images/courses/the-links-at-kahite/${i}.webp`, '_blank');
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
        window.onclick = function(event) {
            const modal = document.getElementById('galleryModal');
            if (event.target === modal) {
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