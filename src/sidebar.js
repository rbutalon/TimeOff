const toggleButton = document.getElementById('toggle__btn');
const sidebar = document.getElementById('sidebar');
const hamburger = document.getElementById('hamburger');

function toggleSideBar() {
    sidebar.classList.toggle('mobile-open');
    sidebar.classList.toggle('mobile-close');
    sidebar.classList.toggle('close');
    toggleButton.classList.toggle('rotate');
}

function toggleSubMenu(button) {
    button.nextElementSibling.classList.toggle('active');
    button.classList.toggle('rotate');
}

hamburger.addEventListener('click', () => {
    toggleSideBar();
    sidebar.classList.remove('close');
});
