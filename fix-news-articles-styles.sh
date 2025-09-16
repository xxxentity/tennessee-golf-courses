#!/bin/bash

# Add inline styles to all news article PHP files
cd "/mnt/c/Users/ddhoward/TGC LLC/tennessee-golf-courses/news"

# Read the styles content
STYLES=$(<../news-article-styles.php)

for file in *.php; do
    if [[ "$file" == "test-fresh.php" ]]; then
        continue
    fi

    echo "Adding styles to: $file"

    # Check if styles already exist
    if ! grep -q "News Article Styles" "$file"; then
        # Add the styles right after the Google Analytics script
        sed -i "/gtag('config', 'G-7VPNPCDTBP');/a\\
    </script>\\
    \\
    ${STYLES//\\/\\\\}\\
" "$file"
    fi
done

echo "All news articles have been updated with inline styles!"