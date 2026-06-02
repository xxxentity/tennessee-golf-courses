<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'harpeth-hills-golf-course';
$course_name = 'Harpeth Hills Golf Course';

$course_data = [
    'name' => 'Harpeth Hills Golf Course',
    'location' => 'Nashville, TN',
    'description' => 'Nashville Metro Parks municipal course in Percy Warner Park. Allen Brown & Herschel Eaton redesign (1991), 6,899 yards, TifEagle greens, former USGA Public Links qualifying site.',
    'image' => '/images/courses/harpeth-hills-golf-course/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Allen Brown & Herschel Eaton',
    'year_built' => 1965,
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
            'streetAddress' => '2424 Old Hickory Blvd',
            'addressLocality' => 'Nashville',
            'addressRegion' => 'TN',
            'postalCode' => '37221',
            'addressCountry' => 'US'
        ],
        'telephone' => '+16158628493',
        'sport' => 'Golf',
        'numberOfHoles' => $course_data['holes'] ?? null,
    ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <section style="
        height: 60vh;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/harpeth-hills-golf-course/1.jpeg') center/cover no-repeat;
        display: flex; align-items: center; justify-content: center;
        text-align: center; color: white; margin-top: 20px;
    ">
        <div style="max-width: 800px; padding: 2rem;">
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Harpeth Hills Golf Course</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">Nashville Metro Parks &bull; Percy Warner Park</p>
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
                            <span style="color: #666;">6,899 yards</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Designer:</span>
                            <span style="color: #666;">Brown &amp; Eaton (1991)</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Opened:</span>
                            <span style="color: #666;">1965</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Greens:</span>
                            <span style="color: #666;">TifEagle Bermuda</span>
                        </div>
                        <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Type:</span>
                            <span style="color: #666;">Municipal</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;">
                        <i class="fas fa-dollar-sign"></i> Green Fees
                    </h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead>
                            <tr style="background: #f0f4f0;">
                                <th style="padding: 0.5rem; text-align: left; border-bottom: 2px solid #2c5234;"></th>
                                <th style="padding: 0.5rem; text-align: center; border-bottom: 2px solid #2c5234;">Walking</th>
                                <th style="padding: 0.5rem; text-align: center; border-bottom: 2px solid #2c5234;">Cart</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 0.5rem; border-bottom: 1px solid #e0e0e0; font-weight: 600;">Weekday</td>
                                <td style="padding: 0.5rem; text-align: center; border-bottom: 1px solid #e0e0e0;">$29–$34</td>
                                <td style="padding: 0.5rem; text-align: center; border-bottom: 1px solid #e0e0e0;">+$8/9</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem; border-bottom: 1px solid #e0e0e0; font-weight: 600;">Weekend</td>
                                <td style="padding: 0.5rem; text-align: center; border-bottom: 1px solid #e0e0e0;">$34–$39</td>
                                <td style="padding: 0.5rem; text-align: center; border-bottom: 1px solid #e0e0e0;">+$8/9</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem; font-weight: 600;">Senior</td>
                                <td style="padding: 0.5rem; text-align: center;" colspan="2">Reduced rates available</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.85rem; color: #666; margin-top: 0.75rem;">Operated by Nashville Metro Parks. Rates subject to change — call <a href="tel:+16158628493" style="color: #2c5234;">(615) 862-8493</a> or visit the website for current pricing.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;">
                        <i class="fas fa-map-marker-alt"></i> Location &amp; Contact
                    </h3>
                    <p><strong>Address:</strong><br>2424 Old Hickory Blvd<br>Nashville, TN 37221</p>
                    <p><strong>Phone:</strong><br><a href="tel:+16158628493" style="color: #2c5234;">(615) 862-8493</a></p>
                    <p><strong>Website:</strong><br><a href="https://www.nashville.gov/departments/parks/golf-courses/harpeth-hills-golf-course" target="_blank" rel="noopener" style="color: #2c5234;">nashville.gov</a></p>
                    <iframe
                        src="https://maps.google.com/maps?q=2424+Old+Hickory+Blvd,+Nashville,+TN+37221&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="180" style="border:0; border-radius: 8px; margin-top: 0.75rem;" loading="lazy">
                    </iframe>
                    <p style="margin-top: 0.5rem;">
                        <a href="https://maps.google.com/maps?q=2424+Old+Hickory+Blvd,+Nashville,+TN+37221" target="_blank" rel="noopener" style="color: #2c5234;">
                            <i class="fas fa-directions"></i> Get Directions
                        </a>
                    </p>
                </div>
            </div>

            <!-- About + Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Harpeth Hills Golf Course</h2>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444; margin-bottom: 1.2rem;">
                        Harpeth Hills Golf Course is Nashville's premier municipal golf facility, tucked inside the forested hills of Percy Warner Park on the city's west side. Originally opened in 1965, the course was expertly redesigned in 1991 by Allen Brown and Herschel Eaton into the challenging 6,899-yard, par-72 layout golfers play today. Managed by Nashville Metro Parks, it offers one of the most compelling public golf values in the entire metro area — championship terrain at municipal pricing.
                    </p>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444; margin-bottom: 1.2rem;">
                        The course winds through dramatic elevation changes and mature hardwood forest with minimal water hazards, placing the emphasis squarely on strategic shot placement, club selection, and course management. There's no routing around Percy Warner's hills — they're in play on nearly every hole, creating a round that rewards players who can shape shots and read terrain. In 2017, a full renovation of the putting surfaces brought new TifEagle ultra-dwarf Bermuda greens that provide consistent, fast conditions rivaling any private club in the region.
                    </p>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444;">
                        Harpeth Hills has served as a USGA Public Links Championship qualifying site, a credential that reflects the genuine championship quality of both the layout and conditioning. The park setting adds another dimension — wildlife sightings are common, the tree canopy provides natural shade throughout the round, and the overall atmosphere is one of quiet natural beauty rarely found on a city-operated course. For Nashville-area golfers seeking a test that goes beyond the typical public layout, Harpeth Hills consistently delivers.
                    </p>
                </div>

                <div>
                    <h3 style="color: #2c5234; margin-bottom: 1rem;">Amenities</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-store" style="color: #2c5234; width: 20px;"></i> Pro Shop
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-golf-ball" style="color: #2c5234; width: 20px;"></i> Practice Facilities
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-utensils" style="color: #2c5234; width: 20px;"></i> Clubhouse / Grill
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-car" style="color: #2c5234; width: 20px;"></i> Cart Rentals
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-seedling" style="color: #2c5234; width: 20px;"></i> TifEagle Bermuda Greens
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-certificate" style="color: #2c5234; width: 20px;"></i> Former USGA Qualifying Site
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-leaf" style="color: #2c5234; width: 20px;"></i> Percy Warner Park Setting
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-dollar-sign" style="color: #2c5234; width: 20px;"></i> Municipal Pricing
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
                <p>Experience the beauty of Harpeth Hills Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/harpeth-hills-golf-course/1.jpeg" alt="Harpeth Hills Golf Course Nashville TN Percy Warner Park fairway" loading="lazy">
                </div>
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/harpeth-hills-golf-course/2.jpeg" alt="Harpeth Hills Golf Course hole view through wooded terrain" loading="lazy">
                </div>
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/harpeth-hills-golf-course/3.jpeg" alt="Harpeth Hills Golf Course championship putting green" loading="lazy">
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
                <h2 class="modal-title">Harpeth Hills Golf Course — Photo Gallery</h2>
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/harpeth-hills-golf-course'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Harpeth Hills Golf Course in Nashville, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/harpeth-hills-golf-course'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Harpeth Hills Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/harpeth-hills-golf-course'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
            '../images/courses/harpeth-hills-golf-course/1.jpeg',
            '../images/courses/harpeth-hills-golf-course/2.jpeg',
            '../images/courses/harpeth-hills-golf-course/3.jpeg',
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
