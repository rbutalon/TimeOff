  document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    const cards = document.querySelectorAll('.employee__card');

    cards.forEach(card => {
      const name = card.querySelector('h3').textContent.toLowerCase();
      const email = card.querySelector('p:nth-of-type(1)').textContent.toLowerCase();
      const role = card.querySelector('p:nth-of-type(2)').textContent.toLowerCase();
      const department = card.querySelector('p:nth-of-type(3)').textContent.toLowerCase();

      if (name.includes(query) || email.includes(query) || department.includes(query)) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });
  });