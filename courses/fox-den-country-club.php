<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'fox-den-country-club';
$course_name = 'Fox Den Country Club';

$course_data = [
    'name' => 'Fox Den Country Club',
    'location' => 'Knoxville, TN',
    'description' => 'Private championship club in Knoxville, TN. Willard Byrd original 1969 design — 18-hole par 72 stretching 7,110 yards; hosted the Ben Hogan/Nike Tour Knoxville Open 1990–1999.',
    'image' => '/images/courses/fox-den-country-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Willard Byrd',
    'year_built' => 1969,
    'course_type' => 'Private'
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
        .photo-gallery { margin: 4rem 0; }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        .gallery-item {
            height: 250px;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .gallery-item:hover { transform: scale(1.05); }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; }
        .gallery-button { text-align: center; margin-top: 2rem; }
        .btn-gallery {
            background: #4a7c59; color: white; padding: 1rem 2rem;
            border: none; border-radius: 50px; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease;
        }
        .btn-gallery:hover { background: #2c5234; transform: translateY(-2px); }
        .modal {
            display: none; position: fixed; z-index: 9999;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.9);
        }
        .modal.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-content {
            margin: 2% auto; padding: 20px;
            width: 90%; max-width: 1200px; position: relative;
            animation: slideUp 0.3s ease;
        }
        .modal-header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 2rem; color: white;
        }
        .modal-title { font-size: 2rem; margin: 0; }
        .close {
            color: white; font-size: 3rem; font-weight: bold;
            cursor: pointer; background: none; border: none;
        }
        .close:hover { color: #ccc; }
        .full-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem; max-height: 70vh; overflow-y: auto;
        }
        .full-gallery-item {
            aspect-ratio: 4/3;
            background-size: cover; background-position: center;
            border-radius: 10px; cursor: pointer; transition: transform 0.3s ease;
        }
        .full-gallery-item:hover { transform: scale(1.05); }
        @media (max-width: 768px) {
            .course-hero-content h1 { font-size: 2.5rem; }
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
            'streetAddress' => '12284 N Fox Den Drive',
            'addressLocality' => 'Knoxville',
            'addressRegion' => 'TN',
            'postalCode' => '37934',
            'addressCountry' => 'US'
        ],
        'telephone' => '+18659669771',
        'sport' => 'Golf',
        'numberOfHoles' => $course_data['holes'] ?? null,
    ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <section style="
        height: 60vh;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/fox-den-country-club/1.jpeg') center/cover no-repeat;
        display: flex; align-items: center; justify-content: center;
        text-align: center; color: white; margin-top: 20px;
    ">
        <div style="max-width: 800px; padding: 2rem;">
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Fox Den Country Club</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">Willard Byrd Design &bull; Knoxville, Tennessee</p>
        </div>
    </section>

    <section style="padding: 4rem 0;">
        <div class="container">

            <!-- Three-box row -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 3rem;">

                <!-- Course Information -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;">
                        <i class="fas fa-info-circle"></i> Course Information
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0;">
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Holes:</span>
                            <span style="color: #666;">18</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Par:</span>
                            <span style="color: #666;">72</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Yardage:</span>
                            <span style="color: #666;">7,110 yards</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Course Rating:</span>
                            <span style="color: #666;">74.3</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Slope Rating:</span>
                            <span style="color: #666;">135</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Designer:</span>
                            <span style="color: #666;">Willard Byrd</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Opened:</span>
                            <span style="color: #666;">1969</span>
                        </div>
                        <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Type:</span>
                            <span style="color: #666;">Private</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;">
                        <i class="fas fa-dollar-sign"></i> Green Fees
                    </h3>
                    <div style="background: linear-gradient(135deg, #2c5234, #4a7c59); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; margin-bottom: 1.25rem;">
                        <p style="font-size: 1.1rem; font-weight: 600; margin: 0 0 0.5rem;">Private Members Only</p>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Membership required for play</p>
                    </div>
                    <p style="font-size: 0.95rem; color: #555; line-height: 1.6;">Fox Den Country Club is a private club — green fees are available exclusively to members and their guests. Contact the club to inquire about membership opportunities.</p>
                    <p style="margin-top: 0.75rem; font-size: 0.95rem; color: #555;">
                        <strong>Membership Inquiries:</strong><br>
                        <a href="tel:+18659669771" style="color: #2c5234;">(865) 966-9771</a>
                    </p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;">
                        <i class="fas fa-map-marker-alt"></i> Location &amp; Contact
                    </h3>
                    <p><strong>Address:</strong><br>12284 N Fox Den Drive<br>Knoxville, TN 37934</p>
                    <p><strong>Phone:</strong><br><a href="tel:+18659669771" style="color: #2c5234;">(865) 966-9771</a></p>
                    <p><strong>Website:</strong><br><a href="https://www.foxdencountryclub.com" target="_blank" rel="noopener" style="color: #2c5234;">foxdencountryclub.com</a></p>
                    <iframe
                        src="https://maps.google.com/maps?q=12284+N+Fox+Den+Drive,+Knoxville,+TN+37934&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="180" style="border:0; border-radius: 8px; margin-top: 0.75rem;" loading="lazy">
                    </iframe>
                    <p style="margin-top: 0.5rem;">
                        <a href="https://maps.google.com/maps?q=12284+N+Fox+Den+Drive,+Knoxville,+TN+37934" target="_blank" rel="noopener" style="color: #2c5234;">
                            <i class="fas fa-directions"></i> Get Directions
                        </a>
                    </p>
                </div>
            </div>

            <!-- About + Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Fox Den Country Club</h2>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444; margin-bottom: 1.2rem;">
                        Fox Den Country Club is one of East Tennessee's most storied private golf clubs, founded by Chester A. Massey and first opening its original nine holes on May 15, 1969. The full 18-hole layout was completed in 1970, designed by Willard Byrd — a prolific architect whose courses are known for their strategic bunkering and natural flow across the terrain. In 2004, the course underwent a significant renovation by Bill Bergin, refreshing the layout while preserving its classic character.
                    </p>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444; margin-bottom: 1.2rem;">
                        The championship layout stretches to 7,110 yards from the back tees with a course rating of 74.3 and slope of 135 — a demanding test that earned national recognition when Fox Den hosted the Ben Hogan Tour (later Nike Tour) Knoxville Open for a full decade, from 1990 through 1999. That tournament brought some of the best developmental-tour players in the country to Knoxville, cementing Fox Den's reputation as a course capable of holding professional competition.
                    </p>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444;">
                        Today Fox Den operates as a fully private club, offering members a championship golf experience alongside comprehensive club amenities. The course is known for its mature tree canopy, rolling East Tennessee terrain, and well-maintained bentgrass greens. A new clubhouse opened in June 1995 brought enhanced dining and event facilities to complement the golf experience. Multiple tee options allow members of every skill level to enjoy a fulfilling round on this historic layout.
                    </p>
                </div>

                <div>
                    <h3 style="color: #2c5234; margin-bottom: 1rem;">Amenities</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-store" style="color: #2c5234; width: 20px;"></i> Pro Shop
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-golf-ball" style="color: #2c5234; width: 20px;"></i> Driving Range
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-circle" style="color: #2c5234; width: 20px;"></i> Practice Putting Green
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-utensils" style="color: #2c5234; width: 20px;"></i> Clubhouse Dining
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-graduation-cap" style="color: #2c5234; width: 20px;"></i> Golf Instruction
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-calendar-alt" style="color: #2c5234; width: 20px;"></i> Tournament Hosting
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-users" style="color: #2c5234; width: 20px;"></i> Member Events
                        </div>
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
                <p>Experience the beauty of Fox Den Country Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/fox-den-country-club/1.jpeg" alt="Fox Den Country Club championship fairway Knoxville TN" loading="lazy">
                </div>
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/fox-den-country-club/2.jpeg" alt="Fox Den Country Club course view" loading="lazy">
                </div>
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/fox-den-country-club/3.jpeg" alt="Fox Den Country Club Willard Byrd design" loading="lazy">
                </div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery</button>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Fox Den Country Club — Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid"></div>
        </div>
    </div>

    <!-- Share This Course -->
    <section style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <h3 style="font-size: 1.3rem; color: #333; margin-bottom: 1rem;">Share This Course</h3>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/fox-den-country-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Fox Den Country Club in Knoxville, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/fox-den-country-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Fox Den Country Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/fox-den-country-club'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <script src="/script.js?v=5"></script>
    <script>
        const galleryImages = [
            '../images/courses/fox-den-country-club/1.jpeg',
            '../images/courses/fox-den-country-club/2.jpeg',
            '../images/courses/fox-den-country-club/3.jpeg',
        ];

        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const grid = document.getElementById('fullGalleryGrid');
            grid.innerHTML = galleryImages.map(src =>
                `<div class="full-gallery-item" style="background-image: url('${src}');" onclick="window.open('${src}', '_blank')"></div>`
            ).join('');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.getElementById('galleryModal').addEventListener('click', function(e) {
            if (e.target === this) closeGallery();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeGallery();
        });
    </script>
</body>
</html>
