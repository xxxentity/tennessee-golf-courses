<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Get form data
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validation
$errors = [];

if (empty($username) || strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address";
}

if (empty($first_name) || empty($last_name)) {
    $errors[] = "First and last name are required";
}

if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters long";
}

if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match";
}

// Check if username or email already exists
if (empty($errors)) {
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            $errors[] = "Username or email already exists";
        }
    } catch (PDOException $e) {
        $errors[] = "Database error occurred";
    }
}

// If there are errors, redirect back with error message
if (!empty($errors)) {
    $error_message = implode(". ", $errors);
    header('Location: register.php?error=' . urlencode($error_message));
    exit;
}

// Hash password and insert user
try {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, email, first_name, last_name, password_hash) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $first_name, $last_name, $password_hash]);
    
    // Success - redirect to login with success message
    header('Location: login.php?success=' . urlencode('Account created successfully! Please log in.'));
    exit;
    
} catch (PDOException $e) {
    header('Location: register.php?error=' . urlencode('Registration failed. Please try again.'));
    exit;
}
?>