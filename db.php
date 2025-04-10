<?php
$host = 'localhost';  // XAMPP's MySQL host
$dbname = 'song_database';  // Name of your database
$username = 'root';  // Default MySQL username in XAMPP
$password = '';  // Default MySQL password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
