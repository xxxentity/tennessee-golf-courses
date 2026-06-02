<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'henry-horton-state-park-golf-course';
$course_name = 'Henry Horton State Park Golf Course';

$course_data = [
    'name' => 'Henry Horton State Park Golf Course',
    'location' => 'Chapel Hill, TN',
    'description' => 'Historic 18-hole championship golf course in Chapel Hill, Tennessee. The first course on the Tennessee State Park Golf Trail, playing 7,066 yards from the championship tees with a par of 72 and a course rating of 74.0/slope 129.',
    'image' => '/images/courses/henry-horton-state-park-golf-course/1.webp',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Bob Cupp',
    'year_built' => 1962,
    'course_type' => 'State Park'
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
            'streetAddress' => '4358 Nashville Highway',
            'addressLocality' => 'Chapel Hill',
            'addressRegion' => 'TN',
            'postalCode' => '37034',
            'addressCountry' => 'US'
        ],
        'telephone' => '+19313642319',
        'sport' => 'Golf',
        'numberOfHoles' => $course_data['holes'] ?? null,
    ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Hero -->
    <section style="height: 60vh; background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/henry-horton-state-park-golf-course/1.webp') center/cover; display: flex; align-items: center; justify-content: center; text-align: center; color: white; margin-top: 20px;">
        <div>
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Henry Horton State Park Golf Course</h1>
            <p style="font-size: 1.3rem; opacity: 0.9;">State Park Course &bull; Chapel Hill, Tennessee</p>
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
                            <span style="font-weight: 700; color: #2c5234;">7,066 (Black)</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Course Rating</span>
                            <span style="font-weight: 700; color: #2c5234;">74.0</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Slope Rating</span>
                            <span style="font-weight: 700; color: #2c5234;">129</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Designer</span>
                            <span style="font-weight: 700; color: #2c5234;">Bob Cupp</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Opened</span>
                            <span style="font-weight: 700; color: #2c5234;">1962</span>
                        </div>
                        <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #666;">Type</span>
                            <span style="font-weight: 700; color: #2c5234;">State Park</span>
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
                            <tr>
                                <td colspan="2" style="padding: 0.4rem 0.5rem; font-size: 0.8rem; font-weight: 700; color: #2c5234; background: #f8fdf9;">In Season (Apr &ndash; Oct)</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">Weekday 18-Hole w/ Cart</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$44 + tax</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">Weekend 18-Hole w/ Cart</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$50 + tax</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">Senior (62+) w/ Cart</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$39 + tax</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 0.4rem 0.5rem; font-size: 0.8rem; font-weight: 700; color: #2c5234; background: #f8fdf9;">Off Season (Nov &ndash; Mar)</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">Standard w/ Cart</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$39 + tax</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.5rem; color: #444;">Senior w/ Cart</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$34 + tax</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6rem 0.5rem; color: #444;">Annual Pass</td>
                                <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #2c5234;">$1,200</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.85rem; color: #666; margin-top: 1rem;">Walking available. Call (931) 364-2319 for current rates.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Location &amp; Contact</h3>
                    <p><strong>Address:</strong><br>4358 Nashville Highway<br>Chapel Hill, TN 37034</p>
                    <p><strong>Phone:</strong><br><a href="tel:+19313642319" style="color: #4a7c59;">(931) 364-2319</a></p>
                    <p><strong>Director of Golf:</strong><br>Neil Collins, PGA</p>
                    <p><strong>Website:</strong><br><a href="https://tnstateparks.com/golf/course/henry-horton/" target="_blank" rel="noopener noreferrer" style="color: #4a7c59;">tnstateparks.com</a></p>
                    <iframe
                        src="https://maps.google.com/maps?q=4358+Nashville+Highway+Chapel+Hill+TN+37034&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="180"
                        style="border:0; border-radius: 8px; margin-top: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Henry Horton State Park Golf Course Location">
                    </iframe>
                    <div style="margin-top: 0.5rem; text-align: center;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=4358+Nashville+Highway+Chapel+Hill+TN+37034"
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
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-golf-ball"></i> About Henry Horton State Park Golf Course</h3>
                    <p>Henry Horton State Park Golf Course holds a distinguished place in Tennessee golf history as the first course on the Tennessee State Park Golf Trail. Opened in 1962 and later redesigned by Bob Cupp, this championship layout known as the Buford Ellington Course has challenged golfers for more than six decades in the rolling countryside of Chapel Hill, Tennessee — roughly 50 miles south of Nashville in Marshall County.</p>

                    <br>

                    <p>From the championship black tees, the course stretches to 7,066 yards with a course rating of 74.0 and a slope of 129, making it one of the more demanding tests on the state park trail. Six tee options allow golfers of all abilities to enjoy the layout, with the forward red tees measuring 5,470 yards. The heavily wooded design winds through mature hardwoods, with generous fairways and larger-than-average greens that reward strategic play over raw power.</p>

                    <br>

                    <p>The course sits within Henry Horton State Park, a 1,120-acre facility on the Duck River named for Tennessee's 36th governor. Managed by the Tennessee Department of Environment and Conservation, the park offers lodging, a restaurant, conference facilities, hiking trails, and river access alongside the golf course — making it a popular destination for overnight golf getaways and corporate outings. PGA Director of Golf Neil Collins oversees golf operations and instruction.</p>

                    <br>

                    <p>Green fees are priced in line with the state park system's commitment to accessible public golf. Cart is included in the in-season rate, walking is available in the off-season, and an annual pass covers unlimited play for frequent visitors. Senior discounts are available for players 62 and older. Tee times can be booked by phone or through the Tennessee State Parks golf reservation system.</p>

                    <br>

                    <p>As the founding course of the Tennessee Golf Trail, Henry Horton carries a significance beyond its scorecard. The trail has grown to include multiple state park courses across Tennessee, and Henry Horton remains its flagship — a course that reflects what public golf in a state park setting can be at its best. It's a must-play for anyone making their way through Middle Tennessee's golf landscape.</p>
                </div>

                <!-- Amenities -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1rem; font-size: 1.5rem;"><i class="fas fa-star"></i> Amenities</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-golf-ball" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Driving Range</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-shopping-cart" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Pro Shop</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-user-tie" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>PGA Professional</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-utensils" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Restaurant &amp; Grill</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-bed" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>On-Site Lodging</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-trophy" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Tournament Hosting</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-hiking" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>Hiking Trails Nearby</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-award" style="color: #4a7c59; font-size: 1.1rem;"></i>
                            <span>TN Golf Trail Founder</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div style="margin-bottom: 3rem;">
                <div class="section-header">
                    <h2>Course Gallery</h2>
                    <p>Experience the historic beauty of Henry Horton State Park Golf Course</p>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                    <img src="../images/courses/henry-horton-state-park-golf-course/2.webp" alt="Henry Horton State Park Golf Course Chapel Hill TN - mature hardwood lined fairway on the Buford Ellington Championship Course" style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/courses/henry-horton-state-park-golf-course/3.webp" alt="Henry Horton State Park Golf Course Chapel Hill TN - championship layout from the black tees at 7,066 yards" style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/courses/henry-horton-state-park-golf-course/4.webp" alt="Henry Horton State Park Golf Course Chapel Hill TN - scenic rolling terrain of the first Tennessee State Park Golf Trail course" style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px; cursor: pointer; transition: transform 0.3s ease;" onclick="openGallery()" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="text-align: center; margin-top: 2rem;">
                    <button onclick="openGallery()" style="background: #4a7c59; color: white; padding: 1rem 2rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#2c5234'" onmouseout="this.style.background='#4a7c59'">View Full Gallery (25 Photos)</button>
                </div>
            </div>

            <!-- Share This Course -->
            <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; margin-bottom: 3rem;">
                <h3 style="font-size: 1.3rem; color: #2c5234; margin-bottom: 1rem;">Share This Course</h3>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/henry-horton-state-park-golf-course'); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Henry Horton State Park Golf Course in Chapel Hill, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/henry-horton-state-park-golf-course'); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">&#x1D54F;</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Henry Horton State Park Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/henry-horton-state-park-golf-course'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Henry Horton State Park Golf Course &mdash; Full Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid"></div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="/script.js?v=5"></script>
    <script>
        const galleryImages = Array.from({length: 25}, (_, i) => ({
            src: `../images/courses/henry-horton-state-park-golf-course/${i + 1}.webp`,
            alt: `Henry Horton State Park Golf Course Chapel Hill TN - photo ${i + 1}`
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
