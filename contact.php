<?php
session_start();

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $inquiry_type = $_POST['inquiry_type'];
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($inquiry_type)) {
        $errors[] = "Please select an inquiry type";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    if (empty($errors)) {
        // Send email
        $to = "info@tennesseegolfcourses.com";
        $email_subject = "[$inquiry_type] $subject";
        
        $email_message = "
New contact form submission from Tennessee Golf Courses website:

Inquiry Type: $inquiry_type
Name: $name
Email: $email
Subject: $subject

Message:
$message

---
Submitted from: " . $_SERVER['HTTP_HOST'] . "
IP Address: " . $_SERVER['REMOTE_ADDR'] . "
User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "
Time: " . date('Y-m-d H:i:s');

        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $email_subject, $email_message, $headers)) {
            $success_message = "Thank you for your message! We'll get back to you within 24 hours.";
            // Clear form data after successful submission
            $_POST = array();
        } else {
            $error_message = "Sorry, there was an error sending your message. Please try again.";
        }
    } else {
        $error_message = implode(", ", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Tennessee Golf Courses</title>
    <meta name="description" content="Get in touch with Tennessee Golf Courses. Contact us about course suggestions, partnerships, technical issues, media inquiries, or general questions.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logos/tab-logo.png?v=2">
    <link rel="shortcut icon" href="/images/logos/tab-logo.png?v=2">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .contact-page {
            padding-top: 140px;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .contact-hero {
            text-align: center;
            padding: 60px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            margin-bottom: 80px;
            margin-top: -140px;
        }
        
        .contact-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .contact-hero p {
            font-size: 1.3rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .contact-form-section {
            background: var(--bg-white);
            padding: 40px;
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(6, 78, 59, 0.1);
        }
        
        .contact-info-section {
            padding: 40px;
        }
        
        .section-title {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, 0.1);
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            padding: 16px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-medium);
        }
        
        .submit-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-heavy);
        }
        
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 500;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .contact-method {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding: 20px;
            background: var(--bg-white);
            border-radius: 12px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(6, 78, 59, 0.1);
        }
        
        .contact-method-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 1.2rem;
        }
        
        .contact-method-info h4 {
            margin: 0 0 4px 0;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .contact-method-info p {
            margin: 0;
            color: var(--text-gray);
        }
        
        .faq-section {
            margin-top: 60px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }
        
        .faq-item {
            background: var(--bg-white);
            border-radius: 12px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(6, 78, 59, 0.1);
        }
        
        .faq-question {
            padding: 24px;
            font-weight: 600;
            color: var(--primary-color);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .faq-answer {
            padding: 0 24px 24px;
            color: var(--text-gray);
            line-height: 1.6;
        }
        
        .response-time {
            background: var(--bg-light);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-top: 32px;
            border: 2px solid var(--primary-color);
        }
        
        .response-time h4 {
            color: var(--primary-color);
            margin: 0 0 8px 0;
        }
        
        .response-time p {
            margin: 0;
            color: var(--text-gray);
        }
        
        @media (max-width: 768px) {
            .contact-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .contact-hero h1 {
                font-size: 2rem;
            }
            
            .contact-form-section,
            .contact-info-section {
                padding: 24px;
            }
            
            .contact-method {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <div class="contact-page">
        <!-- Contact Hero -->
        <section class="contact-hero">
            <div class="container">
                <h1>Get In Touch</h1>
                <p>We'd love to hear from you! Whether you have questions, suggestions, or want to partner with us, we're here to help.</p>
            </div>
        </section>

        <!-- Contact Content -->
        <section class="contact-content">
            <!-- Contact Form -->
            <div class="contact-form-section">
                <h2 class="section-title">Send Us a Message</h2>
                
                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="inquiry_type">Inquiry Type *</label>
                        <select id="inquiry_type" name="inquiry_type" required>
                            <option value="">Select an option...</option>
                            <option value="General" <?php echo (isset($_POST['inquiry_type']) && $_POST['inquiry_type'] === 'General') ? 'selected' : ''; ?>>General</option>
                            <option value="Course Suggestion" <?php echo (isset($_POST['inquiry_type']) && $_POST['inquiry_type'] === 'Course Suggestion') ? 'selected' : ''; ?>>Course Suggestion</option>
                            <option value="Partnership" <?php echo (isset($_POST['inquiry_type']) && $_POST['inquiry_type'] === 'Partnership') ? 'selected' : ''; ?>>Partnership</option>
                            <option value="Technical Issue" <?php echo (isset($_POST['inquiry_type']) && $_POST['inquiry_type'] === 'Technical Issue') ? 'selected' : ''; ?>>Technical Issue</option>
                            <option value="Media Inquiry" <?php echo (isset($_POST['inquiry_type']) && $_POST['inquiry_type'] === 'Media Inquiry') ? 'selected' : ''; ?>>Media Inquiry</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" required value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" required placeholder="Tell us more about your inquiry..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane" style="margin-right: 8px;"></i>
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="contact-info-section">
                <h2 class="section-title">Contact Information</h2>
                
                <div class="contact-method">
                    <div class="contact-method-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-method-info">
                        <h4>Email Us</h4>
                        <p>info@tennesseegolfcourses.com</p>
                    </div>
                </div>

                <div class="contact-method">
                    <div class="contact-method-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-method-info">
                        <h4>Call Us</h4>
                        <p>(615) 555-GOLF</p>
                    </div>
                </div>

                <div class="contact-method">
                    <div class="contact-method-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-method-info">
                        <h4>Location</h4>
                        <p>Nashville, Tennessee</p>
                    </div>
                </div>

                <div class="response-time">
                    <h4><i class="fas fa-clock"></i> Response Time</h4>
                    <p>We respond to all inquiries within 24 hours</p>
                </div>

                <div style="margin-top: 40px;">
                    <h3 style="color: var(--primary-color); margin-bottom: 16px;">What We Do</h3>
                    <p style="color: var(--text-gray); line-height: 1.6;">
                        Tennessee Golf Courses is your premier directory and review platform for discovering the best golf courses across the Volunteer State. We help golfers find their next great round and assist courses in reaching more players.
                    </p>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section">
            <h2 class="section-title" style="text-align: center; margin-bottom: 40px;">Frequently Asked Questions</h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    How do I submit a course review?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Simply create a free account, visit any course page, and leave your rating and review. Your feedback helps other golfers discover great courses!
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    How do golf courses get added to the site?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    We carefully research and add courses across Tennessee. You can suggest a course using our contact form, and we'll review it for inclusion based on our quality standards.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    Can I suggest a course that's not listed?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Absolutely! Use the "Course Suggestion" option in our contact form above. Include the course name, location, and any details you think would be helpful.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    How do I report a technical issue?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Select "Technical Issue" in the contact form and describe what you're experiencing. Include your browser, device, and any error messages you see.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    Do you offer partnership opportunities?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Yes! We're always interested in partnerships with golf courses, tourism boards, and golf-related businesses. Choose "Partnership" in the contact form to discuss opportunities.
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer" style="margin-top: 80px;">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="/images/logos/logo.png" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/courses">Golf Courses</a></li>
                        <li><a href="/reviews">Reviews</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/about">About Us</a></li>
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

    <script src="/weather.js?v=3"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>