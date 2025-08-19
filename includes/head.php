<?php
// Load security headers first
require_once __DIR__ . '/security-headers.php';

// Default head elements for all pages
// Usage: include this file in the <head> section of every page
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/styles.css?v=5">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<!-- Favicon -->
<link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=3">
<link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=3">

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-7VPNPCDTBP');
</script>