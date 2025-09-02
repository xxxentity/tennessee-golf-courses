#!/usr/bin/env python3
"""
Final extraction of all golf course data with improved address handling
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
    
    # Extract course name from the PHP variable
    name_match = re.search(r'\$course_name = [\'"]([^\'";]+)[\'"];', content)
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
    
    # Extract city/state info
    city = ""
    city_match = re.search(r'City:</span>\s*<span[^>]*>([^<]+)</span>', content)
    if city_match:
        city = clean_html_from_text(city_match.group(1))
    
    # Alternative city pattern in address blocks
    if not city:
        alt_city_match = re.search(r'<br>\s*([^<]+,\s*TN[^<]*)', content)
        if alt_city_match:
            city = clean_html_from_text(alt_city_match.group(1))
    
    # Build full address
    if address and city:
        # Check if city info is already in address
        if any(x in address.upper() for x in ['TN', 'TENNESSEE']) or city.upper() in address.upper():
            full_address = address
        else:
            full_address = f"{address}, {city}"
    elif address:
        full_address = address
    elif city:
        full_address = city
    else:
        # Fallback: look in iframe src for address
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

def geocode_address(address):
    """Get GPS coordinates for an address"""
    if not address:
        return [0, 0]
    
    try:
        # Clean and prepare address
        clean_address = address.replace('\n', ' ').strip()
        
        # Ensure Tennessee is in address
        if 'TN' not in clean_address and 'Tennessee' not in clean_address:
            clean_address += ', TN'
        
        # Use Nominatim geocoding service
        base_url = "https://nominatim.openstreetmap.org/search"
        params = {
            'q': clean_address,
            'format': 'json',
            'limit': 1,
            'countrycodes': 'us',
            'addressdetails': 1
        }
        
        headers = {
            'User-Agent': 'TennesseeGolfCourses/1.0'
        }
        
        response = requests.get(base_url, params=params, headers=headers, timeout=15)
        response.raise_for_status()
        
        results = response.json()
        if results:
            lat = float(results[0]['lat'])
            lon = float(results[0]['lon'])
            return [lon, lat]  # [longitude, latitude]
        else:
            return [0, 0]
            
    except Exception as e:
        print(f"  Geocoding error for '{address}': {e}")
        return [0, 0]

def main():
    courses_dir = "/Users/entity./TGC LLC/courses"
    course_files = sorted(Path(courses_dir).glob("*.php"))
    
    all_course_data = []
    successful_geocodes = 0
    
    print(f"Processing {len(course_files)} course files...")
    
    for i, course_file in enumerate(course_files, 1):
        print(f"{i:2d}/{len(course_files)}: {course_file.name}")
        
        course_data = extract_course_data_from_file(course_file)
        if course_data:
            # Geocode the address
            coordinates = geocode_address(course_data['address'])
            course_data['coordinates'] = coordinates
            
            if coordinates != [0, 0]:
                successful_geocodes += 1
                print(f"     ✓ {course_data['address']} -> [{coordinates[0]:.5f}, {coordinates[1]:.5f}]")
            else:
                print(f"     ✗ Failed: {course_data['address']}")
            
            all_course_data.append(course_data)
            
            # Respectful delay
            time.sleep(1.1)
    
    # Save results
    output_file = "/Users/entity./TGC LLC/golf_course_data_final.json"
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump(all_course_data, f, indent=2, ensure_ascii=False)
    
    print(f"\n" + "="*60)
    print(f"EXTRACTION COMPLETE")
    print(f"="*60)
    print(f"Total courses: {len(all_course_data)}")
    print(f"Successfully geocoded: {successful_geocodes}")
    print(f"Failed geocoding: {len(all_course_data) - successful_geocodes}")
    print(f"Success rate: {successful_geocodes/len(all_course_data)*100:.1f}%")
    print(f"Results saved to: {output_file}")

if __name__ == "__main__":
    main()