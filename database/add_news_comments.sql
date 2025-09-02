-- Create news_comments table for user comments on news articles
CREATE TABLE news_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    article_slug VARCHAR(100) NOT NULL,
    article_title VARCHAR(255) NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_approved BOOLEAN DEFAULT TRUE,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_article_slug (article_slug),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
);

-- Insert sample comments for testing (optional)
-- Note: These would need valid user_ids from your users table
-- INSERT INTO news_comments (user_id, article_slug, article_title, comment_text) VALUES
-- (1, 'scheffler-extends-lead-open-championship-round-3', 'Scheffler Extends Lead to Four Shots with Bogey-Free 67', 'Great coverage of the tournament! Scheffler is looking unstoppable.'),
-- (2, 'scheffler-extends-lead-open-championship-round-3', 'Scheffler Extends Lead to Four Shots with Bogey-Free 67', 'That eagle on 12 by McIlroy was incredible to watch live!');