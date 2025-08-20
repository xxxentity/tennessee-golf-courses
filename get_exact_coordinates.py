#!/usr/bin/env python3
"""
Get exact GPS coordinates for golf courses using multiple geocoding services
"""

import requests
import time
import json

# Courses that need exact coordinates
problem_courses = {
    "The Honors Course": "9603 Lee Highway, Ooltewah, TN 37363",
    "Chattanooga Golf & Country Club": "1511 Riverview Road, Chattanooga, TN 37405", 
    "Bear Trace at Harrison Bay": "8919 Harrison Bay Road, Harrison, TN 37341",
    "Council Fire Golf Club": "1400 Council Fire Drive, Chattanooga, TN 37421"
}

def geocode_with_nominatim(address):
    """Use OpenStreetMap Nominatim for geocoding"""
    try:
        url = "https://nominatim.openstreetmap.org/search"
        params = {
            'q': address,
            'format': 'json',
            'limit': 1,
            'countrycodes': 'us',
            'addressdetails': 1
        }
        headers = {'User-Agent': 'TennesseeGolfCourses/1.0'}
        
        response = requests.get(url, params=params, headers=headers, timeout=10)
        response.raise_for_status()
        
        results = response.json()
        if results:
            lat = float(results[0]['lat'])
            lon = float(results[0]['lon'])
            display_name = results[0].get('display_name', '')
            return [lon, lat], display_name
        else:
            return None, None
    except Exception as e:
        print(f"Nominatim error for {address}: {e}")
        return None, None

def geocode_with_mapbox(address, access_token):
    """Use Mapbox Geocoding API for more precise results"""
    try:
        url = f"https://api.mapbox.com/geocoding/v5/mapbox.places/{address}.json"
        params = {
            'access_token': access_token,
            'limit': 1,
            'country': 'US'
        }
        
        response = requests.get(url, params=params, timeout=10)
        response.raise_for_status()
        
        data = response.json()
        if data['features']:
            coords = data['features'][0]['geometry']['coordinates']
            place_name = data['features'][0]['place_name']
            return coords, place_name
        else:
            return None, None
    except Exception as e:
        print(f"Mapbox error for {address}: {e}")
        return None, None

print("Getting exact coordinates for problem golf courses...")
print("=" * 60)

results = {}

for course_name, address in problem_courses.items():
    print(f"\n🏌️ {course_name}")
    print(f"Address: {address}")
    
    # Try Nominatim first (free)
    coords, display_name = geocode_with_nominatim(address)
    if coords:
        results[course_name] = {
            'address': address,
            'coordinates': coords,
            'source': 'OpenStreetMap Nominatim',
            'display_name': display_name
        }
        print(f"✅ Nominatim: {coords}")
        print(f"   Full name: {display_name}")
    else:
        print("❌ Nominatim failed")
        results[course_name] = {
            'address': address,
            'coordinates': None,
            'source': 'Failed',
            'display_name': None
        }
    
    time.sleep(1)  # Be respectful to the API

print("\n" + "=" * 60)
print("EXACT COORDINATES RESULTS:")
print("=" * 60)

for course_name, data in results.items():
    if data['coordinates']:
        lon, lat = data['coordinates']
        print(f"'{course_name.lower().replace(' ', '-').replace('&', '').replace('at-', 'at-')}': [{lon:.6f}, {lat:.6f}],")
    else:
        print(f"❌ {course_name}: FAILED TO GEOCODE")

# Save results
with open('/Users/entity./TGC LLC/exact_coordinates_results.json', 'w') as f:
    json.dump(results, f, indent=2)

print(f"\nResults saved to exact_coordinates_results.json")