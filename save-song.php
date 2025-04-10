<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
  header("Location: login.html");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $category = $_POST['category'];
    $lyrics = $_POST['lyrics'];
    $video_link = $_POST['video_link'];
    
    // Handle file uploads (cover image)
    $cover = $_FILES['cover']['name'];
    $coverTmpName = $_FILES['cover']['tmp_name'];
    $coverDestination = 'upload/images/' . $cover;
    move_uploaded_file($coverTmpName, $coverDestination);

    // Save to database (example with PDO)
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=song_database', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert data into the songs table
        $stmt = $pdo->prepare("INSERT INTO songs (title, artist, category, lyrics, cover, video_link) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $artist, $category, $lyrics, $coverDestination, $video_link]);

        header('Location: admin-dashboard.php');  // Redirect to dashboard after saving
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
