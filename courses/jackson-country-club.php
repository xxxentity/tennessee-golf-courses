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
    'name' => 'Jackson Country Club',
    'location' => 'Jackson, TN',
    'description' => 'Historic private club in Jackson, TN since 1914. Championship golf with 6,849 yards, host to TGA championships and USGA qualifiers.',
    'image' => '/images/courses/jackson-country-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'N/A',
    'year_built' => 1914,
    'course_type' => 'Private'
];

SEO::setupCoursePage($course_data);

$course_slug = 'jackson-country-club';
$course_name = 'Jackson Country Club';

// Check if user is logged in
$is_logged_in = SecureSession::isLoggedIn();

// Handle comment submission with CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && SecureSession::isLoggedIn()) {
    if (isset($_POST['csrf_token']) && CSRFProtection::validateToken($_POST['csrf_token'])) {
        $rating = (int)$_POST['rating'];
        $comment_text = trim($_POST['comment_text']);
        $user_id = SecureSession::get('user_id');

        if ($rating >= 1 && $rating <= 5 && !empty($comment_text)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO course_comments (user_id, course_slug, course_name, rating, comment_text) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $course_slug, $course_name, $rating, $comment_text]);

                // PRG Pattern - Redirect after successful post
                header('Location: ' . $_SERVER['REQUEST_URI'] . '?success=1');
                exit;
            } catch (PDOException $e) {
                $error_message = "Error posting review. Please try again.";
            }
        } else {
            $error_message = "Please provide a valid rating and comment.";
        }
    } else {
        $error_message = "Invalid security token. Please try again.";
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
    
    // Calculate average rating - excluding replies
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM course_comments WHERE course_slug = ? AND parent_comment_id IS NULL AND rating IS NOT NULL");
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

    <script>
        // Gallery Modal Functions (removed duplicate below)
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Generate all 25 images
            
            // Alt text patterns for different image types
            function getAltText(imageIndex) {
                const courseName = 'Jackson Country Club';
                const location = 'Jackson, TN';
                const locationShort = 'Jackson TN';
                
                if (imageIndex <= 5) {
                    // Course overview shots
                    const overviewTexts = [
                        `${courseName} ${location} - Aerial view of championship 18-hole golf course showing signature holes and clubhouse facilities`,
                        `${courseName} ${locationShort} - Panoramic fairway view hole 7 with strategic bunkers and mature trees`,
                        `${courseName} Tennessee - Championship golf course layout showing undulating fairways and natural terrain`,
                        `${courseName} ${locationShort} - Championship golf course entrance with professional landscaping and signage`,
                        `${courseName} ${location} - Golf course overview showing scenic terrain and championship facilities`
                    ];
                    return overviewTexts[imageIndex - 1];
                } else if (imageIndex <= 10) {
                    // Signature holes
                    const holes = [6, 8, 12, 15, 18];
                    const holeIndex = imageIndex - 6;
                    const holeNum = holes[holeIndex];
                    const signatures = [
                        `${courseName} Tennessee golf course - Signature par 3 hole ${holeNum} with water hazard and bentgrass green`,
                        `${courseName} ${locationShort} - Challenging par 4 hole ${holeNum} with scenic views and strategic bunkering`,
                        `${courseName} Tennessee - Par 5 hole ${holeNum} with risk-reward layout and elevated green complex`,
                        `${courseName} ${location} - Signature hole ${holeNum} featuring championship design and natural beauty`,
                        `${courseName} Tennessee - Finishing hole ${holeNum} with dramatic approach shot and clubhouse backdrop`
                    ];
                    return signatures[holeIndex];
                } else if (imageIndex <= 15) {
                    // Greens and approaches
                    return `${courseName} ${locationShort} - Undulating putting green with championship pin positions and bentgrass surface - Image ${imageIndex}`;
                } else if (imageIndex <= 20) {
                    // Course features
                    const features = [
                        'Practice facility driving range and putting green area',
                        'Golf cart fleet and maintenance facilities',
                        'Professional golf instruction area and practice tees',
                        'Course landscaping with native Tennessee flora and water features',
                        'Golf course pro shop and equipment rental facilities'
                    ];
                    return `${courseName} Tennessee - ${features[(imageIndex - 16) % features.length]}`;
                } else {
                    // Clubhouse and amenities
                    const amenities = [
                        'Golf course clubhouse pro shop and restaurant facilities',
                        'Clubhouse dining room with scenic Tennessee views',
                        'Golf course event space and meeting facilities',
                        'Professional locker room and amenities',
                        'Golf course entrance and parking facilities'
                    ];
                    return `${courseName} ${location} - ${amenities[(imageIndex - 21) % amenities.length]}`;
                }
            }
            
            // Generate all 25 images
            for (let i = 1; i <= 25; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.innerHTML = `<img src="../images/courses/jackson-country-club/${i}.webp" alt="${getAltText(i)}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">`;
                galleryItem.onclick = () => window.open(`../images/courses/jackson-country-club/${i}.webp`, '_blank');
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
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/jackson-country-club/1.webp'); 
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Jackson Country Club</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">The Hub of Hub City • Jackson, Tennessee</p>
            <div class="course-rating" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
                <?php if ($avg_rating): ?>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="color: #ffd700; font-size: 1.2rem;">★</span>
                        <span style="font-weight: 600;"><?php echo $avg_rating; ?></span>
                        <span>(<?php echo $total_reviews; ?> reviews)</span>
                    </div>
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
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">6,849</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Designer:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Hugh H. Miller</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Established:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">1914</span>
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
                        Jackson Country Club has been fostering social pleasure and cultivating the enjoyment of life since 1914. 
                        Contact the club directly for membership information and guest policies.
                    </p>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p style="margin-bottom: 1rem;"><strong>Address:</strong><br>
                    31 Jackson Country Club Ln<br>
                    Jackson, TN 38305</p>
                    
                    <p style="margin-bottom: 1rem;"><strong>Phone:</strong><br>
                    (731) 668-0980</p>
                    
                    <p style="margin-bottom: 1rem;"><strong>Golf Shop:</strong><br>
                    (731) 300-7916</p>
                    
                    <p style="margin-bottom: 1.5rem;"><strong>Website:</strong><br>
                    <a href="https://jacksoncclub.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">jacksoncclub.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=31+Jackson+Country+Club+Ln,+Jackson,+TN+38305&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Jackson Country Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=31+Jackson+Country+Club+Ln,+Jackson,+TN+38305" 
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
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Jackson Country Club</h3>
                <p>Jackson Country Club stands as "The Hub" of Hub City, a distinguished private club that has been the cornerstone of Jackson, Tennessee's social and sporting landscape since its founding on February 7, 1914. Established by five visionary Jackson businessmen with the mission "to foster social pleasure, provide entertainment, and cultivate the enjoyment of life," this historic club has maintained its commitment to excellence for over a century, creating a legacy that continues to honor tradition while embracing progress.</p>
                
                <br>
                
                <p>The championship golf course, moved to its current location in early 1929, stretches an impressive 6,849 yards from the championship gold tees with a challenging par 72 design that features a demanding course rating of 73.4 and slope rating of 134. With five distinct tee sets ranging from 4,597 to 6,849 yards, the course accommodates golfers of all skill levels while maintaining the championship caliber that has made it a premier tournament venue, annually hosting the US Open Local Qualifier and Tennessee State Amateur Championship.</p>
                
                <br>
                
                <p>The course's Champion Bermuda greens and comprehensive practice facilities provide an exceptional playing surface and preparation opportunities that have attracted golf legends throughout its history. Walter Hagen, Ben Hogan, Byron Nelson, Patty Berg, and Mickey Wright have all graced these fairways, testament to the course's reputation for providing both challenge and hospitality. With 45 Tennessee Golf Association championships and numerous USGA qualifiers held here, Jackson Country Club has cemented its place among Tennessee's most respected golf destinations.</p>
                
                <br>
                
                <p>Beyond its championship golf offering, Jackson Country Club provides a comprehensive private club experience emphasizing camaraderie, sportsmanship, and community engagement. The club features world-class tennis facilities, swimming pool, fitness amenities, and exceptional dining services, all designed to create an environment where families can build genuine connections and create cherished memories. With a recently approved Master Plan by Golf Course Architect Kris Spence for a major 2025 renovation, Jackson Country Club continues to look toward the future while honoring its storied past as a central gathering place for the Jackson community.</p>
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
                        <i class="fas fa-tennis-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Tennis Facilities</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-swimmer" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Swimming Pool</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-dumbbell" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Health & Fitness</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Dining Services</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-glass-cheers" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Banquet Hall</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-trophy" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Tournament Host</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-heart" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Family Activities</span>
                    </div>
                </div>
            </div>

            
    </section>
    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Jackson Country Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/jackson-country-club/1.webp" alt="Jackson Country Club Jackson, TN - Aerial view of championship 18-hole golf course showing signature holes and clubhouse facilities" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/jackson-country-club/2.webp" alt="Jackson Country Club Jackson TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/jackson-country-club/3.webp" alt="Jackson Country Club Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/jackson-country-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Jackson Country Club in Jackson, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/jackson-country-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Jackson Country Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/jackson-country-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Jackson Country Club - Complete Photo Gallery</h2>
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

        // Gallery Modal Functions - removed duplicate (defined above)
// Duplicate removed -         /*function openGallery() {
// Duplicate removed -             const modal = document.getElementById('galleryModal');
// Duplicate removed -             const galleryGrid = document.getElementById('fullGalleryGrid');
// Duplicate removed -             
// Duplicate removed -             // Clear existing content
// Duplicate removed -             galleryGrid.innerHTML = '';
// Duplicate removed -             
// Duplicate removed -             // Generate all 25 images
// Duplicate removed -             
// Duplicate removed -             // Alt text patterns for different image types
// Duplicate removed -             function getAltText(imageIndex) {
// Duplicate removed -                 const courseName = 'Jackson Country Club';
// Duplicate removed -                 const location = 'Jackson, TN';
// Duplicate removed -                 const locationShort = 'Jackson TN';
// Duplicate removed -                 
// Duplicate removed -                 if (imageIndex <= 5) {
// Duplicate removed -                     // Course overview shots
// Duplicate removed -                     const overviewTexts = [
// Duplicate removed -                         `${courseName} ${location} - Aerial view of championship 18-hole golf course showing signature holes and clubhouse facilities`,
// Duplicate removed -                         `${courseName} ${locationShort} - Panoramic fairway view hole 7 with strategic bunkers and mature trees`,
// Duplicate removed -                         `${courseName} Tennessee - Championship golf course layout showing undulating fairways and natural terrain`,
// Duplicate removed -                         `${courseName} ${locationShort} - Championship golf course entrance with professional landscaping and signage`,
// Duplicate removed -                         `${courseName} ${location} - Golf course overview showing scenic terrain and championship facilities`
// Duplicate removed -                     ];
// Duplicate removed -                     return overviewTexts[imageIndex - 1];
// Duplicate removed -                 } else if (imageIndex <= 10) {
// Duplicate removed -                     // Signature holes
// Duplicate removed -                     const holes = [6, 8, 12, 15, 18];
// Duplicate removed -                     const holeIndex = imageIndex - 6;
// Duplicate removed -                     const holeNum = holes[holeIndex];
// Duplicate removed -                     const signatures = [
// Duplicate removed -                         `${courseName} Tennessee golf course - Signature par 3 hole ${holeNum} with water hazard and bentgrass green`,
// Duplicate removed -                         `${courseName} ${locationShort} - Challenging par 4 hole ${holeNum} with scenic views and strategic bunkering`,
// Duplicate removed -                         `${courseName} Tennessee - Par 5 hole ${holeNum} with risk-reward layout and elevated green complex`,
// Duplicate removed -                         `${courseName} ${location} - Signature hole ${holeNum} featuring championship design and natural beauty`,
// Duplicate removed -                         `${courseName} Tennessee - Finishing hole ${holeNum} with dramatic approach shot and clubhouse backdrop`
// Duplicate removed -                     ];
// Duplicate removed -                     return signatures[holeIndex];
// Duplicate removed -                 } else if (imageIndex <= 15) {
// Duplicate removed -                     // Greens and approaches
// Duplicate removed -                     return `${courseName} ${locationShort} - Undulating putting green with championship pin positions and bentgrass surface - Image ${imageIndex}`;
// Duplicate removed -                 } else if (imageIndex <= 20) {
// Duplicate removed -                     // Course features
// Duplicate removed -                     const features = [
// Duplicate removed -                         'Practice facility driving range and putting green area',
// Duplicate removed -                         'Golf cart fleet and maintenance facilities',
// Duplicate removed -                         'Professional golf instruction area and practice tees',
// Duplicate removed -                         'Course landscaping with native Tennessee flora and water features',
// Duplicate removed -                         'Golf course pro shop and equipment rental facilities'
// Duplicate removed -                     ];
// Duplicate removed -                     return `${courseName} Tennessee - ${features[(imageIndex - 16) % features.length]}`;
// Duplicate removed -                 } else {
// Duplicate removed -                     // Clubhouse and amenities
// Duplicate removed -                     const amenities = [
// Duplicate removed -                         'Golf course clubhouse pro shop and restaurant facilities',
// Duplicate removed -                         'Clubhouse dining room with scenic Tennessee views',
// Duplicate removed -                         'Golf course event space and meeting facilities',
// Duplicate removed -                         'Professional locker room and amenities',
// Duplicate removed -                         'Golf course entrance and parking facilities'
// Duplicate removed -                     ];
// Duplicate removed -                     return `${courseName} ${location} - ${amenities[(imageIndex - 21) % amenities.length]}`;
// Duplicate removed -                 }
// Duplicate removed -             }
// Duplicate removed -             
// Duplicate removed -             // Generate all 25 images
// Duplicate removed -             for (let i = 1; i <= 25; i++) {
// Duplicate removed -                 const galleryItem = document.createElement('div');
// Duplicate removed -                 galleryItem.className = 'full-gallery-item';
// Duplicate removed -                 galleryItem.innerHTML = `<img src="../images/courses/jackson-country-club/${i}.webp" alt="${getAltText(i)}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">`;
// Duplicate removed -                 galleryItem.onclick = () => window.open(`../images/courses/jackson-country-club/${i}.webp`, '_blank');
// Duplicate removed -                 galleryGrid.appendChild(galleryItem);
// Duplicate removed -             }
// Duplicate removed -             
// Duplicate removed -             modal.style.display = 'block';
// Duplicate removed -             document.body.style.overflow = 'hidden'; // Prevent background scrolling
// Duplicate removed -         }
// Duplicate removed -         
// Duplicate removed -         function closeGallery() {
// Duplicate removed -             const modal = document.getElementById('galleryModal');
// Duplicate removed -             modal.style.display = 'none';
// Duplicate removed -             document.body.style.overflow = 'auto'; // Restore scrolling
// Duplicate removed -         }
        
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
