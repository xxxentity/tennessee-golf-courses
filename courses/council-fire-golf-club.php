<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'council-fire-golf-club';
$course_name = 'Council Fire Golf Club';

$course_data = [
    'name' => 'Council Fire Golf Club',
    'location' => 'Chattanooga, TN',
    'description' => 'Bob Cupp designed championship golf course in Chattanooga, TN. Private club featuring 7,186 yards of premier golf with tournament pedigree.',
    'image' => '/images/courses/council-fire-golf-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Bob Cupp',
    'year_built' => 1992,
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
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/council-fire-golf-club/1.webp');
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Council Fire Golf Club</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">Bob Cupp Design &bull; Chattanooga, Tennessee</p>
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
                            <span style="font-weight: 700; color: #2c5234;">7,186 yards</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Course Rating:</span>
                            <span style="font-weight: 700; color: #2c5234;">74.7</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Slope Rating:</span>
                            <span style="font-weight: 700; color: #2c5234;">142</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Designer:</span>
                            <span style="font-weight: 700; color: #2c5234;">Bob Cupp</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-weight: 600; color: #666;">Opened:</span>
                            <span style="font-weight: 700; color: #2c5234;">1992</span>
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
                        Council Fire Golf Club operates as a private facility serving members and their guests.
                        Contact the club directly for membership information and guest policies.
                    </p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location &amp; Contact</h3>
                    <p><strong>Address:</strong><br>
                    100 Council Fire Dr<br>
                    Chattanooga, TN 37421</p>

                    <p style="margin-top: 0.75rem;"><strong>Phone:</strong><br>
                    <a href="tel:4238947888" style="color: #4a7c59; text-decoration: none;">(423) 894-7888</a></p>

                    <p style="margin-top: 0.75rem;"><strong>Website:</strong><br>
                    <a href="https://www.councilfireclub.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">councilfireclub.com</a></p>

                    <iframe
                        src="https://maps.google.com/maps?q=100+Council+Fire+Dr,+Chattanooga,+TN+37421&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="200"
                        style="border:0; border-radius: 8px; margin-top: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Council Fire Golf Club Location">
                    </iframe>
                    <div style="margin-top: 0.5rem; text-align: center;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=100+Council+Fire+Dr,+Chattanooga,+TN+37421"
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
                <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Council Fire Golf Club</h3>

                <p>Council Fire Golf Club stands as one of Chattanooga's premier private golf destinations, featuring a championship Bob Cupp design that has challenged golfers since 1992. Set on 114 pristine acres with dramatic mountain range backdrops, the course weaves through natural terrain enhanced by lakes, streams, and indigenous foliage that creates a spectacular golfing experience in the heart of Tennessee.</p>

                <br>

                <p>The layout stretches to 7,186 yards from the tips with a par of 72, course rating of 74.7, and slope of 142 — reflecting its true championship caliber. Multiple tee options accommodate players of all skill levels, and premium Bermuda grass playing surfaces are maintained to tournament standards throughout the season.</p>

                <br>

                <p>The club's tournament pedigree is exceptional, having hosted PGA Tour and Senior Tour events, NCAA Championships, and numerous USGA and State championships. This history speaks to the course's meticulous conditioning and challenging yet fair design that tests every aspect of a golfer's game while remaining playable for members and guests.</p>

                <br>

                <p>Council Fire operates as "a home away from home" for all members and their guests. The club combines world-class golf with comprehensive recreational facilities including tennis, swimming, and fine dining — a complete country club experience in one of Chattanooga's most beautiful natural settings.</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Championship Golf</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-trophy" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Tournament Host</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-tennis-ball" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Tennis Courts</span>
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
                        <i class="fas fa-users" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Social Areas</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-mountain" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Mountain Views</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; width: 100%;">
                        <i class="fas fa-water" style="color: #4a7c59; font-size: 1.2rem;"></i>
                        <span>Water Features</span>
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
                <p style="color: #666; font-size: 1.1rem;">Experience the beauty of Council Fire Golf Club</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/courses/council-fire-golf-club/1.webp" alt="Council Fire Golf Club Chattanooga TN - Championship 18-hole golf course" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/council-fire-golf-club/2.webp" alt="Council Fire Golf Club - Fairway view with strategic bunkers and mountain backdrop" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="gallery-item">
                    <img src="../images/courses/council-fire-golf-club/3.webp" alt="Council Fire Golf Club Tennessee - Championship course layout and natural terrain" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/council-fire-golf-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Council Fire Golf Club'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/council-fire-golf-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">&#x1D54F;</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Council Fire Golf Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/council-fire-golf-club'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Council Fire Golf Club &mdash; Complete Photo Gallery</h2>
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
                item.style.backgroundImage = `url('../images/courses/council-fire-golf-club/${i}.webp')`;
                item.onclick = () => window.open(`../images/courses/council-fire-golf-club/${i}.webp`, '_blank');
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
