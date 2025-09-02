<?php
session_start();

// Clear admin session
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_email']);
unset($_SESSION['is_admin']);

// Destroy session if no other session data
if (empty($_SESSION)) {
    session_destroy();
}

header('Location: /admin/login?message=logged_out');
exit;
?>