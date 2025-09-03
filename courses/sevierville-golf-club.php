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
    'name' => 'Sevierville Golf Club',
    'location' => 'Sevierville, TN',
    'description' => 'Premier championship golf facility in Sevierville, TN. Two 18-hole courses (River & Highlands) in the heart of the Great Smoky Mountains.',
    'image' => '/images/courses/sevierville-golf-club/1.jpeg',
    'holes' => 36,
    'par' => 72,
    'designer' => 'D.J. DeVictor',
    'year_built' => 1994,
    'course_type' => 'Public'
];

SEO::setupCoursePage($course_data);

$course_slug = 'sevierville-golf-club';
$course_name = 'Sevierville Golf Club';

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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/sevierville-golf-club/1.jpeg');
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
            width: 100%;
            object-fit: cover;
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
            color: #ddd;
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        /* Responsive Design for Course Info Grid */
        @media (max-width: 1024px) {
            .course-info-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .course-info-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .course-details {
                padding: 2rem 0;
            }
            
            .course-info-card {
                padding: 1.5rem;
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
            <h1>Sevierville Golf Club</h1>
            <p>Championship Golf Facility • Sevierville, Tennessee</p>
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
                            <span class="spec-label">Courses:</span>
                            <span class="spec-value">2 x 18-hole + 9-hole</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">River Course:</span>
                            <span class="spec-value">Par 72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Highlands Course:</span>
                            <span class="spec-value">Par 70</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">D.J. DeVictor</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Established:</span>
                            <span class="spec-value">1994 (Expanded 2011)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Public/Municipal</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">Standard Rates (Cart Included)</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">18-Hole:</span>
                                <span class="spec-value">$79</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Twilight (After 4pm):</span>
                                <span class="spec-value">$38</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Senior Rate:</span>
                                <span class="spec-value">$49</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9-Hole Option:</span>
                                <span class="spec-value">$29</span>
                            </div>
                        </div>
                    </div>
                    <div style="font-size: 0.9rem; color: #666; font-style: italic;">
                        <p>* Prices subject to change seasonally</p>
                        <p>* Online tee time booking available</p>
                        <p>* Golf attire required (collared shirts)</p>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    1444 Old Knoxville Highway<br>
                    Sevierville, TN 37876</p>
                    
                    <p><strong>Phone:</strong><br>
                    (865) 429-4223</p>
                    
                    <p><strong>Hours:</strong><br>
                    Pro Shop: 7:00 AM - 7:00 PM<br>
                    Driving Range: 7:00 AM - 7:00 PM</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://www.seviervillegolfclub.com/" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">seviervillegolfclub.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=1444+Old+Knoxville+Highway,+Sevierville,+TN+37876&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Sevierville Golf Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=1444+Old+Knoxville+Highway,+Sevierville,+TN+37876" 
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
                <h3><i class="fas fa-golf-ball"></i> About Sevierville Golf Club</h3>
                <p>Sevierville Golf Club (formerly Eagle's Landing Golf Club) is a premier championship golf facility that originally opened in 1994 and was expanded in 2011. Located just north of Pigeon Forge in the heart of the Great Smoky Mountains, this municipal golf facility offers golfers an exceptional multi-course experience with spectacular mountain views.</p>
                
                <br>
                
                <p>The facility features two championship 18-hole courses designed by D.J. DeVictor, plus a 9-hole option. <strong>The River Course</strong> (par 72) plays along and across the scenic Little Pigeon River, while <strong>The Highlands Course</strong> (par 70) winds through rolling hills and fresh water mountain ponds.</p>
                
                <br>
                
                <p>Recognized by Golf Advisor as one of the top 50 US Courses and awarded a 4-star rating by Golf Digest, Sevierville Golf Club has established itself as a "resort class facility" operating on a daily fee basis. The course was nominated for "Best Public Course" award in 1995.</p>
                
                <br>
                
                <p>The facility offers a complete golf experience with an extensive driving range, chipping area, putting green, and a full-service clubhouse with Mulligan's restaurant featuring mountain and river views. The pro shop is fully stocked and private dining facilities are available for events.<br><br></p>
                
                <p>What sets Sevierville Golf Club apart is its combination of championship-quality golf with affordability and accessibility. The courses accommodate all skill levels while providing the challenge and beauty that serious golfers demand.<br><br></p>
                
                <p>Located in the heart of Tennessee's most popular tourist destination, the club is perfectly positioned near <a href="https://www.dollywood.com/" target="_blank" rel="noopener noreferrer" style="color: #4a7c59; font-weight: 600;">Dollywood</a>, <a href="https://www.gatlinburg.com/" target="_blank" rel="noopener noreferrer" style="color: #4a7c59; font-weight: 600;">Gatlinburg</a>, and the <a href="https://www.nps.gov/grsm/" target="_blank" rel="noopener noreferrer" style="color: #4a7c59; font-weight: 600;">Great Smoky Mountains National Park</a>, making it an ideal destination for golf vacations and family trips.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Course Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Extensive Driving Range</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-bullseye"></i>
                        <span>Putting Green</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Full-Service Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Mulligan's Restaurant</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-user-tie"></i>
                        <span>PGA Instruction</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-car"></i>
                        <span>Cart Included</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-trophy"></i>
                        <span>Chipping Area</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Private Event Dining</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-laptop"></i>
                        <span>Online Booking</span>
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
                <p>Experience the beauty of Sevierville Golf Club</p>
            </div>
            <div class="gallery-grid">
                <img src="../images/courses/sevierville-golf-club/2.jpeg" alt="Sevierville Golf Club Sevierville, TN - Championship 36-hole golf course fairway with manicured Bermuda grass designed by D.J. DeVictor, Tennessee public golf course" class="gallery-item">
                <img src="../images/courses/sevierville-golf-club/3.jpeg" alt="Sevierville Golf Club Sevierville, TN - Pristine putting green with strategic bunkers and mature landscaping, Public 36-hole Tennessee golf course" class="gallery-item">
                <img src="../images/courses/sevierville-golf-club/4.jpeg" alt="Sevierville Golf Club Sevierville, TN - Scenic golf course view featuring D.J. DeVictor architectural design, premium Tennessee public golf course" class="gallery-item">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/sevierville-golf-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Sevierville Golf Club in Sevierville, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/sevierville-golf-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Sevierville Golf Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/sevierville-golf-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Sevierville Golf Club Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid">
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/1.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/2.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/3.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/4.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/5.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/6.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/7.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/8.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/9.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/10.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/11.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/12.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/13.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/14.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/15.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/16.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/17.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/18.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/19.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/20.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/21.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/22.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/23.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/24.jpeg');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/sevierville-golf-club/25.jpeg');"></div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Gallery Modal Functions
        function openGallery() {
            document.getElementById('galleryModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
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

        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating label');
            const radioInputs = document.querySelectorAll('.star-rating input[type="radio"]');
            
            stars.forEach((star, index) => {
                star.addEventListener('mouseover', function() {
                    highlightStars(index + 1);
                });
                
                star.addEventListener('click', function() {
                    radioInputs[index].checked = true;
                    highlightStars(index + 1);
                });
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
            
            // Reset stars on mouse leave
            const starContainer = document.querySelector('.star-rating');
            if (starContainer) {
                starContainer.addEventListener('mouseleave', function() {
                    const checkedInput = document.querySelector('.star-rating input[type="radio"]:checked');
                    if (checkedInput) {
                        const checkedIndex = Array.from(radioInputs).indexOf(checkedInput);
                        highlightStars(checkedIndex + 1);
                    } else {
                        highlightStars(0);
                    }
                });
            }
        });
    </script>
</body>
</html>