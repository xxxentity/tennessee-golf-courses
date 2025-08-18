-- API Security Database Schema
-- Tables for API authentication, rate limiting, and security logging

-- API Tokens for regular users
CREATE TABLE IF NOT EXISTS api_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(128) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL COMMENT 'Human-readable token name',
    permissions JSON COMMENT 'Array of permitted actions',
    expires_at TIMESTAMP NOT NULL,
    last_used TIMESTAMP NULL,
    usage_count INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at),
    INDEX idx_active (is_active)
);

-- API Tokens for admin users
CREATE TABLE IF NOT EXISTS admin_api_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL,
    token VARCHAR(128) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL COMMENT 'Human-readable token name',
    permissions JSON COMMENT 'Array of permitted admin actions',
    expires_at TIMESTAMP NOT NULL,
    last_used TIMESTAMP NULL,
    usage_count INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_admin_id (admin_id),
    INDEX idx_expires_at (expires_at),
    INDEX idx_active (is_active)
);

-- Rate limiting tracking
CREATE TABLE IF NOT EXISTS api_rate_limits (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    identifier VARCHAR(255) NOT NULL COMMENT 'User ID, IP address, or API token',
    user_type ENUM('public', 'user', 'admin', 'internal') DEFAULT 'public',
    endpoint VARCHAR(500) COMMENT 'API endpoint accessed',
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_identifier_created (identifier, created_at),
    INDEX idx_created_at (created_at),
    INDEX idx_ip_created (ip_address, created_at)
);

-- API security event logging
CREATE TABLE IF NOT EXISTS api_security_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    event_type VARCHAR(100) NOT NULL,
    severity ENUM('info', 'warning', 'error', 'critical') DEFAULT 'info',
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    user_id INT NULL COMMENT 'User ID if authenticated',
    admin_id INT NULL COMMENT 'Admin ID if admin authenticated',
    endpoint VARCHAR(500) COMMENT 'API endpoint involved',
    details JSON COMMENT 'Additional event details',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_event_type (event_type),
    INDEX idx_severity (severity),
    INDEX idx_ip_address (ip_address),
    INDEX idx_user_id (user_id),
    INDEX idx_admin_id (admin_id),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- API request analytics (optional, for monitoring)
CREATE TABLE IF NOT EXISTS api_analytics (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    endpoint VARCHAR(500) NOT NULL,
    method VARCHAR(10) NOT NULL,
    status_code INT NOT NULL,
    response_time_ms INT COMMENT 'Response time in milliseconds',
    user_type ENUM('public', 'user', 'admin', 'internal') DEFAULT 'public',
    user_id INT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    request_size INT COMMENT 'Request size in bytes',
    response_size INT COMMENT 'Response size in bytes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_endpoint (endpoint),
    INDEX idx_method (method),
    INDEX idx_status_code (status_code),
    INDEX idx_user_type (user_type),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Cleanup old rate limit records (run this as a scheduled job)
-- DELETE FROM api_rate_limits WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY);

-- Cleanup old security logs (keep 90 days)
-- DELETE FROM api_security_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- Cleanup old analytics (keep 30 days for detailed analytics)
-- DELETE FROM api_analytics WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- Example queries for monitoring:

-- Most active API endpoints
-- SELECT endpoint, COUNT(*) as requests, AVG(response_time_ms) as avg_response_time
-- FROM api_analytics 
-- WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
-- GROUP BY endpoint 
-- ORDER BY requests DESC 
-- LIMIT 10;

-- API error rates by endpoint
-- SELECT endpoint, 
--        COUNT(*) as total_requests,
--        SUM(CASE WHEN status_code >= 400 THEN 1 ELSE 0 END) as error_requests,
--        (SUM(CASE WHEN status_code >= 400 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as error_rate
-- FROM api_analytics 
-- WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
-- GROUP BY endpoint 
-- HAVING total_requests > 10
-- ORDER BY error_rate DESC;

-- Rate limit violations
-- SELECT identifier, user_type, COUNT(*) as violations
-- FROM api_security_logs 
-- WHERE event_type = 'rate_limit_exceeded' 
--   AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
-- GROUP BY identifier, user_type 
-- ORDER BY violations DESC;

-- Security events summary
-- SELECT event_type, severity, COUNT(*) as event_count
-- FROM api_security_logs 
-- WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
-- GROUP BY event_type, severity 
-- ORDER BY event_count DESC;