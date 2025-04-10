<?php
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM songs");
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Songs with Modal</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 12px;
      text-align: center;
    }

    th {
      background: #3498db;
      color: white;
    }

    .btn {
      padding: 8px 12px;
      color: white;
      border-radius: 4px;
      text-decoration: none;
      cursor: pointer;
    }

    .edit-btn { background-color: #2ecc71; }
    .delete-btn { background-color: #e74c3c; }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 8px;
      width: 400px;
      position: relative;
    }

    .modal-content h3 {
      margin-top: 0;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      cursor: pointer;
      font-size: 20px;
      color: #aaa;
    }

    .close-btn:hover {
      color: black;
    }

    input[type="text"] {
      width: 100%;
      padding: 8px;
      margin: 8px 0;
    }

    .modal-footer {
      margin-top: 10px;
      text-align: right;
    }
  </style>
</head>
<body>

<h2>Manage Songs</h2>

<table>
  <tr>
    <th>ID</th>
    <th>Title</th>
    <th>Artist</th>
    <th>Actions</th>
  </tr>
  <?php foreach ($songs as $song): ?>
    <tr>
      <td><?= $song['id'] ?></td>
      <td><?= htmlspecialchars($song['title']) ?></td>
      <td><?= htmlspecialchars($song['artist']) ?></td>
      <td>
        <button class="btn edit-btn" onclick="openEditModal(<?= $song['id'] ?>, '<?= htmlspecialchars(addslashes($song['title'])) ?>', '<?= htmlspecialchars(addslashes($song['artist'])) ?>')">Edit</button>
        <button class="btn delete-btn" onclick="openDeleteModal(<?= $song['id'] ?>)">Delete</button>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<!-- Edit Modal -->
<div class="modal" id="editModal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeEditModal()">&times;</span>
    <h3>Edit Song</h3>
    <form method="POST" action="edit-song.php">
      <input type="hidden" name="id" id="edit-id">
      <label>Title:</label>
      <input type="text" name="title" id="edit-title" required>
      <label>Artist:</label>
      <input type="text" name="artist" id="edit-artist" required>
      <div class="modal-footer">
        <button type="submit" class="btn edit-btn">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal" id="deleteModal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeDeleteModal()">&times;</span>
    <h3>Confirm Deletion</h3>
    <p>Are you sure you want to delete this song?</p>
    <form method="POST" action="delete-song.php">
      <input type="hidden" name="id" id="delete-id">
      <div class="modal-footer">
        <button type="submit" class="btn delete-btn">Yes, Delete</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openEditModal(id, title, artist) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-artist').value = artist;
    document.getElementById('editModal').style.display = 'flex';
  }

  function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
  }

  function openDeleteModal(id) {
    document.getElementById('delete-id').value = id;
    document.getElementById('deleteModal').style.display = 'flex';
  }

  function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
  }
</script>

</body>
</html>
