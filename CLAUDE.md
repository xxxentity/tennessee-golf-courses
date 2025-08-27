# Tennessee Golf Courses Project - Development Guidelines

## ⚠️ CRITICAL: ALWAYS PUSH TO GITHUB ⚠️

**MANDATORY RULE:** After ANY file change, update, or creation:
1. **git add [files]**
2. **git commit -m "descriptive message"** 
3. **git push**

**NO EXCEPTIONS!** This must be done automatically without being reminded.

## Critical Workflow Requirements

### 1. Git Workflow - ALWAYS Push After Updates
**IMPORTANT:** After EVERY file update or creation, you MUST:
1. Commit the changes with a descriptive message
2. Push to GitHub immediately

This ensures:
- All changes are backed up
- Version history is maintained
- Work is never lost
- Collaboration is seamless

### 2. Course Page Standards

#### Gallery Format (MUST match across all courses):
- Button text: "View Full Gallery (25 Photos)"
- Modal title: "[Course Name] - Complete Photo Gallery"
- Section class: `.photo-gallery`
- Preview images: Exactly 3 images (not 4 or 6)
- Image formats: Check actual file extensions (.jpeg vs .webp)

#### Template to Use:
- **Public Courses**: Use Bear Trace template
- **Private Clubs**: Use Belle Meade template for pricing structure
- Always verify course details from multiple sources

#### Course Data Requirements:
1. Holes (9 or 18)
2. Par
3. Yardage
4. Course Rating
5. Slope Rating
6. Year Established
7. Course Designer (use "N/A" if unknown)
8. Public/Private status

### 3. File Organization

#### Required Updates for New Courses:
1. Create course page in `/courses/[course-slug].php`
2. Add to `/courses.php` (alphabetically ordered)
3. Add to `/search-courses.php` (alphabetically ordered)
4. Add clean URL rule to `/.htaccess`
5. Create image directory `/images/courses/[course-slug]/`

### 4. Database Status
- Total Tennessee courses: 350+ estimated
- Completed: 97 courses
- High-priority missing: 34 courses
- Tracking file: `tennessee-golf-courses-database.txt`

### 5. Testing Requirements
- Test search functionality after adding courses
- Verify gallery loads correctly
- Check clean URLs work properly
- Ensure all links are functional

## Project Goals
Create a comprehensive database of ALL Tennessee golf courses, including:
- Major championship courses
- Public municipal courses
- Private country clubs
- Small town 9-hole courses
- Resort courses
- State park courses

## Remember
- Quality over speed
- Accuracy is critical
- Always verify data from multiple sources
- Maintain consistent formatting across all pages
- Push to GitHub after EVERY update