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
$first_name = $is_logged_in ? SecureSession::get('first_name', '') : '';

// Determine if this is a main site page (should show weather bar)
$current_page = $_SERVER['REQUEST_URI'];
$is_main_page = (
    $current_page === '/' ||
    strpos($current_page, '/courses') === 0 ||
    strpos($current_page, '/media') === 0 ||
    strpos($current_page, '/reviews') === 0 ||
    strpos($current_page, '/news') === 0 ||
    strpos($current_page, '/events') === 0 ||
    strpos($current_page, '/community') === 0 ||
    strpos($current_page, '/about') === 0 ||
    strpos($current_page, '/contact') === 0
);


?>

<?php if ($is_main_page): ?>
<!-- Weather Bar (only on main site pages) -->
<div class="weather-bar" style="transition: transform 0.3s ease;">
    <div class="weather-container">
        <div class="weather-info">
            <div class="current-time">
                <i class="fas fa-clock"></i>
                <span id="current-time">Loading...</span>
            </div>
            <div class="weather-widget">
                <i class="fas fa-cloud-sun"></i>
                <span id="weather-temp">Perfect Golf Weather</span>
                <span class="weather-location">Nashville, TN</span>
            </div>
        </div>
        <div class="golf-conditions">
            <div class="condition-item">
                <i class="fas fa-wind"></i>
                <span>Light Breeze</span>
            </div>
            <div class="condition-item">
                <i class="fas fa-eye"></i>
                <span>Clear Visibility</span>
            </div>
            <div class="condition-item">
                <i class="fas fa-golf-ball"></i>
                <span>Great Conditions</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Header -->
<header class="header">
    <nav class="nav">
        <div class="nav-container">
        <?php
        echo "<!-- Professional Clean URLs Implementation -->";
        ?>
        <a href="/" class="nav-logo">
            <img src="/images/logos/logo.webp" alt="Tennessee Golf Courses" class="logo-image">
        </a>
        
        <ul class="nav-menu" id="nav-menu">
            <li><a href="/" class="nav-link">Home</a></li>
            <li class="nav-dropdown">
                <a href="/courses" class="nav-link dropdown-toggle">
                    Courses <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/maps" class="dropdown-link">Maps</a></li>
                </ul>
            </li>
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
            
            <!-- Mobile Auth Links -->
            <li class="mobile-auth-divider"></li>
            <?php if ($is_logged_in): ?>
                <li class="mobile-auth-item"><span class="nav-link welcome-mobile">Welcome, <?php echo htmlspecialchars($first_name); ?>!</span></li>
                <li class="mobile-auth-item"><a href="/profile" class="nav-link">My Profile</a></li>
                <li class="mobile-auth-item"><a href="/logout" class="nav-link logout-link">Logout</a></li>
            <?php else: ?>
                <li class="mobile-auth-item"><a href="/login" class="nav-link login-btn">Login</a></li>
                <li class="mobile-auth-item"><a href="/register" class="nav-link register-btn">Register</a></li>
            <?php endif; ?>
        </ul>
        
        <div class="nav-auth">
            <?php if ($is_logged_in): ?>
                <!-- Logged in navigation -->
                <div class="user-welcome">
                    <span class="welcome-text">Welcome, <?php echo htmlspecialchars($first_name); ?>!</span>
                </div>
                <a href="/profile" class="nav-link">My Profile</a>
                <a href="/logout" class="nav-link logout-link">Logout</a>
            <?php else: ?>
                <!-- Logged out navigation -->
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

.login-btn {
    background: transparent;
    border: 1px solid var(--primary-color);
    color: var(--primary-color) !important;
    border-radius: 8px;
    transition: all 0.3s ease;
    padding: 6px 12px !important;
    font-size: 14px;
    font-weight: 500;
    width: auto;
    text-align: center;
    line-height: 1.2;
    white-space: nowrap;
    flex-shrink: 0;
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
    padding: 6px 12px !important;
    font-size: 14px;
    font-weight: 500;
    width: auto;
    text-align: center;
    line-height: 1.2;
    white-space: nowrap;
    flex-shrink: 0;
}

.register-btn:hover {
    background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
    transform: translateY(-1px);
    box-shadow: var(--shadow-medium);
}

.logout-link {
    color: var(--gold-color) !important;
    font-weight: 500;
    padding: 6px 12px !important;
    font-size: 14px;
    border-radius: 8px;
    min-width: auto;
    text-align: center;
    line-height: 1.2;
    flex-shrink: 0;
    white-space: nowrap;
}

.logout-link:hover {
    background: rgba(234, 88, 12, 0.1);
    color: var(--gold-color) !important;
}

.nav-auth .nav-link {
    padding: 6px 12px !important;
    font-size: 14px;
    border-radius: 8px;
    min-width: auto;
    text-align: center;
    line-height: 1.2;
    flex-shrink: 0;
    white-space: nowrap;
}

/* Medium screen adjustments */
@media screen and (max-width: 1024px) {
    .welcome-text {
        font-size: 13px;
        padding: 5px 10px;
        max-width: 140px;
    }
    
    .nav-auth .nav-link {
        padding: 5px 10px !important;
        font-size: 13px;
    }
    
    .nav-auth {
        gap: 6px;
        margin-right: 12px;
    }
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
/* Conditional header positioning */
.header {
    position: fixed;
    top: <?php echo $is_main_page ? '40px' : '0' ?>; /* Adjust based on weather bar presence */
    left: 0;
    right: 0;
    z-index: 1000;
    background: var(--bg-white);
    box-shadow: var(--shadow-light);
    transition: top 0.3s ease;
}

/* Body padding adjustment */
body {
    padding-top: <?php echo $is_main_page ? '140px' : '100px' ?>; /* Adjust based on weather bar presence */
}

/* Auth page specific overrides */
<?php if (!$is_main_page): ?>
.auth-page .header {
    position: fixed;
    top: 0 !important;
    left: 0;
    right: 0;
    z-index: 999;
    background: var(--bg-white);
    box-shadow: var(--shadow-light);
}

.auth-page {
    padding-top: 80px !important;
}

/* Hide weather bar on non-main pages */
.weather-bar {
    display: none !important;
    height: 0 !important;
}
<?php endif; ?>

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
    border-radius: 0;
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