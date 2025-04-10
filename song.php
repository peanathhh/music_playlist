<?php
require_once 'db.php';  // Include the database connection

$category = isset($_GET['category']) ? $_GET['category'] : 'all';

if ($category === 'all') {
    $songsStmt = $pdo->query("SELECT * FROM songs ORDER BY id DESC");
} else {
    $songsStmt = $pdo->prepare("SELECT * FROM songs WHERE category = :category ORDER BY id DESC");
    $songsStmt->execute(['category' => $category]);
}

$songs = $songsStmt->fetchAll(PDO::FETCH_ASSOC);

// Generate the song list as HTML
foreach ($songs as $song) {
    echo '<div class="song-card">';
    echo '<h3>' . htmlspecialchars($song['title']) . '</h3>';
    echo '<p>Artist: ' . htmlspecialchars($song['artist']) . '</p>';
    echo '<p>Category: ' . htmlspecialchars($song['category']) . '</p>';

    // Clean up the filename and prepend the path
    $filename = htmlspecialchars($song['cover']);
    if (!empty($filename)) {
        // Ensure it doesn't already include the full path
        if (strpos($filename, 'upload/images/') !== 0) {
            $coverImagePath = 'upload/images/' . $filename;
        } else {
            $coverImagePath = $filename;
        }

        // Display the image
        echo '<img src="' . $coverImagePath . '" alt="Cover Image" class="cover-image">';
    } else {
        echo '<div class="no-cover">';
        echo '<p>Cover image not available</p>';
        echo '</div>';
    }

    // Display lyrics with line breaks
    echo '<div class="lyrics">' . nl2br(htmlspecialchars($song['lyrics'])) . '</div>';

    // Display video link if available
    if (!empty($song['video_link'])) {
        echo '<a href="' . htmlspecialchars($song['video_link']) . '" target="_blank" class="video-link">Watch Video</a>';
    }

    echo '</div>'; // Close song-card
}
?>
