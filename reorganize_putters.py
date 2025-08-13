#!/usr/bin/env python3

import re

def reorganize_putters():
    # Read the file
    with open('reviews/top-10-putters-2025-amazon-guide.php', 'r') as f:
        content = f.read()
    
    # Find the start of putter sections (after the ol list)
    start_marker = '                <!-- #10 Putter -->'
    start_pos = content.find(start_marker)
    
    # Find the end of putter sections (before Quick Links)
    end_marker = '                <!-- Quick Links to Amazon -->'
    end_pos = content.find(end_marker)
    
    if start_pos == -1 or end_pos == -1:
        print("Could not find start or end markers")
        return
    
    # Extract the sections before and after putters
    before_putters = content[:start_pos]
    after_putters = content[end_pos:]
    
    # Extract all putter sections using regex
    putter_pattern = r'(<!-- #(\d+) Putter -->.*?)(?=<!-- #\d+ Putter -->|<!-- Quick Links to Amazon -->)'
    putter_sections = {}
    
    # Get all putter sections from the middle part
    middle_content = content[start_pos:end_pos]
    
    # Split by putter comments and process
    putter_parts = re.split(r'(<!-- #\d+ Putter -->)', middle_content)
    
    current_section = ""
    current_number = None
    
    for part in putter_parts:
        if part.startswith('<!-- #') and part.endswith(' Putter -->'):
            # Save previous section if exists
            if current_number is not None and current_section:
                putter_sections[current_number] = current_section.strip()
            
            # Extract putter number
            number_match = re.search(r'<!-- #(\d+) Putter -->', part)
            if number_match:
                current_number = int(number_match.group(1))
                current_section = part + '\n'
        else:
            current_section += part
    
    # Save the last section
    if current_number is not None and current_section:
        putter_sections[current_number] = current_section.strip()
    
    print(f"Found {len(putter_sections)} putter sections")
    print(f"Putter numbers: {sorted(putter_sections.keys())}")
    
    # Reorganize in correct order: 10, 9, 8, 7, 6, 5, 4, 3, 2, 1
    correct_order = [10, 9, 8, 7, 6, 5, 4, 3, 2, 1]
    
    reorganized_content = ""
    for putter_num in correct_order:
        if putter_num in putter_sections:
            reorganized_content += putter_sections[putter_num] + "\n\n"
        else:
            print(f"Warning: Putter #{putter_num} not found")
    
    # Combine everything back together
    final_content = before_putters + reorganized_content.rstrip() + "\n\n" + after_putters
    
    # Write the reorganized content back
    with open('reviews/top-10-putters-2025-amazon-guide.php', 'w') as f:
        f.write(final_content)
    
    print("File reorganized successfully!")

if __name__ == "__main__":
    reorganize_putters()