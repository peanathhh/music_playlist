<?php
// Define the function to get songs from the database
function getSongsFromDatabase() {
    // Database connection parameters
    $servername = "localhost"; // Your server (localhost for local development)
    $username = "root";        // Your MySQL username
    $password = "";            // Your MySQL password (leave empty for XAMPP default)
    $dbname = "music_playlist"; // Your database name

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to get the songs
    $sql = "SELECT title, cover_image FROM songs";
    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Fetch all rows
        $songs = [];
        while($row = $result->fetch_assoc()) {
            $songs[] = $row;
        }
        return $songs; // Return the song data
    } else {
        return []; // Return an empty array if no songs are found
    }

    // Close the connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Song Lyrics Collection</title>
  <link rel="stylesheet" href="styles.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <header>
    <h1>🎵 Song Lyrics</h1>
    <a href="login.html" class="login-btn">Admin Login</a>
  </header>

  <section class="filter-section">
    <label for="category">Filter by Category:</label>
    <select id="category" onchange="filterSongs()">
      <option value="all">All</option>
      <option value="pop">Pop</option>
      <option value="rock">Rock</option>
      <option value="rap">Rap</option>
      <!-- Add more categories as needed -->
    </select>
  </section>

  <main class="song-grid" id="song-grid">
    <!-- Song cards will be dynamically loaded here -->
  </main>

  <footer>
    &copy; 2025 Song Lyrics Collection
  </footer>

  <script>
    function filterSongs() {
      var category = document.getElementById("category").value;

      $.ajax({
        url: "song.php", // This file will handle the song retrieval
        method: "GET",
        data: { category: category },
        success: function(response) {
          // Clear the song grid before appending new songs
          document.getElementById("song-grid").innerHTML = response;
        },
        error: function() {
          alert("Error loading song.");
        }
      });
    }

    // Load all songs on page load
    $(document).ready(function() {
      filterSongs();  // Load all songs initially
    });
  </script>
</body>
</html>
