<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'bear-trace-cumberland-mountain';
$course_name = 'Bear Trace at Cumberland Mountain';

$course_data = [
    'name' => 'Bear Trace at Cumberland Mountain',
    'location' => 'Crossville, TN',
    'description' => 'Jack Nicklaus Signature Design golf course in Crossville, TN. One of the Top Ten Courses in Tennessee by Golf Digest with championship layout.',
    'image' => '/images/courses/bear-trace-cumberland-mountain/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Jack Nicklaus',
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
    <?php echo $debug_info; ?>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php include '../includes/favicon.php'; ?>
    
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/bear-trace-cumberland-mountain/1.jpeg');
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
        
        /* Enhanced Star Rating Styles */
        .star-rating {
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        
        .star-rating label {
            padding: 5px;
            -webkit-text-stroke: 1px #ccc;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .star-rating label:hover {
            filter: brightness(1.2);
        }
        
        /* Mobile-friendly tap targets */
        @media (max-width: 768px) {
            .star-rating label {
                font-size: 2.5rem !important;
                padding: 8px;
            }
            
            .star-rating {
                gap: 12px !important;
            }
            
            .rating-text {
                display: block;
                margin-top: 10px !important;
                margin-left: 0 !important;
            }
        }
        
        /* Reply system styles */
        .reply-button {
            background: transparent;
            color: #4a7c59;
            border: 1px solid #4a7c59;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .reply-button:hover {
            background: #4a7c59;
            color: white;
        }
        
        .reply-form {
            margin-top: 1rem;
            padding-left: 2rem;
            border-left: 3px solid #e2e8f0;
        }
        
        .replies-container {
            margin-top: 1rem;
            padding-left: 2rem;
            border-left: 3px solid #f3f4f6;
        }
        
        .reply-item {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.8rem;
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
            <h1>Bear Trace at Cumberland Mountain</h1>
            <p>Jack Nicklaus Signature Design • Crossville, Tennessee</p>
            </div>
    </section>

    <!-- Course Details -->
    <section class="course-details">
        <div class="container">
            <div class="course-info-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 4rem;">

                <!-- Box 1: Course Information -->
                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs single-column" style="display: grid; grid-template-columns: 1fr; gap: 0;">
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Holes:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">18</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Par:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">72</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Yardage:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">6,928 (Nicklaus)</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Designer:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Jack Nicklaus</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Opened:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">1999</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Type:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Public</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Rating:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">74.0</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Slope:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">141</span>
                        </div>
                    </div>
                </div>

                <!-- Box 2: Green Fees -->
                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <p style="font-size: 0.8rem; font-weight: 600; color: #2c5234; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">In Season (Apr 1–Nov 16)</p>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; margin-bottom: 1.25rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid #2c5234;">
                                <th style="text-align: left; padding: 0.5rem 0; color: #2c5234;"></th>
                                <th style="text-align: right; padding: 0.5rem 0.25rem; color: #2c5234;">w/ Cart</th>
                                <th style="text-align: right; padding: 0.5rem 0.25rem; color: #2c5234;">Walking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">18 Holes</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$80</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$60</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">9 Holes</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$40</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$30</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">Senior 18 (62+)</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$65</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$45</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">Junior 18</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$44</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$24</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0; color: #666;">Twilight (after 3pm)</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$33</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$23</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.8rem; font-weight: 600; color: #2c5234; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Off Season (Nov 17–Mar 31)</p>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid #2c5234;">
                                <th style="text-align: left; padding: 0.5rem 0; color: #2c5234;"></th>
                                <th style="text-align: right; padding: 0.5rem 0.25rem; color: #2c5234;">w/ Cart</th>
                                <th style="text-align: right; padding: 0.5rem 0.25rem; color: #2c5234;">Walking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">18 Holes</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$49</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$29</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">9 Holes</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$25</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$15</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">Senior 18 (62+)</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$49</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$29</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0; color: #666;">Junior 18</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$44</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$24</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.8rem; color: #999; margin-top: 1rem; font-style: italic;">All rates + tax &bull; Junior: 18 &amp; under or valid college ID &bull; Rates subject to change</p>
                </div>

                <!-- Box 3: Location & Contact -->
                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location &amp; Contact</h3>
                    <p style="margin-bottom: 1rem;"><strong>Address:</strong><br>
                    407 Wild Plum Lane<br>
                    Crossville, TN 38572</p>
                    <p style="margin-bottom: 1rem;"><strong>Phone:</strong><br>
                    <a href="tel:9317071640" style="color: #4a7c59;">(931) 707-1640</a></p>
                    <p style="margin-bottom: 1.5rem;"><strong>Website:</strong><br>
                    <a href="https://tnstateparks.com/golf/course/bear-trace-at-cumberland-mountain" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">tnstateparks.com</a></p>
                    <div class="course-map">
                        <iframe
                            src="https://maps.google.com/maps?q=407+Wild+Plum+Lane,+Crossville,+TN+38572&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            width="100%"
                            height="200"
                            style="border:0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Bear Trace at Cumberland Mountain Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=407+Wild+Plum+Lane,+Crossville,+TN+38572"
                               target="_blank"
                               rel="noopener noreferrer"
                               style="font-size: 0.85rem; color: #4a7c59; text-decoration: none; font-weight: 500;">
                                <i class="fas fa-directions"></i> Get Directions
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tee Box Details -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-flag"></i> Tee Box Details</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid #2c5234;">
                            <th style="text-align: left; padding: 0.6rem 0.5rem; color: #2c5234;">Tee</th>
                            <th style="text-align: right; padding: 0.6rem 0.5rem; color: #2c5234;">Yardage</th>
                            <th style="text-align: right; padding: 0.6rem 0.5rem; color: #2c5234;">Rating</th>
                            <th style="text-align: right; padding: 0.6rem 0.5rem; color: #2c5234;">Slope</th>
                            <th style="text-align: right; padding: 0.6rem 0.5rem; color: #2c5234;">Par</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: #555;">Nicklaus</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">6,928</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">74.0</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">141</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">72</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: #1a56db;">Blue</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">6,430</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">71.6</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">139</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">72</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: #555;">White</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">5,916</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">69.0</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">133</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">72</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: #b8860b;">Gold</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">5,250</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">66.1</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">117</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">72</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: #c0392b;">Red</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">5,073</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">69.6</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">128</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">72</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Course Description -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Bear Trace at Cumberland Mountain</h3>
                <p>Bear Trace at Cumberland Mountain is one of the most acclaimed golf courses in Tennessee, featuring a masterful Jack Nicklaus design that opened in 1999. Located within Cumberland Mountain State Park in Crossville, TN, this public championship course has earned recognition as one of the premier golf destinations in the southeastern United States.</p>

                <br>

                <p>The 6,928-yard, par-72 layout from the Nicklaus tees carries a course rating of 74.0 and slope of 141—making it the most demanding of the three Bear Trace courses on the Tennessee Golf Trail. The design capitalizes on dramatic elevation changes and natural features including flowing brooks and clusters of mature pines. The signature 7th hole, a 393-yard par 4, incorporates the region's natural layered flagstone along the front of the green, creating both beauty and strategic challenge.</p>

                <br>

                <p>This Bear Trace course has earned numerous prestigious accolades: named "One of the Top Ten You Can Play in North America" by Golf Magazine in 1999, "One of the Top Ten Courses in Tennessee" by Golf Digest in 2001, and recognized by GOLFWEEK as the #1 Public Course in Tennessee for both 2012 and 2013.</p>

                <br>

                <p>The course features five sets of tees ranging from 5,073 to 6,928 yards, Tahoma 31 Bermuda fairways, and pristine bent grass greens. Several holes require forced carries over creeks and barrancas to undulating greens, many of which are cleverly set into hillsides. This variety ensures the layout is equally rewarding for first-time visitors and seasoned players managing the course strategically.</p>

                <br>

                <p>As part of <a href="https://tnstateparks.com/parks/cumberland-mountain" target="_blank" rel="noopener noreferrer" style="color: #4a7c59; font-weight: 600;">Cumberland Mountain State Park</a>, the course offers additional recreational opportunities including on-site cabins, camping, and hiking trails, making it an ideal destination for extended golf vacations with family.</p>
            </div>

            <!-- Amenities -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
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
                        <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Snack Bar</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-person-chalkboard" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Golf Lessons</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-campground" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>On-Site Cabins &amp; Campsites</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-bag-shopping" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Club Rental</span>
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
                <p>Experience the beauty of Bear Trace at Cumberland Mountain</p>
            </div>
            <div class="gallery-grid">
                <img src="../images/courses/bear-trace-cumberland-mountain/2.jpeg" alt="Bear Trace at Cumberland Mountain Crossville, TN - Championship 18-hole golf course fairway with manicured Bermuda grass designed by Jack Nicklaus, Tennessee public golf course" class="gallery-item">
                <img src="../images/courses/bear-trace-cumberland-mountain/3.jpeg" alt="Bear Trace at Cumberland Mountain Crossville, TN - Pristine putting green with strategic bunkers and mature landscaping, Public 18-hole Tennessee golf course" class="gallery-item">
                <img src="../images/courses/bear-trace-cumberland-mountain/4.jpeg" alt="Bear Trace at Cumberland Mountain Crossville, TN - Scenic golf course view featuring Jack Nicklaus architectural design, premium Tennessee public golf course" class="gallery-item">
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
                <h2 class="modal-title">Bear Trace at Cumberland Mountain - Complete Photo Gallery</h2>
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/bear-trace-cumberland-mountain'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Bear Trace at Cumberland Mountain'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/bear-trace-cumberland-mountain'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Bear Trace at Cumberland Mountain'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/bear-trace-cumberland-mountain'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Booking Section -->
    <section class="booking-section">
        <div class="container">
            <div class="booking-content">
                <h2>Ready to Play Bear Trace?</h2>
                <p>Book your tee time today and experience Jack Nicklaus championship golf in Tennessee</p>
                <div class="booking-buttons">
                    <a href="https://tnstateparks.com/golf/course/bear-trace-at-cumberland-mountain" target="_blank" class="btn-book">Book Tee Time</a>
                    <a href="tel:931-707-1640" class="btn-contact">Contact Pro Shop</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="../images/logos/logo.webp" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61579553544749" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        <a href="https://x.com/TNGolfCourses" target="_blank" rel="noopener noreferrer"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/tennesseegolfcourses/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@TennesseeGolf" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="../index.php#courses">Golf Courses</a></li>
                        <li><a href="../index.php#reviews">Reviews</a></li>
                        <li><a href="../index.php#news">News</a></li>
                        <li><a href="../index.php#about">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="/courses?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="/courses?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="/courses?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="/courses?region=Memphis Area">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-of-service">Terms of Service</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/script.js?v=5"></script>
    <script>
        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Generate all 25 images
            
            // Alt text patterns for different image types
            function getAltText(imageIndex) {
                const courseName = 'Bear Trace at Cumberland Mountain';
                const location = 'Crossville, TN';
                const locationShort = 'Crossville TN';
                
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
                galleryItem.innerHTML = `<img src="../images/courses/bear-trace-cumberland-mountain/${i}.jpeg" alt="${getAltText(i)}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">`;
                galleryItem.onclick = () => window.open(`../images/courses/bear-trace-cumberland-mountain/${i}.jpeg`, '_blank');
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
    <script>
        // Enhanced star rating with half-star support
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating-input');
        const ratingText = document.querySelector('.rating-text');
        let currentRating = 0;
        
        // Rating labels for different values
        const getRatingLabel = (rating) => {
            if (rating <= 1) return 'Poor';
            if (rating <= 2) return 'Fair';
            if (rating <= 3) return 'Good';
            if (rating <= 4) return 'Very Good';
            return 'Excellent';
        };
        
        // Update star display with half-star support
        function updateStarDisplay(rating) {
            stars.forEach((star, index) => {
                const starValue = index + 1;
                const starFull = star.querySelector('.star-full');
                
                if (rating >= starValue) {
                    // Full star
                    starFull.style.width = '100%';
                } else if (rating >= starValue - 0.5) {
                    // Half star
                    starFull.style.width = '50%';
                } else {
                    // Empty star
                    starFull.style.width = '0%';
                }
            });
            
            // Update rating text
            if (ratingText && rating > 0) {
                const displayRating = rating % 1 === 0 ? rating : rating.toFixed(1);
                ratingText.textContent = `${displayRating} star${rating > 1 ? 's' : ''} - ${getRatingLabel(rating)}`;
                ratingText.style.color = '#2c5234';
                ratingText.style.fontWeight = '600';
            } else if (ratingText) {
                ratingText.textContent = 'Click to rate';
                ratingText.style.color = '#666';
                ratingText.style.fontWeight = 'normal';
            }
        }
        
        // Handle star clicks with half-star detection
        stars.forEach((star) => {
            star.addEventListener('click', function(e) {
                const rect = star.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const width = rect.width;
                const starValue = parseFloat(star.dataset.value);
                
                // Determine if it's a half or full star click
                if (x < width / 2) {
                    // Click on left half - half star
                    currentRating = starValue - 0.5;
                } else {
                    // Click on right half - full star
                    currentRating = starValue;
                }
                
                ratingInput.value = currentRating;
                updateStarDisplay(currentRating);
            });
            
            // Handle hover with half-star preview
            star.addEventListener('mousemove', function(e) {
                if (currentRating > 0) return; // Don't show hover if already rated
                
                const rect = star.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const width = rect.width;
                const starValue = parseFloat(star.dataset.value);
                
                let hoverRating;
                if (x < width / 2) {
                    hoverRating = starValue - 0.5;
                } else {
                    hoverRating = starValue;
                }
                
                updateStarDisplay(hoverRating);
            });
        });
        
        // Reset display on mouse leave
        document.querySelector('.star-display').addEventListener('mouseleave', function() {
            updateStarDisplay(currentRating);
        });
        
        // Reply form toggle
        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById('reply-form-' + commentId);
            if (replyForm) {
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
            }
        }
        
        // Load More Reviews functionality - define globally for Cloudflare compatibility
        // Reset on page load to ensure correct starting point
        window.currentReviewOffset = 5; // We start by showing 5 reviews
        
        // Bypass rocket-loader by defining immediately
        window.loadMoreReviews = function() {
            const button = document.getElementById('load-more-reviews');
            button.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Loading...';
            button.disabled = true;
            
            console.log('Loading more reviews with offset:', window.currentReviewOffset);
            
            fetch('/courses/load-more-reviews?t=' + Date.now(), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache'
                },
                body: 'course_slug=bear-trace-cumberland-mountain&offset=' + window.currentReviewOffset
            })
            .then(response => response.text())
            .then(html => {
                console.log('Response HTML length:', html.length);
                console.log('Response content:', html.substring(0, 200) + '...');
                
                if (html.trim() && html.trim() !== '<!-- Not a POST request -->') {
                    // Insert new reviews before the Load More button
                    const loadMoreDiv = button.parentElement;
                    loadMoreDiv.insertAdjacentHTML('beforebegin', html);
                    window.currentReviewOffset += 5;
                    
                    console.log('Updated offset to:', window.currentReviewOffset);
                    
                    button.innerHTML = '<i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i>Load More Reviews';
                    button.disabled = false;
                } else {
                    // No more reviews
                    console.log('No more reviews found');
                    button.innerHTML = 'No more reviews';
                    button.disabled = true;
                    button.style.opacity = '0.5';
                }
            })
            .catch(error => {
                console.error('Error loading reviews:', error);
                button.innerHTML = '<i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i>Load More Reviews';
                button.disabled = false;
            });
        };
        
        // Load More Replies functionality  
        window.loadMoreReplies = function(commentId) {
            const button = document.getElementById('load-more-replies-' + commentId);
            button.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Loading...';
            button.disabled = true;
            
            fetch('/courses/load-more-replies?t=' + Date.now(), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache'
                },
                body: 'comment_id=' + commentId
            })
            .then(response => response.text())
            .then(html => {
                if (html.trim()) {
                    // Insert new replies before the Load More button
                    button.parentElement.insertAdjacentHTML('beforebegin', html);
                    button.remove(); // Remove the button since we loaded all replies
                } else {
                    button.innerHTML = 'No more replies';
                    button.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error loading replies:', error);
                button.innerHTML = '<i class="fas fa-comments" style="margin-right: 0.5rem;"></i>Try Again';
                button.disabled = false;
            });
        };
        
        // Also define toggleReplyForm globally for Cloudflare compatibility
        window.toggleReplyForm = function(commentId) {
            const replyForm = document.getElementById('reply-form-' + commentId);
            if (replyForm) {
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
            }
        };
    </script>
</body>
</html>
