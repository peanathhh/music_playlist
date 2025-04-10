<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $artist = $_POST['artist'];

    $stmt = $pdo->prepare("UPDATE songs SET title = ?, artist = ? WHERE id = ?");
    $stmt->execute([$title, $artist, $id]);

    header("Location: dashboard.php");
    exit();
}
?>
