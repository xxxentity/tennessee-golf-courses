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
            
            // Golf course data with complete information - ALL 92 COURSES WITH ACCURATE COORDINATES
            const golfCourses = [
            {
                name: "Avalon Golf & Country Club",
                address: "1299 Oak Chase Blvd, Lenoir City, TN 37772",
                phone: "(865) 986-4653",
                type: "Semi-Private",
                slug: "avalon-golf-country-club",
                coordinates: [-84.308721, 35.797543]
            },
            {
                name: "Bear Trace at Tims Ford",
                address: "570 Bear Trace Drive, Winchester, TN 37398",
                phone: "(931) 962-4653",
                type: "Public",
                slug: "bear-trace-at-tims-ford",
                coordinates: [-86.111389, 35.206111]
            },
            {
                name: "Bear Trace at Cumberland Mountain",
                address: "407 Wild Plum Lane, Crossville, TN 38572",
                phone: "(931) 707-1640",
                type: "State Park",
                slug: "bear-trace-cumberland-mountain",
                coordinates: [-85.01106, 35.89133]
            },
            {
                name: "Bear Trace at Harrison Bay",
                address: "8919 Harrison Bay Road, Harrison, TN 37341",
                phone: "(423) 326-0885",
                type: "Public",
                slug: "bear-trace-harrison-bay",
                coordinates: [-85.1347, 35.1195]
            },
            {
                name: "Belle Acres Golf Course",
                address: "901 E Broad St, Cookeville, TN",
                phone: "(931) 310-0645",
                type: "Public",
                slug: "belle-acres-golf-course",
                coordinates: [-85.48702, 36.16332]
            },
            {
                name: "Belle Meade Country Club",
                address: "815 Belle Meade Blvd, Nashville, TN 37205",
                phone: "(615) 385-0150",
                type: "Private",
                slug: "belle-meade-country-club",
                coordinates: [-86.86211, 36.09526]
            },
            {
                name: "Big Creek Golf Club",
                address: "6195 Woodstock Cuba Rd, Millington, TN 38053",
                phone: "(901) 353-1654",
                type: "Semi-Private",
                slug: "big-creek-golf-club",
                coordinates: [-89.874722, 35.344167]
            },
            {
                name: "Blackthorn Club",
                address: "1501 Ridges Club Drive, Jonesborough, TN 37659",
                phone: "",
                type: "Private",
                slug: "blackthorn-club",
                coordinates: [-82.473611, 36.294444]
            },
            {
                name: "Bluegrass Yacht & Country Club",
                address: "550 Johnny Cash Parkway, Hendersonville, TN 37075",
                phone: "(615) 824-6566",
                type: "Private",
                slug: "bluegrass-yacht-country-club",
                coordinates: [-86.620278, 36.304444]
            },
            {
                name: "Brainerd Golf Course",
                address: "5203 Old Mission Road, Chattanooga, TN 37411",
                phone: "(423) 855-2692",
                type: "Municipal",
                slug: "brainerd-golf-course",
                coordinates: [-85.22019, 35.01866]
            },
            {
                name: "Brown Acres Golf Course",
                address: "406 Brown Road, Chattanooga, TN 37421",
                phone: "(423) 855-2680",
                type: "Municipal",
                slug: "brown-acres-golf-course",
                coordinates: [-85.19782, 35.01204]
            },
            {
                name: "Cedar Crest Golf Club",
                address: "7972 Mona Rd, Murfreesboro, TN 37129",
                phone: "",
                type: "Public",
                slug: "cedar-crest-golf-club",
                coordinates: [-86.41485, 36.00529]
            },
            {
                name: "Chattanooga Golf & Country Club",
                address: "1511 Riverview Road, Chattanooga, TN 37405",
                phone: "",
                type: "Private",
                slug: "chattanooga-golf-country-club",
                coordinates: [-85.285962, 35.073018]
            },
            {
                name: "Cheekwood Golf Club",
                address: "285 Spencer Creek Rd, Franklin, TN 37069",
                phone: "(615) 794-8223",
                type: "Public",
                slug: "cheekwood-golf-club",
                coordinates: [-86.87283, 35.95067]
            },
            {
                name: "Cherokee Country Club",
                address: "5138 Lyons View Pike, Knoxville, TN 37919",
                phone: "(865) 584-4637",
                type: "Private",
                slug: "cherokee-country-club",
                coordinates: [-83.98489, 35.93483]
            },
            {
                name: "Clarksville Country Club",
                address: "334 Fairway Dr, Clarksville, TN 37043",
                phone: "",
                type: "Private",
                slug: "clarksville-country-club",
                coordinates: [-87.35887, 36.52776]
            },
            {
                name: "Colonial Country Club",
                address: "2736 Countrywood Parkway, Cordova, TN 38016",
                phone: "",
                type: "Private",
                slug: "colonial-country-club",
                coordinates: [-89.77620, 35.15565]
            },
            {
                name: "Council Fire Golf Club",
                address: "1400 Council Fire Drive, Chattanooga, TN 37421",
                phone: "",
                type: "Private",
                slug: "council-fire-golf-club",
                coordinates: [-85.165625, 34.986288]
            },
            {
                name: "Cumberland Cove Golf Course",
                address: "16941 Highway 70 N, Monterey, TN 38574",
                phone: "(931) 839-3313",
                type: "Public",
                slug: "cumberland-cove-golf-course",
                coordinates: [-85.268889, 36.148889]
            },
            {
                name: "Dead Horse Lake Golf Course",
                address: "3016 Gravelly Hills Road, Louisville, TN 37777",
                phone: "(865) 693-5270",
                type: "Public",
                slug: "dead-horse-lake-golf-course",
                coordinates: [-83.918889, 35.831944]
            },
            {
                name: "Druid Hills Golf Club",
                address: "433 Lakeview Drive, Fairfield Glade, TN 38558",
                phone: "(931) 456-2864",
                type: "Resort",
                slug: "druid-hills-golf-club",
                coordinates: [-84.88907, 36.00125]
            },
            {
                name: "Eagle's Landing Golf Club",
                address: "1556 Old Knoxville Highway, Sevierville, TN 37876",
                phone: "(865) 429-4223",
                type: "Public/Municipal",
                slug: "eagles-landing-golf-club",
                coordinates: [-83.59206, 35.90818]
            },
            {
                name: "Egwani Farms Golf Course",
                address: "3920 S Singleton Station Road, Rockford, TN 37853",
                phone: "(865) 970-7132",
                type: "Public",
                slug: "egwani-farms-golf-course",
                coordinates: [-83.95204, 35.83056]
            },
            {
                name: "Fall Creek Falls State Park Golf Course",
                address: "626 Golf Course Road, Spencer, TN 38585",
                phone: "(423) 881-5706",
                type: "State Park",
                slug: "fall-creek-falls-state-park-golf-course",
                coordinates: [-85.467222, 35.746111]
            },
            {
                name: "Forrest Crossing Golf Course",
                address: "750 Riverview Dr, Franklin, TN 37064",
                phone: "",
                type: "Semi-Private",
                slug: "forrest-crossing-golf-course",
                coordinates: [-86.84227, 35.89344]
            },
            {
                name: "Fox Den Country Club",
                address: "6000 Fox Den Drive, Knoxville, TN 37918",
                phone: "(865) 922-3035",
                type: "Private",
                slug: "fox-den-country-club",
                coordinates: [-83.915278, 35.996111]
            },
            {
                name: "Gaylord Springs Golf Links",
                address: "18 Springs Blvd, Nashville, TN 37214",
                phone: "(615) 458-1730",
                type: "Resort Public",
                slug: "gaylord-springs-golf-links",
                coordinates: [-86.677222, 36.208333]
            },
            {
                name: "Greystone Golf Course",
                address: "1 Greystone Drive, Dickson, TN 37055",
                phone: "(615) 441-8888",
                type: "Public",
                slug: "greystone-golf-course",
                coordinates: [-87.388889, 36.077222]
            },
            {
                name: "Harpeth Hills Golf Course",
                address: "2424 Old Hickory Blvd, Nashville, TN 37221",
                phone: "(615) 862-8493",
                type: "Municipal",
                slug: "harpeth-hills-golf-course",
                coordinates: [-86.88270, 36.05643]
            },
            {
                name: "Henry Horton State Park Golf Course",
                address: "4358 Nashville Highway, Chapel Hill, TN 37034",
                phone: "(931) 364-2319",
                type: "State Park",
                slug: "henry-horton-state-park-golf-course",
                coordinates: [-86.69965, 35.59916]
            },
            {
                name: "Hermitage Golf Course",
                address: "3939 Old Hickory Blvd, Old Hickory, TN 37138",
                phone: "(615) 847-4001",
                type: "Public",
                slug: "hermitage-golf-course",
                coordinates: [-86.63997, 36.22812]
            },
            {
                name: "Hillwood Country Club",
                address: "6201 Hickory Valley Road, Nashville, TN 37205",
                phone: "(615) 352-6591",
                type: "Private",
                slug: "hillwood-country-club",
                coordinates: [-86.86980, 36.12134]
            },
            {
                name: "Holston Hills Country Club",
                address: "5801 Lyons View Pike, Knoxville, TN 37919",
                phone: "(865) 584-6230",
                type: "Private",
                slug: "holston-hills-country-club",
                coordinates: [-83.99170, 35.92751]
            },
            {
                name: "Honky Tonk National Golf Course",
                address: "235 Harbour Greens Place, Sparta, TN 38583",
                phone: "",
                type: "Private",
                slug: "honky-tonk-national-golf-course",
                coordinates: [-85.46413, 35.92589]
            },
            {
                name: "Island Pointe Golf Club",
                address: "9610 Kodak Road, Kodak, TN 37764",
                phone: "",
                type: "Public",
                slug: "island-pointe-golf-club",
                coordinates: [-83.64381, 35.97019]
            },
            {
                name: "Jackson Country Club",
                address: "31 Jackson Country Club Ln, Jackson, TN 38305",
                phone: "",
                type: "Private",
                slug: "jackson-country-club",
                coordinates: [-88.81774, 35.61444]
            },
            {
                name: "Lake Tansi Golf Course",
                address: "2476 Dunbar Road, Crossville, TN 38572",
                phone: "(931) 788-3301",
                type: "Public",
                slug: "lake-tansi-golf-course",
                coordinates: [-85.048611, 35.944444]
            },
            {
                name: "Lambert Acres Golf Club",
                address: "3402 Tuckaleechee Pike, Maryville, TN 37803",
                phone: "(865) 982-9838",
                type: "Public",
                slug: "lambert-acres-golf-club",
                coordinates: [-83.92451, 35.74997]
            },
            {
                name: "Laurel Valley Country Club",
                address: "702 Country Club Drive, Townsend, TN 37882",
                phone: "(865) 738-3134",
                type: "Public",
                slug: "laurel-valley-country-club",
                coordinates: [-83.754167, 35.676944]
            },
            {
                name: "Lookout Mountain Club",
                address: "1201 Fleetwood Drive, Lookout Mountain, GA 30750",
                phone: "(706) 820-1551",
                type: "Private",
                slug: "lookout-mountain-club",
                coordinates: [-85.3480, 34.9789]
            },
            {
                name: "McCabe Golf Course",
                address: "100 46th Avenue North, Nashville, TN 37209",
                phone: "(615) 862-8491",
                type: "Municipal",
                slug: "mccabe-golf-course",
                coordinates: [-86.84533, 36.13875]
            },
            {
                name: "Mirimichi Golf Course",
                address: "6195 Woodstock Cuba Rd, Millington, TN 38053",
                phone: "(901) 259-3800",
                type: "Public",
                slug: "mirimichi-golf-course",
                coordinates: [-89.874722, 35.344167]
            },
            {
                name: "Moccasin Bend Golf Course",
                address: "381 Moccasin Bend Rd, Chattanooga, TN 37405",
                phone: "(423) 267-3585",
                type: "Public",
                slug: "moccasin-bend-golf-course",
                coordinates: [-85.33341, 35.04597]
            },
            {
                name: "Montgomery Bell State Park Golf Course",
                address: "1020 Jackson Hill Road, Burns, TN 37029",
                phone: "",
                type: "State Park",
                slug: "montgomery-bell-state-park-golf-course",
                coordinates: [-87.311944, 36.058333]
            },
            {
                name: "Nashville Golf & Athletic Club",
                address: "1101 Golf Club Lane, Belle Meade, TN 37205",
                phone: "(615) 356-8580",
                type: "Private Club",
                slug: "nashville-golf-athletic-club",
                coordinates: [-86.866944, 36.103889]
            },
            {
                name: "Nashville National Golf Links",
                address: "1725 New Hope Road, Nashville, TN 37080",
                phone: "(615) 876-4653",
                type: "Semi-Private",
                slug: "nashville-national-golf-links",
                coordinates: [-86.58177, 36.18286]
            },
            {
                name: "Old Fort Golf Course",
                address: "1028 Golf Lane, Murfreesboro, TN 37129",
                phone: "(615) 896-2448",
                type: "Municipal",
                slug: "old-fort-golf-course",
                coordinates: [-86.41077, 35.85126]
            },
            {
                name: "Old Hickory Country Club",
                address: "1000 Old Hickory Blvd, Old Hickory, TN 37138",
                phone: "(615) 847-3966",
                type: "Private",
                slug: "old-hickory-country-club",
                coordinates: [-86.65257, 36.26653]
            },
            {
                name: "Overton Park 9",
                address: "2080 Poplar Ave, Memphis, TN 38104",
                phone: "(901) 305-2604",
                type: "Municipal Public",
                slug: "overton-park-9",
                coordinates: [-89.99145, 35.14196]
            },
            {
                name: "Paris Landing State Park Golf Course",
                address: "285 Golf Course Lane, Buchanan, TN 38222",
                phone: "(731) 642-2555",
                type: "Public",
                slug: "paris-landing-state-park-golf-course",
                coordinates: [-88.196944, 36.491667]
            },
            {
                name: "Percy Warner Golf Course",
                address: "1221 Forrest Park Dr, Nashville, TN 37205",
                phone: "(615) 862-8492",
                type: "Municipal",
                slug: "percy-warner-golf-course",
                coordinates: [-86.87222, 36.08344]
            },
            {
                name: "Pickwick Landing State Park Golf Course",
                address: "60 Winfield Dunn Lane, Pickwick Dam, TN 38365",
                phone: "(731) 689-3149",
                type: "Public",
                slug: "pickwick-landing-state-park",
                coordinates: [-88.246111, 35.058889]
            },
            {
                name: "Pine Oaks Golf Course",
                address: "1709 Buffalo Road, Johnson City, TN 37604",
                phone: "(423) 434-6250",
                type: "Municipal",
                slug: "pine-oaks-golf-course",
                coordinates: [-82.34772, 36.30041]
            },
            {
                name: "Richland Country Club",
                address: "116 Richland Country Club Drive, Nashville, TN 37205",
                phone: "(615) 352-4444",
                type: "Private Club",
                slug: "richland-country-club",
                coordinates: [-86.870556, 36.091667]
            },
            {
                name: "Ross Creek Landing Golf Course",
                address: "110 Airport Rd, Clifton, TN 38425",
                phone: "(931) 676-3174",
                type: "Public",
                slug: "ross-creek-landing-golf-course",
                coordinates: [-87.988889, 35.388889]
            },
            {
                name: "Sevierville Golf Club",
                address: "1444 Old Knoxville Highway, Sevierville, TN 37876",
                phone: "(865) 429-4223",
                type: "Public/Municipal",
                slug: "sevierville-golf-club",
                coordinates: [-83.58705, 35.89824]
            },
            {
                name: "Signal Mountain Golf & Country Club",
                address: "809 James Boulevard, Signal Mountain, TN 37377",
                phone: "(423) 886-5767",
                type: "Private Club",
                slug: "signal-mountain-golf-country-club",
                coordinates: [-85.34448, 35.12781]
            },
            {
                name: "Southern Hills Golf & Country Club",
                address: "4770 Ben Jared Rd, Cookeville, TN 38506",
                phone: "(931) 432-5149",
                type: "Public",
                slug: "southern-hills-golf-country-club",
                coordinates: [-85.501944, 36.162778]
            },
            {
                name: "Springhouse Golf Club",
                address: "18 Springhouse Ln, Nashville, TN 37214",
                phone: "(615) 452-1730",
                type: "Public",
                slug: "springhouse-golf-club",
                coordinates: [-86.68045, 36.21909]
            },
            {
                name: "Stonehenge Golf Club",
                address: "222 Fairfield Blvd, Fairfield Glade, TN 38558",
                phone: "",
                type: "Resort/Public",
                slug: "stonehenge-golf-club",
                coordinates: [-84.88010, 36.00925]
            },
            {
                name: "Stones River Country Club",
                address: "1830 NW Broad Street, Murfreesboro, TN 37129",
                phone: "(615) 893-6420",
                type: "Private",
                slug: "stones-river-country-club",
                coordinates: [-86.40386, 35.85607]
            },
            {
                name: "Sweetens Cove Golf Club",
                address: "2040 Sweetens Cove Road, South Pittsburg, TN 37380",
                phone: "(423) 280-9692",
                type: "Public",
                slug: "sweetens-cove-golf-club",
                coordinates: [-85.72304, 35.05254]
            },
            {
                name: "Tanasi Golf Course",
                address: "450 Clubhouse Point, Loudon, TN 37774",
                phone: "",
                type: "Resort/Private",
                slug: "tanasi-golf-course",
                coordinates: [-84.335278, 35.732222]
            },
            {
                name: "Ted Rhodes Golf Course",
                address: "1901 Ed Temple Blvd, Nashville, TN 37208",
                phone: "(615) 862-8463",
                type: "Municipal",
                slug: "ted-rhodes-golf-course",
                coordinates: [-86.82412, 36.17531]
            },
            {
                name: "Temple Hills Country Club",
                address: "6376 Temple Road, Franklin, TN 37069",
                phone: "(615) 646-4785",
                type: "Private",
                slug: "temple-hills-country-club",
                coordinates: [-86.868333, 35.925556]
            },
            {
                name: "Tennessee Grasslands Golf & CC - Fairvue",
                address: "981 Plantation Boulevard, Gallatin, TN 37066",
                phone: "",
                type: "Private",
                slug: "tennessee-grasslands-fairvue",
                coordinates: [-86.44759, 36.38830]
            },
            {
                name: "Tennessee Grasslands Golf & CC - Foxland",
                address: "1445 Foxland Boulevard, Gallatin, TN 37066",
                phone: "",
                type: "Private",
                slug: "tennessee-grasslands-foxland",
                coordinates: [-86.44759, 36.38830]
            },
            {
                name: "Tennessee National Golf Club",
                address: "8301 Tennessee National Dr, Loudon, TN 37774",
                phone: "",
                type: "Semi-Private",
                slug: "tennessee-national-golf-club",
                coordinates: [-83.95363, 35.82506]
            },
            {
                name: "The Club at Five Oaks",
                address: "621 Five Oaks Blvd, Lebanon, TN 37087",
                phone: "(615) 444-2784",
                type: "Private",
                slug: "the-club-at-five-oaks",
                coordinates: [-86.37271, 36.25101]
            },
            {
                name: "The Club at Gettysvue",
                address: "9317 Linksvue Drive, Knoxville, TN 37922",
                phone: "",
                type: "Private Club",
                slug: "the-club-at-gettysvue",
                coordinates: [-84.08193, 35.88971]
            },
            {
                name: "The Golf Club of Tennessee",
                address: "1 Sneed Road West, Kingston Springs, TN 37082",
                phone: "(615) 370-4653",
                type: "Private",
                slug: "the-golf-club-of-tennessee",
                coordinates: [-87.108889, 36.113333]
            },
            {
                name: "The Governors Club",
                address: "1500 Governors Club Drive, Brentwood, TN 37027",
                phone: "(615) 370-0707",
                type: "Private Club",
                slug: "the-governors-club",
                coordinates: [-86.787222, 35.998889]
            },
            {
                name: "The Grove",
                address: "7165 Nolensville Road, College Grove, TN 37046",
                phone: "",
                type: "Private Club Community",
                slug: "the-grove",
                coordinates: [-86.901944, 35.853889]
            },
            {
                name: "The Honors Course",
                address: "9603 Lee Highway, Ooltewah, TN 37363",
                phone: "",
                type: "Private",
                slug: "the-honors-course",
                coordinates: [-85.046365, 35.087642]
            },
            {
                name: "The Legacy Golf Course",
                address: "100 Ray Floyd Drive, Springfield, TN 37172",
                phone: "(615) 384-4653",
                type: "Public",
                slug: "the-legacy-golf-course",
                coordinates: [-86.885278, 36.509167]
            },
            {
                name: "The Links at Audubon",
                address: "4160 Park Avenue, Memphis, TN 38117",
                phone: "(901) 683-6941",
                type: "Municipal",
                slug: "the-links-at-audubon",
                coordinates: [-89.92145, 35.10967]
            },
            {
                name: "The Links at Fox Meadows",
                address: "3064 Clarke Road, Memphis, TN 38115",
                phone: "(901) 636-0932",
                type: "Municipal",
                slug: "the-links-at-fox-meadows",
                coordinates: [-89.87283, 35.06470]
            },
            {
                name: "The Links at Galloway",
                address: "3815 Walnut Grove Road, Memphis, TN 38111",
                phone: "(901) 362-0232",
                type: "Municipal",
                slug: "the-links-at-galloway",
                coordinates: [-89.93614, 35.12908]
            },
            {
                name: "The Links at Kahite",
                address: "100 Kahite Trail, Vonore, TN 37885",
                phone: "",
                type: "Resort/Private",
                slug: "the-links-at-kahite",
                coordinates: [-84.237222, 35.606111]
            },
            {
                name: "The Links at Whitehaven",
                address: "750 E Holmes Road, Memphis, TN 38109",
                phone: "(901) 396-1608",
                type: "Municipal",
                slug: "the-links-at-whitehaven",
                coordinates: [-90.03993, 35.01085]
            },
            {
                name: "Three Ridges Golf Course",
                address: "6601 Ridgewood Drive, Knoxville, TN 37921",
                phone: "(865) 525-4653",
                type: "Municipal Public",
                slug: "three-ridges-golf-course",
                coordinates: [-83.990278, 35.963889]
            },
            {
                name: "Toqua Golf Course",
                address: "200 Toqua Club Way, Loudon, TN 37774",
                phone: "",
                type: "Resort/Public",
                slug: "toqua-golf-course",
                coordinates: [-84.25925, 35.67931]
            },
            {
                name: "TPC Southwind",
                address: "3325 Club at Southwind, Memphis, TN 38125",
                phone: "(901) 748-0330",
                type: "Private",
                slug: "tpc-southwind",
                coordinates: [-89.78579, 35.05459]
            },
            {
                name: "Troubadour Golf & Field Club",
                address: "7230 Harlow Dr, College Grove, TN 37046",
                phone: "(615) 436-6850",
                type: "Private",
                slug: "troubadour-golf-field-club",
                coordinates: [-86.901944, 35.853889]
            },
            {
                name: "Two Rivers Golf Course",
                address: "3140 McGavock Pike, Nashville, TN 37214",
                phone: "(615) 889-2675",
                type: "Municipal",
                slug: "two-rivers-golf-course",
                coordinates: [-86.67589, 36.18622]
            },
            {
                name: "Vanderbilt Legends Club",
                address: "1500 Legends Club Lane, Franklin, TN 37069",
                phone: "(615) 791-8100",
                type: "Private",
                slug: "vanderbilt-legends-club",
                coordinates: [-86.84892, 35.95011]
            },
            {
                name: "Warrior's Path State Park Golf Course",
                address: "490 Hemlock Road, Kingsport, TN 37663",
                phone: "(423) 239-8531",
                type: "State Park",
                slug: "warriors-path-state-park-golf-course",
                coordinates: [-82.49697, 36.49784]
            },
            {
                name: "White Plains Golf Course",
                address: "4000 Plantation Dr, Cookeville, TN 38506",
                phone: "(931) 526-3306",
                type: "Semi-Private",
                slug: "white-plains-golf-course",
                coordinates: [-85.42979, 36.18251]
            },
            {
                name: "Whittle Springs Golf Course",
                address: "3113 Valley View Dr, Knoxville, TN 37917",
                phone: "",
                type: "Municipal Public",
                slug: "whittle-springs-golf-course",
                coordinates: [-83.91252, 36.01673]
            },
            {
                name: "Williams Creek Golf Course",
                address: "2351 Dandridge Ave, Knoxville, TN 37915",
                phone: "(865) 546-5828",
                type: "Public",
                slug: "williams-creek-golf-course",
                coordinates: [-83.88508, 35.97125]
            },
            {
                name: "Willow Creek Golf Club",
                address: "12003 Kingston Pike, Knoxville, TN 37934",
                phone: "(865) 675-0100",
                type: "Public",
                slug: "willow-creek-golf-club",
                coordinates: [-83.94678, 35.95154]
            },
            {
                name: "Windtree Golf Course",
                address: "810 Nonaville Rd, Mount Juliet, TN 37122",
                phone: "(615) 754-4653 (Disconnected)",
                type: "",
                slug: "windtree-golf-course",
                coordinates: [-86.52802, 36.24273]
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