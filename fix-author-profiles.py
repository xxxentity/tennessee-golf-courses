#!/usr/bin/env python3
import os
import re

news_dir = "/mnt/c/Users/ddhoward/TGC LLC/tennessee-golf-courses/news"

# Author profile link structure
author_profile_link = '<a href="/profile/ColeH" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/cole-harrington.webp" alt="Cole Harrington" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform=\'scale(1.1)\'" onmouseout="this.style.transform=\'scale(1)\'"><span style="text-decoration: underline;">Cole Harrington</span></a>'

for filename in os.listdir(news_dir):
    if filename.endswith('.php') and filename != 'test-fresh.php':
        filepath = os.path.join(news_dir, filename)

        # Skip files that already have the correct structure
        if filename in ['2025-tour-championship-atlanta-predictions.php', 'liv-golf-michigan-2025-semifinals-thriller.php']:
            print(f"Skipping {filename} - already has correct author structure")
            continue

        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        # Check if it has the old simple author structure
        old_pattern = r'<span><i class="far fa-user"></i> Cole Harrington</span>'
        if old_pattern not in content:
            print(f"Skipping {filename} - doesn't match expected pattern")
            continue

        # Replace the old author span with the new profile link
        new_content = content.replace(
            '<span><i class="far fa-user"></i> Cole Harrington</span>',
            f'<span>{author_profile_link}</span>'
        )

        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)

        print(f"Updated {filename}")

print("All news articles have been updated with author profiles!")