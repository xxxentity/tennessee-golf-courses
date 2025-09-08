#!/bin/bash

# Fix all course pages to use PRG pattern
cd courses

for file in *.php; do
  # Check if file has the review posting code
  if grep -q "Your review has been posted successfully!" "$file" 2>/dev/null; then
    # Check if it doesn't already have the redirect
    if ! grep -q "header(\"Location:" "$file" 2>/dev/null; then
      echo "Fixing: $file"
      
      # Replace the success message line with redirect
      sed -i '' 's/$success_message = "Your review has been posted successfully!";/\/\/ Redirect to prevent duplicate submission on refresh (PRG pattern)\
                header("Location: " . $_SERVER['"'"'REQUEST_URI'"'"'] . "?success=1");\
                exit;/g' "$file"
      
      # Add success message handler before comment submission
      sed -i '' '/\/\/ Check if user is logged in/a\
\
\/\/ Check for success message from redirect\
if (isset($_GET['"'"'success'"'"']) && $_GET['"'"'success'"'"'] == '"'"'1'"'"') {\
    $success_message = "Your review has been posted successfully!";\
}
' "$file"
      
      echo "✅ Fixed $file"
    else
      echo "⏩ Already fixed: $file"
    fi
  fi
done

echo "All course files have been updated!"