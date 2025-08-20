#!/usr/bin/env python3
"""
Use ArcGIS World Geocoder to fix any remaining inaccurate golf course coordinates
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

# Courses that might need fixing based on previous issues
courses_to_check = [
    ("Bear Trace at Harrison Bay", "8919 Harrison Bay Road, Harrison, TN 37341"),
    ("Council Fire Golf Club", "1400 Council Fire Drive, Chattanooga, TN 37421"),
    ("Clarksville Country Club", "334 Fairway Dr, Clarksville, TN 37043"),
    ("Jackson Country Club", "31 Jackson Country Club Ln, Jackson, TN 38305"),
    ("Honky Tonk National Golf Course", "235 Harbour Greens Place, Sparta, TN 38583"),
    ("Cumberland Cove Golf Course", "16941 Highway 70 N, Monterey, TN 38574"),
    ("Fall Creek Falls State Park Golf Course", "626 Golf Course Road, Spencer, TN 38585"),
    ("Paris Landing State Park Golf Course", "285 Golf Course Lane, Buchanan, TN 38222"),
]

print("🎯 Using ArcGIS World Geocoder (the proven accurate method)")
print("=" * 70)

results = {}

for course_name, address in courses_to_check:
    print(f"\n🏌️ {course_name}")
    print(f"Address: {address}")
    
    coords, formatted_addr, score = geocode_arcgis(address)
    if coords and score > 80:  # Good confidence score
        results[course_name] = {
            'address': address,
            'coordinates': coords,
            'formatted_address': formatted_addr,
            'confidence_score': score
        }
        print(f"✅ ArcGIS: {coords} (Score: {score})")
        print(f"   Formatted: {formatted_addr}")
    else:
        print(f"❌ Failed or low confidence (Score: {score})")
        results[course_name] = None
    
    time.sleep(1)  # Be respectful to the API

print(f"\n{'='*70}")
print("JAVASCRIPT COORDINATES TO UPDATE:")
print("="*70)

for course_name, data in results.items():
    if data:
        coords = data['coordinates']
        slug = course_name.lower().replace(' ', '-').replace('&', '').replace('at-', 'at-')
        print(f"// {course_name}")
        print(f"coordinates: [{coords[0]:.6f}, {coords[1]:.6f}],")
        print()

# Save results
with open('/Users/entity./TGC LLC/arcgis_coordinate_fixes.json', 'w') as f:
    json.dump(results, f, indent=2)

print(f"Results saved to arcgis_coordinate_fixes.json")
print(f"\n🚀 Ready to apply these proven-accurate ArcGIS coordinates!")