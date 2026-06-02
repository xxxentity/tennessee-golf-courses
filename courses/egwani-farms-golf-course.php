<?php
require_once '../includes/performance.php';
require_once '../config/database.php';
require_once '../includes/seo.php';
Performance::start();
Performance::enableCompression();

$course_slug = 'egwani-farms-golf-course';
$course_name = 'Egwani Farms Golf Course';

$course_data = [
    'name' => 'Egwani Farms Golf Course',
    'location' => 'Knoxville, TN',
    'description' => 'Historic 18-hole public course built in 1991 on former farmland near Knoxville. "Egwani" means little river — the Little River borders 7 holes. Extensively renovated in 2016.',
    'image' => '/images/courses/egwani-farms-golf-course/1.jpeg',
    'holes' => 18,
    'par' => 72,
    'designer' => 'N/A',
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
            'streetAddress' => '3920 S Singleton Station Road',
            'addressLocality' => 'Rockford',
            'addressRegion' => 'TN',
            'postalCode' => '37853',
            'addressCountry' => 'US'
        ],
        'telephone' => '+18659707132',
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
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/egwani-farms-golf-course/1.jpeg');
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 700;">Egwani Farms Golf Course</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9;">Built on Former Farmland • Knoxville, Tennessee</p>
        </div>
    </section>

    <!-- Course Details -->
    <section class="course-details" style="padding: 4rem 0; background: white;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">

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
                            <span style="font-weight: 600; color: #2c5234;">6,708</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Type</span>
                            <span style="font-weight: 600; color: #2c5234;">Public</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0;">
                            <span style="color: #666; font-weight: 500;">Greens</span>
                            <span style="font-weight: 600; color: #2c5234;">Bermuda</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 0;">
                            <span style="color: #666; font-weight: 500;">Opened</span>
                            <span style="font-weight: 600; color: #2c5234;">1991</span>
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
                                <th style="padding: 0.6rem 0.8rem; text-align: center;">9 Holes</th>
                                <th style="padding: 0.6rem 0.8rem; text-align: center;">18 Holes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem; font-weight: 500;">Mon – Fri</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">$29</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">$49</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 0.6rem 0.8rem; font-weight: 500;">Sat – Sun</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">$34</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">$59</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6rem 0.8rem; font-weight: 500;">Senior (Mon–Fri)</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #666;">—</td>
                                <td style="padding: 0.6rem 0.8rem; text-align: center; color: #2c5234; font-weight: 600;">$41</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 0.82rem; color: #888; margin-top: 1rem;">Rates include cart, range balls, and sales tax. Call <a href="tel:8659707132" style="color: #2c5234;">(865) 970-7132</a> for tee times.</p>
                </div>

                <!-- Location & Contact -->
                <div style="background: #f8f9fa; border-radius: 15px; padding: 2rem; border-left: 4px solid #2c5234;">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem; font-size: 1.3rem;">Location & Contact</h3>
                    <p style="color: #555; line-height: 1.7; margin-bottom: 1rem;">
                        <strong>Address:</strong><br>
                        3920 S Singleton Station Road<br>
                        Rockford, TN 37853
                    </p>
                    <p style="color: #555; margin-bottom: 0.6rem;">
                        <strong>Phone:</strong><br>
                        <a href="tel:8659707132" style="color: #2c5234; font-weight: 600; text-decoration: none;">(865) 970-7132</a>
                    </p>
                    <p style="color: #555; margin-bottom: 1.2rem;">
                        <strong>Website:</strong><br>
                        <a href="https://www.egwanifarmsgolf.com" target="_blank" rel="noopener noreferrer" style="color: #2c5234; font-weight: 600;">egwanifarmsgolf.com</a>
                    </p>
                    <iframe
                        src="https://maps.google.com/maps?q=3920+S+Singleton+Station+Road,+Rockford,+TN+37853&output=embed"
                        width="100%"
                        height="150"
                        style="border: 0; border-radius: 8px; margin-bottom: 1rem;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                    <a href="https://maps.google.com/maps?q=3920+S+Singleton+Station+Road,+Rockford,+TN+37853"
                       target="_blank"
                       style="display: inline-block; background: #2c5234; color: white; padding: 0.6rem 1.2rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
                        <i class="fas fa-directions" style="margin-right: 0.4rem;"></i> Get Directions
                    </a>
                </div>

            </div>

            <!-- About & Amenities -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; margin-bottom: 4rem;">
                <div>
                    <h2 style="color: #2c5234; margin-bottom: 1.5rem;">About Egwani Farms Golf Course</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        Egwani Farms Golf Course takes its name from the Cherokee word for "little river" — a fitting tribute to the Little River that winds through the property and comes into view on at least seven of the eighteen holes. Built in 1991 on former farmland in Rockford, just southwest of Knoxville, the course was designed to honor the agricultural character of the land it replaced. The preserved original farmhouse now serves as the clubhouse, positioned to overlook the finishing hole, and the original farm silo from more than fifty years ago still stands on the property as a landmark.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                        In 2016, celebrating its 25th anniversary, Egwani Farms underwent a comprehensive renovation that modernized the course without erasing its identity. The improvements included championship Bermuda greens, Billy-style bunkers, new GPS-equipped golf carts, updated cart paths, and fifteen acres of natural fescue grass planted throughout the rough. The fescue adds visual definition and strategic bite to a layout that had always been known for its playable but engaging character.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555;">
                        Playing to 6,708 yards from the back tees with a par of 72, Egwani Farms is consistently cited as one of the best public values in the greater Knoxville market. The course's approach — competitive green fees that include the cart and range balls — keeps it accessible without cutting corners on conditions. The Little River Deli, teaching pro on staff, and short game area round out a facility that functions as a complete golf destination. Its tagline, "Make the Farm Your Home for Golf," is more than a slogan — it reflects the genuinely welcoming atmosphere the staff has maintained since opening day.
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
                            <span>Short Game Area</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-store" style="color: #2c5234; width: 20px;"></i>
                            <span>Pro Shop</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-user-tie" style="color: #2c5234; width: 20px;"></i>
                            <span>Teaching Pro</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-utensils" style="color: #2c5234; width: 20px;"></i>
                            <span>Little River Deli</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-shopping-cart" style="color: #2c5234; width: 20px;"></i>
                            <span>Club Rentals</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-car" style="color: #2c5234; width: 20px;"></i>
                            <span>GPS-Equipped Carts</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-water" style="color: #2c5234; width: 20px;"></i>
                            <span>River Views (7 Holes)</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.7rem 1rem; background: #f8f9fa; border-radius: 8px; width: 100%;">
                            <i class="fas fa-home" style="color: #2c5234; width: 20px;"></i>
                            <span>Historic Farmhouse Clubhouse</span>
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
                    <img src="../images/courses/egwani-farms-golf-course/2.jpeg" alt="Egwani Farms Golf Course - Fairway" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/egwani-farms-golf-course/3.jpeg" alt="Egwani Farms Golf Course - Little River" style="width: 100%; height: 250px; object-fit: cover;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); cursor: pointer;">
                    <img src="../images/courses/egwani-farms-golf-course/4.jpeg" alt="Egwani Farms Golf Course - Course View" style="width: 100%; height: 250px; object-fit: cover;">
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/courses/egwani-farms-golf-course" target="_blank" class="share-button facebook" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #1877f2; color: white;">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Check%20out%20Egwani%20Farms%20Golf%20Course&url=https://tennesseegolfcourses.com/courses/egwani-farms-golf-course" target="_blank" class="share-button twitter" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #000000; color: white;">
                        <strong style="font-size: 1.1rem;">𝕏</strong> Share on X
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode('Check out Egwani Farms Golf Course'); ?>&body=<?php echo urlencode('I thought you might be interested in this golf course: https://tennesseegolfcourses.com/courses/egwani-farms-golf-course'); ?>" class="share-button email" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 500; background: #6c757d; color: white;">
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
                <h2 class="modal-title">Egwani Farms Golf Course - Complete Photo Gallery</h2>
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
                img.src = `../images/courses/egwani-farms-golf-course/${i}.jpeg`;
                img.alt = `Egwani Farms Golf Course - Photo ${i}`;
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

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>
