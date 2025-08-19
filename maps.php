<?php
session_start();
// TEMPORARILY DISABLE CSP FOR TESTING - DO NOT USE IN PRODUCTION
// Comment out the security headers include to test if CSP is the only issue
// require_once 'includes/security-headers.php';

// Temporarily allow all sources for testing
header("Content-Security-Policy: default-src *; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'; img-src * data:; font-src *; connect-src *; frame-src *;");
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
    
    <!-- Mapbox CSS -->
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
    
    <style>
        .maps-page {
            padding-top: 90px;
            min-height: 80vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .map-container {
            width: 100%;
            height: 70vh;
            border-radius: 15px;
            box-shadow: var(--shadow-large);
            margin: 2rem 0;
            overflow: hidden;
        }
        
        #tennessee-golf-map {
            width: 100%;
            height: 100%;
        }
        
        .mapboxgl-popup-content {
            padding: 15px;
            border-radius: 10px;
            box-shadow: var(--shadow-medium);
        }
        
        .popup-course-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        
        .popup-address {
            color: var(--text-gray);
            margin-bottom: 5px;
        }
        
        .popup-phone {
            color: var(--text-gray);
            margin-bottom: 10px;
        }
        
        .popup-link {
            background: var(--primary-color);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .popup-link:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
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
        
        <!-- Interactive Map -->
        <div class="maps-content">
            <div class="section-header" style="text-align: center; margin-bottom: 2rem;">
                <h2 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1rem;">Interactive Tennessee Golf Course Map</h2>
                <p style="color: var(--text-gray); font-size: 1.2rem;">Click on any course marker to view details and get directions</p>
            </div>
            
            <!-- Map Container -->
            <div class="map-container">
                <div id="tennessee-golf-map"></div>
            </div>
            
            <!-- Map Legend -->
            <div style="display: flex; justify-content: center; gap: 2rem; margin: 2rem 0; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background: #4CAF50; border-radius: 50%;"></div>
                    <span>Public Courses</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background: #2196F3; border-radius: 50%;"></div>
                    <span>Municipal Courses</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background: #FF9800; border-radius: 50%;"></div>
                    <span>Private Courses</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background: #9C27B0; border-radius: 50%;"></div>
                    <span>Semi-Private Courses</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- Mapbox JavaScript -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
    
    <script>
        // Debug Mapbox loading
        console.log('1. Script starting...');
        console.log('2. Mapbox GL JS loaded:', typeof mapboxgl !== 'undefined');
        
        // Check if Mapbox GL JS is loaded
        if (typeof mapboxgl === 'undefined') {
            document.getElementById('tennessee-golf-map').innerHTML = '<div style="padding: 20px; text-align: center; color: red;">Error: Mapbox GL JS failed to load. Check internet connection.</div>';
        } else {
            console.log('3. Setting access token...');
            
            // Use your default public token - replace with your actual pk. token
            mapboxgl.accessToken = 'pk.eyJ1IjoidGdjYWRtaW4iLCJhIjoiY21lajN4MnFmMDk0YjJrb2NjNnpuNG11NiJ9.F6spXsBwVWg4LY2iFk0frw';
            console.log('4. Token set, creating map...');
            
            try {
            const map = new mapboxgl.Map({
                container: 'tennessee-golf-map',
                style: 'mapbox://styles/mapbox/streets-v12', // Using basic streets first to test
                center: [-86.7816, 36.1627], // Center of Tennessee
                zoom: 6.5,
                pitch: 0,
                bearing: 0
            });
            
            console.log('Map initialized successfully');
            
            // Add error event listener
            map.on('error', function(e) {
                console.error('Mapbox error:', e);
                document.getElementById('tennessee-golf-map').innerHTML = '<div style="padding: 20px; text-align: center; color: red;">Map failed to load. Check console for errors.</div>';
            });
            
            // Test if map loads
            map.on('load', function() {
                console.log('Map loaded successfully');
            });
            
            // Golf course data with complete information
            const golfCourses = [
            {
                name: "Avalon Golf & Country Club",
                address: "1299 Oak Chase Blvd, Lenoir City, TN 37772",
                phone: "(865) 986-4653",
                type: "Semi-Private",
                coordinates: [-84.2689, 35.7923],
                slug: "avalon-golf-country-club"
            },
            {
                name: "Bear Trace at Tims Ford",
                address: "570 Bear Trace Drive, Winchester, TN 37398", 
                phone: "(931) 962-4653",
                type: "Public",
                coordinates: [-86.1122, 35.1856],
                slug: "bear-trace-at-tims-ford"
            },
            {
                name: "Bear Trace at Cumberland Mountain",
                address: "407 Wild Plum Lane, Crossville, TN 38572",
                phone: "(931) 707-1640", 
                type: "Public",
                coordinates: [-85.0269, 35.9489],
                slug: "bear-trace-at-cumberland-mountain"
            },
            {
                name: "Bear Trace at Harrison Bay",
                address: "8919 Harrison Bay Road, Harrison, TN 37341",
                phone: "(423) 326-0885",
                type: "Public", 
                coordinates: [-85.1355, 35.1178],
                slug: "bear-trace-at-harrison-bay"
            },
            {
                name: "Belle Acres Golf Course",
                address: "901 E Broad St, Cookeville, TN",
                phone: "(931) 310-0645",
                type: "Public",
                coordinates: [-85.4808, 36.1628],
                slug: "belle-acres-golf-course"
            },
            {
                name: "Belle Meade Country Club", 
                address: "815 Belle Meade Blvd, Nashville, TN 37205",
                phone: "(615) 385-0150",
                type: "Private",
                coordinates: [-86.8611, 36.1044],
                slug: "belle-meade-country-club"
            },
            {
                name: "Bluegrass Yacht & Country Club",
                address: "550 Johnny Cash Parkway, Hendersonville, TN 37075", 
                phone: "(615) 824-6566",
                type: "Private",
                coordinates: [-86.6200, 36.3047],
                slug: "bluegrass-yacht-country-club"
            },
            {
                name: "Brainerd Golf Course",
                address: "5203 Old Mission Road, Chattanooga, TN 37411",
                phone: "(423) 855-2692",
                type: "Municipal",
                coordinates: [-85.2872, 35.0356],
                slug: "brainerd-golf-course"
            },
            {
                name: "Brown Acres Golf Course", 
                address: "406 Brown Road, Chattanooga, TN 37421",
                phone: "(423) 855-2680",
                type: "Municipal",
                coordinates: [-85.1791, 35.0061],
                slug: "brown-acres-golf-course"
            },
            {
                name: "Cumberland Cove Golf Course",
                address: "16941 Highway 70 N, Monterey, TN 38574",
                phone: "(931) 839-3313", 
                type: "Public",
                coordinates: [-85.2669, 36.1489],
                slug: "cumberland-cove-golf-course"
            }
            ];
            
            // Color scheme for different course types
            const courseColors = {
                'Public': '#4CAF50',     // Green
                'Municipal': '#2196F3',   // Blue  
                'Private': '#FF9800',     // Orange
                'Semi-Private': '#9C27B0' // Purple
            };
            
            // Add markers when map loads
            map.on('load', function() {
                console.log('Adding course markers...');
                golfCourses.forEach(function(course) {
                    // Create popup content
                    const popupContent = `
                        <div class="popup-course-name">${course.name}</div>
                        <div class="popup-address">${course.address}</div>
                        <div class="popup-phone">${course.phone}</div>
                        <a href="/courses/${course.slug}" class="popup-link">View Course Details</a>
                    `;
                    
                    // Create popup
                    const popup = new mapboxgl.Popup({
                        offset: 25
                    }).setHTML(popupContent);
                    
                    // Create marker
                    const marker = new mapboxgl.Marker({
                        color: courseColors[course.type] || '#4CAF50'
                    })
                    .setLngLat(course.coordinates)
                    .setPopup(popup)
                    .addTo(map);
                });
                console.log('Course markers added successfully');
            });
            
            // Add navigation controls
            map.addControl(new mapboxgl.NavigationControl());
            
            // Add fullscreen control  
            map.addControl(new mapboxgl.FullscreenControl());
            
        } catch (error) {
            console.error('Error initializing map:', error);
            document.getElementById('tennessee-golf-map').innerHTML = '<div style="padding: 20px; text-align: center; color: red;">Error: ' + error.message + '</div>';
        }
    </script>
</body>
</html>