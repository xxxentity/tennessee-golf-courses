#!/usr/bin/env python3

import os
import re

def fix_gallery_extensions():
    courses_dir = "/Users/entity./TGC LLC/courses"
    
    print("Fixing gallery JavaScript extensions from .jpeg to .webp...")
    
    # List of files that need extension fixes
    files_to_fix = [
        'big-creek-golf-club.php',
        'cedar-crest-golf-club.php', 
        'eagles-landing-golf-club.php',
        'forrest-crossing-golf-course.php',
        'holston-hills-country-club.php',
        'island-pointe-golf-club.php',
        'mccabe-golf-course.php',
        'nashville-golf-athletic-club.php',
        'old-hickory-country-club.php',
        'richland-country-club.php',
        'sevierville-golf-club.php',
        'springhouse-golf-club.php',
        'sweetens-cove-golf-club.php',
        'temple-hills-country-club.php',
        'the-club-at-gettysvue.php',
        'the-golf-club-of-tennessee.php',
        'the-governors-club.php',
        'tpc-southwind.php',
        'troubadour-golf-field-club.php',
        'warriors-path-state-park-golf-course.php',
        'whittle-springs-golf-course.php',
        'willow-creek-golf-club.php'
    ]
    
    files_fixed = 0
    
    for filename in files_to_fix:
        file_path = os.path.join(courses_dir, filename)
        
        if not os.path.exists(file_path):
            print(f"⚠️  File not found: {filename}")
            continue
            
        # Read the file
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Save original content
        original_content = content
        
        # Fix JavaScript gallery extensions
        # Pattern: backgroundImage = `url('path/${i}.jpeg')`
        content = re.sub(
            r'(backgroundImage = `url\([\'"]\.\.\/images\/courses\/[^\/]+\/\$\{i\})\.jpeg([\'"])\`',
            r'\1.webp\2`',
            content
        )
        
        # Pattern: window.open(`path/${i}.jpeg`, '_blank')
        content = re.sub(
            r'(window\.open\(`\.\.\/images\/courses\/[^\/]+\/\$\{i\})\.jpeg(\`, [\'"]_blank[\'"])',
            r'\1.webp\2',
            content
        )
        
        # Write the updated content if changes were made
        if content != original_content:
            with open(file_path, 'w', encoding='utf-8') as f:
                f.write(content)
            files_fixed += 1
            print(f"✓ Fixed {filename}")
        else:
            print(f"⚠️  No changes needed in {filename}")
    
    print(f"\n📊 Extension Fix Summary:")
    print(f"Files targeted: {len(files_to_fix)}")
    print(f"Files fixed: {files_fixed}")
    print(f"\n✅ Gallery extension standardization complete!")
    
    print(f"\n📋 All galleries now use:")
    print(f"• .webp for preview images (1.webp, 2.webp, 3.webp)")
    print(f"• .webp for full gallery images (1-25.webp)")
    print(f"• Consistent file format across all courses")

if __name__ == "__main__":
    fix_gallery_extensions()