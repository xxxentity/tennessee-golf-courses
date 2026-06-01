<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'southern-hills-golf-country-club';
$course_name = 'Southern Hills Golf & Country Club';

$course_data = [
    'name' => 'Southern Hills Golf & Country Club',
    'location' => 'Cookeville, TN',
    'description' => 'Public golf course in Cookeville, TN. Established 1988 with 18 holes, par 72, and greens widely regarded as the best in the area.',
    'image' => '/images/courses/southern-hills-golf-country-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'N/A',
    'year_built' => 1988,
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
    <section class="course-hero" style="
        height: 60vh; 
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/southern-hills-golf-country-club/1.jpeg'); 
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Southern Hills Golf & Country Club</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">Cookeville, Tennessee</p>
        </div>
    </section>

    <!-- Course Details -->
    <section class="course-details" style="padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div class="course-info-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem; margin-bottom: 4rem;">
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
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">6,057</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Rating:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">69.1</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Slope:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">119</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Opened:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">1988</span>
                        </div>
                        <div class="spec-item" style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span class="spec-label" style="font-weight: 600; color: #666;">Type:</span>
                            <span class="spec-value" style="font-weight: 700; color: #2c5234;">Public</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>

                    <p style="font-weight: 600; color: #2c5234; margin-bottom: 0.5rem;">Non-Member Rates</p>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.25rem; font-size: 0.95rem;">
                        <thead>
                            <tr style="background: #2c5234; color: white;">
                                <th style="padding: 0.6rem 0.75rem; text-align: left; border-radius: 6px 0 0 0;">Round</th>
                                <th style="padding: 0.6rem 0.75rem; text-align: right; border-radius: 0 6px 0 0;">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background: #f8f9fa;">
                                <td style="padding: 0.6rem 0.75rem; border-bottom: 1px solid #e9ecef;">9 Holes</td>
                                <td style="padding: 0.6rem 0.75rem; border-bottom: 1px solid #e9ecef; text-align: right; font-weight: 600; color: #2c5234;">$28</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6rem 0.75rem;">18 Holes</td>
                                <td style="padding: 0.6rem 0.75rem; text-align: right; font-weight: 600; color: #2c5234;">$45</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.8rem; color: #666; margin-bottom: 1.25rem;">* Cart &amp; tax included</p>

                    <p style="font-weight: 600; color: #2c5234; margin-bottom: 0.5rem;">Annual Membership Pricing <span style="font-weight: 400; font-size: 0.85rem;">(Effective March 1, 2025)</span></p>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 0.75rem; font-size: 0.9rem;">
                        <tbody>
                            <tr style="background: #f8f9fa;">
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef;">Individual Membership</td>
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef; text-align: right; font-weight: 600; color: #2c5234;">$650</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef;">
                                    Family Membership
                                    <div style="font-size: 0.78rem; color: #888;">$400 per member after first two</div>
                                </td>
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef; text-align: right; font-weight: 600; color: #2c5234; vertical-align: top;">$1,000</td>
                            </tr>
                            <tr style="background: #f8f9fa;">
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef;">Cart Rental</td>
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef; text-align: right; font-weight: 600; color: #2c5234;">$1,100</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef;">Unlimited Range (Nonmember)</td>
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef; text-align: right; font-weight: 600; color: #2c5234;">$400</td>
                            </tr>
                            <tr style="background: #f8f9fa;">
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef;">Unlimited Range (Member)</td>
                                <td style="padding: 0.5rem 0.75rem; border-bottom: 1px solid #e9ecef; text-align: right; font-weight: 600; color: #2c5234;">$250</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0.75rem;">Trail Fee</td>
                                <td style="padding: 0.5rem 0.75rem; text-align: right; font-weight: 600; color: #2c5234;">$800</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.82rem; color: #555; margin-bottom: 0.25rem;">Payment plan available — pay ½ now, balance July 1st.</p>
                    <p style="font-size: 0.82rem; color: #555;">Members in good standing from prior year receive a 10% discount.</p>
                </div>

                <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>

                    <p><strong>Address:</strong><br>
                    4770 Ben Jared Rd<br>
                    Cookeville, TN 38506</p>

                    <p><strong>Phone:</strong><br>
                    <a href="tel:9314325149" style="color: #4a7c59; text-decoration: none;">(931) 432-5149</a></p>

                    <p><strong>Website:</strong><br>
                    <a href="https://shgc1776.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">shgc1776.com</a></p>

                    <div class="course-map" style="margin-top: 1.5rem;">
                        <iframe 
                            src="https://maps.google.com/maps?q=4770+Ben+Jared+Rd,+Cookeville,+TN+38506&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Southern Hills Golf & Country Club Location">
                        </iframe>
                        <div style="margin-top: 0.5rem; text-align: center;">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=4770+Ben+Jared+Rd,+Cookeville,+TN+38506" 
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
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Southern Hills Golf & Country Club</h3>
                <p>Southern Hills Golf & Country Club has served the Cookeville community and surrounding areas since 1988, offering an exceptional public golf experience. Located on Ben Jared Road just off I-40, this 18-hole, par-72 course has built a reputation for maintaining the highest standards of course conditioning and customer service.</p>
                
                <br>
                
                <p>The course stretches 6,057 yards from the championship blue tees with a course rating of 69.1 and slope rating of 119, providing an engaging challenge for golfers of all skill levels. With seven different tee options ranging from 3,607 to 6,057 yards, Southern Hills accommodates everyone from beginners to experienced players, ensuring an enjoyable round regardless of ability level.</p>
                
                <br>
                
                <p>What truly sets Southern Hills apart is its commitment to exceptional course maintenance, particularly the greens which are widely regarded as "the best in the area." The pristinely maintained course features double-cut greens and an extremely high level of care that creates optimal playing conditions throughout the season. This attention to detail reflects the course's dedication to providing an outstanding golf experience.</p>
                
                <br>
                
                <p>Beyond the golf course, Southern Hills offers a complete country club experience with a well-appointed pro shop staffed by PGA Professional Bill Franklin, who brings 38 years of experience and provides instruction for golfers at all levels. The facility also features The 19th Hole bar and grill for post-round dining and relaxation, along with event spaces including the Magnolia Room and The Veranda that can accommodate 200-250 people for special occasions.</p>
            </div>

            <!-- Course Ratings -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-chart-line"></i> Course Ratings & Tees</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #2c5234;">
                        <h4 style="color: #2c5234; margin-bottom: 1rem;">Blue Tees (Championship)</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                            <div><strong>Yardage:</strong> 6,057</div>
                            <div><strong>Par:</strong> 72</div>
                            <div><strong>Rating:</strong> 69.1</div>
                            <div><strong>Slope:</strong> 119</div>
                        </div>
                    </div>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #4a7c59;">
                        <h4 style="color: #2c5234; margin-bottom: 1rem;">White Tees (Regular)</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                            <div><strong>Yardage:</strong> 5,863</div>
                            <div><strong>Par:</strong> 72</div>
                            <div><strong>Rating:</strong> 68.3</div>
                            <div><strong>Slope:</strong> 117</div>
                        </div>
                    </div>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #ffc107;">
                        <h4 style="color: #2c5234; margin-bottom: 1rem;">Gold Tees (Senior/Ladies)</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                            <div><strong>Yardage:</strong> 5,189</div>
                            <div><strong>Par:</strong> 72</div>
                            <div><strong>Rating:</strong> 65.3/69.8</div>
                            <div><strong>Slope:</strong> 109/115</div>
                        </div>
                    </div>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #fd7e14;">
                        <h4 style="color: #2c5234; margin-bottom: 1rem;">Orange Tees (Forward)</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                            <div><strong>Yardage:</strong> 4,591</div>
                            <div><strong>Par:</strong> 72</div>
                            <div><strong>Rating:</strong> 63.0</div>
                            <div><strong>Slope:</strong> 105</div>
                        </div>
                    </div>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #dc3545;">
                        <h4 style="color: #2c5234; margin-bottom: 1rem;">Red Tees (Ladies)</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                            <div><strong>Yardage:</strong> 4,591</div>
                            <div><strong>Par:</strong> 72</div>
                            <div><strong>Rating:</strong> 66.5</div>
                            <div><strong>Slope:</strong> 108</div>
                        </div>
                    </div>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #343a40;">
                        <h4 style="color: #2c5234; margin-bottom: 1rem;">Black Tees (Beginner)</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.9rem;">
                            <div><strong>Yardage:</strong> 3,607</div>
                            <div><strong>Par:</strong> 72</div>
                            <div><strong>Rating:</strong> 61.5</div>
                            <div><strong>Slope:</strong> 97</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="course-info-card" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 4rem;">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Club Amenities</h3>
                <div class="amenities-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; justify-items: center;">
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Championship Golf</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-store" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-graduation-cap" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Golf Instruction</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>19th Hole Bar & Grill</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-calendar-alt" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Event Spaces</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-users" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Membership Options</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-tee" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Seven Tee Options</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-award" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Best Greens in Area</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-user-tie" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>PGA Professional</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-clock" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Daily 6AM-6PM</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-road" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Off I-40 Access</span>
                    </div>
                    <div class="amenity-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-parking" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Ample Parking</span>
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
                <p>Experience the beauty of Southern Hills Golf & Country Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/southern-hills-golf-country-club/2.jpeg" alt="Southern Hills Golf & Country Club Cookeville TN - Panoramic fairway view hole 12 with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/southern-hills-golf-country-club/3.jpeg" alt="Southern Hills Golf & Country Club Tennessee - Championship golf course layout showing championship layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/southern-hills-golf-country-club/4.jpeg" alt="Southern Hills Golf & Country Club Cookeville TN - Championship golf course entrance with professional landscaping and signage" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/southern-hills-golf-country-club'); ?>" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Southern Hills Golf Country Club in Cookeville, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/southern-hills-golf-country-club'); ?>" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Southern Hills Golf Country Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/southern-hills-golf-country-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Southern Hills Golf & Country Club - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Dynamic Footer -->
    <?php include '../includes/footer.php'; ?>
    
    <script>
        // Star rating functionality
        document.querySelectorAll('.star-rating input[type="radio"]').forEach((radio) => {
            radio.addEventListener('change', function() {
                const stars = document.querySelectorAll('.star-rating label');
                stars.forEach((star, starIndex) => {
                    if (starIndex >= (5 - this.value)) {
                        star.style.color = '#ffd700';
                    } else {
                        star.style.color = '#ddd';
                    }
                });
            });
        });
        
        // Gallery Modal Functions
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Alt text patterns for different image types
            function getAltText(imageIndex) {
                const courseName = 'Southern Hills Golf & Country Club';
                const location = 'Cookeville, TN';
                const locationShort = 'Cookeville TN';
                
                if (imageIndex <= 5) {
                    // Course overview shots
                    const overviewTexts = [
                        `${courseName} ${location} - Aerial view of championship 18-hole golf course showing signature holes and clubhouse facilities`,
                        `${courseName} ${locationShort} - Panoramic fairway view with strategic bunkers and mature trees`,
                        `${courseName} Tennessee - Championship golf course layout showing undulating fairways and natural terrain`,
                        `${courseName} ${locationShort} - Golf course entrance with professional landscaping and signage`,
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
                galleryItem.innerHTML = `<img src="../images/courses/southern-hills-golf-country-club/${i}.jpeg" alt="${getAltText(i)}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">`;
                galleryItem.onclick = () => window.open(`../images/courses/southern-hills-golf-country-club/${i}.jpeg`, '_blank');
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