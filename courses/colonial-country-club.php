<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'colonial-country-club';
$course_name = 'Colonial Country Club';

$course_data = [
    'name' => 'Colonial Country Club',
    'location' => 'Cordova, TN',
    'description' => 'Premier private club in Cordova, TN with 100+ year history. Rated #4 golf course in Tennessee by Golf Digest, host to 30+ PGA Tour events.',
    'image' => '/images/courses/colonial-country-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Joe Finger',
    'year_built' => 1913,
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
            cursor: pointer;
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .gallery-item:hover { transform: scale(1.05); }

        .gallery-button { text-align: center; margin-top: 2rem; }

        .btn-gallery {
            background: #4a7c59;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-gallery:hover { background: #2c5234; transform: translateY(-2px); }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.9);
        }

        .modal-content {
            margin: 2% auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            color: white;
        }

        .modal-title { font-size: 2rem; margin: 0; }

        .close {
            color: white;
            font-size: 3rem;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
        }

        .close:hover { color: #ccc; }

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

        .full-gallery-item:hover { transform: scale(1.05); }

        @media (max-width: 768px) {
            .course-info-grid { grid-template-columns: 1fr !important; }
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

    <!-- Hero -->
    <section style="
        height: 60vh;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/colonial-country-club/1.webp');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        margin-top: 20px;
    ">
        <div style="max-width: 800px; padding: 2rem;">
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Colonial Country Club</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">Championship Golf &bull; Cordova, Tennessee</p>
        </div>
    </section>

    <!-- Course Details -->
    <section style="padding: 4rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">

            <div class="course-info-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">

                <!-- Course Information -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div style="display: flex; flex-direction: column; gap: 0;">
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Holes:</span>
                            <span style="font-weight: 700; color: #2c5234;">18</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Par:</span>
                            <span style="font-weight: 700; color: #2c5234;">72</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Yardage (Tips):</span>
                            <span style="font-weight: 700; color: #2c5234;">7,334 yards</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Course Rating:</span>
                            <span style="font-weight: 700; color: #2c5234;">75.3</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Slope Rating:</span>
                            <span style="font-weight: 700; color: #2c5234;">138</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Designer:</span>
                            <span style="font-weight: 700; color: #2c5234;">Joe Finger</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Founded:</span>
                            <span style="font-weight: 700; color: #2c5234;">1913</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span style="font-weight: 600; color: #666;">Type:</span>
                            <span style="font-weight: 700; color: #2c5234;">Private</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <div style="background: linear-gradient(135deg, #8B4513, #A0522D); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; margin: 1rem 0;">
                        <h4 style="margin-bottom: 0.5rem; font-size: 1.2rem;">Private Members Only</h4>
                        <p style="margin: 0; opacity: 0.9;">Exclusive club membership required</p>
                    </div>
                    <p style="text-align: center; color: #666; margin-top: 1rem; font-size: 0.95rem;">
                        Colonial Country Club is among the premier private clubs in the Mid-South with a proud 100+ year history.
                        Contact the club directly for membership information and guest policies.
                    </p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location &amp; Contact</h3>
                    <p><strong>Address:</strong><br>
                    2736 Countrywood Parkway<br>
                    Cordova, TN 38016</p>

                    <p style="margin-top: 0.75rem;"><strong>Phone:</strong><br>
                    <a href="tel:9013880150" style="color: #4a7c59; text-decoration: none;">(901) 388-0150</a></p>

                    <p style="margin-top: 0.75rem;"><strong>Golf Shop:</strong><br>
                    <a href="tel:9013820084" style="color: #4a7c59; text-decoration: none;">(901) 382-0084</a></p>

                    <p style="margin-top: 0.75rem;"><strong>Website:</strong><br>
                    <a href="https://www.colonialcountryclub.org" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">colonialcountryclub.org</a></p>

                    <iframe
                        src="https://maps.google.com/maps?q=2736+Countrywood+Parkway,+Cordova,+TN+38016&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="200"
                        style="border:0; border-radius: 8px; margin-top: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Colonial Country Club Location">
                    </iframe>
                    <div style="margin-top: 0.5rem; text-align: center;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=2736+Countrywood+Parkway,+Cordova,+TN+38016"
                           target="_blank"
                           rel="noopener noreferrer"
                           style="font-size: 0.85rem; color: #4a7c59; text-decoration: none; font-weight: 500;">
                            <i class="fas fa-directions"></i> Get Directions
                        </a>
                    </div>
                </div>
            </div>

            <!-- About -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Colonial Country Club</h3>

                <p>Colonial Country Club stands as one of the premier private clubs in the Mid-South region, boasting a proud 100+ year history. Rated as high as #4 in Tennessee by Golf Digest, this championship layout has hosted over 30 PGA Tour events — the Danny Thomas Memphis Classic and its successors — welcoming legends including Jack Nicklaus, Lee Trevino, and Gary Player.</p>

                <br>

                <p>The championship South Course stretches 7,334 yards from the black tees with a par 72 design, course rating of 75.3, and slope of 138. Ten tee options ranging from 4,603 to 7,334 yards accommodate golfers of all skill levels while preserving the strategic depth that has made this a favorite venue for professional competition and discerning members for generations.</p>

                <br>

                <p>The design emphasizes strategic shot-making and precision with thoughtfully placed hazards, undulating greens, and mature landscaping. Each hole presents unique options that reward creative thinking and precise execution, while the varied topography keeps players engaged throughout the round on this tournament-tested layout.</p>

                <br>

                <p>Beyond golf, Colonial offers tennis courts, a state-of-the-art fitness center, a swimming pool with food and beverage service, and multiple dining venues ranging from casual to fine dining. The clubhouse serves as the heart of club life, offering members a picturesque setting defined by Southern hospitality and over a century of tradition.</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Championship Golf</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-tennis-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Tennis Courts</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-dumbbell" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Fitness Center</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-swimmer" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Swimming Pool</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Fine Dining</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-cocktail" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Casual Dining</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-trophy" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>PGA Tour History</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-crown" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Southern Hospitality</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
                <h2 style="color: #2c5234; font-size: 2.5rem; margin-bottom: 1rem;">Course Gallery</h2>
                <p style="color: #666; font-size: 1.1rem;">Experience the beauty of Colonial Country Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/colonial-country-club/1.webp" alt="Colonial Country Club Cordova TN - Championship golf course" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/colonial-country-club/2.webp" alt="Colonial Country Club - Pristine putting green with strategic bunkers and mature landscaping" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/colonial-country-club/3.webp" alt="Colonial Country Club Tennessee - Scenic golf course landscape with rolling hills and tree-lined fairways" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>

    <!-- Share This Course -->
    <section style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center;">
                <h3 style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Course</h3>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/colonial-country-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Colonial Country Club'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/colonial-country-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">&#x1D54F;</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Colonial Country Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/colonial-country-club'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Colonial Country Club &mdash; Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid"></div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        function openGallery() {
            const grid = document.getElementById('fullGalleryGrid');
            grid.innerHTML = '';
            for (let i = 1; i <= 25; i++) {
                const item = document.createElement('div');
                item.className = 'full-gallery-item';
                item.style.backgroundImage = `url('../images/courses/colonial-country-club/${i}.webp')`;
                item.onclick = () => window.open(`../images/courses/colonial-country-club/${i}.webp`, '_blank');
                grid.appendChild(item);
            }
            document.getElementById('galleryModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
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
