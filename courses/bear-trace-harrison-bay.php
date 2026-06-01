<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'bear-trace-harrison-bay';
$course_name = 'Bear Trace at Harrison Bay';

$course_data = [
    'name' => 'Bear Trace at Harrison Bay',
    'location' => 'Harrison, TN',
    'description' => 'Jack Nicklaus Signature Design golf course in Harrison, TN. Experience stunning lakefront views and championship golf at this award-winning course.',
    'image' => '/images/courses/bear-trace-harrison-bay/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Jack Nicklaus',
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/bear-trace-harrison-bay/1.jpeg');
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
        
        /* Review system styles are now in the centralized include file */
        
        
        
        
        
        
        
        
        
        
        
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
            <h1>Bear Trace at Harrison Bay</h1>
            <p>Jack Nicklaus Signature Design • Harrison, Tennessee</p>
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
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">7,111 (Gold)</span>
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
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">74.9</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Slope:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">136</span>
                        </div>
                    </div>
                </div>

                <!-- Box 2: Green Fees -->
                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <p style="font-size: 0.8rem; font-weight: 600; color: #2c5234; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">In Season (Mar 21–Nov 30)</p>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 1.25rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid #2c5234;">
                                <th style="text-align: left; padding: 0.5rem 0; color: #2c5234;"></th>
                                <th style="text-align: right; padding: 0.5rem 0.25rem; color: #2c5234;">Weekday</th>
                                <th style="text-align: right; padding: 0.5rem 0.25rem; color: #2c5234;">Weekend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">18 Holes w/ Cart</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$70</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$80</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">9 Holes w/ Cart</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$35</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$40</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">18 Holes Walking</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$50</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$60</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">Senior 18 w/ Cart (62+)</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$60</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; color: #999;">N/A</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">Junior 18 Walking</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$24</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$24</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0; color: #666;">Twilight 18 (after 3pm)</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$60</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$60</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.8rem; font-weight: 600; color: #2c5234; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Off Season (Dec 1–Mar 20)</p>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid #2c5234;">
                                <th style="text-align: left; padding: 0.5rem 0; color: #2c5234;"></th>
                                <th style="text-align: right; padding: 0.5rem 0.25rem; color: #2c5234;">Weekday</th>
                                <th style="text-align: right; padding: 0.5rem 0.25rem; color: #2c5234;">Weekend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">18 Holes w/ Cart</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$41</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$48</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">9 Holes w/ Cart</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$21</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$25</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.5rem 0; color: #666;">18 Holes Walking</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$24</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$29</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0; color: #666;">Senior 18 w/ Cart (62+)</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; font-weight: 700; color: #2c5234;">$34</td>
                                <td style="text-align: right; padding: 0.5rem 0.25rem; color: #999;">N/A</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.8rem; color: #999; margin-top: 1rem; font-style: italic;">All rates + tax &bull; Senior weekday only &bull; Rates subject to change</p>
                </div>

                <!-- Box 3: Location & Contact -->
                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location &amp; Contact</h3>
                    <p style="margin-bottom: 1rem;"><strong>Address:</strong><br>
                    8919 Harrison Bay Road<br>
                    Harrison, TN 37341</p>
                    <p style="margin-bottom: 1rem;"><strong>Phone:</strong><br>
                    <a href="tel:4233260885" style="color: #4a7c59;">(423) 326-0885</a></p>
                    <p style="margin-bottom: 1.5rem;"><strong>Website:</strong><br>
                    <a href="https://tnstateparks.com/golf/course/bear-trace-at-harrison-bay/" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">tnstateparks.com</a></p>
                    <div class="course-map">
                        <iframe
                            src="https://maps.google.com/maps?q=8919+Harrison+Bay+Road,+Harrison,+TN+37341&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            width="100%"
                            height="200"
                            style="border:0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Bear Trace at Harrison Bay Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=8919+Harrison+Bay+Road,+Harrison,+TN+37341"
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
                            <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: #b8860b;">Gold</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">7,111</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">74.9</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">136</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">72</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: #1a56db;">Blue</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">6,545</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">71.6</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">128</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">72</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: #555;">White</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">5,972</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">68.5</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">125</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">72</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: #c0392b;">Red</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">5,292</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">70.3</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">118</td>
                            <td style="text-align: right; padding: 0.6rem 0.5rem; color: #333;">72</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Course Description -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Bear Trace at Harrison Bay</h3>
                <p>Bear Trace at Harrison Bay is a stunning Jack Nicklaus-designed golf course situated on a peninsula along Chickamauga Lake in Harrison, Tennessee. Located just 18 miles from Chattanooga, this public 18-hole championship course opened in 1999 as part of the Tennessee Golf Trail—the Golden Bear's commitment to bringing quality public golf to the state.</p>

                <br>

                <p>Playing to 7,111 yards from the Gold tees with a course rating of 74.9 and slope of 136, Harrison Bay is both the longest and highest-rated of the three Bear Trace courses. The layout features Champion Bermuda greens and 419 Bermuda fairways lined with soaring pine and hardwood trees. With the Chickamauga Lake surrounding the property on three sides, 12 holes touch water while still providing ample room for safe play.</p>

                <br>

                <p>Most greens are open in front, making the course accessible for higher-handicap players while maintaining the traditional Nicklaus character through strategic fairway and greenside bunkers. The design rewards precise tee shots and thoughtful course management, particularly on the holes where lake carries demand commitment.</p>

                <br>

                <p>As part of <a href="https://tnstateparks.com/parks/harrison-bay" target="_blank" rel="noopener noreferrer" style="color: #4a7c59; font-weight: 600;">Harrison Bay State Park</a>, the course offers families additional recreational opportunities beyond golf. The state park features camping, hiking trails, swimming areas, boat launches, and picnic facilities, making it an ideal destination for golf trips where the whole family can enjoy outdoor activities on Chickamauga Lake.</p>
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
                <p>Experience the beauty of Bear Trace at Harrison Bay</p>
            </div>
            <div class="gallery-grid">
                <img src="../images/courses/bear-trace-harrison-bay/2.jpeg" alt="Bear Trace at Harrison Bay Harrison, TN - Championship 18-hole golf course fairway with manicured Bermuda grass designed by Jack Nicklaus, Tennessee public golf course" class="gallery-item">
                <img src="../images/courses/bear-trace-harrison-bay/3.jpeg" alt="Bear Trace at Harrison Bay Harrison, TN - Pristine putting green with strategic bunkers and mature landscaping, Public 18-hole Tennessee golf course" class="gallery-item">
                <img src="../images/courses/bear-trace-harrison-bay/4.jpeg" alt="Bear Trace at Harrison Bay Harrison, TN - Scenic golf course view featuring Jack Nicklaus architectural design, premium Tennessee public golf course" class="gallery-item">
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View All Photos (100+)</button>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Bear Trace at Harrison Bay - Complete Photo Gallery</h2>
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/bear-trace-harrison-bay'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Bear Trace at Harrison Bay'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/bear-trace-harrison-bay'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Bear Trace at Harrison Bay'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/bear-trace-harrison-bay'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
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
                <p>Book your tee time today and experience one of Tennessee's premier golf destinations</p>
                <div class="booking-buttons">
                    <a href="#" class="btn-book">Book Tee Time</a>
                    <a href="#" class="btn-contact">Contact Pro Shop</a>
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
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
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
            
            // Generate all 103 images
            for (let i = 1; i <= 103; i++) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/bear-trace-harrison-bay/${i}.jpeg')`;
                galleryItem.onclick = () => window.open(`../images/courses/bear-trace-harrison-bay/${i}.jpeg`, '_blank');
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
