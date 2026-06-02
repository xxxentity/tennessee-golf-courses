<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'island-pointe-golf-club';
$course_name = 'Island Pointe Golf Club';

$course_data = [
    'name' => 'Island Pointe Golf Club',
    'location' => 'Kodak, TN',
    'description' => 'Experience Island Pointe Golf Club, an Arthur Hills masterpiece in Kodak. Championship links-style golf with French Broad River island holes and Smoky Mountain views.',
    'image' => '/images/courses/island-pointe-golf-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Arthur Hills',
    'year_built' => 1991,
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?php include '../includes/favicon.php'; ?>

    <!-- Google Analytics -->
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

    <!-- Course Hero -->
    <section style="height: 60vh; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/island-pointe-golf-club/1.jpeg') center/cover no-repeat; display: flex; align-items: center; justify-content: center; color: white; text-align: center; margin-top: 20px;">
        <div>
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">Island Pointe Golf Club</h1>
            <p style="font-size: 1.3rem; margin-bottom: 1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">Championship Links Golf on the French Broad River</p>
            <div style="display: flex; gap: 20px; justify-content: center;">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Kodak, TN</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Three Info Boxes -->
    <section style="padding: 4rem 0; background: #f8f9fa;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 3rem;">

                <!-- Course Information -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-golf-ball"></i> Course Information
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0;">
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Designer</span>
                            <span style="color: #666;">Arthur Hills</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Year Built</span>
                            <span style="color: #666;">1991</span>
                        </div>
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
                            <span style="color: #666;">7,001 yards</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Course Rating</span>
                            <span style="color: #666;">74.3</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Slope Rating</span>
                            <span style="color: #666;">146</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Fairways</span>
                            <span style="color: #666;">Zoysia</span>
                        </div>
                        <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Greens</span>
                            <span style="color: #666;">Mini-Verdi Bermuda</span>
                        </div>
                    </div>
                </div>

                <!-- Green Fees -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-dollar-sign"></i> Green Fees
                    </h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead>
                            <tr style="background: #2c5234; color: white;">
                                <th style="padding: 0.6rem 0.8rem; text-align: left;">Rate Type</th>
                                <th style="padding: 0.6rem 0.8rem; text-align: right;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem;">Weekday (w/ Cart)</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">~$45</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0; background: #f9f9f9;">
                                <td style="padding: 0.6rem 0.8rem;">Weekend (w/ Cart)</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">~$55</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem;">Twilight</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">Call for rates</td>
                            </tr>
                            <tr style="background: #f9f9f9;">
                                <td style="padding: 0.6rem 0.8rem;">Junior / Senior</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: right;">Call for rates</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.85rem; color: #888; margin-top: 1rem;">Rates are approximate. Call for current pricing and tee times.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-map-marker-alt"></i> Location & Contact
                    </h3>
                    <p><strong>Address:</strong><br>9610 Kodak Road<br>Kodak, TN 37764</p>
                    <p><strong>Phone:</strong><br><a href="tel:+18659334653" style="color: #4a7c59;">(865) 933-4653</a></p>
                    <iframe
                        src="https://maps.google.com/maps?q=9610+Kodak+Road,+Kodak,+TN+37764&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="180" style="border:0; border-radius: 8px; margin-top: 1rem;" loading="lazy">
                    </iframe>
                    <a href="https://maps.google.com/maps?q=9610+Kodak+Road,+Kodak,+TN+37764" target="_blank"
                       style="display: inline-block; margin-top: 0.75rem; color: #4a7c59; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>

            </div>

            <!-- About + Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Island Pointe Golf Club</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">Island Pointe Golf Club, formerly known as River Islands Golf Club, is an Arthur Hills design that opened in 1991 on the banks of the French Broad River between Knoxville and the Great Smoky Mountains. Spread across more than 175 acres, the course delivers the most demanding public golf experience in East Tennessee — a true links-style layout that earned a slope rating of 146 from the championship tees at 7,001 yards.</p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">The course's defining feature is its three island holes that literally play on islands in the middle of the French Broad River, requiring precise carry shots over moving water and demanding course management from every level of golfer. These holes make Island Pointe unlike anything else in the region and have established its reputation as one of Tennessee's most memorable public rounds. Arthur Hills used the river's natural contours throughout the layout, with eight additional holes running along the river's edge to create a constant visual and strategic connection to the water.</p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">Zoysia fairways — widely regarded as the finest in East Tennessee — give the course a firm, clean playing surface even after rain, while the Mini-Verdi Bermuda greens provide smooth, consistent putting. The course is threaded by more than seven and a half miles of cart paths, and a newly remodeled bar and grill rounds out the on-site experience. With the Smokies as a backdrop and the river as a constant companion, Island Pointe combines championship design with scenery that few public courses in the state can match.</p>
                </div>
                <div>
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Amenities</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-golf-ball" style="color: #4a7c59;"></i>
                            <span>18-Hole Championship Course</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-water" style="color: #4a7c59;"></i>
                            <span>3 French Broad River Island Holes</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-graduation-cap" style="color: #4a7c59;"></i>
                            <span>Extensive Practice Facilities</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-chalkboard-teacher" style="color: #4a7c59;"></i>
                            <span>PGA Professional Instruction</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-utensils" style="color: #4a7c59;"></i>
                            <span>Bar & Grill</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-road" style="color: #4a7c59;"></i>
                            <span>7.5+ Miles of Cart Paths</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-mountain" style="color: #4a7c59;"></i>
                            <span>Smoky Mountain Views</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-calendar-check" style="color: #4a7c59;"></i>
                            <span>Tournament & Event Hosting</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section style="padding: 4rem 0; background: white;">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of Island Pointe Golf Club</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 2rem;">
                <div style="height: 250px; border-radius: 15px; overflow: hidden; cursor: pointer;" onclick="openGallery()">
                    <img src="../images/courses/island-pointe-golf-club/2.jpeg" alt="Island Pointe Golf Club - French Broad River island hole" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="height: 250px; border-radius: 15px; overflow: hidden; cursor: pointer;" onclick="openGallery()">
                    <img src="../images/courses/island-pointe-golf-club/3.jpeg" alt="Island Pointe Golf Club - Championship links-style fairway" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="height: 250px; border-radius: 15px; overflow: hidden; cursor: pointer;" onclick="openGallery()">
                    <img src="../images/courses/island-pointe-golf-club/4.jpeg" alt="Island Pointe Golf Club - Smoky Mountain backdrop" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
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
            <div style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center;">
                <h3 style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Course</h3>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/island-pointe-golf-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Island Pointe Golf Club in Kodak, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/island-pointe-golf-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Island Pointe Golf Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/island-pointe-golf-club'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Full Gallery Modal -->
    <div id="galleryModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9);">
        <div style="margin: 2% auto; padding: 20px; width: 90%; max-width: 1200px; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; color: white;">
                <h2 style="margin: 0; font-size: 2rem;">Island Pointe Golf Club — Full Gallery</h2>
                <button onclick="closeGallery()" style="color: white; font-size: 3rem; font-weight: bold; cursor: pointer; background: none; border: none; line-height: 1;">&times;</button>
            </div>
            <div id="fullGalleryGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; max-height: 70vh; overflow-y: auto;">
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="../script.js?v=5"></script>
    <script>
        function openGallery() {
            const modal = document.getElementById('galleryModal');
            const grid = document.getElementById('fullGalleryGrid');
            grid.innerHTML = '';
            const galleryImages = Array.from({length: 25}, (_, i) => ({
                src: `../images/courses/island-pointe-golf-club/${i + 1}.jpeg`,
                alt: `Island Pointe Golf Club - Photo ${i + 1}`
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
