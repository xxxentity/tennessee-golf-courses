#!/usr/bin/env python3
"""
Try different variations for Bear Trace at Harrison Bay
"""

import requests
import time

addresses_to_try = [
    "8919 Harrison Bay Road, Harrison, TN 37341",
    "8919 Harrison Bay Rd, Harrison, Tennessee 37341", 
    "Harrison Bay Golf Course, Harrison, TN",
    "Bear Trace Harrison Bay, TN"
]

def geocode_nominatim(address):
    try:
        url = "https://nominatim.openstreetmap.org/search"
        params = {
            'q': address,
            'format': 'json',
            'limit': 1,
            'countrycodes': 'us'
        }
        headers = {'User-Agent': 'TennesseeGolfCourses/1.0'}
        
        response = requests.get(url, params=params, headers=headers, timeout=10)
        results = response.json()
        
        if results:
            lat = float(results[0]['lat'])
            lon = float(results[0]['lon'])
            return [lon, lat], results[0].get('display_name', '')
        return None, None
    except:
        return None, None

print("Trying different address variations for Bear Trace at Harrison Bay...")

for address in addresses_to_try:
    print(f"\nTrying: {address}")
    coords, name = geocode_nominatim(address)
    if coords:
        print(f"✅ SUCCESS: {coords}")
        print(f"   Name: {name}")
        break
    else:
        print("❌ Failed")
    time.sleep(1)

# Manual coordinate based on Tennessee State Park system
print(f"\n🎯 MANUAL RESEARCH:")
print(f"Bear Trace at Harrison Bay is a Tennessee State Park golf course")
print(f"Located at Harrison Bay State Park on Chickamauga Lake")
print(f"Estimated coordinates: [-85.1347, 35.1195]")