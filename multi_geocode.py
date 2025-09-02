#!/usr/bin/env python3
"""
Try multiple geocoding services for maximum accuracy
"""

import requests
import time
import json

def geocode_mapquest(address):
    """MapQuest Open Geocoding (free, no API key needed)"""
    try:
        url = "http://www.mapquestapi.com/geocoding/v1/address"
        params = {
            'key': 'consumer_key_not_required',
            'location': address
        }
        response = requests.get(url, params=params, timeout=10)
        data = response.json()
        
        if data['results'][0]['locations']:
            location = data['results'][0]['locations'][0]
            lat = location['latLng']['lat']
            lng = location['latLng']['lng']
            return [lng, lat], location.get('street', '') + ' ' + location.get('adminArea5', '')
    except:
        pass
    return None, None

def geocode_locationiq(address):
    """LocationIQ (free tier available)"""
    try:
        url = "https://us1.locationiq.com/v1/search.php"
        params = {
            'key': 'demo',  # Demo key for testing
            'q': address,
            'format': 'json',
            'limit': 1
        }
        response = requests.get(url, params=params, timeout=10)
        data = response.json()
        
        if data:
            lat = float(data[0]['lat'])
            lon = float(data[0]['lon'])
            return [lon, lat], data[0].get('display_name', '')
    except:
        pass
    return None, None

def geocode_positionstack(address):
    """PositionStack (free tier)"""
    try:
        url = "http://api.positionstack.com/v1/forward"
        params = {
            'access_key': 'demo',  # Would need real key
            'query': address,
            'limit': 1
        }
        response = requests.get(url, params=params, timeout=10)
        data = response.json()
        
        if 'data' in data and data['data']:
            result = data['data'][0]
            return [result['longitude'], result['latitude']], result.get('label', '')
    except:
        pass
    return None, None

def geocode_arcgis(address):
    """ArcGIS World Geocoding Service (free for non-commercial)"""
    try:
        url = "https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates"
        params = {
            'SingleLine': address,
            'f': 'json',
            'outFields': 'Addr_type,Match_addr',
            'maxLocations': 1
        }
        response = requests.get(url, params=params, timeout=10)
        data = response.json()
        
        if 'candidates' in data and data['candidates']:
            candidate = data['candidates'][0]
            location = candidate['location']
            return [location['x'], location['y']], candidate.get('address', '')
    except:
        pass
    return None, None

def geocode_opencage(address):
    """OpenCage (free tier available)"""
    try:
        url = "https://api.opencagedata.com/geocode/v1/json"
        params = {
            'key': 'demo',  # Would need real key  
            'q': address,
            'limit': 1,
            'countrycode': 'us'
        }
        response = requests.get(url, params=params, timeout=10)
        data = response.json()
        
        if 'results' in data and data['results']:
            result = data['results'][0]
            geometry = result['geometry']
            return [geometry['lng'], geometry['lat']], result.get('formatted', '')
    except:
        pass
    return None, None

# Test address
address = "1511 Riverview Road, Chattanooga, TN 37405"
print(f"Testing multiple geocoding services for: {address}")
print("=" * 60)

services = [
    ("Nominatim (OpenStreetMap)", lambda a: geocode_nominatim(a)),
    ("MapQuest Open", geocode_mapquest),
    ("ArcGIS World Geocoder", geocode_arcgis),
    ("LocationIQ", geocode_locationiq),
]

def geocode_nominatim(address):
    try:
        url = "https://nominatim.openstreetmap.org/search"
        params = {
            'q': address,
            'format': 'json',
            'limit': 1,
            'addressdetails': 1
        }
        headers = {'User-Agent': 'TennesseeGolfCourses/1.0'}
        response = requests.get(url, params=params, headers=headers, timeout=10)
        data = response.json()
        if data:
            return [float(data[0]['lon']), float(data[0]['lat'])], data[0].get('display_name', '')
    except:
        pass
    return None, None

results = []
for service_name, geocode_func in services:
    print(f"\n🔄 Trying {service_name}...")
    coords, formatted_addr = geocode_func(address)
    if coords:
        print(f"✅ {coords}")
        print(f"   Formatted: {formatted_addr}")
        results.append((service_name, coords, formatted_addr))
    else:
        print(f"❌ Failed")
    time.sleep(1)

print(f"\n{'='*60}")
print("COMPARISON OF RESULTS:")
print("="*60)

if results:
    for service, coords, addr in results:
        print(f"{service}: {coords}")
    
    # Calculate average if we have multiple results
    if len(results) > 1:
        avg_lon = sum(coord[0] for _, coord, _ in results) / len(results)
        avg_lat = sum(coord[1] for _, coord, _ in results) / len(results)
        print(f"\nAVERAGE COORDINATES: [{avg_lon:.6f}, {avg_lat:.6f}]")
        
        # Find the most precise (most decimal places)
        most_precise = max(results, key=lambda x: len(str(x[1][0]).split('.')[-1]))
        print(f"MOST PRECISE: {most_precise[0]} - {most_precise[1]}")
else:
    print("❌ ALL SERVICES FAILED")
    print("This address may not exist in geocoding databases")