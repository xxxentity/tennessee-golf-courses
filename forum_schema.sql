-- Tennessee Golf Courses Forum Database Schema
-- Run this SQL to create the forum tables

-- Forum Categories Table
CREATE TABLE forum_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(100) DEFAULT 'fas fa-comments',
    color VARCHAR(7) DEFAULT '#064e3b',
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Forum Topics Table
CREATE TABLE forum_topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_pinned BOOLEAN DEFAULT FALSE,
    is_locked BOOLEAN DEFAULT FALSE,
    view_count INT DEFAULT 0,
    reply_count INT DEFAULT 0,
    last_reply_at TIMESTAMP NULL,
    last_reply_user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES forum_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (last_reply_user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_category_updated (category_id, updated_at DESC),
    INDEX idx_user_topics (user_id, created_at DESC)
);

-- Forum Posts (Replies) Table
CREATE TABLE forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    is_edited BOOLEAN DEFAULT FALSE,
    edited_at TIMESTAMP NULL,
    edited_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (edited_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_topic_posts (topic_id, created_at ASC),
    INDEX idx_user_posts (user_id, created_at DESC)
);

-- Forum User Stats Table (optional - for tracking user forum activity)
CREATE TABLE forum_user_stats (
    user_id INT PRIMARY KEY,
    topic_count INT DEFAULT 0,
    post_count INT DEFAULT 0,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reputation_score INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert default forum categories
INSERT INTO forum_categories (name, description, icon, color, sort_order) VALUES
('Course Reviews & Discussions', 'Share your experiences and reviews of Tennessee golf courses', 'fas fa-golf-ball', '#064e3b', 1),
('Equipment Talk', 'Discuss golf clubs, balls, gear, and equipment reviews', 'fas fa-golf-club', '#ea580c', 2),
('Tournament & Events', 'Local tournaments, events, and golf meetups in Tennessee', 'fas fa-trophy', '#7c3aed', 3),
('Tips & Instruction', 'Golf tips, lessons, and technique discussions', 'fas fa-graduation-cap', '#059669', 4),
('General Golf Chat', 'General golf discussions, news, and casual conversation', 'fas fa-comments', '#0369a1', 5),
('Site Feedback', 'Suggestions and feedback about Tennessee Golf Courses website', 'fas fa-comment-dots', '#dc2626', 6);

-- Triggers to update topic counts and last reply info
DELIMITER //

CREATE TRIGGER update_topic_reply_count_insert
AFTER INSERT ON forum_posts
FOR EACH ROW
BEGIN
    UPDATE forum_topics 
    SET reply_count = reply_count + 1,
        last_reply_at = NEW.created_at,
        last_reply_user_id = NEW.user_id
    WHERE id = NEW.topic_id;
    
    INSERT INTO forum_user_stats (user_id, post_count) 
    VALUES (NEW.user_id, 1)
    ON DUPLICATE KEY UPDATE 
        post_count = post_count + 1,
        last_activity = NEW.created_at;
END//

CREATE TRIGGER update_topic_reply_count_delete
AFTER DELETE ON forum_posts
FOR EACH ROW
BEGIN
    UPDATE forum_topics 
    SET reply_count = reply_count - 1
    WHERE id = OLD.topic_id;
    
    UPDATE forum_user_stats 
    SET post_count = GREATEST(0, post_count - 1)
    WHERE user_id = OLD.user_id;
END//

CREATE TRIGGER update_user_stats_topic_insert
AFTER INSERT ON forum_topics
FOR EACH ROW
BEGIN
    INSERT INTO forum_user_stats (user_id, topic_count) 
    VALUES (NEW.user_id, 1)
    ON DUPLICATE KEY UPDATE 
        topic_count = topic_count + 1,
        last_activity = NEW.created_at;
END//

CREATE TRIGGER update_user_stats_topic_delete
AFTER DELETE ON forum_topics
FOR EACH ROW
BEGIN
    UPDATE forum_user_stats 
    SET topic_count = GREATEST(0, topic_count - 1)
    WHERE user_id = OLD.user_id;
END//

DELIMITER ;

-- Views for easier data retrieval
CREATE VIEW forum_category_stats AS
SELECT 
    c.id,
    c.name,
    c.description,
    c.icon,
    c.color,
    c.sort_order,
    COUNT(DISTINCT t.id) as topic_count,
    COUNT(p.id) as post_count,
    MAX(COALESCE(p.created_at, t.created_at)) as last_activity,
    (SELECT CONCAT(u.first_name, ' ', u.last_name) 
     FROM forum_posts p2 
     JOIN users u ON p2.user_id = u.id 
     WHERE p2.topic_id IN (SELECT id FROM forum_topics WHERE category_id = c.id)
     ORDER BY p2.created_at DESC LIMIT 1) as last_post_author
FROM forum_categories c
LEFT JOIN forum_topics t ON c.id = t.category_id
LEFT JOIN forum_posts p ON t.id = p.topic_id
WHERE c.is_active = TRUE
GROUP BY c.id, c.name, c.description, c.icon, c.color, c.sort_order
ORDER BY c.sort_order;

CREATE VIEW forum_topic_list AS
SELECT 
    t.id,
    t.title,
    t.content,
    t.is_pinned,
    t.is_locked,
    t.view_count,
    t.reply_count,
    t.created_at,
    t.last_reply_at,
    c.name as category_name,
    c.id as category_id,
    CONCAT(u.first_name, ' ', u.last_name) as author_name,
    u.id as author_id,
    COALESCE(CONCAT(ur.first_name, ' ', ur.last_name), CONCAT(u.first_name, ' ', u.last_name)) as last_reply_author
FROM forum_topics t
JOIN forum_categories c ON t.category_id = c.id
JOIN users u ON t.user_id = u.id
LEFT JOIN users ur ON t.last_reply_user_id = ur.id
WHERE c.is_active = TRUE
ORDER BY t.is_pinned DESC, COALESCE(t.last_reply_at, t.created_at) DESC;