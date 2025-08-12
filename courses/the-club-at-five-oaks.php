<?php
session_start();
require_once '../config/database.php';

$course_slug = 'the-club-at-five-oaks';
$course_name = 'The Club at Five Oaks';

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
    <title>The Club at Five Oaks - Tennessee Golf Courses</title>
    <meta name="description" content="The Club at Five Oaks - Neal Carson designed championship golf course in Lebanon, TN. 18-hole par-72 course featuring rolling hills, Zoysia fairways, and Bermuda greens on 500+ acres.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/tab-logo.png?v=3">
    <link rel="shortcut icon" href="../images/logos/tab-logo.png?v=3">
    
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/the-club-at-five-oaks/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 0px;
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
        
        .course-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .course-info {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 3rem;
            margin: 3rem 0;
        }
        
        .course-description {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
        }
        
        .course-specs {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
        }
        
        .course-specs h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--border-light);
        }
        
        .spec-item:last-child {
            border-bottom: none;
        }
        
        .spec-label {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .spec-value {
            color: var(--text-gray);
        }
        
        .course-gallery {
            margin: 3rem 0;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .gallery-item {
            position: relative;
            aspect-ratio: 16/9;
            overflow: hidden;
            border-radius: 10px;
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
        
        .tee-info {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin: 3rem 0;
        }
        
        .tee-info h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
        }
        
        .tee-boxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .tee-box {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }
        
        .tee-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin: 0 auto 1rem;
        }
        
        .tee-blue { background: #4285f4; }
        .tee-white { background: #ffffff; border: 2px solid #ccc; }
        .tee-yellow { background: #fbbc04; }
        .tee-red { background: #ea4335; }
        
        .tee-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }
        
        .tee-yardage {
            font-size: 1.1rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .tee-rating {
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        
        .amenities {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin: 3rem 0;
        }
        
        .amenities h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.8rem;
            background: var(--bg-light);
            border-radius: 8px;
        }
        
        .amenity-item i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .course-map {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin: 3rem 0;
        }
        
        .course-map h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
        }
        
        .map-container {
            width: 100%;
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .reviews-section {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin: 3rem 0;
        }
        
        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .reviews-header h3 {
            color: var(--primary-color);
            font-size: 1.4rem;
            margin: 0;
        }
        
        .average-rating {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .rating-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .rating-stars {
            display: flex;
            gap: 0.2rem;
        }
        
        .star {
            color: #ffd700;
            font-size: 1.2rem;
        }
        
        .star.empty {
            color: #ddd;
        }
        
        .total-reviews {
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        
        .review-form {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .review-form h4 {
            margin-bottom: 1rem;
            color: var(--text-dark);
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
        
        .rating-input input[type="radio"]:checked ~ label,
        .rating-input label:hover {
            color: #ffd700;
        }
        
        .review-textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }
        
        .review-submit {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 1rem;
            transition: background 0.3s;
        }
        
        .review-submit:hover {
            background: var(--secondary-color);
        }
        
        .review-item {
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem 0;
        }
        
        .review-item:last-child {
            border-bottom: none;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.8rem;
        }
        
        .reviewer-name {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .review-date {
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        
        .review-rating {
            display: flex;
            gap: 0.2rem;
            margin-bottom: 0.8rem;
        }
        
        .review-text {
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 10px;
        }
        
        .login-prompt p {
            margin-bottom: 1rem;
            color: var(--text-gray);
        }
        
        .login-button {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .login-button:hover {
            background: var(--secondary-color);
        }
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-info {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            
            .tee-boxes {
                grid-template-columns: 1fr;
            }
            
            .amenities-grid {
                grid-template-columns: 1fr;
            }
            
            .reviews-header {
                flex-direction: column;
                align-items: flex-start;
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
            <h1>The Club at Five Oaks</h1>
            <p>Championship Golf in Lebanon, Tennessee</p>
        </div>
    </section>

    <div class="course-container">
        <!-- Course Information -->
        <div class="course-info">
            <div class="course-description">
                <h2>About The Course</h2>
                <p>Located on over 500 acres of rolling hills in Lebanon, Tennessee, The Club at Five Oaks offers a championship golf experience that challenges skilled players while remaining enjoyable for recreational golfers. The course features immaculate Zoysia fairways and pristine Bermuda greens, creating the perfect conditions for exceptional golf.</p>
                
                <p>The Club at Five Oaks has hosted several prestigious tournaments, including the Tennessee State Men's Open Qualifier (2007-2010), the Tennessee Golf Association Ladies Senior Amateur (2010), and the Tennessee Golf Association Senior Match Play Championship (2017), establishing its reputation as a premier golf destination in Middle Tennessee.</p>
                
                <p>Set among the scenic landscape of Wilson County, the course offers breathtaking views and a challenging layout that demands strategic thinking and precise shot-making. Each hole presents unique challenges, with the natural terrain incorporated seamlessly into the design to create an authentic and memorable golf experience.</p>
                
                <p>The club provides a complete golf experience with modern amenities and services designed to enhance your visit. Whether you're a serious golfer looking to test your skills or someone seeking a relaxing round in beautiful surroundings, The Club at Five Oaks delivers an exceptional golf experience.</p>
            </div>
            
            <div class="course-specs">
                <h3>Course Specifications</h3>
                <div class="spec-item">
                    <span class="spec-label">Course Type</span>
                    <span class="spec-value">Private</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Par</span>
                    <span class="spec-value">72</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Holes</span>
                    <span class="spec-value">18</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Total Yardage</span>
                    <span class="spec-value">6,954 yards</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Designer</span>
                    <span class="spec-value">Neal Carson</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Year Opened</span>
                    <span class="spec-value">2001</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Greens</span>
                    <span class="spec-value">Bermuda</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Fairways</span>
                    <span class="spec-value">Zoysia</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Course Rating</span>
                    <span class="spec-value">73.4</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Slope Rating</span>
                    <span class="spec-value">129</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Address</span>
                    <span class="spec-value">621 Five Oaks Blvd</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">City</span>
                    <span class="spec-value">Lebanon, TN 37087</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Phone</span>
                    <span class="spec-value">(615) 444-2784</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Website</span>
                    <span class="spec-value"><a href="https://www.clubatfiveoaks.com" target="_blank">clubatfiveoaks.com</a></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Membership</span>
                    <span class="spec-value">Contact for information</span>
                </div>
            </div>
        </div>

        <!-- Tee Information -->
        <div class="tee-info">
            <h3>Tee Information & Ratings</h3>
            <div class="tee-boxes">
                <div class="tee-box">
                    <div class="tee-color tee-blue"></div>
                    <div class="tee-name">Championship Tees</div>
                    <div class="tee-yardage">6,954 yards</div>
                    <div class="tee-rating">Course Rating: 73.4 | Slope: 129</div>
                </div>
                <div class="tee-box">
                    <div class="tee-color tee-white"></div>
                    <div class="tee-name">Regular Tees</div>
                    <div class="tee-yardage">6,421 yards</div>
                    <div class="tee-rating">Course Rating: 70.7 | Slope: 125</div>
                </div>
                <div class="tee-box">
                    <div class="tee-color tee-yellow"></div>
                    <div class="tee-name">Senior Tees</div>
                    <div class="tee-yardage">5,871 yards</div>
                    <div class="tee-rating">Course Rating: 74.0 | Slope: 127</div>
                </div>
                <div class="tee-box">
                    <div class="tee-color tee-red"></div>
                    <div class="tee-name">Ladies Tees</div>
                    <div class="tee-yardage">4,936 yards</div>
                    <div class="tee-rating">Course Rating: 68.3 | Slope: 118</div>
                </div>
            </div>
        </div>

        <!-- Course Gallery -->
        <div class="course-gallery">
            <h3>Course Gallery</h3>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/the-club-at-five-oaks/2.jpeg" alt="The Club at Five Oaks - Hole 1" loading="lazy">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-club-at-five-oaks/3.jpeg" alt="The Club at Five Oaks - Fairway View" loading="lazy">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-club-at-five-oaks/4.jpeg" alt="The Club at Five Oaks - Green Complex" loading="lazy">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-club-at-five-oaks/5.jpeg" alt="The Club at Five Oaks - Clubhouse" loading="lazy">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-club-at-five-oaks/6.jpeg" alt="The Club at Five Oaks - Practice Facility" loading="lazy">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-club-at-five-oaks/7.jpeg" alt="The Club at Five Oaks - Scenic View" loading="lazy">
                </div>
            </div>
        </div>

        <!-- Amenities -->
        <div class="amenities">
            <h3>Club Amenities</h3>
            <div class="amenities-grid">
                <div class="amenity-item">
                    <i class="fas fa-golf-ball"></i>
                    <span>Championship Golf Course</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-home"></i>
                    <span>Elegant Clubhouse</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-utensils"></i>
                    <span>Fine Dining</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-bullseye"></i>
                    <span>Driving Range</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-circle"></i>
                    <span>Putting Green</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-store"></i>
                    <span>Pro Shop</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Golf Instruction</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Event Hosting</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-swimming-pool"></i>
                    <span>Pool & Recreation</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-users"></i>
                    <span>Social Events</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-cart-flatbed"></i>
                    <span>Golf Cart Service</span>
                </div>
                <div class="amenity-item">
                    <i class="fas fa-parking"></i>
                    <span>Ample Parking</span>
                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <div class="course-map">
            <h3>Location & Directions</h3>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dOWTgOzPSyIlwo&q=The+Club+at+Five+Oaks+Lebanon+TN" 
                    width="100%" 
                    height="400" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section">
            <div class="reviews-header">
                <h3>Member Reviews</h3>
                <?php if ($total_reviews > 0): ?>
                    <div class="average-rating">
                        <span class="rating-number"><?php echo $avg_rating; ?></span>
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star <?php echo $i <= round($avg_rating) ? '' : 'empty'; ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <span class="total-reviews">(<?php echo $total_reviews; ?> review<?php echo $total_reviews !== 1 ? 's' : ''; ?>)</span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($is_logged_in): ?>
                <div class="review-form">
                    <h4>Share Your Experience</h4>
                    <?php if (isset($success_message)): ?>
                        <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                    <?php endif; ?>
                    <?php if (isset($error_message)): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="rating-input">
                            <input type="radio" id="star5" name="rating" value="5" required>
                            <label for="star5">★</label>
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4">★</label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3">★</label>
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2">★</label>
                            <input type="radio" id="star1" name="rating" value="1">
                            <label for="star1">★</label>
                        </div>
                        <textarea name="comment_text" class="review-textarea" placeholder="Share your thoughts about The Club at Five Oaks..." required></textarea>
                        <button type="submit" class="review-submit">Post Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p>Please log in to leave a review for The Club at Five Oaks.</p>
                    <a href="../login" class="login-button">Log In</a>
                </div>
            <?php endif; ?>

            <!-- Display Reviews -->
            <div class="reviews-list">
                <?php if (empty($comments)): ?>
                    <p style="text-align: center; color: var(--text-gray); padding: 2rem;">No reviews yet. Be the first to share your experience!</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <span class="reviewer-name"><?php echo htmlspecialchars($comment['username']); ?></span>
                                <span class="review-date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></span>
                            </div>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo $i <= $comment['rating'] ? '' : 'empty'; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <p class="review-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>