<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'jackson-country-club';
$course_name = 'Jackson Country Club';

$course_data = [
    'name' => 'Jackson Country Club',
    'location' => 'Jackson, TN',
    'description' => 'Historic private club in Jackson, TN since 1914. Championship golf with 6,849 yards, host to TGA championships and USGA qualifiers.',
    'image' => '/images/courses/jackson-country-club/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'Hugh H. Miller',
    'year_built' => 1914,
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
    <section style="height: 60vh; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/jackson-country-club/1.webp') center/cover no-repeat; display: flex; align-items: center; justify-content: center; color: white; text-align: center; margin-top: 20px;">
        <div>
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">Jackson Country Club</h1>
            <p style="font-size: 1.3rem; margin-bottom: 1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">The Hub of Hub City &bull; Jackson, Tennessee</p>
            <div style="display: flex; gap: 20px; justify-content: center;">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Jackson, TN</span>
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
                            <span style="color: #666;">Hugh H. Miller</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Established</span>
                            <span style="color: #666;">1914</span>
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
                            <span style="color: #666;">6,849 yards</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Course Rating</span>
                            <span style="color: #666;">73.4</span>
                        </div>
                        <div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Slope Rating</span>
                            <span style="color: #666;">134</span>
                        </div>
                        <div style="padding: 0.6rem 0; display: flex; justify-content: space-between;">
                            <span style="font-weight: 600; color: #2c5234;">Greens</span>
                            <span style="color: #666;">Champion Bermuda</span>
                        </div>
                    </div>
                </div>

                <!-- Membership -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-users"></i> Membership
                    </h3>
                    <div style="background: linear-gradient(135deg, #2c5234, #4a7c59); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; margin-bottom: 1rem;">
                        <h4 style="margin-bottom: 0.5rem; font-size: 1.2rem;">Private Members Only</h4>
                        <p style="margin: 0; opacity: 0.9;">Exclusive club membership required</p>
                    </div>
                    <p style="color: #555; line-height: 1.6; font-size: 0.95rem;">Founded in 1914 to foster social pleasure and the enjoyment of life, Jackson Country Club offers full golf, tennis, dining, and social memberships. Contact the club directly for membership availability and guest policies.</p>
                    <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;"><strong>Phone:</strong> <a href="tel:+17316680980" style="color: #4a7c59;">(731) 668-0980</a><br>
                    <strong>Golf Shop:</strong> <a href="tel:+17313007916" style="color: #4a7c59;">(731) 300-7916</a></p>
                </div>

                <!-- Location & Contact -->
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-map-marker-alt"></i> Location & Contact
                    </h3>
                    <p><strong>Address:</strong><br>31 Jackson Country Club Ln<br>Jackson, TN 38305</p>
                    <p><strong>Phone:</strong><br><a href="tel:+17316680980" style="color: #4a7c59;">(731) 668-0980</a></p>
                    <iframe
                        src="https://maps.google.com/maps?q=31+Jackson+Country+Club+Ln,+Jackson,+TN+38305&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="180" style="border:0; border-radius: 8px; margin-top: 1rem;" loading="lazy">
                    </iframe>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=31+Jackson+Country+Club+Ln,+Jackson,+TN+38305"
                       target="_blank"
                       style="display: inline-block; margin-top: 0.75rem; color: #4a7c59; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>

            </div>

            <!-- About + Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Jackson Country Club</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">Jackson Country Club stands as "The Hub" of Hub City, a distinguished private club that has anchored Jackson, Tennessee's social and sporting landscape since its founding on February 7, 1914. Established by five visionary Jackson businessmen with the mission "to foster social pleasure, provide entertainment, and cultivate the enjoyment of life," the club has honored its founding ideals for over a century while building one of West Tennessee's finest golf facilities.</p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">The championship course, moved to its current location in early 1929, stretches 6,849 yards from the championship tees with a course rating of 73.4 and slope of 134. Five tee sets ranging from 4,597 to 6,849 yards accommodate every level of golfer. Champion Bermuda greens provide a consistent, fast putting surface year-round. The course's storied competitive history includes 45 Tennessee Golf Association championships, annual US Open Local Qualifier play, and Tennessee State Amateur Championship events. Golf legends Walter Hagen, Ben Hogan, Byron Nelson, Patty Berg, and Mickey Wright have all played here — a testament to the club's long-standing reputation for championship conditions and hospitality.</p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">Golf Course Architect Kris Spence completed a Master Plan for a major renovation with work planned for 2025, ensuring the course continues to meet modern championship standards while preserving its classic character. Beyond golf, the club offers world-class tennis, swimming, fitness, and dining — a complete private club experience built on more than a century of camaraderie and community.</p>
                </div>
                <div>
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Amenities</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-golf-ball" style="color: #4a7c59;"></i>
                            <span>18-Hole Championship Course</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-trophy" style="color: #4a7c59;"></i>
                            <span>USGA & TGA Tournament Host</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-table-tennis" style="color: #4a7c59;"></i>
                            <span>Tennis Facilities</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-swimming-pool" style="color: #4a7c59;"></i>
                            <span>Swimming Pool</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-dumbbell" style="color: #4a7c59;"></i>
                            <span>Health & Fitness Center</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-utensils" style="color: #4a7c59;"></i>
                            <span>Dining & Banquet Facilities</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-graduation-cap" style="color: #4a7c59;"></i>
                            <span>Practice Facilities</span>
                        </div>
                        <div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-heart" style="color: #4a7c59;"></i>
                            <span>Family Programs & Events</span>
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
                <p>Experience the beauty of Jackson Country Club</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 2rem;">
                <div style="height: 250px; border-radius: 15px; overflow: hidden; cursor: pointer;" onclick="openGallery()">
                    <img src="../images/courses/jackson-country-club/1.webp" alt="Jackson Country Club - Championship golf course" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="height: 250px; border-radius: 15px; overflow: hidden; cursor: pointer;" onclick="openGallery()">
                    <img src="../images/courses/jackson-country-club/2.webp" alt="Jackson Country Club - Fairway and course views" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="height: 250px; border-radius: 15px; overflow: hidden; cursor: pointer;" onclick="openGallery()">
                    <img src="../images/courses/jackson-country-club/3.webp" alt="Jackson Country Club - Course landscape" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/courses/jackson-country-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out Jackson Country Club in Jackson, Tennessee'); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/courses/jackson-country-club'); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Jackson Country Club'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/jackson-country-club'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 style="margin: 0; font-size: 2rem;">Jackson Country Club — Full Gallery</h2>
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
                src: `../images/courses/jackson-country-club/${i + 1}.webp`,
                alt: `Jackson Country Club - Photo ${i + 1}`
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
