<?php
require_once 'db.php';

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new category
    if (isset($_POST['add_category_name'])) {
        $name = trim($_POST['add_category_name']);
        if ($name !== '') {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
        }
        header('Location: admin-dashboard.php');
        exit;
    }

    // Edit category
    if (isset($_POST['edit_category_id']) && isset($_POST['edit_category_name'])) {
        $id = (int)$_POST['edit_category_id'];
        $name = trim($_POST['edit_category_name']);
        if ($name !== '') {
            $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
        }
        header('Location: admin-dashboard.php');
        exit;
    }

    // Delete category
    if (isset($_POST['delete_category_id'])) {
        $id = (int)$_POST['delete_category_id'];
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: admin-dashboard.php');
        exit;
    }
}

// Fetch total songs
$totalSongs = $pdo->query("SELECT COUNT(*) FROM songs")->fetchColumn();
$songs = $pdo->query("SELECT * FROM songs ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch total categories
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
    }
    header {
      background-color: #333;
      color: white;
      padding: 15px;
      text-align: center;
    }
    .dashboard-page {
      padding: 20px;
    }
    .stats {
      display: flex;
      justify-content: space-around;
      margin-top: 30px;
      flex-wrap: wrap;
    }
    .stat-card {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 30%;
      margin-bottom: 20px;
    }
    .admin-links {
      text-align: center;
      margin-top: 30px;
    }
    .admin-btn {
      background-color: #3498db;
      color: white;
      padding: 12px 20px;
      border-radius: 4px;
      font-size: 1.2rem;
      margin: 10px;
      display: inline-block;
      text-decoration: none;
    }
    .admin-btn:hover {
      background-color: #2980b9;
    }
    .modal {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      width: 300px;
      text-align: center;
      position: relative;
    }
    .close {
      position: absolute;
      right: 10px;
      top: 10px;
      cursor: pointer;
      font-size: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #3498db;
      color: white;
    }
    .category-list th {
      background-color: #2ecc71;
    }
    @media (max-width: 768px) {
      .stat-card {
        width: 80%;
      }
    }
  </style>
</head>
<body class="dashboard-page">
  <header>
    <h1>Admin Dashboard</h1>
    <a href="logout.php" style="color: #e74c3c;">Logout</a>
  </header>

  <main>
    <section class="stats">
      <div class="stat-card">
        <h3>Total Songs</h3>
        <p><?= $totalSongs ?></p>
      </div>
      <div class="stat-card">
        <h3>Total Categories</h3>
        <p><?= $totalCategories ?></p>
      </div>
    </section>

    <section class="admin-links">
      <a href="add-song.php" class="admin-btn">➕ Add New Song</a>
      <a href="#" class="admin-btn" onclick="openModal('addCategoryModal')">📂 Add New Category</a>
    </section>

    <section class="song-list">
      <h2>Songs List</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Artist</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($songs as $song): ?>
          <tr>
            <td><?= $song['id'] ?></td>
            <td><?= htmlspecialchars($song['title']) ?></td>
            <td><?= htmlspecialchars($song['artist']) ?></td>
            <td>
              <a href="#" class="edit-btn" data-id="<?= $song['id'] ?>" data-title="<?= htmlspecialchars($song['title']) ?>" data-artist="<?= htmlspecialchars($song['artist']) ?>">✏️ Edit</a>
              <a href="#" class="delete-btn" data-id="<?= $song['id'] ?>">🗑️ Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <section class="category-list">
      <h2>Category List</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $cat): ?>
          <tr>
            <td><?= $cat['id'] ?></td>
            <td><?= htmlspecialchars($cat['name']) ?></td>
            <td>
              <a href="#" class="edit-category-btn" 
                data-id="<?= $cat['id'] ?>" 
                data-name="<?= htmlspecialchars($cat['name']) ?>">✏️ Edit</a>
              <a href="#" class="delete-category-btn" 
                data-id="<?= $cat['id'] ?>" 
                data-name="<?= htmlspecialchars($cat['name']) ?>">🗑️ Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </main>

  <!-- Add Category Modal -->
  <div id="addCategoryModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('addCategoryModal')">&times;</span>
      <h2>Add New Category</h2>
      <form method="POST" action="admin-dashboard.php">
        <input type="text" name="add_category_name" placeholder="Category Name" required>
        <br><br>
        <button type="submit">Add Category</button>
      </form>
    </div>
  </div>

  <!-- Edit Song Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('editModal')">&times;</span>
      <h2>Edit Song</h2>
      <form id="editForm" method="POST" action="update-song.php">
        <input type="hidden" name="id" id="edit-id">
        <input type="text" name="title" id="edit-title" placeholder="Title" required><br><br>
        <input type="text" name="artist" id="edit-artist" placeholder="Artist" required><br><br>
        <button type="submit">Update</button>
      </form>
    </div>
  </div>

  <!-- Delete Song Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('deleteModal')">&times;</span>
      <h2>Delete Song</h2>
      <p>Are you sure you want to delete this song?</p>
      <form id="deleteForm" method="POST" action="delete-song.php">
        <input type="hidden" name="id" id="delete-id">
        <button type="submit">Yes, Delete</button>
        <button type="button" onclick="closeModal('deleteModal')">Cancel</button>
      </form>
    </div>
  </div>

  <!-- Edit Category Modal -->
  <div id="editCategoryModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('editCategoryModal')">&times;</span>
      <h2>Edit Category</h2>
      <form method="POST" action="admin-dashboard.php">
        <input type="hidden" name="edit_category_id" id="edit-category-id">
        <input type="text" name="edit_category_name" id="edit-category-name" placeholder="Category Name" required>
        <br><br>
        <button type="submit">Update Category</button>
      </form>
    </div>
  </div>

  <!-- Delete Category Modal -->
  <div id="deleteCategoryModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('deleteCategoryModal')">&times;</span>
      <h2>Delete Category</h2>
      <p id="delete-category-message"></p>
      <form method="POST" action="admin-dashboard.php">
        <input type="hidden" name="delete_category_id" id="delete-category-id">
        <button type="submit">Yes, Delete Category</button>
        <button type="button" onclick="closeModal('deleteCategoryModal')">Cancel</button>
      </form>
    </div>
  </div>

  <script>
    // Modal open/close helpers
    function openModal(id) {
      document.getElementById(id).style.display = 'flex';
    }
    function closeModal(id) {
      document.getElementById(id).style.display = 'none';
    }

    // Edit Category buttons
    document.querySelectorAll('.edit-category-btn').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.dataset.id;
        const name = button.dataset.name;
        document.getElementById('edit-category-id').value = id;
        document.getElementById('edit-category-name').value = name;
        openModal('editCategoryModal');
      });
    });

    // Delete Category buttons
    document.querySelectorAll('.delete-category-btn').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.dataset.id;
        const name = button.dataset.name;
        document.getElementById('delete-category-id').value = id;
        document.getElementById('delete-category-message').textContent = `Are you sure you want to delete the category "${name}"?`;
        openModal('deleteCategoryModal');
      });
    });

    // Songs edit/delete button handlers (unchanged from your original code)
    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.dataset.id;
        const title = button.dataset.title;
        const artist = button.dataset.artist;
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-title').value = title;
        document.getElementById('edit-artist').value = artist;
        openModal('editModal');
      });
    });
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.dataset.id;
        document.getElementById('delete-id').value = id;
        openModal('deleteModal');
      });
    });

    // Close modal on outside click
    window.onclick = function(event) {
      ['addCategoryModal', 'editModal', 'deleteModal', 'editCategoryModal', 'deleteCategoryModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (event.target === modal) {
          modal.style.display = "none";
        }
      });
    }
  </script>
</body>
</html>
