<?php
session_start();
require_once '../config/database.php';

$course_slug = 'white-plains-golf-course';
$course_name = 'White Plains Golf Course';

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
    <title>White Plains Golf Course - Tennessee Golf Courses</title>
    <meta name="description" content="White Plains Golf Course - Jeff Jackson designed semi-private golf course in Cookeville, TN. Experience year-round golf with comprehensive amenities and multiple tee options.">
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
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero" style="
        height: 60vh; 
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/white-plains-golf-course/1.jpeg'); 
        background-size: cover; 
        background-position: center; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        text-align: center; 
        color: white;
        margin-top: 20px;
    ">
        <div class="course-hero-content" style="max-width: 800px; padding: 2rem;">
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">White Plains Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Jeff Jackson Design • Cookeville, Tennessee</p>
            <div class="course-rating" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
                <?php if ($avg_rating !== null && $total_reviews > 0): ?>
                    <div class="rating-stars" style="color: #ffd700; font-size: 1.5rem;">
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
                    <span class="rating-text" style="font-size: 1.2rem; font-weight: 600;"><?php echo $avg_rating; ?> / 5.0 (<?php echo $total_reviews; ?> review<?php echo $total_reviews !== 1 ? 's' : ''; ?>)</span>
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
    <section class="course-details" style="padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="course-info-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem; margin-bottom: 4rem;">
                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs single-column" style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Holes:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">18</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Par:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">72</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Yardage:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">6,354</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Designer:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Jeff Jackson</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Opened:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">1998</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Type:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Semi-Private</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div class="pricing-section">
                        <div class="pricing-grid" style="display: grid; gap: 1.5rem;">
                            <div class="pricing-category" style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #2c5234;">
                                <h4 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">18 Holes (Cart Included)</h4>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #e5e7eb;">
                                    <span>Weekday</span>
                                    <span>$40.76</span>
                                </div>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0;">
                                    <span>Weekend</span>
                                    <span>$48.05</span>
                                </div>
                            </div>
                            
                            <div class="pricing-category" style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #2c5234;">
                                <h4 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">9 Holes (Cart Included)</h4>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #e5e7eb;">
                                    <span>Weekday</span>
                                    <span>$22.54</span>
                                </div>
                                <div class="pricing-item" style="display: flex; justify-content: space-between; padding: 0.3rem 0;">
                                    <span>Weekend</span>
                                    <span>$25.27</span>
                                </div>
                            </div>
                        </div>
                        <div class="pricing-note" style="font-style: italic; color: #666; font-size: 0.9rem; margin-top: 1rem;">
                            Year-round play • Multiple tee options • Cart rentals available
                        </div>
                    </div>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div class="course-specs single-column" style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Address:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">4000 Plantation Dr</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">City:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Cookeville, TN 38506</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Phone:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">(931) 526-3306</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Website:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;"><a href="https://www.whiteplainsgolf.com" target="_blank" style="color: #2c5234;">Visit Site</a></span>
                        </div>
                    </div>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=4000+Plantation+Dr,+Cookeville,+TN+38506&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="White Plains Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=4000+Plantation+Dr,+Cookeville,+TN+38506" 
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
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About White Plains Golf Course</h3>
                <p>White Plains Golf Course stands as one of Middle Tennessee's premier semi-private golf destinations, offering exceptional golf in the scenic hills of Cookeville. Designed by Jeff Jackson and opened in 1998, this championship 18-hole layout has quickly established itself as a favorite among both local members and visiting golfers seeking a challenging yet enjoyable golf experience.</p>
                
                <br>
                
                <p>The course stretches 6,354 yards from the championship tees with a par of 72, providing a perfect balance of strategy and playability across rolling terrain. Jackson's thoughtful design incorporates the natural beauty of the Cumberland Plateau region, creating holes that demand both precision and course management while remaining accessible to golfers of all skill levels.</p>
                
                <br>
                
                <p>As a semi-private facility, White Plains welcomes both members and daily fee players, offering competitive rates that include cart rental in the green fee structure. The course features year-round playability with multiple tee options on each hole, allowing golfers to choose the appropriate challenge level for their game while enjoying consistent playing conditions.</p>
                
                <br>
                
                <p>Located in Cookeville's growing golf community, White Plains Golf Course combines the accessibility of a public facility with the quality and service standards of a private club. The course serves as a complete golf destination with comprehensive practice facilities, professional instruction, and tournament hosting capabilities in the heart of Tennessee's Upper Cumberland region.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Club Amenities</h3>
                <div class="amenities-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; justify-items: center;">
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Championship Golf</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Clubhouse Dining</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-shopping-cart" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Practice Range</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-graduation-cap" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Golf Instruction</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-car" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Cart Included</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-calendar-alt" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Event Hosting</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-sun" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Year-Round Play</span>
                    </div>
                </div>
            </div>

            <!-- Course Gallery -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-camera"></i> Course Gallery</h3>
                <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                    <div class="gallery-item" style="height: 250px; background: url('../images/courses/white-plains-golf-course/<?php echo $i; ?>.jpeg'); background-size: cover; background-position: center; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()"></div>
                    <?php endfor; ?>
                </div>
                <div class="gallery-button" style="text-align: center; margin-top: 2rem;">
                    <button onclick="openGallery()" style="background: #4a7c59; color: white; padding: 1rem 2rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">View Full Gallery (25 Photos)</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section" style="background: #f8f9fa; padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <h2 style="text-align: center; margin-bottom: 3rem; color: #2c5234;">Course Reviews</h2>
            
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 3rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Share Your Experience</h3>
                    
                    <?php if (isset($success_message)): ?>
                        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #c3e6cb;"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #f5c6cb;"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="comment-form">
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Rating:</label>
                            <div class="star-rating" style="display: flex; gap: 5px;">
                                <input type="radio" name="rating" value="5" id="star5" style="display: none;">
                                <label for="star5" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                                <input type="radio" name="rating" value="4" id="star4" style="display: none;">
                                <label for="star4" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                                <input type="radio" name="rating" value="3" id="star3" style="display: none;">
                                <label for="star3" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                                <input type="radio" name="rating" value="2" id="star2" style="display: none;">
                                <label for="star2" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                                <input type="radio" name="rating" value="1" id="star1" style="display: none;">
                                <label for="star1" style="color: #ddd; font-size: 1.5rem; cursor: pointer;">★</label>
                            </div>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Review:</label>
                            <textarea name="comment_text" placeholder="Share your thoughts about White Plains Golf Course..." required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit; resize: vertical; min-height: 100px;"></textarea>
                        </div>
                        <button type="submit" style="background: #2c5234; color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Post Review</button>
                    </form>
                </div>
            <?php else: ?>
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 15px; text-align: center; margin-bottom: 3rem;">
                    <p><a href="../login.php" style="color: #2c5234; font-weight: 600; text-decoration: none;">Log in</a> to share your review of White Plains Golf Course</p>
                </div>
            <?php endif; ?>
            
            <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                    <div style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <div style="font-weight: 600; color: #2c5234;"><?php echo htmlspecialchars($comment['username']); ?></div>
                            <div style="color: #666; font-size: 0.9rem;"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                        </div>
                        <div style="color: #ffd700; margin-bottom: 1rem;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star" style="color: <?php echo $i <= $comment['rating'] ? '#ffd700' : '#ddd'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <h3>No reviews yet</h3>
                    <p>Be the first to share your experience at White Plains Golf Course!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div id="galleryModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9);">
        <div style="margin: 2% auto; padding: 20px; width: 90%; max-width: 1200px; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; color: white;">
                <h2 style="font-size: 2rem; margin: 0;">White Plains Golf Course Gallery</h2>
                <button onclick="closeGallery()" style="color: white; font-size: 3rem; font-weight: bold; cursor: pointer; background: none; border: none;">&times;</button>
            </div>
            <div id="fullGalleryGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; max-height: 70vh; overflow-y: auto;">
                <!-- Images will be loaded here by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Dynamic Footer -->
    <?php include '../includes/footer.php'; ?>
    
    <script>
        // Star rating functionality
        document.querySelectorAll('.star-rating input[type="radio"]').forEach((radio) => {
            radio.addEventListener('change', function() {
                const stars = document.querySelectorAll('.star-rating label');
                stars.forEach((star, starIndex) => {
                    if (starIndex >= (5 - this.value)) {
                        star.style.color = '#ffd700';
                    } else {
                        star.style.color = '#ddd';
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
                galleryItem.style.height = '200px';
                galleryItem.style.backgroundImage = `url('../images/courses/white-plains-golf-course/${i}.jpeg')`;
                galleryItem.style.backgroundSize = 'cover';
                galleryItem.style.backgroundPosition = 'center';
                galleryItem.style.borderRadius = '10px';
                galleryItem.style.cursor = 'pointer';
                galleryItem.style.transition = 'transform 0.3s ease';
                galleryItem.onclick = () => window.open(`../images/courses/white-plains-golf-course/${i}.jpeg`, '_blank');
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