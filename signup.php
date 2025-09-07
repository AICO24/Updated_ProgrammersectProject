<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$password2 = trim($_POST['password2'] ?? '');

if ($password !== $password2) {
    die("Passwords do not match. <a href='index.php?show_signup=1&signup_username=" . urlencode($username) . "'>Go back</a>");
}

// Check duplicate username or email
$dup = $conn->prepare("SELECT 1 FROM users WHERE username = ? OR email = ? LIMIT 1");
$dup->bind_param("ss", $username, $email);
$dup->execute();
$dup->store_result();

if ($dup->num_rows > 0) {
    $dup->close();
    die("Username or Email already exists. <a href='index.php?show_signup=1&signup_username=" . urlencode($username) . "'>Go back</a>");
}
$dup->close();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $fullname, $username, $email, $hashedPassword);

if ($stmt->execute()) {
    header("Location: index.php?show_signup=0&signup_username=" . urlencode($username));
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
