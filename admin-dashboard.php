<?php
require_once 'db.php';  // Include the database connection

// Fetch the total number of songs and categories
$totalSongsStmt = $pdo->query("SELECT COUNT(*) FROM songs");
$totalSongs = $totalSongsStmt->fetchColumn();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
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

    <section class="admin-links">
      <a href="add-song.php" class="admin-btn">➕ Add New Song</a>
      <a href="manage-songs.php" class="admin-btn">📋 Manage Songs</a>
      <a href="manage-categories.php" class="admin-btn">📂 Manage Categories</a>
    </section>
  </main>
</body>
</html>
