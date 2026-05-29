<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'ross-creek-landing-golf-course';
$course_name = 'Ross Creek Landing Golf Course';

$course_data = [
    'name' => 'Ross Creek Landing Golf Course',
    'location' => 'Clifton, TN',
    'description' => 'Jack Nicklaus Signature Design from 2001 in Clifton, TN. Championship 18-hole course featuring 7,131-yard Par 72 layout with challenging slope ratings.',
    'image' => '/images/courses/ross-creek-landing-golf-course/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Jack Nicklaus',
    'year_built' => 2001,
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/ross-creek-landing-golf-course/1.webp');
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
            <h1>Ross Creek Landing Golf Course</h1>
            <p>Jack Nicklaus Signature Design • Clifton, Tennessee</p>
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
                            <span class="spec-value">7,131</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Jack Nicklaus</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">2001</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Public</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">Weekend & Holiday Rates</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">18 Holes:</span>
                                <span class="spec-value">$48</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9 Holes:</span>
                                <span class="spec-value">$30</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">Weekday Rates</h4>
                        <div class="course-specs">
                            <div class="spec-item">
                                <span class="spec-label">18 Holes:</span>
                                <span class="spec-value">$42</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">9 Holes:</span>
                                <span class="spec-value">$25</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                        <h4 style="color: #2c5234; margin-bottom: 0.5rem;">Senior Thursday (55+)</h4>
                        <div class="spec-item">
                            <span class="spec-label">18 Holes:</span>
                            <span class="spec-value">$28</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    110 Airport Rd<br>
                    Clifton, TN 38425</p>
                    
                    <p><strong>Phone:</strong><br>
                    (931) 676-3174</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://www.golfatrosscreek.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">golfatrosscreek.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=110+Airport+Rd,+Clifton,+TN+38425&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Ross Creek Landing Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=110+Airport+Rd,+Clifton,+TN+38425" 
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
                <h3><i class="fas fa-golf-ball"></i> About Ross Creek Landing Golf Course</h3>
                <p>Ross Creek Landing Golf Course stands as a premier Jack Nicklaus Signature Design, opened in 2001 in the scenic Tennessee Valley town of Clifton. This championship 18-hole course represents the Golden Bear's commitment to creating challenging yet playable golf experiences that showcase the natural beauty of their surroundings while providing strategic challenges for golfers of all skill levels.</p>
                
                <br>
                
                <p>The course stretches an impressive 7,131 yards from the championship Gold tees with a par of 72, featuring one of the more demanding layouts in Middle Tennessee with a slope rating of 136 and course rating of 74.8. Multiple tee options accommodate golfers of all abilities, with the Blue tees playing 6,720 yards and White tees at 6,311 yards, ensuring an enjoyable experience regardless of skill level.</p>
                
                <br>
                
                <p>Located at 110 Airport Road in Clifton, Ross Creek Landing takes full advantage of the rolling Tennessee terrain and natural water features that characterize Nicklaus designs. The course seamlessly integrates with the landscape, featuring strategically placed bunkers, undulating fairways, and elevated greens that reward precision while punishing wayward shots.</p>
                
                <br>
                
                <p>What sets Ross Creek Landing apart is its perfect balance of challenge and playability. The course demands strategic thinking off the tee and precise approach shots, while still remaining accessible to higher handicap golfers who choose appropriate tees. The Golden Bear's signature design philosophy is evident throughout the layout, with risk-reward opportunities on nearly every hole.</p>
                
                <br>
                
                <p>As a public facility, Ross Creek Landing offers exceptional value with reasonable green fees and special pricing for seniors on Thursdays. The course maintains championship conditioning year-round, making it a favorite destination for golfers throughout Middle Tennessee and beyond who seek a true Nicklaus golf experience without the premium pricing often associated with signature designs.</p>
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
                        <i class="fas fa-trophy"></i>
                        <span>Jack Nicklaus Design</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-car"></i>
                        <span>Cart Rentals</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Tournament Play</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-user-clock"></i>
                        <span>Senior Discounts</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-mountain"></i>
                        <span>Elevated Greens</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-water"></i>
                        <span>Water Features</span>
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
                <p>Experience the Jack Nicklaus Signature Design at Ross Creek Landing</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/ross-creek-landing-golf-course/2.webp" alt="Ross Creek Landing Golf Course Clifton TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/ross-creek-landing-golf-course/3.webp" alt="Ross Creek Landing Golf Course Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/ross-creek-landing-golf-course/4.webp" alt="Ross Creek Landing Golf Course Clifton TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/ross-creek-landing-golf-course'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Ross Creek Landing Golf Course in Clifton, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/ross-creek-landing-golf-course'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Ross Creek Landing Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/ross-creek-landing-golf-course'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Ross Creek Landing Golf Course Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid">
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/1.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/2.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/3.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/4.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/5.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/6.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/7.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/8.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/9.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/10.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/11.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/12.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/13.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/14.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/15.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/16.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/17.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/18.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/19.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/20.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/21.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/22.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/23.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/24.webp');"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/ross-creek-landing-golf-course/25.webp');"></div>
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