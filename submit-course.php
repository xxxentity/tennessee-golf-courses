<?php
// Include security headers
require_once 'includes/security-headers.php';
require_once 'includes/csrf.php';
require_once 'includes/input-validation.php';
require_once 'includes/rate-limiter.php';

// Set security headers
SecurityHeaders::set();

header('Content-Type: application/json');

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Rate limiting - allow max 3 submissions per hour per IP
$rateLimiter = new RateLimiter();
if (!$rateLimiter->checkRate($_SERVER['REMOTE_ADDR'], 'course_submission', 3, 3600)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many submissions. Please try again later.']);
    exit;
}

try {
    // Get and validate input
    $courseName = InputValidation::sanitizeText($_POST['courseName'] ?? '');
    $courseLocation = InputValidation::sanitizeText($_POST['courseLocation'] ?? '');
    
    // Validate required fields
    if (empty($courseName) || empty($courseLocation)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Course name and location are required.']);
        exit;
    }
    
    // Additional validation
    if (strlen($courseName) < 3 || strlen($courseName) > 100) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Course name must be between 3 and 100 characters.']);
        exit;
    }
    
    if (strlen($courseLocation) < 3 || strlen($courseLocation) > 100) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Location must be between 3 and 100 characters.']);
        exit;
    }
    
    // Prepare email content
    $to = 'info@tennesseegolfcourses.com';
    $subject = 'Missing Course Submission - ' . $courseName;
    
    $message = "A new golf course has been submitted to Tennessee Golf Courses:\n\n";
    $message .= "Course Name: " . $courseName . "\n";
    $message .= "Location: " . $courseLocation . "\n\n";
    $message .= "Submitted on: " . date('F j, Y \a\t g:i A T') . "\n";
    $message .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $message .= "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "\n\n";
    $message .= "Please review and consider adding this course to the directory.\n\n";
    $message .= "---\n";
    $message .= "Tennessee Golf Courses Website\n";
    $message .= "https://tennesseegolfcourses.com";
    
    // Email headers
    $headers = [
        'From: noreply@tennesseegolfcourses.com',
        'Reply-To: noreply@tennesseegolfcourses.com',
        'X-Mailer: PHP/' . phpversion(),
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8'
    ];
    
    // Send email
    $success = mail($to, $subject, $message, implode("\r\n", $headers));
    
    if ($success) {
        // Log successful submission
        error_log("Course submission: $courseName in $courseLocation from IP " . $_SERVER['REMOTE_ADDR']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you! Your course submission has been sent successfully. We\'ll review it and add it to our directory.'
        ]);
    } else {
        // Log error
        error_log("Failed to send course submission email: $courseName in $courseLocation");
        
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Sorry, there was an error sending your submission. Please try again later.'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Course submission error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'An unexpected error occurred. Please try again later.'
    ]);
}
?>