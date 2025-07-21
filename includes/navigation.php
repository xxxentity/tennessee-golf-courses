<?php
session_start();

// Check if user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$first_name = $is_logged_in ? $_SESSION['first_name'] : '';
?>

<!-- Header -->
<header class="header">
    <nav class="nav">
        <div class="nav-container">
        <a href="/index.html" class="nav-logo">
            <i class="fas fa-golf-ball"></i>
            Tennessee Golf Courses
        </a>
        
        <ul class="nav-menu" id="nav-menu">
            <li><a href="/index.html" class="nav-link">Home</a></li>
            
            <?php if ($is_logged_in): ?>
                <!-- Logged in navigation -->
                <li><a href="/user/profile.php" class="nav-link">My Profile</a></li>
                <li><a href="/auth/logout.php" class="nav-link logout-link">Logout</a></li>
                <li class="user-welcome">
                    <span class="welcome-text">Welcome, <?php echo htmlspecialchars($first_name); ?>!</span>
                </li>
            <?php else: ?>
                <!-- Logged out navigation -->
                <li><a href="/auth/login.php" class="nav-link login-btn">Login</a></li>
                <li><a href="/auth/register.php" class="nav-link register-btn">Join Free</a></li>
            <?php endif; ?>
        </ul>
        
        <div class="nav-toggle" id="nav-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </div>
    </nav>
</header>

<style>
/* Additional styles for user navigation */
.user-welcome {
    display: flex;
    align-items: center;
    margin-left: 16px;
}

.welcome-text {
    color: var(--primary-color);
    font-weight: 600;
    font-size: 14px;
    padding: 8px 16px;
    background: linear-gradient(135deg, rgba(6, 78, 59, 0.1), rgba(234, 88, 12, 0.1));
    border-radius: 8px;
    border: 1px solid rgba(6, 78, 59, 0.2);
}

.login-btn {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color) !important;
    border-radius: 8px;
    transition: all 0.3s ease;
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
}

.register-btn:hover {
    background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
    transform: translateY(-1px);
    box-shadow: var(--shadow-medium);
}

.logout-link {
    color: var(--gold-color) !important;
    font-weight: 500;
}

.logout-link:hover {
    background: rgba(234, 88, 12, 0.1);
    color: var(--gold-color) !important;
}

/* Mobile responsiveness for user navigation */
@media screen and (max-width: 768px) {
    .user-welcome {
        margin-left: 0;
        margin-top: 12px;
        justify-content: center;
    }
    
    .welcome-text {
        font-size: 13px;
        padding: 6px 12px;
    }
    
    .nav-menu li {
        margin: 8px 0;
    }
}
</style>