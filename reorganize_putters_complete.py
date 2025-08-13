#!/usr/bin/env python3

import re

def reorganize_putters_complete():
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
    
    # Extract all putter sections - split by comment headers
    middle_content = content[start_pos:end_pos]
    
    # Split into sections by putter comments
    putter_sections = {}
    
    # Use regex to find all putter sections
    pattern = r'(<!-- #(\d+) Putter -->.*?)(?=<!-- #\d+ Putter -->|<!-- Quick Links to Amazon -->|$)'
    matches = re.findall(pattern, middle_content, re.DOTALL)
    
    for full_match, putter_number in matches:
        putter_sections[int(putter_number)] = full_match.strip()
    
    print(f"Found {len(putter_sections)} putter sections")
    print(f"Putter numbers: {sorted(putter_sections.keys())}")
    
    # Now reorganize the content with the ID and rank updates
    reorganized_content = ""
    correct_order = [10, 9, 8, 7, 6, 5, 4, 3, 2, 1]
    
    for new_rank in correct_order:
        # Find which original putter should be at this rank
        # Based on temp_putter_sections.txt, we know:
        # Rank 10: Original #10 (Bettinardi)
        # Rank 9: Original #9 (TaylorMade TP Black)
        # Rank 8: Original #8 (Cleveland HB Soft)
        # etc.
        original_number = new_rank  # They should match since we want correct countdown
        
        if original_number in putter_sections:
            section = putter_sections[original_number]
            
            # Update the rank display and ID in this section
            # Change the rank number
            section = re.sub(r'<div class="putter-rank">#\d+</div>', f'<div class="putter-rank">#{new_rank}</div>', section)
            
            # Update the ID
            section = re.sub(r'id="putter-\d+"', f'id="putter-{new_rank}"', section)
            
            reorganized_content += section + "\n\n"
        else:
            print(f"Warning: Original putter #{original_number} not found")
    
    # Combine everything back together
    final_content = before_putters + reorganized_content.rstrip() + "\n\n" + after_putters
    
    # Write the reorganized content back
    with open('reviews/top-10-putters-2025-amazon-guide.php', 'w') as f:
        f.write(final_content)
    
    print("File reorganized successfully with correct content order!")

if __name__ == "__main__":
    reorganize_putters_complete()