<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'lake-tansi-golf-course';
$course_name = 'Lake Tansi Golf Course';

$course_data = [
    'name' => 'Lake Tansi Golf Course',
    'location' => 'Crossville, TN',
    'description' => 'Robert Renaud designed championship course in Crossville, TN. Ranked 9th in Tennessee by Golf Advisor with 6 tee options on 18 holes.',
    'image' => '/images/courses/lake-tansi-golf-course/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Robert Renaud',
    'year_built' => 1961,
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
    <section style="height: 60vh; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/lake-tansi-golf-course/1.jpeg') center/cover no-repeat; display: flex; align-items: center; justify-content: center; color: white; text-align: center; margin-top: 20px;">
        <div>
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">Lake Tansi Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">Robert Renaud Design • Crossville, Tennessee</p>
            <div style="display: flex; gap: 20px; justify-content: center;">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Crossville, TN</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Three Boxes + About/Amenities -->
    <section style="padding: 4rem 0; background: #f8f9fa;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 3rem;">

                <!-- Course Information -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.4rem;"><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Holes</span>
                        <span style="color: #666;">18</span>
                    </div>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Par</span>
                        <span style="color: #666;">72</span>
                    </div>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Yardage</span>
                        <span style="color: #666;">6,701</span>
                    </div>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Course Rating</span>
                        <span style="color: #666;">72.2</span>
                    </div>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Slope Rating</span>
                        <span style="color: #666;">134</span>
                    </div>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Designer</span>
                        <span style="color: #666;">Robert Renaud</span>
                    </div>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Opened</span>
                        <span style="color: #666;">1961</span>
                    </div>
                    <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Type</span>
                        <span style="color: #666;">Public</span>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.4rem;"><i class="fas fa-dollar-sign"></i> Green Fees</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead>
                            <tr style="background: #2c5234; color: white;">
                                <th style="padding: 0.6rem 0.8rem; text-align: left;">Rate Type</th>
                                <th style="padding: 0.6rem 0.8rem; text-align: right;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem;">Summer 18 Holes</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">$25–$65</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0; background: #f9f9f9;">
                                <td style="padding: 0.6rem 0.8rem;">Summer 9 Holes</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">$25–$45</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem;">Winter 18 Holes</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">$37–$39</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0; background: #f9f9f9;">
                                <td style="padding: 0.6rem 0.8rem;">Winter 9 Holes</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">$27–$29</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem;">Cart (18 Holes)</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">$22</td>
                            </tr>
                            <tr style="background: #f9f9f9;">
                                <td style="padding: 0.6rem 0.8rem;">Cart (9 Holes)</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">$15</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="margin-top: 1rem; font-size: 0.85rem; color: #666; text-align: center;">Annual memberships and Frequent Players Cards available. Call (931) 788-3301 or (800) 600-9913 for packages.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.4rem;"><i class="fas fa-map-marker-alt"></i> Location & Contact</h3>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Address</span>
                        <span style="color: #666;">2476 Dunbar Road</span>
                    </div>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">City</span>
                        <span style="color: #666;">Crossville, TN 38572</span>
                    </div>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Phone</span>
                        <span style="color: #666;">(931) 788-3301</span>
                    </div>
                    <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                        <span style="font-weight: 600; color: #2c5234;">Website</span>
                        <span style="color: #666;"><a href="https://www.laketansigolf.com" target="_blank" style="color: #4a7c59;">Visit Site</a></span>
                    </div>
                    <iframe
                        src="https://maps.google.com/maps?q=2476+Dunbar+Road,+Crossville,+TN+38572&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="180"
                        style="border:0; border-radius: 8px; margin-top: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lake Tansi Golf Course Location">
                    </iframe>
                    <div style="text-align: center; margin-top: 0.5rem;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=2476+Dunbar+Road,+Crossville,+TN+38572" target="_blank" rel="noopener noreferrer" style="font-size: 0.85rem; color: #4a7c59; text-decoration: none; font-weight: 500;">
                            <i class="fas fa-directions"></i> Get Directions
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2fr/1fr: About + Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.4rem;"><i class="fas fa-golf-ball"></i> About Lake Tansi Golf Course</h3>
                    <p>Lake Tansi Golf Course is one of Tennessee's most acclaimed public layouts, opening in 1961 within the Lake Tansi Village resort community on the Cumberland Plateau outside Crossville. Designed by Robert Renaud, the 18-hole, par-72 championship course stretches 6,701 yards from the championship blue tees, earning a 72.2 course rating and 134 slope — placing it among the top courses in the state on Golf Advisor's rankings.</p>
                    <br>
                    <p>Set at an elevation that delivers cooler summers and sweeping mountain views, the course winds through the scenic terrain of Cumberland County with a design that rewards strategic play. Six tee options accommodate golfers of every level, ranging from the championship blue tees at 6,701 yards down to the beginner pink tees at 4,242 yards, with white (6,205 / 70.1 / 125), green (5,758 / 67.9 / 120), gold (5,364 / 66.5 / 112), and red (4,579 / 66.7 / 113) sets in between.</p>
                    <br>
                    <p>Seasonal green fees make Lake Tansi one of the most accessible championship courses in the region — summer public rates run $25–$65 for 18 holes, while winter rates hold at $37–$39. Annual memberships and Frequent Players Cards offer year-round discounted access. Cart fees run $22 for 18 holes or $15 for 9. The facility features a full-service pro shop, driving range, putting green, and professional instruction, with tee times available online or by calling (931) 788-3301.</p>
                </div>
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.4rem;"><i class="fas fa-star"></i> Amenities</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-golf-ball" style="color: #4a7c59;"></i>
                            <span>Championship 18 Holes</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-store" style="color: #4a7c59;"></i>
                            <span>Pro Shop</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-bullseye" style="color: #4a7c59;"></i>
                            <span>Driving Range</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-circle" style="color: #4a7c59;"></i>
                            <span>Putting Green</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-graduation-cap" style="color: #4a7c59;"></i>
                            <span>Golf Lessons</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-calendar-alt" style="color: #4a7c59;"></i>
                            <span>Online Tee Times</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-mountain" style="color: #4a7c59;"></i>
                            <span>Mountain Views</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-trophy" style="color: #4a7c59;"></i>
                            <span>Tournament Hosting</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-calendar" style="color: #4a7c59;"></i>
                            <span>Year-Round Play</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section style="padding: 4rem 0;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="font-size: 2rem; color: #2c5234;">Course Gallery</h2>
                <p style="color: #666;">Experience the beauty of Lake Tansi Golf Course</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <div style="height: 250px; border-radius: 15px; overflow: hidden; cursor: pointer;" onclick="openGallery()">
                    <img src="../images/courses/lake-tansi-golf-course/1.jpeg" alt="Lake Tansi Golf Course - Crossville, TN" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="height: 250px; border-radius: 15px; overflow: hidden; cursor: pointer;" onclick="openGallery()">
                    <img src="../images/courses/lake-tansi-golf-course/2.jpeg" alt="Lake Tansi Golf Course - Fairway View" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="height: 250px; border-radius: 15px; overflow: hidden; cursor: pointer;" onclick="openGallery()">
                    <img src="../images/courses/lake-tansi-golf-course/3.jpeg" alt="Lake Tansi Golf Course - Championship Layout" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <button onclick="openGallery()" style="background: #4a7c59; color: white; padding: 1rem 2rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; font-size: 1rem;">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>

    <!-- Share This Course -->
    <section style="padding: 3rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <h3 style="font-size: 1.3rem; color: #333; margin-bottom: 1rem;">Share This Course</h3>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/lake-tansi-golf-course'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Lake Tansi Golf Course in Crossville, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/lake-tansi-golf-course'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Lake Tansi Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/lake-tansi-golf-course'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div id="galleryModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9);">
        <div style="margin: 2% auto; padding: 20px; width: 90%; max-width: 1200px; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; color: white;">
                <h2 style="margin: 0; font-size: 2rem;">Lake Tansi Golf Course - Complete Photo Gallery</h2>
                <button onclick="closeGallery()" style="color: white; font-size: 3rem; font-weight: bold; cursor: pointer; background: none; border: none;">&times;</button>
            </div>
            <div id="fullGalleryGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; max-height: 70vh; overflow-y: auto;"></div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const grid = document.getElementById('fullGalleryGrid');
            grid.innerHTML = '';
            const galleryImages = Array.from({length: 25}, (_, i) => ({
                src: `../images/courses/lake-tansi-golf-course/${i + 1}.jpeg`,
                alt: `Lake Tansi Golf Course - Photo ${i + 1}`
            }));
            galleryImages.forEach(img => {
                const item = document.createElement('div');
                item.style.cssText = 'aspect-ratio: 4/3; border-radius: 10px; overflow: hidden; cursor: pointer;';
                item.innerHTML = `<img src="${img.src}" alt="${img.alt}" loading="lazy" style="width:100%;height:100%;object-fit:cover;" onclick="window.open('${img.src}','_blank')">`;
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
