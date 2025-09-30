<?php
// Simple header file for news articles
// Includes Google Analytics and basic head elements
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <title><?php echo htmlspecialchars($pageTitle ?? 'Tennessee Golf Courses'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription ?? 'Your premier destination for discovering the best golf courses across Tennessee.'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords ?? 'Tennessee golf courses, golf news, golf reviews'); ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle ?? 'Tennessee Golf Courses'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription ?? 'Your premier destination for discovering the best golf courses across Tennessee.'); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage ?? 'https://tennesseegolfcourses.com/images/logos/logo.webp'); ?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Tennessee Golf Courses">

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=5">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=5">

    <!-- CSS and Fonts -->
    <link rel="stylesheet" href="/styles.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
</head>
<body>

<!-- Navigation -->
<?php include __DIR__ . '/navigation.php'; ?>