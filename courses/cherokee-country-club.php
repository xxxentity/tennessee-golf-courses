<?php
require_once '../includes/session-security.php';
require_once '../config/database.php';
require_once '../includes/csrf.php';
require_once '../includes/seo.php';

// Course data for SEO
$course_data = [
    'name' => 'Cherokee Country Club',
    'location' => 'Knoxville, TN',
    'description' => 'Donald Ross designed private country club in Knoxville, TN. Classic 6,418-yard championship golf course established in 1907.',
    'image' => '/images/courses/cherokee-country-club/1.jpeg',
    'holes' => 18,
    'par' => 71,
    'designer' => 'Donald Ross',
    'year_built' => 1907,
    'course_type' => 'Private'
];

SEO::setupCoursePage($course_data);

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - user not logged in
}

$course_slug = 'cherokee-country-club';
$course_name = 'Cherokee Country Club';

// Calculate rating data for header display
try {
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM course_comments WHERE course_slug = ? AND parent_comment_id IS NULL AND rating IS NOT NULL");
    $stmt->execute([$course_slug]);
    $rating_data = $stmt->fetch();
    $avg_rating = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : null;
    $total_reviews = $rating_data['total_reviews'] ?: 0;
} catch (PDOException $e) {
    $avg_rating = null;
    $total_reviews = 0;
}

// Check if user is logged in using secure session

// Check for success message from redirect
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success_message = "Your review has been posted successfully!";
}
$is_logged_in = SecureSession::isLoggedIn();

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    // Validate CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!CSRFProtection::validateToken($csrf_token)) {
        $error_message = 'Security token expired or invalid. Please refresh the page and try again.';
    } else {
        $rating = floatval($_POST['rating']);
        $comment_text = trim($_POST['comment_text']);
        $user_id = SecureSession::get('user_id');
        
        if ($rating >= 1 && $rating <= 5 && !empty($comment_text)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO course_comments (user_id, course_slug, course_name, rating, comment_text) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $course_slug, $course_name, $rating, $comment_text]);
                // Redirect to prevent duplicate submission on refresh (PRG pattern)
                header("Location: " . $_SERVER['REQUEST_URI'] . "?success=1");
                exit;
            } catch (PDOException $e) {
                $error_message = "Error posting review. Please try again.";
            }
        } else {
            $error_message = "Please provide a valid rating and comment.";
        }
    }
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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BSFPY01T7C"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-BSFPY01T7C');
    </script>
    
    <style>
        .course-hero {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/cherokee-country-club/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
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
            animation: fadeIn 0.3s;
        }
        
        .modal-content {
            max-width: 1400px;
            width: 95%;
            margin: 2rem auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s;
        }
        
        .modal-header {
            background: #2c5234;
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }
        
        .close {
            font-size: 2rem;
            cursor: pointer;
            background: none;
            border: none;
            color: white;
            transition: transform 0.3s;
        }
        
        .close:hover {
            transform: scale(1.2);
        }
        
        .full-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }
        
        .full-gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            aspect-ratio: 4/3;
        }
        
        .full-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .full-gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                transform: translateY(50px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @media (max-width: 768px) {
            .modal-content {
                width: 100%;
                margin: 0;
                border-radius: 0;
                max-height: 100vh;
            }
            
            .full-gallery-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>
    
    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Cherokee Country Club</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Donald Ross Design • Knoxville, Tennessee</p>
            <div class="course-rating" style="display: flex; align-items: center; justify-content: center; gap: 1rem;">
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
                        <i class="fas fa-star-o" style="color: #999; margin-right: 8px;"></i>
                        <span class="rating-text" style="color: #666;">No ratings yet - Be the first to review!</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Course Details -->
    <section class="course-details" style="padding: 4rem 0; background: white;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 4rem; margin-bottom: 4rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 2rem;">About Cherokee Country Club</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        Cherokee Country Club stands as one of Tennessee's most prestigious private golf clubs, featuring a masterful Donald Ross design from 1907. This historic championship course has hosted numerous Tennessee State Golf Association events and continues to challenge golfers with its classic layout and strategic design.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555;">
                        The 6,418-yard par 71 layout showcases Ross's signature design elements including crowned greens, strategic bunkering, and thoughtful routing through rolling terrain. The course demands precision and strategy, rewarding well-placed shots while punishing errant ones. Cherokee's pristine conditions and timeless design make it a true gem of Tennessee golf.
                    </p>
                </div>
                <div class="course-info-card" style="background: #f8f9fa; padding: 2rem; border-radius: 15px;">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Course Information</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #e0e0e0;"><strong>Designer:</strong> Donald Ross</li>
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #e0e0e0;"><strong>Year Built:</strong> 1907</li>
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #e0e0e0;"><strong>Par:</strong> 71</li>
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #e0e0e0;"><strong>Yardage:</strong> 6,418 yards</li>
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #e0e0e0;"><strong>Course Rating:</strong> 71.5</li>
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #e0e0e0;"><strong>Slope Rating:</strong> 133</li>
                        <li style="padding: 0.75rem 0;"><strong>Type:</strong> Private</li>
                    </ul>
                </div>
            </div>
            
            <!-- Amenities Section -->
            <div class="amenities-section" style="margin-bottom: 4rem;">
                <h2 style="color: #2c5234; margin-bottom: 2rem;">Club Amenities</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.5rem;"></i>
                        <span>Championship Golf Course</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-home" style="color: #4a7c59; font-size: 1.5rem;"></i>
                        <span>Elegant Clubhouse</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.5rem;"></i>
                        <span>Fine Dining</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-swimming-pool" style="color: #4a7c59; font-size: 1.5rem;"></i>
                        <span>Swimming Pool</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-dumbbell" style="color: #4a7c59; font-size: 1.5rem;"></i>
                        <span>Tennis Courts</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-graduation-cap" style="color: #4a7c59; font-size: 1.5rem;"></i>
                        <span>Golf Academy</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Photo Gallery Section -->
    <section class="photo-gallery" style="padding: 4rem 0; background: #f8f9fa;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <h2 style="text-align: center; margin-bottom: 3rem; color: #2c5234;">Course Gallery</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/cherokee-country-club/1.jpeg" alt="Cherokee Country Club - Hole 1" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/cherokee-country-club/2.jpeg" alt="Cherokee Country Club - Clubhouse" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/cherokee-country-club/3.jpeg" alt="Cherokee Country Club - Green Complex" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
            </div>
            <div style="text-align: center;">
                <button onclick="openGallery()" style="background: #4a7c59; color: white; padding: 1rem 2rem; border: none; border-radius: 50px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-images" style="margin-right: 0.5rem;"></i> View Full Gallery (25 Photos)
                </button>
            </div>
        </div>
    </section>
    
    <!-- Share Section -->
    <section class="share-section" style="padding: 3rem 0; background: white;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="text-align: center;">
                <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Share This Course</h3>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/courses/cherokee-country-club" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Check%20out%20Cherokee%20Country%20Club&url=https://tennesseegolfcourses.com/courses/cherokee-country-club" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1da1f2; color: white;">
                        <i class="fab fa-twitter"></i> Share on Twitter
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Cherokee Country Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/cherokee-country-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <?php 
    // Variables needed for the centralized review system
    // $course_slug and $course_name are already set at the top of this file
    include '../includes/course-reviews-fixed.php'; 
    ?>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Cherokee Country Club - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
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
        
        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Generate all 25 images
            for (let i = 1; i <= 25; i++) {
                const item = document.createElement('div');
                item.className = 'full-gallery-item';
                
                const img = document.createElement('img');
                img.src = `../images/courses/cherokee-country-club/${i}.jpeg`;
                img.alt = `Cherokee Country Club - Photo ${i}`;
                img.loading = 'lazy';
                
                item.appendChild(img);
                galleryGrid.appendChild(item);
            }
            
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeGallery() {
            const modal = document.getElementById('galleryModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside content
        window.onclick = function(event) {
            const modal = document.getElementById('galleryModal');
            if (event.target == modal) {
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
</body>
</html>