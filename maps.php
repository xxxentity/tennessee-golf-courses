<?php
session_start();
// Security headers are now properly configured in .htaccess
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
            
            // Golf course data with complete information - ALL 92 COURSES
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
                slug: "bear-trace-cumberland-mountain"
            },
            {
                name: "Bear Trace at Harrison Bay",
                address: "8919 Harrison Bay Road, Harrison, TN 37341",
                phone: "(423) 326-0885",
                type: "Public", 
                coordinates: [-85.1355, 35.1178],
                slug: "bear-trace-harrison-bay"
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
                name: "Big Creek Golf Club",
                address: "5400 Big Creek Dr, Millington, TN 38053",
                phone: "(901) 353-1654",
                type: "Semi-Private",
                coordinates: [-89.8989, 35.3434],
                slug: "big-creek-golf-club"
            },
            {
                name: "Blackthorn Club",
                address: "1501 Ridges Club Drive, Jonesborough, TN 37659",
                phone: "(423) 753-7888",
                type: "Private",
                coordinates: [-82.4734, 36.2945],
                slug: "blackthorn-club"
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
                name: "Cedar Crest Golf Club",
                address: "2371 Cedar Crest Road, Benton, TN 37307",
                phone: "(423) 338-2251",
                type: "Public",
                coordinates: [-84.6544, 35.1756],
                slug: "cedar-crest-golf-club"
            },
            {
                name: "Chattanooga Golf & Country Club",
                address: "7110 Shallowford Road, Chattanooga, TN 37421",
                phone: "(423) 894-1441",
                type: "Private",
                coordinates: [-85.1580, 35.0539],
                slug: "chattanooga-golf-country-club"
            },
            {
                name: "Cheekwood Golf Club",
                address: "285 Spencer Creek Rd, Franklin, TN 37069",
                phone: "(615) 794-8223",
                type: "Public",
                coordinates: [-86.8556, 35.9269],
                slug: "cheekwood-golf-club"
            },
            {
                name: "Cherokee Country Club",
                address: "5138 Lyons View Pike, Knoxville, TN 37919",
                phone: "(865) 584-4637",
                type: "Private",
                coordinates: [-83.9919, 35.9392],
                slug: "cherokee-country-club"
            },
            {
                name: "Clarksville Country Club",
                address: "1025 Tylertown Rd, Clarksville, TN 37040",
                phone: "(931) 647-8894",
                type: "Private",
                coordinates: [-87.3778, 36.5298],
                slug: "clarksville-country-club"
            },
            {
                name: "Colonial Country Club",
                address: "9765 Poplar Ave, Cordova, TN 38018",
                phone: "(901) 754-1775",
                type: "Private",
                coordinates: [-89.7833, 35.1667],
                slug: "colonial-country-club"
            },
            {
                name: "Council Fire Golf Club",
                address: "1300 Council Fire Dr, Chattanooga, TN 37421",
                phone: "(423) 855-4653",
                type: "Private",
                coordinates: [-85.1889, 35.0278],
                slug: "council-fire-golf-club"
            },
            {
                name: "Cumberland Cove Golf Course",
                address: "16941 Highway 70 N, Monterey, TN 38574",
                phone: "(931) 839-3313", 
                type: "Public",
                coordinates: [-85.2669, 36.1489],
                slug: "cumberland-cove-golf-course"
            },
            {
                name: "Dead Horse Lake Golf Course",
                address: "3016 Gravelly Hills Road, Louisville, TN 37777",
                phone: "(865) 693-5270",
                type: "Public",
                coordinates: [-83.9167, 35.8167],
                slug: "dead-horse-lake-golf-course"
            },
            {
                name: "Druid Hills Golf Club",
                address: "3025 Walnut Grove Rd, Memphis, TN 38111",
                phone: "(901) 685-0036",
                type: "Public",
                coordinates: [-89.8467, 35.1078],
                slug: "druid-hills-golf-club"
            },
            {
                name: "Eagle's Landing Golf Club",
                address: "1556 Old Knoxville Highway, Sevierville, TN 37876",
                phone: "(865) 429-4223",
                type: "Municipal",
                coordinates: [-83.5619, 35.8481],
                slug: "eagles-landing-golf-club"
            },
            {
                name: "Egwani Farms Golf Course",
                address: "4250 Egwani Farms Drive, Rockford, TN 37853",
                phone: "(865) 970-7132",
                type: "Public",
                coordinates: [-83.9333, 35.8500],
                slug: "egwani-farms-golf-course"
            },
            {
                name: "Fall Creek Falls State Park Golf Course",
                address: "2009 Village Camp Rd, Pikeville, TN 37367",
                phone: "(423) 881-5706",
                type: "Public",
                coordinates: [-85.3444, 35.6667],
                slug: "fall-creek-falls-state-park-golf-course"
            },
            {
                name: "Forrest Crossing Golf Course",
                address: "750 Riverview Dr, Franklin, TN 37064",
                phone: "(615) 794-9400",
                type: "Public",
                coordinates: [-86.8389, 35.9050],
                slug: "forrest-crossing-golf-course"
            },
            {
                name: "Fox Den Country Club",
                address: "2001 Twelve Stones Crossing, Goodlettsville, TN 37072",
                phone: "(615) 859-4653",
                type: "Semi-Private",
                coordinates: [-86.7261, 36.3231],
                slug: "fox-den-country-club"
            },
            {
                name: "Gaylord Springs Golf Links",
                address: "18 Springs Blvd, Nashville, TN 37214",
                phone: "(615) 458-1730",
                type: "Public",
                coordinates: [-86.6917, 36.2028],
                slug: "gaylord-springs-golf-links"
            },
            {
                name: "Greystone Golf Course",
                address: "5555 Greystone Dr, Dickson, TN 37055",
                phone: "(615) 446-0044",
                type: "Public",
                coordinates: [-87.3889, 36.0833],
                slug: "greystone-golf-course"
            },
            {
                name: "Harpeth Hills Golf Course",
                address: "2424 Old Hickory Blvd, Nashville, TN 37221",
                phone: "(615) 862-8493",
                type: "Municipal",
                coordinates: [-86.8556, 36.0889],
                slug: "harpeth-hills-golf-course"
            },
            {
                name: "Henry Horton State Park Golf Course",
                address: "4209 Nashville Hwy, Chapel Hill, TN 37034",
                phone: "(931) 364-7724",
                type: "Public",
                coordinates: [-86.6889, 35.6167],
                slug: "henry-horton-state-park-golf-course"
            },
            {
                name: "Hermitage Golf Course",
                address: "3939 Old Hickory Blvd, Old Hickory, TN 37138",
                phone: "(615) 847-4001",
                type: "Public",
                coordinates: [-86.6167, 36.2167],
                slug: "hermitage-golf-course"
            },
            {
                name: "Hillwood Country Club",
                address: "5200 Hillwood Blvd, Nashville, TN 37205",
                phone: "(615) 353-6347",
                type: "Private",
                coordinates: [-86.8833, 36.1167],
                slug: "hillwood-country-club"
            },
            {
                name: "Holston Hills Country Club",
                address: "7489 Holston Hills Rd, Knoxville, TN 37914",
                phone: "(865) 523-2411",
                type: "Private",
                coordinates: [-83.8556, 35.9667],
                slug: "holston-hills-country-club"
            },
            {
                name: "Honky Tonk National Golf Course",
                address: "210 Willie Nelson Blvd, Smyrna, TN 37167",
                phone: "(615) 459-9292",
                type: "Public",
                coordinates: [-86.5167, 35.9833],
                slug: "honky-tonk-national-golf-course"
            },
            {
                name: "Island Pointe Golf Club",
                address: "1 Clubhouse Dr, Vonore, TN 37885",
                phone: "(423) 884-1808",
                type: "Public",
                coordinates: [-84.2333, 35.6167],
                slug: "island-pointe-golf-club"
            },
            {
                name: "Jackson Country Club",
                address: "318 Country Club Ln, Jackson, TN 38305",
                phone: "(731) 422-1504",
                type: "Private",
                coordinates: [-88.8167, 35.6167],
                slug: "jackson-country-club"
            },
            {
                name: "Lake Tansi Golf Course",
                address: "1 Fairway Dr, Crossville, TN 38572",
                phone: "(931) 788-3301",
                type: "Semi-Private",
                coordinates: [-85.0833, 35.9833],
                slug: "lake-tansi-golf-course"
            },
            {
                name: "Lambert Acres Golf Club",
                address: "607 W Main St, Hendersonville, TN 37075",
                phone: "(615) 824-1100",
                type: "Public",
                coordinates: [-86.6333, 36.3167],
                slug: "lambert-acres-golf-club"
            },
            {
                name: "Laurel Valley Country Club",
                address: "550 Country Club Dr, Townsend, TN 37882",
                phone: "(865) 448-6590",
                type: "Semi-Private",
                coordinates: [-83.7556, 35.6778],
                slug: "laurel-valley-country-club"
            },
            {
                name: "Lookout Mountain Club",
                address: "104 Fairway Ave, Lookout Mountain, TN 37350",
                phone: "(423) 821-9807",
                type: "Private",
                coordinates: [-85.3500, 34.9667],
                slug: "lookout-mountain-club"
            },
            {
                name: "McCabe Golf Course",
                address: "100 26th Ave N, Nashville, TN 37203",
                phone: "(615) 862-8474",
                type: "Municipal",
                coordinates: [-86.8056, 36.1833],
                slug: "mccabe-golf-course"
            },
            {
                name: "Mirimichi Golf Course",
                address: "6195 Millington-Memphis Hwy, Millington, TN 38053",
                phone: "(901) 873-4653",
                type: "Public",
                coordinates: [-89.9167, 35.3333],
                slug: "mirimichi-golf-course"
            },
            {
                name: "Moccasin Bend Golf Course",
                address: "381 Moccasin Bend Rd, Chattanooga, TN 37405",
                phone: "(423) 267-3585",
                type: "Municipal",
                coordinates: [-85.3167, 35.0667],
                slug: "moccasin-bend-golf-course"
            },
            {
                name: "Montgomery Bell State Park Golf Course",
                address: "1020 Hotel Ave, Burns, TN 37029",
                phone: "(615) 797-2578",
                type: "Public",
                coordinates: [-87.3167, 36.0500],
                slug: "montgomery-bell-state-park-golf-course"
            },
            {
                name: "Nashville Golf & Athletic Club",
                address: "300 Franklin Rd, Franklin, TN 37069",
                phone: "(615) 383-3921",
                type: "Private",
                coordinates: [-86.8556, 36.0167],
                slug: "nashville-golf-athletic-club"
            },
            {
                name: "Nashville National Golf Links",
                address: "1324 Murfreesboro Pike, Nashville, TN 37217",
                phone: "(615) 361-8000",
                type: "Public",
                coordinates: [-86.7167, 36.1167],
                slug: "nashville-national-golf-links"
            },
            {
                name: "Old Fort Golf Course",
                address: "245 Fortress Dr, Murfreesboro, TN 37128",
                phone: "(615) 895-1068",
                type: "Public",
                coordinates: [-86.4167, 35.8167],
                slug: "old-fort-golf-course"
            },
            {
                name: "Old Hickory Country Club",
                address: "4853 Old Hickory Blvd, Old Hickory, TN 37138",
                phone: "(615) 847-9040",
                type: "Private",
                coordinates: [-86.6167, 36.2000],
                slug: "old-hickory-country-club"
            },
            {
                name: "Overton Park Golf Course",
                address: "2080 Poplar Ave, Memphis, TN 38104",
                phone: "(901) 725-9905",
                type: "Municipal",
                coordinates: [-90.0167, 35.1500],
                slug: "overton-park-9"
            },
            {
                name: "Paris Landing State Park Golf Course",
                address: "16055 Hwy 79 N, Buchanan, TN 38222",
                phone: "(731) 644-0023",
                type: "Public",
                coordinates: [-88.0167, 36.4833],
                slug: "paris-landing-state-park-golf-course"
            },
            {
                name: "Percy Warner Golf Course",
                address: "2500 Old Hickory Blvd, Nashville, TN 37221",
                phone: "(615) 862-8463",
                type: "Municipal",
                coordinates: [-86.8667, 36.0833],
                slug: "percy-warner-golf-course"
            },
            {
                name: "Pickwick Landing State Park Golf Course",
                address: "120 Playground Loop, Pickwick Dam, TN 38365",
                phone: "(731) 689-3149",
                type: "Public",
                coordinates: [-88.2333, 35.0667],
                slug: "pickwick-landing-state-park"
            },
            {
                name: "Pine Oaks Golf Course",
                address: "3465 US-411, Vonore, TN 37885",
                phone: "(423) 884-6681",
                type: "Public",
                coordinates: [-84.2167, 35.5833],
                slug: "pine-oaks-golf-course"
            },
            {
                name: "Richland Country Club",
                address: "1 Country Club Dr, Nashville, TN 37205",
                phone: "(615) 353-6441",
                type: "Private",
                coordinates: [-86.8833, 36.1000],
                slug: "richland-country-club"
            },
            {
                name: "Ross Creek Landing Golf Course",
                address: "7031 Brevard Rd, Lenoir City, TN 37771",
                phone: "(865) 717-4653",
                type: "Public",
                coordinates: [-84.2667, 35.7833],
                slug: "ross-creek-landing-golf-course"
            },
            {
                name: "Sevierville Golf Club",
                address: "1556 Old Knoxville Highway, Sevierville, TN 37876",
                phone: "(865) 429-4223",
                type: "Municipal",
                coordinates: [-83.5619, 35.8481],
                slug: "sevierville-golf-club"
            },
            {
                name: "Signal Mountain Golf & Country Club",
                address: "2935 Corral Rd, Signal Mountain, TN 37377",
                phone: "(423) 886-9090",
                type: "Private",
                coordinates: [-85.3500, 35.1333],
                slug: "signal-mountain-golf-country-club"
            },
            {
                name: "Southern Hills Golf & Country Club",
                address: "4080 Massey Totten Rd, Cookeville, TN 38506",
                phone: "(931) 526-4653",
                type: "Private",
                coordinates: [-85.5167, 36.1667],
                slug: "southern-hills-golf-country-club"
            },
            {
                name: "Springhouse Golf Club",
                address: "18 Springhouse Ln, Nashville, TN 37214",
                phone: "(615) 871-7759",
                type: "Public",
                coordinates: [-86.6667, 36.2167],
                slug: "springhouse-golf-club"
            },
            {
                name: "Stonehenge Golf Club",
                address: "4095 Akins Rd, Fairview, TN 37062",
                phone: "(615) 799-8000",
                type: "Public",
                coordinates: [-87.1167, 35.9833],
                slug: "stonehenge-golf-club"
            },
            {
                name: "Stones River Country Club",
                address: "1830 NW Broad St, Murfreesboro, TN 37129",
                phone: "(615) 893-6426",
                type: "Private",
                coordinates: [-86.4167, 35.8667],
                slug: "stones-river-country-club"
            },
            {
                name: "Sweetens Cove Golf Club",
                address: "4650 Sweetens Cove Rd, South Pittsburg, TN 37380",
                phone: "(423) 837-7370",
                type: "Public",
                coordinates: [-85.7167, 35.0000],
                slug: "sweetens-cove-golf-club"
            },
            {
                name: "Tanasi Golf Course",
                address: "2200 Tanasi Trail, Vonore, TN 37885",
                phone: "(423) 884-6781",
                type: "Public",
                coordinates: [-84.2333, 35.6000],
                slug: "tanasi-golf-course"
            },
            {
                name: "Ted Rhodes Golf Course",
                address: "1901 Ed Temple Blvd, Nashville, TN 37208",
                phone: "(615) 862-8463",
                type: "Municipal",
                coordinates: [-86.8167, 36.1833],
                slug: "ted-rhodes-golf-course"
            },
            {
                name: "Temple Hills Country Club",
                address: "5200 Edmondson Pike, Nashville, TN 37211",
                phone: "(615) 832-0969",
                type: "Private",
                coordinates: [-86.7167, 36.0500],
                slug: "temple-hills-country-club"
            },
            {
                name: "Tennessee Grasslands at Fairvue",
                address: "1400 Union Camp Rd, Gallatin, TN 37066",
                phone: "(615) 230-8474",
                type: "Public",
                coordinates: [-86.4167, 36.3833],
                slug: "tennessee-grasslands-fairvue"
            },
            {
                name: "Tennessee Grasslands at Foxland",
                address: "2000 Twelve Stones Crossing, Gallatin, TN 37066",
                phone: "(615) 230-8474",
                type: "Public",
                coordinates: [-86.4333, 36.3667],
                slug: "tennessee-grasslands-foxland"
            },
            {
                name: "Tennessee National Golf Club",
                address: "1901 Loudon Hwy, Loudon, TN 37774",
                phone: "(865) 458-5797",
                type: "Public",
                coordinates: [-84.3333, 35.7333],
                slug: "tennessee-national-golf-club"
            },
            {
                name: "The Club at Five Oaks",
                address: "4725 Saundersville Rd, Lebanon, TN 37090",
                phone: "(615) 443-4653",
                type: "Private",
                coordinates: [-86.2667, 36.2167],
                slug: "the-club-at-five-oaks"
            },
            {
                name: "The Club at Gettysvue",
                address: "1400 Gettysvue Dr, Knoxville, TN 37922",
                phone: "(865) 966-4653",
                type: "Private",
                coordinates: [-84.0167, 35.9167],
                slug: "the-club-at-gettysvue"
            },
            {
                name: "The Golf Club of Tennessee",
                address: "1000 Twelve Stones Crossing, Kingston Springs, TN 37082",
                phone: "(615) 352-5000",
                type: "Private",
                coordinates: [-87.1167, 36.1167],
                slug: "the-golf-club-of-tennessee"
            },
            {
                name: "The Governors Club",
                address: "1500 Twelve Stones Crossing, Brentwood, TN 37027",
                phone: "(615) 790-9977",
                type: "Private",
                coordinates: [-86.7833, 35.9833],
                slug: "the-governors-club"
            },
            {
                name: "The Grove",
                address: "1 Champions Blvd, College Grove, TN 37046",
                phone: "(615) 591-4653",
                type: "Private",
                coordinates: [-86.6667, 35.7833],
                slug: "the-grove"
            },
            {
                name: "The Honors Course",
                address: "9062 Lee Hwy, Ooltewah, TN 37363",
                phone: "(423) 894-4653",
                type: "Private",
                coordinates: [-85.0667, 35.0833],
                slug: "the-honors-course"
            },
            {
                name: "The Legacy Golf Course",
                address: "1500 Legacy Dr, Springfield, TN 37172",
                phone: "(615) 384-0246",
                type: "Public",
                coordinates: [-86.8833, 36.5167],
                slug: "the-legacy-golf-course"
            },
            {
                name: "The Links at Audubon",
                address: "4160 Audubon Dr, Memphis, TN 38125",
                phone: "(901) 756-7770",
                type: "Public",
                coordinates: [-89.8167, 35.0833],
                slug: "the-links-at-audubon"
            },
            {
                name: "The Links at Fox Meadows",
                address: "3740 Clarke Rd, Memphis, TN 38115",
                phone: "(901) 362-0232",
                type: "Public",
                coordinates: [-89.8667, 35.0667],
                slug: "the-links-at-fox-meadows"
            },
            {
                name: "The Links at Galloway",
                address: "3815 Linbar Dr, Memphis, TN 38127",
                phone: "(901) 685-7805",
                type: "Public",
                coordinates: [-89.9167, 35.1833],
                slug: "the-links-at-galloway"
            },
            {
                name: "The Links at Kahite",
                address: "100 Kahite Trail, Tellico Plains, TN 37385",
                phone: "(423) 253-4653",
                type: "Public",
                coordinates: [-84.2833, 35.3667],
                slug: "the-links-at-kahite"
            },
            {
                name: "The Links at Whitehaven",
                address: "1645 Nazareth Rd, Memphis, TN 38116",
                phone: "(901) 396-7320",
                type: "Public",
                coordinates: [-90.0000, 35.0333],
                slug: "the-links-at-whitehaven"
            },
            {
                name: "Three Ridges Golf Course",
                address: "3440 Three Ridges Dr, Knoxville, TN 37931",
                phone: "(865) 687-4797",
                type: "Public",
                coordinates: [-84.1000, 35.9833],
                slug: "three-ridges-golf-course"
            },
            {
                name: "Toqua Golf Course",
                address: "352 Toqua Dr, Vonore, TN 37885",
                phone: "(423) 884-6781",
                type: "Public",
                coordinates: [-84.2167, 35.6167],
                slug: "toqua-golf-course"
            },
            {
                name: "TPC Southwind",
                address: "3325 Club at Southwind, Memphis, TN 38125",
                phone: "(901) 748-0405",
                type: "Private",
                coordinates: [-89.8167, 35.0667],
                slug: "tpc-southwind"
            },
            {
                name: "Troubadour Golf & Field Club",
                address: "4740 Shallowford Rd, Nashville, TN 37205",
                phone: "(615) 383-7179",
                type: "Private",
                coordinates: [-86.8667, 36.1000],
                slug: "troubadour-golf-field-club"
            },
            {
                name: "Two Rivers Golf Course",
                address: "3140 McGavock Pike, Nashville, TN 37214",
                phone: "(615) 889-2675",
                type: "Municipal",
                coordinates: [-86.6500, 36.2167],
                slug: "two-rivers-golf-course"
            },
            {
                name: "Vanderbilt Legends Club",
                address: "1500 Legends Club Ln, Franklin, TN 37069",
                phone: "(615) 791-8100",
                type: "Private",
                coordinates: [-86.8167, 35.9500],
                slug: "vanderbilt-legends-club"
            },
            {
                name: "Warriors Path State Park Golf Course",
                address: "490 Hemlock Rd, Kingsport, TN 37663",
                phone: "(423) 323-4990",
                type: "Public",
                coordinates: [-82.4833, 36.5167],
                slug: "warriors-path-state-park-golf-course"
            },
            {
                name: "White Plains Golf Course",
                address: "2886 White Plains Rd, White Plains, TN 37890",
                phone: "(423) 794-7931",
                type: "Public",
                coordinates: [-83.2833, 36.4667],
                slug: "white-plains-golf-course"
            },
            {
                name: "Whittle Springs Golf Course",
                address: "1200 Whittle Springs Rd, Knoxville, TN 37917",
                phone: "(865) 525-1022",
                type: "Municipal",
                coordinates: [-83.9500, 36.0167],
                slug: "whittle-springs-golf-course"
            },
            {
                name: "Williams Creek Golf Course",
                address: "2901 Williams Creek Dr, Knoxville, TN 37918",
                phone: "(865) 688-9140",
                type: "Municipal",
                coordinates: [-83.9833, 35.9833],
                slug: "williams-creek-golf-course"
            },
            {
                name: "Willow Creek Golf Club",
                address: "106 Augusta Dr, Knoxville, TN 37922",
                phone: "(865) 675-0100",
                type: "Public",
                coordinates: [-84.0333, 35.9333],
                slug: "willow-creek-golf-club"
            },
            {
                name: "Windtree Golf Course",
                address: "200 Windtree Golf Dr, Mount Juliet, TN 37122",
                phone: "(615) 754-4653",
                type: "Public",
                coordinates: [-86.4833, 36.2167],
                slug: "windtree-golf-course"
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
                        <a href="/courses/${course.slug}" target="_blank" rel="noopener noreferrer" class="popup-link">View Course Details</a>
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
        }
    </script>
</body>
</html>