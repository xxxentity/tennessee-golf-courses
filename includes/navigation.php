<?php
// Determine if this is a main site page (should show weather bar)
$current_page = $_SERVER['REQUEST_URI'];

$is_individual_course = (strpos($current_page, '/courses/') === 0 && $current_page !== '/courses/');

$course_slug = null;
if ($is_individual_course) {
    $course_slug = substr($current_page, 9);
    if (strpos($course_slug, '?') !== false) {
        $course_slug = substr($course_slug, 0, strpos($course_slug, '?'));
    }
    if (strpos($course_slug, '/') !== false) {
        $course_slug = substr($course_slug, 0, strpos($course_slug, '/'));
    }
}

$is_main_page = (
    $current_page === '/' ||
    strpos($current_page, '/courses') === 0 ||
    strpos($current_page, '/reviews') === 0 ||
    strpos($current_page, '/news') === 0 ||
    strpos($current_page, '/about') === 0 ||
    strpos($current_page, '/contact') === 0
);
?>

<?php if ($is_main_page): ?>
<!-- Weather Bar -->
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
                <a href="/reviews" class="nav-link dropdown-toggle">
                    Media <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/news" class="dropdown-link">News</a></li>
                    <li><a href="/reviews" class="dropdown-link">Reviews</a></li>
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
        </ul>

        <div class="nav-toggle" id="nav-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </div>
    </nav>
</header>

<?php
require_once __DIR__ . '/cookie-consent.php';
?>

<style>
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

/* Conditional header positioning */
.header {
    position: fixed;
    top: <?php echo $is_main_page ? '40px' : '0' ?>;
    left: 0;
    right: 0;
    z-index: 1000;
    background: var(--bg-white);
    box-shadow: var(--shadow-light);
    transition: top 0.3s ease;
}

body {
    padding-top: <?php echo $is_main_page ? '140px' : '100px' ?>;
}

/* Override nav-container overflow for dropdowns */
.nav-container {
    overflow: visible !important;
}

/* Dropdown Navigation Styles */
.nav-dropdown {
    position: relative;
}

.dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.dropdown-toggle i {
    font-size: 0.8rem;
    transition: transform 0.3s ease;
}

.nav-dropdown:hover .dropdown-toggle i {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: var(--bg-white);
    min-width: 160px;
    box-shadow: var(--shadow-medium);
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 9999;
    list-style: none;
    padding: 0.5rem 0;
    margin: 0;
}

.nav-dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-link {
    display: block;
    padding: 0.75rem 1.25rem;
    color: var(--text-dark);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.dropdown-link:hover {
    background: var(--bg-light);
    color: var(--primary-color);
    padding-left: 1.5rem;
}

/* Mobile dropdown styles */
@media (max-width: 768px) {
    .nav-dropdown .dropdown-menu {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        border: none;
        background: transparent;
        padding: 0;
        margin-left: 1rem;
    }

    .dropdown-toggle i {
        display: none;
    }

    .dropdown-link {
        padding: 0.5rem 0;
        font-size: 0.9rem;
        color: var(--text-gray);
    }

    .dropdown-link:hover {
        padding-left: 0;
        color: var(--primary-color);
    }
}
</style>

<?php if ($is_main_page): ?>
<!-- Weather Scripts -->
<?php if ($is_individual_course && $course_slug): ?>
<script src="/course-weather.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.initializeCourseWeather) {
            window.initializeCourseWeather('<?php echo htmlspecialchars($course_slug); ?>');
        }
    });
</script>
<?php else: ?>
<script src="/weather.js"></script>
<?php endif; ?>
<script src="/script.js?v=<?php echo time(); ?>"></script>
<?php endif; ?>
