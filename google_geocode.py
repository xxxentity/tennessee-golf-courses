#!/usr/bin/env python3
"""
Use Google Maps Geocoding API for maximum accuracy
"""

import requests
import json

# You can get a free Google Maps API key at: https://developers.google.com/maps/documentation/geocoding/get-api-key
# For now, let's try without API key using the public endpoint

def google_geocode_no_key(address):
    """Try Google geocoding without API key (limited)"""
    try:
        # This sometimes works for basic geocoding
        url = f"https://maps.googleapis.com/maps/api/geocode/json"
        params = {'address': address}
        
        response = requests.get(url, params=params, timeout=10)
        data = response.json()
        
        if data['status'] == 'OK' and data['results']:
            location = data['results'][0]['geometry']['location']
            return [location['lng'], location['lat']], data['results'][0]['formatted_address']
        else:
            return None, f"Status: {data.get('status', 'Unknown')}"
    except Exception as e:
        return None, str(e)

def alternative_search(course_name, address):
    """Manual lookup suggestions"""
    print(f"\n🔍 MANUAL SEARCH SUGGESTIONS for {course_name}:")
    print(f"1. Search Google Maps: '{address}'")
    print(f"2. Search Google Maps: '{course_name}'") 
    print(f"3. Search: '{course_name} golf course GPS coordinates'")
    print(f"4. Check course website for exact location")
    
    # Provide some educated guesses based on known patterns
    if "chattanooga golf" in course_name.lower():
        print(f"💡 This is likely on the north side of Chattanooga near the river")
        print(f"💡 Try coordinates around: [-85.28xx, 35.07xx]")

# Test address
address = "1511 Riverview Road, Chattanooga, TN 37405"
course = "Chattanooga Golf & Country Club"

print(f"Trying to geocode: {address}")

coords, result = google_geocode_no_key(address)
if coords:
    print(f"✅ Google result: {coords}")
    print(f"   Formatted: {result}")
else:
    print(f"❌ Google failed: {result}")
    alternative_search(course, address)

print(f"\n🎯 FASTEST SOLUTION:")
print(f"1. Go to maps.google.com")
print(f"2. Search: 'Chattanooga Golf & Country Club'")
print(f"3. Right-click on the golf course location")
print(f"4. Select 'What's here?' to get exact coordinates")
print(f"5. Copy the lat,lng values and I'll update immediately")

print(f"\n📍 ALTERNATIVE: Use the course's Google Maps embed")
print(f"Check if the course page has a Google Maps iframe - those usually have exact coords")