<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'cedar-crest-golf-club';
$course_name = 'Cedar Crest Golf Club';

$course_data = [
    'name' => 'Cedar Crest Golf Club',
    'location' => 'Murfreesboro, TN',
    'description' => 'John Floyd designed public golf course in Murfreesboro, TN. 6,828 yards on rolling farmland with bentgrass greens since 1999.',
    'image' => '/images/courses/cedar-crest-golf-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'John Floyd',
    'year_built' => 1999,
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
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/cedar-crest-golf-club/1.jpeg');
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
            border-bottom: 1px solid #eee;
        }
        
        .spec-label {
            font-weight: 600;
            color: #333;
        }
        
        .spec-value {
            color: #666;
            font-weight: 500;
        }
        
        .amenities-list {
            list-style: none;
            padding: 0;
        }
        
        .amenities-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .amenities-list i {
            color: #2c5234;
            width: 20px;
        }
        
        .location-info {
            margin-bottom: 2rem;
        }
        
        .location-info iframe {
            width: 100%;
            height: 200px;
            border: 0;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .location-details p {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .location-details i {
            color: #2c5234;
            width: 20px;
        }
        
        .course-description {
            background: #f8f9fa;
            padding: 3rem 0;
        }
        
        .description-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .description-content h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        
        .description-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 1.5rem;
        }
        
        .signature-holes {
            padding: 4rem 0;
        }
        
        .holes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .hole-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .hole-number {
            background: #2c5234;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }
        
        .hole-details h4 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .hole-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
        }
        
        .hole-stat {
            text-align: center;
        }
        
        .hole-stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .hole-stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c5234;
        }
        
        .reviews-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .reviews-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .reviews-header h2 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        
        .review-form {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .review-form h3 {
            color: #2c5234;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .rating-input {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .rating-input input[type="radio"] {
            display: none;
        }
        
        .rating-input label {
            font-size: 1.5rem;
            color: #999;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .rating-input label:hover {
            color: #ffd700;
        }
        
        .rating-input label.active {
            color: #ffd700;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }
        
        .form-group textarea:focus {
            border-color: #2c5234;
            outline: none;
        }
        
        .submit-btn {
            background: #2c5234;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .submit-btn:hover {
            background: #1a3020;
        }
        
        
        
        
        
        .comments-list {
            space-y: 2rem;
        }
        
        .comment-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: #2c5234;
        }
        
        .comment-rating {
            color: #ffd700;
        }
        
        .comment-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .comment-text {
            color: #555;
            line-height: 1.6;
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
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-info-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .course-specs {
                grid-template-columns: 1fr;
            }
            
            .holes-grid {
                grid-template-columns: 1fr;
            }
            
            .hole-stats {
                gap: 1rem;
            }
        }
    </style>
    <script type="application/ld+json">
    <?php echo json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'GolfCourse',
        'name' => $course_data['name'] ?? '',
        'url' => 'https://tennesseegolfcourses.com/courses/' . ($course_slug ?? ''),
        'description' => $course_data['description'] ?? '',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => explode(',', $course_data['location'] ?? 'Tennessee')[0],
            'addressRegion' => 'TN',
            'addressCountry' => 'US'
        ],
        'sport' => 'Golf',
        'numberOfHoles' => $course_data['holes'] ?? null,
    ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Cedar Crest Golf Club</h1>
            <p>John Floyd Design • Murfreesboro, Tennessee</p>
            </div>
    </section>

    <!-- Course Details -->
    <section class="course-details" style="padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="course-info-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem; margin-bottom: 4rem;">
                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div style="display: grid; grid-template-columns: 1fr; gap: 0;">
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Holes:</span>
                            <span style="font-weight: 700; color: #2c5234;">18</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Par:</span>
                            <span style="font-weight: 700; color: #2c5234;">72</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Yardage:</span>
                            <span style="font-weight: 700; color: #2c5234;">6,828</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Designer:</span>
                            <span style="font-weight: 700; color: #2c5234;">John Floyd</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Opened:</span>
                            <span style="font-weight: 700; color: #2c5234;">1999</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Type:</span>
                            <span style="font-weight: 700; color: #2c5234;">Public</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Rating:</span>
                            <span style="font-weight: 700; color: #2c5234;">71.6</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span style="font-weight: 600; color: #666;">Slope:</span>
                            <span style="font-weight: 700; color: #2c5234;">124</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                        <thead>
                            <tr style="background: #f0f4f0;">
                                <th style="padding: 0.5rem; text-align: left; color: #2c5234;">Rate</th>
                                <th style="padding: 0.5rem; text-align: center; color: #2c5234;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem; font-weight: 600;">Weekday (18)</td>
                                <td style="padding: 0.5rem; text-align: center; font-weight: 700; color: #2c5234;">~$32</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0; background: #f8f9fa;">
                                <td style="padding: 0.5rem; font-weight: 600;">Weekend (18)</td>
                                <td style="padding: 0.5rem; text-align: center; font-weight: 700; color: #2c5234;">~$38</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="margin-top: 1rem; font-size: 0.85rem; color: #666;">Rates may vary by season. Cart included in green fee. Call for current pricing and tee time availability.</p>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location &amp; Contact</h3>
                    <p style="margin-bottom: 1rem;"><strong>Address:</strong><br>
                    7972 Mona Road<br>
                    Murfreesboro, TN 37129</p>
                    <p style="margin-bottom: 1rem;"><strong>Phone:</strong><br>
                    <a href="tel:6158497837" style="color: #4a7c59;">(615) 849-7837</a></p>
                    <p style="margin-bottom: 1.5rem;"><strong>Website:</strong><br>
                    <a href="https://www.cedarcrestgolfclub.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">cedarcrestgolfclub.com</a></p>
                    <div>
                        <iframe
                            src="https://maps.google.com/maps?q=7972+Mona+Road,+Murfreesboro,+TN+37129&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            width="100%"
                            height="200"
                            style="border:0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Cedar Crest Golf Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=7972+Mona+Road,+Murfreesboro,+TN+37129"
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
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Cedar Crest Golf Club</h3>
                <p>Cedar Crest Golf Club is a public golf course in Murfreesboro, Tennessee, designed by John Floyd and opened in 1999. The 18-hole, par-72 layout measures 6,828 yards from the championship tees, with a course rating of 71.6 and slope of 124. The course was built on 175 acres of rolling former farmland.</p>

                <br>

                <p>The layout features bentgrass greens and Bermuda fairways, with four sets of tee boxes to accommodate a range of playing abilities. The back nine plays through tree-lined corridors that demand more precision than the more open front side. A log cabin clubhouse with a covered dining patio serves as the facility's hub.</p>

                <br>

                <p>Cedar Crest is open daily and offers cart-included green fees in the $32–$38 range depending on day and season. Online tee time booking is available through the club's website.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Course Amenities</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin: 2rem 0; justify-items: center;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Driving Range</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-circle-dot" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Practice Green</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-shopping-cart" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Covered Dining Patio</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-car" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Cart Included</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-calendar-alt" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Event Space</span>
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
                <p>Experience the beauty of Cedar Crest Golf Club</p>
            </div>
            <div class="gallery-grid">
                <img src="../images/courses/cedar-crest-golf-club/2.jpeg" alt="Cedar Crest Golf Club Murfreesboro, TN - Championship 18-hole golf course fairway with manicured Bermuda grass, Tennessee public golf course" class="gallery-item">
                <img src="../images/courses/cedar-crest-golf-club/3.jpeg" alt="Cedar Crest Golf Club Murfreesboro, TN - Pristine putting green with strategic bunkers and mature landscaping, Public 18-hole Tennessee golf course" class="gallery-item">
                <img src="../images/courses/cedar-crest-golf-club/4.jpeg" alt="Cedar Crest Golf Club Murfreesboro, TN - Scenic golf course landscape with rolling hills and tree-lined fairways, Tennessee public golf course" class="gallery-item">
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Cedar Crest Golf Club - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Share This Course Section -->
    <section class="share-course-section" style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="share-section" style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center;">
                <h3 class="share-title" style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Course</h3>
                <div class="share-buttons" style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/cedar-crest-golf-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Cedar Crest Golf Club'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/cedar-crest-golf-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Cedar Crest Golf Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/cedar-crest-golf-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Dynamic Footer -->
    <?php include '../includes/footer.php'; ?>

    <script src="/script.js?v=5"></script>
</body>
</html>
