<?php
/**
 * SEO Management System
 * Handles meta tags, structured data, sitemaps, and SEO optimization
 */

class SEO {
    private static $title = '';
    private static $description = '';
    private static $keywords = '';
    private static $canonicalUrl = '';
    private static $ogImage = '';
    private static $pageType = 'website';
    private static $breadcrumbs = [];
    private static $structuredData = [];
    
    // Default site settings
    const SITE_NAME = 'Tennessee Golf Courses';
    const SITE_DESCRIPTION = 'Discover the best golf courses in Tennessee. Complete guide to Tennessee golf with course reviews, ratings, and detailed information.';
    const SITE_URL = 'https://tennesseegolfcourses.com';
    const DEFAULT_IMAGE = '/images/tennessee-golf-og.jpg';
    
    /**
     * Set page title
     */
    public static function setTitle($title, $includeSiteName = true) {
        if ($includeSiteName && !empty($title)) {
            self::$title = $title . ' | ' . self::SITE_NAME;
        } else {
            self::$title = $title ?: self::SITE_NAME;
        }
    }
    
    /**
     * Set meta description
     */
    public static function setDescription($description) {
        self::$description = substr($description, 0, 160); // Limit to 160 chars
    }
    
    /**
     * Set meta keywords
     */
    public static function setKeywords($keywords) {
        if (is_array($keywords)) {
            self::$keywords = implode(', ', $keywords);
        } else {
            self::$keywords = $keywords;
        }
    }
    
    /**
     * Set canonical URL
     */
    public static function setCanonicalUrl($url) {
        self::$canonicalUrl = $url;
    }
    
    /**
     * Set Open Graph image
     */
    public static function setOgImage($image) {
        self::$ogImage = $image;
    }
    
    /**
     * Set page type
     */
    public static function setPageType($type) {
        self::$pageType = $type; // website, article, etc.
    }
    
    /**
     * Add breadcrumb
     */
    public static function addBreadcrumb($name, $url = null) {
        self::$breadcrumbs[] = [
            'name' => $name,
            'url' => $url
        ];
    }
    
    /**
     * Add structured data
     */
    public static function addStructuredData($data) {
        self::$structuredData[] = $data;
    }
    
    /**
     * Generate all meta tags
     */
    public static function generateMetaTags() {
        $currentUrl = self::getCurrentUrl();
        $canonicalUrl = self::$canonicalUrl ?: $currentUrl;
        $ogImage = self::$ogImage ?: self::DEFAULT_IMAGE;
        
        // Make sure image URL is absolute
        if (strpos($ogImage, 'http') !== 0) {
            $ogImage = self::SITE_URL . $ogImage;
        }
        
        $html = "\n<!-- SEO Meta Tags -->\n";
        
        // Basic meta tags
        if (self::$title) {
            $html .= "<title>" . htmlspecialchars(self::$title) . "</title>\n";
        }
        
        if (self::$description) {
            $html .= '<meta name="description" content="' . htmlspecialchars(self::$description) . '">' . "\n";
        }
        
        if (self::$keywords) {
            $html .= '<meta name="keywords" content="' . htmlspecialchars(self::$keywords) . '">' . "\n";
        }
        
        // Canonical URL
        $html .= '<link rel="canonical" href="' . htmlspecialchars($canonicalUrl) . '">' . "\n";
        
        // Open Graph tags
        $html .= '<meta property="og:type" content="' . htmlspecialchars(self::$pageType) . '">' . "\n";
        $html .= '<meta property="og:title" content="' . htmlspecialchars(self::$title) . '">' . "\n";
        $html .= '<meta property="og:description" content="' . htmlspecialchars(self::$description) . '">' . "\n";
        $html .= '<meta property="og:url" content="' . htmlspecialchars($canonicalUrl) . '">' . "\n";
        $html .= '<meta property="og:image" content="' . htmlspecialchars($ogImage) . '">' . "\n";
        $html .= '<meta property="og:site_name" content="' . htmlspecialchars(self::SITE_NAME) . '">' . "\n";
        
        // Twitter Card tags
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . htmlspecialchars(self::$title) . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . htmlspecialchars(self::$description) . '">' . "\n";
        $html .= '<meta name="twitter:image" content="' . htmlspecialchars($ogImage) . '">' . "\n";
        
        // Additional SEO tags
        $html .= '<meta name="robots" content="index, follow">' . "\n";
        $html .= '<meta name="author" content="' . htmlspecialchars(self::SITE_NAME) . '">' . "\n";
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        
        // AdSense-friendly meta tags
        $html .= '<meta name="category" content="Sports">' . "\n";
        $html .= '<meta name="coverage" content="Local">' . "\n";
        $html .= '<meta name="distribution" content="Global">' . "\n";
        $html .= '<meta name="rating" content="General">' . "\n";
        $html .= '<meta name="language" content="English">' . "\n";
        
        // Content freshness and relevance
        $html .= '<meta name="revised" content="' . date('Y-m-d') . '">' . "\n";
        $html .= '<meta name="topic" content="Golf Courses Tennessee">' . "\n";
        
        return $html;
    }
    
    /**
     * Generate news keywords meta tag for AdSense optimization
     */
    public static function generateNewsKeywords($keywords = null) {
        if ($keywords === null) {
            // Default news keywords based on content type
            $keywords = ['golf', 'Tennessee', 'sports', 'courses', 'reviews'];
        }
        
        if (is_array($keywords)) {
            $keywords = implode(', ', $keywords);
        }
        
        return '<meta name="news_keywords" content="' . htmlspecialchars($keywords) . '">' . "\n";
    }
    
    /**
     * Generate structured data JSON-LD
     */
    public static function generateStructuredData() {
        if (empty(self::$structuredData)) {
            return '';
        }
        
        $html = "\n<!-- Structured Data -->\n";
        foreach (self::$structuredData as $data) {
            $html .= '<script type="application/ld+json">' . "\n";
            $html .= json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
            $html .= '</script>' . "\n";
        }
        
        return $html;
    }
    
    /**
     * Generate breadcrumb structured data
     */
    public static function generateBreadcrumbStructuredData() {
        if (empty(self::$breadcrumbs)) {
            return '';
        }
        
        $items = [];
        foreach (self::$breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url'] ? self::SITE_URL . $breadcrumb['url'] : null
            ];
        }
        
        $breadcrumbData = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
        
        self::addStructuredData($breadcrumbData);
    }
    
    /**
     * Generate golf course structured data
     */
    public static function generateGolfCourseStructuredData($course) {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'GolfCourse',
            'name' => $course['name'],
            'description' => $course['description'],
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $course['location'],
                'addressRegion' => 'TN',
                'addressCountry' => 'US'
            ],
            'url' => self::SITE_URL . '/courses/' . $course['slug'],
            'telephone' => $course['phone'] ?? null,
            'geo' => isset($course['latitude'], $course['longitude']) ? [
                '@type' => 'GeoCoordinates',
                'latitude' => $course['latitude'],
                'longitude' => $course['longitude']
            ] : null
        ];
        
        // Add rating if available
        if (isset($course['rating']) && $course['rating'] > 0) {
            $structuredData['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $course['rating'],
                'bestRating' => 5,
                'worstRating' => 1
            ];
        }
        
        // Add images
        if (isset($course['image'])) {
            $structuredData['image'] = self::SITE_URL . $course['image'];
        }
        
        self::addStructuredData($structuredData);
    }
    
    /**
     * Generate website structured data
     */
    public static function generateWebsiteStructuredData() {
        $websiteData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => self::SITE_NAME,
            'description' => self::SITE_DESCRIPTION,
            'url' => self::SITE_URL,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => self::SITE_URL . '/courses?search={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
        
        self::addStructuredData($websiteData);
    }
    
    /**
     * Generate organization structured data
     */
    public static function generateOrganizationStructuredData() {
        $organizationData = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => self::SITE_NAME,
            'description' => self::SITE_DESCRIPTION,
            'url' => self::SITE_URL,
            'logo' => self::SITE_URL . '/images/logo.png',
            'sameAs' => [
                // Add social media URLs here if available
            ]
        ];
        
        self::addStructuredData($organizationData);
    }
    
    /**
     * Get current URL
     */
    private static function getCurrentUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'tennesseegolfcourses.com';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        return $protocol . '://' . $host . $uri;
    }
    
    /**
     * Set up SEO for homepage
     */
    public static function setupHomepage() {
        self::setTitle('Best Golf Courses in Tennessee - Complete Guide & Reviews');
        self::setDescription('Discover Tennessee\'s premier golf courses. Expert reviews, ratings, and complete information for golf enthusiasts. Find your perfect golf experience in Tennessee.');
        self::setKeywords([
            'Tennessee golf courses', 
            'best golf courses Tennessee', 
            'golf in Tennessee', 
            'Tennessee golf reviews',
            'Nashville golf courses',
            'Memphis golf courses',
            'public golf courses Tennessee',
            'private golf clubs Tennessee',
            'golf course ratings Tennessee',
            'Tennessee golf directory'
        ]);
        self::setCanonicalUrl(self::SITE_URL);
        self::setOgImage('/images/logos/tab-logo.webp');
        
        self::generateWebsiteStructuredData();
        self::generateOrganizationStructuredData();
    }
    
    /**
     * Set up SEO for about page
     */
    public static function setupAboutPage() {
        self::setTitle('About Us - Tennessee Golf Courses');
        self::setDescription('Learn about Tennessee Golf Courses - a community-driven platform built by golfers, for golfers to discover the best courses across the Volunteer State.');
        self::setKeywords([
            'about Tennessee golf courses',
            'Tennessee golf community',
            'golf course directory Tennessee',
            'Tennessee golf platform',
            'golf enthusiasts Tennessee',
            'volunteer state golf',
            'Tennessee golf reviews',
            'golf course information'
        ]);
        self::setCanonicalUrl(self::SITE_URL . '/about');
        self::setOgImage('/images/logos/tab-logo.webp');
        
        self::addBreadcrumb('Home', '/');
        self::addBreadcrumb('About Us');
        self::generateBreadcrumbStructuredData();
        
        // Add organization structured data for About page
        self::generateOrganizationStructuredData();
    }
    
    /**
     * Set up SEO for contact page
     */
    public static function setupContactPage() {
        self::setTitle('Contact Us - Tennessee Golf Courses');
        self::setDescription('Get in touch with Tennessee Golf Courses. Contact us about course suggestions, partnerships, technical issues, media inquiries, or general questions.');
        self::setKeywords([
            'contact Tennessee golf courses',
            'golf course submissions Tennessee',
            'Tennessee golf partnerships',
            'golf course suggestions',
            'Tennessee golf community contact',
            'golf course directory contact',
            'media inquiries golf Tennessee',
            'golf course feedback'
        ]);
        self::setCanonicalUrl(self::SITE_URL . '/contact');
        self::setOgImage('/images/logos/tab-logo.webp');
        
        self::addBreadcrumb('Home', '/');
        self::addBreadcrumb('Contact Us');
        self::generateBreadcrumbStructuredData();
    }
    
    /**
     * Set up SEO for community page
     */
    public static function setupCommunityPage() {
        self::setTitle('Community - Tennessee Golf Courses');
        self::setDescription('Join the Tennessee Golf Courses community - connect with fellow golfers, share experiences, and discover new courses across the Volunteer State.');
        self::setKeywords([
            'Tennessee golf community',
            'golf community Tennessee',
            'Tennessee golfers network',
            'volunteer state golf community',
            'golf course discussions Tennessee',
            'Tennessee golf events',
            'golf club community',
            'Tennessee golf experiences'
        ]);
        self::setCanonicalUrl(self::SITE_URL . '/community');
        self::setOgImage('/images/logos/tab-logo.webp');
        
        self::addBreadcrumb('Home', '/');
        self::addBreadcrumb('Community');
        self::generateBreadcrumbStructuredData();
    }
    
    /**
     * Set up SEO for events page
     */
    public static function setupEventsPage() {
        self::setTitle('Golf Tournaments & Events - Tennessee Golf Courses');
        self::setDescription('Complete guide to LIV Golf, PGA Tour, and Tennessee golf tournaments. Find professional and local golf events, championships, and competitions near you.');
        self::setKeywords([
            'Tennessee golf tournaments',
            'LIV Golf events',
            'PGA Tour tournaments',
            'golf events Tennessee',
            'professional golf tournaments',
            'Tennessee golf championships',
            'golf competitions Tennessee',
            'tournament schedule golf'
        ]);
        self::setCanonicalUrl(self::SITE_URL . '/events');
        self::setOgImage('/images/logos/tab-logo.webp');
        
        self::addBreadcrumb('Home', '/');
        self::addBreadcrumb('Tournaments & Events');
        self::generateBreadcrumbStructuredData();
    }
    
    /**
     * Set up SEO for handicap page
     */
    public static function setupHandicapPage() {
        self::setTitle('Golf Handicap Calculator & Guide - Tennessee Golf Courses');
        self::setDescription('Learn about golf handicaps and calculate your handicap index. Complete guide to the USGA handicap system with interactive calculator for Tennessee golfers.');
        self::setKeywords([
            'golf handicap calculator',
            'USGA handicap system',
            'Tennessee golf handicap',
            'golf handicap index calculator',
            'how to calculate golf handicap',
            'golf scoring Tennessee',
            'handicap index guide',
            'USGA rules handicap'
        ]);
        self::setCanonicalUrl(self::SITE_URL . '/handicap');
        self::setOgImage('/images/logos/tab-logo.webp');
        
        self::addBreadcrumb('Home', '/');
        self::addBreadcrumb('Handicap Calculator');
        self::generateBreadcrumbStructuredData();
    }
    
    /**
     * Set up SEO for maps page
     */
    public static function setupMapsPage() {
        self::setTitle('Golf Course Maps - Tennessee Golf Courses');
        self::setDescription('Interactive maps of Tennessee golf courses - find courses by location, region, and proximity to major cities across the Volunteer State.');
        self::setKeywords([
            'Tennessee golf course maps',
            'interactive golf course map',
            'golf courses near me Tennessee',
            'Tennessee golf course locations',
            'golf course finder map',
            'Nashville golf course map',
            'Memphis golf course map',
            'Tennessee golf regions map'
        ]);
        self::setCanonicalUrl(self::SITE_URL . '/maps');
        self::setOgImage('/images/logos/tab-logo.webp');
        
        self::addBreadcrumb('Home', '/');
        self::addBreadcrumb('Course Maps');
        self::generateBreadcrumbStructuredData();
    }
    
    /**
     * Set up SEO for media page
     */
    public static function setupMediaPage() {
        self::setTitle('Media - Tennessee Golf Courses');
        self::setDescription('Tennessee Golf Courses Media Hub - Your source for golf news, course reviews, and media content covering golf across the Volunteer State.');
        self::setKeywords([
            'Tennessee golf media',
            'golf news Tennessee',
            'Tennessee golf coverage',
            'volunteer state golf news',
            'golf media hub Tennessee',
            'Tennessee golf reviews',
            'golf content Tennessee',
            'Tennessee golf journalism'
        ]);
        self::setCanonicalUrl(self::SITE_URL . '/media');
        self::setOgImage('/images/logos/tab-logo.webp');
        
        self::addBreadcrumb('Home', '/');
        self::addBreadcrumb('Media');
        self::generateBreadcrumbStructuredData();
    }
    
    /**
     * Set up SEO for courses listing page
     */
    public static function setupCoursesPage() {
        self::setTitle('Tennessee Golf Courses Directory - Find Golf Courses');
        self::setDescription('Browse all golf courses in Tennessee. Filter by region, price, and difficulty. Comprehensive directory with reviews and detailed course information.');
        self::setKeywords(['Tennessee golf directory', 'golf courses Tennessee', 'Tennessee golf guide', 'golf course finder']);
        self::setCanonicalUrl(self::SITE_URL . '/courses');
        self::setOgImage('/images/logos/tab-logo.webp');
        
        self::addBreadcrumb('Home', '/');
        self::addBreadcrumb('Golf Courses');
        self::generateBreadcrumbStructuredData();
    }
    
    /**
     * Set up SEO for individual course page
     */
    public static function setupCoursePage($course) {
        $title = $course['name'] . ' Golf Course - ' . $course['location'];
        $description = $course['description'] . ' Located in ' . $course['location'] . '. ' . 
                      ($course['holes'] ?? 18) . ' holes, Par ' . ($course['par'] ?? 72) . '.';
        
        self::setTitle($title);
        self::setDescription($description);
        self::setKeywords([
            $course['name'], 
            $course['location'] . ' golf', 
            'Tennessee golf course',
            $course['designer'] ?? 'golf course design'
        ]);
        self::setCanonicalUrl(self::SITE_URL . '/courses/' . $course['slug']);
        self::setOgImage($course['image'] ?? self::DEFAULT_IMAGE);
        self::setPageType('article');
        
        self::addBreadcrumb('Home', '/');
        self::addBreadcrumb('Golf Courses', '/courses');
        self::addBreadcrumb($course['name']);
        self::generateBreadcrumbStructuredData();
        
        self::generateGolfCourseStructuredData($course);
    }
}
?>