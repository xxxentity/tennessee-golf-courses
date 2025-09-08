<?php
// Simple Course Reviews Include File - Debug Version
// This is a minimal version to test basic functionality

if (!isset($course_slug) || !isset($course_name)) {
    echo '<!-- Error: Variables not set -->';
    return;
}

echo "<!-- DEBUG: Include loaded for $course_slug -->";
?>

<!-- Reviews Section -->
<section class="reviews-section" style="background: #f8f9fa; padding: 4rem 0;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #2c5234;">Course Reviews (Debug Mode)</h2>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; text-align: center;">
            <p>Debug: Course = <?php echo htmlspecialchars($course_name); ?></p>
            <p>Debug: Slug = <?php echo htmlspecialchars($course_slug); ?></p>
            <p>Review system temporarily in debug mode</p>
        </div>
    </div>
</section>