<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golf Course Maps - Tennessee Golf Courses</title>
    <meta name="description" content="Interactive maps of Tennessee golf courses - find courses by location, region, and proximity to major cities across the Volunteer State.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=6">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=6">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .maps-page {
            padding-top: 90px;
            min-height: 80vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .maps-hero {
            text-align: center;
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            margin-bottom: 80px;
            margin-top: -140px;
        }
        
        .maps-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .maps-hero p {
            font-size: 1.4rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .maps-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .maps-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
            margin-bottom: 4rem;
        }
        
        .maps-section {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .maps-section:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-large);
        }
        
        .maps-section .icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
        }
        
        .maps-section h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .maps-section p {
            color: var(--text-gray);
            line-height: 1.8;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .maps-btn {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .maps-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .maps-btn.secondary {
            background: var(--secondary-color);
        }
        
        .maps-btn.secondary:hover {
            background: var(--accent-color);
        }
        
        .coming-soon-section {
            background: var(--bg-light);
            padding: 4rem 3rem;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .coming-soon-section h3 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .coming-soon-section p {
            font-size: 1.2rem;
            color: var(--text-gray);
            margin-bottom: 2rem;
            line-height: 1.8;
        }
        
        .region-preview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .region-card {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .region-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        
        .region-card .icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .region-card h4 {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .region-card p {
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .course-count {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }
        
        @media (max-width: 768px) {
            .maps-hero h1 {
                font-size: 2.5rem;
            }
            
            .maps-hero p {
                font-size: 1.2rem;
            }
            
            .maps-sections {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .maps-section {
                padding: 2rem;
            }
            
            .region-preview {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="maps-page">
        <!-- Dynamic Navigation -->
        <?php include 'includes/navigation.php'; ?>
        
        <!-- Maps Hero Section -->
        <div class="maps-hero">
            <h1>Tennessee Golf Course Maps</h1>
            <p>Discover golf courses across Tennessee with interactive maps and regional guides</p>
        </div>
        
        <!-- Maps Content -->
        <div class="maps-content">
            <!-- Maps Sections -->
            <div class="maps-sections">
                <div class="maps-section">
                    <div class="icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h2>Interactive Map</h2>
                    <p>Explore all Tennessee golf courses on our interactive map. Filter by location, course type, difficulty level, and amenities to find the perfect course for your next round.</p>
                    <a href="#" class="maps-btn" onclick="showComingSoon('Interactive Map')">
                        <i class="fas fa-map"></i>
                        Launch Map
                    </a>
                </div>
                
                <div class="maps-section">
                    <div class="icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <h2>Course Directions</h2>
                    <p>Get turn-by-turn directions to any Tennessee golf course. Integrated with Google Maps for the most accurate routing and real-time traffic updates.</p>
                    <a href="#" class="maps-btn secondary" onclick="showComingSoon('Course Directions')">
                        <i class="fas fa-directions"></i>
                        Get Directions
                    </a>
                </div>
            </div>
            
            <!-- Coming Soon Section -->
            <div class="coming-soon-section">
                <div class="icon" style="font-size: 6rem; color: var(--primary-color); margin-bottom: 2rem;">
                    <i class="fas fa-hammer"></i>
                </div>
                <h3>Coming Soon: Interactive Maps</h3>
                <p>We're developing comprehensive mapping features to help you discover and navigate to Tennessee's best golf courses. Our interactive maps will include course locations, driving directions, nearby amenities, and much more.</p>
                
                <!-- Region Preview -->
                <div class="region-preview">
                    <div class="region-card">
                        <div class="icon">
                            <i class="fas fa-city"></i>
                        </div>
                        <h4>Nashville Area</h4>
                        <p>Middle Tennessee's golf hub with premier public and private courses</p>
                        <span class="course-count">25+ Courses</span>
                    </div>
                    
                    <div class="region-card">
                        <div class="icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h4>East Tennessee</h4>
                        <p>Mountain golf with scenic views in Knoxville and Gatlinburg areas</p>
                        <span class="course-count">30+ Courses</span>
                    </div>
                    
                    <div class="region-card">
                        <div class="icon">
                            <i class="fas fa-water"></i>
                        </div>
                        <h4>West Tennessee</h4>
                        <p>Memphis area courses featuring river views and challenging layouts</p>
                        <span class="course-count">20+ Courses</span>
                    </div>
                    
                    <div class="region-card">
                        <div class="icon">
                            <i class="fas fa-tree"></i>
                        </div>
                        <h4>Middle Tennessee</h4>
                        <p>Rolling hills and tree-lined fairways in Tennessee's heartland</p>
                        <span class="course-count">35+ Courses</span>
                    </div>
                </div>
            </div>
            
            <!-- Current Access -->
            <div class="maps-section" style="text-align: center; grid-column: 1 / -1;">
                <h3 style="color: var(--primary-color); margin-bottom: 2rem;">In the Meantime</h3>
                <p style="margin-bottom: 2rem;">While we build our interactive mapping features, you can still find course locations and get directions from individual course pages.</p>
                <a href="/courses" class="maps-btn">
                    <i class="fas fa-golf-ball"></i>
                    Browse All Courses
                </a>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <script>
        function showComingSoon(feature) {
            alert(`${feature} feature is coming soon! We're working hard to bring you the best golf course mapping experience in Tennessee.`);
        }
    </script>
</body>
</html>