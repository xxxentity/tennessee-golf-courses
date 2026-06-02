<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'gaylord-springs-golf-links';
$course_name = 'Gaylord Springs Golf Links';

$course_data = [
    'name' => 'Gaylord Springs Golf Links',
    'location' => 'Nashville, TN',
    'description' => 'Larry Nelson Scottish links-style design at Gaylord Opryland Resort. 6,981 yards along the Cumberland River with limestone bluffs, wetlands, and a historic 100-year-old springhouse.',
    'image' => '/images/courses/gaylord-springs-golf-links/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Larry Nelson',
    'year_built' => 1990,
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
            'streetAddress' => '18 Springhouse Lane',
            'addressLocality' => 'Nashville',
            'addressRegion' => 'TN',
            'postalCode' => '37214',
            'addressCountry' => 'US'
        ],
        'telephone' => '+16154581730',
        'sport' => 'Golf',
        'numberOfHoles' => $course_data['holes'] ?? null,
    ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <section style="
        height: 60vh;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/gaylord-springs-golf-links/1.jpeg') center/cover no-repeat;
        display: flex; align-items: center; justify-content: center;
        text-align: center; color: white; margin-top: 20px;
    ">
        <div style="max-width: 800px; padding: 2rem;">
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Gaylord Springs Golf Links</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">Larry Nelson Design &bull; Nashville, Tennessee</p>
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
                            <span style="color: #666;">6,981 yards</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Course Rating:</span>
                            <span style="color: #666;">73.1</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Slope Rating:</span>
                            <span style="color: #666;">133</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Designer:</span>
                            <span style="color: #666;">Larry Nelson</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Opened:</span>
                            <span style="color: #666;">1990</span>
                        </div>
                        <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Type:</span>
                            <span style="color: #666;">Resort</span>
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
                                <th style="padding: 0.5rem; text-align: center; border-bottom: 2px solid #2c5234;">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 0.5rem; border-bottom: 1px solid #e0e0e0; font-weight: 600;">18 Holes w/ Cart</td>
                                <td style="padding: 0.5rem; text-align: center; border-bottom: 1px solid #e0e0e0;">~$179</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem; border-bottom: 1px solid #e0e0e0; font-weight: 600;">Resort Guests</td>
                                <td style="padding: 0.5rem; text-align: center; border-bottom: 1px solid #e0e0e0;">Preferred rates</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem; font-weight: 600;">Twilight / Mon–Thu</td>
                                <td style="padding: 0.5rem; text-align: center;">Reduced rates</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.85rem; color: #666; margin-top: 0.75rem;">Cart included. Dynamic pricing — rates vary by date and booking time. Visit website for current pricing.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;">
                        <i class="fas fa-map-marker-alt"></i> Location &amp; Contact
                    </h3>
                    <p><strong>Address:</strong><br>18 Springhouse Lane<br>Nashville, TN 37214</p>
                    <p><strong>Phone:</strong><br><a href="tel:+16154581730" style="color: #2c5234;">(615) 458-1730</a></p>
                    <p><strong>Website:</strong><br><a href="https://www.gaylordsprings.com" target="_blank" rel="noopener" style="color: #2c5234;">gaylordsprings.com</a></p>
                    <iframe
                        src="https://maps.google.com/maps?q=18+Springhouse+Lane,+Nashville,+TN+37214&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="180" style="border:0; border-radius: 8px; margin-top: 0.75rem;" loading="lazy">
                    </iframe>
                    <p style="margin-top: 0.5rem;">
                        <a href="https://maps.google.com/maps?q=18+Springhouse+Lane,+Nashville,+TN+37214" target="_blank" rel="noopener" style="color: #2c5234;">
                            <i class="fas fa-directions"></i> Get Directions
                        </a>
                    </p>
                </div>
            </div>

            <!-- About + Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Gaylord Springs Golf Links</h2>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444; margin-bottom: 1.2rem;">
                        Gaylord Springs Golf Links is Nashville's premier resort golf destination, designed by three-time major champion Larry Nelson and opened in 1990 as part of the legendary Gaylord Opryland Resort complex. Nelson channeled the spirit of authentic Scottish links golf into this 18-hole layout, which winds for 6,981 yards along the meandering banks of the Cumberland River. Now managed by Marriott Golf, the course has maintained its championship reputation for more than three decades while earning a consistent following among both local golfers and resort guests.
                    </p>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444; margin-bottom: 1.2rem;">
                        What makes Gaylord Springs distinctive is its landscape — rather than trees and rough, Nelson built the course around Cumberland River lowlands, limestone bluffs, and natural wetlands. Wide, windswept fairways demand thoughtful shot-shaping, strategic bunker play, and an understanding of the terrain that grows with each visit. The course plays to a rating of 73.1 with a slope of 133 from the Black tees, with four additional tee options accommodating golfers of every skill level from 5,179 to 6,981 yards.
                    </p>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444;">
                        One of the course's most beloved features is the historic springhouse on the fourth hole — a 100-year-old stone structure that once served the original farmland and gave the course its earlier name, Springhouse Links. The 43,000-square-foot antebellum-style clubhouse anchors the property with fine dining, a top-ranked golf shop, and the Golf Institute at Gaylord Springs, which offers PGA instruction, club fitting, and equipment repair. Whether you're a resort guest or a local looking for a premier Nashville golf experience, Gaylord Springs delivers championship conditions with a one-of-a-kind setting.
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
                            <i class="fas fa-graduation-cap" style="color: #2c5234; width: 20px;"></i> Golf Institute / Instruction
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-utensils" style="color: #2c5234; width: 20px;"></i> Clubhouse Dining
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-water" style="color: #2c5234; width: 20px;"></i> Cumberland River Views
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-building" style="color: #2c5234; width: 20px;"></i> Gaylord Opryland Resort Access
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-calendar-alt" style="color: #2c5234; width: 20px;"></i> Tournament Hosting
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
                <p>Experience the beauty of Gaylord Springs Golf Links</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/gaylord-springs-golf-links/1.jpeg" alt="Gaylord Springs Golf Links Nashville TN fairway view" loading="lazy">
                </div>
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/gaylord-springs-golf-links/2.jpeg" alt="Gaylord Springs Golf Links Cumberland River hole" loading="lazy">
                </div>
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/gaylord-springs-golf-links/3.jpeg" alt="Gaylord Springs Golf Links Scottish links-style layout" loading="lazy">
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
                <h2 class="modal-title">Gaylord Springs Golf Links — Photo Gallery</h2>
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/gaylord-springs-golf-links'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Gaylord Springs Golf Links in Nashville, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/gaylord-springs-golf-links'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Gaylord Springs Golf Links'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/gaylord-springs-golf-links'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
            '../images/courses/gaylord-springs-golf-links/1.jpeg',
            '../images/courses/gaylord-springs-golf-links/2.jpeg',
            '../images/courses/gaylord-springs-golf-links/3.jpeg',
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
