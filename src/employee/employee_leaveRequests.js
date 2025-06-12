document.addEventListener("DOMContentLoaded", () => {
    const filterStatus = document.getElementById('filterStatus');
    const requestWrapper = document.getElementById('request__wrapper');
    const leaveCards = requestWrapper.querySelectorAll('.leave__card');
    const emptyMessage = document.getElementById('empty');
    const emptyImage = document.getElementById('alden');

    const cards = document.querySelectorAll('.leave__card');

    function updateEmptyState(visibleCardsCount) {
        if (visibleCardsCount === 0) {
            if (emptyMessage) {
                emptyMessage.style.display = 'block';
            }
            if (emptyImage) {
                emptyImage.style.display = 'block';
            }
        } else {
            if (emptyMessage) {
                emptyMessage.style.display = 'none';
            }
            if (emptyImage) {
                emptyImage.style.display = 'none';
            }
        }
    }

    filterStatus.addEventListener('change', function() {
        const selectedStatus = this.value;
        let visibleCardsCount = 0;

        leaveCards.forEach(card => {
            const cardStatusElement = card.querySelector('.card__status h4');
            if (cardStatusElement) {
                const cardStatus = cardStatusElement.textContent.toLowerCase();

                if (selectedStatus === 'all' || cardStatus === selectedStatus) {
                    card.style.display = 'block';
                    visibleCardsCount++;
                } else {
                    card.style.display = 'none';
                }
            } else {
                if (selectedStatus === 'all') {
                    card.style.display = 'block';
                    visibleCardsCount++;
                } else {
                    card.style.display = 'none';
                }
            }
        });

        updateEmptyState(visibleCardsCount);
    });

    // Initialize the empty state on page load (for the default 'All Requests' selection)
    let initialVisibleCardsCount = 0;
    leaveCards.forEach(card => {
        card.style.display = 'block'; // Initially show all
        initialVisibleCardsCount++;
    });
    updateEmptyState(initialVisibleCardsCount);

    cards.forEach(card => {
        const header = card.querySelector('.card__header');

        header.addEventListener('click', () => {
            card.classList.toggle('activeCard');
        });
    });
});