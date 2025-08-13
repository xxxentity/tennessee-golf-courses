<?php
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$first_name = $is_logged_in ? $_SESSION['first_name'] : '';

// Determine if this is a main site page (should show weather bar)
$current_page = $_SERVER['REQUEST_URI'];
$is_main_page = (
    $current_page === '/' ||
    strpos($current_page, '/courses') === 0 ||
    strpos($current_page, '/reviews') === 0 ||
    strpos($current_page, '/news') === 0 ||
    strpos($current_page, '/events') === 0 ||
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
            <li><a href="/courses" class="nav-link">Courses</a></li>
            <li><a href="/reviews" class="nav-link">Reviews</a></li>
            <li><a href="/news" class="nav-link">News</a></li>
            <li><a href="/events" class="nav-link">Events</a></li>
            <li><a href="/about" class="nav-link">About</a></li>
            <li><a href="/contact" class="nav-link">Contact</a></li>
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
</style>