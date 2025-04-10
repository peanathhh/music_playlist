<?php
require_once 'db.php';  // Include the database connection

// Fetch the total number of songs and categories
$totalSongsStmt = $pdo->query("SELECT COUNT(*) FROM songs");
$totalSongs = $totalSongsStmt->fetchColumn();
// Fetch all songs
$songsStmt = $pdo->query("SELECT * FROM songs ORDER BY id DESC");
$songs = $songsStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <script src="main.js"></script>
  <style>
    /* General Styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    header {
      background-color: #333;
      color: white;
      padding: 15px;
      text-align: center;
    }

    h1 {
      margin: 0;
      font-size: 2rem;
    }

    a {
      color: #3498db;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    /* Dashboard Content */
    .dashboard-page {
      margin: 20px;
    }

    /* Stats Section */
    .stats {
      display: flex;
      justify-content: space-around;
      margin-top: 30px;
    }

    .stat-card {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 30%;
    }

    .stat-card h3 {
      font-size: 1.5rem;
    }

    .stat-card p {
      font-size: 2rem;
      color: #3498db;
    }

    /* Admin Links Section */
    .admin-links {
      margin-top: 30px;
      text-align: center;
    }

    .admin-btn {
      background-color: #3498db;
      color: white;
      padding: 12px 20px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 1.2rem;
      margin: 10px;
      display: inline-block;
    }

    .admin-btn:hover {
      background-color: #2980b9;
    }

    .logout-btn {
      color: #e74c3c;
      text-decoration: none;
      font-size: 1.2rem;
      margin-left: 20px;
    }

    .logout-btn:hover {
      text-decoration: underline;
    }

    /* Modal Styles */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      width: 300px;
      text-align: center;
    }

    .close {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 20px;
      cursor: pointer;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .stats {
        flex-direction: column;
        align-items: center;
      }

      .stat-card {
        width: 80%;
        margin-bottom: 20px;
      }

      .admin-btn {
        width: 100%;
        padding: 15px;
      }
    }
  </style>
</head>
<body class="dashboard-page">
  <header class="admin-header">
    <h1>Admin Dashboard</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
  </header>

  <main class="dashboard-content">
    <section class="stats">
      <div class="stat-card">
        <h3>Total Songs</h3>
        <p><?php echo $totalSongs; ?></p>
      </div>
      <div class="stat-card">
        <h3>Total Categories</h3>
        <p></p>
      </div>
    </section>
    </section>

    <section class="admin-links">
      <a href="add-song.php" class="admin-btn">➕ Add New Song</a>
      
    </section>

    <section class="song-list" style="margin: 40px 20px;">
      <h2>🎵 Songs List</h2>
      <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
          <tr style="background-color: #3498db; color: white;">
            <th style="padding: 10px;">ID</th>
            <th style="padding: 10px;">Title</th>
            <th style="padding: 10px;">Artist</th>
            <th style="padding: 10px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($songs as $song): ?>
            <tr style="background-color: #fff; border-bottom: 1px solid #ddd;">
              <td style="padding: 10px;"><?php echo $song['id']; ?></td>
              <td style="padding: 10px;"><?php echo htmlspecialchars($song['title']); ?></td>
              <td style="padding: 10px;"><?php echo htmlspecialchars($song['artist']); ?></td>
              <td style="padding: 10px;">
                <a href="#" class="edit-btn" data-id="<?php echo $song['id']; ?>" data-title="<?php echo htmlspecialchars($song['title']); ?>" data-artist="<?php echo htmlspecialchars($song['artist']); ?>">✏️ Edit</a>
                <a href="#" class="delete-btn" data-id="<?php echo $song['id']; ?>">🗑️ Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Edit Song Modal -->
    <div id="editModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h2>Edit Song</h2>
        <form id="editForm" method="POST" action="update-song.php">
          <input type="hidden" name="id" id="edit-id">
          <label>Title:</label>
          <input type="text" name="title" id="edit-title" required><br>
          <label>Artist:</label>
          <input type="text" name="artist" id="edit-artist" required><br>
          <button type="submit">Update</button>
        </form>
      </div>
    </div>

    <!-- Delete Song Modal -->
    <div id="deleteModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal('deleteModal')">&times;</span>
        <h2>Confirm Delete</h2>
        <p>Are you sure you want to delete this song?</p>
        <form method="POST" action="delete-song.php">
          <input type="hidden" name="id" id="delete-id">
          <button type="submit" style="background-color: #e74c3c; color: white;">Yes, Delete</button>
          <button type="button" onclick="closeModal('deleteModal')">Cancel</button>
        </form>
      </div>
    </div>

  </main>

  <script>
    // JavaScript for handling modal actions
    document.addEventListener('DOMContentLoaded', () => {
      const editBtns = document.querySelectorAll('.edit-btn');
      const deleteBtns = document.querySelectorAll('.delete-btn');

      // Open Edit Modal and populate the fields
      editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          document.getElementById('edit-id').value = this.dataset.id;
          document.getElementById('edit-title').value = this.dataset.title;
          document.getElementById('edit-artist').value = this.dataset.artist;
          document.getElementById('editModal').style.display = 'flex';
        });
      });

      // Open Delete Modal and set song ID
      deleteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          document.getElementById('delete-id').value = this.dataset.id;
          document.getElementById('deleteModal').style.display = 'flex';
        });
      });

      // Close Modals
      window.closeModal = function(id) {
        document.getElementById(id).style.display = 'none';
      };
    });
  </script>

</body>
</html>
