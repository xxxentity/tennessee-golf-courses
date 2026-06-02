<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'hillwood-country-club';
$course_name = 'Hillwood Country Club';

$course_data = [
    'name' => 'Hillwood Country Club',
    'location' => 'Nashville, TN',
    'description' => 'Dick Wilson–designed private championship golf course in Nashville, Tennessee. Established in 1957, Hillwood plays 7,059 yards from the championship tees with a course rating of 74.2 and slope of 140.',
    'image' => '/images/courses/hillwood-country-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Dick Wilson',
    'year_built' => 1957,
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
            aspect-ratio: 4/3;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        .full-gallery-item:hover { transform: scale(1.05); }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
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
            'streetAddress' => '6201 Hickory Valley Road',
            'addressLocality' => 'Nashville',
            'addressRegion' => 'TN',
            'postalCode' => '37205',
            'addressCountry' => 'US'
        ],
        'telephone' => '+16153526591',
        'sport' => 'Golf',
        'numberOfHoles' => $course_data['holes'] ?? null,
    ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Hero -->
    <section style="height: 60vh; background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/hillwood-country-club/1.jpeg') center/cover; display: flex; align-items: center; justify-content: center; text-align: center; color: white; margin-top: 20px;">
        <div>
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Hillwood Country Club</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">Private Club &bull; Nashville, Tennessee</p>
        </div>
    </section>

    <!-- Course Details -->
    <section style="padding: 4rem 0;">
        <div class="container">

            <!-- Three Boxes -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 3rem;">

                <!-- Course Information -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div style="display: flex; flex-direction: column; gap: 0;">
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Holes</span>
                            <span style="font-weight: 700; color: #2c5234;">18</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Par</span>
                            <span style="font-weight: 700; color: #2c5234;">72</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Yardage</span>
                            <span style="font-weight: 700; color: #2c5234;">7,059</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Course Rating</span>
                            <span style="font-weight: 700; color: #2c5234;">74.2</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Slope Rating</span>
                            <span style="font-weight: 700; color: #2c5234;">140</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Designer</span>
                            <span style="font-weight: 700; color: #2c5234;">Dick Wilson</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Established</span>
                            <span style="font-weight: 700; color: #2c5234;">1957</span>
                        </div>
                        <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Type</span>
                            <span style="font-weight: 700; color: #2c5234;">Private</span>
                        </div>
                    </div>
                </div>

                <!-- Membership -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-users"></i> Membership</h3>
                    <div style="background: linear-gradient(135deg, #2c5234, #4a7c59); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; margin: 1rem 0;">
                        <h4 style="margin-bottom: 0.5rem; font-size: 1.2rem;">Private Members Only</h4>
                        <p style="margin: 0; opacity: 0.9;">Membership required for play</p>
                    </div>
                    <p style="text-align: center; color: #666; margin-top: 1rem; font-size: 0.95rem;">
                        Hillwood Country Club is an exclusive private club serving Nashville families since 1957. Contact the club directly for membership information and guest policies.
                    </p>
                    <p style="text-align: center; margin-top: 1rem;">
                        <a href="tel:+16153526591" style="color: #4a7c59; font-weight: 600;">(615) 352-6591</a>
                    </p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location &amp; Contact</h3>
                    <p><strong>Address:</strong><br>6201 Hickory Valley Road<br>Nashville, TN 37205</p>
                    <p><strong>Phone:</strong><br><a href="tel:+16153526591" style="color: #4a7c59;">(615) 352-6591</a></p>
                    <p><strong>Website:</strong><br><a href="https://www.hillwoodcc.org" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">hillwoodcc.org</a></p>
                    <iframe
                        src="https://maps.google.com/maps?q=6201+Hickory+Valley+Road+Nashville+TN+37205&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="180"
                        style="border:0; border-radius: 8px; margin-top: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Hillwood Country Club Location">
                    </iframe>
                    <div style="margin-top: 0.5rem; text-align: center;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=6201+Hickory+Valley+Road+Nashville+TN+37205"
                           target="_blank"
                           rel="noopener noreferrer"
                           style="font-size: 0.85rem; color: #4a7c59; text-decoration: none; font-weight: 500;">
                            <i class="fas fa-directions"></i> Get Directions
                        </a>
                    </div>
                </div>
            </div>

            <!-- About + Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; margin-bottom: 3rem;">

                <!-- About -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Hillwood Country Club</h3>
                    <p>Established in 1957, Hillwood Country Club is one of Nashville's most prestigious private clubs, anchored by a championship golf course designed by renowned architect Dick Wilson. Located on Hickory Valley Road in west Nashville, the club has served as a cornerstone of Nashville society and competitive golf for nearly seven decades.</p>

                    <br>

                    <p>Wilson's layout stretches 7,059 yards from the championship tees with a course rating of 74.2 and a slope of 140 — among the more demanding ratings in Middle Tennessee. Mach 1 Ultradwarf Bermuda greens provide fast, consistent surfaces year-round. The course underwent significant renovations in 2003 and again in 2011, both carried out by Bruce Hepner of Renaissance Golf Design, who modernized the bunkering and green complexes while preserving Wilson's core design intent.</p>

                    <br>

                    <p>Dick Wilson is widely regarded as one of the most talented course architects of the mid-20th century, responsible for iconic designs including Doral's Blue Monster, Bay Hill, and Cog Hill's Dubsdread. His Tennessee portfolio is small, making Hillwood a particularly notable example of his work in the region. Wilson's trademark characteristics — strategic bunkering, rolling fairways, and firm, true greens — are evident throughout the layout.</p>

                    <br>

                    <p>Beyond golf, Hillwood is a full-amenity country club with a 22,000-square-foot fitness center, competition swimming pools, multiple tennis courts, four dining venues, and spa services. The club hosts junior golf programs, men's and women's golf associations, and a full calendar of member events throughout the year. As a private facility, play is restricted to members and their guests.</p>
                </div>

                <!-- Amenities -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Amenities</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Championship Golf</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-tennis-ball" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Tennis Complex</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Four Dining Venues</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-dumbbell" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>22,000 sq ft Fitness</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-swimmer" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Competition Pools</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-spa" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Spa Services</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-shopping-cart" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Pro Shop</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-calendar-alt" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Event Hosting</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div style="margin-bottom: 3rem;">
                <div class="section-header">
                    <h2>Course Gallery</h2>
                    <p>Experience the beauty of Hillwood Country Club</p>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                    <img src="../images/courses/hillwood-country-club/1.jpeg" alt="Hillwood Country Club Nashville TN - Dick Wilson championship fairway design from 1957" style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/courses/hillwood-country-club/2.jpeg" alt="Hillwood Country Club Nashville TN - Mach 1 Ultradwarf Bermuda putting green with slope rating 140" style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/courses/hillwood-country-club/3.jpeg" alt="Hillwood Country Club Nashville TN - Bruce Hepner renovation bunkering on Dick Wilson layout" style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="text-align: center; margin-top: 2rem;">
                    <button onclick="openGallery()" style="background: #4a7c59; color: white; padding: 1rem 2rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#2c5234'" onmouseout="this.style.background='#4a7c59'">View Full Gallery (25 Photos)</button>
                </div>
            </div>

            <!-- Share This Course -->
            <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; margin-bottom: 3rem;">
                <h3 style="font-size: 1.3rem; color: #2c5234; margin-bottom: 1rem;">Share This Course</h3>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/hillwood-country-club'); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Hillwood Country Club in Nashville, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/hillwood-country-club'); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">&#x1D54F;</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Hillwood Country Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/hillwood-country-club'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Hillwood Country Club &mdash; Full Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid"></div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="/script.js?v=5"></script>
    <script>
        const galleryImages = Array.from({length: 25}, (_, i) => ({
            src: `../images/courses/hillwood-country-club/${i + 1}.jpeg`,
            alt: `Hillwood Country Club Nashville TN - photo ${i + 1}`
        }));

        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const grid = document.getElementById('fullGalleryGrid');
            grid.innerHTML = '';
            galleryImages.forEach(img => {
                const item = document.createElement('div');
                item.className = 'full-gallery-item';
                item.innerHTML = `<img src="${img.src}" alt="${img.alt}" loading="lazy" style="width:100%;height:100%;object-fit:cover;">`;
                item.onclick = () => window.open(img.src, '_blank');
                grid.appendChild(item);
            });
            modal.style.display = 'block';
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
