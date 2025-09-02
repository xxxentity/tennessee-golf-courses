// Mobile Improvements Script
document.addEventListener('DOMContentLoaded', function() {
    // Close mobile menu when clicking outside
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle && navMenu) {
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideMenu = navMenu.contains(event.target);
            const isClickOnToggle = navToggle.contains(event.target);
            
            if (!isClickInsideMenu && !isClickOnToggle && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
            }
        });
        
        // Close menu when clicking on a link
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
            });
        });
        
        // Prevent body scroll when menu is open
        navToggle.addEventListener('click', function() {
            if (navMenu.classList.contains('active')) {
                document.body.style.overflow = 'auto';
            } else {
                document.body.style.overflow = 'hidden';
            }
        });
    }
    
    // Improve touch responsiveness
    if ('ontouchstart' in window) {
        document.body.classList.add('touch-device');
        
        // Add active states for better touch feedback
        const touchElements = document.querySelectorAll('a, button, .btn-primary, .btn-secondary');
        touchElements.forEach(element => {
            element.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
            });
            
            element.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touch-active');
                }, 150);
            });
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerOffset = 80; // Account for fixed headers
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Fix iOS input zoom issue
    if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
        const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], textarea');
        inputs.forEach(input => {
            input.style.fontSize = '16px';
        });
    }
});

// Add CSS for touch feedback
const style = document.createElement('style');
style.textContent = `
    .touch-active {
        opacity: 0.7 !important;
        transform: scale(0.98) !important;
    }
    
    .touch-device a,
    .touch-device button {
        -webkit-tap-highlight-color: rgba(0,0,0,0.1);
    }
`;
document.head.appendChild(style);