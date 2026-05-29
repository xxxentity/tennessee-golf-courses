<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'troubadour-golf-field-club';
$course_name = 'Troubadour Golf & Field Club';

$course_data = [
    'name' => 'Troubadour Golf & Field Club',
    'location' => 'College Grove, TN',
    'description' => 'Tom Fazio masterpiece in College Grove, TN. Private club with championship course design and luxury amenities.',
    'image' => '/images/courses/troubadour-golf-field-club/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Tom Fazio',
    'year_built' => 2007,
    'course_type' => 'Private'
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
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/troubadour-golf-field-club/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 20px;
        }
        
        .course-hero-content {
            max-width: 800px;
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
        
        .private-membership {
            background: linear-gradient(135deg, #8B4513, #A0522D);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
        }
        
        .private-membership h4 {
            color: white;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .private-membership p {
            opacity: 0.9;
            line-height: 1.6;
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
            padding: 0;
            line-height: 1;
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
        
        
        
        .comment-form-container h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
        }
        
        .comment-form .form-group {
            margin-bottom: 1.5rem;
        }
        
        .comment-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c5234;
        }
        
        .star-rating {
            display: flex;
            justify-content: flex-start;
            gap: 5px;
        }
        
        .star-rating input[type="radio"] {
            display: none;
        }
        
        .star-rating label {
            color: #999;
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .star-rating label:hover {
            color: #ffd700;
        }
        
        .star-rating label.active {
            color: #ffd700;
        }
        
        .comment-form textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
        }
        
        .comment-form textarea:focus {
            outline: none;
            border-color: #2c5234;
        }
        
        .btn-submit {
            background: #2c5234;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            background: #1e3f26;
            transform: translateY(-1px);
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
            <h1>Troubadour Golf & Field Club</h1>
            <p>Tom Fazio Masterpiece • College Grove, Tennessee</p>
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
                            <span class="spec-value">71</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">7,157</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Tom Fazio</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">2016</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Private</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-users"></i> Membership</h3>
                    <div class="private-membership">
                        <h4>Private Members Only</h4>
                        <p>Troubadour Golf & Field Club is an exclusive private residential community course designed by Tom Fazio. Membership is required to play this championship golf course, which offers a relaxed atmosphere and exceptional golf experience for members and their guests.</p>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    7230 Harlow Dr<br>
                    College Grove, TN 37046</p>
                    
                    <p><strong>Phone:</strong><br>
                    (615) 436-6850</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://thetroubadourclub.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">thetroubadourclub.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=7230+Harlow+Dr,+College+Grove,+TN+37046&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Troubadour Golf & Field Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=7230+Harlow+Dr,+College+Grove,+TN+37046" 
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
                <h3><i class="fas fa-golf-ball"></i> About Troubadour Golf & Field Club</h3>
                <p>Troubadour Golf & Field Club represents a new era of golf course design, where Tom Fazio's legendary craftsmanship meets a relaxed, member-focused philosophy. This championship 18-hole course is thoughtfully crafted for both leisure and challenge, seamlessly blending into the natural landscape of College Grove.</p>
                
                <br>
                
                <p>The course features lush, meticulously maintained greens and offers long-range views that showcase the beauty of Tennessee's rolling countryside. Fazio's design philosophy shines through in every hole, creating strategic options for players of all skill levels while maintaining the integrity of championship golf.</p>
                
                <br>
                
                <p>As part of an exclusive residential community, Troubadour redefines traditional golf club culture with a laid-back environment that encourages friendly competition over strict protocols. The club welcomes players of all abilities and fosters a social atmosphere where making new friends is as important as lowering your handicap.</p>
                
                <br>
                
                <p>Since opening in 2016, this Tom Fazio masterpiece has established itself as one of Tennessee's premier private golf experiences, offering members an unparalleled combination of championship golf, stunning natural beauty, and a welcoming community atmosphere that sets it apart from traditional country clubs.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Club Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Championship Golf</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Fine Dining</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>Practice Facilities</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Event Hosting</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-users"></i>
                        <span>Member Events</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-car"></i>
                        <span>Valet Service</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-home"></i>
                        <span>Residential Community</span>
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
                <p>Experience the beauty of Troubadour Golf & Field Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/troubadour-golf-field-club/2.jpeg" alt="Troubadour Golf & Field Club College Grove TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/troubadour-golf-field-club/3.jpeg" alt="Troubadour Golf & Field Club Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/troubadour-golf-field-club/4.jpeg" alt="Troubadour Golf & Field Club College Grove TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/troubadour-golf-field-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Troubadour Golf Field Club in College Grove, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/troubadour-golf-field-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Troubadour Golf Field Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/troubadour-golf-field-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Troubadour Golf & Field Club - College Grove, TN</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid">
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/1.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/1.jpeg', '_blank')" title="Troubadour Golf & Field Club - Course Overview"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/2.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/2.jpeg', '_blank')" title="Troubadour Golf & Field Club - Fairway View"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/3.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/3.jpeg', '_blank')" title="Troubadour Golf & Field Club - Course Layout"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/4.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/4.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 4"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/5.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/5.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 5"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/6.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/6.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 6"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/7.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/7.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 7"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/8.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/8.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 8"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/9.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/9.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 9"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/10.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/10.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 10"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/11.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/11.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 11"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/12.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/12.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 12"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/13.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/13.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 13"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/14.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/14.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 14"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/15.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/15.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 15"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/16.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/16.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 16"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/17.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/17.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 17"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/18.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/18.jpeg', '_blank')" title="Troubadour Golf & Field Club - Hole 18"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/19.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/19.jpeg', '_blank')" title="Troubadour Golf & Field Club - Clubhouse"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/20.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/20.jpeg', '_blank')" title="Troubadour Golf & Field Club - Practice Facilities"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/21.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/21.jpeg', '_blank')" title="Troubadour Golf & Field Club - Pro Shop"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/22.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/22.jpeg', '_blank')" title="Troubadour Golf & Field Club - Tom Fazio Design"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/23.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/23.jpeg', '_blank')" title="Troubadour Golf & Field Club - Championship Course"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/24.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/24.jpeg', '_blank')" title="Troubadour Golf & Field Club - Course Scenic View"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/troubadour-golf-field-club/25.jpeg')" onclick="window.open('../images/courses/troubadour-golf-field-club/25.jpeg', '_blank')" title="Troubadour Golf & Field Club - Course Panoramic View"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script>
        function openGallery() {
            document.getElementById('galleryModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the content
        window.onclick = function(event) {
            var modal = document.getElementById('galleryModal');
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

        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating label');
            const inputs = document.querySelectorAll('.star-rating input');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = inputs.length - index;
                    inputs[inputs.length - 1 - index].checked = true;
                    
                    // Update visual state
                    stars.forEach((s, i) => {
                        if (i >= inputs.length - rating) {
                            s.style.color = '#ffd700';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
                
                star.addEventListener('mouseenter', function() {
                    const rating = inputs.length - index;
                    stars.forEach((s, i) => {
                        if (i >= inputs.length - rating) {
                            s.style.color = '#ffd700';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
            });
            
            document.querySelector('.star-rating').addEventListener('mouseleave', function() {
                const checkedInput = document.querySelector('.star-rating input:checked');
                if (checkedInput) {
                    const rating = parseInt(checkedInput.value);
                    stars.forEach((s, i) => {
                        if (i >= inputs.length - rating) {
                            s.style.color = '#ffd700';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                } else {
                    stars.forEach(s => s.style.color = '#ddd');
                }
            });
        });
    </script>
</body>
</html>