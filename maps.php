<?php
require_once 'includes/init.php';
require_once 'includes/seo.php';

SEO::setupMapsPage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php echo SEO::generateMetaTags(); ?>
    <?php echo SEO::generateNewsKeywords(['golf course maps', 'Tennessee', 'interactive map', 'location finder', 'Nashville', 'Memphis', 'courses near me']); ?>
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <?php include 'includes/favicon.php'; ?>

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

        /* Google Maps InfoWindow styles */
        .gm-popup {
            font-family: 'Inter', sans-serif;
            min-width: 260px;
            padding: 4px;
        }

        .gm-popup-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c5234;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .gm-popup-type {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .gm-popup-address {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .gm-popup-phone {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .gm-popup-buttons {
            display: flex;
            gap: 8px;
        }

        .gm-popup-btn {
            flex: 1;
            padding: 8px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s ease;
        }

        .gm-popup-btn-green {
            background: #2c5234;
            color: white;
        }

        .gm-popup-btn-green:hover {
            background: #1a3a20;
        }

        .gm-popup-btn-blue {
            background: #1a73e8;
            color: white;
        }

        .gm-popup-btn-blue:hover {
            background: #1557b0;
        }

        @media (max-width: 768px) {
            .maps-hero h1 { font-size: 2.5rem; }
            .maps-hero p { font-size: 1.2rem; }
        }
    </style>

    <?php echo SEO::generateStructuredData(); ?>
</head>

<body>
    <div class="maps-page">
        <?php include 'includes/navigation.php'; ?>

        <div class="maps-hero">
            <h1>Tennessee Golf Course Maps</h1>
            <p>Discover golf courses across Tennessee with interactive maps and regional guides</p>
        </div>

        <div class="maps-content">
            <!-- Search and Filter Controls -->
            <div class="map-controls" style="background: white; padding: 1.5rem; border-radius: 15px; box-shadow: var(--shadow-medium); margin-bottom: 2rem;">
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem; align-items: center;">
                    <div style="position: relative;">
                        <input type="text" id="courseSearch" placeholder="Search golf courses..."
                               style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 2px solid #e2e8f0; border-radius: 25px; font-size: 1rem; transition: border-color 0.3s ease;">
                        <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                    </div>
                    <div class="filter-buttons" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <button class="filter-btn active" data-filter="all" style="padding: 0.5rem 1rem; border: none; border-radius: 15px; background: var(--primary-color); color: white; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">All</button>
                        <button class="filter-btn" data-filter="Public" style="padding: 0.5rem 1rem; border: 2px solid #4CAF50; border-radius: 15px; background: white; color: #4CAF50; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">Public</button>
                        <button class="filter-btn" data-filter="Municipal" style="padding: 0.5rem 1rem; border: 2px solid #2196F3; border-radius: 15px; background: white; color: #2196F3; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">Municipal</button>
                        <button class="filter-btn" data-filter="Private" style="padding: 0.5rem 1rem; border: 2px solid #FF9800; border-radius: 15px; background: white; color: #FF9800; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">Private</button>
                        <button class="filter-btn" data-filter="Semi-Private" style="padding: 0.5rem 1rem; border: 2px solid #9C27B0; border-radius: 15px; background: white; color: #9C27B0; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">Semi-Private</button>
                    </div>
                </div>
                <div id="resultsCounter" style="margin-top: 1rem; text-align: center; color: var(--text-gray); font-size: 0.9rem;"></div>
            </div>

            <!-- Map Container -->
            <div class="map-container">
                <div id="tennessee-golf-map"></div>
            </div>

            <!-- Legend -->
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

    <?php include 'includes/footer.php'; ?>

    <script>
        const courseColors = {
            'Public': '#4CAF50',
            'Municipal': '#2196F3',
            'Private': '#FF9800',
            'Semi-Private': '#9C27B0'
        };

        function getColor(type) {
            if (!type) return '#4CAF50';
            if (type.includes('Municipal')) return '#2196F3';
            if (type.includes('Private') && !type.includes('Semi')) return '#FF9800';
            if (type.includes('Semi-Private')) return '#9C27B0';
            return '#4CAF50';
        }

        const golfCourses = [
            { name: "Avalon Golf & Country Club", address: "1299 Oak Chase Blvd, Lenoir City, TN 37772", phone: "(865) 986-4653", type: "Semi-Private", slug: "avalon-golf-country-club", coordinates: [-84.257511, 35.849731] },
            { name: "Bear Trace at Tims Ford", address: "570 Bear Trace Drive, Winchester, TN 37398", phone: "(931) 962-4653", type: "Public", slug: "bear-trace-at-tims-ford", coordinates: [-86.112207, 35.185916] },
            { name: "Bear Trace at Cumberland Mountain", address: "407 Wild Plum Lane, Crossville, TN 38572", phone: "(931) 707-1640", type: "Public", slug: "bear-trace-cumberland-mountain", coordinates: [-85.007849, 35.891681] },
            { name: "Bear Trace at Harrison Bay", address: "8919 Harrison Bay Road, Harrison, TN 37341", phone: "(423) 326-0885", type: "Public", slug: "bear-trace-harrison-bay", coordinates: [-85.108348, 35.197956] },
            { name: "Belle Acres Golf Course", address: "901 E Broad St, Cookeville, TN", phone: "(931) 310-0645", type: "Public", slug: "belle-acres-golf-course", coordinates: [-85.483316, 36.162551] },
            { name: "Belle Meade Country Club", address: "815 Belle Meade Blvd, Nashville, TN 37205", phone: "(615) 385-0150", type: "Private", slug: "belle-meade-country-club", coordinates: [-86.858119, 36.094694] },
            { name: "Big Creek Golf Club", address: "6195 Woodstock Cuba Rd, Millington, TN 38053", phone: "(901) 353-1654", type: "Semi-Private", slug: "big-creek-golf-club", coordinates: [-89.995974, 35.292701] },
            { name: "Blackthorn Club", address: "1501 Ridges Club Drive, Jonesborough, TN 37659", phone: "", type: "Private", slug: "blackthorn-club", coordinates: [-82.465418, 36.34614] },
            { name: "Bluegrass Yacht & Country Club", address: "550 Johnny Cash Parkway, Hendersonville, TN 37075", phone: "(615) 824-6566", type: "Private", slug: "bluegrass-yacht-country-club", coordinates: [-86.579186, 36.324052] },
            { name: "Brainerd Golf Course", address: "5203 Old Mission Road, Chattanooga, TN 37411", phone: "(423) 855-2692", type: "Municipal", slug: "brainerd-golf-course", coordinates: [-85.220013, 35.014851] },
            { name: "Brown Acres Golf Course", address: "406 Brown Road, Chattanooga, TN 37421", phone: "(423) 855-2680", type: "Municipal", slug: "brown-acres-golf-course", coordinates: [-85.196529, 35.008026] },
            { name: "Cedar Crest Golf Club", address: "7972 Mona Rd, Murfreesboro, TN 37129", phone: "", type: "Public", slug: "cedar-crest-golf-club", coordinates: [-86.4162, 36.006086] },
            { name: "Chattanooga Golf & Country Club", address: "1511 Riverview Road, Chattanooga, TN 37405", phone: "", type: "Private", slug: "chattanooga-golf-country-club", coordinates: [-85.281947, 35.064939] },
            { name: "Cheekwood Golf Club", address: "285 Spencer Creek Rd, Franklin, TN 37069", phone: "(615) 794-8223", type: "Public", slug: "cheekwood-golf-club", coordinates: [-86.866425, 35.949887] },
            { name: "Cherokee Country Club", address: "5138 Lyons View Pike, Knoxville, TN 37919", phone: "(865) 584-4637", type: "Private", slug: "cherokee-country-club", coordinates: [-83.985895, 35.93352] },
            { name: "Chickasaw Country Club", address: "3395 Galloway Ave, Memphis, TN 38122", phone: "(901) 323-6216", type: "Private", slug: "chickasaw-country-club", coordinates: [-89.949843, 35.144744] },
            { name: "Chickasaw Golf Course", address: "9555 State Route 100 W, Henderson, TN 38340", phone: "(731) 989-3111", type: "Public", slug: "chickasaw-golf-course", coordinates: [-88.790093, 35.388363] },
            { name: "Clarksville Country Club", address: "334 Fairway Dr, Clarksville, TN 37043", phone: "", type: "Private", slug: "clarksville-country-club", coordinates: [-87.29048, 36.527804] },
            { name: "Colonial Country Club", address: "2736 Countrywood Parkway, Cordova, TN 38016", phone: "", type: "Private", slug: "colonial-country-club", coordinates: [-89.778774, 35.196538] },
            { name: "Council Fire Golf Club", address: "100 Council Fire Dr, Chattanooga, TN 37421", phone: "", type: "Private", slug: "council-fire-golf-club", coordinates: [-85.173458, 34.986263] },
            { name: "Cumberland Cove Golf Course", address: "16941 Highway 70 N, Monterey, TN 38574", phone: "(931) 839-3313", type: "Public", slug: "cumberland-cove-golf-course", coordinates: [-85.227605, 36.083534] },
            { name: "Dead Horse Lake Golf Course", address: "3016 Gravelly Hills Road, Louisville, TN 37777", phone: "(865) 693-5270", type: "Public", slug: "dead-horse-lake-golf-course", coordinates: [-84.124378, 35.804098] },
            { name: "Dorchester Golf Club", address: "576 Westchester Dr, Fairfield Glade, TN 38558", phone: "(931) 456-2864", type: "Semi-Private", slug: "dorchester-golf-club", coordinates: [-84.866624, 35.970002] },
            { name: "Druid Hills Golf Club", address: "433 Lakeview Drive, Fairfield Glade, TN 38558", phone: "(931) 456-2864", type: "Public", slug: "druid-hills-golf-club", coordinates: [-84.887123, 36.000731] },
            { name: "Eagle's Landing Golf Club", address: "1556 Old Knoxville Highway, Sevierville, TN 37876", phone: "(865) 429-4223", type: "Public", slug: "eagles-landing-golf-club", coordinates: [-83.586617, 35.903555] },
            { name: "Egwani Farms Golf Course", address: "3920 S Singleton Station Road, Rockford, TN 37853", phone: "(865) 970-7132", type: "Public", slug: "egwani-farms-golf-course", coordinates: [-83.95128, 35.832396] },
            { name: "Fall Creek Falls State Park Golf Course", address: "626 Golf Course Road, Spencer, TN 38585", phone: "(423) 881-5706", type: "Public", slug: "fall-creek-falls-state-park-golf-course", coordinates: [-85.357059, 35.646524] },
            { name: "Forrest Crossing Golf Course", address: "750 Riverview Dr, Franklin, TN 37064", phone: "", type: "Semi-Private", slug: "forrest-crossing-golf-course", coordinates: [-86.841485, 35.892212] },
            { name: "Fox Den Country Club", address: "6000 Fox Den Drive, Knoxville, TN 37918", phone: "(865) 922-3035", type: "Private", slug: "fox-den-country-club", coordinates: [-84.187834, 35.874645] },
            { name: "Glen Eagle Golf Course", address: "6168 Attu Street extended, Millington, TN 38054", phone: "(901) 874-5168", type: "Public", slug: "glen-eagle-golf-course", coordinates: [-89.857429, 35.341065] },
            { name: "Gaylord Springs Golf Links", address: "18 Springhouse Ln, Nashville, TN 37214", phone: "(615) 458-1730", type: "Public", slug: "gaylord-springs-golf-links", coordinates: [-86.679994, 36.218551] },
            { name: "Greystone Golf Course", address: "2555 US-70, Dickson, TN 37055", phone: "(615) 441-8888", type: "Public", slug: "greystone-golf-course", coordinates: [-87.337635, 36.091129] },
            { name: "Harpeth Hills Golf Course", address: "2424 Old Hickory Blvd, Nashville, TN 37221", phone: "(615) 862-8493", type: "Municipal", slug: "harpeth-hills-golf-course", coordinates: [-86.883498, 36.053059] },
            { name: "Heatherhurst Brae Golf Course", address: "421 Stonehenge Dr, Crossville, TN 38558", phone: "(931) 456-2864", type: "Public", slug: "heatherhurst-brae-golf-course", coordinates: [-84.882097, 36.038274] },
            { name: "Heatherhurst Crag Golf Course", address: "421 Stonehenge Dr, Crossville, TN 38558", phone: "(931) 456-2864", type: "Semi-Private", slug: "heatherhurst-crag-golf-course", coordinates: [-84.882097, 36.038274] },
            { name: "Henry Horton State Park Golf Course", address: "4358 Nashville Highway, Chapel Hill, TN 37034", phone: "(931) 364-2319", type: "Public", slug: "henry-horton-state-park-golf-course", coordinates: [-86.699712, 35.595475] },
            { name: "Hermitage Golf Course", address: "3939 Old Hickory Blvd, Old Hickory, TN 37138", phone: "(615) 847-4001", type: "Public", slug: "hermitage-golf-course", coordinates: [-86.638436, 36.229589] },
            { name: "Hillwood Country Club", address: "6201 Hickory Valley Road, Nashville, TN 37205", phone: "(615) 352-6591", type: "Private", slug: "hillwood-country-club", coordinates: [-86.870707, 36.122107] },
            { name: "Holston Hills Country Club", address: "5200 Holston Hills Rd, Knoxville, TN 37914", phone: "(865) 584-6230", type: "Private", slug: "holston-hills-country-club", coordinates: [-83.839851, 35.995597] },
            { name: "Honky Tonk National Golf Course", address: "235 Harbour Greens Place, Sparta, TN 38583", phone: "", type: "Private", slug: "honky-tonk-national-golf-course", coordinates: [-85.687161, 35.957121] },
            { name: "Island Pointe Golf Club", address: "9610 Kodak Road, Kodak, TN 37764", phone: "", type: "Public", slug: "island-pointe-golf-club", coordinates: [-83.675072, 35.959607] },
            { name: "Jackson Country Club", address: "31 Jackson Country Club Ln, Jackson, TN 38305", phone: "", type: "Private", slug: "jackson-country-club", coordinates: [-88.837703, 35.67434] },
            { name: "Lake Tansi Golf Course", address: "2476 Dunbar Road, Crossville, TN 38572", phone: "(931) 788-3301", type: "Public", slug: "lake-tansi-golf-course", coordinates: [-85.057157, 35.876276] },
            { name: "Lambert Acres Golf Club", address: "3402 Tuckaleechee Pike, Maryville, TN 37803", phone: "(865) 982-9838", type: "Public", slug: "lambert-acres-golf-club", coordinates: [-83.888786, 35.752577] },
            { name: "Laurel Valley Country Club", address: "702 Country Club Drive, Townsend, TN 37882", phone: "(865) 738-3134", type: "Public", slug: "laurel-valley-country-club", coordinates: [-83.809942, 35.66918] },
            { name: "Lookout Mountain Club", address: "1201 Fleetwood Drive, Lookout Mountain, GA 30750", phone: "(706) 820-1551", type: "Private", slug: "lookout-mountain-club", coordinates: [-85.347997, 34.978802] },
            { name: "McCabe Golf Course", address: "100 46th Avenue North, Nashville, TN 37209", phone: "(615) 862-8491", type: "Municipal", slug: "mccabe-golf-course", coordinates: [-86.841038, 36.141492] },
            { name: "Memphis Country Club", address: "600 Goodwyn Street, Memphis, TN 38111", phone: "(901) 452-2131", type: "Private", slug: "memphis-country-club", coordinates: [-89.960686, 35.116426] },
            { name: "Mirimichi Golf Course", address: "6195 Woodstock Cuba Rd, Millington, TN 38053", phone: "(901) 259-3800", type: "Public", slug: "mirimichi-golf-course", coordinates: [-89.995974, 35.292701] },
            { name: "Moccasin Bend Golf Course", address: "381 Moccasin Bend Rd, Chattanooga, TN 37405", phone: "(423) 267-3585", type: "Public", slug: "moccasin-bend-golf-course", coordinates: [-85.338173, 35.044447] },
            { name: "Montgomery Bell State Park Golf Course", address: "800 Hotel Ave, Burns, TN 37029", phone: "", type: "Public", slug: "montgomery-bell-state-park-golf-course", coordinates: [-87.264751, 36.092607] },
            { name: "Nashville Golf & Athletic Club", address: "1703 Crockett Springs Trail, Brentwood, TN 37027", phone: "(615) 356-8580", type: "Private", slug: "nashville-golf-athletic-club", coordinates: [-86.786567, 35.962076] },
            { name: "Nashville National Golf Links", address: "1725 New Hope Road, Nashville, TN 37080", phone: "(615) 876-4653", type: "Semi-Private", slug: "nashville-national-golf-links", coordinates: [-86.930002, 36.381163] },
            { name: "Old Fort Golf Course", address: "1028 Golf Lane, Murfreesboro, TN 37129", phone: "(615) 896-2448", type: "Municipal", slug: "old-fort-golf-course", coordinates: [-86.414966, 35.853766] },
            { name: "Old Hickory Country Club", address: "1904 Old Hickory Blvd, Old Hickory, TN 37138", phone: "(615) 847-3966", type: "Private", slug: "old-hickory-country-club", coordinates: [-86.647362, 36.248563] },
            { name: "Overton Park 9", address: "2080 Poplar Ave, Memphis, TN 38104", phone: "(901) 305-2604", type: "Municipal", slug: "overton-park-9", coordinates: [-89.992422, 35.144067] },
            { name: "Paris Landing State Park Golf Course", address: "285 Golf Course Lane, Buchanan, TN 38222", phone: "(731) 642-2555", type: "Public", slug: "paris-landing-state-park-golf-course", coordinates: [-88.086163, 36.430914] },
            { name: "Percy Warner Golf Course", address: "1221 Forrest Park Dr, Nashville, TN 37205", phone: "(615) 862-8492", type: "Municipal", slug: "percy-warner-golf-course", coordinates: [-86.872206, 36.083423] },
            { name: "Pickwick Landing State Park Golf Course", address: "60 Winfield Dunn Lane, Pickwick Dam, TN 38365", phone: "(731) 689-3149", type: "Public", slug: "pickwick-landing-state-park", coordinates: [-88.24392, 35.051578] },
            { name: "Pine Oaks Golf Course", address: "1709 Buffalo Road, Johnson City, TN 37604", phone: "(423) 434-6250", type: "Municipal", slug: "pine-oaks-golf-course", coordinates: [-82.349258, 36.298467] },
            { name: "Richland Country Club", address: "116 Richland Country Club Drive, Nashville, TN 37205", phone: "(615) 352-4444", type: "Private", slug: "richland-country-club", coordinates: [-86.822853, 36.049786] },
            { name: "Ross Creek Landing Golf Course", address: "110 Airport Rd, Clifton, TN 38425", phone: "(931) 676-3174", type: "Public", slug: "ross-creek-landing-golf-course", coordinates: [-87.972945, 35.388933] },
            { name: "Sevierville Golf Club", address: "1444 Old Knoxville Highway, Sevierville, TN 37876", phone: "(865) 429-4223", type: "Public", slug: "sevierville-golf-club", coordinates: [-83.583287, 35.895882] },
            { name: "Signal Mountain Golf & Country Club", address: "809 James Boulevard, Signal Mountain, TN 37377", phone: "(423) 886-5767", type: "Private", slug: "signal-mountain-golf-country-club", coordinates: [-85.342694, 35.130426] },
            { name: "Southern Hills Golf & Country Club", address: "4770 Ben Jared Rd, Cookeville, TN 38506", phone: "(931) 432-5149", type: "Public", slug: "southern-hills-golf-country-club", coordinates: [-85.599422, 36.104732] },
            { name: "Sparta Country Club", address: "235 Country Club Drive, Sparta, TN 38583", phone: "", type: "Private", slug: "sparta-country-club", coordinates: [-85.426489, 35.919362] },
            { name: "Springhouse Golf Club", address: "18 Springhouse Ln, Nashville, TN 37214", phone: "(615) 452-1730", type: "Public", slug: "springhouse-golf-club", coordinates: [-86.679994, 36.218551] },
            { name: "Stonehenge Golf Club", address: "222 Fairfield Blvd, Fairfield Glade, TN 38558", phone: "", type: "Public", slug: "stonehenge-golf-club", coordinates: [-84.879122, 36.010854] },
            { name: "Stones River Country Club", address: "1830 NW Broad Street, Murfreesboro, TN 37129", phone: "(615) 893-6420", type: "Private", slug: "stones-river-country-club", coordinates: [-86.417465, 35.876951] },
            { name: "Sweetens Cove Golf Club", address: "2040 Sweetens Cove Road, South Pittsburg, TN 37380", phone: "(423) 280-9692", type: "Public", slug: "sweetens-cove-golf-club", coordinates: [-85.72692, 35.053232] },
            { name: "Tanasi Golf Course", address: "450 Clubhouse Point, Loudon, TN 37774", phone: "", type: "Semi-Private", slug: "tanasi-golf-course", coordinates: [-84.25866, 35.730086] },
            { name: "Ted Rhodes Golf Course", address: "1901 Ed Temple Blvd, Nashville, TN 37208", phone: "(615) 862-8463", type: "Municipal", slug: "ted-rhodes-golf-course", coordinates: [-86.824843, 36.184658] },
            { name: "The Country Club", address: "1686 Doyal Drive, Morristown, TN 37814", phone: "(423) 586-1941", type: "Private", slug: "the-country-club", coordinates: [-83.326364, 36.219715] },
            { name: "Temple Hills Country Club", address: "6376 Temple Road, Franklin, TN 37069", phone: "(615) 646-4785", type: "Private", slug: "temple-hills-country-club", coordinates: [-86.953019, 36.013908] },
            { name: "Tennessee Grasslands Golf & CC - Fairvue", address: "981 Plantation Boulevard, Gallatin, TN 37066", phone: "", type: "Private", slug: "tennessee-grasslands-fairvue", coordinates: [-86.490117, 36.344898] },
            { name: "Tennessee Grasslands Golf & CC - Foxland", address: "1445 Foxland Boulevard, Gallatin, TN 37066", phone: "", type: "Private", slug: "tennessee-grasslands-foxland", coordinates: [-86.509833, 36.345264] },
            { name: "Tennessee National Golf Club", address: "8301 Tennessee National Dr, Loudon, TN 37774", phone: "", type: "Semi-Private", slug: "tennessee-national-golf-club", coordinates: [-84.410044, 35.767193] },
            { name: "The Club at Five Oaks", address: "621 Five Oaks Blvd, Lebanon, TN 37087", phone: "(615) 444-2784", type: "Private", slug: "the-club-at-five-oaks", coordinates: [-86.377233, 36.247965] },
            { name: "The Club at Gettysvue", address: "9317 Linksvue Drive, Knoxville, TN 37922", phone: "", type: "Private", slug: "the-club-at-gettysvue", coordinates: [-84.081498, 35.890593] },
            { name: "The Golf Club of Tennessee", address: "1000 Golf Club Dr, Kingston Springs, TN 37082", phone: "(615) 370-4653", type: "Private", slug: "the-golf-club-of-tennessee", coordinates: [-87.09044, 36.068157] },
            { name: "The Governors Club", address: "1500 Governors Club Drive, Brentwood, TN 37027", phone: "(615) 370-0707", type: "Private", slug: "the-governors-club", coordinates: [-86.822296, 36.050479] },
            { name: "The Grove", address: "7165 Nolensville Road, College Grove, TN 37046", phone: "", type: "Private", slug: "the-grove", coordinates: [-86.658562, 35.859501] },
            { name: "The Honors Course", address: "9603 Lee Highway, Ooltewah, TN 37363", phone: "", type: "Private", slug: "the-honors-course", coordinates: [-85.044343, 35.087476] },
            { name: "The Legacy Golf Course", address: "100 Ray Floyd Drive, Springfield, TN 37172", phone: "(615) 384-4653", type: "Public", slug: "the-legacy-golf-course", coordinates: [-86.840558, 36.484862] },
            { name: "The Links at Audubon", address: "4160 Park Avenue, Memphis, TN 38117", phone: "(901) 683-6941", type: "Municipal", slug: "the-links-at-audubon", coordinates: [-89.923459, 35.109465] },
            { name: "The Links at Fox Meadows", address: "3064 Clarke Road, Memphis, TN 38115", phone: "(901) 636-0932", type: "Municipal", slug: "the-links-at-fox-meadows", coordinates: [-89.87462, 35.064806] },
            { name: "The Links at Galloway", address: "3815 Walnut Grove Road, Memphis, TN 38111", phone: "(901) 362-0232", type: "Municipal", slug: "the-links-at-galloway", coordinates: [-89.934835, 35.131165] },
            { name: "The Links at Kahite", address: "400 Kahite Trail, Vonore, TN 37885", phone: "", type: "Semi-Private", slug: "the-links-at-kahite", coordinates: [-84.24058, 35.56978] },
            { name: "The Links at Whitehaven", address: "750 E Holmes Road, Memphis, TN 38109", phone: "(901) 396-1608", type: "Municipal", slug: "the-links-at-whitehaven", coordinates: [-90.039894, 35.007576] },
            { name: "Three Ridges Golf Course", address: "6601 Ridgewood Drive, Knoxville, TN 37921", phone: "(865) 525-4653", type: "Municipal", slug: "three-ridges-golf-course", coordinates: [-83.902271, 35.903936] },
            { name: "Toqua Golf Course", address: "200 Toqua Club Way, Loudon, TN 37774", phone: "", type: "Public", slug: "toqua-golf-course", coordinates: [-84.257511, 35.681091] },
            { name: "Timber Truss Golf Course", address: "9425 Plantation Road, Olive Branch, MS 38654", phone: "(662) 895-3530", type: "Public", slug: "timber-truss-golf-course", coordinates: [-89.822991, 34.976025] },
            { name: "TPC Southwind", address: "3325 Club at Southwind, Memphis, TN 38125", phone: "(901) 748-0330", type: "Private", slug: "tpc-southwind", coordinates: [-89.779304, 35.057175] },
            { name: "Troubadour Golf & Field Club", address: "7230 Harlow Dr, College Grove, TN 37046", phone: "(615) 436-6850", type: "Private", slug: "troubadour-golf-field-club", coordinates: [-86.683361, 35.820693] },
            { name: "Two Rivers Golf Course", address: "3140 McGavock Pike, Nashville, TN 37214", phone: "(615) 889-2675", type: "Municipal", slug: "two-rivers-golf-course", coordinates: [-86.678328, 36.189044] },
            { name: "Vanderbilt Legends Club", address: "1500 Legends Club Lane, Franklin, TN 37069", phone: "(615) 791-8100", type: "Private", slug: "vanderbilt-legends-club", coordinates: [-86.842759, 35.950162] },
            { name: "Warrior's Path State Park Golf Course", address: "490 Hemlock Road, Kingsport, TN 37663", phone: "(423) 239-8531", type: "Public", slug: "warriors-path-state-park-golf-course", coordinates: [-82.483102, 36.49147] },
            { name: "White Plains Golf Course", address: "4000 Plantation Dr, Cookeville, TN 38506", phone: "(931) 526-3306", type: "Semi-Private", slug: "white-plains-golf-course", coordinates: [-85.431608, 36.178956] },
            { name: "Whittle Springs Golf Course", address: "3113 Valley View Dr, Knoxville, TN 37917", phone: "", type: "Municipal", slug: "whittle-springs-golf-course", coordinates: [-83.907586, 36.015671] },
            { name: "Williams Creek Golf Course", address: "2351 Dandridge Ave, Knoxville, TN 37915", phone: "(865) 546-5828", type: "Public", slug: "williams-creek-golf-course", coordinates: [-83.885638, 35.97154] },
            { name: "WindRiver Golf Club", address: "1800 Oakum Court, Lenoir City, TN 37772", phone: "(865) 988-0370", type: "Private", slug: "windriver-golf-club", coordinates: [-84.24332, 35.743441] },
            { name: "Willow Creek Golf Club", address: "12003 Kingston Pike, Knoxville, TN 37934", phone: "(865) 675-0100", type: "Public", slug: "willow-creek-golf-club", coordinates: [-84.189666, 35.860736] },
            { name: "Windyke Country Club", address: "8535 Winchester Road, Memphis, TN 38125", phone: "(901) 754-7273", type: "Private", slug: "windyke-country-club", coordinates: [-89.777302, 35.044353] },
            { name: "Windtree Golf Course", address: "810 Nonaville Rd, Mount Juliet, TN 37122", phone: "", type: "Public", slug: "windtree-golf-course", coordinates: [-86.516076, 36.243711] }
        ];

        let map;
        let allMarkers = [];
        let openInfoWindow = null;
        let currentFilter = 'all';
        let currentSearch = '';

        function initMap() {
            map = new google.maps.Map(document.getElementById('tennessee-golf-map'), {
                center: { lat: 36.1627, lng: -86.7816 },
                zoom: 7,
                mapTypeId: 'roadmap',
                mapTypeControl: true,
                fullscreenControl: true,
                streetViewControl: false
            });

            golfCourses.forEach(function(course) {
                const color = getColor(course.type);

                const marker = new google.maps.Marker({
                    position: { lat: course.coordinates[1], lng: course.coordinates[0] },
                    map: map,
                    title: course.name,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: color,
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2,
                        scale: 9
                    }
                });

                const popupContent = `
                    <div class="gm-popup">
                        <div class="gm-popup-name">${course.name}</div>
                        <div class="gm-popup-type" style="color:${color}">${course.type}</div>
                        <div class="gm-popup-address">${course.address}</div>
                        ${course.phone ? `<div class="gm-popup-phone">${course.phone}</div>` : '<div style="margin-bottom:12px"></div>'}
                        <div class="gm-popup-buttons">
                            <a href="/courses/${course.slug}" class="gm-popup-btn gm-popup-btn-green">Course Details</a>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(course.address)}" target="_blank" rel="noopener noreferrer" class="gm-popup-btn gm-popup-btn-blue">Get Directions</a>
                        </div>
                    </div>`;

                const infoWindow = new google.maps.InfoWindow({ content: popupContent });

                marker.addListener('click', function() {
                    if (openInfoWindow) openInfoWindow.close();
                    infoWindow.open(map, marker);
                    openInfoWindow = infoWindow;
                });

                marker.courseData = course;
                allMarkers.push(marker);
            });

            updateResultsCounter();

            document.getElementById('courseSearch').addEventListener('input', function(e) {
                currentSearch = e.target.value.trim();
                filterMarkers();
            });

            document.querySelectorAll('.filter-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.filter-btn').forEach(function(btn) {
                        btn.classList.remove('active');
                        const f = btn.dataset.filter;
                        if (f === 'all') {
                            btn.style.background = 'white';
                            btn.style.color = 'var(--primary-color)';
                            btn.style.border = '2px solid var(--primary-color)';
                        } else {
                            const colors = { 'Public': '#4CAF50', 'Municipal': '#2196F3', 'Private': '#FF9800', 'Semi-Private': '#9C27B0' };
                            btn.style.background = 'white';
                            btn.style.color = colors[f] || '#4CAF50';
                        }
                    });

                    this.classList.add('active');
                    if (this.dataset.filter === 'all') {
                        this.style.background = 'var(--primary-color)';
                        this.style.color = 'white';
                        this.style.border = 'none';
                    } else {
                        const colors = { 'Public': '#4CAF50', 'Municipal': '#2196F3', 'Private': '#FF9800', 'Semi-Private': '#9C27B0' };
                        this.style.background = colors[this.dataset.filter] || '#4CAF50';
                        this.style.color = 'white';
                    }

                    currentFilter = this.dataset.filter;
                    filterMarkers();
                });
            });
        }

        function filterMarkers() {
            let visibleCount = 0;
            allMarkers.forEach(function(marker) {
                const course = marker.courseData;
                let show = true;

                if (currentFilter !== 'all') {
                    const t = course.type || '';
                    if (currentFilter === 'Public') show = t.includes('Public') && !t.includes('Semi');
                    else if (currentFilter === 'Municipal') show = t.includes('Municipal');
                    else if (currentFilter === 'Private') show = t.includes('Private') && !t.includes('Semi');
                    else if (currentFilter === 'Semi-Private') show = t.includes('Semi-Private');
                }

                if (show && currentSearch) {
                    const s = currentSearch.toLowerCase();
                    show = course.name.toLowerCase().includes(s) || course.address.toLowerCase().includes(s);
                }

                marker.setMap(show ? map : null);
                if (show) visibleCount++;
            });
            updateResultsCounter(visibleCount);
        }

        function updateResultsCounter(count) {
            const counter = document.getElementById('resultsCounter');
            const total = (count !== undefined) ? count : allMarkers.length;
            if (currentSearch || currentFilter !== 'all') {
                counter.textContent = `Showing ${total} of ${golfCourses.length} golf courses`;
            } else {
                counter.textContent = `Showing all ${golfCourses.length} golf courses`;
            }
        }
    </script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ0tT8D1xyUIlbX1m5B4IaC68mE3YNexw&callback=initMap" async defer></script>

</body>
</html>
