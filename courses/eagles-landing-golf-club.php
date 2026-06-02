<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'eagles-landing-golf-club';
$course_name = 'Eagle\'s Landing Golf Club';

$course_data = [
    'name' => 'Eagle\'s Landing Golf Club',
    'location' => 'Sevierville, TN',
    'description' => 'Historic municipal golf course in Sevierville, TN. Designed by D.J. DeVictor and opened in 1994. Now operating as Sevierville Golf Club.',
    'image' => '/images/courses/eagles-landing-golf-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'D.J. DeVictor',
    'year_built' => 1994,
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
        .course-notice {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-left: 5px solid #ffc107;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        .course-notice i { color: #856404; font-size: 1.2rem; margin-top: 2px; flex-shrink: 0; }
        .course-notice p { color: #664d03; margin: 0; line-height: 1.6; }
        .course-notice a { color: #2c5234; font-weight: 600; }

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
            'streetAddress' => '1556 Old Knoxville Highway',
            'addressLocality' => 'Sevierville',
            'addressRegion' => 'TN',
            'postalCode' => '37876',
            'addressCountry' => 'US'
        ],
        'telephone' => '+18654294223',
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
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/eagles-landing-golf-club/1.jpeg');
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Eagle's Landing Golf Club</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Now: Sevierville Golf Club • Sevierville, Tennessee</p>
        </div>
    </section>

    <!-- Course Details -->
    <section class="course-details" style="padding: 4rem 0; background: white;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">

            <!-- Rebranding notice -->
            <div class="course-notice">
                <i class="fas fa-info-circle"></i>
                <p><strong>Course Update:</strong> Eagle's Landing Golf Club has been rebranded and now operates as <a href="/courses/sevierville-golf-club">Sevierville Golf Club</a>. This page preserves historical information about the course under its original name. Visit the <a href="/courses/sevierville-golf-club">Sevierville Golf Club page</a> for current rates, tee times, and details.</p>
            </div>

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
                            <span style="font-weight: 600; color: #2c5234;">7,027</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Type</span>
                            <span style="font-weight: 600; color: #2c5234;">Public / Municipal</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Designer</span>
                            <span style="font-weight: 600; color: #2c5234;">D.J. DeVictor</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span style="color: #666; font-weight: 500;">Opened</span>
                            <span style="font-weight: 600; color: #2c5234;">1994</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.2rem; font-size: 1.3rem;">Green Fees</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead>
                            <tr style="background: #2c5234; color: white;">
                                <th style="padding: 0.6rem 0.8rem; text-align: left;">Rate</th>
                                <th style="padding: 0.6rem 0.8rem; text-align: center;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem; font-weight: 500;">18 Holes (Cart Incl.)</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">$79</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem; font-weight: 500;">Senior Rate</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">$49</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem; font-weight: 500;">9 Holes</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">$29</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6rem 0.8rem; font-weight: 500;">Twilight (After 4 pm)</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">$38</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.82rem; color: #888; margin-top: 1rem;">Historical rates — visit <a href="/courses/sevierville-golf-club" style="color: #2c5234;">Sevierville Golf Club</a> for current pricing.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.3rem;">Location & Contact</h3>
                    <p style="color: #555; line-height: 1.7; margin-bottom: 1rem;">
                        <strong>Address:</strong><br>
                        1556 Old Knoxville Highway<br>
                        Sevierville, TN 37876
                    </p>
                    <p style="color: #555; margin-bottom: 0.6rem;">
                        <strong>Phone:</strong><br>
                        <a href="tel:8654294223" style="color: #2c5234; font-weight: 600; text-decoration: none;">(865) 429-4223</a>
                    </p>
                    <p style="color: #555; margin-bottom: 1.2rem;">
                        <strong>Website:</strong><br>
                        <a href="https://www.seviervillegolfclub.com/" target="_blank" rel="noopener noreferrer" style="color: #2c5234; font-weight: 600;">seviervillegolfclub.com</a>
                    </p>
                    <iframe
                        src="https://maps.google.com/maps?q=1556+Old+Knoxville+Highway,+Sevierville,+TN+37876&output=embed"
                        width="100%"
                        height="150"
                        style="border: 0; border-radius: 8px; margin-bottom: 1rem;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                    <a href="https://maps.google.com/maps?q=1556+Old+Knoxville+Highway,+Sevierville,+TN+37876"
                       target="_blank"
                       style="display: inline-block; background: #2c5234; color: white; padding: 0.6rem 1.2rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
                        <i class="fas fa-directions" style="margin-right: 0.4rem;"></i> Get Directions
                    </a>
                </div>

            </div>

            <!-- About & Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; margin-bottom: 4rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Eagle's Landing Golf Club</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        Eagle's Landing Golf Club opened in 1994 as Sevierville's premier municipal golf course, designed by D.J. DeVictor along the banks of the Little Pigeon River in the shadow of the Great Smoky Mountains. The 18-hole layout stretched 7,027 yards from the gold tees and was immediately recognized for its championship conditioning and its accessible public pricing — a combination that made it one of the most-played courses in East Tennessee during its years under that name.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        The course featured Bermuda grass fairways with bentgrass greens, five different tee options from 4,986 to 7,027 yards, and strategic water hazards drawn from the river corridor running through the property. During its time as Eagle's Landing, the course earned recognition from both the Tennessee Turfgrass Association (Best Public Golf Course) and Golf Advisor (Top 50 US Courses), cementing its reputation beyond the regional market.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555;">
                        The course has since been rebranded as Sevierville Golf Club and continues to operate at the same location with updated facilities. Its position near Dollywood and the Great Smoky Mountains National Park keeps it on the radar of visiting golfers who mix a round of golf into trips centered around the broader Smoky Mountain region. The Eagle's Landing name remains part of the course's history and legacy in the Sevier County golf market.
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
                            <i class="fas fa-bullseye" style="color: #2c5234; width: 20px;"></i>
                            <span>Practice Bunkers</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-store" style="color: #2c5234; width: 20px;"></i>
                            <span>Pro Shop</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-utensils" style="color: #2c5234; width: 20px;"></i>
                            <span>Mulligan's Restaurant</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-user-tie" style="color: #2c5234; width: 20px;"></i>
                            <span>PGA Instruction</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-car" style="color: #2c5234; width: 20px;"></i>
                            <span>Cart Included</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-calendar-alt" style="color: #2c5234; width: 20px;"></i>
                            <span>Banquet Facility</span>
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
                    <img src="../images/courses/eagles-landing-golf-club/2.jpeg" alt="Eagle's Landing Golf Club - Fairway" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/eagles-landing-golf-club/3.jpeg" alt="Eagle's Landing Golf Club - Mountain View" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/eagles-landing-golf-club/4.jpeg" alt="Eagle's Landing Golf Club - Course View" style="width: 100%; height: 250px; object-fit: cover;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/courses/eagles-landing-golf-club" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Check%20out%20Eagle%27s%20Landing%20Golf%20Club&url=https://tennesseegolfcourses.com/courses/eagles-landing-golf-club" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode("Check out Eagle's Landing Golf Club"); ?>&body=<?php echo urlencode("I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/eagles-landing-golf-club"); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Eagle's Landing Golf Club - Complete Photo Gallery</h2>
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
                img.src = `../images/courses/eagles-landing-golf-club/${i}.jpeg`;
                img.alt = `Eagle's Landing Golf Club - Photo ${i}`;
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
