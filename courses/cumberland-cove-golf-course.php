<?php
session_start();
require_once '../config/database.php';
require_once '../includes/seo.php';

// Course data for SEO
$course_data = [
    'name' => 'Cumberland Cove Golf Course',
    'location' => 'Monterey, TN',
    'description' => '18-hole championship course in Monterey, TN. Gary Roger Baird designed back nine with bent grass greens and scenic mountain views since 1967.',
    'image' => '/images/courses/cumberland-cove-golf-course/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Gary Roger Baird',
    'year_built' => 1967,
    'course_type' => 'Public'
];

SEO::setupCoursePage($course_data);

$course_slug = 'cumberland-cove-golf-course';
$course_name = 'Cumberland Cove Golf Course';

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
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=6">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=6">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    
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
                galleryItem.style.backgroundImage = `url('../images/courses/cumberland-cove-golf-course/${i}.webp')`;
                galleryItem.onclick = () => window.open(`../images/courses/cumberland-cove-golf-course/${i}.webp`, '_blank');
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
        });\n    </script>
    
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
            background-size: cover;
            background-position: center;
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
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/cumberland-cove-golf-course/1.webp'); 
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Cumberland Cove Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Gary Roger Baird Design • Monterey, Tennessee</p>
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
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">18</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Par:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">72</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Yardage:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">6,675 yards</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Course Type:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">Public</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Designer:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">Gary Roger Baird</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Year Opened:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">1967</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Course Rating:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">71.7</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Slope Rating:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">126</span>
                    </div>
                </div>
            </div>

            <!-- Green Fees -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                
                <div style="background: linear-gradient(135deg, #4a7c59 0%, #2c5234 100%); color: white; padding: 1.5rem; border-radius: 8px; margin: 1rem 0; text-align: center; box-shadow: 0 4px 15px rgba(74, 124, 89, 0.3);">
                    <h4 style="margin: 0 0 0.5rem 0; font-size: 1.1rem;">Call for Current Rates</h4>
                    <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">(931) 839-3313</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1rem 0;">
                    <div style="margin-bottom: 1rem;">
                        <h5 style="margin: 0 0 0.5rem 0; color: #2c5234; font-size: 1rem;"><strong>Walking Policy</strong></h5>
                        <div style="font-size: 0.9rem; color: #666;">
                            <div>• Walking allowed anytime</div>
                            <div>• Weekend restrictions before 2pm may apply</div>
                        </div>
                    </div>
                    <div style="border-top: 1px solid #ddd; padding-top: 1rem;">
                        <div style="font-size: 0.9rem; color: #666; text-align: center;">
                            <strong>Payment:</strong> VISA and MasterCard accepted
                        </div>
                    </div>
                </div>
                <p style="text-align: center; color: #666; margin-top: 1rem; font-size: 0.9rem;">
                    Contact course directly for tee times and current specials.
                </p>
            </div>

            <!-- Contact & Location -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                <div class="course-specs single-column" style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Address:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">16941 Highway 70 N</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">City:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">Monterey, TN 38574</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Phone:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">(931) 839-3313</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Email:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;">info@cumberlandcovegolf.com</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                        <span class="spec-label" style="font-weight: 600; color: #666;">Website:</span>
                        <span class="spec-value" style="font-weight: 700; color: #2c5234;"><a href="https://cumberlandcovegolf.com" target="_blank" style="color: #2c5234;">Visit Site</a></span>
                    </div>
                </div>
                
                <div class="course-map" style="margin-top: 1.5rem;">
                    <iframe 
                        src="https://maps.google.com/maps?q=16941+Highway+70+N,+Monterey,+TN+38574&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                        width="100%" 
                        height="200" 
                        style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Cumberland Cove Golf Course Location">
                    </iframe>
                    <div style="margin-top: 0.5rem; text-align: center;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=16941+Highway+70+N,+Monterey,+TN+38574" 
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
            <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Cumberland Cove Golf Course</h3>
            <p>Cumberland Cove Golf Course, nestled in the scenic Tennessee mountains of Monterey, has been providing challenging and enjoyable golf since 1967. This 18-hole, par-72 championship course stretches 6,675 yards from the back tees and offers golfers a true test of skill with its course rating of 71.7 and slope rating of 126, indicating a course that rewards strategic play and precision.</p>
            
            <br>
            
            <p>The course has an interesting design history, with the original layout opening in 1967 and the back nine being redesigned by renowned golf course architect Gary Roger Baird, ASGCA, in 1992. This renovation brought modern course design principles to complement the natural mountain terrain, creating a layout that seamlessly blends challenging golf with the spectacular beauty of the Cumberland Plateau region.</p>
            
            <br>
            
            <p>Cumberland Cove features pristine bent grass greens that provide consistent putting surfaces year-round, complemented by fescue fairways that offer excellent playing conditions. The course incorporates natural water hazards and strategically placed sand bunkers (11-20 throughout the layout) that challenge golfers to think carefully about course management and shot selection. The mountain setting provides not only stunning views but also varying elevations that add complexity and visual appeal to each hole.</p>
            
            <br>
            
            <p>As a public course, Cumberland Cove welcomes golfers of all skill levels while maintaining the quality and challenge expected of a championship layout. The facility combines the accessibility of public golf with the high standards of course conditioning and customer service, making it a premier destination for both local players and visiting golf enthusiasts exploring Tennessee's mountain golf offerings.</p>
        </div>

        <!-- Course Features -->
        <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
            <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-chart-line"></i> Course Features & Specifications</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #2c5234;">
                    <h4 style="color: #2c5234; margin-bottom: 1rem;">Course Specifications</h4>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                        <div><strong>Total Yardage:</strong> 6,675</div>
                        <div><strong>Par:</strong> 72</div>
                        <div><strong>Rating:</strong> 71.7</div>
                        <div><strong>Slope:</strong> 126</div>
                        <div><strong>Holes:</strong> 18</div>
                        <div><strong>Opened:</strong> 1967</div>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #4a7c59;">
                    <h4 style="color: #2c5234; margin-bottom: 1rem;">Playing Surfaces</h4>
                    <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                        <div><strong>Greens:</strong> Bent Grass</div>
                        <div><strong>Fairways:</strong> Fescue</div>
                        <div><strong>Bunkers:</strong> 11-20 Sand Traps</div>
                        <div><strong>Water:</strong> Natural Hazards</div>
                        <div><strong>Setting:</strong> Mountain Terrain</div>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #ffc107;">
                    <h4 style="color: #2c5234; margin-bottom: 1rem;">Design History</h4>
                    <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                        <div><strong>Original:</strong> 1967</div>
                        <div><strong>Back Nine:</strong> 1992 Redesign</div>
                        <div><strong>Architect:</strong> Gary Roger Baird</div>
                        <div><strong>Certification:</strong> ASGCA Member</div>
                        <div><strong>Style:</strong> Mountain Course</div>
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
                    <span>Championship Course</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-store" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Pro Shop</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-bullseye" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Driving Range</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-dot-circle" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Putting Green</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-graduation-cap" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Teaching Professional</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-shopping-cart" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Cart Rentals</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-walking" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Walking Allowed</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Bar & Grill</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-credit-card" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Credit Cards Accepted</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-mountain" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Mountain Views</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-leaf" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Bent Grass Greens</span>
                </div>
                <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-water" style="color: #4a7c59; font-size: 1.2rem;"></i>
                    <span>Water Features</span>
                </div>
            </div>
        </div>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Cumberland Cove Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/cumberland-cove-golf-course/1.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/cumberland-cove-golf-course/2.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/cumberland-cove-golf-course/3.webp');"></div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>

    <!-- Share This Course Section -->
    <section class="share-course-section" style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="share-section" style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center;">
                <h3 class="share-title" style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Course</h3>
                <div class="share-buttons" style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/cumberland-cove-golf-course'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Cumberland Cove Golf Course'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/cumberland-cove-golf-course'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Cumberland Cove Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/cumberland-cove-golf-course'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
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
                                <textarea name="comment_text" placeholder="Share your thoughts about Cumberland Cove Golf Course..." required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit; resize: vertical; min-height: 100px;"></textarea>
                            </div>
                            <button type="submit" style="background: #2c5234; color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Post Review</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div style="background: #f8f9fa; padding: 2rem; border-radius: 15px; text-align: center; margin-bottom: 3rem;">
                        <p><a href="../login.php" style="color: #2c5234; font-weight: 600; text-decoration: none;">Log in</a> to share your review of Cumberland Cove Golf Course</p>
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
                        <p>Be the first to share your experience at Cumberland Cove Golf Course!</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Cumberland Cove Golf Course - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Footer -->
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
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/cumberland-cove-golf-course/${i}.webp')`;
                galleryItem.onclick = () => window.open(`../images/courses/cumberland-cove-golf-course/${i}.webp`, '_blank');
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
