<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'cumberland-cove-golf-course';
$course_name = 'Cumberland Cove Golf Course';

$course_data = [
    'name' => 'Cumberland Cove Golf Course',
    'location' => 'Monterey, TN',
    'description' => 'Historic 18-hole public golf course on the Cumberland Plateau, originally opened in 1967 as Mountain Ridge Golf Course. Scenic tree-lined fairways and rolling terrain near Monterey, Tennessee.',
    'image' => '/images/courses/cumberland-cove-golf-course/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'N/A',
    'year_built' => 1967,
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

    <?php include '../includes/favicon.php'; ?>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BSFPY01T7C"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-BSFPY01T7C');
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
        .modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }
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
        .full-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .full-gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
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
            'streetAddress' => '16941 Highway 70 N',
            'addressLocality' => 'Monterey',
            'addressRegion' => 'TN',
            'postalCode' => '38574',
            'addressCountry' => 'US'
        ],
        'telephone' => '+19318393313',
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
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/cumberland-cove-golf-course/1.jpeg');
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Cumberland Cove Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Public Golf on the Cumberland Plateau • Monterey, Tennessee</p>
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
                            <span style="color: #666; font-weight: 500;">Course Rating</span>
                            <span style="font-weight: 600; color: #2c5234;">71.7</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Slope</span>
                            <span style="font-weight: 600; color: #2c5234;">126</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Type</span>
                            <span style="font-weight: 600; color: #2c5234;">Public</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Designer</span>
                            <span style="font-weight: 600; color: #2c5234;">N/A</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span style="color: #666; font-weight: 500;">Opened</span>
                            <span style="font-weight: 600; color: #2c5234;">1967</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.3rem;">Green Fees</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead>
                            <tr style="background: #2c5234; color: white;">
                                <th style="padding: 0.6rem 0.8rem; text-align: left; border-radius: 6px 0 0 0;">Rate</th>
                                <th style="padding: 0.6rem 0.8rem; text-align: center;">Weekday</th>
                                <th style="padding: 0.6rem 0.8rem; text-align: center; border-radius: 0 6px 0 0;">Weekend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem; font-weight: 500;">18 Holes</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">Call</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">Call</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6rem 0.8rem; font-weight: 500;">9 Holes</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">Call</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">Call</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.85rem; color: #888; margin-top: 1rem;">Call (931) 839-3313 for current rates and tee times.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.3rem;">Location & Contact</h3>
                    <p style="color: #555; line-height: 1.7; margin-bottom: 1rem;">
                        <strong>Address:</strong><br>
                        16941 Highway 70 N<br>
                        Monterey, TN 38574
                    </p>
                    <p style="color: #555; margin-bottom: 1.2rem;">
                        <strong>Phone:</strong><br>
                        <a href="tel:9318393313" style="color: #2c5234; font-weight: 600; text-decoration: none;">(931) 839-3313</a>
                    </p>
                    <iframe
                        src="https://maps.google.com/maps?q=16941+Highway+70+N,+Monterey,+TN+38574&output=embed"
                        width="100%"
                        height="160"
                        style="border: 0; border-radius: 8px; margin-bottom: 1rem;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                    <a href="https://maps.google.com/maps?q=16941+Highway+70+N,+Monterey,+TN+38574"
                       target="_blank"
                       style="display: inline-block; background: #2c5234; color: white; padding: 0.6rem 1.2rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
                        <i class="fas fa-directions" style="margin-right: 0.4rem;"></i> Get Directions
                    </a>
                </div>

            </div>

            <!-- About & Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; margin-bottom: 4rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Cumberland Cove Golf Course</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        Cumberland Cove Golf Course carries more than half a century of history on the Cumberland Plateau, having first opened in 1967 under the name Mountain Ridge Golf Course. Situated along Highway 70 North near Monterey, the 18-hole layout occupies a stretch of plateau terrain defined by mature hardwoods, natural elevation changes, and the kind of quiet that draws golfers looking to escape the noise of the city. At roughly 2,000 feet above sea level, the plateau delivers cooler temperatures in summer and vivid foliage in the fall, giving the course a look and feel that changes meaningfully with each season.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        Playing to a par of 72 with a course rating of 71.7 and a slope of 126, Cumberland Cove offers a genuine but approachable challenge. The layout rewards course management over raw power — tree-lined corridors tighten the landing zones on many holes, and the rolling terrain means golfers regularly contend with uneven lies and uphill or downhill approaches. The greens are modest in size but hold their shape well throughout the season, and the overall conditioning reflects the course's status as a well-regarded public option in the upper Cumberland region.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555;">
                        For golfers traveling through Middle Tennessee or staying in the Crossville-Monterey corridor, Cumberland Cove provides a scenic and affordable alternative to the more commercial resort tracks nearby. The course draws a loyal local following and remains one of the longer-standing public layouts in the plateau area, a testament to its enduring appeal across generations of Tennessee golfers.
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
                            <i class="fas fa-shopping-cart" style="color: #2c5234; width: 20px;"></i>
                            <span>Club Rentals</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-car" style="color: #2c5234; width: 20px;"></i>
                            <span>Golf Cart Rentals</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-utensils" style="color: #2c5234; width: 20px;"></i>
                            <span>Snack Bar</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-flag" style="color: #2c5234; width: 20px;"></i>
                            <span>Practice Putting Green</span>
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
                    <img src="../images/courses/cumberland-cove-golf-course/1.jpeg" alt="Cumberland Cove Golf Course" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/cumberland-cove-golf-course/2.jpeg" alt="Cumberland Cove Golf Course - Fairway" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/cumberland-cove-golf-course/3.jpeg" alt="Cumberland Cove Golf Course - Scenic View" style="width: 100%; height: 250px; object-fit: cover;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/courses/cumberland-cove-golf-course" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Check%20out%20Cumberland%20Cove%20Golf%20Course&url=https://tennesseegolfcourses.com/courses/cumberland-cove-golf-course" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1da1f2; color: white;">
                        <i class="fab fa-twitter"></i> Share on Twitter
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Cumberland Cove Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/cumberland-cove-golf-course'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Cumberland Cove Golf Course - Complete Photo Gallery</h2>
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
                img.src = `../images/courses/cumberland-cove-golf-course/${i}.jpeg`;
                img.alt = `Cumberland Cove Golf Course - Photo ${i}`;
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
</body>
</html>
