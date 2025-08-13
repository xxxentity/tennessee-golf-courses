<?php
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <!-- Homepage -->
  <url>
    <loc>https://tennesseegolfcourses.com/</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  
  <!-- Main Pages -->
  <url>
    <loc>https://tennesseegolfcourses.com/courses</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  
  <url>
    <loc>https://tennesseegolfcourses.com/reviews</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  
  <url>
    <loc>https://tennesseegolfcourses.com/news</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  
  <url>
    <loc>https://tennesseegolfcourses.com/events</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
  </url>
  
  <url>
    <loc>https://tennesseegolfcourses.com/about</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
  
  <url>
    <loc>https://tennesseegolfcourses.com/contact</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
  
  <!-- Course Pages -->
  <?php
  $courses = [
    'avalon-golf-country-club',
    'bear-trace-cumberland-mountain',
    'bear-trace-harrison-bay', 
    'belle-acres-golf-course',
    'belle-meade-country-club',
    'big-creek-golf-club',
    'blackthorn-club',
    'bluegrass-yacht-country-club',
    'cedar-crest-golf-club',
    'cheekwood-golf-club',
    'cherokee-country-club',
    'cumberland-cove-golf-course',
    'dead-horse-lake-golf-course',
    'druid-hills-golf-club',
    'eagles-landing-golf-club',
    'egwani-farms-golf-course',
    'forrest-crossing-golf-course',
    'fox-den-country-club',
    'gaylord-springs-golf-links',
    'greystone-golf-course',
    'harpeth-hills-golf-course',
    'hermitage-golf-course',
    'hillwood-country-club',
    'holston-hills-country-club',
    'island-pointe-golf-club',
    'lake-tansi-golf-course',
    'lambert-acres-golf-club',
    'laurel-valley-country-club',
    'mccabe-golf-course',
    'mirimichi-golf-course',
    'nashville-golf-athletic-club',
    'nashville-national-golf-links',
    'old-hickory-country-club',
    'percy-warner-golf-course',
    'pine-oaks-golf-course',
    'richland-country-club',
    'sevierville-golf-club',
    'southern-hills-golf-country-club',
    'springhouse-golf-club',
    'stones-river-country-club',
    'sweetens-cove-golf-club',
    'ted-rhodes-golf-course',
    'temple-hills-country-club',
    'the-club-at-five-oaks',
    'the-club-at-gettysvue',
    'the-golf-club-of-tennessee',
    'the-governors-club',
    'the-grove',
    'the-legacy-golf-course',
    'three-ridges-golf-course',
    'tpc-southwind',
    'troubadour-golf-field-club',
    'two-rivers-golf-course',
    'vanderbilt-legends-club',
    'warriors-path-state-park-golf-course',
    'white-plains-golf-course',
    'whittle-springs-golf-course',
    'williams-creek-golf-course',
    'willow-creek-golf-club',
    'windtree-golf-course'
  ];
  
  foreach ($courses as $course): ?>
  <url>
    <loc>https://tennesseegolfcourses.com/courses/<?php echo $course; ?></loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  <?php endforeach; ?>
  
  <!-- News Articles -->
  <?php
  $news = [
    'fedex-st-jude-championship-2025-complete-recap-community-impact',
    'fedex-st-jude-first-round-bhatia-leads',
    'fleetwood-maintains-narrow-lead-scheffler-charges',
    'fleetwood-takes-command-weather-halts-play',
    'open-championship-2025-round-1-royal-portrush',
    'rose-captures-thrilling-playoff-victory-fleetwood-heartbreak',
    'scheffler-extends-lead-open-championship-round-3',
    'scheffler-seizes-control-open-championship-round-2',
    'scheffler-wins-2025-british-open-final-round'
  ];
  
  foreach ($news as $article): ?>
  <url>
    <loc>https://tennesseegolfcourses.com/news/<?php echo $article; ?></loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>yearly</changefreq>
    <priority>0.6</priority>
  </url>
  <?php endforeach; ?>
  
  <!-- Review Articles -->
  <url>
    <loc>https://tennesseegolfcourses.com/reviews/top-10-putters-2025-amazon-guide</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  
  <url>
    <loc>https://tennesseegolfcourses.com/reviews/top-5-golf-balls-2025</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  
  <!-- Legal Pages -->
  <url>
    <loc>https://tennesseegolfcourses.com/privacy-policy</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>yearly</changefreq>
    <priority>0.3</priority>
  </url>
  
  <url>
    <loc>https://tennesseegolfcourses.com/terms-of-service</loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>yearly</changefreq>
    <priority>0.3</priority>
  </url>
</urlset>