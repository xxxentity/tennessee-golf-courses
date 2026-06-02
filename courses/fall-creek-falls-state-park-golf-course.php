<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'fall-creek-falls-state-park-golf-course';
$course_name = 'Fall Creek Falls State Park Golf Course';

$course_data = [
    'name' => 'Fall Creek Falls State Park Golf Course',
    'location' => 'Spencer, TN',
    'description' => 'Joe Lee designed championship golf course in Spencer, TN. 3-time Golf Digest Top 100 Public Courses selection featuring Cumberland Plateau views.',
    'image' => '/images/courses/fall-creek-falls-state-park-golf-course/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Joe Lee',
    'year_built' => 1972,
    'course_type' => 'State Park'
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
            'streetAddress' => '626 Golf Course Road',
            'addressLocality' => 'Spencer',
            'addressRegion' => 'TN',
            'postalCode' => '38585',
            'addressCountry' => 'US'
        ],
        'telephone' => '+14238815706',
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
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/fall-creek-falls-state-park-golf-course/1.webp');
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Fall Creek Falls State Park Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Joe Lee Design • Spencer, Tennessee</p>
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
                            <span style="font-weight: 600; color: #2c5234;">6,655</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Course Rating</span>
                            <span style="font-weight: 600; color: #2c5234;">71.4</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Slope</span>
                            <span style="font-weight: 600; color: #2c5234;">128</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Type</span>
                            <span style="font-weight: 600; color: #2c5234;">State Park</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Designer</span>
                            <span style="font-weight: 600; color: #2c5234;">Joe Lee</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span style="color: #666; font-weight: 500;">Opened</span>
                            <span style="font-weight: 600; color: #2c5234;">1972</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.2rem; font-size: 1.3rem;">Green Fees</h3>

                    <p style="font-size: 0.8rem; font-weight: 600; color: #2c5234; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">In Season (Mar – Nov)</p>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; margin-bottom: 1rem;">
                        <thead>
                            <tr style="background: #2c5234; color: white;">
                                <th style="padding: 0.45rem 0.6rem; text-align: left;">Rate</th>
                                <th style="padding: 0.45rem 0.6rem; text-align: center;">9 Holes</th>
                                <th style="padding: 0.45rem 0.6rem; text-align: center;">18 Holes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">Weekday</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$27</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$47</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">Weekend</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$30</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$52</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="font-size: 0.8rem; font-weight: 600; color: #2c5234; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Off Season (Nov – Mar)</p>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; margin-bottom: 1rem;">
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">Weekday 18</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;"></td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$43</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.4rem 0.6rem; font-weight: 500;">Weekend 18</td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;"></td>
                                <td style="padding: 0.4rem 0.6rem; text-align: center; color: #2c5234; font-weight: 600;">$46</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="font-size: 0.82rem; color: #888;">Cart included. All rates + tax. Senior/twilight discounts available. Call (423) 881-5706 for current rates.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.3rem;">Location & Contact</h3>
                    <p style="color: #555; line-height: 1.7; margin-bottom: 1rem;">
                        <strong>Address:</strong><br>
                        626 Golf Course Road<br>
                        Spencer, TN 38585
                    </p>
                    <p style="color: #555; margin-bottom: 0.6rem;">
                        <strong>Phone:</strong><br>
                        <a href="tel:4238815706" style="color: #2c5234; font-weight: 600; text-decoration: none;">(423) 881-5706</a>
                    </p>
                    <p style="color: #555; margin-bottom: 1.2rem;">
                        <strong>Website:</strong><br>
                        <a href="https://tnstateparks.com/golf/course/fall-creek-falls/" target="_blank" rel="noopener noreferrer" style="color: #2c5234; font-weight: 600;">tnstateparks.com</a>
                    </p>
                    <iframe
                        src="https://maps.google.com/maps?q=626+Golf+Course+Road,+Spencer,+TN+38585&output=embed"
                        width="100%"
                        height="150"
                        style="border: 0; border-radius: 8px; margin-bottom: 1rem;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                    <a href="https://maps.google.com/maps?q=626+Golf+Course+Road,+Spencer,+TN+38585"
                       target="_blank"
                       style="display: inline-block; background: #2c5234; color: white; padding: 0.6rem 1.2rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
                        <i class="fas fa-directions" style="margin-right: 0.4rem;"></i> Get Directions
                    </a>
                </div>

            </div>

            <!-- About & Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; margin-bottom: 4rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Fall Creek Falls State Park Golf Course</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        Fall Creek Falls State Park Golf Course is one of the most celebrated public golf destinations in the American South — a Joe Lee design that opened in 1972 within the boundaries of Tennessee's largest state park and has earned three separate selections on Golf Digest's list of Top 100 Public Places to Play. That kind of sustained recognition across multiple decades speaks to a course that holds up over time, and it's one that any serious Tennessee golfer eventually makes the trip to play.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        The layout occupies the top of the Cumberland Plateau near Spencer in Van Buren County, where the elevation and the surrounding 20,000-plus acres of protected park land give it a sense of scale and solitude that's difficult to find anywhere else in the region. The course plays to 6,655 yards from the championship Blue Tees with a rating of 71.4 and a slope of 128, offering multiple tee options that bring the layout down to 3,573 yards for families and beginners. Greens were fully rebuilt in 1998 to Joe Lee's original specifications, restoring the design intent and championship conditioning that the course has maintained since.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555;">
                        The course has been designated a Certified Audubon Cooperative Sanctuary by Audubon International, recognizing its commitment to environmental stewardship and wildlife management within the park setting. For golfers combining a trip to Fall Creek Falls with hiking, kayaking, or simply taking in the waterfall views that make the park famous, the golf course sits at the center of a legitimate outdoor destination rather than just a standalone stop. The combination of prestige, scenery, and state park pricing makes Fall Creek Falls one of the true values in Tennessee golf.
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
                            <i class="fas fa-store" style="color: #2c5234; width: 20px;"></i>
                            <span>Pro Shop</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-utensils" style="color: #2c5234; width: 20px;"></i>
                            <span>Restaurant & Grill</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-user-tie" style="color: #2c5234; width: 20px;"></i>
                            <span>PGA Instruction</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-car" style="color: #2c5234; width: 20px;"></i>
                            <span>Cart Rentals</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-calendar-alt" style="color: #2c5234; width: 20px;"></i>
                            <span>Tournament Hosting</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-leaf" style="color: #2c5234; width: 20px;"></i>
                            <span>Audubon Sanctuary</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-mountain" style="color: #2c5234; width: 20px;"></i>
                            <span>State Park Setting</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-award" style="color: #2c5234; width: 20px;"></i>
                            <span>Golf Digest Top 100</span>
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
                    <img src="../images/courses/fall-creek-falls-state-park-golf-course/2.webp" alt="Fall Creek Falls Golf Course - Fairway" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/fall-creek-falls-state-park-golf-course/3.webp" alt="Fall Creek Falls Golf Course - Plateau Views" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/fall-creek-falls-state-park-golf-course/4.webp" alt="Fall Creek Falls Golf Course - Scenic Layout" style="width: 100%; height: 250px; object-fit: cover;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/courses/fall-creek-falls-state-park-golf-course" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Check%20out%20Fall%20Creek%20Falls%20State%20Park%20Golf%20Course&url=https://tennesseegolfcourses.com/courses/fall-creek-falls-state-park-golf-course" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Fall Creek Falls State Park Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/fall-creek-falls-state-park-golf-course'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Fall Creek Falls State Park Golf Course - Complete Photo Gallery</h2>
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
                img.src = `../images/courses/fall-creek-falls-state-park-golf-course/${i}.webp`;
                img.alt = `Fall Creek Falls State Park Golf Course - Photo ${i}`;
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

    <script src="/script.js?v=5"></script>
</body>
</html>
