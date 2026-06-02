<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'chickasaw-golf-course';
$course_name = 'Chickasaw Golf Course';

$course_data = [
    'name' => 'Chickasaw Golf Course',
    'location' => 'Henderson, TN',
    'description' => 'Jack Nicklaus Signature Design in Henderson, TN. Experience championship golf in a pristine natural woodland setting.',
    'image' => '/images/courses/chickasaw-golf-course/1.webp',
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
    <?php include '../includes/favicon.php'; ?>

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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/chickasaw-golf-course/1.webp');
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
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .spec-item:last-child {
            border-bottom: none;
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
            width: 100%;
        }

        .amenity-item i {
            color: #4a7c59;
            font-size: 1.2rem;
        }

        .photo-gallery {
            background: #f8f9fa;
            padding: 4rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            color: #2c5234;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
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
            overflow: hidden;
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
            .course-hero-content h1 { font-size: 2.5rem; }
            .course-info-grid { grid-template-columns: 1fr; gap: 2rem; }
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
    <?php include '../includes/navigation.php'; ?>

    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Chickasaw Golf Course</h1>
            <p>Jack Nicklaus Signature Design &bull; Henderson, Tennessee</p>
        </div>
    </section>

    <section class="course-details">
        <div class="container">
            <div class="course-info-grid">

                <!-- Course Information -->
                <div class="course-info-card">
                    <h3><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Holes:</span>
                            <span class="spec-value">18</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">72</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage (Tips):</span>
                            <span class="spec-value">7,118 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">75.1</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">135</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Jack Nicklaus</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Year Opened:</span>
                            <span class="spec-value">2000</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Type:</span>
                            <span class="spec-value">Public</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div class="course-info-card">
                    <h3><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem; margin-bottom: 1rem;">
                        <thead>
                            <tr style="background: #f0f4f0;">
                                <th style="padding: 0.6rem 0.8rem; text-align: left; font-weight: 600; color: #2c5234; border-bottom: 2px solid #2c5234;"></th>
                                <th style="padding: 0.6rem 0.8rem; text-align: center; font-weight: 600; color: #2c5234; border-bottom: 2px solid #2c5234;">18 Holes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 0.6rem 0.8rem; font-weight: 600; color: #666;">Weekday</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; font-weight: 700; color: #2c5234;">$35</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6rem 0.8rem; font-weight: 600; color: #666;">Weekend / Holiday</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; font-weight: 700; color: #2c5234;">$40</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.85rem; color: #888;"><i class="fas fa-info-circle"></i> Cart included in all rates. Rental clubs available. Call to confirm current pricing.</p>
                </div>

                <!-- Location & Contact -->
                <div class="course-info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location &amp; Contact</h3>
                    <p><strong>Address:</strong><br>
                    9555 State Route 100 W<br>
                    Henderson, TN 38340</p>

                    <p style="margin-top: 0.75rem;"><strong>Phone:</strong><br>
                    <a href="tel:7319893111" style="color: #4a7c59; text-decoration: none;">(731) 989-3111</a></p>

                    <p style="margin-top: 0.75rem;"><strong>Website:</strong><br>
                    <a href="https://www.golfatchickasaw.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">golfatchickasaw.com</a></p>

                    <iframe
                        src="https://maps.google.com/maps?q=9555+State+Route+100+W,+Henderson,+TN+38340&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="200"
                        style="border:0; border-radius: 8px; margin-top: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Chickasaw Golf Course Location">
                    </iframe>
                    <div style="margin-top: 0.5rem; text-align: center;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=9555+State+Route+100+W,+Henderson,+TN+38340"
                           target="_blank"
                           rel="noopener noreferrer"
                           style="font-size: 0.85rem; color: #4a7c59; text-decoration: none; font-weight: 500;">
                            <i class="fas fa-directions"></i> Get Directions
                        </a>
                    </div>
                </div>
            </div>

            <!-- About -->
            <div class="course-info-card">
                <h3><i class="fas fa-golf-ball"></i> About Chickasaw Golf Course</h3>

                <p>Chickasaw Golf Course, originally known as Bear Trace at Chickasaw, is a Jack Nicklaus Signature Design championship course nestled within Chickasaw State Park near Henderson, Tennessee. Opened in 2000 as one of five Bear Trace courses designed by the Golden Bear for Tennessee State Parks, the course now operates independently while maintaining its championship standards.</p>

                <br>

                <p>Set in a breathtaking natural woodland setting without a single home in sight, Chickasaw offers a pure golf experience amid forests of pines, oaks, tulip poplars, and hickories. The 7,118-yard layout from the tips features elevated tees with clear views of hazards, elevated greens requiring precise approach shots, and Piney Creek meandering past seven holes.</p>

                <br>

                <p>The course showcases Nicklaus's design philosophy with strategic bunkering, well-placed small ponds, and fairways wide enough to be playable without losing their challenge. With an average par-3 yardage of 197 yards, accuracy and shot-making matter at every level. A handcrafted log cabin clubhouse overlooks the 18th hole, complete with a stone fireplace lounge and full dining facilities.</p>

                <br>

                <p>Located approximately one hour east of Memphis within the scenic Chickasaw State Park, the course combines Jack Nicklaus-caliber design with an exceptional value that makes it one of the standout public courses in West Tennessee.</p>

                <div class="amenities-grid">
                    <div class="amenity-item" style="width: 100%;">
                        <i class="fas fa-golf-ball"></i>
                        <span>Driving Range</span>
                    </div>
                    <div class="amenity-item" style="width: 100%;">
                        <i class="fas fa-circle"></i>
                        <span>Putting Green</span>
                    </div>
                    <div class="amenity-item" style="width: 100%;">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pro Shop</span>
                    </div>
                    <div class="amenity-item" style="width: 100%;">
                        <i class="fas fa-location-dot"></i>
                        <span>GPS Carts</span>
                    </div>
                    <div class="amenity-item" style="width: 100%;">
                        <i class="fas fa-utensils"></i>
                        <span>Dining Area</span>
                    </div>
                    <div class="amenity-item" style="width: 100%;">
                        <i class="fas fa-beer"></i>
                        <span>Bar</span>
                    </div>
                    <div class="amenity-item" style="width: 100%;">
                        <i class="fas fa-users"></i>
                        <span>Banquet Hall</span>
                    </div>
                    <div class="amenity-item" style="width: 100%;">
                        <i class="fas fa-user-tie"></i>
                        <span>Pro on Site</span>
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
                <p>Experience the beauty of Chickasaw Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/chickasaw-golf-course/2.webp" alt="Chickasaw Golf Course Henderson TN - Panoramic fairway view with strategic bunkers and mature trees" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/chickasaw-golf-course/3.webp" alt="Chickasaw Golf Course Tennessee - Championship course layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/chickasaw-golf-course/4.webp" alt="Chickasaw Golf Course Henderson TN - Course entrance with professional landscaping" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Chickasaw Golf Course &mdash; Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid">
                <?php for ($i = 1; $i <= 25; $i++): ?>
                    <div class="full-gallery-item" style="background-image: url('../images/courses/chickasaw-golf-course/<?php echo $i; ?>.webp');"></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Share This Course -->
    <section style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center;">
                <h3 style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Course</h3>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/chickasaw-golf-course'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Chickasaw Golf Course'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/chickasaw-golf-course'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">&#x1D54F;</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Chickasaw Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/chickasaw-golf-course'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

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

        window.onclick = function(event) {
            const modal = document.getElementById('galleryModal');
            if (event.target === modal) closeGallery();
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeGallery();
        });
    </script>
</body>
</html>
