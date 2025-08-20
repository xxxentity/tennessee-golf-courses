#!/usr/bin/env python3
"""
Extract golf course data from PHP files including:
- Course name
- Address 
- Phone
- Course type
- Slug
Then geocode addresses to get GPS coordinates
"""

import os
import re
import json
import requests
import time
from pathlib import Path

def extract_course_data_from_file(file_path):
    """Extract course data from a single PHP file"""
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
    except Exception as e:
        print(f"Error reading {file_path}: {e}")
        return None
    
    # Get slug from filename
    slug = Path(file_path).stem
    
    # Extract course name from the PHP variable or title tag
    name_match = re.search(r'\$course_name = [\'"]([^\'";]+)[\'"];', content)
    if not name_match:
        name_match = re.search(r'<title>([^-]+)', content)
    
    course_name = name_match.group(1).strip() if name_match else slug.replace('-', ' ').title()
    
    # Extract address - looking for the Address: pattern
    address_match = re.search(r'Address:</span>\s*<span[^>]*>([^<]+)</span>', content)
    address = address_match.group(1).strip() if address_match else ""
    
    # Extract city info - sometimes combined with address
    city_match = re.search(r'City:</span>\s*<span[^>]*>([^<]+)</span>', content)
    city = city_match.group(1).strip() if city_match else ""
    
    # Combine address and city for full address
    if address and city:
        full_address = f"{address}, {city}"
    elif address:
        full_address = address
    elif city:
        full_address = city
    else:
        # Alternative pattern - look for address in different formats
        alt_address_match = re.search(r'<p><strong>Address:</strong><br>\s*([^<]+)<br>\s*([^<]+)</p>', content)
        if alt_address_match:
            full_address = f"{alt_address_match.group(1).strip()}, {alt_address_match.group(2).strip()}"
        else:
            # Look in iframe src for address
            iframe_match = re.search(r'maps\.google\.com/maps\?q=([^&]+)', content)
            if iframe_match:
                full_address = iframe_match.group(1).replace('+', ' ')
            else:
                full_address = ""
    
    # Extract phone number
    phone_match = re.search(r'Phone:</span>\s*<span[^>]*>([^<]+)</span>', content)
    if not phone_match:
        phone_match = re.search(r'<p><strong>Phone:</strong><br>\s*([^<]+)</p>', content)
    
    phone = phone_match.group(1).strip() if phone_match else ""
    
    # Extract course type
    type_match = re.search(r'Type:</span>\s*<span[^>]*>([^<]+)</span>', content)
    course_type = type_match.group(1).strip() if type_match else ""
    
    return {
        'name': course_name,
        'address': full_address,
        'phone': phone,
        'type': course_type,
        'slug': slug
    }

def geocode_address(address):
    """Get GPS coordinates for an address using Nominatim (OpenStreetMap)"""
    if not address:
        return [0, 0]
    
    try:
        # Clean address for better geocoding
        clean_address = address.replace('\n', ' ').strip()
        
        # Use Nominatim (free OpenStreetMap geocoding service)
        base_url = "https://nominatim.openstreetmap.org/search"
        params = {
            'q': clean_address,
            'format': 'json',
            'limit': 1,
            'countrycodes': 'us'
        }
        
        headers = {
            'User-Agent': 'TennesseeGolfCourses/1.0'
        }
        
        response = requests.get(base_url, params=params, headers=headers, timeout=10)
        response.raise_for_status()
        
        results = response.json()
        if results:
            lat = float(results[0]['lat'])
            lon = float(results[0]['lon'])
            return [lon, lat]  # Return as [longitude, latitude]
        else:
            print(f"No geocoding results for: {address}")
            return [0, 0]
            
    except Exception as e:
        print(f"Geocoding error for '{address}': {e}")
        return [0, 0]

def main():
    courses_dir = "/Users/entity./TGC LLC/courses"
    course_files = sorted(Path(courses_dir).glob("*.php"))
    
    all_course_data = []
    
    print(f"Processing {len(course_files)} course files...")
    
    for i, course_file in enumerate(course_files, 1):
        print(f"Processing {i}/{len(course_files)}: {course_file.name}")
        
        course_data = extract_course_data_from_file(course_file)
        if course_data:
            # Geocode the address
            coordinates = geocode_address(course_data['address'])
            course_data['coordinates'] = coordinates
            
            all_course_data.append(course_data)
            
            # Small delay to be respectful to the geocoding service
            time.sleep(1)
    
    # Save results
    output_file = "/Users/entity./TGC LLC/course_locations.json"
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump(all_course_data, f, indent=2, ensure_ascii=False)
    
    print(f"\nExtracted data for {len(all_course_data)} courses")
    print(f"Results saved to: {output_file}")
    
    # Display first few results
    print("\nFirst 5 courses:")
    for course in all_course_data[:5]:
        print(f"  {course['name']}: {course['address']} -> {course['coordinates']}")

if __name__ == "__main__":
    main()