<?php
session_start();

// Fake credentials for demo
$validUsername = "admin";
$validPassword = "1234";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION["logged_in"] = true;
        header("Location: admin-dashboard.php"); // use .php instead of .html so we can protect it
        exit;
    } else {
        echo "<script>alert('Invalid credentials'); window.location.href='login.html';</script>";
    }
}
?>
