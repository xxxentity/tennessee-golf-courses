#!/usr/bin/env python3
"""
Fix ALL remaining courses with ArcGIS - systematic approach
"""

import requests
import time
import json

def geocode_arcgis(address):
    """ArcGIS World Geocoding Service - proven accurate"""
    try:
        url = "https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates"
        params = {
            'SingleLine': address,
            'f': 'json',
            'outFields': 'Addr_type,Match_addr,Score',
            'maxLocations': 1,
            'countryCode': 'USA'
        }
        response = requests.get(url, params=params, timeout=10)
        data = response.json()
        
        if 'candidates' in data and data['candidates']:
            candidate = data['candidates'][0]
            location = candidate['location']
            score = candidate.get('score', 0)
            return [location['x'], location['y']], candidate.get('address', ''), score
    except Exception as e:
        print(f"ArcGIS error: {e}")
    return None, None, 0

# ALL courses that might need fixing based on extraction log failures or city-only geocoding
all_courses_to_check = [
    ("Southern Hills Golf & Country Club", "4770 Ben Jared Rd, Cookeville, TN 38506"),
    ("Avalon Golf & Country Club", "1299 Oak Chase Blvd, Lenoir City, TN 37772"),
    ("Bear Trace at Tims Ford", "570 Bear Trace Drive, Winchester, TN 37398"),
    ("Big Creek Golf Club", "6195 Woodstock Cuba Rd, Millington, TN 38053"),
    ("Blackthorn Club", "1501 Ridges Club Drive, Jonesborough, TN 37659"),
    ("Bluegrass Yacht & Country Club", "550 Johnny Cash Parkway, Hendersonville, TN 37075"),
    ("Dead Horse Lake Golf Course", "3016 Gravelly Hills Road, Louisville, TN 37777"),
    ("Fox Den Country Club", "6000 Fox Den Drive, Knoxville, TN 37918"),
    ("Gaylord Springs Golf Links", "18 Springs Blvd, Nashville, TN 37214"),
    ("Greystone Golf Course", "1 Greystone Drive, Dickson, TN 37055"),
    ("Lake Tansi Golf Course", "2476 Dunbar Road, Crossville, TN 38572"),
    ("Laurel Valley Country Club", "702 Country Club Drive, Townsend, TN 37882"),
    ("Mirimichi Golf Course", "6195 Woodstock Cuba Rd, Millington, TN 38053"),
    ("Montgomery Bell State Park Golf Course", "1020 Jackson Hill Road, Burns, TN 37029"),
    ("Nashville Golf & Athletic Club", "1101 Golf Club Lane, Belle Meade, TN 37205"),
    ("Pickwick Landing State Park Golf Course", "60 Winfield Dunn Lane, Pickwick Dam, TN 38365"),
    ("Richland Country Club", "116 Richland Country Club Drive, Nashville, TN 37205"),
    ("Ross Creek Landing Golf Course", "110 Airport Rd, Clifton, TN 38425"),
    ("Tanasi Golf Course", "450 Clubhouse Point, Loudon, TN 37774"),
    ("Temple Hills Country Club", "6376 Temple Road, Franklin, TN 37069"),
    ("The Golf Club of Tennessee", "1 Sneed Road West, Kingston Springs, TN 37082"),
    ("The Governors Club", "1500 Governors Club Drive, Brentwood, TN 37027"),
    ("The Grove", "7165 Nolensville Road, College Grove, TN 37046"),
    ("The Legacy Golf Course", "100 Ray Floyd Drive, Springfield, TN 37172"),
    ("The Links at Kahite", "100 Kahite Trail, Vonore, TN 37885"),
    ("Three Ridges Golf Course", "6601 Ridgewood Drive, Knoxville, TN 37921"),
    ("Troubadour Golf & Field Club", "7230 Harlow Dr, College Grove, TN 37046"),
    ("Windtree Golf Course", "810 Nonaville Rd, Mount Juliet, TN 37122"),
    ("White Plains Golf Course", "4000 Plantation Dr, Cookeville, TN 38506")
]

print("🎯 SYSTEMATIC FIX: Running ALL remaining courses through ArcGIS")
print("=" * 80)

results = {}
successful_updates = []

for i, (course_name, address) in enumerate(all_courses_to_check, 1):
    print(f"\n[{i}/{len(all_courses_to_check)}] 🏌️ {course_name}")
    print(f"Address: {address}")
    
    coords, formatted_addr, score = geocode_arcgis(address)
    if coords and score > 80:  # Good confidence score
        results[course_name] = {
            'address': address,
            'coordinates': coords,
            'formatted_address': formatted_addr,
            'confidence_score': score
        }
        successful_updates.append((course_name, coords))
        print(f"✅ ArcGIS: {coords} (Score: {score})")
        print(f"   Formatted: {formatted_addr}")
    else:
        print(f"❌ Failed or low confidence (Score: {score})")
        results[course_name] = None
    
    time.sleep(0.5)  # Be respectful to the API

print(f"\n{'='*80}")
print(f"SUCCESSFUL UPDATES: {len(successful_updates)} courses")
print("="*80)

for course_name, coords in successful_updates:
    slug = course_name.lower().replace(' ', '-').replace('&', '').replace('at-', 'at-')
    print(f"// {course_name}")
    print(f"'{slug}': [{coords[0]:.6f}, {coords[1]:.6f}],")
    print()

# Save results
with open('/Users/entity./TGC LLC/all_course_arcgis_fixes.json', 'w') as f:
    json.dump(results, f, indent=2)

print(f"Results saved to all_course_arcgis_fixes.json")
print(f"\n🚀 Ready to apply {len(successful_updates)} precise coordinate updates!")