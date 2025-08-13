<?php
session_start();
require_once '../config/database.php';

$course_slug = 'honky-tonk-national-golf-course';
$course_name = 'Honky Tonk National Golf Course';

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
    <title>Honky Tonk National Golf Course - Tennessee Golf Courses</title>
    <meta name="description" content="Honky Tonk National Golf Course - Peter Jacobsen & Jim Hardy championship design in Sparta, TN. 6,900 yards overlooking Center Hill Lake.">
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
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero" style="
        height: 60vh; 
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/honky-tonk-national-golf-course/1.webp'); 
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Honky Tonk National Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Jacobsen & Hardy Design • Sparta, Tennessee</p>
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
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">72</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Yardage:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">6,900</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Designer:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Jacobsen & Hardy</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Opened:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">2012</span>
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
                        Honky Tonk National Golf Course operates as a private club overlooking Center Hill Lake. 
                        Contact the club directly for membership information and guest policies.
                    </p>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p style="margin-bottom: 1rem;"><strong>Address:</strong><br>
                    235 Harbour Greens Place<br>
                    Sparta, TN 38583</p>
                    
                    <p style="margin-bottom: 1rem;"><strong>Phone:</strong><br>
                    (931) 761-8124</p>
                    
                    <p style="margin-bottom: 1.5rem;"><strong>Website:</strong><br>
                    <a href="https://www.honkytonknationalgolf.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">honkytonknationalgolf.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=235+Harbour+Greens+Place,+Sparta,+TN+38583&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Honky Tonk National Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=235+Harbour+Greens+Place,+Sparta,+TN+38583" 
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
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Honky Tonk National Golf Course</h3>
                <p>Honky Tonk National Golf Course stands as Tennessee's first 18-hole championship golf course designed by the acclaimed team of PGA professional Peter Jacobsen and renowned architect Jim Hardy. Perched high above the pristine waters of Center Hill Lake in middle Tennessee, this exceptional layout transforms the natural landscape into challenging, though encouraging, tests of ability that reward golfers of all skill levels with an unforgettable playing experience.</p>
                
                <br>
                
                <p>The championship course stretches 6,900 yards with a challenging par 72 design that masterfully integrates rolling hillsides and picturesque plateaus into a cohesive golfing masterpiece. Featuring seven distinct tee options with slope ratings ranging from 109 to 130 and course ratings from 62.9 to 73.1, Honky Tonk National provides appropriate challenges for every level of golfer while maintaining the strategic depth and shot-making demands that define championship-caliber golf.</p>
                
                <br>
                
                <p>Jacobsen and Hardy's thoughtful design philosophy emphasizes the natural beauty of the Tennessee landscape, creating a course that flows seamlessly through rolling terrain while offering dramatic elevation changes and scenic vistas of Center Hill Lake. Each hole presents unique strategic challenges that encourage creative shot-making and reward precise execution, while the varied topography ensures that no two holes feel alike, maintaining interest and excitement throughout the entire round.</p>
                
                <br>
                
                <p>Beyond its championship golf offering, Honky Tonk National provides a comprehensive golf experience with complete amenities including a well-appointed pro shop, bar restaurant, and lodging options for extended stays. The course operates under USGA rules with thoughtful local modifications, ensuring both competitive integrity and playability. This commitment to excellence in both course conditioning and hospitality has established Honky Tonk National as a premier golf destination where the spirit of Tennessee hospitality meets world-class championship golf in one of the state's most beautiful natural settings.</p>
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
                        <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Bar Restaurant</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-cocktail" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Dinner Bar</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-bed" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Lodging Available</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-water" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Center Hill Lake</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-chart-line" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Seven Tee Options</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-crown" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Lakeside Experience</span>
                    </div>
                </div>
            </div>

            <!-- Course Gallery -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-camera"></i> Course Gallery</h3>
                <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                    <div class="gallery-item" style="height: 250px; background: url('../images/courses/honky-tonk-national-golf-course/<?php echo $i; ?>.webp'); background-size: cover; background-position: center; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()"></div>
                    <?php endfor; ?>
                </div>
                <div class="gallery-button" style="text-align: center; margin-top: 2rem;">
                    <button onclick="openGallery()" style="background: #4a7c59; color: white; padding: 1rem 2rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">View Full Gallery (25 Photos)</button>
                </div>
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
                                      placeholder="Share your experience playing at Honky Tonk National Golf Course..."></textarea>
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

    <!-- Gallery Modal -->
    <div id="galleryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 9999;">
        <span onclick="closeGallery()" style="position: absolute; top: 20px; right: 40px; color: white; font-size: 40px; cursor: pointer;">&times;</span>
        <div style="height: 100%; display: flex; align-items: center; justify-content: center;">
            <img id="galleryImage" src="" style="max-width: 90%; max-height: 90%; object-fit: contain;">
        </div>
        <button id="prevBtn" onclick="changeImage(-1)" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; color: white; font-size: 30px; padding: 10px 20px; cursor: pointer;">&#10094;</button>
        <button id="nextBtn" onclick="changeImage(1)" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; color: white; font-size: 30px; padding: 10px 20px; cursor: pointer;">&#10095;</button>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        let currentRating = 0;
        let currentImageIndex = 1;
        const totalImages = 25;

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

        function openGallery() {
            document.getElementById('galleryModal').style.display = 'block';
            currentImageIndex = 1;
            showImage(currentImageIndex);
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
        }

        function changeImage(direction) {
            currentImageIndex += direction;
            if (currentImageIndex > totalImages) currentImageIndex = 1;
            if (currentImageIndex < 1) currentImageIndex = totalImages;
            showImage(currentImageIndex);
        }

        function showImage(index) {
            const img = document.getElementById('galleryImage');
            img.src = `../images/courses/honky-tonk-national-golf-course/${index}.webp`;
        }

        // Keyboard navigation for gallery
        document.addEventListener('keydown', function(e) {
            if (document.getElementById('galleryModal').style.display === 'block') {
                if (e.key === 'ArrowLeft') changeImage(-1);
                if (e.key === 'ArrowRight') changeImage(1);
                if (e.key === 'Escape') closeGallery();
            }
        });
    </script>
</body>
</html>