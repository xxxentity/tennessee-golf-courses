<!-- Cookie Consent Banner -->
<style>
    .cookie-consent-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #2c5234, #4a7c59);
        color: white;
        padding: 1.5rem;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
        z-index: 9999;
        display: none;
        animation: slideUp 0.5s ease-out;
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }
    
    .cookie-consent-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    .cookie-consent-text {
        flex: 1;
        min-width: 300px;
    }
    
    .cookie-consent-text h3 {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .cookie-consent-text p {
        font-size: 0.95rem;
        opacity: 0.95;
        line-height: 1.5;
        margin: 0;
    }
    
    .cookie-consent-text a {
        color: #ffd700;
        text-decoration: underline;
        transition: opacity 0.3s ease;
    }
    
    .cookie-consent-text a:hover {
        opacity: 0.8;
    }
    
    .cookie-consent-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .cookie-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .cookie-btn-accept {
        background: #ffd700;
        color: #2c5234;
    }
    
    .cookie-btn-accept:hover {
        background: #ffed4e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255,215,0,0.3);
    }
    
    .cookie-btn-decline {
        background: transparent;
        color: white;
        border: 2px solid white;
    }
    
    .cookie-btn-decline:hover {
        background: white;
        color: #2c5234;
    }
    
    .cookie-btn-settings {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
    }
    
    .cookie-btn-settings:hover {
        background: rgba(255,255,255,0.3);
    }
    
    /* Cookie Settings Modal */
    .cookie-settings-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: none;
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }
    
    .cookie-settings-content {
        background: white;
        border-radius: 15px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        padding: 2rem;
    }
    
    .cookie-settings-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .cookie-settings-header h2 {
        color: #2c5234;
        margin: 0 0 0.5rem 0;
    }
    
    .cookie-settings-header p {
        color: #666;
        margin: 0;
    }
    
    .cookie-category {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .cookie-category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .cookie-category h3 {
        color: #2c5234;
        margin: 0;
        font-size: 1.1rem;
    }
    
    .cookie-category p {
        color: #666;
        margin: 0.5rem 0 0 0;
        font-size: 0.9rem;
    }
    
    .cookie-switch {
        position: relative;
        width: 50px;
        height: 26px;
    }
    
    .cookie-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .cookie-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 26px;
    }
    
    .cookie-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    .cookie-switch input:checked + .cookie-slider {
        background-color: #4a7c59;
    }
    
    .cookie-switch input:checked + .cookie-slider:before {
        transform: translateX(24px);
    }
    
    .cookie-switch input:disabled + .cookie-slider {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .cookie-settings-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f0f0f0;
    }
    
    @media (max-width: 768px) {
        .cookie-consent-container {
            flex-direction: column;
            text-align: center;
        }
        
        .cookie-consent-buttons {
            width: 100%;
            justify-content: center;
        }
        
        .cookie-btn {
            flex: 1;
            min-width: 120px;
        }
    }
</style>

<div class="cookie-consent-banner" id="cookieConsent">
    <div class="cookie-consent-container">
        <div class="cookie-consent-text">
            <h3>🍪 We use cookies</h3>
            <p>We use cookies to enhance your browsing experience and analyze our traffic. By clicking "Accept All", you consent to our use of cookies. Read our <a href="/privacy-policy.php" target="_blank">Privacy Policy</a> for more information.</p>
        </div>
        <div class="cookie-consent-buttons">
            <button class="cookie-btn cookie-btn-settings" onclick="openCookieSettings()">Cookie Settings</button>
            <button class="cookie-btn cookie-btn-decline" onclick="declineCookies()">Decline</button>
            <button class="cookie-btn cookie-btn-accept" onclick="acceptAllCookies()">Accept All</button>
        </div>
    </div>
</div>

<!-- Cookie Settings Modal -->
<div class="cookie-settings-modal" id="cookieSettingsModal">
    <div class="cookie-settings-content">
        <div class="cookie-settings-header">
            <h2>Cookie Settings</h2>
            <p>Manage your cookie preferences for Tennessee Golf Courses</p>
        </div>
        
        <div class="cookie-category">
            <div class="cookie-category-header">
                <h3>Essential Cookies</h3>
                <label class="cookie-switch">
                    <input type="checkbox" checked disabled>
                    <span class="cookie-slider"></span>
                </label>
            </div>
            <p>These cookies are necessary for the website to function properly. They enable basic functions like page navigation and access to secure areas.</p>
        </div>
        
        <div class="cookie-category">
            <div class="cookie-category-header">
                <h3>Analytics Cookies</h3>
                <label class="cookie-switch">
                    <input type="checkbox" id="analyticsCookies" checked>
                    <span class="cookie-slider"></span>
                </label>
            </div>
            <p>These cookies help us understand how visitors interact with our website. We use Google Analytics to collect anonymous information about page views and site performance.</p>
        </div>
        
        <div class="cookie-category">
            <div class="cookie-category-header">
                <h3>Functional Cookies</h3>
                <label class="cookie-switch">
                    <input type="checkbox" id="functionalCookies" checked>
                    <span class="cookie-slider"></span>
                </label>
            </div>
            <p>These cookies enable enhanced functionality and personalization, such as remembering your preferences and login status.</p>
        </div>
        
        <div class="cookie-settings-footer">
            <button class="cookie-btn cookie-btn-decline" onclick="closeCookieSettings()">Cancel</button>
            <button class="cookie-btn cookie-btn-accept" onclick="saveCustomCookies()">Save Preferences</button>
        </div>
    </div>
</div>

<script>
    // Cookie consent management
    const COOKIE_CONSENT_KEY = 'tn_golf_cookie_consent';
    const COOKIE_PREFERENCES_KEY = 'tn_golf_cookie_preferences';
    const COOKIE_DURATION = 365; // days
    
    // Check if consent has been given
    function checkCookieConsent() {
        const consent = getCookie(COOKIE_CONSENT_KEY);
        if (!consent) {
            document.getElementById('cookieConsent').style.display = 'block';
        } else if (consent === 'declined') {
            // Remove Google Analytics if declined
            disableGoogleAnalytics();
        }
    }
    
    // Accept all cookies
    function acceptAllCookies() {
        setCookie(COOKIE_CONSENT_KEY, 'accepted', COOKIE_DURATION);
        setCookie(COOKIE_PREFERENCES_KEY, JSON.stringify({
            essential: true,
            analytics: true,
            functional: true
        }), COOKIE_DURATION);
        document.getElementById('cookieConsent').style.display = 'none';
        
        // Reload to apply Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'update', {
                'analytics_storage': 'granted'
            });
        }
    }
    
    // Decline cookies
    function declineCookies() {
        setCookie(COOKIE_CONSENT_KEY, 'declined', COOKIE_DURATION);
        setCookie(COOKIE_PREFERENCES_KEY, JSON.stringify({
            essential: true,
            analytics: false,
            functional: false
        }), COOKIE_DURATION);
        document.getElementById('cookieConsent').style.display = 'none';
        disableGoogleAnalytics();
    }
    
    // Open cookie settings
    function openCookieSettings() {
        document.getElementById('cookieSettingsModal').style.display = 'flex';
        
        // Load saved preferences
        const prefs = getCookie(COOKIE_PREFERENCES_KEY);
        if (prefs) {
            const preferences = JSON.parse(prefs);
            document.getElementById('analyticsCookies').checked = preferences.analytics !== false;
            document.getElementById('functionalCookies').checked = preferences.functional !== false;
        }
    }
    
    // Close cookie settings
    function closeCookieSettings() {
        document.getElementById('cookieSettingsModal').style.display = 'none';
    }
    
    // Save custom cookie preferences
    function saveCustomCookies() {
        const preferences = {
            essential: true,
            analytics: document.getElementById('analyticsCookies').checked,
            functional: document.getElementById('functionalCookies').checked
        };
        
        setCookie(COOKIE_CONSENT_KEY, 'custom', COOKIE_DURATION);
        setCookie(COOKIE_PREFERENCES_KEY, JSON.stringify(preferences), COOKIE_DURATION);
        
        document.getElementById('cookieConsent').style.display = 'none';
        document.getElementById('cookieSettingsModal').style.display = 'none';
        
        // Apply preferences
        if (preferences.analytics) {
            if (typeof gtag !== 'undefined') {
                gtag('consent', 'update', {
                    'analytics_storage': 'granted'
                });
            }
        } else {
            disableGoogleAnalytics();
        }
    }
    
    // Disable Google Analytics
    function disableGoogleAnalytics() {
        // Set Google Analytics to not track
        window['ga-disable-G-7VPNPCDTBP'] = true;
        
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'update', {
                'analytics_storage': 'denied'
            });
        }
        
        // Remove GA cookies
        deleteCookie('_ga');
        deleteCookie('_gid');
        deleteCookie('_gat');
        deleteCookie('_ga_7VPNPCDTBP');
    }
    
    // Cookie utility functions
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/;SameSite=Lax";
    }
    
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    
    function deleteCookie(name) {
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/;';
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/;domain=' + window.location.hostname + ';';
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/;domain=.' + window.location.hostname + ';';
    }
    
    // Close modal when clicking outside
    document.getElementById('cookieSettingsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCookieSettings();
        }
    });
    
    // Initialize Google Analytics consent mode
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    
    // Set default consent state
    const consent = getCookie(COOKIE_CONSENT_KEY);
    if (!consent) {
        // Default to denied until user makes a choice
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'default', {
                'analytics_storage': 'denied'
            });
        }
    } else if (consent === 'declined') {
        disableGoogleAnalytics();
    } else {
        const prefs = getCookie(COOKIE_PREFERENCES_KEY);
        if (prefs) {
            const preferences = JSON.parse(prefs);
            if (!preferences.analytics) {
                disableGoogleAnalytics();
            }
        }
    }
    
    // Check consent on page load
    document.addEventListener('DOMContentLoaded', checkCookieConsent);
</script>