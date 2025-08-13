<?php
// Determine if this is a human visitor or search engine bot
$isHuman = !isset($_GET['format']) || $_GET['format'] !== 'xml';
$userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
$isBot = strpos($userAgent, 'bot') !== false || strpos($userAgent, 'crawler') !== false || strpos($userAgent, 'spider') !== false;

// Force XML for bots, allow HTML for humans
if ($isBot) {
    $isHuman = false;
}

$baseUrl = 'https://tennesseegolfcourses.com';

// Function to get file modification date
function getFileModDate($filepath) {
    if (file_exists($filepath)) {
        return date('Y-m-d', filemtime($filepath));
    }
    return date('Y-m-d');
}

// Function to scan directory for PHP files
function scanDirectory($dir, $baseDir = '') {
    $files = [];
    if (is_dir($dir)) {
        $items = glob($dir . '/*.php');
        foreach ($items as $item) {
            $filename = basename($item, '.php');
            $cleanUrl = $baseDir ? $baseDir . '/' . $filename : $filename;
            $files[] = [
                'url' => $cleanUrl,
                'file' => $item,
                'modified' => getFileModDate($item)
            ];
        }
    }
    return $files;
}

// Scan all content directories
$courseFiles = scanDirectory(__DIR__ . '/courses', 'courses');
$newsFiles = scanDirectory(__DIR__ . '/news', 'news');
$reviewFiles = scanDirectory(__DIR__ . '/reviews', 'reviews');

// Main pages with their file paths
$mainPages = [
    ['url' => '', 'name' => 'Home', 'file' => __DIR__ . '/index.php', 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['url' => 'courses', 'name' => 'Golf Courses', 'file' => __DIR__ . '/courses.php', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['url' => 'reviews', 'name' => 'Reviews', 'file' => __DIR__ . '/reviews.php', 'priority' => '0.8', 'changefreq' => 'weekly'],
    ['url' => 'news', 'name' => 'News', 'file' => __DIR__ . '/news.php', 'priority' => '0.8', 'changefreq' => 'daily'],
    ['url' => 'events', 'name' => 'Events', 'file' => __DIR__ . '/events.php', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['url' => 'about', 'name' => 'About', 'file' => __DIR__ . '/about.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['url' => 'contact', 'name' => 'Contact', 'file' => __DIR__ . '/contact.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['url' => 'privacy-policy', 'name' => 'Privacy Policy', 'file' => __DIR__ . '/privacy-policy.php', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ['url' => 'terms-of-service', 'name' => 'Terms of Service', 'file' => __DIR__ . '/terms-of-service.php', 'priority' => '0.3', 'changefreq' => 'yearly']
];

if ($isHuman) {
    // Human-readable HTML version
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Site Map - Tennessee Golf Courses</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
        <style>
            body { font-family: 'Inter', sans-serif; margin: 0; padding: 20px; background: #f8f9fa; color: #333; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
            h1 { color: #2c5234; margin-bottom: 2rem; text-align: center; }
            
            .sitemap-section { margin-bottom: 1rem; border: 1px solid #e9ecef; border-radius: 10px; overflow: hidden; }
            
            .section-header { 
                background: #f8f9fa; 
                padding: 1rem 1.5rem; 
                cursor: pointer; 
                display: flex; 
                align-items: center; 
                justify-content: space-between; 
                transition: all 0.3s ease;
                border: none;
                width: 100%;
                text-align: left;
                font-family: inherit;
            }
            .section-header:hover { background: #e9ecef; }
            
            .section-title { 
                color: #2c5234; 
                font-size: 1.1rem; 
                font-weight: 600; 
                display: flex; 
                align-items: center; 
                gap: 0.5rem; 
                margin: 0;
            }
            
            .section-meta { display: flex; align-items: center; gap: 1rem; }
            .count { background: #4a7c59; color: white; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
            .arrow { color: #4a7c59; transition: transform 0.3s ease; font-size: 1.2rem; }
            .arrow.expanded { transform: rotate(180deg); }
            
            .section-content { 
                display: none; 
                padding: 0 1.5rem 1.5rem 1.5rem; 
                background: white;
                border-top: 1px solid #f0f0f0;
            }
            .section-content.expanded { display: block; }
            
            .sitemap-links { 
                list-style: none; 
                padding: 0; 
                margin: 0; 
                display: grid; 
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
                gap: 0.5rem; 
            }
            .sitemap-links li { margin-bottom: 0; }
            .sitemap-links a { 
                color: #4a7c59; 
                text-decoration: none; 
                font-weight: 500; 
                display: flex; 
                align-items: center; 
                gap: 0.5rem; 
                padding: 0.5rem; 
                border-radius: 5px; 
                transition: all 0.3s ease;
                font-size: 0.9rem;
            }
            .sitemap-links a:hover { background: #f8f9fa; color: #2c5234; }
            
            .main-pages .sitemap-links { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
            
            .xml-link { 
                text-align: center; 
                margin-top: 2rem; 
                padding: 1rem; 
                background: #e9ecef; 
                border-radius: 10px; 
            }
            .xml-link a { color: #4a7c59; font-weight: 600; text-decoration: none; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1><i class="fas fa-sitemap"></i> Tennessee Golf Courses - Site Map</h1>
            
            <!-- Main Pages Section -->
            <div class="sitemap-section main-pages">
                <button class="section-header" onclick="toggleSection('main-pages')">
                    <h3 class="section-title">
                        <i class="fas fa-home"></i> Main Pages
                    </h3>
                    <div class="section-meta">
                        <span class="count"><?php echo count($mainPages); ?></span>
                        <i class="fas fa-chevron-down arrow" id="main-pages-arrow"></i>
                    </div>
                </button>
                <div class="section-content" id="main-pages-content">
                    <ul class="sitemap-links">
                        <?php foreach ($mainPages as $page): ?>
                        <li><a href="<?php echo $baseUrl . '/' . $page['url']; ?>">
                            <i class="fas fa-link"></i>
                            <?php echo $page['name']; ?>
                        </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Golf Courses Section -->
            <div class="sitemap-section">
                <button class="section-header" onclick="toggleSection('courses')">
                    <h3 class="section-title">
                        <i class="fas fa-golf-ball"></i> Golf Courses
                    </h3>
                    <div class="section-meta">
                        <span class="count"><?php echo count($courseFiles); ?></span>
                        <i class="fas fa-chevron-down arrow" id="courses-arrow"></i>
                    </div>
                </button>
                <div class="section-content" id="courses-content">
                    <ul class="sitemap-links">
                        <?php 
                        usort($courseFiles, function($a, $b) { return strcmp($a['url'], $b['url']); });
                        foreach ($courseFiles as $course): 
                            $courseName = ucwords(str_replace('-', ' ', basename($course['url'])));
                        ?>
                        <li><a href="<?php echo $baseUrl . '/' . $course['url']; ?>">
                            <i class="fas fa-golf-ball"></i>
                            <?php echo $courseName; ?>
                        </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- News Articles Section -->
            <div class="sitemap-section">
                <button class="section-header" onclick="toggleSection('news')">
                    <h3 class="section-title">
                        <i class="fas fa-newspaper"></i> News Articles
                    </h3>
                    <div class="section-meta">
                        <span class="count"><?php echo count($newsFiles); ?></span>
                        <i class="fas fa-chevron-down arrow" id="news-arrow"></i>
                    </div>
                </button>
                <div class="section-content" id="news-content">
                    <ul class="sitemap-links">
                        <?php 
                        usort($newsFiles, function($a, $b) { return strcmp($b['modified'], $a['modified']); });
                        foreach ($newsFiles as $article): 
                            $articleName = ucwords(str_replace('-', ' ', basename($article['url'])));
                        ?>
                        <li><a href="<?php echo $baseUrl . '/' . $article['url']; ?>">
                            <i class="fas fa-newspaper"></i>
                            <?php echo $articleName; ?>
                        </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="sitemap-section">
                <button class="section-header" onclick="toggleSection('reviews')">
                    <h3 class="section-title">
                        <i class="fas fa-star"></i> Reviews
                    </h3>
                    <div class="section-meta">
                        <span class="count"><?php echo count($reviewFiles); ?></span>
                        <i class="fas fa-chevron-down arrow" id="reviews-arrow"></i>
                    </div>
                </button>
                <div class="section-content" id="reviews-content">
                    <ul class="sitemap-links">
                        <?php 
                        usort($reviewFiles, function($a, $b) { return strcmp($b['modified'], $a['modified']); });
                        foreach ($reviewFiles as $review): 
                            $reviewName = ucwords(str_replace('-', ' ', basename($review['url'])));
                        ?>
                        <li><a href="<?php echo $baseUrl . '/' . $review['url']; ?>">
                            <i class="fas fa-star"></i>
                            <?php echo $reviewName; ?>
                        </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="xml-link">
                <p><strong>For search engines:</strong> <a href="<?php echo $baseUrl; ?>/sitemap.xml?format=xml">XML Sitemap</a></p>
                <p style="font-size: 0.9rem; color: #666; margin-top: 1rem;">
                    This sitemap automatically updates when new content is added. 
                    Total pages: <?php echo count($mainPages) + count($courseFiles) + count($newsFiles) + count($reviewFiles); ?>
                </p>
            </div>
        </div>

        <script>
            function toggleSection(sectionId) {
                const content = document.getElementById(sectionId + '-content');
                const arrow = document.getElementById(sectionId + '-arrow');
                
                if (content.classList.contains('expanded')) {
                    content.classList.remove('expanded');
                    arrow.classList.remove('expanded');
                } else {
                    content.classList.add('expanded');
                    arrow.classList.add('expanded');
                }
            }

            // Auto-expand main pages section by default
            document.addEventListener('DOMContentLoaded', function() {
                toggleSection('main-pages');
            });
        </script>
    </body>
    </html>
    <?php
} else {
    // XML version for search engines
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($mainPages as $page): ?>
    <url>
        <loc><?php echo $baseUrl . '/' . $page['url']; ?></loc>
        <lastmod><?php echo getFileModDate($page['file']); ?></lastmod>
        <changefreq><?php echo $page['changefreq']; ?></changefreq>
        <priority><?php echo $page['priority']; ?></priority>
    </url>
    <?php endforeach; ?>

    <?php foreach ($courseFiles as $course): ?>
    <url>
        <loc><?php echo $baseUrl . '/' . $course['url']; ?></loc>
        <lastmod><?php echo $course['modified']; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>

    <?php foreach ($newsFiles as $article): ?>
    <url>
        <loc><?php echo $baseUrl . '/' . $article['url']; ?></loc>
        <lastmod><?php echo $article['modified']; ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>

    <?php foreach ($reviewFiles as $review): ?>
    <url>
        <loc><?php echo $baseUrl . '/' . $review['url']; ?></loc>
        <lastmod><?php echo $review['modified']; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>
</urlset>
    <?php
}
?>