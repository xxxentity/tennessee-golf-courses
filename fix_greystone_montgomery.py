#!/usr/bin/env python3
"""
Get exact coordinates for Greystone and Montgomery Bell's correct addresses
"""

import requests

def geocode_arcgis(address):
    """ArcGIS World Geocoding Service"""
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

courses = [
    ("Greystone Golf Course", "2555 US-70, Dickson, TN 37055"),
    ("Montgomery Bell State Park Golf Course", "800 Hotel Ave, Burns, TN 37029")
]

for course_name, address in courses:
    print(f"\n🏌️ {course_name}")
    print(f"Getting coordinates for: {address}")
    
    coords, formatted_addr, score = geocode_arcgis(address)
    if coords and score > 80:
        print(f"✅ ArcGIS: {coords} (Score: {score})")
        print(f"   Formatted: {formatted_addr}")
        print(f"   Coordinates to use: [{coords[0]:.6f}, {coords[1]:.6f}]")
    else:
        print(f"❌ Failed or low confidence (Score: {score})")