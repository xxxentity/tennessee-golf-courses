#!/bin/bash

# Fix CSS and JS references in all news article PHP files
cd "/mnt/c/Users/ddhoward/TGC LLC/tennessee-golf-courses/news"

for file in *.php; do
    echo "Processing: $file"

    # Fix CSS references
    sed -i 's|<link rel="stylesheet" href="/css/style.css">|<link rel="stylesheet" href="/styles.css">|g' "$file"
    sed -i 's|<link rel="stylesheet" href="/css/news.css">||g' "$file"

    # Add Font Awesome and Google Fonts if not present
    if ! grep -q "fonts.googleapis.com" "$file"; then
        sed -i '/<link rel="stylesheet" href="\/styles.css">/a\    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">\n    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">' "$file"
    fi

    # Fix Google Analytics ID
    sed -i 's|G-XXXXXXXXXX|G-7VPNPCDTBP|g' "$file"

    # Add script.js before </body> if not present
    if ! grep -q "/script.js" "$file"; then
        sed -i '/<\/body>/i\    <!-- Scripts -->\n    <script src="/script.js"></script>' "$file"
    fi
done

echo "All news articles have been updated!"