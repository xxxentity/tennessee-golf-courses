<?php
// Automatic favicon management with cache busting
// Check PNG, JPEG, and WebP formats
$favicon_png = '/images/logos/tab-logo.png';
$favicon_jpeg = '/images/logos/tab-logo.jpeg';
$favicon_webp = '/images/logos/tab-logo.webp';

// Check which files exist
$favicon_png_path = $_SERVER['DOCUMENT_ROOT'] . $favicon_png;
$favicon_jpeg_path = $_SERVER['DOCUMENT_ROOT'] . $favicon_jpeg;
$favicon_webp_path = $_SERVER['DOCUMENT_ROOT'] . $favicon_webp;

if (file_exists($favicon_png_path)) {
    $favicon_file = $favicon_png;
    $favicon_full_path = $favicon_png_path;
} elseif (file_exists($favicon_jpeg_path)) {
    $favicon_file = $favicon_jpeg;
    $favicon_full_path = $favicon_jpeg_path;
} elseif (file_exists($favicon_webp_path)) {
    $favicon_file = $favicon_webp;
    $favicon_full_path = $favicon_webp_path;
} else {
    $favicon_file = $favicon_png; // fallback
    $favicon_full_path = $favicon_png_path;
}

// Get file modification time for automatic cache busting
if (file_exists($favicon_full_path)) {
    $favicon_version = filemtime($favicon_full_path);
} else {
    $favicon_version = time();
}

// Generate favicon HTML with automatic versioning
$favicon_url = $favicon_file . '?t=' . $favicon_version;
?>
<!-- Favicon with automatic cache busting and cross-browser compatibility -->
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $favicon_url; ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favicon_url; ?>">
<link rel="shortcut icon" href="<?php echo $favicon_url; ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $favicon_url; ?>">