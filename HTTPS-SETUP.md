# HTTPS Setup Guide for Tennessee Golf Courses

## Overview
This guide explains how to enable HTTPS for your website to complete the security headers implementation.

## Steps to Enable HTTPS

### 1. Obtain an SSL Certificate
You have several options:
- **Let's Encrypt** (Free) - Most hosting providers offer automatic Let's Encrypt SSL
- **Your hosting provider's SSL** - Often included in hosting plans
- **Purchase an SSL certificate** - From providers like Comodo, DigiCert, etc.

### 2. Install the SSL Certificate
If using cPanel:
1. Log into cPanel
2. Navigate to "Security" → "SSL/TLS"
3. Click "Manage SSL sites"
4. Select your domain
5. Install the certificate

### 3. Enable HTTPS Redirect
Once SSL is installed, uncomment these lines in `.htaccess`:

```apache
# Force HTTPS (uncomment when SSL certificate is installed)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]
```

### 4. Enable HSTS Header
Uncomment this line in `.htaccess`:

```apache
# HSTS - Uncomment when HTTPS is enabled
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
```

### 5. Update Your Website Configuration
1. Update any hardcoded HTTP URLs to HTTPS in your database
2. Update your sitemap to use HTTPS URLs
3. Update Google Analytics and Search Console to HTTPS

### 6. Test Your HTTPS Implementation
- Visit: https://www.ssllabs.com/ssltest/
- Enter your domain to verify SSL configuration
- Aim for an A+ rating

## Security Headers Currently Active

Even without HTTPS, these security headers are protecting your site:
- ✅ X-Frame-Options (Clickjacking protection)
- ✅ X-Content-Type-Options (MIME sniffing protection)
- ✅ X-XSS-Protection (XSS protection for older browsers)
- ✅ Content-Security-Policy (CSP)
- ✅ Referrer-Policy (Privacy protection)
- ✅ Permissions-Policy (Feature restrictions)

## Additional Security Measures Implemented

1. **File Access Protection**
   - Blocks access to .env, .git, .sql, .log files
   - Prevents directory browsing
   - Blocks backup files

2. **Upload Security**
   - Prevents PHP execution in upload directories
   - Blocks potentially dangerous file types

3. **Exploit Protection**
   - Blocks common SQL injection attempts
   - Prevents XSS via query strings
   - Disables HTTP TRACE method

## Testing Security Headers

To verify headers are working:
1. Open browser Developer Tools (F12)
2. Go to Network tab
3. Reload your website
4. Click on the main document request
5. Check Response Headers

Or use online tools:
- https://securityheaders.com/
- https://observatory.mozilla.org/