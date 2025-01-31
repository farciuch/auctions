import './bootstrap';


import.meta.glob([
    '../images/**'
]);



document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('carousel');
    const items = carousel.querySelectorAll('[data-carousel-item]');
    const prevButton = carousel.querySelector('[data-carousel-prev]');
    const nextButton = carousel.querySelector('[data-carousel-next]');

    let currentIndex = 0;

    function showItem(index) {
        items.forEach((item, i) => {
            item.style.display = i === index ? 'block' : 'none';
        });
    }

    function prevItem() {
        currentIndex = (currentIndex === 0) ? items.length - 1 : currentIndex - 1;
        showItem(currentIndex);
    }

    function nextItem() {
        currentIndex = (currentIndex === items.length - 1) ? 0 : currentIndex + 1;
        showItem(currentIndex);
    }

    prevButton.addEventListener('click', prevItem);
    nextButton.addEventListener('click', nextItem);

    showItem(currentIndex);
});
