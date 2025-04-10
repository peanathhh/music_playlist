<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM songs WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: admin-dashboard.php");
    exit();
}
?>
