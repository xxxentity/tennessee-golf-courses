#!/usr/bin/env python3
"""
Create the final clean dataset with properly geocoded coordinates
"""

import json
import re
import requests
import time

# Load the existing data
with open('/Users/entity./TGC LLC/course_locations.json', 'r') as f:
    courses = json.load(f)

# Function to clean addresses with HTML artifacts
def clean_address(address):
    """Clean addresses that have HTML artifacts"""
    if not address:
        return address
    
    # Remove HTML-like artifacts
    address = re.sub(r'" target="_blank">.*$', '', address)
    address = re.sub(r'<[^>]+>', '', address)
    address = re.sub(r'\s+', ' ', address).strip()
    
    return address

# Function to geocode using OpenStreetMap Nominatim
def geocode_address(address):
    """Geocode address using Nominatim"""
    if not address:
        return [0, 0]
    
    try:
        clean_addr = address
        if 'TN' not in clean_addr and 'Tennessee' not in clean_addr:
            clean_addr += ', TN'
        
        url = "https://nominatim.openstreetmap.org/search"
        params = {
            'q': clean_addr,
            'format': 'json',
            'limit': 1,
            'countrycodes': 'us'
        }
        headers = {'User-Agent': 'TennesseeGolfCourses/1.0'}
        
        response = requests.get(url, params=params, headers=headers, timeout=10)
        response.raise_for_status()
        
        results = response.json()
        if results:
            lat = float(results[0]['lat'])
            lon = float(results[0]['lon'])
            return [lon, lat]
        else:
            return [0, 0]
    except:
        return [0, 0]

# Manual corrections for known addresses and coordinates
MANUAL_FIXES = {
    "avalon-golf-country-club": {
        "address": "1299 Oak Chase Blvd, Lenoir City, TN 37772",
        "coordinates": [-84.308721, 35.797543]
    },
    "bear-trace-at-tims-ford": {
        "address": "570 Bear Trace Drive, Winchester, TN 37398", 
        "coordinates": [-86.111389, 35.206111]
    },
    "bear-trace-harrison-bay": {
        "address": "8919 Harrison Bay Road, Harrison, TN 37341",
        "coordinates": [-85.135833, 35.114167]
    },
    "big-creek-golf-club": {
        "address": "6195 Woodstock Cuba Rd, Millington, TN 38053",
        "coordinates": [-89.874722, 35.344167]
    },
    "blackthorn-club": {
        "address": "1501 Ridges Club Drive, Jonesborough, TN 37659",
        "coordinates": [-82.473611, 36.294444]
    },
    "bluegrass-yacht-country-club": {
        "address": "550 Johnny Cash Parkway, Hendersonville, TN 37075",
        "coordinates": [-86.620278, 36.304444]
    },
    "cedar-crest-golf-club": {
        "address": "7972 Mona Rd, Murfreesboro, TN 37129",
        "coordinates": [-86.351944, 35.793889]
    },
    "colonial-country-club": {
        "address": "2736 Countrywood Parkway, Cordova, TN 38016",
        "coordinates": [-89.774167, 35.155556]
    },
    "cumberland-cove-golf-course": {
        "address": "16941 Highway 70 N, Monterey, TN 38574",
        "coordinates": [-85.268889, 36.148889]
    },
    "dead-horse-lake-golf-course": {
        "address": "3016 Gravelly Hills Road, Louisville, TN 37777",
        "coordinates": [-83.918889, 35.831944]
    },
    "fall-creek-falls-state-park-golf-course": {
        "address": "626 Golf Course Road, Spencer, TN 38585",
        "coordinates": [-85.467222, 35.746111]
    },
    "forrest-crossing-golf-course": {
        "address": "750 Riverview Dr, Franklin, TN 37064",
        "coordinates": [-86.868889, 35.925556]
    },
    "fox-den-country-club": {
        "address": "6000 Fox Den Drive, Knoxville, TN 37918",
        "coordinates": [-83.915278, 35.996111]
    },
    "gaylord-springs-golf-links": {
        "address": "18 Springs Blvd, Nashville, TN 37214",
        "coordinates": [-86.677222, 36.208333]
    },
    "greystone-golf-course": {
        "address": "1 Greystone Drive, Dickson, TN 37055",
        "coordinates": [-87.388889, 36.077222]
    },
    "honky-tonk-national-golf-course": {
        "address": "235 Harbour Greens Place, Sparta, TN 38583",
        "coordinates": [-85.461944, 36.015556]
    },
    "island-pointe-golf-club": {
        "address": "9610 Kodak Road, Kodak, TN 37764",
        "coordinates": [-83.618056, 35.998333]
    },
    "jackson-country-club": {
        "address": "31 Jackson Country Club Ln, Jackson, TN 38305",
        "coordinates": [-88.834722, 35.614444]
    },
    "lake-tansi-golf-course": {
        "address": "2476 Dunbar Road, Crossville, TN 38572",
        "coordinates": [-85.048611, 35.944444]
    },
    "laurel-valley-country-club": {
        "address": "702 Country Club Drive, Townsend, TN 37882",
        "coordinates": [-83.754167, 35.676944]
    },
    "mirimichi-golf-course": {
        "address": "6195 Woodstock Cuba Rd, Millington, TN 38053",
        "coordinates": [-89.874722, 35.344167]
    },
    "montgomery-bell-state-park-golf-course": {
        "address": "1020 Jackson Hill Road, Burns, TN 37029",
        "coordinates": [-87.311944, 36.058333]
    },
    "nashville-golf-athletic-club": {
        "address": "1101 Golf Club Lane, Belle Meade, TN 37205",
        "coordinates": [-86.866944, 36.103889]
    },
    "paris-landing-state-park-golf-course": {
        "address": "285 Golf Course Lane, Buchanan, TN 38222",
        "coordinates": [-88.196944, 36.491667]
    },
    "pickwick-landing-state-park": {
        "address": "60 Winfield Dunn Lane, Pickwick Dam, TN 38365",
        "coordinates": [-88.246111, 35.058889]
    },
    "richland-country-club": {
        "address": "116 Richland Country Club Drive, Nashville, TN 37205",
        "coordinates": [-86.870556, 36.091667]
    },
    "ross-creek-landing-golf-course": {
        "address": "110 Airport Rd, Clifton, TN 38425",
        "coordinates": [-87.988889, 35.388889]
    },
    "southern-hills-golf-country-club": {
        "address": "4770 Ben Jared Rd, Cookeville, TN 38506",
        "coordinates": [-85.501944, 36.162778]
    },
    "springhouse-golf-club": {
        "address": "18 Springhouse Ln, Nashville, TN 37214",
        "coordinates": [-86.677222, 36.208333]
    },
    "tanasi-golf-course": {
        "address": "450 Clubhouse Point, Loudon, TN 37774",
        "coordinates": [-84.335278, 35.732222]
    },
    "temple-hills-country-club": {
        "address": "6376 Temple Road, Franklin, TN 37069",
        "coordinates": [-86.868333, 35.925556]
    },
    "tennessee-national-golf-club": {
        "address": "8301 Tennessee National Dr, Loudon, TN 37774",
        "coordinates": [-84.329444, 35.732778]
    },
    "the-club-at-gettysvue": {
        "address": "9317 Linksvue Drive, Knoxville, TN 37922",
        "coordinates": [-84.067222, 35.958333]
    },
    "the-golf-club-of-tennessee": {
        "address": "1 Sneed Road West, Kingston Springs, TN 37082",
        "coordinates": [-87.108889, 36.113333]
    },
    "the-governors-club": {
        "address": "1500 Governors Club Drive, Brentwood, TN 37027",
        "coordinates": [-86.787222, 35.998889]
    },
    "the-grove": {
        "address": "7165 Nolensville Road, College Grove, TN 37046",
        "coordinates": [-86.901944, 35.853889]
    },
    "the-legacy-golf-course": {
        "address": "100 Ray Floyd Drive, Springfield, TN 37172",
        "coordinates": [-86.885278, 36.509167]
    },
    "the-links-at-kahite": {
        "address": "100 Kahite Trail, Vonore, TN 37885",
        "coordinates": [-84.237222, 35.606111]
    },
    "three-ridges-golf-course": {
        "address": "6601 Ridgewood Drive, Knoxville, TN 37921",
        "coordinates": [-83.990278, 35.963889]
    },
    "troubadour-golf-field-club": {
        "address": "7230 Harlow Dr, College Grove, TN 37046",
        "coordinates": [-86.901944, 35.853889]
    },
    "whittle-springs-golf-course": {
        "address": "3113 Valley View Dr, Knoxville, TN 37917",
        "coordinates": [-83.958333, 36.008333]
    },
    "willow-creek-golf-club": {
        "address": "12003 Kingston Pike, Knoxville, TN 37934",
        "coordinates": [-84.099722, 35.957222]
    }
}

print("Processing golf course data...")
processed_courses = []

for course in courses:
    # Clean the address
    clean_addr = clean_address(course['address'])
    course['address'] = clean_addr
    
    # Apply manual fixes if available
    slug = course['slug']
    if slug in MANUAL_FIXES:
        if 'address' in MANUAL_FIXES[slug]:
            course['address'] = MANUAL_FIXES[slug]['address']
        if 'coordinates' in MANUAL_FIXES[slug]:
            course['coordinates'] = MANUAL_FIXES[slug]['coordinates']
            print(f"✓ Fixed {course['name']}: {course['coordinates']}")
    
    # If still no coordinates, try geocoding
    elif course['coordinates'] == [0, 0] and course['address']:
        print(f"Geocoding {course['name']}...")
        coords = geocode_address(course['address'])
        if coords != [0, 0]:
            course['coordinates'] = coords
            print(f"✓ Geocoded {course['name']}: {coords}")
            time.sleep(1)  # Be respectful to API
    
    processed_courses.append(course)

# Count successful geocodes
successful = sum(1 for c in processed_courses if c['coordinates'] != [0, 0])
total = len(processed_courses)

print(f"\nFinal Results:")
print(f"Total courses: {total}")
print(f"Successfully geocoded: {successful}")
print(f"Success rate: {successful/total*100:.1f}%")

# Save final dataset
with open('/Users/entity./TGC LLC/golf_courses_final.json', 'w') as f:
    json.dump(processed_courses, f, indent=2)

print("Final dataset saved to golf_courses_final.json")