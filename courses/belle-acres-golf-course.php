<?php
session_start();
require_once '../config/database.php';

$course_slug = 'belle-acres-golf-course';
$course_name = 'Belle Acres Golf Course';

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
    <title>Belle Acres Golf Course - Tennessee Golf Courses</title>
    <meta name="description" content="Belle Acres Golf Course - Cookeville's oldest golf course since 1930. Dr. J.P. Terry designed 9-hole par 35 course with bent grass greens and largest practice facility in the area.">
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
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/belle-acres-golf-course/1.webp'); 
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Belle Acres Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Dr. J.P. Terry Design • Cookeville, Tennessee</p>
            <div class="course-rating" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
                <?php if ($avg_rating): ?>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="display: flex;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star" style="color: <?= $i <= $avg_rating ? '#ffd700' : '#666' ?>; font-size: 1.2rem;"></i>
                            <?php endfor; ?>
                        </div>
                        <span style="font-size: 1.1rem; font-weight: 600;"><?= $avg_rating ?></span>
                        <span style="opacity: 0.8;">(<?= $total_reviews ?> review<?= $total_reviews !== 1 ? 's' : '' ?>)</span>
                    </div>
                <?php else: ?>
                    <span style="opacity: 0.8;">No reviews yet</span>
                <?php endif; ?>
            </div>
            <a href="#course-info" style="background: #4a7c59; color: white; padding: 1rem 2rem; border-radius: 50px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: inline-block;">Explore Course Details</a>
        </div>
    </section>

    <!-- Course Information Section -->
    <section id="course-info" style="padding: 4rem 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem; margin-bottom: 4rem;">
            <!-- Quick Stats -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> Course Details</h3>
                <div class="course-specs" style="display: grid; gap: 1rem;">
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Holes:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">9</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Par:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">35</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Yardage:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">2,934 yards</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Course Type:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">Public</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Designer:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">Dr. J.P. Terry</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Year Opened:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">1930</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Slope Rating:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">111</span>
                    </div>
                </div>
            </div>

            <!-- Green Fees -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1rem 0;">
                    <div style="margin-bottom: 1rem;">
                        <h5 style="margin: 0 0 0.5rem 0; color: #2c5234; font-size: 1rem;"><strong>Weekdays (Mon-Fri)</strong></h5>
                        <div style="font-size: 0.9rem; color: #666;">
                            <div>• 9 Holes w/ Cart: $21.00</div>
                            <div>• 9 Holes Walking: $10.50</div>
                            <div>• 18 Holes w/ Cart: $29.40</div>
                            <div>• 18 Holes Walking: $16.80</div>
                        </div>
                    </div>
                    <div style="border-top: 1px solid #ddd; padding-top: 1rem;">
                        <h5 style="margin: 0 0 0.5rem 0; color: #2c5234; font-size: 1rem;"><strong>Weekends & Holidays</strong></h5>
                        <div style="font-size: 0.9rem; color: #666;">
                            <div>• 9 Holes w/ Cart: $23.10</div>
                            <div>• 9 Holes Walking: $12.60</div>
                            <div>• 18 Holes w/ Cart: $32.55</div>
                            <div>• 18 Holes Walking: $19.95</div>
                        </div>
                    </div>
                    <div style="border-top: 1px solid #ddd; padding-top: 1rem; margin-top: 1rem;">
                        <div style="font-size: 0.85rem; color: #666; text-align: center;">
                            <strong>Special Rates:</strong> Senior (55+), Military & Tech discounts available
                        </div>
                    </div>
                </div>
                <p style="text-align: center; color: #666; margin-top: 1rem; font-size: 0.9rem;">
                    Call (931) 310-0645 for tee times and current specials.
                </p>
            </div>

            <!-- Contact & Location -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                <div class="course-specs single-column" style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Address:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">901 E Broad St</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">City:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">Cookeville, TN</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Phone:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">(931) 310-0645</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Website:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;"><a href="https://belleacresgolf.com" target="_blank" style="color: #2c5234;">Visit Site</a></span>
                    </div>
                </div>
                
                <div class="course-map" style="margin-top: 1.5rem;">
                    <iframe 
                        src="https://maps.google.com/maps?q=901+E+Broad+St,+Cookeville,+TN&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                        width="100%" 
                        height="200" 
                        style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Belle Acres Golf Course Location">
                    </iframe>
                    <div style="margin-top: 0.5rem; text-align: center;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=901+E+Broad+St,+Cookeville,+TN" 
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
            <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Belle Acres Golf Course</h3>
            <p>Belle Acres Golf Course holds the distinction of being Cookeville's oldest golf course, having served the community since May 1, 1930. Built by Dr. J.P. Terry with assistance from Arnold Mears, a professional from Richland Golf Course in Nashville, this historic 9-hole course has evolved significantly from its humble beginnings with sand greens to become a modern golfing facility with bent grass greens.</p>
            
            <br>
            
            <p>The course originally opened with just 7 holes featuring sand greens, but within a year was expanded to 9 holes. Over the decades, the playing surfaces have undergone several transformations - from sand greens to cotton-seed hulls, then to Bermuda grass (which presented crabgrass challenges), and finally to the current bent grass greens that provide excellent playing conditions year-round.</p>
            
            <br>
            
            <p>Today's Belle Acres offers golfers a challenging yet accessible 9-hole, par-35 experience stretching 2,934 yards from the championship tees. The course features three different tee options to accommodate players of all skill levels, with slope ratings ranging from 109 to 111. What truly sets Belle Acres apart is its practice facilities - boasting the largest practice facility in the Cookeville area, making it an ideal destination for both casual rounds and serious practice sessions.</p>
            
            <br>
            
            <p>The course maintains a strong community focus with its popular weekly A-B-C-D Scramble every Tuesday at 5:00 PM, where golfers can sign up in person by 4:30 PM and compete for prizes. This tradition exemplifies Belle Acres' commitment to fostering local golf culture while providing an affordable, quality golf experience in the heart of Tennessee.</p>
        </div>

        <!-- Tee Information -->
        <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
            <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-chart-line"></i> Tee Options & Ratings</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #2c5234;">
                    <h4 style="color: #2c5234; margin-bottom: 1rem;">Championship Tees</h4>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                        <div><strong>Yardage:</strong> 2,934</div>
                        <div><strong>Par:</strong> 35</div>
                        <div><strong>Slope:</strong> 111</div>
                        <div><strong>Holes:</strong> 9</div>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #4a7c59;">
                    <h4 style="color: #2c5234; margin-bottom: 1rem;">Middle Tees</h4>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                        <div><strong>Yardage:</strong> 2,745</div>
                        <div><strong>Par:</strong> 35</div>
                        <div><strong>Slope:</strong> 109</div>
                        <div><strong>Holes:</strong> 9</div>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #ffc107;">
                    <h4 style="color: #2c5234; margin-bottom: 1rem;">Forward Tees</h4>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                        <div><strong>Yardage:</strong> 2,290</div>
                        <div><strong>Par:</strong> 35</div>
                        <div><strong>Slope:</strong> 110</div>
                        <div><strong>Holes:</strong> 9</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amenities -->
        <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
            <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Course Amenities</h3>
            <div class="amenities-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; justify-items: center;">
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>9-Hole Course</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-bullseye" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Largest Practice Facility</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-leaf" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Bent Grass Greens</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Range Balls Available</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-shopping-cart" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Cart Rentals</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-walking" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Walking Friendly</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-users" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Weekly Scrambles</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-history" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Historic Course (1930)</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-tee" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Multiple Tee Options</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-medal" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Metal Spikes Allowed</span>
                </div>
            </div>
        </div>

        <!-- Course Gallery -->
        <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
            <h3 style="color: #2c5234; margin-bottom: 2rem; font-size: 1.5rem;"><i class="fas fa-camera"></i> Course Gallery</h3>
            <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <div class="gallery-item" style="position: relative; aspect-ratio: 4/3; overflow: hidden; border-radius: 10px; cursor: pointer;" onclick="openGallery(<?= $i ?>)">
                        <img src="../images/courses/belle-acres-golf-course/<?= $i ?>.webp" 
                             alt="Belle Acres Golf Course Image <?= $i ?>" 
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                             onmouseover="this.style.transform='scale(1.05)'"
                             onmouseout="this.style.transform='scale(1)'">
                        <div style="position: absolute; inset: 0; background: linear-gradient(45deg, rgba(0,0,0,0.1), rgba(0,0,0,0)); transition: background 0.3s ease;" 
                             onmouseover="this.style.background='linear-gradient(45deg, rgba(0,0,0,0.3), rgba(0,0,0,0.1))'"
                             onmouseout="this.style.background='linear-gradient(45deg, rgba(0,0,0,0.1), rgba(0,0,0,0))'"></div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h3 style="color: #2c5234; margin: 0; font-size: 1.5rem;"><i class="fas fa-star"></i> Course Reviews</h3>
                <?php if ($is_logged_in): ?>
                    <button onclick="showReviewForm()" style="background: #4a7c59; color: white; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; font-weight: 600;">Write a Review</button>
                <?php else: ?>
                    <a href="../login" style="background: #4a7c59; color: white; padding: 0.5rem 1rem; border-radius: 5px; text-decoration: none; font-weight: 600;">Login to Review</a>
                <?php endif; ?>
            </div>

            <?php if (isset($success_message)): ?>
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
                    <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <!-- Review Form -->
            <?php if ($is_logged_in): ?>
            <div id="reviewForm" style="display: none; background: #f8f9fa; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
                <h4 style="color: #2c5234; margin-bottom: 1rem;">Share Your Experience</h4>
                <form method="POST">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Rating:</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" name="rating" value="<?= $i ?>" id="rating<?= $i ?>" required style="display: none;">
                                <label for="rating<?= $i ?>" style="cursor: pointer; font-size: 1.5rem; color: #ddd;" onmouseover="highlightStars(<?= $i ?>)" onclick="selectRating(<?= $i ?>)">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label for="comment_text" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Review:</label>
                        <textarea name="comment_text" id="comment_text" rows="4" required 
                                style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; resize: vertical;"
                                placeholder="Share your thoughts about Belle Acres Golf Course..."></textarea>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" style="background: #4a7c59; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 5px; cursor: pointer; font-weight: 600;">Submit Review</button>
                        <button type="button" onclick="hideReviewForm()" style="background: #6c757d; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 5px; cursor: pointer; font-weight: 600;">Cancel</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- Display Reviews -->
            <div style="space-y: 1.5rem;">
                <?php if (empty($comments)): ?>
                    <p style="text-align: center; color: #666; font-style: italic; padding: 2rem;">No reviews yet. Be the first to share your experience!</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div style="border-bottom: 1px solid #eee; padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <div>
                                    <div style="font-weight: 600; color: #2c5234;"><?= htmlspecialchars($comment['username']) ?></div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                        <div style="display: flex;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span style="color: <?= $i <= $comment['rating'] ? '#ffd700' : '#ddd' ?>; font-size: 0.9rem;">★</span>
                                            <?php endfor; ?>
                                        </div>
                                        <span style="font-size: 0.85rem; color: #666;"><?= date('M j, Y', strtotime($comment['created_at'])) ?></span>
                                    </div>
                                </div>
                            </div>
                            <p style="color: #555; line-height: 1.5; margin: 0;"><?= nl2br(htmlspecialchars($comment['comment_text'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div id="galleryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 1000; justify-content: center; align-items: center;">
        <div style="position: relative; max-width: 90%; max-height: 90%;">
            <img id="galleryImage" src="" alt="Course Image" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            <button onclick="closeGallery()" style="position: absolute; top: -40px; right: 0; background: none; border: none; color: white; font-size: 2rem; cursor: pointer; padding: 0.5rem;">×</button>
            <button onclick="prevImage()" style="position: absolute; left: -50px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; color: white; font-size: 2rem; cursor: pointer; padding: 0.5rem; border-radius: 50%;">‹</button>
            <button onclick="nextImage()" style="position: absolute; right: -50px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; color: white; font-size: 2rem; cursor: pointer; padding: 0.5rem; border-radius: 50%;">›</button>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script>
        let currentImageIndex = 1;
        const totalImages = 25;

        function openGallery(imageIndex) {
            currentImageIndex = imageIndex;
            document.getElementById('galleryImage').src = `../images/courses/belle-acres-golf-course/${imageIndex}.webp`;
            document.getElementById('galleryModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function nextImage() {
            currentImageIndex = currentImageIndex < totalImages ? currentImageIndex + 1 : 1;
            document.getElementById('galleryImage').src = `../images/courses/belle-acres-golf-course/${currentImageIndex}.webp`;
        }

        function prevImage() {
            currentImageIndex = currentImageIndex > 1 ? currentImageIndex - 1 : totalImages;
            document.getElementById('galleryImage').src = `../images/courses/belle-acres-golf-course/${currentImageIndex}.webp`;
        }

        function showReviewForm() {
            document.getElementById('reviewForm').style.display = 'block';
        }

        function hideReviewForm() {
            document.getElementById('reviewForm').style.display = 'none';
        }

        function highlightStars(rating) {
            for (let i = 1; i <= 5; i++) {
                const star = document.querySelector(`label[for="rating${i}"]`);
                star.style.color = i <= rating ? '#ffd700' : '#ddd';
            }
        }

        function selectRating(rating) {
            document.getElementById(`rating${rating}`).checked = true;
            for (let i = 1; i <= 5; i++) {
                const star = document.querySelector(`label[for="rating${i}"]`);
                star.style.color = i <= rating ? '#ffd700' : '#ddd';
            }
        }

        // Close gallery with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeGallery();
            }
        });

        // Close gallery when clicking outside image
        document.getElementById('galleryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeGallery();
            }
        });
    </script>
</body>
</html>