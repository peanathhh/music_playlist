<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);

  if (!empty($name)) {
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$name]);
    header("Location: admin-dashboard.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Category</title>
</head>
<body>
  <h1>Add New Category</h1>
  <form method="POST">
    <label>Category Name:</label>
    <input type="text" name="name" required>
    <button type="submit">Add Category</button>
  </form>
</body>
</html>
