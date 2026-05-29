<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'timber-truss-golf-course';
$course_name = 'Timber Truss Golf Course';

$course_data = [
    'name' => 'Timber Truss Golf Course',
    'location' => 'Olive Branch, MS',
    'description' => 'Championship golf course formerly known as Plantation Golf Club. Experience exceptional golf on this 180-acre property with modern amenities and classic charm.',
    'image' => '/images/courses/timber-truss-golf-course/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'N/A',
    'year_built' => 'N/A',
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/timber-truss-golf-course/1.webp');
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

    <!-- Course Hero -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1><?php echo htmlspecialchars($course_name); ?></h1>
            <p>Championship Golf Course in Olive Branch, Mississippi</p>
            
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
                            <span class="spec-value">6,773</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">N/A</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Opened:</span>
                            <span class="spec-value">N/A</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Public</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Weekday:</span>
                            <span class="spec-value">$32-$42</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Friday:</span>
                            <span class="spec-value">$40-$50</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Weekend:</span>
                            <span class="spec-value">$60-$75</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Senior (Weekday):</span>
                            <span class="spec-value">$35</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Twilight:</span>
                            <span class="spec-value">$32</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">9 Holes:</span>
                            <span class="spec-value">$30-$45</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <p><strong>Address:</strong><br>
                    9425 Plantation Road<br>
                    Olive Branch, MS 38654</p>
                    
                    <p><strong>Phone:</strong><br>
                    (662) 895-3530</p>
                    
                    <p><strong>Website:</strong><br>
                    <a href="https://timbertrussgolf.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">timbertrussgolf.com</a></p>
                    
                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=9425+Plantation+Road,+Olive+Branch,+MS+38654&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Timber Truss Golf Course Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=9425+Plantation+Road,+Olive+Branch,+MS+38654" 
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
                <h3><i class="fas fa-golf-ball"></i> About Timber Truss Golf Course</h3>
                <p>Timber Truss Golf Course, formerly known as Plantation Golf Club, is a premier championship golf destination located on a beautifully maintained 180-acre property in Olive Branch, Mississippi. This exceptional course combines a rich golfing legacy with modern amenities to deliver an unforgettable golf experience for players of all skill levels.</p>
                
                <br>
                
                <p>The course features meticulously maintained fairways and greens that challenge golfers while providing fair playing conditions. With multiple tee boxes offering yardages from 5,055 to 6,773 yards, Timber Truss accommodates everyone from beginners to scratch golfers.</p>
                
                <br>
                
                <p>The layout showcases strategic bunkering, water features, and mature landscaping that create both beauty and challenge throughout the round.</p>
                
                <br>
                
                <p>Modern amenities include GPS-equipped golf carts, a fully stocked pro shop, TrackMan simulator for year-round practice and instruction, and a comfortable clubhouse with dining options.<br><br></p>
                
                <p>The course's motto "Once Found, Forever Golfing" reflects their commitment to creating memorable experiences that keep golfers returning season after season.<br><br></p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card">
                <h3><i class="fas fa-star"></i> Course Amenities</h3>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <i class="fas fa-golf-ball"></i>
                        <span>GPS Carts</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-desktop"></i>
                        <span>TrackMan Simulator</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Golf Lessons</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        <span>Snack Bar</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-car"></i>
                        <span>Cart Rentals</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-wifi"></i>
                        <span>Free WiFi</span>
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-trophy"></i>
                        <span>Tournament Hosting</span>
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
                <p>Experience the beauty of Timber Truss Golf Course</p>
            </div>
            <div class="gallery-grid">
                <img src="../images/courses/timber-truss-golf-course/2.webp" alt="Timber Truss Golf Course Olive Branch, MS - Championship 18-hole golf course fairway with manicured Bermuda grass, Tennessee public golf course" class="gallery-item">
                <img src="../images/courses/timber-truss-golf-course/3.webp" alt="Timber Truss Golf Course Olive Branch, MS - Pristine putting green with strategic bunkers and mature landscaping, Public 18-hole Tennessee golf course" class="gallery-item">
                <img src="../images/courses/timber-truss-golf-course/4.webp" alt="Timber Truss Golf Course Olive Branch, MS - Scenic golf course landscape with rolling hills and tree-lined fairways, Tennessee public golf course" class="gallery-item">
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View All Photos (25+)</button>
            </div>
        </div>    <!-- Share This Course Section -->
    <section class="share-course-section" style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="share-section" style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center;">
                <h3 class="share-title" style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Course</h3>
                <div class="share-buttons" style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/timber-truss-golf-course'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Timber Truss Golf Course in Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/timber-truss-golf-course'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Timber Truss Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/timber-truss-golf-course'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Timber Truss Golf Course - Olive Branch, MS</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid">
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/1.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/1.webp', '_blank')" title="Timber Truss Golf Course - Course Overview"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/2.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/2.webp', '_blank')" title="Timber Truss Golf Course - Fairway View"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/3.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/3.webp', '_blank')" title="Timber Truss Golf Course - Course Layout"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/4.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/4.webp', '_blank')" title="Timber Truss Golf Course - Hole 4"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/5.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/5.webp', '_blank')" title="Timber Truss Golf Course - Hole 5"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/6.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/6.webp', '_blank')" title="Timber Truss Golf Course - Hole 6"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/7.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/7.webp', '_blank')" title="Timber Truss Golf Course - Hole 7"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/8.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/8.webp', '_blank')" title="Timber Truss Golf Course - Hole 8"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/9.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/9.webp', '_blank')" title="Timber Truss Golf Course - Hole 9"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/10.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/10.webp', '_blank')" title="Timber Truss Golf Course - Hole 10"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/11.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/11.webp', '_blank')" title="Timber Truss Golf Course - Hole 11"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/12.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/12.webp', '_blank')" title="Timber Truss Golf Course - Hole 12"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/13.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/13.webp', '_blank')" title="Timber Truss Golf Course - Hole 13"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/14.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/14.webp', '_blank')" title="Timber Truss Golf Course - Hole 14"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/15.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/15.webp', '_blank')" title="Timber Truss Golf Course - Hole 15"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/16.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/16.webp', '_blank')" title="Timber Truss Golf Course - Hole 16"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/17.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/17.webp', '_blank')" title="Timber Truss Golf Course - Hole 17"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/18.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/18.webp', '_blank')" title="Timber Truss Golf Course - Hole 18"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/19.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/19.webp', '_blank')" title="Timber Truss Golf Course - Clubhouse"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/20.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/20.webp', '_blank')" title="Timber Truss Golf Course - Practice Facilities"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/21.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/21.webp', '_blank')" title="Timber Truss Golf Course - Pro Shop"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/22.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/22.webp', '_blank')" title="Timber Truss Golf Course - TrackMan Simulator"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/23.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/23.webp', '_blank')" title="Timber Truss Golf Course - Championship Golf"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/24.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/24.webp', '_blank')" title="Timber Truss Golf Course - Course Scenic View"></div>
                <div class="full-gallery-item" style="background-image: url('../images/courses/timber-truss-golf-course/25.webp')" onclick="window.open('../images/courses/timber-truss-golf-course/25.webp', '_blank')" title="Timber Truss Golf Course - Course Panoramic View"></div>
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