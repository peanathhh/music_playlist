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
  
  const editBtns = document.querySelectorAll('.edit-btn');
  const deleteBtns = document.querySelectorAll('.delete-btn');

  editBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('edit-id').value = this.dataset.id;
      document.getElementById('edit-title').value = this.dataset.title;
      document.getElementById('edit-artist').value = this.dataset.artist;
      document.getElementById('editModal').style.display = 'flex';
    });
  });

  deleteBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('delete-id').value = this.dataset.id;
      document.getElementById('deleteModal').style.display = 'flex';
    });
  });

  function closeModal(id) {
    document.getElementById(id).style.display = 'none';
  }