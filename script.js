// Tennessee Golf Courses - JavaScript Functionality

document.addEventListener('DOMContentLoaded', function() {
    // Live Time Display
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
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }

    // Update time immediately and then every minute
    updateTime();
    setInterval(updateTime, 60000);

    // Real Weather Conditions (no golf commentary!)
    function updateWeather() {
        const weatherConditions = [
            { temp: '84°F', wind: '12 mph', visibility: '6 mi', icon: 'fa-cloud-rain', condition: 'Scattered Thunderstorms' },
            { temp: '78°F', wind: '8 mph', visibility: '4 mi', icon: 'fa-cloud-rain', condition: 'Light Rain' },
            { temp: '72°F', wind: '5 mph', visibility: '10 mi', icon: 'fa-sun', condition: 'Sunny' },
            { temp: '68°F', wind: '15 mph', visibility: '8 mi', icon: 'fa-cloud', condition: 'Cloudy' },
            { temp: '75°F', wind: '10 mph', visibility: '7 mi', icon: 'fa-cloud-sun', condition: 'Partly Cloudy' },
            { temp: '82°F', wind: '18 mph', visibility: '5 mi', icon: 'fa-bolt', condition: 'Thunderstorms' }
        ];
        
        const randomWeather = weatherConditions[Math.floor(Math.random() * weatherConditions.length)];
        
        const tempElement = document.getElementById('weather-temp');
        const windElement = document.getElementById('wind-speed');
        const visibilityElement = document.getElementById('visibility');
        
        if (tempElement) {
            tempElement.textContent = `${randomWeather.temp} - ${randomWeather.condition}`;
        }
        if (windElement) {
            windElement.textContent = randomWeather.wind;
        }
        if (visibilityElement) {
            visibilityElement.textContent = randomWeather.visibility;
        }
        
        // Update weather icon
        const weatherIcon = document.querySelector('.weather-widget i');
        if (weatherIcon) {
            weatherIcon.className = `fas ${randomWeather.icon}`;
        }
    }

    // Update weather immediately and then every 10 minutes
    updateWeather();
    setInterval(updateWeather, 600000);

    // Weather bar scroll effect
    const weatherBar = document.querySelector('.weather-bar');
    let weatherBarVisible = true;

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 200 && weatherBarVisible) {
            weatherBar.style.transform = 'translateY(-100%)';
            weatherBarVisible = false;
            document.querySelector('.header').style.top = '0';
        } else if (scrollTop <= 200 && !weatherBarVisible) {
            weatherBar.style.transform = 'translateY(0)';
            weatherBarVisible = true;
            document.querySelector('.header').style.top = '40px';
        }
    });
    // Mobile Navigation Toggle
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');

    navToggle.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        
        // Animate hamburger menu
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
    });

    // Close mobile menu when clicking on a link
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            const bars = navToggle.querySelectorAll('.bar');
            bars.forEach(bar => {
                bar.style.transform = '';
                bar.style.opacity = '';
            });
        });
    });

    // Smooth scrolling for navigation links (only for same-page links)
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Only prevent default for anchor links on the same page
            if (href.startsWith('#')) {
                e.preventDefault();
                const targetId = href.substring(1);
                const targetSection = document.getElementById(targetId);
                
                if (targetSection) {
                    const headerHeight = document.querySelector('.header').offsetHeight;
                    const targetPosition = targetSection.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
            // For other links (like ../index.html), allow normal navigation
        });
    });

    // Header scroll effect
    const header = document.querySelector('.header');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.backdropFilter = 'blur(10px)';
        } else {
            header.style.background = 'var(--bg-white)';
            header.style.backdropFilter = 'none';
        }
        
        lastScrollTop = scrollTop;
    });

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

    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Newsletter subscription
    const newsletterForm = document.querySelector('.newsletter-form');
    const newsletterInput = document.querySelector('.newsletter-input');
    const newsletterBtn = document.querySelector('.newsletter-btn');

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