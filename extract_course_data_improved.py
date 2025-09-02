#!/usr/bin/env python3
"""
Extract golf course data from PHP files and geocode addresses
Improved version with better address cleaning and error handling
"""

import os
import re
import json
import requests
import time
from pathlib import Path
import html

def clean_html_from_text(text):
    """Remove HTML tags and decode HTML entities from text"""
    # Decode HTML entities
    text = html.unescape(text)
    # Remove HTML tags
    text = re.sub(r'<[^>]+>', '', text)
    # Clean up whitespace
    text = ' '.join(text.split())
    return text.strip()

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
    
    # Extract address - multiple patterns
    address = ""
    
    # Pattern 1: Address: in span
    address_match = re.search(r'Address:</span>\s*<span[^>]*>([^<]+)</span>', content)
    if address_match:
        address = clean_html_from_text(address_match.group(1))
    
    # Pattern 2: Address in <p><strong> format
    if not address:
        alt_address_match = re.search(r'<p><strong>Address:</strong><br>\s*([^<]+)<br>', content)
        if alt_address_match:
            address = clean_html_from_text(alt_address_match.group(1))
    
    # Extract city info
    city = ""
    city_match = re.search(r'City:</span>\s*<span[^>]*>([^<]+)</span>', content)
    if city_match:
        city = clean_html_from_text(city_match.group(1))
    
    # If we found both address and city separately, combine them
    if address and city and address not in city:
        full_address = f"{address}, {city}"
    elif address:
        full_address = address
    elif city:
        full_address = city
    else:
        # Look in iframe src for address as fallback
        iframe_match = re.search(r'maps\.google\.com/maps\?q=([^&"]+)', content)
        if iframe_match:
            full_address = iframe_match.group(1).replace('+', ' ').replace('%20', ' ')
            full_address = clean_html_from_text(full_address)
        else:
            full_address = ""
    
    # Extract phone number
    phone = ""
    phone_match = re.search(r'Phone:</span>\s*<span[^>]*>([^<]+)</span>', content)
    if not phone_match:
        phone_match = re.search(r'<p><strong>Phone:</strong><br>\s*([^<]+)</p>', content)
    
    if phone_match:
        phone = clean_html_from_text(phone_match.group(1))
    
    # Extract course type
    course_type = ""
    type_match = re.search(r'Type:</span>\s*<span[^>]*>([^<]+)</span>', content)
    if type_match:
        course_type = clean_html_from_text(type_match.group(1))
    
    return {
        'name': course_name,
        'address': full_address,
        'phone': phone,
        'type': course_type,
        'slug': slug
    }

def geocode_address_google(address):
    """Geocode using Google Maps (requires API key - not included for security)"""
    # This would require a Google Maps API key
    # return [0, 0]  # Placeholder
    pass

def geocode_address_nominatim(address):
    """Get GPS coordinates for an address using Nominatim (OpenStreetMap)"""
    if not address:
        return [0, 0]
    
    try:
        # Clean address for better geocoding
        clean_address = address.replace('\n', ' ').strip()
        
        # Add Tennessee if not present
        if 'TN' not in clean_address and 'Tennessee' not in clean_address:
            clean_address += ', TN'
        
        # Use Nominatim (free OpenStreetMap geocoding service)
        base_url = "https://nominatim.openstreetmap.org/search"
        params = {
            'q': clean_address,
            'format': 'json',
            'limit': 1,
            'countrycodes': 'us',
            'addressdetails': 1
        }
        
        headers = {
            'User-Agent': 'TennesseeGolfCourses/1.0 (contact@example.com)'
        }
        
        response = requests.get(base_url, params=params, headers=headers, timeout=15)
        response.raise_for_status()
        
        results = response.json()
        if results:
            lat = float(results[0]['lat'])
            lon = float(results[0]['lon'])
            print(f"  ✓ Geocoded: {clean_address} -> [{lon:.6f}, {lat:.6f}]")
            return [lon, lat]  # Return as [longitude, latitude]
        else:
            print(f"  ✗ No results for: {clean_address}")
            return [0, 0]
            
    except Exception as e:
        print(f"  ✗ Geocoding error for '{address}': {e}")
        return [0, 0]

# Manual address corrections for problematic cases
ADDRESS_CORRECTIONS = {
    "1299 Oak Chase Blvd, Lenoir City, TN 37772": "1299 Oak Chase Blvd, Lenoir City, TN",
    "570 Bear Trace Drive, Winchester, TN 37398": "570 Bear Trace Dr, Winchester, TN",
    "8919 Harrison Bay Road, Harrison, TN 37341": "8919 Harrison Bay Rd, Harrison, TN",
    "550 Johnny Cash Parkway, Hendersonville, TN 37075": "550 Johnny Cash Pkwy, Hendersonville, TN",
    "18 Springs Blvd, Nashville, TN 37214": "18 Springhouse Ln, Nashville, TN",
}

def main():
    courses_dir = "/Users/entity./TGC LLC/courses"
    course_files = sorted(Path(courses_dir).glob("*.php"))
    
    all_course_data = []
    successful_geocodes = 0
    
    print(f"Processing {len(course_files)} course files...")
    
    for i, course_file in enumerate(course_files, 1):
        print(f"\nProcessing {i}/{len(course_files)}: {course_file.name}")
        
        course_data = extract_course_data_from_file(course_file)
        if course_data:
            print(f"  Name: {course_data['name']}")
            print(f"  Address: {course_data['address']}")
            print(f"  Phone: {course_data['phone']}")
            print(f"  Type: {course_data['type']}")
            
            # Apply manual corrections if needed
            address_for_geocoding = course_data['address']
            if address_for_geocoding in ADDRESS_CORRECTIONS:
                address_for_geocoding = ADDRESS_CORRECTIONS[address_for_geocoding]
                print(f"  Using corrected address: {address_for_geocoding}")
            
            # Geocode the address
            coordinates = geocode_address_nominatim(address_for_geocoding)
            course_data['coordinates'] = coordinates
            
            if coordinates != [0, 0]:
                successful_geocodes += 1
            
            all_course_data.append(course_data)
            
            # Small delay to be respectful to the geocoding service
            time.sleep(1.2)
    
    # Save results
    output_file = "/Users/entity./TGC LLC/course_locations_complete.json"
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump(all_course_data, f, indent=2, ensure_ascii=False)
    
    print(f"\n" + "="*60)
    print(f"EXTRACTION COMPLETE")
    print(f"="*60)
    print(f"Total courses processed: {len(all_course_data)}")
    print(f"Successfully geocoded: {successful_geocodes}")
    print(f"Failed geocoding: {len(all_course_data) - successful_geocodes}")
    print(f"Results saved to: {output_file}")
    
    # Display sample results
    print("\nSample results:")
    for course in all_course_data[:3]:
        coords_str = f"[{course['coordinates'][0]:.6f}, {course['coordinates'][1]:.6f}]" if course['coordinates'] != [0, 0] else "No coordinates"
        print(f"  {course['name']}")
        print(f"    Address: {course['address']}")
        print(f"    Coordinates: {coords_str}")
        print()

if __name__ == "__main__":
    main()