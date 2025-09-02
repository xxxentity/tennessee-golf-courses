<?php
/**
 * Helper functions for profile links
 */

/**
 * Generate a profile link for a user
 * @param string $username The username
 * @param string $display_name Optional display name (defaults to username)
 * @param string $css_class Optional CSS class
 * @return string HTML link
 */
function getProfileLink($username, $display_name = null, $css_class = '') {
    if (empty($username)) {
        return htmlspecialchars($display_name ?: 'Anonymous');
    }
    
    $display = htmlspecialchars($display_name ?: $username);
    $username = htmlspecialchars($username);
    $class_attr = $css_class ? ' class="' . htmlspecialchars($css_class) . '"' : '';
    
    return '<a href="/profile/' . $username . '"' . $class_attr . '>' . $display . '</a>';
}

/**
 * Generate profile link HTML for comments
 * @param array $comment Comment data with username
 * @return string HTML for comment author with profile link
 */
function getCommentAuthorLink($comment) {
    $username = $comment['username'] ?? '';
    return getProfileLink($username, $username, 'comment-author');
}
?>