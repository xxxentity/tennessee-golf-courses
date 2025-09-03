// Tennessee Golf Courses - JavaScript Functionality

document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check if JavaScript is loading
    console.log('TGC Script loaded, current page:', window.location.pathname);
    
    // Make all external links open in new tabs
    const links = document.querySelectorAll('a[href]');
    links.forEach(link => {
        const href = link.getAttribute('href');
        // Check if it's an external link (starts with http/https but not our domain)
        if ((href.startsWith('http://') || href.startsWith('https://') || href.startsWith('www.')) 
            && !href.includes('tennesseegolfcourses.com')) {
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', 'noopener noreferrer');
        }
    });
    
    // Live Date/Time Display (simplified without icon)
    function updateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            timeZone: 'America/Chicago' // Central Time for Tennessee
        };
        const timeString = now.toLocaleDateString('en-US', options);
        const timeElement = document.getElementById('current-datetime');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
        // Also check for old ID for backward compatibility
        const oldTimeElement = document.getElementById('current-time');
        if (oldTimeElement) {
            oldTimeElement.textContent = timeString;
        }
    }

    // Update time immediately and then every minute
    updateTime();
    setInterval(updateTime, 60000);

    // Weather is now handled by weather.js centralized system

    // Weather bar scroll functionality with debugging
    const weatherBar = document.querySelector('.weather-bar');
    const header = document.querySelector('.header');
    let weatherBarVisible = true;
    
    console.log('Weather bar element found:', !!weatherBar);
    console.log('Header element found:', !!header);
    
    if (weatherBar) {
        console.log('Setting up weather bar scroll listener');
        let ticking = false;
        
        function updateWeatherBarOnScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            console.log('Scroll position:', scrollTop);
            
            if (scrollTop > 200 && weatherBarVisible) {
                console.log('Hiding weather bar');
                weatherBar.style.transform = 'translateY(-100%)';
                weatherBarVisible = false;
                if (header) {
                    header.style.top = '0';
                }
            } else if (scrollTop <= 200 && !weatherBarVisible) {
                console.log('Showing weather bar');
                weatherBar.style.transform = 'translateY(0)';
                weatherBarVisible = true;
                if (header) {
                    header.style.top = '40px';
                }
            }
            
            ticking = false;
        }
        
        function requestScrollUpdate() {
            if (!ticking) {
                requestAnimationFrame(updateWeatherBarOnScroll);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestScrollUpdate, { passive: true });
    }
    // Mobile Navigation Toggle with Debug
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');

    console.log('Nav toggle found:', !!navToggle);
    console.log('Nav menu found:', !!navMenu);

    // Remove debug indicator for production
    // const debugIndicator = document.createElement('div');
    // debugIndicator.style.cssText = `...`;
    // document.body.appendChild(debugIndicator);

    if (navToggle && navMenu) {
        // Add touch and click handlers for better mobile support
        navToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const wasActive = navMenu.classList.contains('active');
            navMenu.classList.toggle('active');
            const nowActive = navMenu.classList.contains('active');
            
            // Force the menu to show if active class is present
            if (navMenu.classList.contains('active')) {
                // Try multiple approaches to force show
                navMenu.style.cssText = `
                    position: fixed !important;
                    left: 0px !important;
                    top: 92px !important;
                    width: 100% !important;
                    height: calc(100vh - 92px) !important;
                    background: white !important;
                    z-index: 9998 !important;
                    display: flex !important;
                    flex-direction: column !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                    transform: translateX(0) !important;
                `;
            } else {
                navMenu.style.left = '-100%';
                navMenu.style.transform = 'translateX(-100%)';
            }
            
            // Animate hamburger menu
            const bars = navToggle.querySelectorAll('.bar');
            console.log('Bars found:', bars.length);
            bars.forEach((bar, index) => {
                if (navMenu.classList.contains('active')) {
                    if (index === 0) bar.style.transform = 'rotate(45deg) translate(5px, 5px)';
                    if (index === 1) bar.style.opacity = '0';
                    if (index === 2) bar.style.transform = 'rotate(-45deg) translate(7px, -6px)';
                } else {
                    bar.style.transform = '';
                    bar.style.opacity = '';
                }
            });
        });
        
        // Add multiple touch event handlers for iOS
        navToggle.addEventListener('touchstart', function(e) {
            navToggle.style.background = '#f0f0f0';
            e.preventDefault();
            e.stopPropagation();
        });
        
        navToggle.addEventListener('touchend', function(e) {
            navToggle.style.background = '#ffffff';
            e.preventDefault();
            e.stopPropagation();
            
            // Manually trigger the click logic
            setTimeout(() => {
                const wasActive = navMenu.classList.contains('active');
                navMenu.classList.toggle('active');
                const nowActive = navMenu.classList.contains('active');
                
                if (navMenu.classList.contains('active')) {
                    navMenu.style.cssText = `
                        position: fixed !important;
                        left: 0px !important;
                        top: 92px !important;
                        width: 100% !important;
                        height: calc(100vh - 92px) !important;
                        background: white !important;
                        z-index: 9998 !important;
                        display: flex !important;
                        flex-direction: column !important;
                        visibility: visible !important;
                        opacity: 1 !important;
                        transform: translateX(0) !important;
                    `;
                } else {
                    navMenu.style.left = '-100%';
                    navMenu.style.transform = 'translateX(-100%)';
                }
                
                // Animate hamburger bars
                const bars = navToggle.querySelectorAll('.bar');
                bars.forEach((bar, index) => {
                    if (navMenu.classList.contains('active')) {
                        if (index === 0) bar.style.transform = 'rotate(45deg) translate(5px, 5px)';
                        if (index === 1) bar.style.opacity = '0';
                        if (index === 2) bar.style.transform = 'rotate(-45deg) translate(7px, -6px)';
                    } else {
                        bar.style.transform = '';
                        bar.style.opacity = '';
                    }
                });
            }, 50);
        });
    }

    // Navigation links - Let browser handle completely naturally
    console.log('Navigation enabled - pure HTML link behavior');
    
    // Newsletter subscription handling
    const newsletterSubmit = document.getElementById('newsletter-submit');
    const newsletterEmail = document.getElementById('newsletter-email');
    const newsletterMessage = document.getElementById('newsletter-message');
    
    if (newsletterSubmit && newsletterEmail && newsletterMessage) {
        newsletterSubmit.addEventListener('click', function() {
            const email = newsletterEmail.value.trim();
            
            if (!email) {
                showNewsletterMessage('Please enter your email address', 'error');
                return;
            }
            
            if (!isValidEmail(email)) {
                showNewsletterMessage('Please enter a valid email address', 'error');
                return;
            }
            
            // Disable button and show loading
            newsletterSubmit.disabled = true;
            newsletterSubmit.textContent = 'Subscribing...';
            
            // Send subscription request
            fetch('/newsletter-subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNewsletterMessage(data.message, 'success');
                    newsletterEmail.value = '';
                } else {
                    showNewsletterMessage(data.message, 'error');
                }
            })
            .catch(error => {
                showNewsletterMessage('There was an error. Please try again.', 'error');
                console.error('Newsletter subscription error:', error);
            })
            .finally(() => {
                // Re-enable button
                newsletterSubmit.disabled = false;
                newsletterSubmit.textContent = 'Subscribe';
            });
        });
        
        // Allow Enter key to submit
        newsletterEmail.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                newsletterSubmit.click();
            }
        });
    }
    
    function showNewsletterMessage(message, type) {
        newsletterMessage.textContent = message;
        newsletterMessage.style.display = 'block';
        newsletterMessage.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
        newsletterMessage.style.color = type === 'success' ? '#155724' : '#721c24';
        newsletterMessage.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
        
        // Hide message after 5 seconds
        setTimeout(() => {
            newsletterMessage.style.display = 'none';
        }, 5000);
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Header background effect only
    if (header) {
        let lastScrollTop = 0;
        let headerTicking = false;
        
        function updateHeaderOnScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            } else {
                header.style.background = 'var(--bg-white)';
                header.style.backdropFilter = 'none';
            }
            
            lastScrollTop = scrollTop;
            headerTicking = false;
        }
        
        function requestHeaderUpdate() {
            if (!headerTicking) {
                requestAnimationFrame(updateHeaderOnScroll);
                headerTicking = true;
            }
        }
        
        window.addEventListener('scroll', requestHeaderUpdate, { passive: true });
    }

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');

    function performSearch() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            // For now, we'll scroll to courses section
            // Later this can be expanded to actual search functionality
            const coursesSection = document.getElementById('courses');
            if (coursesSection) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = coursesSection.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
            
            // Show search feedback
            showNotification(`Searching for: "${searchTerm}"`, 'info');
        }
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', performSearch);
    }
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }

    // Newsletter subscription
    const newsletterForm = document.querySelector('.newsletter-form');
    const newsletterInput = document.querySelector('.newsletter-input');
    const newsletterBtn = document.querySelector('.newsletter-btn');

    if (newsletterBtn && newsletterInput) {
        newsletterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const email = newsletterInput.value.trim();
        
        if (email && isValidEmail(email)) {
            // Simulate subscription (replace with actual API call)
            newsletterBtn.textContent = 'Subscribing...';
            newsletterBtn.disabled = true;
            
            setTimeout(() => {
                showNotification('Thank you for subscribing!', 'success');
                newsletterInput.value = '';
                newsletterBtn.textContent = 'Subscribe';
                newsletterBtn.disabled = false;
            }, 1500);
        } else {
            showNotification('Please enter a valid email address', 'error');
        }
    });
    }

    // Course card interactions - Allow natural link navigation
    // Real course pages now exist, so we don't need to prevent default navigation

    // News card interactions - Allow natural link navigation to real articles
    // Real news articles now exist, so we don't need to prevent default navigation

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe course and news cards for animations
    const animatedElements = document.querySelectorAll('.course-card, .news-card, .stat-item');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Counter animation for stats
    const statNumbers = document.querySelectorAll('.stat-number');
    
    function animateCounter(element, target, duration) {
        let start = 0;
        const increment = target / (duration / 16); // 60fps
        
        function updateCounter() {
            start += increment;
            if (start < target) {
                element.textContent = Math.floor(start) + '+';
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target + '+';
            }
        }
        
        updateCounter();
    }

    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                const numberText = entry.target.textContent.replace('+', '');
                const targetNumber = parseInt(numberText.replace(',', ''));
                
                if (!isNaN(targetNumber)) {
                    animateCounter(entry.target, targetNumber, 2000);
                }
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(stat => {
        statsObserver.observe(stat);
    });

    // Utility functions
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;

        // Style the notification
        notification.style.cssText = `
            position: fixed;
            top: 90px;
            right: 20px;
            background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            font-weight: 500;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;

        document.body.appendChild(notification);

        // Animate in
        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
        });

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
    }

    // Add loading states for buttons
    function addLoadingState(button, text = 'Loading...') {
        const originalText = button.textContent;
        button.textContent = text;
        button.disabled = true;
        button.style.opacity = '0.7';
        
        return function restore() {
            button.textContent = originalText;
            button.disabled = false;
            button.style.opacity = '';
        };
    }

    // Add hover effects for better UX
    const hoverElements = document.querySelectorAll('.course-card, .news-card, .btn-primary, .btn-secondary');
    hoverElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });

    // Add keyboard navigation support
    document.addEventListener('keydown', function(e) {
        // ESC key closes mobile menu
        if (e.key === 'Escape' && navMenu.classList.contains('active')) {
            navMenu.classList.remove('active');
            const bars = navToggle.querySelectorAll('.bar');
            bars.forEach(bar => {
                bar.style.transform = '';
                bar.style.opacity = '';
            });
        }
    });

    // Performance optimization: Debounce scroll events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Lazy loading for images (basic implementation)
    const images = document.querySelectorAll('img');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            }
        });
    });

    images.forEach(img => {
        if (img.dataset.src) {
            imageObserver.observe(img);
        }
    });

    console.log('Tennessee Golf Courses website loaded successfully!');
});

// Add some additional utility functions for future use
window.TennesseeGolf = {
    // Function to filter courses (for future implementation)
    filterCourses: function(criteria) {
        console.log('Filtering courses by:', criteria);
        // Implementation coming soon
    },
    
    // Function to load course details (for future implementation)
    loadCourseDetails: function(courseId) {
        console.log('Loading course details for:', courseId);
        // Implementation coming soon
    },
    
    // Function to submit review (for future implementation)
    submitReview: function(courseId, review) {
        console.log('Submitting review for course:', courseId, review);
        // Implementation coming soon
    }
};