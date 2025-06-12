 document.addEventListener("DOMContentLoaded", () => {

  const cards = document.querySelectorAll('.leave__card');

  cards.forEach(card => {
    const header = card.querySelector('.card__header');

    header.addEventListener('click', () => {
      card.classList.toggle('activeCard');
    });
  });
});

