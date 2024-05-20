document.getElementById('search-btn').addEventListener('click', function() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const roomCards = document.querySelectorAll('.room__card');
  
    roomCards.forEach(card => {
      const cardTitle = card.querySelector('h4').textContent.toLowerCase();
      if (cardTitle.includes(searchInput)) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  });