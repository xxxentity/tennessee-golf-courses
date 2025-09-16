#!/usr/bin/env python3
import os
import re

news_dir = "/mnt/c/Users/ddhoward/TGC LLC/tennessee-golf-courses/news"

for filename in os.listdir(news_dir):
    if filename.endswith('.php') and filename != 'test-fresh.php':
        filepath = os.path.join(news_dir, filename)

        # Skip already fixed files (2025-tour-championship is our reference)
        if filename == '2025-tour-championship-atlanta-predictions.php':
            print(f"Skipping {filename} - reference file")
            continue

        # Skip the one we just fixed
        if filename == 'liv-golf-michigan-2025-semifinals-thriller.php':
            print(f"Skipping {filename} - already fixed")
            continue

        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        # Check if it has the old structure with duplicate meta tags
        if '<meta name="description" content="<?php echo $article' not in content:
            print(f"Skipping {filename} - already has correct structure")
            continue

        # Remove the duplicate $article array definition
        content = re.sub(
            r"\$article = \[[\s\S]*?\];\s*\?>",
            "?>",
            content
        )

        # Remove duplicate meta tags (keep only SEO::generateMetaTags())
        # Find and replace the entire meta section
        pattern = r'(\s*<\?php echo SEO::generateMetaTags\(\); \?>\s*\n)(.*?)(\s*<!-- Stylesheets -->|\s*<!-- Favicon -->)'

        def replace_meta(match):
            seo_line = match.group(1)
            # Return just the SEO line and skip to stylesheets
            return seo_line

        content = re.sub(pattern, replace_meta, content, flags=re.DOTALL)

        # Fix the stylesheet and favicon section
        old_styles = r'    <!-- Stylesheets -->\s*\n    <link rel="stylesheet" href="/styles.css">'
        new_styles = '    <link rel="stylesheet" href="/styles.css?v=5">'
        content = content.replace(old_styles, new_styles)

        # Fix favicon to use the correct logo
        old_favicon = r'    <!-- Favicon -->\s*\n    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">\s*\n    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">'
        new_favicon = '''
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=5">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=5">'''

        content = re.sub(old_favicon, new_favicon, content)

        # Replace $article references with $article_data
        content = content.replace("$article['title']", "$article_data['title']")
        content = content.replace("$article['author']", "$article_data['author']")
        content = content.replace("$article['date']", "$article_data['date']")
        content = content.replace("$article['image']", "$article_data['image']")
        content = content.replace("$article['excerpt']", "$article_data['description']")
        content = content.replace("$article['category']", "$article_data['category']")

        # Fix the time display
        content = re.sub(
            r'<time datetime="<\?php echo \$article_data\[\'date\'\]; \?>T<\?php echo date\(\'H:i\', strtotime\(\$article\[\'time\'\]\)\); \?>">\s*<\?php echo date\(\'F j, Y\', strtotime\(\$article_data\[\'date\'\]\)\); \?> at <\?php echo \$article\[\'time\'\]; \?>',
            '<time datetime="<?php echo $article_data[\'date\']; ?>">\n                            <?php echo date(\'F j, Y\', strtotime($article_data[\'date\'])); ?>',
            content
        )

        # Fix read time (hardcode as it's not in article_data)
        content = re.sub(
            r'<span class="read-time"><\?php echo \$article\[\'read_time\'\]; \?></span>',
            '<span class="read-time">5 min read</span>',
            content
        )

        # Fix share links to use $article_slug
        content = content.replace("$article['slug']", "$article_slug")

        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)

        print(f"Fixed {filename}")

print("All news articles have been fixed!")