function filterSongs() {
    const category = document.getElementById('category').value;
    const songs = document.querySelectorAll('.song-card');
  
    songs.forEach(song => {
      const songCategory = song.getAttribute('data-category');
  
      if (category === 'all' || songCategory === category) {
        song.style.display = 'block';
      } else {
        song.style.display = 'none';
      }
    });
  }
  