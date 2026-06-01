# Monday June 1 - Session Priorities

## SEO / Course Page Fixes
1. **Fix gallery alt text** on course pages — auto-generated alt text has wrong hole numbers (references hole 12/15/18 on 9-hole courses, etc). Can use $course_data array already in each file to generate accurate alt text via script. Estimated 20-30 min.
2. **Convert old course pages to new header/footer format** — same conversion done with news articles. ~104 course pages still use old require_once '../includes/init.php' + seo.php setup. Should be done via worktree agent like the news articles were. New format uses $pageTitle, $pageDescription, $pageKeywords, $ogImage vars + include '../includes/header.php'.
3. **Beef up thin course descriptions** — Google has 134 pages crawled but not indexed. Main reason is thin/generic content on course pages. Need more unique factual content per course.

## Search Console Cleanup
- Validated fixes for old 404s (courses/courses/ double paths) — mark as fixed in Search Console to prompt recrawl
- Remaining ghost URLs (old auth system pages, old redirects) will clear on their own as Google recrawls

## Already Done This Session
- Canonical tags added to all 102 old-format course pages
- JSON-LD GolfCourse schema markup added to all 104 course pages
- Empty star ratings removed from course thumbnails (review system was deleted)
- Broken course-reviews-fixed.php include removed from 25 course pages
- Featured stories carousel redesigned (fire emoji removed, image display fixed)
- News filter dropdown updated (added Tournament Recap, Major Championship)
- robots.txt cleaned up (blocked auth pages, query strings, .php variants, dynamic endpoints)
- Canonical tags added to includes/header.php (covers all news articles)
- Double courses/courses/ path fixed at source in courses.php
- Three broken course links fixed in Tennessee golf guide
- Maps page restored, Maps added back to navbar
- Reviews stat removed from homepage

## Upcoming Articles to Write
- Memorial Tournament recap (June 4-7, Muirfield Village, Signature Event, $20M purse, Scheffler going for 3rd straight win)
- Tennessee Vols NCAA Championship result (May 29 - June 3, La Costa — check results when session starts)
- US Women's Amateur recap (August 4-9, Honors Course Ooltewah TN) — after it happens
