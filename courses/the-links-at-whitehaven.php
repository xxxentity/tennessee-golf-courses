<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'the-links-at-whitehaven';
$course_name = 'The Links at Whitehaven';

$course_data = [
    'name' => 'The Links at Whitehaven',
    'location' => 'Memphis, TN',
    'description' => 'Kevin Tucker designed 9-hole municipal course in Memphis, TN. Historic 1973 course featuring 2,460-yard Par 36 layout on 100 acres.',
    'image' => '/images/courses/the-links-at-whitehaven/1.jpeg',
    'holes' => 9,
    'par' => 36,
    'designer' => 'Kevin Tucker',
    'year_built' => 1973,
    'course_type' => 'Public'
];

SEO::setupCoursePage($course_data);
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
    
    <style>
        .course-hero {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/the-links-at-whitehaven/1.webp');
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
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .spec-label {
            font-weight: 600;
            color: #666;
        }
        
        .spec-value {
            font-weight: 700;
            color: #2c5234;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
            justify-items: center;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .amenity-item i {
            color: #4a7c59;
            font-size: 1.2rem;
        }
        
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
        
        .reviews-section {
            background: #f8f9fa;
            padding: 4rem 0;
        }
        
        .review-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .reviewer-name {
            font-weight: 600;
            color: #2c5234;
        }
        
        .review-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .booking-section {
            background: linear-gradient(135deg, #2c5234, #4a7c59);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        
        .booking-content h2 {
            margin-bottom: 1rem;
        }
        
        .booking-content p {
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .booking-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-book {
            background: #ffd700;
            color: #2c5234;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-book:hover {
            background: #ffed4e;
            transform: translateY(-2px);
        }
        
        .btn-contact {
            background: transparent;
            color: white;
            padding: 1rem 2rem;
            border: 2px solid white;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-contact:hover {
            background: white;
            color: #2c5234;
        }
        
        
        
        /* Responsive Design for Course Info Grid */
        @media (max-width: 1024px) {
            .course-info-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .course-info-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .course-details {
                padding: 2rem 0;
            }
            
            .course-info-card {
                padding: 1.5rem;
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
            <h1>The Links at Whitehaven</h1>
            <p>Kevin Tucker Design • Memphis, Tennessee</p>
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
                            <span class="spec-value">9</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">36</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">2,460</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Kevin Tucker</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">1973</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Municipal</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">9 Holes (Closed Mondays)</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">Tues-Fri:</span>
                                <span class="spec-value">$10</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Weekend/Holidays:</span>
                                <span class="spec-value">$10</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Juniors (17 & under):</span>
                                <span class="spec-value">FREE</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Seniors (62 & older):</span>
                                <span class="spec-value">$7-10</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">Additional Fees</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">Cart (9 holes):</span>
                                <span class="spec-value">$5 per player</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Range Bucket:</span>
                                <span class="spec-value">$5</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    750 E Holmes Road<br>
                    Memphis, TN 38109</p>
                    
                    <p><strong>Phone:</strong><br>
                    (901) 396-1608</p>
                    
                    <p><strong>Facility Manager:</strong><br>
                    Dustin Sykes</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://memphispubliclinks.com/whitehaven/" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">memphispubliclinks.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=750+E+Holmes+Road,+Memphis,+TN+38109&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="The Links at Whitehaven Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=750+E+Holmes+Road,+Memphis,+TN+38109" 
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
                <h3><i class="fas fa-golf-ball"></i> About The Links at Whitehaven</h3>
                <p>The Links at Whitehaven is a 9-hole municipal golf course located in the Whitehaven neighborhood of Memphis, Tennessee. Designed by Kevin Tucker and established in 1973, this course has served the Memphis community for over 50 years as an affordable and accessible golfing destination.</p>
                
                <br>
                
                <p>The course spans 100 acres and features a Par 36 layout measuring 2,460 yards. Despite its compact size, The Links at Whitehaven offers an enjoyable golf experience suitable for players of all skill levels, from beginners looking to learn the game to experienced golfers seeking a quick round.</p>
                
                <br>
                
                <p>As part of the Memphis Public Links system, the course offers some of the most affordable golf in the region, with special rates for seniors and free golf for juniors 17 and under, demonstrating the city's commitment to making golf accessible to everyone in the community.</p>
                
                <br>
                
                <p>The facility features a well-equipped clubhouse with restrooms, concessions, and banquet facilities available for rent. The course also offers golf cart rentals, a driving range, and a short-game practice area for those looking to improve their skills.<br><br></p>
                
                <p>The Links at Whitehaven operates year-round with seasonal hours and is closed on Mondays for maintenance. During the summer months, the course hosts Nike Junior Golf Summer Camp for ages 7-17, helping to introduce the next generation to the game of golf.<br><br></p>
                
                <p>Under the management of Facility Manager Dustin Sykes, The Links at Whitehaven continues to provide quality municipal golf to the Memphis community, maintaining its role as an important recreational resource for the Whitehaven neighborhood and surrounding areas.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Course Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Driving Range</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Snack Bar</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Golf Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Banquet Facilities</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-car"></i>
                        <span>Cart Rentals</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-bullseye"></i>
                        <span>Practice Area</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-users"></i>
                        <span>Meeting Rooms</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-child"></i>
                        <span>Junior Programs</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-heart"></i>
                        <span>Outdoor Weddings</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Golf Clinics</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of The Links at Whitehaven</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/the-links-at-whitehaven/2.webp" alt="The Links at Whitehaven Memphis TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-links-at-whitehaven/3.webp" alt="The Links at Whitehaven Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/the-links-at-whitehaven/4.webp" alt="The Links at Whitehaven Memphis TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>    <!-- Share This Course Section -->
    <section class="share-course-section" style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="share-section" style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center;">
                <h3 class="share-title" style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Course</h3>
                <div class="share-buttons" style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/the-links-at-whitehaven'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out The Links At Whitehaven in the Whitehaven neighborhood of Memphis, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/the-links-at-whitehaven'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out The Links At Whitehaven'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/the-links-at-whitehaven'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>


    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">The Links at Whitehaven - Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div id="fullGalleryGrid" class="full-gallery-grid">
                <!-- Gallery items will be dynamically generated -->
            </div>
        </div>
    </div>

    <script>
        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Alt text patterns for different image types
            function getAltText(imageIndex) {
                const courseName = 'The Links at Whitehaven';
                const location = 'Memphis, TN';
                const locationShort = 'Memphis TN';
                
                if (imageIndex <= 5) {
                    // Course overview shots
                    const overviewTexts = [
                        `${courseName} ${location} - Aerial view of 9-hole golf course showing signature holes and clubhouse facilities`,
                        `${courseName} ${locationShort} - Panoramic fairway view hole 7 with strategic bunkers and mature trees`,
                        `${courseName} Tennessee - Championship golf course layout showing championship layout and natural terrain`,
                        `${courseName} ${locationShort} - Championship golf course entrance with professional landscaping and signage`,
                        `${courseName} ${location} - Golf course overview showing scenic terrain and championship facilities`
                    ];
                    return overviewTexts[imageIndex - 1];
                } else if (imageIndex <= 10) {
                    // Signature holes
                    const holes = [3, 5, 6, 8, 9];
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
                galleryItem.innerHTML = `<img src="../images/courses/the-links-at-whitehaven/${i}.webp" alt="${getAltText(i)}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">`;
                galleryItem.onclick = () => window.open(`../images/courses/the-links-at-whitehaven/${i}.webp`, '_blank');
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
        window.onclick = function(event) {
            const modal = document.getElementById('galleryModal');
            if (event.target === modal) {
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