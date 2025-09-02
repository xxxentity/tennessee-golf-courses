<?php
/**
 * Security Headers
 * Add essential security headers to protect against common web vulnerabilities
 */

// X-Frame-Options removed - using CSP frame-src instead for better control
// header('X-Frame-Options: SAMEORIGIN');

// Prevent MIME type sniffing
header('X-Content-Type-Options: nosniff');

// Enable XSS protection
header('X-XSS-Protection: 1; mode=block');

// Referrer policy for privacy
header('Referrer-Policy: strict-origin-when-cross-origin');

// Content Security Policy - Basic policy, can be customized based on needs
$csp = "default-src 'self'; " .
       "script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com https://api.mapbox.com; " .
       "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://api.mapbox.com; " .
       "img-src 'self' data: https:; " .
       "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
       "connect-src 'self' https://www.google-analytics.com https://api.mapbox.com https://*.tiles.mapbox.com; " .
       "frame-src 'self' https://maps.google.com https://www.google.com; " .
       "object-src 'none'; " .
       "base-uri 'self';";

header("Content-Security-Policy: $csp");

// Force HTTPS in production
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');

// Permissions Policy (formerly Feature Policy)
header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
?>