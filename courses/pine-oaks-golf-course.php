<?php
session_start();
require_once '../config/database.php';

$course_slug = 'pine-oaks-golf-course';
$course_name = 'Pine Oaks Golf Course';

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
    <title>Pine Oaks Golf Course - Tennessee Golf Courses</title>
    <meta name="description" content="Pine Oaks Golf Course - Historic municipal course in Johnson City, TN designed by Alexander G. McKay. Opened in 1963 providing affordable golf for all skill levels.">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/pine-oaks-golf-course/1.jpeg');
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
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .spec-label {
            font-weight: 500;
            color: #666;
        }
        
        .spec-value {
            font-weight: 600;
            color: #2c5234;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
        }
        
        .amenity-item i {
            color: #4a7c59;
            width: 20px;
        }
        
        .gallery-section {
            background: #f8f9fa;
            padding: 4rem 0;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .gallery-item {
            aspect-ratio: 4/3;
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
        
        .view-all-btn {
            background: #4a7c59;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 2rem;
            transition: background 0.3s ease;
        }
        
        .view-all-btn:hover {
            background: #3d6249;
        }
        
        .reviews-section {
            padding: 4rem 0;
        }
        
        .review-form-card, .comments-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .star-rating {
            display: flex;
            gap: 0.5rem;
            margin: 1rem 0;
        }
        
        .star-rating i {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
        }
        
        .star-rating i.active,
        .star-rating i:hover {
            color: #ffd700;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
        }
        
        .form-group textarea:focus {
            border-color: #4a7c59;
            outline: none;
        }
        
        .submit-btn {
            background: #4a7c59;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .submit-btn:hover {
            background: #3d6249;
        }
        
        .comment-item {
            border-bottom: 1px solid #eee;
            padding: 1.5rem 0;
        }
        
        .comment-item:last-child {
            border-bottom: none;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: #2c5234;
        }
        
        .comment-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .comment-rating {
            color: #ffd700;
            margin-bottom: 0.5rem;
        }
        
        .comment-text {
            line-height: 1.6;
            color: #333;
        }
        
        .no-rating {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-style: italic;
        }
        
        @media (max-width: 1024px) {
            .course-info-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .course-specs {
                grid-template-columns: 1fr;
            }
            
            .amenities-grid {
                grid-template-columns: 1fr;
            }
            
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            
            .course-hero-content h1 {
                font-size: 2rem;
            }
            
            .course-hero-content p {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Pine Oaks Golf Course</h1>
            <p>Historic Municipal Course • Johnson City, TN</p>
            <div class="course-rating">
                <?php if ($avg_rating && $total_reviews > 0): ?>
                    <div class="rating-stars">
                        <?php
                        $stars = round($avg_rating);
                        for ($i = 1; $i <= 3; $i++) {
                            echo $i <= $stars ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>
                    <div class="rating-text">
                        <?= $avg_rating ?> out of 5 stars (<?= $total_reviews ?> review<?= $total_reviews > 1 ? 's' : '' ?>)
                    </div>
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
                            <span class="spec-value">71</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">6,561</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Alexander G. McKay</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">1963</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Municipal</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    
                    <!-- Weekday Rates -->
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem; font-size: 1.1rem;">Monday - Thursday</h4>
                        <div style="background: #f0f8f2; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.9rem;">
                                <div><strong>18 Holes with Cart:</strong></div>
                                <div>$35</div>
                                <div><strong>9 Holes with Cart:</strong></div>
                                <div>$25</div>
                                <div><strong>Unlimited Walking:</strong></div>
                                <div>$15</div>
                            </div>
                        </div>
                    </div>

                    <!-- Weekend Rates -->
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem; font-size: 1.1rem;">Friday - Sunday & Holidays</h4>
                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.9rem;">
                                <div><strong>18 Holes with Cart:</strong></div>
                                <div>$40</div>
                                <div><strong>9 Holes with Cart:</strong></div>
                                <div>$30</div>
                                <div><strong>18 Holes Walking:</strong></div>
                                <div>$20</div>
                                <div><strong>9 Holes Walking:</strong></div>
                                <div>$15</div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Rates -->
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem; font-size: 1.1rem;">Special Rates</h4>
                        <div style="background: #e8f4ea; padding: 1rem; border-radius: 8px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.9rem;">
                                <div><strong>Senior Thursday (55+):</strong></div>
                                <div>$25 w/cart</div>
                                <div><strong>Ladies Tuesday:</strong></div>
                                <div>$25 w/cart</div>
                                <div><strong>Junior (15 & under):</strong></div>
                                <div>$7 unlimited</div>
                                <div><strong>Adult with Child (12-):</strong></div>
                                <div>$20 w/cart</div>
                                <div><strong>Health Initiative:</strong></div>
                                <div>$10 walking</div>
                            </div>
                        </div>
                    </div>

                    <div style="font-size: 0.85rem; color: #666; font-style: italic; text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                        <p><strong>For Reservations:</strong> <a href="tel:423-434-6250" style="color: #4a7c59; font-weight: 600;">(423) 434-6250</a></p>
                        <p style="margin: 0.5rem 0;">Hours: 7:30am - 7:00pm daily • Online booking available</p>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    1709 Buffalo Road<br>
                    Johnson City, TN 37604</p>
                    
                    <p><strong>Phone:</strong><br>
                    (423) 434-6250</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://www.pineoaksgolf.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">pineoaksgolf.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBokugMWbWJvSkdv3Op_d7MKs8eEGIhkCw&q=1709+Buffalo+Road,+Johnson+City,+TN+37604" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Pine Oaks Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=1709+Buffalo+Road,+Johnson+City,+TN+37604" 
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
                <h3><i class="fas fa-golf-ball"></i> About Pine Oaks Golf Course</h3>
                <p>Pine Oaks Golf Course has been a cornerstone of affordable public golf in East Tennessee since opening in 1963. Originally known as Johnson City Municipal Golf Course, this historic facility was developed in the early 1960s as part of an urban renewal initiative to provide "affordable golf for the common man" when golf was primarily accessible only to private club members.</p>
                
                <br>
                
                <p>Designed by golf course architect Alexander G. McKay, the course was constructed with a budget of $243,000 and funded through city bonds and urban renewal grants. The first nine holes (current back nine) opened on March 23, 1963, with the full 18-hole layout completed by 1964. The course was renamed "Pine Oaks" in 1970 through a public naming contest.</p>
                
                <br>
                
                <p>Located at 1709 Buffalo Road in Johnson City, Pine Oaks has remained true to its founding mission of providing accessible golf for players of all skill levels. The course features scenic mountain views and has been "a favorite of locals for years," maintaining its reputation for quality conditions, low rates, and friendly service.</p>
                
                <br>
                
                <p>Today, Pine Oaks continues to serve the community with modern amenities including a well-stocked pro shop, practice facilities, and online tee time booking, while preserving the welcoming atmosphere that has made it a beloved East Tennessee golf destination for over 60 years.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Course Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Practice Facility</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-bullseye"></i>
                        <span>Practice Green</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-store"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-car"></i>
                        <span>Cart Rentals</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Club Rentals</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-walking"></i>
                        <span>Walking Friendly</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Online Booking</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-clock"></i>
                        <span>Extended Hours</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-dollar-sign"></i>
                        <span>Affordable Rates</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-users"></i>
                        <span>All Skill Levels</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-mountain"></i>
                        <span>Mountain Views</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-landmark"></i>
                        <span>Historic Course</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="gallery-section">
        <div class="container">            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Pine Oaks Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/pine-oaks-golf-course/2.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/pine-oaks-golf-course/3.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/pine-oaks-golf-course/4.webp');"></div>
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
                <h2 class="modal-title">Pine Oaks Golf Course - Complete Photo Gallery</h2>
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
            <h2>Reviews & Ratings</h2>
            
            <?php if ($is_logged_in): ?>
                <div class="review-form-card">
                    <h3>Write a Review</h3>
                    <?php if (isset($success_message)): ?>
                        <div style="color: green; margin-bottom: 1rem;"><?= $success_message ?></div>
                    <?php endif; ?>
                    <?php if (isset($error_message)): ?>
                        <div style="color: red; margin-bottom: 1rem;"><?= $error_message ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Your Rating</label>
                            <div class="star-rating" id="starRating">
                                <i class="far fa-star" data-rating="1"></i>
                                <i class="far fa-star" data-rating="2"></i>
                                <i class="far fa-star" data-rating="3"></i>
                                <i class="far fa-star" data-rating="4"></i>
                                <i class="far fa-star" data-rating="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="comment_text">Your Review</label>
                            <textarea name="comment_text" id="comment_text" placeholder="Share your experience at Pine Oaks Golf Course..." required></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">Submit Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="review-form-card">
                    <h3>Want to Write a Review?</h3>
                    <p>Please <a href="/login" style="color: #4a7c59;">login</a> or <a href="/register" style="color: #4a7c59;">register</a> to share your experience at Pine Oaks Golf Course.</p>
                </div>
            <?php endif; ?>
            
            <div class="comments-section">
                <h3>Reviews (<?= $total_reviews ?>)</h3>
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-item">
                            <div class="comment-header">
                                <span class="comment-author"><?= htmlspecialchars($comment['username']) ?></span>
                                <span class="comment-date"><?= date('M j, Y', strtotime($comment['created_at'])) ?></span>
                            </div>
                            <div class="comment-rating">
                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                    <i class="<?= $i <= $comment['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="comment-text"><?= nl2br(htmlspecialchars($comment['comment_text'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #666; font-style: italic;">No reviews yet. Be the first to share your experience!</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Star rating functionality
        const stars = document.querySelectorAll('#starRating i');
        const ratingInput = document.getElementById('ratingInput');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                        s.classList.add('active');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                        s.classList.remove('active');
                    }
                });
            });
            
            star.addEventListener('mouseenter', function() {
                const rating = this.getAttribute('data-rating');
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.style.color = '#ffd700';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });
        
        document.getElementById('starRating').addEventListener('mouseleave', function() {
            const currentRating = ratingInput.value;
            stars.forEach((s, index) => {
                if (index < currentRating) {
                    s.style.color = '#ffd700';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
        
        // Gallery functionality
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
                galleryItem.style.backgroundImage = `url('../images/courses/pine-oaks-golf-course/${i}.webp')`;
                galleryItem.onclick = () => window.open(`../images/courses/pine-oaks-golf-course/${i}.webp`, '_blank');
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
    
    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>
