-- Add reply system support to course_comments table
-- Run this SQL to enable the reply functionality

-- Add parent_comment_id column to support threaded replies
ALTER TABLE course_comments 
ADD COLUMN parent_comment_id INT NULL DEFAULT NULL AFTER id;

-- Add foreign key constraint (optional, for data integrity)
ALTER TABLE course_comments 
ADD CONSTRAINT fk_parent_comment 
FOREIGN KEY (parent_comment_id) REFERENCES course_comments(id) 
ON DELETE CASCADE;

-- Add index for better performance when fetching replies
CREATE INDEX idx_parent_comment_id ON course_comments(parent_comment_id);

-- Also modify rating column to support decimal ratings (half-stars)
ALTER TABLE course_comments 
MODIFY COLUMN rating DECIMAL(2,1) NULL;

-- Show the updated table structure
DESCRIBE course_comments;