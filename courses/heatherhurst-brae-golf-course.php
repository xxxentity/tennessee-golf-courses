<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'heatherhurst-brae-golf-course';
$course_name = 'Heatherhurst Brae Golf Course';

$course_data = [
    'name' => 'Heatherhurst Brae Golf Course',
    'location' => 'Crossville, TN',
    'description' => '18-hole championship resort golf course in Fairfield Glade, Tennessee. Set at 2,000 feet on the Cumberland Plateau, Brae plays 6,499 yards from the blue tees with a par of 72. One of five courses within the Fairfield Glade community.',
    'image' => '/images/courses/heatherhurst-brae-golf-course/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'N/A',
    'year_built' => 'N/A',
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
            'streetAddress' => '421 Stonehenge Dr',
            'addressLocality' => 'Crossville',
            'addressRegion' => 'TN',
            'postalCode' => '38558',
            'addressCountry' => 'US'
        ],
        'telephone' => '+19314562864',
        'sport' => 'Golf',
        'numberOfHoles' => $course_data['holes'] ?? null,
    ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Hero -->
    <section style="height: 60vh; background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/heatherhurst-brae-golf-course/1.webp') center/cover; display: flex; align-items: center; justify-content: center; text-align: center; color: white; margin-top: 20px;">
        <div>
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Heatherhurst Brae Golf Course</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">Resort Course &bull; Crossville, Tennessee</p>
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
                            <span style="font-weight: 700; color: #2c5234;">6,499 (Blue)</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Course Rating</span>
                            <span style="font-weight: 700; color: #2c5234;">70.7</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Slope Rating</span>
                            <span style="font-weight: 700; color: #2c5234;">123</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Type</span>
                            <span style="font-weight: 700; color: #2c5234;">Resort</span>
                        </div>
                        <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Elevation</span>
                            <span style="font-weight: 700; color: #2c5234;">2,000 ft</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead>
                            <tr style="background: #f0f7f0;">
                                <th style="padding: 0.6rem 0.5rem; text-align: left; color: #2c5234; border-bottom: 2px solid #2c5234;">Round</th>
                                <th style="padding: 0.6rem 0.5rem; text-align: right; color: #2c5234; border-bottom: 2px solid #2c5234;">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">18-Hole (Guest w/ Member)</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$80</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">9-Hole (Guest w/ Member)</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$42</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">18-Hole After 2:00 pm</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$51</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">9-Hole After 4:00 pm</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$28</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">18-Hole (Unaccompanied)</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$90</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">9-Hole (Unaccompanied)</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$47</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6rem 0.5rem; color: #444;">18-Hole Unaccompanied After 2pm</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$51</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.85rem; color: #666; margin-top: 1rem;">Rates include cart. Contact pro shop for current pricing.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location &amp; Contact</h3>
                    <p><strong>Address:</strong><br>421 Stonehenge Dr<br>Crossville, TN 38558</p>
                    <p><strong>Phone:</strong><br><a href="tel:+19314562864" style="color: #4a7c59;">(931) 456-2864</a></p>
                    <p><strong>Website:</strong><br><a href="https://www.fairfieldglade.com" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">fairfieldglade.com</a></p>
                    <iframe
                        src="https://maps.google.com/maps?q=421+Stonehenge+Dr+Crossville+TN+38558&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="180"
                        style="border:0; border-radius: 8px; margin-top: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Heatherhurst Brae Golf Course Location">
                    </iframe>
                    <div style="margin-top: 0.5rem; text-align: center;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=421+Stonehenge+Dr+Crossville+TN+38558"
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
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Heatherhurst Brae Golf Course</h3>
                    <p>Heatherhurst Brae Golf Course is an 18-hole championship resort course set within the Fairfield Glade community in Crossville, Tennessee. Perched at 2,000 feet on the Cumberland Plateau, Brae is one of five golf courses within the resort, which has long been nicknamed the "Golf Capital of Tennessee." The course plays to 6,499 yards from the blue tees with a par of 72, a course rating of 70.7, and a slope of 123.</p>

                    <br>

                    <p>Multiple tee options make the course accessible for golfers of all skill levels. The white tees measure 5,980 yards, the yellow tees 5,018 yards, and the red tees 4,667 yards, ensuring a comfortable and enjoyable round regardless of playing ability. The name "Brae" — a Scottish word for a hillside — reflects the course's elevated, rolling character on the plateau.</p>

                    <br>

                    <p>The design takes full advantage of the Cumberland Plateau's natural topography. Hardwood forests frame nearly every hole, and the terrain's natural contours create elevation changes that demand thoughtful club selection and precise shot-making. The cooler mountain climate — significantly milder than Nashville and Knoxville during summer — also makes Brae a popular warm-weather escape, with comfortable playing conditions well into summer months.</p>

                    <br>

                    <p>Fairfield Glade's five-course portfolio also includes Heatherhurst Crag, Stonehenge, Druid Hills, and Dorchester — each with its own character and challenge. Staying guests and resort members can access all five courses, making Fairfield Glade one of the Southeast's most complete golf resort experiences. The resort also features 12 tennis courts, 11 lakes, hiking trails, swimming pools, and multiple dining options on-site.</p>

                    <br>

                    <p>Located roughly midway between Knoxville and Nashville off I-40, Heatherhurst Brae is an easy day trip from either city and a natural anchor for multi-night golf getaways on the plateau. The pro shop offers full equipment rental, merchandise, and club storage for visiting golfers.</p>
                </div>

                <!-- Amenities -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Amenities</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Pro Shop</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-mountain" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Mountain Views</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-layer-group" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Multiple Tee Options</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-hotel" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Full Resort Access</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Dining Options</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-swimming-pool" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Swimming Pools</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-tree" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Wooded Terrain</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-car" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Cart Included in Fees</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div style="margin-bottom: 3rem;">
                <div class="section-header">
                    <h2>Course Gallery</h2>
                    <p>Experience the beauty of Heatherhurst Brae Golf Course</p>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                    <img src="../images/courses/heatherhurst-brae-golf-course/2.webp" alt="Heatherhurst Brae Golf Course Crossville, TN - fairway lined with hardwood forest on the Cumberland Plateau" style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/courses/heatherhurst-brae-golf-course/3.webp" alt="Heatherhurst Brae Golf Course Crossville, TN - putting green with mountain backdrop at Fairfield Glade resort" style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/courses/heatherhurst-brae-golf-course/4.webp" alt="Heatherhurst Brae Golf Course Crossville, TN - scenic par 3 hole with elevated tee box at 2,000 feet elevation" style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="text-align: center; margin-top: 2rem;">
                    <button onclick="openGallery()" style="background: #4a7c59; color: white; padding: 1rem 2rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#2c5234'" onmouseout="this.style.background='#4a7c59'">View Full Gallery (25 Photos)</button>
                </div>
            </div>

            <!-- Share This Course -->
            <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; margin-bottom: 3rem;">
                <h3 style="font-size: 1.3rem; color: #2c5234; margin-bottom: 1rem;">Share This Course</h3>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/heatherhurst-brae-golf-course'); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Heatherhurst Brae Golf Course in Crossville, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/heatherhurst-brae-golf-course'); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">&#x1D54F;</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Heatherhurst Brae Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/heatherhurst-brae-golf-course'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Heatherhurst Brae Golf Course &mdash; Full Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid"></div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="/script.js?v=5"></script>
    <script>
        const galleryImages = Array.from({length: 25}, (_, i) => ({
            src: `../images/courses/heatherhurst-brae-golf-course/${i + 1}.webp`,
            alt: `Heatherhurst Brae Golf Course Crossville TN - photo ${i + 1}`
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
