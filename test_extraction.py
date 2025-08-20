#!/usr/bin/env python3
"""
Test extraction for a few courses to debug issues
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

def test_geocoding(address):
    """Test geocoding for a single address"""
    if not address:
        return [0, 0]
    
    try:
        # Clean address for better geocoding
        clean_address = address.replace('\n', ' ').strip()
        
        # Add Tennessee if not present
        if 'TN' not in clean_address and 'Tennessee' not in clean_address:
            clean_address += ', TN'
        
        print(f"  Trying to geocode: {clean_address}")
        
        # Use Nominatim (free OpenStreetMap geocoding service)
        base_url = "https://nominatim.openstreetmap.org/search"
        params = {
            'q': clean_address,
            'format': 'json',
            'limit': 3,
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
            print(f"  Found {len(results)} results:")
            for i, result in enumerate(results[:2]):
                lat = float(result['lat'])
                lon = float(result['lon'])
                display_name = result.get('display_name', 'Unknown')
                print(f"    {i+1}. [{lon:.6f}, {lat:.6f}] - {display_name}")
            
            # Use the first result
            lat = float(results[0]['lat'])
            lon = float(results[0]['lon'])
            return [lon, lat]
        else:
            print(f"  ✗ No results found")
            return [0, 0]
            
    except Exception as e:
        print(f"  ✗ Geocoding error: {e}")
        return [0, 0]

def main():
    # Test with just a few courses
    test_courses = [
        "/Users/entity./TGC LLC/courses/avalon-golf-country-club.php",
        "/Users/entity./TGC LLC/courses/bear-trace-at-tims-ford.php",
        "/Users/entity./TGC LLC/courses/belle-acres-golf-course.php",
        "/Users/entity./TGC LLC/courses/tpc-southwind.php",
        "/Users/entity./TGC LLC/courses/nashville-national-golf-links.php"
    ]
    
    results = []
    
    for course_file in test_courses:
        print(f"\n{'='*60}")
        print(f"Processing: {Path(course_file).name}")
        print(f"{'='*60}")
        
        course_data = extract_course_data_from_file(course_file)
        if course_data:
            print(f"Name: {course_data['name']}")
            print(f"Address: {course_data['address']}")
            print(f"Phone: {course_data['phone']}")
            print(f"Type: {course_data['type']}")
            
            # Test geocoding
            coordinates = test_geocoding(course_data['address'])
            course_data['coordinates'] = coordinates
            
            results.append(course_data)
            
            time.sleep(2)  # Be respectful to the geocoding service
    
    print(f"\n{'='*60}")
    print("FINAL RESULTS")
    print(f"{'='*60}")
    
    for course in results:
        coords_str = f"[{course['coordinates'][0]:.6f}, {course['coordinates'][1]:.6f}]" if course['coordinates'] != [0, 0] else "No coordinates"
        print(f"\n{course['name']}")
        print(f"  Address: {course['address']}")
        print(f"  Phone: {course['phone']}")
        print(f"  Type: {course['type']}")
        print(f"  Coordinates: {coords_str}")
        print(f"  Slug: {course['slug']}")

if __name__ == "__main__":
    main()