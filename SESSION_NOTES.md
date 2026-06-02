# Session Handoff Notes
Last updated: 2026-06-02

## What We Are Doing

Standardizing all 104 course pages on tennesseegolfcourses.com (PHP site, deployed via GitHub → cPanel git pull on Namecheap).

**The job on every page:**
1. Three info boxes in a single row: **Course Information | Green Fees (or Membership) | Location & Contact**
2. Below the boxes: **2fr/1fr grid** — About paragraphs (left) + Amenities list (right)
3. All CSS must be **inline** (no CSS class-based layout)
4. Amenity items each have `width: 100%` in a flex-column parent
5. About paragraphs rewritten with **real, researched facts** (verify what's on the page; don't invent)
6. Remove dead star-rating block in hero (`<?php if ($avg_rating):` block)
7. Remove dead star JS (referencing `.star-rating`, `.rating-select i`, `input[name="rating"]`)
8. Remove broken gallery JS from `<head>` — move clean version to bottom of `<body>`
9. Hard-coded inline footer → `<?php include '../includes/footer.php'; ?>`
10. Hard-coded favicon links → `<?php include '../includes/favicon.php'; ?>`
11. Brown gradient membership boxes → **green gradient** (`background: linear-gradient(135deg, #2c5234, #4a7c59)`)
12. Private clubs: replace Green Fees box with **Membership box** (green gradient)
13. Remove booking sections (gradient green background CTA sections)
14. Remove weather JS (`course-weather.js`)
15. Gallery JS uses `.webp` for most courses, `.jpeg` for some (check existing img src)

---

## Standard Code Patterns

### Hero
```html
<section style="height: 60vh; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../images/courses/SLUG/1.EXT') center/cover no-repeat; display: flex; align-items: center; justify-content: center; color: white; text-align: center; margin-top: 20px;">
    <div>
        <h1 style="font-size: 3.5rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">COURSE NAME</h1>
        <p style="font-size: 1.3rem; margin-bottom: 1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">TAGLINE</p>
        <div style="display: flex; gap: 20px; justify-content: center;">
            <div style="display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-map-marker-alt"></i>
                <span>CITY, TN</span>
            </div>
        </div>
    </div>
</section>
```

### Three-Box Row Wrapper
```html
<section style="padding: 4rem 0; background: #f8f9fa;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 3rem;">
            <!-- Box 1: Course Information -->
            <!-- Box 2: Green Fees OR Membership -->
            <!-- Box 3: Location & Contact -->
        </div>
        <!-- 2fr/1fr About + Amenities grid here -->
    </div>
</section>
```

### Course Info Box Items
```html
<div style="padding: 0.6rem 0; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between;">
    <span style="font-weight: 600; color: #2c5234;">Label</span>
    <span style="color: #666;">Value</span>
</div>
```
Last item has no `border-bottom`.

### Green Fees Table (public courses)
```html
<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
    <thead>
        <tr style="background: #2c5234; color: white;">
            <th style="padding: 0.6rem 0.8rem; text-align: left;">Rate Type</th>
            <th style="padding: 0.6rem 0.8rem; text-align: right;">Price</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom: 1px solid #e0e0e0;">
            <td style="padding: 0.6rem 0.8rem;">Weekday (w/ Cart)</td>
            <td style="padding: 0.6rem 0.8rem; text-align: right;">$XX</td>
        </tr>
        <!-- alternate rows get background: #f9f9f9 -->
    </tbody>
</table>
```

### Membership Box (private clubs)
```html
<div style="background: linear-gradient(135deg, #2c5234, #4a7c59); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; margin-bottom: 1rem;">
    <h4 style="margin-bottom: 0.5rem; font-size: 1.2rem;">Private Members Only</h4>
    <p style="margin: 0; opacity: 0.9;">Exclusive club membership required</p>
</div>
```

### Amenity Item
```html
<div style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
    <i class="fas fa-golf-ball" style="color: #4a7c59;"></i>
    <span>Amenity Name</span>
</div>
```

### Gallery JS (bottom of body)
```html
<script>
    function openGallery() {
        const modal = document.getElementById('galleryModal');
        const grid = document.getElementById('fullGalleryGrid');
        grid.innerHTML = '';
        const galleryImages = Array.from({length: 25}, (_, i) => ({
            src: `../images/courses/SLUG/${i + 1}.EXT`,
            alt: `COURSE NAME - Photo ${i + 1}`
        }));
        galleryImages.forEach(img => {
            const item = document.createElement('div');
            item.style.cssText = 'aspect-ratio: 4/3; border-radius: 10px; overflow: hidden; cursor: pointer;';
            item.innerHTML = `<img src="${img.src}" alt="${img.alt}" loading="lazy" style="width:100%;height:100%;object-fit:cover;" onclick="window.open('${img.src}','_blank')">`;
            grid.appendChild(item);
        });
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeGallery() {
        document.getElementById('galleryModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    document.getElementById('galleryModal').addEventListener('click', function(e) {
        if (e.target === this) closeGallery();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeGallery();
    });
</script>
```

---

## Progress: 43 / 104 Completed

See COURSE_PROGRESS.md for full list. Next course to work on:

### → lake-tansi-golf-course.php

After that (in order):
- lambert-acres-golf-lab.php
- laurel-valley-country-club.php
- lookout-mountain-club.php
- mccabe-golf-course.php
- memphis-country-club.php
- mirimichi-golf-course.php
- moccasin-bend-golf-course.php
- montgomery-bell-state-park-golf-course.php
- nashville-golf-athletic-club.php
- nashville-national-golf-links.php
- old-fort-golf-course.php
- old-hickory-country-club.php
- overton-park-9.php
- paris-landing-state-park-golf-course.php
- percy-warner-golf-course.php
- pickwick-landing-state-park.php
- pine-oaks-golf-course.php
- richland-country-club.php
- ross-creek-landing-golf-course.php
- sevierville-golf-club.php
- signal-mountain-golf-country-club.php
- sparta-country-club.php
- springhouse-golf-club.php
- stonehenge-golf-club.php
- stones-river-country-club.php
- sweetens-cove-golf-club.php
- tanasi-golf-course.php
- ted-rhodes-golf-course.php
- temple-hills-country-club.php
- tennessee-grasslands-fairvue.php
- tennessee-grasslands-foxland.php
- tennessee-national-golf-club.php
- the-club-at-five-oaks.php
- the-club-at-gettysvue.php
- the-country-club.php
- the-golf-club-of-tennessee.php
- the-governors-club.php
- the-grove.php
- the-honors-course.php
- the-legacy-golf-course.php
- the-links-at-audubon.php
- the-links-at-fox-meadows.php
- the-links-at-galloway.php
- the-links-at-kahite.php
- the-links-at-whitehaven.php
- three-ridges-golf-course.php
- timber-truss-golf-course.php
- toqua-golf-course.php
- tpc-southwind.php
- troubadour-golf-field-club.php
- two-rivers-golf-course.php
- vanderbilt-legends-club.php
- warriors-path-state-park-golf-course.php
- white-plains-golf-course.php
- whittle-springs-golf-course.php
- williams-creek-golf-course.php
- willow-creek-golf-club.php
- windriver-golf-club.php
- windtree-golf-course.php
- windyke-country-club.php

---

## Key Rules (User Instructions)

- "search the web and if you dont find move on. goes for the future too."
- "we just standardizing the three blocks, amenities, better longer paragraphs based on information or research online. standardizing so all course pages are the same essentially."
- "have to research the sight and double check the information."
- Don't fabricate facts — if info can't be found, leave it out or mark as approximate
- `southern-hills-golf-country-club.php` is already done (listed as duplicate in Remaining — skip it)
- `sevierville-golf-club.php` — this is the renamed eagles-landing which was already standardized — verify before touching

---

## Common Problems to Watch For

1. **Gallery JS in `<head>`** — Some files have openGallery/closeGallery functions inside the `<script>` tag that also holds gtag. This breaks because `document.getElementById('galleryModal')` doesn't exist yet when the head runs. Remove from head, put clean version at bottom of body.

2. **Dead star block in hero** — `<?php if ($avg_rating): ?>...<?php endif; ?>` block. Remove it. `$avg_rating` is never set.

3. **Dead rating JS** — Functions like `highlightStars()`, `resetStars()`, `setRating()` referencing `.rating-select i` or `input[name="rating"]` — these DOM elements don't exist. Remove.

4. **Brown gradient membership boxes** — `background: linear-gradient(135deg, #8B4513, #A0522D)` — change to green: `background: linear-gradient(135deg, #2c5234, #4a7c59)`

5. **Hard-coded footer** — Large `<footer class="footer">` block — replace with `<?php include '../includes/footer.php'; ?>`

6. **Hard-coded favicon** — `<link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=5">` — replace with `<?php include '../includes/favicon.php'; ?>`

7. **CSS class-based layout** — `.course-hero`, `.course-layout`, `.course-info-cards`, `.course-info-card`, `.course-specs`, `.amenities-grid` etc. — convert all to inline styles

8. **Booking sections** — Green gradient CTA blocks ("Book Your Tee Time Today"). Remove entirely.

9. **Commented-out duplicate code** — Some files have huge blocks of `// Duplicate removed -` commented code. Remove it all.

10. **Google Fonts link** — Some files have `<link href="https://fonts.googleapis.com/css2?family=Inter:...">`. Remove it (not needed).

---

## Git Status

- Branch: main
- Last commit: 6ec3e7e — island-pointe + jackson-country-club
- Remote: ahead by 2 commits (push periodically with `git push`)
- Working directory: /mnt/c/users/ddhoward/TGC LLC/tennessee-golf-courses
