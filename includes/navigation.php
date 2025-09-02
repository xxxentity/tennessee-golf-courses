<?php
require_once __DIR__ . '/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - user not logged in
}

// Check if user is logged in using secure session
$is_logged_in = SecureSession::isLoggedIn();
$username = $is_logged_in ? SecureSession::get('username', '') : '';
$first_name = $is_logged_in ? SecureSession::get('first_name', '') : '';

// Determine if this is a main site page (should show weather bar)
$current_page = $_SERVER['REQUEST_URI'];

// Check if this is an individual course page (exclude these from main page logic)
$is_individual_course = (strpos($current_page, '/courses/') === 0 && $current_page !== '/courses/');

// Extract course slug ONLY for individual course pages
$course_slug = null;
if ($is_individual_course) {
    $course_slug = substr($current_page, 9); // Remove '/courses/'
    if (strpos($course_slug, '/') !== false) {
        $course_slug = substr($course_slug, 0, strpos($course_slug, '/')); // Remove any trailing path
    }
}

// Main pages (including individual course pages) - all show weather bar
$is_main_page = (
    $current_page === '/' ||
    (strpos($current_page, '/courses') === 0) ||  // Include ALL course pages (main + individual)
    strpos($current_page, '/media') === 0 ||
    strpos($current_page, '/reviews') === 0 ||
    strpos($current_page, '/news') === 0 ||
    strpos($current_page, '/events') === 0 ||
    strpos($current_page, '/community') === 0 ||
    strpos($current_page, '/maps') === 0 ||
    strpos($current_page, '/about') === 0 ||
    strpos($current_page, '/contact') === 0 ||
    strpos($current_page, '/contests') === 0 ||
    strpos($current_page, '/handicap') === 0 ||
    strpos($current_page, '/user') === 0 ||  // Include user directory pages
    strpos($current_page, '/profile') === 0 ||  // Include profile pages
    strpos($current_page, '/edit-profile') === 0 ||  // Include edit profile
    strpos($current_page, '/security-dashboard') === 0  // Include security dashboard
);
?>

<?php if ($is_main_page): ?>
<!-- Weather Bar (only on main site pages) -->
<div class="weather-bar" style="transition: transform 0.3s ease; text-align: center !important;">
    <div class="weather-container" style="display: flex !important; justify-content: center !important; align-items: center !important; width: 100% !important; text-align: center !important;">
        <div class="weather-info" style="display: flex !important; justify-content: center !important; align-items: center !important; gap: 20px !important; text-align: center !important;">
            <div class="current-time" style="text-align: center !important;">
                <span id="current-datetime">Loading...</span>
            </div>
            <div class="weather-data" style="display: flex !important; justify-content: center !important; align-items: center !important; gap: 10px !important; text-align: center !important;">
                <span class="weather-label" id="weather-location">Nashville, TN:</span>
                <span id="weather-temp">--°F</span>
                <span id="weather-precip-section">
                    <span class="weather-separator">|</span>
                    <span class="weather-label">Precip:</span>
                    <span id="weather-precip">--%</span>
                </span>
                <span class="weather-separator">|</span>
                <span class="weather-label">Wind:</span>
                <span id="weather-wind">-- mph</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Header -->
<header class="header">
    <nav class="nav">
        <div class="nav-container">
            <a href="/" class="nav-logo">
                <img src="/images/logos/logo.webp" alt="Tennessee Golf Courses" class="logo-image">
            </a>
            
            <ul class="nav-menu" id="nav-menu">
                <li><a href="/" class="nav-link">Home</a></li>
                <li><a href="/courses" class="nav-link">Courses</a></li>
                <li><a href="/maps" class="nav-link">Maps</a></li>
                <li class="nav-dropdown">
                    <a href="/media" class="nav-link dropdown-toggle">
                        Media <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/news" class="dropdown-link">News</a></li>
                        <li><a href="/reviews" class="dropdown-link">Reviews</a></li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <a href="/community" class="nav-link dropdown-toggle">
                        Community <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/events" class="dropdown-link">Events</a></li>
                        <li><a href="/handicap" class="dropdown-link">Handicap</a></li>
                        <li><a href="/contests" class="dropdown-link">Contests</a></li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <a href="/about" class="nav-link dropdown-toggle">
                        About <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/about" class="dropdown-link">About Us</a></li>
                        <li><a href="/contact" class="dropdown-link">Contact</a></li>
                    </ul>
                </li>
                
                <!-- Mobile Auth Links (hidden on desktop) -->
                <?php if ($is_logged_in): ?>
                    <li class="mobile-auth-item"><span class="nav-link welcome-mobile">Welcome, <?php echo htmlspecialchars($username); ?>!</span></li>
                    <li class="mobile-auth-item"><a href="/profile" class="nav-link">My Profile</a></li>
                    <li class="mobile-auth-item"><a href="/logout" class="nav-link logout-link">Logout</a></li>
                <?php else: ?>
                    <li class="mobile-auth-item"><a href="/login" class="nav-link login-btn">Login</a></li>
                    <li class="mobile-auth-item"><a href="/register" class="nav-link register-btn">Register</a></li>
                <?php endif; ?>
            </ul>
            
            <!-- Desktop Auth Section -->
            <div class="nav-auth">
                <?php if ($is_logged_in): ?>
                    <div class="user-welcome">
                        <span class="welcome-text">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
                    </div>
                    <a href="/profile" class="nav-link">My Profile</a>
                    <a href="/logout" class="nav-link logout-link">Logout</a>
                <?php else: ?>
                    <a href="/login" class="nav-link login-btn">Login</a>
                    <a href="/register" class="nav-link register-btn">Register</a>
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

<?php
// Include cookie consent banner
require_once __DIR__ . '/cookie-consent.php';
?>

<style>
/* Logo image styling */
.logo-image {
    height: 60px;
    width: auto;
    object-fit: contain;
    transition: all 0.3s ease;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    max-height: 60px;
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
    gap: 8px;
    margin-left: auto;
    margin-right: 16px;
    flex-wrap: nowrap;
    min-width: 0;
    flex-shrink: 0;
}

.user-welcome {
    display: flex;
    align-items: center;
    margin-left: 0;
}

.welcome-text {
    color: var(--primary-color);
    font-weight: 500;
    font-size: 14px;
    padding: 6px 12px;
    background: linear-gradient(135deg, rgba(6, 78, 59, 0.1), rgba(234, 88, 12, 0.1));
    border-radius: 8px;
    border: 1px solid rgba(6, 78, 59, 0.2);
    white-space: nowrap;
    max-width: 160px;
    overflow: hidden;
    text-overflow: ellipsis;
    flex-shrink: 1;
}

/* Hide mobile auth items on desktop */
.mobile-auth-item {
    display: none;
}

/* Mobile navigation */
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
    
    /* Show mobile auth items on mobile */
    .mobile-auth-item {
        display: list-item;
        margin: 8px 0;
    }
}
</style>

<?php if ($is_main_page): ?>
<!-- Weather Scripts -->
<?php if ($course_slug): ?>
<!-- Course-specific weather -->
<script src="/course-weather.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.initializeCourseWeather) {
            window.initializeCourseWeather('<?php echo htmlspecialchars($course_slug); ?>');
        }
    });
</script>
<?php else: ?>
<!-- Default Nashville weather -->
<script src="/weather.js"></script>
<?php endif; ?>
<script src="/script.js?v=6"></script>
<?php endif; ?>