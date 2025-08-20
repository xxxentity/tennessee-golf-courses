#!/usr/bin/env python3
"""
Get the most precise coordinates possible for Colonial Country Club
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
            'maxLocations': 3,  # Get multiple results to choose best
            'countryCode': 'USA'
        }
        response = requests.get(url, params=params, timeout=10)
        data = response.json()
        
        if 'candidates' in data and data['candidates']:
            return data['candidates']
    except Exception as e:
        print(f"ArcGIS error: {e}")
    return []

def geocode_nominatim(address):
    """OpenStreetMap Nominatim for comparison"""
    try:
        url = "https://nominatim.openstreetmap.org/search"
        params = {
            'q': address,
            'format': 'json',
            'limit': 3,
            'addressdetails': 1,
            'countrycodes': 'us'
        }
        headers = {'User-Agent': 'TennesseeGolfCourses/1.0'}
        response = requests.get(url, params=params, headers=headers, timeout=10)
        return response.json()
    except Exception as e:
        print(f"Nominatim error: {e}")
    return []

# Try different variations of the address
addresses_to_try = [
    "2736 Countrywood Parkway, Cordova, TN 38016",
    "Colonial Country Club, Cordova, TN",
    "2736 Countrywood Pkwy, Cordova, TN 38016",
    "Colonial Country Club, 2736 Countrywood Parkway, Memphis, TN"
]

print("🎯 Getting PRECISE coordinates for Colonial Country Club")
print("=" * 60)

all_results = []

for address in addresses_to_try:
    print(f"\n📍 Testing: {address}")
    
    # Try ArcGIS
    arcgis_results = geocode_arcgis(address)
    for i, candidate in enumerate(arcgis_results[:2]):  # Top 2 results
        location = candidate['location']
        score = candidate.get('score', 0)
        coords = [location['x'], location['y']]
        formatted = candidate.get('address', '')
        all_results.append((f"ArcGIS-{i+1}", coords, score, formatted, address))
        print(f"  ArcGIS {i+1}: {coords} (Score: {score}) - {formatted}")
    
    # Try Nominatim
    nom_results = geocode_nominatim(address)
    for i, result in enumerate(nom_results[:2]):  # Top 2 results
        coords = [float(result['lon']), float(result['lat'])]
        formatted = result.get('display_name', '')
        all_results.append((f"Nominatim-{i+1}", coords, 0, formatted, address))
        print(f"  Nominatim {i+1}: {coords} - {formatted}")

print(f"\n{'='*60}")
print("BEST COORDINATE OPTIONS:")
print("="*60)

# Sort by ArcGIS score (highest first)
arcgis_only = [r for r in all_results if 'ArcGIS' in r[0]]
arcgis_sorted = sorted(arcgis_only, key=lambda x: x[2], reverse=True)

for i, (source, coords, score, formatted, orig_addr) in enumerate(arcgis_sorted[:5]):
    print(f"{i+1}. {source}: [{coords[0]:.6f}, {coords[1]:.6f}] (Score: {score})")
    print(f"   From: {orig_addr}")
    print(f"   Result: {formatted}")
    print()

if arcgis_sorted:
    best = arcgis_sorted[0]
    print(f"🎯 RECOMMENDED: [{best[1][0]:.6f}, {best[1][1]:.6f}] (Score: {best[2]})")