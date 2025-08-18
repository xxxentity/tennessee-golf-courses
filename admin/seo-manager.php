<?php
require_once '../includes/admin-security-simple.php';
require_once '../includes/seo.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

// Handle sitemap generation
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generate_sitemap'])) {
        $sitemapContent = file_get_contents('https://tennesseegolfcourses.com/sitemap-generator.php');
        if ($sitemapContent) {
            file_put_contents('../sitemap.xml', $sitemapContent);
            $message = "Sitemap generated successfully!";
        } else {
            $message = "Failed to generate sitemap.";
        }
    }
}

// Sample SEO data for analysis
$seoAnalysis = [
    'pages_with_meta_description' => 45,
    'pages_without_meta_description' => 3,
    'pages_with_structured_data' => 42,
    'total_pages' => 48,
    'avg_title_length' => 58,
    'avg_description_length' => 142,
    'images_with_alt_text' => 156,
    'images_without_alt_text' => 12
];

$seoScore = round((
    ($seoAnalysis['pages_with_meta_description'] / $seoAnalysis['total_pages']) * 25 +
    ($seoAnalysis['pages_with_structured_data'] / $seoAnalysis['total_pages']) * 25 +
    ($seoAnalysis['images_with_alt_text'] / ($seoAnalysis['images_with_alt_text'] + $seoAnalysis['images_without_alt_text'])) * 25 +
    (min($seoAnalysis['avg_title_length'] / 60, 1)) * 25
), 1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO Manager - Admin - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .admin-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .seo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .seo-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
        }
        
        .seo-card h3 {
            color: #374151;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        
        .seo-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        
        .seo-label {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .seo-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-success {
            background: #16a34a;
            color: white;
        }
        
        .btn-warning {
            background: #d97706;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }
        
        .progress-fill {
            height: 100%;
            transition: width 0.3s ease;
        }
        
        .progress-excellent { background: #16a34a; }
        .progress-good { background: #22c55e; }
        .progress-fair { background: #eab308; }
        .progress-poor { background: #ef4444; }
        
        .seo-score {
            text-align: center;
            padding: 2rem;
        }
        
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            font-weight: 700;
            color: white;
        }
        
        .score-excellent { background: linear-gradient(135deg, #16a34a, #22c55e); }
        .score-good { background: linear-gradient(135deg, #22c55e, #65a30d); }
        .score-fair { background: linear-gradient(135deg, #eab308, #f59e0b); }
        .score-poor { background: linear-gradient(135deg, #ef4444, #dc2626); }
        
        .checklist {
            list-style: none;
            padding: 0;
        }
        
        .checklist li {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .checklist li:last-child {
            border-bottom: none;
        }
        
        .checklist .icon {
            width: 20px;
            margin-right: 0.75rem;
        }
        
        .check { color: #16a34a; }
        .cross { color: #ef4444; }
        .warning { color: #f59e0b; }
        
        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .tool-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            text-align: center;
        }
        
        .tool-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        
        .tool-icon.blue { background: #dbeafe; color: #2563eb; }
        .tool-icon.green { background: #dcfce7; color: #16a34a; }
        .tool-icon.orange { background: #fed7aa; color: #ea580c; }
        .tool-icon.purple { background: #e9d5ff; color: #9333ea; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <div>
                <h1><i class="fas fa-search"></i> SEO Manager</h1>
                <div style="font-size: 0.9rem; opacity: 0.8;">
                    <a href="/admin/dashboard" style="color: rgba(255,255,255,0.8);">Dashboard</a>
                    <i class="fas fa-chevron-right" style="margin: 0 0.5rem;"></i>
                    <span>SEO</span>
                </div>
            </div>
            <div>
                <span>Welcome, <?php echo htmlspecialchars($currentAdmin['first_name'] ?? 'Admin'); ?>!</span>
                <a href="/admin/logout" style="color: rgba(255,255,255,0.8); margin-left: 1rem;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>
    
    <main class="admin-container">
        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- SEO Score Overview -->
        <div class="seo-section">
            <h2 class="section-title">
                <i class="fas fa-chart-line"></i> SEO Performance Overview
            </h2>
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: center;">
                <div class="seo-score">
                    <?php
                    $scoreClass = 'score-poor';
                    if ($seoScore >= 90) $scoreClass = 'score-excellent';
                    elseif ($seoScore >= 75) $scoreClass = 'score-good';
                    elseif ($seoScore >= 60) $scoreClass = 'score-fair';
                    ?>
                    <div class="score-circle <?php echo $scoreClass; ?>">
                        <?php echo $seoScore; ?>%
                    </div>
                    <h3>Overall SEO Score</h3>
                    <p style="color: #6b7280;">
                        <?php
                        if ($seoScore >= 90) echo 'Excellent - Your SEO is well optimized';
                        elseif ($seoScore >= 75) echo 'Good - Minor improvements needed';
                        elseif ($seoScore >= 60) echo 'Fair - Several areas need attention';
                        else echo 'Poor - Significant SEO improvements required';
                        ?>
                    </p>
                </div>
                
                <div>
                    <ul class="checklist">
                        <li>
                            <i class="fas fa-check icon check"></i>
                            <span>Meta descriptions on <?php echo $seoAnalysis['pages_with_meta_description']; ?>/<?php echo $seoAnalysis['total_pages']; ?> pages</span>
                        </li>
                        <li>
                            <i class="fas fa-check icon check"></i>
                            <span>Structured data on <?php echo $seoAnalysis['pages_with_structured_data']; ?>/<?php echo $seoAnalysis['total_pages']; ?> pages</span>
                        </li>
                        <li>
                            <i class="fas fa-<?php echo $seoAnalysis['avg_title_length'] > 60 ? 'exclamation-triangle icon warning' : 'check icon check'; ?>"></i>
                            <span>Average title length: <?php echo $seoAnalysis['avg_title_length']; ?> characters</span>
                        </li>
                        <li>
                            <i class="fas fa-check icon check"></i>
                            <span>Images with alt text: <?php echo $seoAnalysis['images_with_alt_text']; ?>/<?php echo $seoAnalysis['images_with_alt_text'] + $seoAnalysis['images_without_alt_text']; ?></span>
                        </li>
                        <li>
                            <i class="fas fa-check icon check"></i>
                            <span>Clean URLs and HTTPS enabled</span>
                        </li>
                        <li>
                            <i class="fas fa-check icon check"></i>
                            <span>Mobile-responsive design</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- SEO Metrics -->
        <div class="seo-grid">
            <div class="seo-card">
                <h3>Pages Indexed</h3>
                <div class="seo-value"><?php echo $seoAnalysis['total_pages']; ?></div>
                <div class="seo-label">Total pages</div>
            </div>
            
            <div class="seo-card">
                <h3>Meta Coverage</h3>
                <div class="seo-value"><?php echo round(($seoAnalysis['pages_with_meta_description'] / $seoAnalysis['total_pages']) * 100, 1); ?>%</div>
                <div class="seo-label">Pages with meta descriptions</div>
            </div>
            
            <div class="seo-card">
                <h3>Structured Data</h3>
                <div class="seo-value"><?php echo round(($seoAnalysis['pages_with_structured_data'] / $seoAnalysis['total_pages']) * 100, 1); ?>%</div>
                <div class="seo-label">Pages with schema markup</div>
            </div>
            
            <div class="seo-card">
                <h3>Image Optimization</h3>
                <div class="seo-value"><?php echo round(($seoAnalysis['images_with_alt_text'] / ($seoAnalysis['images_with_alt_text'] + $seoAnalysis['images_without_alt_text'])) * 100, 1); ?>%</div>
                <div class="seo-label">Images with alt text</div>
            </div>
        </div>
        
        <!-- SEO Tools -->
        <div class="seo-section">
            <h2 class="section-title">
                <i class="fas fa-tools"></i> SEO Tools & Actions
            </h2>
            
            <div class="tools-grid">
                <div class="tool-card">
                    <div class="tool-icon green">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <h3>Generate Sitemap</h3>
                    <p style="color: #6b7280; margin-bottom: 1rem;">Update the XML sitemap with latest pages and courses</p>
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="generate_sitemap" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i> Generate Now
                        </button>
                    </form>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon blue">
                        <i class="fas fa-external-link-alt"></i>
                    </div>
                    <h3>View Sitemap</h3>
                    <p style="color: #6b7280; margin-bottom: 1rem;">Check the current XML sitemap file</p>
                    <a href="/sitemap.xml" target="_blank" class="btn btn-primary">
                        <i class="fas fa-eye"></i> View Sitemap
                    </a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon orange">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>Robots.txt</h3>
                    <p style="color: #6b7280; margin-bottom: 1rem;">Manage search engine crawler instructions</p>
                    <a href="/robots.txt" target="_blank" class="btn btn-warning">
                        <i class="fas fa-file-alt"></i> View Robots.txt
                    </a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon purple">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Performance</h3>
                    <p style="color: #6b7280; margin-bottom: 1rem;">Monitor site performance metrics</p>
                    <a href="/admin/performance-monitor" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> View Performance
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick SEO Tips -->
        <div class="seo-section">
            <h2 class="section-title">
                <i class="fas fa-lightbulb"></i> SEO Recommendations
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div>
                    <h4 style="color: #374151; margin-bottom: 0.75rem;">Content Optimization</h4>
                    <ul style="color: #6b7280; line-height: 1.6;">
                        <li>Keep title tags under 60 characters</li>
                        <li>Write compelling meta descriptions (150-160 chars)</li>
                        <li>Use heading tags (H1, H2, H3) strategically</li>
                        <li>Include target keywords naturally</li>
                    </ul>
                </div>
                
                <div>
                    <h4 style="color: #374151; margin-bottom: 0.75rem;">Technical SEO</h4>
                    <ul style="color: #6b7280; line-height: 1.6;">
                        <li>Ensure fast page load times (&lt;3 seconds)</li>
                        <li>Optimize images with alt text</li>
                        <li>Use clean, descriptive URLs</li>
                        <li>Implement structured data markup</li>
                    </ul>
                </div>
                
                <div>
                    <h4 style="color: #374151; margin-bottom: 0.75rem;">Content Strategy</h4>
                    <ul style="color: #6b7280; line-height: 1.6;">
                        <li>Create regular, high-quality content</li>
                        <li>Focus on local Tennessee golf keywords</li>
                        <li>Build internal links between related pages</li>
                        <li>Encourage user reviews and testimonials</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="/admin/dashboard" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </main>
</body>
</html>