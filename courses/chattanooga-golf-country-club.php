<?php
session_start();
require_once '../config/database.php';
require_once '../includes/seo.php';

// Course data for SEO
$course_data = [
    'name' => 'Chattanooga Golf & Country Club',
    'location' => 'Chattanooga, TN',
    'description' => 'Historic Donald Ross designed golf course in Chattanooga, TN. The oldest golf course in the South, established in 1896.',
    'image' => '/images/courses/chattanooga-golf-country-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Donald Ross',
    'year_built' => 1896,
    'course_type' => 'Private'
];

SEO::setupCoursePage($course_data);

$course_slug = 'chattanooga-golf-country-club';
$course_name = 'Chattanooga Golf & Country Club';

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
      gtag('config', 'G-7VPNPCDTBP');\n    </script>
    
    <style>
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
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero" style="
        height: 60vh; 
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/chattanooga-golf-country-club/1.webp'); 
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Chattanooga Golf & Country Club</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Donald Ross Design • Chattanooga, Tennessee</p>
            <div class="course-rating" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
                <?php if ($avg_rating): ?>
                    <div class="rating-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star" style="color: <?php echo $i <= round($avg_rating) ? '#ffc107' : '#e0e0e0'; ?>; font-size: 1.5rem;"></i>
                        <?php endfor; ?>
                    </div>
                    <span style="font-size: 1.2rem;"><?php echo $avg_rating; ?> (<?php echo $total_reviews; ?> reviews)</span>
                <?php else: ?>
                    <span style="font-size: 1.1rem; opacity: 0.8;">No reviews yet</span>
                <?php endif; ?>
            </div>
            <a href="#reviews" class="btn-primary" style="background: #4a7c59; color: white; padding: 1rem 2.5rem; border-radius: 50px; text-decoration: none; font-weight: 600; display: inline-block; transition: all 0.3s ease;">Write a Review</a>
        </div>
    </section>

    <!-- Course Information Section -->
    <section class="course-details" style="padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="course-info-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem; margin-bottom: 4rem;">
                
                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs single-column" style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Holes:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">18</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Par:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">71</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Yardage:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">6,702</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Designer:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Donald Ross</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Opened:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">1896</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Type:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Private</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-users"></i> Membership</h3>
                    <div style="background: linear-gradient(135deg, #8B4513, #A0522D); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; margin: 1rem 0;">
                        <h4 style="margin-bottom: 0.5rem; font-size: 1.2rem;">Private Members Only</h4>
                        <p style="margin: 0; opacity: 0.9;">Exclusive club membership required</p>
                    </div>
                    <p style="text-align: center; color: #666; margin-top: 1rem;">
                        Chattanooga Golf & Country Club operates as an exclusive private club serving families since 1896. 
                        Contact the club directly for membership information and guest policies.
                    </p>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p style="margin-bottom: 1rem;"><strong>Address:</strong><br>
                    1511 Riverview Road<br>
                    Chattanooga, TN 37405</p>
                    
                    <p style="margin-bottom: 1rem;"><strong>Phone:</strong><br>
                    (423) 266-6178</p>
                    
                    <p style="margin-bottom: 1.5rem;"><strong>Website:</strong><br>
                    <a href="https://www.chattanoogagcc.org" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">chattanoogagcc.org</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=1511+Riverview+Road,+Chattanooga,+TN+37405&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Chattanooga Golf & Country Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=1511+Riverview+Road,+Chattanooga,+TN+37405" 
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
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Chattanooga Golf & Country Club</h3>
                <p>Chattanooga Golf & Country Club stands as the oldest golf club in the South, establishing a legacy of excellence since 1896. Located in the prestigious Riverview neighborhood just minutes from downtown Chattanooga, this historic club has served as a cornerstone of the community for over 125 years, offering an unparalleled golf experience amidst the natural beauty of the Tennessee Valley.</p>
                
                <br>
                
                <p>The course features the timeless design work of Donald Ross, who crafted the layout in the 1920s to take full advantage of the dramatic riverside setting. In 2005, acclaimed architect Bill Bergin enhanced Ross's original vision, carefully preserving the classic design elements while modernizing the course to meet contemporary standards. The championship layout stretches to 6,702 yards from the championship tees, with a par of 71 that challenges golfers of all skill levels.</p>
                
                <br>
                
                <p>The club's commitment to excellence is evident in its meticulously maintained Ultra-dwarf Bermuda greens, which replaced the original bent grass surfaces to provide superior playing conditions year-round. With a course rating of 73.4 and a slope of 139 from the back tees, the course offers a stern test while remaining enjoyable for members of varying abilities through five sets of tees.</p>
                
                <br>
                
                <p>Consistently ranked among the top 5 courses in Tennessee by Golf Digest, Chattanooga Golf & Country Club has hosted numerous prestigious local, state, and national tournaments throughout its storied history. The club maintains its position as a family-focused modern private club while honoring its rich traditions, providing members with a sanctuary that combines championship golf, exceptional amenities, and the warm hospitality that defines Southern golf culture.</p>
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
                        <i class="fas fa-store" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-graduation-cap" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Private Lessons</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-tools" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Club Fitting</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-child" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Junior Golf Program</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Fine Dining</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-calendar-alt" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Event Hosting</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-building" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Historic Clubhouse</span>
                    </div>
                </div>
            </div>

            
    </section>
    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Chattanooga Golf & Country Club</p>
            </div>
            <div class="gallery-grid">
                <img src="../images/courses/chattanooga-golf-country-club/1.webp" alt="Chattanooga Golf & Country Club - Private Golf Course" class="gallery-item">
                <img src="../images/courses/chattanooga-golf-country-club/2.webp" alt="Chattanooga Golf & Country Club - Scenic Golf Views" class="gallery-item">
                <img src="../images/courses/chattanooga-golf-country-club/3.webp" alt="Chattanooga Golf & Country Club - Course Landscape" class="gallery-item">
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section" id="reviews" style="background: #f8f9fa; padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <h2 style="text-align: center; margin-bottom: 3rem; color: #2c5234;">Course Reviews</h2>
            
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 3rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Share Your Experience</h3>
                    
                    <?php if (isset($success_message)): ?>
                        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Rating</label>
                            <div class="rating-select" style="display: flex; gap: 0.5rem;">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <label style="cursor: pointer;">
                                        <input type="radio" name="rating" value="<?php echo $i; ?>" required style="display: none;">
                                        <i class="fas fa-star" style="font-size: 1.5rem; color: #e0e0e0; transition: color 0.2s;" 
                                           onmouseover="highlightStars(<?php echo $i; ?>)" 
                                           onmouseout="resetStars()" 
                                           onclick="setRating(<?php echo $i; ?>)"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <label for="comment_text" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Your Review</label>
                            <textarea name="comment_text" id="comment_text" rows="4" required 
                                      style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; resize: vertical;"
                                      placeholder="Share your experience playing at Chattanooga Golf & Country Club..."></textarea>
                        </div>
                        
                        <button type="submit" style="background: #4a7c59; color: white; padding: 0.75rem 2rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                            Post Review
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div style="background: #fff3cd; color: #856404; padding: 1.5rem; border-radius: 8px; margin-bottom: 3rem; text-align: center;">
                    <p style="margin: 0;">Please <a href="/auth/login.php" style="color: #856404; font-weight: 600;">log in</a> to leave a review.</p>
                </div>
            <?php endif; ?>
            
            <!-- Display existing reviews -->
            <div class="reviews-container">
                <?php if (empty($comments)): ?>
                    <div style="text-align: center; padding: 3rem; background: white; border-radius: 15px;">
                        <p style="color: #666;">No reviews yet. Be the first to share your experience!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="review-card" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 1.5rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                <div>
                                    <h4 style="margin: 0; color: #2c5234;"><?php echo htmlspecialchars($comment['username']); ?></h4>
                                    <div class="rating-stars" style="margin: 0.5rem 0;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star" style="color: <?php echo $i <= $comment['rating'] ? '#ffc107' : '#e0e0e0'; ?>;"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <span style="color: #666; font-size: 0.9rem;">
                                    <?php echo date('F j, Y', strtotime($comment['created_at'])); ?>
                                </span>
                            </div>
                            <p style="margin: 0; line-height: 1.6; color: #333;">
                                <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Chattanooga Golf & Country Club - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        let currentRating = 0;
        function highlightStars(rating) {
            const stars = document.querySelectorAll('.rating-select i');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#e0e0e0';
                }
            });
        }

        function resetStars() {
            if (currentRating === 0) {
                const stars = document.querySelectorAll('.rating-select i');
                stars.forEach(star => {
                    star.style.color = '#e0e0e0';
                });
            } else {
                highlightStars(currentRating);
            }
        }

        function setRating(rating) {
            currentRating = rating;
            const inputs = document.querySelectorAll('input[name="rating"]');
            inputs[5 - rating].checked = true;
            highlightStars(rating);
        }

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
                galleryItem.style.backgroundImage = `url('../images/courses/chattanooga-golf-country-club/${i}.webp')`;
                galleryItem.onclick = () => window.open(`../images/courses/chattanooga-golf-country-club/${i}.webp`, '_blank');
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
</body>
</html>
