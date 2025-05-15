<?php
// Shared DB config
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "song_database";

// Connect to DB
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

// Handle AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';

    if ($category === 'all') {
        $stmt = $pdo->query("SELECT * FROM songs ORDER BY id DESC");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM songs WHERE LOWER(category) = LOWER(:category) ORDER BY id DESC");
        $stmt->execute(['category' => $category]);
    }

    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$songs) {
        echo '<p>No songs found in this category.</p>';
        exit;
    }

    foreach ($songs as $song) {
        $title = htmlspecialchars($song['title']);
        $artist = htmlspecialchars($song['artist']);
        $categoryName = htmlspecialchars($song['category']);
        $lyrics = nl2br(htmlspecialchars($song['lyrics']));
        $videoLink = htmlspecialchars($song['video_link']);
        $filename = htmlspecialchars($song['cover']);
        $coverImagePath = $filename ? (strpos($filename, 'upload/images/') === 0 ? $filename : 'upload/images/' . $filename) : '';

        echo '<div class="song-card">';
        echo "<h3 class='song-title'>{$title}</h3>";
        echo "<p class='song-artist'>Artist: {$artist}</p>";
        echo "<p class='song-category'>Category: {$categoryName}</p>";

        if ($coverImagePath) {
            echo "<img src='{$coverImagePath}' alt='Cover image of {$title}' class='cover-image'>";
        } else {
            echo '<div class="no-cover">Cover image not available</div>';
        }

        echo "<div class='lyrics'>{$lyrics}</div>";

        if (!empty($videoLink)) {
            echo "<a href='{$videoLink}' target='_blank' class='video-link'>▶ Watch Video</a>";
        }

        echo '</div>';
    }
    exit;
}

// Load categories for dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>🎵 Song Lyrics Collection</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    header {
      background: #2d2d86;
      color: white;
      padding: 20px;
      text-align: center;
      position: relative;
    }

    .login-btn {
      position: absolute;
      right: 20px;
      top: 20px;
      background: #fff;
      color: #2d2d86;
      padding: 8px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
    }

    .filter-section {
      padding: 15px 20px;
      background: #e0e0e0;
    }

    select {
      padding: 6px 10px;
      font-size: 1rem;
    }

    .song-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
      gap: 20px;
      padding: 20px;
    }

    .song-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      padding: 20px;
    }

    .song-title {
      font-size: 1.5rem;
      margin-bottom: 8px;
      color: #2d2d86;
    }

    .song-artist,
    .song-category {
      font-size: 0.9rem;
      color: #555;
      font-style: italic;
      margin: 4px 0;
    }

    .cover-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      margin: 12px 0;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .no-cover {
      background: #eee;
      text-align: center;
      padding: 50px 0;
      font-style: italic;
      color: #777;
      border-radius: 10px;
      margin: 12px 0;
    }

    .lyrics {
      background: #f0f0f5;
      border-radius: 8px;
      padding: 15px;
      white-space: pre-wrap;
      font-family: 'Courier New', Courier, monospace;
      font-size: 0.9rem;
      max-height: 180px;
      overflow-y: auto;
      color: #333;
    }

    .video-link {
      display: inline-block;
      margin-top: 12px;
      padding: 10px 16px;
      background-color: #ff4081;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }

    .video-link:hover {
      background-color: #e73370;
    }

    footer {
      background: #2d2d86;
      color: white;
      text-align: center;
      padding: 10px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <header>
    <h1>🎵 Song Lyrics</h1>
    <a href="login.html" class="login-btn">Admin Login</a>
  </header>

  <form id="filterForm" method="GET" action="index.php" class="filter-section">
    <label for="category">Filter by Category:</label>
    <select name="category" id="category" onchange="filterSongs()">
      <option value="all">All</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat['name']) ?>" <?= (isset($_GET['category']) && $_GET['category'] === $cat['name']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>

  <main class="song-grid" id="song-grid">
    <!-- Songs will be loaded here -->
  </main>

  <footer>
    &copy; 2025 Song Lyrics Collection
  </footer>

  <script>
    function filterSongs() {
      const category = document.getElementById("category").value;

      fetch(`index.php?ajax=1&category=${encodeURIComponent(category)}`)
        .then(response => response.text())
        .then(data => {
          document.getElementById("song-grid").innerHTML = data;
        })
        .catch(err => {
          document.getElementById("song-grid").innerHTML = "<p>Error loading songs.</p>";
        });
    }

    window.onload = filterSongs;
  </script>
</body>
</html>
