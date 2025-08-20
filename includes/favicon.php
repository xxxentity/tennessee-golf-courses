<?php
// Automatic favicon management with cache busting
// Uses file modification time for automatic versioning
$favicon_path = '/images/logos/tab-logo.webp';
$favicon_full_path = $_SERVER['DOCUMENT_ROOT'] . $favicon_path;

// Get file modification time for automatic cache busting
if (file_exists($favicon_full_path)) {
    $favicon_version = filemtime($favicon_full_path);
} else {
    // Fallback if file doesn't exist
    $favicon_version = time();
}

// Generate favicon HTML with automatic versioning
$favicon_url = $favicon_path . '?t=' . $favicon_version;
?>
<!-- Favicon with automatic cache busting -->
<link rel="icon" type="image/webp" href="<?php echo $favicon_url; ?>">
<link rel="shortcut icon" href="<?php echo $favicon_url; ?>">
<link rel="apple-touch-icon" href="<?php echo $favicon_url; ?>">