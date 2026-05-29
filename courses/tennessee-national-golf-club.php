<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'tennessee-national-golf-club';
$course_name = 'Tennessee National Golf Club';

$course_data = [
    'name' => 'Tennessee National Golf Club',
    'location' => 'Loudon, TN',
    'description' => 'Greg Norman and Tad Burnett designed championship course in Loudon, TN. Top 10 Tennessee course featuring 7,393-yard Par 72 layout with 19-acre practice facility.',
    'image' => '/images/courses/tennessee-national-golf-club/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Greg Norman, Tad Burnett',
    'year_built' => 2000,
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
    <?php
    $_canonical = 'https://tennesseegolfcourses.com' . strtok($_SERVER['REQUEST_URI'], '?');
    $_canonical = rtrim($_canonical, '/');
    ?>
    <link rel="canonical" href="<?php echo htmlspecialchars($_canonical); ?>">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/tennessee-national-golf-club/1.webp');
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
            <h1>Tennessee National Golf Club</h1>
            <p>Greg Norman Signature Design • Loudon, Tennessee</p>
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
                            <span class="spec-value">7,393</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Greg Norman & Tad Burnett</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">2007</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Semi-Private</span>
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
                        Tennessee National Golf Club operates as an exclusive private club serving East Tennessee families since 2007. 
                        Contact the club directly for membership information and guest policies.
                    </p>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    8301 Tennessee National Dr<br>
                    Loudon, TN 37774</p>
                    
                    <p><strong>Pro Shop:</strong><br>
                    (865) 657-2001 ext. 2</p>
                    
                    <p><strong>Director of Golf:</strong><br>
                    Cody Cable, PGA</p>
                    
                    <p><strong>Membership:</strong><br>
                    Kara Pratt - (865) 224-0838</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://www.tennesseenational.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">tennesseenational.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=8301+Tennessee+National+Dr,+Loudon,+TN+37774&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Tennessee National Golf Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=8301+Tennessee+National+Dr,+Loudon,+TN+37774" 
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
                <h3><i class="fas fa-golf-ball"></i> About Tennessee National Golf Club</h3>
                <p>Tennessee National Golf Club is a stunning Greg Norman Signature Golf Course co-designed with Tad Burnett, opened in 2007 in the scenic hills of Loudon, Tennessee. Ranked as one of the "Top 10 Courses in Tennessee," this championship layout offers a premier golf experience with breathtaking views of Watts Bar Lake and the surrounding East Tennessee landscape.</p>
                
                <br>
                
                <p>The course measures an impressive 7,393 yards from the championship tees with a challenging slope rating of 139, making it one of the more demanding tests of golf in the state. The Norman-Burnett design takes full advantage of the natural terrain, incorporating dramatic elevation changes, strategic bunkering, and water features that come into play on several holes.</p>
                
                <br>
                
                <p>One of the standout features of Tennessee National is its state-of-the-art practice facility spanning 19 acres. This comprehensive training ground includes an oversized grass teeing area, two practice chipping and putting greens, and a private tee with a dedicated short game area. The facility also features TopTracer technology, allowing golfers to track their shots and improve their game with real-time data.</p>
                
                <br>
                
                <p>The club recently unveiled a brand-new 25,000 square foot clubhouse that serves as the centerpiece of the golf experience. This impressive facility houses dining options, event spaces, and the pro shop, providing members and guests with first-class amenities. Adjacent to the golf facilities, the Sunset Saloon offers a casual atmosphere for post-round refreshments and camaraderie.<br><br></p>
                
                <p>Under the direction of PGA Class A Professional Cody Cable, Tennessee National maintains the highest standards of course conditioning and customer service. The semi-private nature of the club allows public play while maintaining an exclusive atmosphere, making it accessible to both members and daily fee players seeking a premium golf experience.<br><br></p>
                
                <p>Tennessee National Golf Club represents the perfect blend of championship golf, stunning natural beauty, and world-class amenities, making it a must-play destination for golfers visiting East Tennessee. The course's strategic design challenges players of all skill levels while providing multiple tee options to ensure an enjoyable round for everyone.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Course Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>19-Acre Practice Facility</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-bullseye"></i>
                        <span>TopTracer Range</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-building"></i>
                        <span>25,000 sq ft Clubhouse</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Full Restaurant</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-user-tie"></i>
                        <span>PGA Instruction</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-glass-cheers"></i>
                        <span>Sunset Saloon</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Event Hosting</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-bullseye"></i>
                        <span>Short Game Area</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-users"></i>
                        <span>Memberships Available</span>
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
                <p>Experience the beauty of Tennessee National Golf Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/tennessee-national-golf-club/2.webp" alt="Tennessee National Golf Club Loudon TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/tennessee-national-golf-club/3.webp" alt="Tennessee National Golf Club Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/tennessee-national-golf-club/4.webp" alt="Tennessee National Golf Club Loudon TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/tennessee-national-golf-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Tennessee National Golf Club in Loudon, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/tennessee-national-golf-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Tennessee National Golf Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/tennessee-national-golf-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Tennessee National Golf Club Gallery</h2>
                <span class="close" onclick="closeGallery()">&times;</span>
            </div>
            <div class="full-gallery-grid">
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/1.webp');" alt="Tennessee National Golf Club Loudon TN - Signature hole with scenic water features and championship golf course design"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/2.webp');" alt="Tennessee National Golf Club Loudon TN - Panoramic fairway view with strategic bunkers and mature trees"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/3.webp');" alt="Tennessee National Golf Club Loudon TN - Championship golf course layout showing natural terrain"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/4.webp');" alt="Tennessee National Golf Club Loudon TN - Golf course entrance with professional landscaping and signage"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/5.webp');" alt="Tennessee National Golf Club Loudon TN - Challenging par 3 hole with water hazard and elevated green"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/6.webp');" alt="Tennessee National Golf Club Loudon TN - Scenic tee box view overlooking rolling fairway and forest"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/7.webp');" alt="Tennessee National Golf Club Loudon TN - Well-maintained putting green with flag and surrounding bunkers"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/8.webp');" alt="Tennessee National Golf Club Loudon TN - Dramatic dogleg hole with creek running alongside fairway"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/9.webp');" alt="Tennessee National Golf Club Loudon TN - Elevated tee shot over valley to distant green"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/10.webp');" alt="Tennessee National Golf Club Loudon TN - Pristine fairway bunkers and sand traps with raked patterns"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/11.webp');" alt="Tennessee National Golf Club Loudon TN - Golf course clubhouse exterior with architectural details and landscaping"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/12.webp');" alt="Tennessee National Golf Club Loudon TN - Multiple tee boxes showing course setup and yardage options"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/13.webp');" alt="Tennessee National Golf Club Loudon TN - Water feature and fountain near clubhouse entrance"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/14.webp');" alt="Tennessee National Golf Club Loudon TN - Long par 4 hole with tree-lined fairway and strategic bunkering"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/15.webp');" alt="Tennessee National Golf Club Loudon TN - Golf cart path winding through natural Tennessee landscape"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/16.webp');" alt="Tennessee National Golf Club Loudon TN - Practice facility with driving range and target greens"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/17.webp');" alt="Tennessee National Golf Club Loudon TN - Scenic bridge crossing over water hazard on golf course"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/18.webp');" alt="Tennessee National Golf Club Loudon TN - Championship finishing hole with gallery and spectator areas"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/19.webp');" alt="Tennessee National Golf Club Loudon TN - Golf course maintenance equipment and perfectly manicured grounds"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/20.webp');" alt="Tennessee National Golf Club Loudon TN - Early morning mist over fairway creating dramatic golf course atmosphere"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/21.webp');" alt="Tennessee National Golf Club Loudon TN - Golf course pro shop interior with merchandise and equipment displays"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/22.webp');" alt="Tennessee National Golf Club Loudon TN - Tournament setup with grandstands and professional golf event atmosphere"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/23.webp');" alt="Tennessee National Golf Club Loudon TN - Sunset view over golf course with golden hour lighting on greens"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/24.webp');" alt="Tennessee National Golf Club Loudon TN - Wildlife and natural habitat preserved throughout the golf course grounds"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/tennessee-national-golf-club/25.webp');" alt="Tennessee National Golf Club Loudon TN - Award ceremony and trophy presentation at championship golf tournament"></div>
            </div>
        </div>
    </div>

    <script>
        // Gallery modal functionality
        function openGallery() {
            document.getElementById('galleryModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside of it
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

        // Star rating functionality for comment form
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating input[type="radio"]');
            const labels = document.querySelectorAll('.star-rating label');
            
            function updateStars(rating) {
                labels.forEach((label, index) => {
                    if (index < rating) {
                        label.classList.add('active');
                    } else {
                        label.classList.remove('active');
                    }
                });
            }
            
            labels.forEach((label, index) => {
                label.addEventListener('mouseover', () => updateStars(index + 1));
                label.addEventListener('click', () => {
                    stars[index].checked = true;
                    updateStars(index + 1);
                });
            });
            
            document.querySelector('.star-rating').addEventListener('mouseleave', () => {
                const checkedStar = document.querySelector('.star-rating input[type="radio"]:checked');
                if (checkedStar) {
                    const rating = Array.from(stars).indexOf(checkedStar) + 1;
                    updateStars(rating);
                } else {
                    updateStars(0);
                }
            });
        });
    </script>

    <?php include '../includes/footer.php'; ?>