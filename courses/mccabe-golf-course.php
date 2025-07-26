<?php
session_start();
require_once '../config/database.php';

$course_slug = 'mccabe-golf-course';
$course_name = 'McCabe Golf Course';

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
    <title>McCabe Golf Course - Nashville Parks & Recreation | Tennessee Golf Courses</title>
    <meta name="description" content="Play McCabe Golf Course, Nashville's premier municipal golf course since 1942. 27 holes, voted 'best place to play' by Nashville Scene magazine.">
    <link rel="canonical" href="https://tennesseegolfcourses.com/courses/mccabe-golf-course">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/mccabe-golf-course/1.jpeg');
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
            background-color: #f8f9fa;
        }
        
        .course-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
            margin-bottom: 1rem;
            font-size: 1.3rem;
            border-bottom: 2px solid #228B22;
            padding-bottom: 0.5rem;
        }
        
        .course-specs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .spec-label {
            font-weight: 600;
            color: #555;
        }
        
        .spec-value {
            color: #2c5234;
            font-weight: 600;
        }
        
        .course-description {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .course-description h2 {
            color: #2c5234;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        
        .course-description p {
            line-height: 1.8;
            margin-bottom: 1.5rem;
            color: #333;
        }
        
        .course-layout {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .course-layout h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2rem;
        }
        
        .layout-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .nine-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #228B22;
        }
        
        .nine-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #228B22;
            margin-bottom: 0.5rem;
        }
        
        .nine-details {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .nine-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        .pricing-section {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .pricing-section h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-align: center;
        }
        
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .pricing-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .pricing-card:hover {
            border-color: #228B22;
            transform: translateY(-2px);
        }
        
        .pricing-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c5234;
            margin-bottom: 1rem;
        }
        
        .price {
            font-size: 2rem;
            font-weight: 700;
            color: #228B22;
            margin-bottom: 0.5rem;
        }
        
        .price-description {
            color: #555;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .facilities-grid {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .facilities-grid h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-align: center;
        }
        
        .facilities-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .facility-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .facility-card:hover {
            border-color: #228B22;
            transform: translateY(-2px);
        }
        
        .facility-icon {
            font-size: 2.5rem;
            color: #228B22;
            margin-bottom: 1rem;
        }
        
        .facility-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c5234;
            margin-bottom: 0.5rem;
        }
        
        .facility-description {
            color: #555;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .photo-gallery {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .photo-gallery h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-align: center;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        
        .gallery-item {
            position: relative;
            height: 200px;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
        }
        
        .modal-content {
            margin: auto;
            display: block;
            width: 90%;
            max-width: 900px;
            max-height: 80%;
            margin-top: 5%;
        }
        
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .reviews-section {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .reviews-title {
            color: #2c5234;
            font-size: 2rem;
            margin: 0;
        }
        
        .overall-rating {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .rating-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .stars {
            color: #ffd700;
        }
        
        .review-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
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
        
        .star-rating {
            display: flex;
            gap: 0.25rem;
            margin-bottom: 1rem;
        }
        
        .star-rating input {
            display: none;
        }
        
        .star-rating label {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            margin-bottom: 0;
        }
        
        .star-rating input:checked ~ label,
        .star-rating label:hover {
            color: #ffd700;
        }
        
        .form-group textarea {
            width: 100%;
            min-height: 100px;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #2c5234, #228B22);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
        }
        
        .reviews-list {
            space-y: 1.5rem;
        }
        
        .review-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
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
            color: #888;
            font-size: 0.9rem;
        }
        
        .review-rating {
            color: #ffd700;
            margin-bottom: 0.5rem;
        }
        
        .review-text {
            color: #555;
            line-height: 1.6;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .login-link {
            color: #2c5234;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-info-grid {
                grid-template-columns: 1fr;
            }
            
            .course-specs {
                grid-template-columns: 1fr;
            }
            
            .layout-grid {
                grid-template-columns: 1fr;
            }
            
            .pricing-grid {
                grid-template-columns: 1fr;
            }
            
            .facilities-list {
                grid-template-columns: 1fr;
            }
            
            .reviews-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>McCabe Golf Course</h1>
            <p>Nashville's Premier Municipal Course Since 1942</p>
            
            <div class="course-rating">
                <?php if ($avg_rating !== null): ?>
                    <div class="rating-stars">
                        <?php
                        $rating = $avg_rating;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= floor($rating)) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ($i <= ceil($rating)) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <div class="rating-text">
                        <?php echo number_format($avg_rating, 1); ?>/5 (<?php echo $total_reviews; ?> reviews)
                    </div>
                <?php else: ?>
                    <div class="rating-text">No ratings yet - Be the first to review!</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Course Details -->
    <section class="course-details">
        <div class="container">
            <!-- Course Information Cards -->
            <div class="course-info-grid">
                <div class="course-info-card">
                    <h3><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Total Holes:</span>
                            <span class="spec-value">27 holes</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par (18 holes):</span>
                            <span class="spec-value">70-71</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Total Yardage:</span>
                            <span class="spec-value">6,000-6,100 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">114</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Gary Roger Baird (South)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Year Opened:</span>
                            <span class="spec-value">1942</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-award"></i> Accolades & Recognition</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Nashville Scene:</span>
                            <span class="spec-value">"Best Place to Play" 2014</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Status:</span>
                            <span class="spec-value">Most Profitable Municipal</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Historic Tournament:</span>
                            <span class="spec-value">Capital City Golf Classic</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Celebrities:</span>
                            <span class="spec-value">Mickey Mantle, Jackie Fargo</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Age Ranking:</span>
                            <span class="spec-value">3rd Oldest Municipal</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-phone"></i> Contact & Hours</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Phone:</span>
                            <span class="spec-value">(615) 862-8491</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Address:</span>
                            <span class="spec-value">100 46th Avenue North</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Hours:</span>
                            <span class="spec-value">6:00 AM to Dark</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Tee Times:</span>
                            <span class="spec-value">7 Days in Advance</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Walk-ins:</span>
                            <span class="spec-value">Welcome</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="course-description">
                <h2>About McCabe Golf Course</h2>
                <p>McCabe Golf Course stands as Nashville's premier municipal golf facility, serving the community since 1942 when it was built through FDR's Works Progress Administration. Originally designed under Parks Commissioner Edwin Warner and C.E. Danis from Shelby Golf Club, with assistance from Hershel "Hut" Eaton, McCabe has evolved into a 27-hole championship facility that consistently earns recognition as the best place to play golf in Metro Nashville.</p>
                
                <p>Located just minutes from downtown Nashville in the Sylvan Park area, McCabe offers golfers a unique 27-hole experience with three distinct nine-hole courses: the North Nine, Middle Nine, and South Nine (designed by Gary Roger Baird, ASGCA). This configuration allows players to combine any two nines for varied 18-hole experiences, ensuring that repeat visits always offer something fresh.</p>
                
                <p>The course has been voted "best place to play" by Nashville Scene magazine in 2014 and holds the distinction of being the most profitable municipal course in Nashville. With wide-open fairways, large tree-lined layouts, and excellent course conditions, McCabe provides an accessible yet challenging experience for golfers of all skill levels.</p>
                
                <p>McCabe's rich tournament history includes the annual Capital City Golf Classic, which has been hosted since 1960 and has attracted celebrities like Mickey Mantle and pro wrestling legend Jackie Fargo. Today, the course continues to serve five active golf associations and maintains its reputation as a cornerstone of Nashville's municipal golf system.</p>
            </div>

            <!-- Course Layout -->
            <div class="course-layout">
                <h2>27-Hole Layout</h2>
                <div class="layout-grid">
                    <div class="nine-card">
                        <div class="nine-title">North Nine (2007)</div>
                        <div class="nine-details">Rebuilt in 2007 with challenging elevation changes, especially on the finishing 9th hole.</div>
                        <div class="nine-specs">
                            <div><strong>Par:</strong> 35</div>
                            <div><strong>Yardage:</strong> 3,115 yards</div>
                            <div><strong>Signature:</strong> 9th hole</div>
                            <div><strong>Features:</strong> Elevation changes</div>
                        </div>
                    </div>
                    <div class="nine-card">
                        <div class="nine-title">Middle Nine (2006)</div>
                        <div class="nine-details">Features traditional parkland design with mature trees and strategic bunker placement.</div>
                        <div class="nine-specs">
                            <div><strong>Par:</strong> 35</div>
                            <div><strong>Yardage:</strong> 2,980 yards</div>
                            <div><strong>Challenging:</strong> #3 (439 yards)</div>
                            <div><strong>Finish:</strong> Par-4, 335 yards</div>
                        </div>
                    </div>
                    <div class="nine-card">
                        <div class="nine-title">South Nine (Gary Baird)</div>
                        <div class="nine-details">Designed by Gary Roger Baird, ASGCA, featuring the course's lone par-5 and signature holes.</div>
                        <div class="nine-specs">
                            <div><strong>Par:</strong> 35 (men), 36 (women)</div>
                            <div><strong>Yardage:</strong> 3,032 yards</div>
                            <div><strong>Par-5:</strong> 538 yards</div>
                            <div><strong>Signature:</strong> #9 (420-yard par-4)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Section -->
            <div class="pricing-section">
                <h2>2025 Green Fees</h2>
                <div class="pricing-grid">
                    <div class="pricing-card">
                        <div class="pricing-title">Weekday 9 Holes</div>
                        <div class="price">$17</div>
                        <div class="price-description">Monday through Friday</div>
                    </div>
                    <div class="pricing-card">
                        <div class="pricing-title">Weekday 18 Holes</div>
                        <div class="price">$34</div>
                        <div class="price-description">Monday through Friday</div>
                    </div>
                    <div class="pricing-card">
                        <div class="pricing-title">Weekend 9 Holes</div>
                        <div class="price">$22</div>
                        <div class="price-description">Saturday and Sunday</div>
                    </div>
                    <div class="pricing-card">
                        <div class="pricing-title">Weekend 18 Holes</div>
                        <div class="price">$44</div>
                        <div class="price-description">Saturday and Sunday</div>
                    </div>
                    <div class="pricing-card">
                        <div class="pricing-title">Cart Rental</div>
                        <div class="price">$8</div>
                        <div class="price-description">Per 9 holes, per player</div>
                    </div>
                    <div class="pricing-card">
                        <div class="pricing-title">Annual Membership</div>
                        <div class="price">$1,000</div>
                        <div class="price-description">Resident ($1,200 Non-Resident)</div>
                    </div>
                </div>
            </div>

            <!-- Facilities Grid -->
            <div class="facilities-grid">
                <h2>Practice Facilities & Amenities</h2>
                <div class="facilities-list">
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-golf-ball"></i>
                        </div>
                        <div class="facility-title">27-Hole Golf</div>
                        <div class="facility-description">Three distinct nine-hole courses for varied 18-hole combinations and experiences</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="facility-title">Driving Range</div>
                        <div class="facility-description">27 hitting stations on two-level tee with Bermuda grass surface and target greens</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div class="facility-title">Putting & Chipping</div>
                        <div class="facility-description">Large practice area with fine white silica sand bunkers for short game improvement</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="facility-title">PGA Instruction</div>
                        <div class="facility-description">Three PGA Professionals available for individual and group lessons</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="facility-title">Pro Shop</div>
                        <div class="facility-description">Full-service pro shop with helpful staff and rental clubs available</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="facility-title">Golf Associations</div>
                        <div class="facility-description">Home to five active golf associations including men's, women's, and senior groups</div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div class="photo-gallery">
                <h2>Course Gallery</h2>
                <div class="gallery-grid">
                    <?php for ($i = 2; $i <= 25; $i++): ?>
                        <div class="gallery-item" onclick="openModal('../images/courses/mccabe-golf-course/<?php echo $i; ?>.jpeg')">
                            <img src="../images/courses/mccabe-golf-course/<?php echo $i; ?>.jpeg" alt="McCabe Golf Course - Photo <?php echo $i; ?>">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="reviews-section">
                <div class="reviews-header">
                    <h2 class="reviews-title">Player Reviews</h2>
                    <?php if ($total_reviews > 0): ?>
                        <div class="overall-rating">
                            <div class="rating-display">
                                <div class="stars">
                                    <?php
                                    $rating = $avg_rating;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= floor($rating)) {
                                            echo '<i class="fas fa-star"></i>';
                                        } elseif ($i <= ceil($rating)) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <span><?php echo number_format($avg_rating, 1); ?>/5</span>
                                <span class="review-count">(<?php echo $total_reviews; ?> reviews)</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($is_logged_in): ?>
                    <!-- Review Form -->
                    <div class="review-form">
                        <h3>Share Your Experience</h3>
                        
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-error"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label>Your Rating:</label>
                                <div class="star-rating">
                                    <input type="radio" name="rating" value="5" id="star5">
                                    <label for="star5">★</label>
                                    <input type="radio" name="rating" value="4" id="star4">
                                    <label for="star4">★</label>
                                    <input type="radio" name="rating" value="3" id="star3">
                                    <label for="star3">★</label>
                                    <input type="radio" name="rating" value="2" id="star2">
                                    <label for="star2">★</label>
                                    <input type="radio" name="rating" value="1" id="star1">
                                    <label for="star1">★</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="comment_text">Your Review:</label>
                                <textarea name="comment_text" id="comment_text" placeholder="Share your thoughts about McCabe Golf Course..." required></textarea>
                            </div>
                            
                            <button type="submit" class="submit-btn">Submit Review</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt">
                        <p><a href="../login" class="login-link">Login</a> or <a href="../register" class="login-link">Register</a> to leave a review</p>
                    </div>
                <?php endif; ?>

                <!-- Existing Reviews -->
                <?php if (!empty($comments)): ?>
                    <div class="reviews-list">
                        <?php foreach ($comments as $comment): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-name"><?php echo htmlspecialchars($comment['username']); ?></div>
                                    <div class="review-date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                                </div>
                                <div class="review-rating">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $comment['rating']) {
                                            echo '<i class="fas fa-star"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="review-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-reviews">
                        <p>No reviews yet. Be the first to share your experience!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Photo Modal -->
    <div id="photoModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script>
        // Photo gallery modal functionality
        function openModal(imageSrc) {
            document.getElementById('photoModal').style.display = 'block';
            document.getElementById('modalImage').src = imageSrc;
        }

        function closeModal() {
            document.getElementById('photoModal').style.display = 'none';
        }

        // Close modal when clicking outside the image
        window.onclick = function(event) {
            const modal = document.getElementById('photoModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Star rating functionality
        const starInputs = document.querySelectorAll('.star-rating input');
        const starLabels = document.querySelectorAll('.star-rating label');

        starLabels.forEach((label, index) => {
            label.addEventListener('mouseover', () => {
                highlightStars(index);
            });
            
            label.addEventListener('click', () => {
                starInputs[index].checked = true;
            });
        });

        document.querySelector('.star-rating').addEventListener('mouseleave', () => {
            const checkedIndex = Array.from(starInputs).findIndex(input => input.checked);
            if (checkedIndex !== -1) {
                highlightStars(checkedIndex);
            } else {
                clearStars();
            }
        });

        function highlightStars(index) {
            starLabels.forEach((label, i) => {
                if (i >= index) {
                    label.style.color = '#ffd700';
                } else {
                    label.style.color = '#ddd';
                }
            });
        }

        function clearStars() {
            starLabels.forEach(label => {
                label.style.color = '#ddd';
            });
        }
    </script>
</body>
</html>