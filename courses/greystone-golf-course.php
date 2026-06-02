<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'greystone-golf-course';
$course_name = 'Greystone Golf Course';

$course_data = [
    'name' => 'Greystone Golf Course',
    'location' => 'Dickson, TN',
    'description' => 'Mark McCumber championship design in Dickson, TN. 6,858-yard course with island green signature hole, native stone formations, and 10-time host of the Tennessee State Open.',
    'image' => '/images/courses/greystone-golf-course/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Mark McCumber',
    'year_built' => 1998,
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
            'streetAddress' => '2555 US-70',
            'addressLocality' => 'Dickson',
            'addressRegion' => 'TN',
            'postalCode' => '37055',
            'addressCountry' => 'US'
        ],
        'telephone' => '+16154460044',
        'sport' => 'Golf',
        'numberOfHoles' => $course_data['holes'] ?? null,
    ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <section style="
        height: 60vh;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/greystone-golf-course/1.jpeg') center/cover no-repeat;
        display: flex; align-items: center; justify-content: center;
        text-align: center; color: white; margin-top: 20px;
    ">
        <div style="max-width: 800px; padding: 2rem;">
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Greystone Golf Course</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">Mark McCumber Design &bull; Dickson, Tennessee</p>
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
                            <span style="color: #666;">6,858 yards</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Course Rating:</span>
                            <span style="color: #666;">73.1</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Slope Rating:</span>
                            <span style="color: #666;">132</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Designer:</span>
                            <span style="color: #666;">Mark McCumber</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Opened:</span>
                            <span style="color: #666;">1998</span>
                        </div>
                        <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #333;">Type:</span>
                            <span style="color: #666;">Public</span>
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
                                <th style="padding: 0.5rem; text-align: center; border-bottom: 2px solid #2c5234;">18 Holes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 0.5rem; border-bottom: 1px solid #e0e0e0; font-weight: 600;">Weekday</td>
                                <td style="padding: 0.5rem; text-align: center; border-bottom: 1px solid #e0e0e0;">~$40–$55</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem; border-bottom: 1px solid #e0e0e0; font-weight: 600;">Weekend</td>
                                <td style="padding: 0.5rem; text-align: center; border-bottom: 1px solid #e0e0e0;">~$55–$75</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem; font-weight: 600;">Twilight</td>
                                <td style="padding: 0.5rem; text-align: center;">Reduced rates</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.85rem; color: #666; margin-top: 0.75rem;">Rates vary seasonally. Call <a href="tel:+16154460044" style="color: #2c5234;">(615) 446-0044</a> or visit the website for current pricing.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;">
                        <i class="fas fa-map-marker-alt"></i> Location &amp; Contact
                    </h3>
                    <p><strong>Address:</strong><br>2555 US-70<br>Dickson, TN 37055</p>
                    <p><strong>Phone:</strong><br><a href="tel:+16154460044" style="color: #2c5234;">(615) 446-0044</a></p>
                    <p><strong>Website:</strong><br><a href="https://www.greystonegc.com" target="_blank" rel="noopener" style="color: #2c5234;">greystonegc.com</a></p>
                    <iframe
                        src="https://maps.google.com/maps?q=2555+US-70,+Dickson,+TN+37055&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="180" style="border:0; border-radius: 8px; margin-top: 0.75rem;" loading="lazy">
                    </iframe>
                    <p style="margin-top: 0.5rem;">
                        <a href="https://maps.google.com/maps?q=2555+US-70,+Dickson,+TN+37055" target="_blank" rel="noopener" style="color: #2c5234;">
                            <i class="fas fa-directions"></i> Get Directions
                        </a>
                    </p>
                </div>
            </div>

            <!-- About + Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Greystone Golf Course</h2>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444; margin-bottom: 1.2rem;">
                        Greystone Golf Course in Dickson, Tennessee is the championship design creation of 10-time PGA Tour winner Mark McCumber, crafted alongside designer Mike Beebe and opened in 1998. Set among the rolling hills and native limestone outcroppings of Middle Tennessee, the 6,858-yard par-72 layout carved a unique identity by embracing the natural terrain rather than flattening it — resulting in a course that demands creativity and precision from every tee.
                    </p>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444; margin-bottom: 1.2rem;">
                        The course's most famous feature is the signature island green on the par-3 15th hole, one of the most photographed and talked-about holes in Tennessee golf. The course also features Zoysia fairways and Crenshaw Bent greens, combination that holds up well through Tennessee's humid summers. With a course rating of 73.1 and slope of 132, Greystone provides a genuine championship test while remaining accessible to public players at competitive daily-fee rates just 40 miles west of Nashville.
                    </p>
                    <p style="font-size: 1.05rem; line-height: 1.8; color: #444;">
                        Greystone's tournament credentials are among the strongest of any public-access course in Tennessee. The course has hosted the Tennessee State Open a record 10 times — more than any other venue in the state — and has served as a site for PGA Tour Q-School qualifying. These accolades reflect not just the quality of the design, but also the consistency of the conditioning and the club's commitment to hosting high-level competitive golf. The combination of tournament pedigree, unique natural scenery, and a true championship layout makes Greystone one of the most distinctive public-access golf experiences in Middle Tennessee.
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
                            <i class="fas fa-utensils" style="color: #2c5234; width: 20px;"></i> Clubhouse Restaurant
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-graduation-cap" style="color: #2c5234; width: 20px;"></i> Golf Instruction
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-trophy" style="color: #2c5234; width: 20px;"></i> Tournament Host (TN State Open)
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-mountain" style="color: #2c5234; width: 20px;"></i> Native Stone Formations
                        </div>
                        <div style="width: 100%; padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-calendar-check" style="color: #2c5234; width: 20px;"></i> Online Tee Times
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
                <p>Experience the beauty of Greystone Golf Course</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/greystone-golf-course/1.jpeg" alt="Greystone Golf Course Dickson TN fairway and stone formations" loading="lazy">
                </div>
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/greystone-golf-course/2.jpeg" alt="Greystone Golf Course championship hole view" loading="lazy">
                </div>
                <div class="gallery-item" onclick="openGallery()">
                    <img src="../images/courses/greystone-golf-course/3.jpeg" alt="Greystone Golf Course Mark McCumber design" loading="lazy">
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
                <h2 class="modal-title">Greystone Golf Course — Photo Gallery</h2>
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/greystone-golf-course'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Greystone Golf Course in Dickson, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/greystone-golf-course'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Greystone Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/greystone-golf-course'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
            '../images/courses/greystone-golf-course/1.jpeg',
            '../images/courses/greystone-golf-course/2.jpeg',
            '../images/courses/greystone-golf-course/3.jpeg',
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
