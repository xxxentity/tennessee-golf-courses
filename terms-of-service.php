<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Tennessee Golf Courses</title>
    <meta name="description" content="Terms of Service for Tennessee Golf Courses - Review platform guidelines, user responsibilities, and terms for using our golf course review service.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php include 'includes/favicon.php'; ?>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .legal-page {
            padding-top: 140px;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .legal-hero {
            text-align: center;
            padding: 60px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            margin-bottom: 80px;
            margin-top: -140px;
        }
        
        .legal-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .legal-hero p {
            font-size: 1.3rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .legal-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px 80px;
        }
        
        .legal-section {
            background: var(--bg-white);
            padding: 40px;
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(6, 78, 59, 0.1);
            margin-bottom: 40px;
        }
        
        .legal-section h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.5rem;
        }
        
        .legal-section h3 {
            color: var(--primary-color);
            font-size: 1.3rem;
            margin: 2rem 0 1rem 0;
            font-weight: 600;
        }
        
        .legal-section p {
            color: var(--text-gray);
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }
        
        .legal-section ul {
            color: var(--text-gray);
            line-height: 1.8;
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }
        
        .legal-section li {
            margin-bottom: 0.5rem;
        }
        
        .contact-info {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            padding: 32px;
            border-radius: 12px;
            text-align: center;
            margin-top: 40px;
        }
        
        .contact-info h3 {
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .contact-info p {
            opacity: 0.9;
            margin-bottom: 1rem;
        }
        
        .contact-info a {
            color: var(--text-light);
            text-decoration: underline;
        }
        
        .last-updated {
            text-align: center;
            color: var(--text-gray);
            font-style: italic;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(6, 78, 59, 0.1);
        }
        
        @media (max-width: 768px) {
            .legal-hero h1 {
                font-size: 2rem;
            }
            
            .legal-section {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <div class="legal-page">
        <!-- Terms of Service Hero -->
        <section class="legal-hero">
            <div class="container">
                <h1>Terms of Service</h1>
                <p>Guidelines and terms for using our golf course review platform</p>
            </div>
        </section>

        <!-- Terms of Service Content -->
        <div class="legal-content">
            <div class="legal-section">
                <h2>Acceptance of Terms</h2>
                <p>By accessing and using Tennessee Golf Courses ("we," "our," or "us"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                <p>These Terms of Service may be updated from time to time. Your continued use of the platform constitutes acceptance of any changes.</p>
            </div>

            <div class="legal-section">
                <h2>Description of Service</h2>
                <p>Tennessee Golf Courses is a platform that provides information about golf courses in Tennessee, user-generated reviews and ratings, golf course discovery and comparison tools, community features for golf enthusiasts, and news and updates about Tennessee golf.</p>
                <p>Our service is provided "as is" and we reserve the right to modify or discontinue features at any time.</p>
            </div>

            <div class="legal-section">
                <h2>User Accounts and Registration</h2>
                
                <h3>Account Creation</h3>
                <p>You must provide accurate and complete information when creating an account, maintain the security of your account credentials, be at least 13 years old to create an account, and one person may not maintain multiple accounts.</p>
                
                <h3>Account Responsibilities</h3>
                <p>You are responsible for all activities that occur under your account and must notify us immediately of any unauthorized use of your account. We are not liable for any loss or damage from unauthorized account access.</p>
            </div>

            <div class="legal-section">
                <h2>User Content and Reviews</h2>
                
                <h3>Content Guidelines</h3>
                <p>When submitting reviews, comments, or other content, you agree to provide honest, accurate, and helpful information, base reviews on your actual experience at the golf course, respect others and maintain a civil tone, avoid inappropriate, offensive, or illegal content, not submit fake or misleading reviews, and not include personal information of others without consent.</p>
                
                <h3>Prohibited Content</h3>
                <p>The following types of content are strictly prohibited: hate speech, discrimination, or harassment; spam, advertising, or promotional content; false or misleading information; copyrighted material without permission; personal attacks on individuals or businesses; and content that violates any applicable laws.</p>
                
                <h3>Content Ownership and Rights</h3>
                <p>You retain ownership of content you submit but grant us a non-exclusive license to use, display, and distribute your content. We may edit or remove content that violates these terms, and you represent that you have the right to submit the content.</p>
            </div>

            <div class="legal-section">
                <h2>Content Moderation</h2>
                <p>We reserve the right to monitor and review user-generated content, remove content that violates our guidelines, suspend or terminate accounts for policy violations, and report illegal activity to appropriate authorities.</p>
                <p>We are not obligated to monitor content but may do so to maintain platform quality and safety.</p>
            </div>

            <div class="legal-section">
                <h2>Acceptable Use</h2>
                
                <h3>Permitted Uses</h3>
                <p>You may browse and search golf course information, submit genuine reviews based on your experiences, share the platform with other golf enthusiasts, and use the platform for personal, non-commercial purposes.</p>
                
                <h3>Prohibited Uses</h3>
                <p>You may not attempt to hack, compromise, or damage the platform; use automated tools to scrape or download content; impersonate others or create fake accounts; interfere with other users' access to the platform; or use the platform for commercial purposes without permission.</p>
            </div>

            <div class="legal-section">
                <h2>Intellectual Property</h2>
                <p>The Tennessee Golf Courses platform, including its design, logos, text, graphics, and functionality, is protected by copyright and other intellectual property laws. You may not copy, reproduce, or distribute our content without permission, and our trademarks and logos may not be used without written consent. User-generated content remains the property of its creators, and golf course information may be subject to third-party rights.</p>
            </div>

            <div class="legal-section">
                <h2>Privacy and Data Protection</h2>
                <p>Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these Terms of Service.</p>
                <p>By using our platform, you consent to the collection and use of information as described in our Privacy Policy.</p>
            </div>

            <div class="legal-section">
                <h2>Disclaimers and Limitations</h2>
                
                <h3>Service Availability</h3>
                <p>We strive for 24/7 availability but cannot guarantee uninterrupted service. Planned maintenance may temporarily limit access, and we are not responsible for technical issues beyond our control.</p>
                
                <h3>Information Accuracy</h3>
                <p>Golf course information is provided for convenience and may not be current. User reviews represent individual opinions and experiences. We recommend verifying information directly with golf courses, and we are not responsible for decisions based on platform information.</p>
                
                <h3>Limitation of Liability</h3>
                <p>To the fullest extent permitted by law, Tennessee Golf Courses shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of the platform.</p>
            </div>

            <div class="legal-section">
                <h2>Account Termination</h2>
                
                <h3>Termination by You</h3>
                <p>You may delete your account at any time through your account settings. Account deletion will remove your personal information but may retain anonymized reviews, and some data may be retained for legal or security purposes.</p>
                
                <h3>Termination by Us</h3>
                <p>We may suspend or terminate your account if you violate these Terms of Service or our community guidelines, engage in fraudulent or illegal activity, abuse the platform or other users, or fail to respond to communications regarding policy violations.</p>
            </div>

            <div class="legal-section">
                <h2>Governing Law</h2>
                <p>These Terms of Service are governed by the laws of the State of Tennessee, without regard to conflict of law principles. Any disputes arising from these terms or your use of the platform shall be resolved in the courts of Tennessee.</p>
            </div>

            <div class="legal-section">
                <h2>Changes to Terms</h2>
                <p>We reserve the right to modify these Terms of Service at any time. We will notify users of material changes by posting updated terms on our website, updating the "Last Updated" date, and sending email notifications for significant changes (if subscribed).</p>
                <p>Your continued use of the platform after changes constitutes acceptance of the new terms.</p>
            </div>

            <div class="contact-info">
                <h3>Contact Us</h3>
                <p>If you have questions about these Terms of Service, please contact us:</p>
                <p>
                    <i class="fas fa-envelope" style="margin-right: 8px;"></i>
                    <a href="mailto:legal@tennesseegolfcourses.com">legal@tennesseegolfcourses.com</a>
                </p>
                <p>
                    <i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>
                    Tennessee Golf Courses<br>
                    Nashville, TN
                </p>
            </div>

            <div class="last-updated">
                <p>Last Updated: July 24, 2025</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="/images/logos/logo.webp" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61579553544749" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        <a href="https://x.com/TNGolfCourses" target="_blank" rel="noopener noreferrer"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/tennesseegolfcourses/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@TennesseeGolf" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/courses">Courses</a></li>
                        <li><a href="/reviews">Reviews</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/events">Events</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="/courses?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="/courses?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="/courses?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="/courses?region=Memphis Area">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-of-service">Terms of Service</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>