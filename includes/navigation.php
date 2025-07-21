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
            <li><a href="#home" class="nav-link">Home</a></li>
            <li><a href="#courses" class="nav-link">Courses</a></li>
            <li><a href="#reviews" class="nav-link">Reviews</a></li>
            <li><a href="#news" class="nav-link">News</a></li>
            <li><a href="#about" class="nav-link">About</a></li>
            <li><a href="#contact" class="nav-link">Contact</a></li>
        </ul>
        
        <div class="nav-auth">
            <?php if ($is_logged_in): ?>
                <!-- Logged in navigation -->
                <a href="/user/profile.php" class="nav-link">My Profile</a>
                <a href="/auth/logout.php" class="nav-link logout-link">Logout</a>
                <div class="user-welcome">
                    <span class="welcome-text">Welcome, <?php echo htmlspecialchars($first_name); ?>!</span>
                </div>
            <?php else: ?>
                <!-- Logged out navigation -->
                <a href="/auth/login.php" class="nav-link login-btn">Login</a>
                <a href="/auth/register.php" class="nav-link register-btn">Join Free</a>
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
/* Navigation auth section on the right */
.nav-auth {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    margin-left: auto;
    margin-right: 8px;
}

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
    border: 1px solid var(--primary-color);
    color: var(--primary-color) !important;
    border-radius: 4px;
    transition: all 0.3s ease;
    padding: 4px 12px !important;
    font-size: 12px;
    font-weight: 500;
    width: 85px;
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
    border-radius: 4px;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-light);
    padding: 4px 12px !important;
    font-size: 12px;
    font-weight: 500;
    width: 85px;
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
    padding: 4px 10px !important;
    font-size: 12px;
    border-radius: 4px;
    min-width: 60px;
    text-align: center;
    line-height: 1.2;
}

.logout-link:hover {
    background: rgba(234, 88, 12, 0.1);
    color: var(--gold-color) !important;
}

.nav-auth .nav-link {
    padding: 4px 10px !important;
    font-size: 12px;
    border-radius: 4px;
    min-width: 60px;
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
        font-size: 13px;
        padding: 6px 12px;
    }
    
    .nav-menu li {
        margin: 8px 0;
    }
}
</style>