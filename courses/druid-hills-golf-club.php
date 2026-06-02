<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'druid-hills-golf-club';
$course_name = 'Druid Hills Golf Club';

$course_data = [
    'name' => 'Druid Hills Golf Club',
    'location' => 'Fairfield Glade, TN',
    'description' => 'Championship 18-hole resort course designed by Leon Howard in 1970. Sits on the highest point in Fairfield Glade with rolling bentgrass fairways and spectacular Cumberland Mountain vistas.',
    'image' => '/images/courses/druid-hills-golf-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Leon Howard',
    'year_built' => 1970,
    'course_type' => 'Resort'
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            animation: fadeIn 0.3s;
        }
        .modal-content {
            max-width: 1400px;
            width: 95%;
            margin: 2rem auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s;
        }
        .modal-header {
            background: #2c5234;
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .modal-title { font-size: 1.8rem; font-weight: 700; margin: 0; }
        .close {
            font-size: 2rem;
            cursor: pointer;
            background: none;
            border: none;
            color: white;
            transition: transform 0.3s;
        }
        .close:hover { transform: scale(1.2); }
        .full-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }
        .full-gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            aspect-ratio: 4/3;
        }
        .full-gallery-item img { width: 100%; height: 100%; object-fit: cover; }
        .full-gallery-item:hover { transform: scale(1.05); box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @media (max-width: 768px) {
            .modal-content { width: 100%; margin: 0; border-radius: 0; max-height: 100vh; }
            .full-gallery-grid { grid-template-columns: 1fr; padding: 1rem; }
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
            'streetAddress' => '433 Lakeview Drive',
            'addressLocality' => 'Fairfield Glade',
            'addressRegion' => 'TN',
            'postalCode' => '38558',
            'addressCountry' => 'US'
        ],
        'telephone' => '+19314562864',
        'sport' => 'Golf',
        'numberOfHoles' => $course_data['holes'] ?? null,
    ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero" style="
        height: 60vh;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/druid-hills-golf-club/1.jpeg');
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Druid Hills Golf Club</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Fairfield Glade Resort • Mountain Vistas • Fairfield Glade, Tennessee</p>
        </div>
    </section>

    <!-- Course Details -->
    <section class="course-details" style="padding: 4rem 0; background: white;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">

            <!-- Three-box row -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 3rem;">

                <!-- Course Information -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.3rem;">Course Information</h3>
                    <div style="display: flex; flex-direction: column; gap: 0;">
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Holes</span>
                            <span style="font-weight: 600; color: #2c5234;">18</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Par</span>
                            <span style="font-weight: 600; color: #2c5234;">72</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Yardage</span>
                            <span style="font-weight: 600; color: #2c5234;">6,270</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Course Rating</span>
                            <span style="font-weight: 600; color: #2c5234;">71.1</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Slope</span>
                            <span style="font-weight: 600; color: #2c5234;">128</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Type</span>
                            <span style="font-weight: 600; color: #2c5234;">Resort</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Designer</span>
                            <span style="font-weight: 600; color: #2c5234;">Leon Howard</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span style="color: #666; font-weight: 500;">Opened</span>
                            <span style="font-weight: 600; color: #2c5234;">1970</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.2rem; font-size: 1.3rem;">Green Fees</h3>

                    <p style="font-size: 0.8rem; font-weight: 600; color: #2c5234; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Member Rates</p>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 1rem;">
                        <thead>
                            <tr style="background: #2c5234; color: white;">
                                <th style="padding: 0.45rem 0.6rem; text-align: left;">Rate</th>
                                <th style="padding: 0.45rem 0.6rem; text-align: center;">9</th>
                                <th style="padding: 0.45rem 0.6rem; text-align: center;">18</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">Preferred Member</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$23</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$45</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">Regular Member</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$32</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$61</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">Member Walking</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$15</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$28</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="font-size: 0.8rem; font-weight: 600; color: #2c5234; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Guest Rates</p>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 1rem;">
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">Guest w/ Member</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$42</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$80</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">Unaccompanied</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$47</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$90</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">After 2 pm</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$28</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$51</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="font-size: 0.82rem; color: #888;">Cart included in all rates. Resort packages and annual memberships available.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.3rem;">Location & Contact</h3>
                    <p style="color: #555; line-height: 1.7; margin-bottom: 1rem;">
                        <strong>Address:</strong><br>
                        433 Lakeview Drive<br>
                        Fairfield Glade, TN 38558
                    </p>
                    <p style="color: #555; margin-bottom: 0.6rem;">
                        <strong>Phone:</strong><br>
                        <a href="tel:9314562864" style="color: #2c5234; font-weight: 600; text-decoration: none;">(931) 456-2864</a>
                    </p>
                    <p style="color: #555; margin-bottom: 1.2rem;">
                        <strong>Resort:</strong> Fairfield Glade Resort
                    </p>
                    <iframe
                        src="https://maps.google.com/maps?q=433+Lakeview+Drive,+Fairfield+Glade,+TN+38558&output=embed"
                        width="100%"
                        height="150"
                        style="border: 0; border-radius: 8px; margin-bottom: 1rem;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                    <a href="https://maps.google.com/maps?q=433+Lakeview+Drive,+Fairfield+Glade,+TN+38558"
                       target="_blank"
                       style="display: inline-block; background: #2c5234; color: white; padding: 0.6rem 1.2rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
                        <i class="fas fa-directions" style="margin-right: 0.4rem;"></i> Get Directions
                    </a>
                </div>

            </div>

            <!-- About & Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; margin-bottom: 4rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Druid Hills Golf Club</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        Druid Hills Golf Club claims one of the most commanding positions of any course on the Cumberland Plateau, sitting at the highest elevation within the Fairfield Glade resort community. Designed by Leon Howard and opened in 1970, the 18-hole layout was built to take full advantage of its hilltop setting — panoramic views of the surrounding Cumberland Mountains are visible from multiple points across the course, and the elevation provides a cooler, more temperate playing environment than the valleys below.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        The course plays to a par of 72 from 6,270 yards off the blue tees, with a rating of 71.1 and a slope of 128. Multiple tee options ranging from 5,005 to 6,270 yards make it accessible to a wide spectrum of players while still presenting a genuine test from the back. Bentgrass covers both the fairways and greens, giving the course a lush look and consistent playing surface throughout the season. Howard's routing uses the natural slope of the hilltop to create elevation change on nearly every hole — uphill approaches, downhill drives, and sidehill lies keep golfers engaged from the first tee to the eighteenth green.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555;">
                        As part of the broader Fairfield Glade resort complex, Druid Hills is complemented by The Legends Restaurant, a full practice facility, and professional instruction. The resort community setting means the course regularly hosts events, leagues, and groups, yet maintains the standards of a well-kept private course. For visitors staying in the Cumberland Mountain area, Druid Hills offers an experience that's genuinely different from the valley courses — quieter, more scenic, and with a course design that rewards players who think their way around rather than overpower it.
                    </p>
                </div>

                <!-- Amenities -->
                <div>
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Amenities</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-golf-ball" style="color: #2c5234; width: 20px;"></i>
                            <span>Driving Range</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-flag" style="color: #2c5234; width: 20px;"></i>
                            <span>Practice Putting Green</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-store" style="color: #2c5234; width: 20px;"></i>
                            <span>Pro Shop</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-utensils" style="color: #2c5234; width: 20px;"></i>
                            <span>The Legends Restaurant</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-shopping-cart" style="color: #2c5234; width: 20px;"></i>
                            <span>Club Rentals</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-chalkboard-teacher" style="color: #2c5234; width: 20px;"></i>
                            <span>Golf Lessons</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-calendar-alt" style="color: #2c5234; width: 20px;"></i>
                            <span>Event Hosting</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-mountain" style="color: #2c5234; width: 20px;"></i>
                            <span>Mountain Views</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-home" style="color: #2c5234; width: 20px;"></i>
                            <span>Resort Community</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-calendar" style="color: #2c5234; width: 20px;"></i>
                            <span>Year-Round Play</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Photo Gallery Section -->
    <section class="photo-gallery" style="padding: 4rem 0; background: #f8f9fa;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <h2 style="text-align: center; margin-bottom: 3rem; color: #2c5234;">Course Gallery</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/druid-hills-golf-club/2.jpeg" alt="Druid Hills Golf Club - Fairway" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/druid-hills-golf-club/3.jpeg" alt="Druid Hills Golf Club - Mountain View" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/druid-hills-golf-club/4.jpeg" alt="Druid Hills Golf Club - Course Landscape" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
            </div>
            <div style="text-align: center;">
                <button onclick="openGallery()" style="background: #4a7c59; color: white; padding: 1rem 2rem; border: none; border-radius: 50px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-images" style="margin-right: 0.5rem;"></i> View Full Gallery (25 Photos)
                </button>
            </div>
        </div>
    </section>

    <!-- Share Section -->
    <section class="share-section" style="padding: 3rem 0; background: white;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="text-align: center;">
                <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Share This Course</h3>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/courses/druid-hills-golf-club" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Check%20out%20Druid%20Hills%20Golf%20Club&url=https://tennesseegolfcourses.com/courses/druid-hills-golf-club" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Druid Hills Golf Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/druid-hills-golf-club'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Druid Hills Golf Club - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid"></div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script>
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            galleryGrid.innerHTML = '';
            for (let i = 1; i <= 25; i++) {
                const item = document.createElement('div');
                item.className = 'full-gallery-item';
                const img = document.createElement('img');
                img.src = `../images/courses/druid-hills-golf-club/${i}.jpeg`;
                img.alt = `Druid Hills Golf Club - Photo ${i}`;
                img.loading = 'lazy';
                item.appendChild(img);
                galleryGrid.appendChild(item);
            }
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('galleryModal');
            if (event.target == modal) closeGallery();
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeGallery();
        });
    </script>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>
