#!/usr/bin/env python3
"""
Get exact coordinates for The Golf Club of Tennessee's correct address
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

correct_address = "1000 Golf Club Dr, Kingston Springs, TN 37082"
print(f"Getting coordinates for: {correct_address}")

coords, formatted_addr, score = geocode_arcgis(correct_address)
if coords and score > 80:
    print(f"✅ ArcGIS: {coords} (Score: {score})")
    print(f"   Formatted: {formatted_addr}")
    print(f"\nCoordinates to use: [{coords[0]:.6f}, {coords[1]:.6f}]")
else:
    print(f"❌ Failed or low confidence (Score: {score})")