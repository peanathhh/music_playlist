<?php
// Include the database connection
require_once 'db.php';

// Get the category filter from the query string
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Construct the query to get songs based on the selected category
$query = "SELECT * FROM songs";
if ($category !== 'all') {
    $query .= " WHERE category = :category";
}

$stmt = $pdo->prepare($query);
if ($category !== 'all') {
    $stmt->bindParam(':category', $category);
}

$stmt->execute();
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output the songs as HTML
if ($songs) {
    foreach ($songs as $song) {
        echo '<div class="song-card">';
        echo '<img src="images/' . htmlspecialchars($song['cover_image']) . '" alt="Cover" />';
        echo '<h3>' . htmlspecialchars($song['title']) . '</h3>';
        echo '<a href="song.php?id=' . $song['id'] . '">View Lyrics</a>';
        echo '</div>';
    }
} else {
    echo '<p>No songs available in this category.</p>';
}
?>
