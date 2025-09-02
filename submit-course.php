<?php
header('Content-Type: application/json');

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get and validate input (simple sanitization)
    $courseName = trim(strip_tags($_POST['courseName'] ?? ''));
    $courseLocation = trim(strip_tags($_POST['courseLocation'] ?? ''));
    
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
    
    // Check if mail function exists
    if (!function_exists('mail')) {
        error_log("PHP mail function not available");
        echo json_encode([
            'success' => false, 
            'message' => 'Email functionality not available on this server. Please contact us directly at info@tennesseegolfcourses.com'
        ]);
        exit;
    }
    
    // Send email
    $success = mail($to, $subject, $message, implode("\r\n", $headers));
    
    if ($success) {
        // Log successful submission
        error_log("Course submission SUCCESS: $courseName in $courseLocation from IP " . $_SERVER['REMOTE_ADDR']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you! Your course submission has been sent successfully. We\'ll review it and add it to our directory.'
        ]);
    } else {
        // Log error with more details
        $lastError = error_get_last();
        error_log("Course submission FAILED: $courseName in $courseLocation - " . ($lastError['message'] ?? 'Unknown error'));
        
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Sorry, there was an error sending your submission. Please contact us directly at info@tennesseegolfcourses.com'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Course submission EXCEPTION: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'An unexpected error occurred: ' . $e->getMessage()
    ]);
}

// Debug info (remove in production)
error_log("Course submission script accessed - Method: " . $_SERVER['REQUEST_METHOD'] . ", POST data: " . print_r($_POST, true));
?>