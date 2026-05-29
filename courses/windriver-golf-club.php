<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'windriver-golf-club';
$course_name = 'WindRiver Golf Club';

$course_data = [
    'name' => 'WindRiver Golf Club',
    'location' => 'Lenoir City, TN',
    'description' => 'Bob Cupp designed championship course overlooking Tellico Lake in Lenoir City, TN. Tennessee\'s first Audubon certified golf course.',
    'image' => '/images/courses/windriver-golf-club/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Bob Cupp',
    'year_built' => 1991,
    'course_type' => 'Semi-Private'
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
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=6">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=6">
    
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/windriver-golf-club/1.webp');
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
            <h1>WindRiver Golf Club</h1>
            <p>Bob Cupp Design • Lenoir City, Tennessee</p>
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
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">7,453</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Bob Cupp</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">2007</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Private</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-users"></i> Membership</h3>
                    <div style="background: linear-gradient(135deg, #8B4513, #A0522D); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; margin: 1rem 0;">
                        <h4 style="margin-bottom: 0.5rem; font-size: 1.2rem;">Private Members Only</h4>
                        <p style="margin: 0; opacity: 0.9;">Exclusive club membership required</p>
                    </div>
                    <p style="text-align: center; color: #666; margin-top: 1rem;">
                        WindRiver Golf Club operates as an exclusive private club serving the Tellico Lake community. 
                        Contact the club directly for membership information and guest policies.
                    </p>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    1800 Oakum Court<br>
                    Lenoir City, TN 37772</p>
                    
                    <p><strong>Phone:</strong><br>
                    (865) 988-0370</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://clubwindriver.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">clubwindriver.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=1800+Oakum+Court,+Lenoir+City,+TN+37772&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="WindRiver Golf Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=1800+Oakum+Court,+Lenoir+City,+TN+37772" 
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
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About WindRiver Golf Club</h3>
                <p>WindRiver Golf Club stands as Tennessee's first Audubon-certified golf course, offering a pristine championship layout overlooking the spectacular Tellico Lake with the Great Smoky Mountains as its backdrop. Opened in 2007, this Bob Cupp-designed masterpiece showcases the natural beauty of East Tennessee while providing one of the region's most challenging and rewarding golf experiences.</p>
                
                <br>
                
                <p>Designed by Bob Cupp, a protégé of Jack Nicklaus, the course features rolling fairways and undulating greens that accent each hole, while sparkling water hazards and distinctive Billy Bunkers guard each green. The layout offers six sets of tees ranging from 4,146 to 7,453 yards, ensuring an appropriate challenge for golfers of all skill levels while maintaining the integrity of the championship design.</p>
                
                <br>
                
                <p>The course's championship credentials are evident in its impressive ratings, with a course rating of 76.2 and slope of 132 from the back tees. The meticulously maintained Prism Zoysia greens provide year-round exceptional putting surfaces, while the strategic bunkering and water features demand both precision and course management from every golfer.</p>
                
                <br>
                
                <p>As Tennessee's first Audubon-certified course, WindRiver demonstrates an unwavering commitment to environmental stewardship while providing members with exceptional golf amenities including an expansive practice facility, short game area, and plans for a new Clubhouse Village. The combination of championship golf, stunning natural beauty, and exclusive member privileges makes WindRiver one of Tennessee's premier private golf destinations.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Club Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Championship Golf</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-leaf"></i>
                        <span>Audubon Certified</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-bullseye"></i>
                        <span>Practice Facility</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Short Game Area</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-glass-cheers"></i>
                        <span>Beverage Cart</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-trophy"></i>
                        <span>League Play</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-users"></i>
                        <span>Private Memberships</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
                <h2 style="color: #2c5234; font-size: 2.5rem; margin-bottom: 1rem;">Course Gallery</h2>
                <p style="color: #666; font-size: 1.1rem;">Experience the scenic beauty of WindRiver Golf Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/windriver-golf-club/2.webp" alt="WindRiver Golf Club Lenoir City TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/windriver-golf-club/3.webp" alt="WindRiver Golf Club Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/windriver-golf-club/4.webp" alt="WindRiver Golf Club Lenoir City TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/windriver-golf-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Windriver Golf Club in Lenoir City, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/windriver-golf-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Windriver Golf Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/windriver-golf-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">WindRiver Golf Club Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid">
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/1.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/2.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/3.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/4.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/5.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/6.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/7.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/8.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/9.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/10.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/11.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/12.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/13.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/14.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/15.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/16.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/17.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/18.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/19.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/20.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/21.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/22.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/23.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/24.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/windriver-golf-club/25.webp');"></div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Gallery Modal Functions
        function openGallery() {
            document.getElementById('galleryModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
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

        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating label');
            const radioInputs = document.querySelectorAll('.star-rating input[type="radio"]');
            
            stars.forEach((star, index) => {
                star.addEventListener('mouseover', function() {
                    highlightStars(index + 1);
                });
                
                star.addEventListener('click', function() {
                    radioInputs[index].checked = true;
                    highlightStars(index + 1);
                });
            });
            
            function highlightStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            }
            
            // Reset stars on mouse leave
            const starContainer = document.querySelector('.star-rating');
            if (starContainer) {
                starContainer.addEventListener('mouseleave', function() {
                    const checkedInput = document.querySelector('.star-rating input[type="radio"]:checked');
                    if (checkedInput) {
                        const checkedIndex = Array.from(radioInputs).indexOf(checkedInput);
                        highlightStars(checkedIndex + 1);
                    } else {
                        highlightStars(0);
                    }
                });
            }
        });
    </script>
</body>
</html>