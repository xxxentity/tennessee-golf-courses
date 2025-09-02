<?php
// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /courses');
    exit;
}

// Set JSON response header
header('Content-Type: application/json');

// Get form data
$courseName = isset($_POST['courseName']) ? trim($_POST['courseName']) : '';
$courseLocation = isset($_POST['courseLocation']) ? trim($_POST['courseLocation']) : '';

// Validate input
if (empty($courseName) || empty($courseLocation)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// Sanitize input
$courseName = htmlspecialchars($courseName, ENT_QUOTES, 'UTF-8');
$courseLocation = htmlspecialchars($courseLocation, ENT_QUOTES, 'UTF-8');

// Email configuration
$to = 'info@tennesseegolfcourses.com';
$subject = 'Missing Course Report - Tennessee Golf Courses';
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: Tennessee Golf Courses <noreply@tennesseegolfcourses.com>' . "\r\n";

// Create email body
$message = '
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #064e3b; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f8f8; padding: 20px; margin-top: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #064e3b; }
        .value { margin-left: 10px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Missing Course Report</h2>
        </div>
        <div class="content">
            <p>A user has reported a missing golf course:</p>
            
            <div class="field">
                <span class="label">Course Name:</span>
                <span class="value">' . $courseName . '</span>
            </div>
            
            <div class="field">
                <span class="label">Location:</span>
                <span class="value">' . $courseLocation . '</span>
            </div>
            
            <div class="field">
                <span class="label">Submitted:</span>
                <span class="value">' . date('F j, Y g:i A') . '</span>
            </div>
            
            <div class="field">
                <span class="label">IP Address:</span>
                <span class="value">' . $_SERVER['REMOTE_ADDR'] . '</span>
            </div>
        </div>
        <div class="footer">
            <p>This report was submitted through the Tennessee Golf Courses website.</p>
        </div>
    </div>
</body>
</html>
';

// Send email
$mailSent = mail($to, $subject, $message, $headers);

// Return response
if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Course reported successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send report. Please try again later.']);
}
?>