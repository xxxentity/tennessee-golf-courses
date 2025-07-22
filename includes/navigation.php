<?php
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$first_name = $is_logged_in ? $_SESSION['first_name'] : '';
?>

<!-- Header -->
<header class="header">
    <nav class="nav">
        <div class="nav-container">
        <?php
        // Determine the correct base URL for navigation
        $script_name = $_SERVER['SCRIPT_NAME'];
        
        // Check if we're in an actual subdirectory (contains folder/file.php pattern)
        // Root files: /index.php, /courses.php, /news.php, /about.php, etc. -> use ''
        // Subfolder files: /courses/bear-trace.php, /auth/login.php, /user/profile.php, /news/article.php -> use '../'
        $is_in_subdirectory = (
            strpos($script_name, '/courses/') !== false ||
            strpos($script_name, '/auth/') !== false || 
            strpos($script_name, '/user/') !== false ||
            (strpos($script_name, '/news/') !== false && substr_count($script_name, '/') > 1)
        );
        
        // For subdirectories, use ../ to go back to root, otherwise use root path
        $base_url = $is_in_subdirectory ? '../' : '';
        
        // Debug info (temporarily disabled)
        // echo "<!-- DEBUG: Script name = " . $script_name . ", Is subdirectory: " . ($is_in_subdirectory ? 'true' : 'false') . ", Base URL: '" . $base_url . "' -->";
        ?>
        <a href="<?php echo $base_url; ?>index.php" class="nav-logo">
            <img src="<?php echo $base_url; ?>images/logos/logo.png" alt="Tennessee Golf Courses" class="logo-image">
        </a>
        
        <ul class="nav-menu" id="nav-menu">
            <li><a href="<?php echo $base_url; ?>index.php" class="nav-link">Home</a></li>
            <li><a href="<?php echo $base_url; ?>courses.php" class="nav-link">Courses</a></li>
            <li><a href="<?php echo $base_url; ?>reviews.php" class="nav-link">Reviews</a></li>
            <li><a href="<?php echo $base_url; ?>news.php" class="nav-link">News</a></li>
            <li><a href="<?php echo $base_url; ?>about.php" class="nav-link">About</a></li>
            <li><a href="<?php echo $base_url; ?>contact.php" class="nav-link">Contact</a></li>
        </ul>
        
        <div class="nav-auth">
            <?php if ($is_logged_in): ?>
                <!-- Logged in navigation -->
                <div class="user-welcome">
                    <span class="welcome-text">Welcome, <?php echo htmlspecialchars($first_name); ?>!</span>
                </div>
                <a href="<?php echo $base_url; ?>user/profile.php" class="nav-link">My Profile</a>
                <a href="<?php echo $base_url; ?>auth/logout.php" class="nav-link logout-link">Logout</a>
            <?php else: ?>
                <!-- Logged out navigation -->
                <a href="<?php echo $base_url; ?>auth/login.php" class="nav-link login-btn">Login</a>
                <a href="<?php echo $base_url; ?>auth/register.php" class="nav-link register-btn">Join Free</a>
            <?php endif; ?>
        </div>
        
        <div class="nav-toggle" id="nav-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </div>
    </nav>
</header>

<style>
/* Logo image styling */
.logo-image {
    height: 100px;
    width: auto;
    object-fit: contain;
    transition: all 0.3s ease;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    max-height: 100px;
}

.nav-logo:hover .logo-image {
    transform: scale(1.05);
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
}

/* Navigation auth section on the right */
.nav-auth {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 6px;
    margin-left: auto;
    margin-right: 8px;
    flex-wrap: nowrap;
    max-width: 400px;
}

.user-welcome {
    display: flex;
    align-items: center;
    margin-left: 0;
}

.welcome-text {
    color: var(--primary-color);
    font-weight: 500;
    font-size: 16px;
    padding: 8px 16px;
    background: linear-gradient(135deg, rgba(6, 78, 59, 0.1), rgba(234, 88, 12, 0.1));
    border-radius: 8px;
    border: 1px solid rgba(6, 78, 59, 0.2);
    white-space: nowrap;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.login-btn {
    background: transparent;
    border: 1px solid var(--primary-color);
    color: var(--primary-color) !important;
    border-radius: 8px;
    transition: all 0.3s ease;
    padding: 8px 16px !important;
    font-size: 16px;
    font-weight: 500;
    width: auto;
    text-align: center;
    line-height: 1.2;
    white-space: nowrap;
}

.login-btn:hover {
    background: var(--primary-color);
    color: var(--text-light) !important;
    transform: translateY(-1px);
}

.register-btn {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-light) !important;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-light);
    padding: 8px 16px !important;
    font-size: 16px;
    font-weight: 500;
    width: auto;
    text-align: center;
    line-height: 1.2;
    white-space: nowrap;
}

.register-btn:hover {
    background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
    transform: translateY(-1px);
    box-shadow: var(--shadow-medium);
}

.logout-link {
    color: var(--gold-color) !important;
    font-weight: 500;
    padding: 8px 16px !important;
    font-size: 16px;
    border-radius: 8px;
    min-width: auto;
    text-align: center;
    line-height: 1.2;
}

.logout-link:hover {
    background: rgba(234, 88, 12, 0.1);
    color: var(--gold-color) !important;
}

.nav-auth .nav-link {
    padding: 8px 16px !important;
    font-size: 16px;
    border-radius: 8px;
    min-width: auto;
    text-align: center;
    line-height: 1.2;
}

/* Mobile responsiveness for user navigation */
@media screen and (max-width: 768px) {
    .nav-auth {
        position: fixed;
        left: -100%;
        top: 110px;
        flex-direction: column;
        background-color: var(--bg-white);
        width: 100%;
        text-align: center;
        transition: var(--transition);
        box-shadow: var(--shadow-medium);
        padding: 20px 0;
        gap: 20px;
        z-index: 999;
    }
    
    .nav-menu.active + .nav-auth {
        left: 0;
    }
    
    .user-welcome {
        margin-left: 0;
        margin-top: 12px;
        justify-content: center;
    }
    
    .welcome-text {
        font-size: 16px;
        padding: 8px 16px;
    }
    
    .nav-menu li {
        margin: 8px 0;
    }
}
</style>