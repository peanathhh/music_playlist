<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
        header("Location: admin-dashboard.php"); // Or whatever your admin page is
        exit;
    } else {
        echo "Category name is required.";
    }
} else {
    echo "Invalid request.";
}
