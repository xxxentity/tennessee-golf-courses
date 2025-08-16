<?php
/**
 * Script to add CSRF protection to all course comment forms
 */

$courses_dir = __DIR__ . '/courses/';
$files = glob($courses_dir . '*.php');

$updated_count = 0;
$errors = [];

foreach ($files as $file) {
    $filename = basename($file);
    echo "Processing: $filename\n";
    
    $content = file_get_contents($file);
    if ($content === false) {
        $errors[] = "Could not read $filename";
        continue;
    }
    
    // Check if file needs CSRF updates
    $needs_update = false;
    $original_content = $content;
    
    // 1. Add CSRF require statement after session_start if not already present
    if (strpos($content, "require_once '../includes/csrf.php';") === false) {
        $content = str_replace(
            "require_once '../config/database.php';",
            "require_once '../config/database.php';\nrequire_once '../includes/csrf.php';",
            $content
        );
        $needs_update = true;
    }
    
    // 2. Add CSRF validation to comment submission
    if (strpos($content, 'CSRFProtection::validateToken') === false && 
        strpos($content, "if (\$_SERVER['REQUEST_METHOD'] === 'POST' && \$is_logged_in)") !== false) {
        
        $old_pattern = "if (\$_SERVER['REQUEST_METHOD'] === 'POST' && \$is_logged_in) {\n    \$rating = (int)\$_POST['rating'];";
        $new_replacement = "if (\$_SERVER['REQUEST_METHOD'] === 'POST' && \$is_logged_in) {\n    // Validate CSRF token\n    \$csrf_token = \$_POST['csrf_token'] ?? '';\n    if (!CSRFProtection::validateToken(\$csrf_token)) {\n        \$error_message = 'Security token expired or invalid. Please refresh the page and try again.';\n    } else {\n    \$rating = (int)\$_POST['rating'];";
        
        $content = str_replace($old_pattern, $new_replacement, $content);
        $needs_update = true;
        
        // Close the CSRF validation block
        $content = str_replace(
            "    } else {\n        \$error_message = \"Please provide a valid rating and comment.\";\n    }\n}",
            "    } else {\n        \$error_message = \"Please provide a valid rating and comment.\";\n    }\n    } // Close CSRF validation\n}",
            $content
        );
    }
    
    // 3. Add CSRF token to comment form HTML
    if (strpos($content, 'CSRFProtection::getTokenField') === false &&
        strpos($content, '<form method="POST" class="comment-form">') !== false) {
        
        $content = str_replace(
            '<form method="POST" class="comment-form">',
            '<form method="POST" class="comment-form">' . "\n                        <?php echo CSRFProtection::getTokenField(); ?>",
            $content
        );
        $needs_update = true;
    }
    
    // Write back if changed
    if ($needs_update && $content !== $original_content) {
        if (file_put_contents($file, $content) !== false) {
            $updated_count++;
            echo "  ✅ Updated $filename\n";
        } else {
            $errors[] = "Could not write to $filename";
            echo "  ❌ Failed to write $filename\n";
        }
    } else {
        echo "  ⏭️  No changes needed for $filename\n";
    }
}

echo "\n📊 Summary:\n";
echo "✅ Updated: $updated_count files\n";
if (!empty($errors)) {
    echo "❌ Errors: " . count($errors) . "\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
}

echo "\n🔒 CSRF protection has been added to all course files!\n";
?>