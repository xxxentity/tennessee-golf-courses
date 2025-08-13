<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Tennessee Golf Courses</title>
    <meta name="description" content="Privacy Policy for Tennessee Golf Courses - Learn how we collect, use, and protect your personal information on our golf course review platform.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logos/tab-logo.webp?v=2">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=2">
    
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
        <!-- Privacy Policy Hero -->
        <section class="legal-hero">
            <div class="container">
                <h1>Privacy Policy</h1>
                <p>Your privacy is important to us. Learn how we collect, use, and protect your information.</p>
            </div>
        </section>

        <!-- Privacy Policy Content -->
        <div class="legal-content">
            <div class="legal-section">
                <h2>Information We Collect</h2>
                
                <h3>Personal Information</h3>
                <p>When you create an account or interact with our website, we may collect:</p>
                <ul>
                    <li>Name and email address</li>
                    <li>Profile information you choose to provide</li>
                    <li>Golf course reviews and ratings you submit</li>
                    <li>Comments and feedback you share</li>
                    <li>Communication preferences</li>
                </ul>
                
                <h3>Automatically Collected Information</h3>
                <p>We automatically collect certain information when you visit our website:</p>
                <ul>
                    <li>IP address and browser information</li>
                    <li>Pages visited and time spent on our site</li>
                    <li>Device type and operating system</li>
                    <li>Referral source (how you found our website)</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide and improve our golf course review platform</li>
                    <li>Process and display your reviews and ratings</li>
                    <li>Send you updates about new courses and features (if subscribed)</li>
                    <li>Respond to your questions and provide customer support</li>
                    <li>Prevent fraud and ensure platform security</li>
                    <li>Analyze website usage to improve user experience</li>
                    <li>Comply with legal obligations</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>Information Sharing</h2>
                <p>We respect your privacy and do not sell your personal information. We may share information in these limited circumstances:</p>
                <ul>
                    <li><strong>Public Reviews:</strong> Reviews and ratings you submit are displayed publicly with your chosen username</li>
                    <li><strong>Service Providers:</strong> We may use third-party services for hosting, analytics, and email delivery</li>
                    <li><strong>Legal Requirements:</strong> We may disclose information if required by law or to protect our rights</li>
                    <li><strong>Business Transfers:</strong> Information may be transferred if our business is acquired or merged</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>Cookies and Tracking</h2>
                <p>We use cookies and similar technologies to:</p>
                <ul>
                    <li>Remember your login status and preferences</li>
                    <li>Analyze website traffic and user behavior</li>
                    <li>Improve website functionality and performance</li>
                    <li>Provide personalized content recommendations</li>
                </ul>
                <p>You can control cookies through your browser settings, but some features may not work properly if you disable them.</p>
            </div>

            <div class="legal-section">
                <h2>Data Security</h2>
                <p>We implement appropriate security measures to protect your personal information, including:</p>
                <ul>
                    <li>Secure data transmission using SSL encryption</li>
                    <li>Regular security updates and monitoring</li>
                    <li>Access controls and authentication requirements</li>
                    <li>Regular data backups and recovery procedures</li>
                </ul>
                <p>While we strive to protect your information, no method of transmission over the internet is 100% secure.</p>
            </div>

            <div class="legal-section">
                <h2>Your Rights and Choices</h2>
                <p>You have the following rights regarding your personal information:</p>
                <ul>
                    <li><strong>Access:</strong> Request copies of your personal information</li>
                    <li><strong>Correction:</strong> Update or correct inaccurate information</li>
                    <li><strong>Deletion:</strong> Request deletion of your account and associated data</li>
                    <li><strong>Newsletter:</strong> Unsubscribe from email communications at any time</li>
                    <li><strong>Review Management:</strong> Edit or delete reviews you've submitted</li>
                </ul>
                <p>To exercise these rights, please contact us using the information below.</p>
            </div>

            <div class="legal-section">
                <h2>Children's Privacy</h2>
                <p>Our website is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and believe your child has provided us with personal information, please contact us immediately.</p>
            </div>

            <div class="legal-section">
                <h2>Changes to This Privacy Policy</h2>
                <p>We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. We will notify users of significant changes by:</p>
                <ul>
                    <li>Posting the updated policy on our website</li>
                    <li>Updating the "Last Updated" date</li>
                    <li>Sending email notifications for material changes (if you're subscribed)</li>
                </ul>
                <p>Your continued use of our website after changes indicates your acceptance of the updated policy.</p>
            </div>

            <div class="contact-info">
                <h3>Contact Us</h3>
                <p>If you have questions about this Privacy Policy or want to exercise your rights, please contact us:</p>
                <p>
                    <i class="fas fa-envelope" style="margin-right: 8px;"></i>
                    <a href="mailto:privacy@tennesseegolfcourses.com">privacy@tennesseegolfcourses.com</a>
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
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
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