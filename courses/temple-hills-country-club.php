<?php
session_start();
require_once '../config/database.php';

$course_slug = 'temple-hills-country-club';
$course_name = 'Temple Hills Country Club';

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
    <title>Temple Hills Country Club - Leon Howard Design | Tennessee Golf Courses</title>
    <meta name="description" content="Experience Temple Hills Country Club, a prestigious private club in Franklin with 27 holes designed by Leon Howard. Championship golf since 1972.">
    <link rel="canonical" href="https://tennesseegolfcourses.com/courses/temple-hills-country-club">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/temple-hills-country-club/1.jpeg');
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
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        .nine-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            border-left: 4px solid #228B22;
        }
        
        .nine-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #228B22;
            margin-bottom: 1rem;
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
        
        .combinations-section {
            background: #e8f5e8;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
        
        .combinations-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c5234;
            margin-bottom: 1rem;
        }
        
        .combinations-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .combination-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            color: #2c5234;
        }
        
        .membership-section {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .membership-section h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-align: center;
        }
        
        .membership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .membership-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .membership-card:hover {
            border-color: #228B22;
            transform: translateY(-2px);
        }
        
        .membership-icon {
            font-size: 3rem;
            color: #228B22;
            margin-bottom: 1rem;
        }
        
        .membership-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c5234;
            margin-bottom: 1rem;
        }
        
        .membership-price {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 1rem;
        }
        
        .membership-features {
            list-style: none;
            padding: 0;
            text-align: left;
        }
        
        .membership-features li {
            padding: 0.25rem 0;
            color: #555;
        }
        
        .membership-features li::before {
            content: '✓';
            color: #228B22;
            font-weight: bold;
            margin-right: 0.5rem;
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
            
            .membership-grid {
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
            <h1>Temple Hills Country Club</h1>
            <p>Prestigious Private Club Since 1972</p>
            
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
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Total Yardage:</span>
                            <span class="spec-value">6,831 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Greens:</span>
                            <span class="spec-value">Bent Grass</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Leon Howard</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Year Opened:</span>
                            <span class="spec-value">1972</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-crown"></i> Private Club Excellence</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Membership:</span>
                            <span class="spec-value">Private Club</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Management:</span>
                            <span class="spec-value">Invited Club Management</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Network Access:</span>
                            <span class="spec-value">300+ Clubs Nationwide</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Wedding Rating:</span>
                            <span class="spec-value">5.0 (100% Recommended)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Location:</span>
                            <span class="spec-value">Franklin, TN</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-phone"></i> Contact Information</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Phone:</span>
                            <span class="spec-value">(615) 646-4785</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Address:</span>
                            <span class="spec-value">6376 Temple Road</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">City:</span>
                            <span class="spec-value">Franklin, TN 37069</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">General Manager:</span>
                            <span class="spec-value">Arthur Foister, PGA</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Fairways:</span>
                            <span class="spec-value">Bermuda Grass</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="course-description">
                <h2>About Temple Hills Country Club</h2>
                <p>Temple Hills Country Club stands as one of Franklin's premier private golf and social destinations, offering an exceptional 27-hole golf experience designed by Leon Howard in 1972. Located in the heart of Tennessee's beautiful rolling countryside, this exclusive club provides members with a unique three-course, nine-hole configuration that was relatively uncommon for private clubs of its era.</p>
                
                <p>The club's distinctive layout features three separate nine-hole courses - Dogwood, Quail Run, and Deer Crest - each with its own character and challenges. This innovative design allows members to enjoy three different 18-hole combinations, providing varied playing experiences and ensuring that each round offers fresh challenges and scenic beauty.</p>
                
                <p>Built on rolling terrain with undulating greens and plush fairways, Temple Hills showcases bent grass greens and Bermuda fairways throughout its 6,831 yards. The course design emphasizes fair but challenging play with no hidden hazards, featuring numerous trees and water hazards that reward accuracy and strategic thinking while maintaining playability for golfers of all skill levels.</p>
                
                <p>As part of the prestigious Invited network, Temple Hills Country Club provides members with access to over 300 clubs nationwide, along with comprehensive amenities including fine dining, tennis, swimming, and world-class event facilities. The club has earned a perfect 5.0 rating for wedding and event services with a 100% recommendation rate, making it a premier destination for both golf and special occasions.</p>
            </div>

            <!-- Course Layout -->
            <div class="course-layout">
                <h2>27-Hole Championship Layout</h2>
                <div class="layout-grid">
                    <div class="nine-card">
                        <div class="nine-title">Dogwood Course</div>
                        <div class="nine-details">The most accessible of the three courses, featuring wide fairways and strategic bunkering that rewards smart course management.</div>
                        <div class="nine-specs">
                            <div><strong>Par:</strong> 36</div>
                            <div><strong>Yardage:</strong> 3,351 yards</div>
                            <div><strong>Character:</strong> Strategic</div>
                            <div><strong>Best For:</strong> All skill levels</div>
                        </div>
                    </div>
                    <div class="nine-card">
                        <div class="nine-title">Quail Run Course</div>
                        <div class="nine-details">Features the signature 8th hole - a stunning 192-yard par 3. Known for its challenging approach shots and well-protected greens.</div>
                        <div class="nine-specs">
                            <div><strong>Par:</strong> 36</div>
                            <div><strong>Yardage:</strong> 3,408 yards</div>
                            <div><strong>Signature:</strong> #8 Par-3</div>
                            <div><strong>Length:</strong> 192 yards</div>
                        </div>
                    </div>
                    <div class="nine-card">
                        <div class="nine-title">Deer Crest Course</div>
                        <div class="nine-details">The most challenging course with tree-lined fairways and numerous out-of-bounds areas requiring precision and local knowledge.</div>
                        <div class="nine-specs">
                            <div><strong>Par:</strong> 36</div>
                            <div><strong>Yardage:</strong> 3,423 yards</div>
                            <div><strong>Difficulty:</strong> Most Challenging</div>
                            <div><strong>Features:</strong> Tree-lined fairways</div>
                        </div>
                    </div>
                </div>
                
                <div class="combinations-section">
                    <div class="combinations-title">18-Hole Playing Combinations</div>
                    <div class="combinations-list">
                        <div class="combination-item">Dogwood + Deer Crest</div>
                        <div class="combination-item">Deer Crest + Quail Run</div>
                        <div class="combination-item">Dogwood + Quail Run</div>
                    </div>
                </div>
            </div>

            <!-- Membership Section -->
            <div class="membership-section">
                <h2>Membership Investment</h2>
                <div class="membership-grid">
                    <div class="membership-card">
                        <div class="membership-icon">
                            <i class="fas fa-golf-ball"></i>
                        </div>
                        <div class="membership-title">Full Golf Membership</div>
                        <div class="membership-price">Initiation: $2,501 - $10,000</div>
                        <ul class="membership-features">
                            <li>Unlimited golf privileges</li>
                            <li>Full club amenities access</li>
                            <li>Dining and social events</li>
                            <li>Guest privileges</li>
                            <li>Invited network access</li>
                        </ul>
                    </div>
                    <div class="membership-card">
                        <div class="membership-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="membership-title">Social Membership</div>
                        <div class="membership-price">Annual Dues: $5,001 - $10,000</div>
                        <ul class="membership-features">
                            <li>Dining privileges</li>
                            <li>Tennis and swimming</li>
                            <li>Social events access</li>
                            <li>Event facility use</li>
                            <li>Limited golf privileges</li>
                        </ul>
                    </div>
                    <div class="membership-card">
                        <div class="membership-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="membership-title">Junior Executive</div>
                        <div class="membership-price">Contact for Pricing</div>
                        <ul class="membership-features">
                            <li>Young professional focused</li>
                            <li>Golf and social privileges</li>
                            <li>Networking opportunities</li>
                            <li>Career development events</li>
                            <li>Flexible membership terms</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Facilities Grid -->
            <div class="facilities-grid">
                <h2>Club Facilities & Amenities</h2>
                <div class="facilities-list">
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-golf-ball"></i>
                        </div>
                        <div class="facility-title">27-Hole Championship Golf</div>
                        <div class="facility-description">Three distinct nine-hole courses by Leon Howard with bent grass greens and Bermuda fairways</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-swimmer"></i>
                        </div>
                        <div class="facility-title">Junior Olympic Pool</div>
                        <div class="facility-description">Seasonal swimming facility with competitive and recreational swimming programs</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-tennis-ball"></i>
                        </div>
                        <div class="facility-title">Tennis Complex</div>
                        <div class="facility-description">Multiple courts with professional instruction, clinics, and tournament hosting</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-table-tennis"></i>
                        </div>
                        <div class="facility-title">Pickleball Courts</div>
                        <div class="facility-description">Modern pickleball facilities for America's fastest-growing racquet sport</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="facility-title">Fine Dining</div>
                        <div class="facility-description">Casual grill room and formal dining with panoramic golf course views</div>
                    </div>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="facility-title">Event Facilities</div>
                        <div class="facility-description">Wedding ceremonies, corporate events, and private celebrations with 5.0 rating</div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div class="photo-gallery">
                <h2>Club Gallery</h2>
                <div class="gallery-grid">
                    <?php for ($i = 2; $i <= 25; $i++): ?>
                        <div class="gallery-item" onclick="openModal('../images/courses/temple-hills-country-club/<?php echo $i; ?>.jpeg')">
                            <img src="../images/courses/temple-hills-country-club/<?php echo $i; ?>.jpeg" alt="Temple Hills Country Club - Photo <?php echo $i; ?>">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="reviews-section">
                <div class="reviews-header">
                    <h2 class="reviews-title">Member Reviews</h2>
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
                                <textarea name="comment_text" id="comment_text" placeholder="Share your thoughts about Temple Hills Country Club..." required></textarea>
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