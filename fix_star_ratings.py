#!/usr/bin/env python3

import os
import re

def fix_star_ratings():
    courses_dir = "/Users/entity./TGC LLC/courses"
    
    print("Fixing broken star rating loops...")
    
    # Counter for tracking fixes
    files_fixed = 0
    total_fixes = 0
    
    # Get all PHP files in courses directory
    for filename in os.listdir(courses_dir):
        if filename.endswith('.php'):
            file_path = os.path.join(courses_dir, filename)
            
            # Read the file
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Save original content
            original_content = content
            
            # Fix star rating loops (NOT gallery loops)
            # Pattern 1: Star ratings in hero section
            content = re.sub(
                r'for \(\$i = 1; \$i <= 3; \$i\+\+\) \{\s*if \(\$i <= \$full_stars\)',
                r'for ($i = 1; $i <= 5; $i++) {\n                            if ($i <= $full_stars)',
                content
            )
            
            # Pattern 2: Star ratings in review sections
            content = re.sub(
                r'for \(\$i = 1; \$i <= 3; \$i\+\+\):\s*\?>\s*<i class="fas fa-star',
                r'for ($i = 1; $i <= 5; $i++): ?>\n                                        <i class="fas fa-star',
                content
            )
            
            # Pattern 3: Alternative star rating pattern
            content = re.sub(
                r'<?php for \(\$i = 1; \$i <= 3; \$i\+\+\): \?>\s*<i class="fas fa-star',
                r'<?php for ($i = 1; $i <= 5; $i++): ?>\n                                        <i class="fas fa-star',
                content
            )
            
            # Count fixes made in this file
            file_fixes = 0
            if content != original_content:
                # Count how many rating loops were fixed
                file_fixes = len(re.findall(r'for \(\$i = 1; \$i <= 5; \$i\+\+\)', content)) - len(re.findall(r'for \(\$i = 1; \$i <= 5; \$i\+\+\)', original_content))
                
                # Write the updated content back
                with open(file_path, 'w', encoding='utf-8') as f:
                    f.write(content)
                
                files_fixed += 1
                total_fixes += file_fixes
                print(f"✓ Fixed {file_fixes} star rating loops in {filename}")
    
    print(f"\n📊 Summary:")
    print(f"Files processed: {len([f for f in os.listdir(courses_dir) if f.endswith('.php')])}")
    print(f"Files fixed: {files_fixed}")
    print(f"Total star rating loops fixed: {total_fixes}")
    print(f"\n✅ Star rating fix complete!")

if __name__ == "__main__":
    fix_star_ratings()