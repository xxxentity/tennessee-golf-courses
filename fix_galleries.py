#!/usr/bin/env python3

import os
import re

def fix_gallery_sections():
    courses_dir = "/Users/entity./TGC LLC/courses"
    
    print("Fixing gallery sections to show exactly 3 preview images...")
    
    # Counters for tracking
    card_type_fixed = 0
    section_type_fixed = 0
    total_processed = 0
    
    # Get all PHP files in courses directory
    for filename in os.listdir(courses_dir):
        if filename.endswith('.php'):
            file_path = os.path.join(courses_dir, filename)
            total_processed += 1
            
            # Read the file
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Save original content
            original_content = content
            
            # Fix gallery loops ONLY (not star rating loops)
            # This is more specific to avoid affecting star ratings again
            
            # Pattern 1: course-info-card gallery with PHP loop (like Greystone)
            if 'course-info-card' in content and 'Course Gallery' in content:
                # Look for PHP loop inside gallery section specifically
                gallery_section_pattern = r'(<div class="course-info-card"[^>]*>.*?<h3[^>]*><i class="fas fa-camera"></i> Course Gallery</h3>.*?<div class="gallery-grid"[^>]*>)\s*<?php for \(\$i = 1; \$i <= \d+; \$i\+\+\): \?>\s*(<div class="gallery-item"[^>]*>[^<]*</div>)\s*<?php endfor; \?>'
                
                def fix_card_gallery(match):
                    prefix = match.group(1)
                    gallery_item = match.group(2)
                    
                    # Generate 3 specific gallery items
                    new_items = []
                    for i in range(1, 4):
                        new_item = re.sub(r'\$i', str(i), gallery_item)
                        new_items.append(f"                    {new_item}")
                    
                    return f"{prefix}\n" + "\n".join(new_items)
                
                new_content = re.sub(gallery_section_pattern, fix_card_gallery, content, flags=re.DOTALL)
                if new_content != content:
                    content = new_content
                    card_type_fixed += 1
                    print(f"✓ Fixed course-info-card gallery in {filename}")
            
            # Pattern 2: photo-gallery section (like Bear Trace)
            elif 'photo-gallery' in content and 'Course Gallery' in content:
                # These already show 3 specific images, just ensure button text is correct
                button_pattern = r'(View Full Gallery \()(\d+)( Photos?\))'
                content = re.sub(button_pattern, r'\g<1>25\g<3>', content)
                
                if content != original_content:
                    section_type_fixed += 1
                    print(f"✓ Fixed photo-gallery section in {filename}")
            
            # Write the updated content if changes were made
            if content != original_content:
                with open(file_path, 'w', encoding='utf-8') as f:
                    f.write(content)
    
    print(f"\n📊 Gallery Fix Summary:")
    print(f"Files processed: {total_processed}")
    print(f"course-info-card galleries fixed: {card_type_fixed}")
    print(f"photo-gallery sections fixed: {section_type_fixed}")
    print(f"Total galleries updated: {card_type_fixed + section_type_fixed}")
    print(f"\n✅ Gallery standardization complete!")
    
    print(f"\n📋 Gallery Structure Summary:")
    print(f"• course-info-card type: {total_processed - section_type_fixed} files")
    print(f"• photo-gallery type: {section_type_fixed} files")

if __name__ == "__main__":
    fix_gallery_sections()