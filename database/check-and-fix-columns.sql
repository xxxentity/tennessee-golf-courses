-- Check if parent_comment_id column exists
SHOW COLUMNS FROM course_comments LIKE 'parent_comment_id';

-- If the above shows no results, run these commands:
-- ALTER TABLE course_comments ADD COLUMN parent_comment_id INT NULL DEFAULT NULL AFTER id;
-- UPDATE course_comments SET parent_comment_id = NULL WHERE parent_comment_id = 0 OR parent_comment_id IS NULL;

-- Test query to show current counts for Avalon:
SELECT 
    'All comments' as type,
    COUNT(*) as count 
FROM course_comments 
WHERE course_slug = 'avalon-golf-country-club'
UNION ALL
SELECT 
    'With ratings' as type,
    COUNT(*) as count 
FROM course_comments 
WHERE course_slug = 'avalon-golf-country-club' 
    AND rating IS NOT NULL
UNION ALL
SELECT 
    'Reviews only (if column exists)' as type,
    COUNT(*) as count 
FROM course_comments 
WHERE course_slug = 'avalon-golf-country-club' 
    AND rating IS NOT NULL
    AND (parent_comment_id IS NULL OR parent_comment_id = 0);