<?php
session_start();
require_once '../config/database.php';

$course_slug = 'avalon-golf-country-club';
$course_name = 'Avalon Golf & Country Club';

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
    <title>Avalon Golf & Country Club - Tennessee Golf Courses</title>
    <meta name="description" content="Avalon Golf & Country Club - Joseph L. Lee designed semi-private golf course in Lenoir City, TN. Experience championship golf with Primo Zoysia greens.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/tab-logo.png?v=2">
    <link rel="shortcut icon" href="../images/logos/tab-logo.png?v=2">
    
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/avalon-golf-country-club/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 120px;
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
            border-bottom: 1px solid #f0f0f0;
        }
        
        .spec-label {
            font-weight: 600;
            color: #666;
        }
        
        .spec-value {
            font-weight: 700;
            color: #2c5234;
        }
        
        .pricing-section {
            margin: 2rem 0;
        }
        
        .pricing-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .pricing-category {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #2c5234;
        }
        
        .pricing-category h4 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .pricing-item {
            display: flex;
            justify-content: space-between;
            padding: 0.3rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .pricing-item:last-child {
            border-bottom: none;
        }
        
        .pricing-note {
            font-style: italic;
            color: #666;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
            justify-items: center;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .amenity-item i {
            color: #4a7c59;
            font-size: 1.2rem;
        }
        
        .signature-holes {
            margin: 4rem 0;
        }
        
        .signature-holes h2 {
            text-align: center;
            margin-bottom: 3rem;
            color: #2c5234;
        }
        
        .holes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .hole-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .hole-image {
            height: 200px;
            background-size: cover;
            background-position: center;
        }
        
        .hole-content {
            padding: 1.5rem;
        }
        
        .hole-title {
            color: #2c5234;
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }
        
        .hole-stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
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
        
        .review-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
            color: #666;
            font-size: 0.9rem;
        }
        
        .booking-section {
            background: linear-gradient(135deg, #2c5234, #4a7c59);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        
        .booking-content h2 {
            margin-bottom: 1rem;
        }
        
        .booking-content p {
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .booking-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-book {
            background: #ffd700;
            color: #2c5234;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-book:hover {
            background: #ffed4e;
            transform: translateY(-2px);
        }
        
        .btn-contact {
            background: transparent;
            color: white;
            padding: 1rem 2rem;
            border: 2px solid white;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-contact:hover {
            background: white;
            color: #2c5234;
        }
        
        /* Comment System Styles */
        .comment-form-container {
            background: white;
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
        
        .no-comments i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-info-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .course-specs {
                grid-template-columns: 1fr;
            }
            
            .holes-grid {
                grid-template-columns: 1fr;
            }
            
            .booking-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>
    
    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Avalon Golf & Country Club</h1>
            <p>Joseph L. Lee Design • 6,764 Yards • Par 72 • Semi-Private</p>
            <?php if ($avg_rating): ?>
            <div class="course-rating">
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
                <div class="rating-text"><?php echo $avg_rating; ?> (<?php echo $total_reviews; ?> reviews)</div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Course Details Section -->
    <section class="course-details">
        <div class="container">
            <div class="course-info-grid">
                <!-- Course Information -->
                <div class="course-info-card">
                    <h3><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Designer</span>
                            <span class="spec-value">Joseph L. Lee</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Year Opened</span>
                            <span class="spec-value">1997</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par</span>
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage</span>
                            <span class="spec-value">6,764</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type</span>
                            <span class="spec-value">Semi-Private</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating</span>
                            <span class="spec-value">72.0</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating</span>
                            <span class="spec-value">128</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Greens</span>
                            <span class="spec-value">Primo Zoysia</span>
                        </div>
                    </div>
                </div>
                
                <!-- Green Fees -->
                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div class="pricing-section">
                        <div class="pricing-grid">
                            <div class="pricing-category">
                                <h4>Monday/Tuesday Rates</h4>
                                <div class="pricing-item">
                                    <span>18 Hole Green Fee</span>
                                    <span>$50</span>
                                </div>
                                <div class="pricing-item">
                                    <span>9 Hole Green Fee</span>
                                    <span>$40</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Member Guest 18</span>
                                    <span>$40</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Member Guest 9</span>
                                    <span>$35</span>
                                </div>
                            </div>
                            
                            <div class="pricing-category">
                                <h4>Wednesday/Thursday Rates</h4>
                                <div class="pricing-item">
                                    <span>18 Hole Green Fee</span>
                                    <span>$65</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Senior/Junior 18 Hole</span>
                                    <span>$50</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Twilight (After 4PM)</span>
                                    <span>$50</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Military/First Responders</span>
                                    <span>$50</span>
                                </div>
                                <div class="pricing-item">
                                    <span>9 Hole Green Fee</span>
                                    <span>$40</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Member Guest 18</span>
                                    <span>$52</span>
                                </div>
                            </div>
                            
                            <div class="pricing-category">
                                <h4>Weekend/Holiday Rates</h4>
                                <div class="pricing-item">
                                    <span>18 Hole Green Fee</span>
                                    <span>$85</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Twilight (After 4PM)</span>
                                    <span>$60</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Junior 18 Hole</span>
                                    <span>$55</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Military/First Responders</span>
                                    <span>$50</span>
                                </div>
                                <div class="pricing-item">
                                    <span>9 Hole Green Fee</span>
                                    <span>$45</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Member Guest 18</span>
                                    <span>$68</span>
                                </div>
                            </div>
                            
                            <div class="pricing-category">
                                <h4>Replay Rates</h4>
                                <div class="pricing-item">
                                    <span>Replay 18 Hole</span>
                                    <span>$35</span>
                                </div>
                                <div class="pricing-item">
                                    <span>Replay 9 Hole</span>
                                    <span>$25</span>
                                </div>
                            </div>
                        </div>
                        <div class="pricing-note">
                            Golf carts are required until Twilight<br>
                            Nine hole rates subject to availability<br>
                            Soft Spikes Only
                        </div>
                    </div>
                </div>
                
                <!-- Location & Contact -->
                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Address</span>
                            <span class="spec-value">1299 Oak Chase Blvd.</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">City</span>
                            <span class="spec-value">Lenoir City, TN 37772</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Phone</span>
                            <span class="spec-value">(865) 986-4653</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Website</span>
                            <span class="spec-value"><a href="https://www.avalongolf.com" target="_blank" style="color: #2c5234;">avalongolf.com</a></span>
                        </div>
                    </div>
                    
                    <div class="amenities-grid">
                        <div class="amenity-item">
                            <i class="fas fa-golf-ball"></i>
                            <span>Driving Range</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-utensils"></i>
                            <span>Restaurant</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Pro Shop</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Golf Lessons</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Course Description -->
            <div class="section-content">
                <h2>About Avalon Golf & Country Club</h2>
                <p>Nestled in the scenic hills of Lenoir City, Tennessee, Avalon Golf & Country Club offers an exceptional semi-private golf experience designed by renowned architect Joseph L. Lee. Since opening in 1997, this championship 18-hole course has established itself as one of East Tennessee's premier golf destinations, combining challenging play with breathtaking natural beauty.</p>
                
                <p>Stretching across 6,764 yards from the blue tees with a par of 72, Avalon presents a perfect balance of risk and reward for golfers of all skill levels. The course features meticulously maintained Primo Zoysia greens that provide consistent putting surfaces year-round, while the Bermuda grass fairways offer excellent playing conditions throughout the season.</p>
                
                <p>The layout takes full advantage of the rolling terrain and mature trees, creating a series of memorable holes that demand both strategic thinking and precise shot-making. With a course rating of 72.0 and slope rating of 128 from the championship tees, Avalon provides a fair but challenging test that rewards good golf while remaining enjoyable for recreational players.</p>
                
                <p>As a semi-private facility, Avalon Golf & Country Club welcomes both members and daily fee players, offering competitive rates and excellent playing conditions. The club features a full-service pro shop, driving range, and restaurant, making it a complete golf destination for tournaments, outings, and casual rounds alike.</p>
            </div>
        </div>
    </section>
    
    <!-- Signature Holes -->
    <section class="signature-holes">
        <div class="container">
            <h2>Signature Holes</h2>
            <div class="holes-grid">
                <div class="hole-card">
                    <div class="hole-image" style="background-image: url('../images/courses/avalon-golf-country-club/hole-signature-1.jpeg');"></div>
                    <div class="hole-content">
                        <h3 class="hole-title">The Challenge</h3>
                        <div class="hole-stats">
                            <span><strong>Par:</strong> 4</span>
                            <span><strong>Yardage:</strong> 425</span>
                        </div>
                        <p>This demanding par-4 requires precision from tee to green, with mature trees lining both sides of the fairway and a well-protected green that rewards accurate approach shots.</p>
                    </div>
                </div>
                
                <div class="hole-card">
                    <div class="hole-image" style="background-image: url('../images/courses/avalon-golf-country-club/hole-signature-2.jpeg');"></div>
                    <div class="hole-content">
                        <h3 class="hole-title">The Finisher</h3>
                        <div class="hole-stats">
                            <span><strong>Par:</strong> 5</span>
                            <span><strong>Yardage:</strong> 535</span>
                        </div>
                        <p>A spectacular finishing hole that offers multiple strategic options, with risk-reward opportunities for long hitters and safe playing routes for those seeking to protect their score.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <h2>Course Gallery</h2>
            <div class="gallery-grid">
                <?php for ($i = 1; $i <= 8; $i++): ?>
                <div class="gallery-item" style="background-image: url('../images/courses/avalon-golf-country-club/<?php echo $i; ?>.jpeg');" onclick="openGallery()"></div>
                <?php endfor; ?>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>
    
    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <h2>Course Reviews</h2>
            
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container">
                    <h3>Share Your Experience</h3>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="success-message"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="error-message"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="comment-form">
                        <div class="form-group">
                            <label for="rating">Your Rating:</label>
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
                            <textarea name="comment_text" id="comment_text" placeholder="Share your thoughts about Avalon Golf & Country Club..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Post Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p><a href="../login.php">Log in</a> to share your review of Avalon Golf & Country Club</p>
                </div>
            <?php endif; ?>
            
            <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="reviewer-name"><?php echo htmlspecialchars($comment['username']); ?></div>
                            <div class="review-date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                        </div>
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star" style="color: <?php echo $i <= $comment['rating'] ? '#ffd700' : '#ddd'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-comments">
                    <i class="fas fa-comments"></i>
                    <h3>No reviews yet</h3>
                    <p>Be the first to share your experience at Avalon Golf & Country Club!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Booking Section -->
    <section class="booking-section">
        <div class="container">
            <div class="booking-content">
                <h2>Ready to Play Avalon Golf & Country Club?</h2>
                <p>Experience Joseph L. Lee's championship design with premium Primo Zoysia greens and exceptional playing conditions.</p>
                <div class="booking-buttons">
                    <a href="tel:(865)986-4653" class="btn-book">Call to Book: (865) 986-4653</a>
                    <a href="https://www.avalongolf.com" target="_blank" class="btn-contact">Visit Website</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Avalon Golf & Country Club Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div id="fullGalleryGrid" class="full-gallery-grid">
                <!-- Images will be loaded here by JavaScript -->
            </div>
        </div>
    </div>
    
    <?php include '../footer.php'; ?>
    
    <script>
        // Star rating functionality
        document.querySelectorAll('.star-rating input[type="radio"]').forEach((radio, index) => {
            radio.addEventListener('change', function() {
                const stars = document.querySelectorAll('.star-rating label');
                stars.forEach((star, starIndex) => {
                    if (starIndex >= (5 - this.value)) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            });
        });
        
        // Gallery modal functionality
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Add all 25 images
            for (let i = 1; i <= 25; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/avalon-golf-country-club/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/avalon-golf-country-club/${i}.jpeg`, '_blank');
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
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('galleryModal');
            if (event.target === modal) {
                closeGallery();
            }
        }
    </script>
</body>
</html>