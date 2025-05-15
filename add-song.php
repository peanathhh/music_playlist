<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
  header("Location: login.html");
  exit;
}

require_once 'db.php';

// Fetch categories from DB
$stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add New Song</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="dashboard-page">
  <header class="admin-header">
    <h1>Add New Song</h1>
    <a href="admin-dashboard.php" class="logout-btn">← Back to Dashboard</a>
  </header>

  <main class="dashboard-content">
    <form action="save-song.php" method="POST" enctype="multipart/form-data" class="admin-form">
      <label for="title">Song Title:</label>
      <input type="text" name="title" id="title" required />

      <label for="artist">Artist:</label>
      <input type="text" name="artist" id="artist" required />

      <label for="category">Category:</label>
      <select name="category" id="category" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>


      <label for="lyrics">Lyrics:</label>
      <textarea name="lyrics" id="lyrics" rows="6" required></textarea>

      <label for="cover">Cover Image:</label>
      <input type="file" name="cover" id="cover" accept="image/*" required />

      <label for="video_link">YouTube/Video Link:</label>
      <input type="url" name="video_link" id="video_link" />

      <label for="video_file">Or Upload Video File:</label>
      <input type="file" name="video_file" id="video_file" accept="video/*" />

      <button type="submit">Save Song</button>
    </form>
  </main>
</body>
</html>
