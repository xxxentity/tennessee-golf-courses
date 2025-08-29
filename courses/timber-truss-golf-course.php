<?php
session_start();
require_once '../config/database.php';
require_once '../includes/seo.php';

// Course data for SEO
$course_data = [
    'name' => 'Timber Truss Golf Course',
    'location' => 'Olive Branch, MS',
    'description' => 'Championship golf course in Olive Branch, MS. Formerly Plantation Golf Club, this 180-acre property offers modern amenities with classic charm and GPS-equipped carts.',
    'image' => '/images/courses/timber-truss-golf-course/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'N/A',
    'year_built' => 'N/A',
    'course_type' => 'Public'
];

SEO::setupCoursePage($course_data);

$course_slug = 'timber-truss-golf-course';
$course_name = 'Timber Truss Golf Course';

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
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate average rating
    $avg_rating = 0;
    if (!empty($comments)) {
        $total_rating = array_sum(array_column($comments, 'rating'));
        $avg_rating = round($total_rating / count($comments), 1);
    }
} catch (PDOException $e) {
    $comments = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo SEO::generateMetaTags(); ?>
    <link rel="stylesheet" href="/styles.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    
    <?php include '../includes/favicon.php'; ?>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        /* Course Page Specific Styles */
        body {
            background: var(--bg-light);
        }
        
        .photo-gallery {
            padding: 3rem 1rem;
            background: var(--bg-white);
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
            border-radius: 25px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-gallery:hover {
            background: #5a9069;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 124, 89, 0.3);
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.9);
        }
        
        .modal-content {
            position: relative;
            margin: 2% auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
        }
        
        .close {
            color: white;
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10001;
        }
        
        .close:hover,
        .close:focus {
            color: #999;
            text-decoration: none;
        }
        
        .full-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
        }
        
        .full-gallery-item {
            width: 100%;
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
        
        .modal-header {
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .weather-widget {
            padding: 0;
            margin: 0;
            background: #1e3a8a;
            color: white;
        }
        
        .weather-content {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            gap: 1rem;
            font-size: 0.9rem;
        }
        
        .weather-icon {
            font-size: 1.2rem;
        }
        
        .weather-info {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        
        .weather-item {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        @media (max-width: 768px) {
            .weather-content {
                font-size: 0.8rem;
            }
            
            .weather-info {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Weather Widget -->
    <div class="weather-widget">
        <div class="weather-content">
            <i class="fas fa-cloud-sun weather-icon"></i>
            <div class="weather-info">
                <span class="weather-item">
                    <i class="fas fa-location-dot"></i>
                    <span id="weather-location">Olive Branch, MS</span>
                </span>
                <span class="weather-item">
                    <i class="fas fa-temperature-high"></i>
                    <span id="weather-temp">Loading...</span>
                </span>
                <span class="weather-item">
                    <span id="weather-conditions">Loading weather...</span>
                </span>
            </div>
        </div>
    </div>

    <?php include '../includes/header.php'; ?>

    <!-- Course Header -->
    <div class="course-header" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/courses/timber-truss-golf-course/1.webp') center/cover; padding: 100px 20px 50px; color: white; text-align: center;">
        <div class="container">
            <h1 style="font-size: 3rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);"><?php echo htmlspecialchars($course_name); ?></h1>
            <p style="font-size: 1.3rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Championship Golf Course in Olive Branch, Mississippi</p>
            <div style="margin-top: 2rem; display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap;">
                <div><i class="fas fa-flag"></i> 18 Holes</div>
                <div><i class="fas fa-golf-ball"></i> Par 72</div>
                <div><i class="fas fa-ruler"></i> 6,773 Yards</div>
                <div><i class="fas fa-globe"></i> Public Course</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container" style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem; margin-bottom: 3rem;">
            <!-- Left Column - Course Info -->
            <div>
                <!-- Course Overview -->
                <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                    <h2 style="color: #2c5530; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 3px solid #4a7c59;">
                        <i class="fas fa-info-circle"></i> Course Overview
                    </h2>
                    <p style="line-height: 1.8; color: #333; margin-bottom: 1.5rem;">
                        Timber Truss Golf Course, formerly known as Plantation Golf Club, is a premier 180-acre golf destination in Olive Branch, Mississippi. This championship course combines rich legacy with modern amenities to create an exceptional golfing experience for players of all skill levels.
                    </p>
                    <p style="line-height: 1.8; color: #333; margin-bottom: 1.5rem;">
                        The course features meticulously maintained fairways and greens spread across a beautifully landscaped property. With GPS-equipped golf carts and a fully stocked pro shop, Timber Truss provides all the modern conveniences while maintaining the classic charm of traditional golf.
                    </p>
                    <p style="line-height: 1.8; color: #333;">
                        Whether you're looking for a challenging round, professional instruction, or a venue for your next tournament, Timber Truss Golf Course offers a comprehensive golf experience. The course's motto "Once Found, Forever Golfing" reflects their commitment to creating memorable experiences that keep golfers returning season after season.
                    </p>
                </div>

                <!-- Course Details -->
                <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                    <h2 style="color: #2c5530; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 3px solid #4a7c59;">
                        <i class="fas fa-list"></i> Course Details
                    </h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div>
                            <h4 style="color: #4a7c59; margin-bottom: 0.5rem;">Course Statistics</h4>
                            <ul style="list-style: none; padding: 0;">
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;"><strong>Par:</strong> 72</li>
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;"><strong>Yardage (Gold):</strong> 6,773 yards</li>
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;"><strong>Yardage (Blue):</strong> 6,178 yards</li>
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;"><strong>Yardage (White):</strong> 5,683 yards</li>
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;"><strong>Yardage (Red):</strong> 5,055 yards</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: #4a7c59; margin-bottom: 0.5rem;">Course Ratings</h4>
                            <ul style="list-style: none; padding: 0;">
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;"><strong>Rating (Gold):</strong> 72.7</li>
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;"><strong>Slope (Gold):</strong> 125</li>
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;"><strong>Rating (Blue):</strong> 70.0</li>
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;"><strong>Slope (Blue):</strong> 124</li>
                                <li style="padding: 0.5rem 0;"><strong>Type:</strong> Public</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                    <h2 style="color: #2c5530; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 3px solid #4a7c59;">
                        <i class="fas fa-star"></i> Amenities & Services
                    </h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div style="padding: 1rem; background: #f8f8f8; border-radius: 10px;">
                            <i class="fas fa-shopping-bag" style="color: #4a7c59; margin-right: 0.5rem;"></i>
                            Fully Stocked Pro Shop
                        </div>
                        <div style="padding: 1rem; background: #f8f8f8; border-radius: 10px;">
                            <i class="fas fa-golf-ball" style="color: #4a7c59; margin-right: 0.5rem;"></i>
                            TrackMan Simulator
                        </div>
                        <div style="padding: 1rem; background: #f8f8f8; border-radius: 10px;">
                            <i class="fas fa-graduation-cap" style="color: #4a7c59; margin-right: 0.5rem;"></i>
                            Golf Lessons Available
                        </div>
                        <div style="padding: 1rem; background: #f8f8f8; border-radius: 10px;">
                            <i class="fas fa-cart-arrow-down" style="color: #4a7c59; margin-right: 0.5rem;"></i>
                            GPS-Equipped Carts
                        </div>
                        <div style="padding: 1rem; background: #f8f8f8; border-radius: 10px;">
                            <i class="fas fa-home" style="color: #4a7c59; margin-right: 0.5rem;"></i>
                            Classic Clubhouse
                        </div>
                        <div style="padding: 1rem; background: #f8f8f8; border-radius: 10px;">
                            <i class="fas fa-utensils" style="color: #4a7c59; margin-right: 0.5rem;"></i>
                            Snack Bar & Lounge
                        </div>
                        <div style="padding: 1rem; background: #f8f8f8; border-radius: 10px;">
                            <i class="fas fa-trophy" style="color: #4a7c59; margin-right: 0.5rem;"></i>
                            Tournament Hosting
                        </div>
                        <div style="padding: 1rem; background: #f8f8f8; border-radius: 10px;">
                            <i class="fas fa-users" style="color: #4a7c59; margin-right: 0.5rem;"></i>
                            Events & Outings
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Info & Map -->
            <div>
                <!-- Quick Info Card -->
                <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem; position: sticky; top: 20px;">
                    <h3 style="color: #2c5530; margin-bottom: 1.5rem; text-align: center;">Quick Information</h3>
                    
                    <!-- Contact Info -->
                    <div style="margin-bottom: 1.5rem;">
                        <p style="margin: 0.5rem 0;"><i class="fas fa-map-marker-alt" style="color: #4a7c59; width: 20px;"></i> 9425 Plantation Road</p>
                        <p style="margin: 0.5rem 0;"><i class="fas fa-city" style="color: #4a7c59; width: 20px;"></i> Olive Branch, MS 38654</p>
                        <p style="margin: 0.5rem 0;"><i class="fas fa-phone" style="color: #4a7c59; width: 20px;"></i> (662) 895-3530</p>
                        <p style="margin: 0.5rem 0;"><i class="fas fa-globe" style="color: #4a7c59; width: 20px;"></i> <a href="https://timbertrussgolf.com" target="_blank" style="color: #4a7c59;">timbertrussgolf.com</a></p>
                    </div>

                    <!-- Pricing -->
                    <div style="background: #f8f8f8; border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5530; margin-bottom: 1rem;">Green Fees</h4>
                        <p style="margin: 0.5rem 0;"><strong>Weekday:</strong> $32-$42</p>
                        <p style="margin: 0.5rem 0;"><strong>Friday:</strong> $40-$50</p>
                        <p style="margin: 0.5rem 0;"><strong>Weekend:</strong> $60-$75</p>
                        <p style="margin: 0.5rem 0;"><strong>Senior (Weekday):</strong> $35</p>
                        <p style="margin: 0.5rem 0; font-size: 0.9rem; color: #666;">*Rates vary by tee time</p>
                    </div>

                    <!-- Book Tee Time Button -->
                    <a href="https://timbertrussgolf.com" target="_blank" style="display: block; background: #4a7c59; color: white; text-align: center; padding: 1rem; border-radius: 10px; text-decoration: none; font-weight: bold; transition: background 0.3s ease;">
                        <i class="fas fa-calendar-check"></i> Book Tee Time
                    </a>
                </div>
            </div>
        </div>

        <!-- Photo Gallery Section -->
        <section class="photo-gallery">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="color: #2c5530; font-size: 2.5rem; margin-bottom: 1rem;">Course Gallery</h2>
                <p style="color: #666;">Experience the beauty of Timber Truss Golf Course</p>
            </div>
            <div class="gallery-grid">
                <img src="../images/courses/timber-truss-golf-course/1.webp" alt="Timber Truss Golf Course - Golf Course Overview" class="gallery-item">
                <img src="../images/courses/timber-truss-golf-course/2.webp" alt="Timber Truss Golf Course - Fairway and Green View" class="gallery-item">
                <img src="../images/courses/timber-truss-golf-course/3.webp" alt="Timber Truss Golf Course - Course Landscape" class="gallery-item">
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </section>

        <!-- Google Maps Section -->
        <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h2 style="color: #2c5530; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 3px solid #4a7c59;">
                <i class="fas fa-map"></i> Course Location
            </h2>
            <div style="border-radius: 15px; overflow: hidden; height: 450px;">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3256.123456789!2d-89.8569!3d34.8989!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sTimber+Truss+Golf+Course+Olive+Branch+MS!5e0!3m2!1sen!2sus!4v1234567890" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <!-- Reviews Section -->
        <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #2c5530; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 3px solid #4a7c59;">
                <i class="fas fa-comments"></i> Reviews & Ratings
            </h2>
            
            <?php if (!empty($comments)): ?>
                <div style="text-align: center; margin-bottom: 2rem; padding: 1rem; background: #f8f8f8; border-radius: 10px;">
                    <div style="font-size: 2rem; color: #4a7c59; margin-bottom: 0.5rem;">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star" style="color: <?php echo $i <= $avg_rating ? '#ffc107' : '#ddd'; ?>;"></i>
                        <?php endfor; ?>
                    </div>
                    <p style="color: #666;">Average Rating: <?php echo $avg_rating; ?>/5 (<?php echo count($comments); ?> reviews)</p>
                </div>
            <?php endif; ?>

            <?php if (isset($success_message)): ?>
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 10px; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($is_logged_in): ?>
                <!-- Review Form -->
                <form method="POST" style="background: #f8f8f8; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1rem;">Leave a Review</h3>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem;">Rating:</label>
                        <select name="rating" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="">Select a rating</option>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Very Good</option>
                            <option value="3">3 - Good</option>
                            <option value="2">2 - Fair</option>
                            <option value="1">1 - Poor</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem;">Your Review:</label>
                        <textarea name="comment_text" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; min-height: 100px;"></textarea>
                    </div>
                    <button type="submit" style="background: #4a7c59; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 5px; cursor: pointer;">
                        Submit Review
                    </button>
                </form>
            <?php else: ?>
                <div style="background: #f8f8f8; padding: 1.5rem; border-radius: 10px; text-align: center; margin-bottom: 2rem;">
                    <p>Please <a href="/login" style="color: #4a7c59;">login</a> to leave a review</p>
                </div>
            <?php endif; ?>

            <!-- Display Reviews -->
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div style="border-bottom: 1px solid #eee; padding: 1.5rem 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                            <div>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star" style="color: <?php echo $i <= $comment['rating'] ? '#ffc107' : '#ddd'; ?>; font-size: 0.9rem;"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p style="color: #666; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                        <small style="color: #999;"><?php echo date('F j, Y', strtotime($comment['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">No reviews yet. Be the first to review this course!</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="modal">
        <span class="close" onclick="closeGallery()">&times;</span>
        <div class="modal-content">
            <div class="modal-header">
                <h2>Timber Truss Golf Course - Complete Photo Gallery</h2>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Gallery items will be loaded dynamically -->
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Scripts -->
    <script>
        // Gallery Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Load all 25 images
            for (let i = 1; i <= 25; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/timber-truss-golf-course/${i}.webp')`;
                galleryItem.onclick = () => window.open(`../images/courses/timber-truss-golf-course/${i}.webp`, '_blank');
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
        document.getElementById('galleryModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeGallery();
            }
        });
        
        // Weather Widget
        async function loadWeather() {
            try {
                // Using Olive Branch, MS coordinates
                const lat = 34.8989;
                const lon = -89.8569;
                const response = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&temperature_unit=fahrenheit`);
                const data = await response.json();
                
                const temp = Math.round(data.current_weather.temperature);
                const weatherCode = data.current_weather.weathercode;
                
                // Weather descriptions
                const weatherDescriptions = {
                    0: 'Clear',
                    1: 'Mostly Clear', 
                    2: 'Partly Cloudy',
                    3: 'Overcast',
                    45: 'Foggy',
                    48: 'Foggy',
                    51: 'Light Drizzle',
                    53: 'Drizzle',
                    55: 'Heavy Drizzle',
                    61: 'Light Rain',
                    63: 'Rain',
                    65: 'Heavy Rain',
                    71: 'Light Snow',
                    73: 'Snow',
                    75: 'Heavy Snow',
                    77: 'Snow Grains',
                    80: 'Light Showers',
                    81: 'Showers',
                    82: 'Heavy Showers',
                    85: 'Light Snow Showers',
                    86: 'Snow Showers',
                    95: 'Thunderstorm',
                    96: 'Thunderstorm with Hail',
                    99: 'Thunderstorm with Heavy Hail'
                };
                
                const description = weatherDescriptions[weatherCode] || 'Unknown';
                
                document.getElementById('weather-temp').textContent = `${temp}°F`;
                document.getElementById('weather-conditions').textContent = description;
            } catch (error) {
                document.getElementById('weather-temp').textContent = 'N/A';
                document.getElementById('weather-conditions').textContent = 'Weather unavailable';
            }
        }
        
        // Load weather on page load
        loadWeather();
        // Refresh weather every 10 minutes
        setInterval(loadWeather, 600000);
    </script>
</body>
</html>