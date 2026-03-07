document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('carouselContainer');
    const card = container.querySelector('.product-card');

    if (!card) return;

    const cardStyle = window.getComputedStyle(card);
    const cardWidth = card.offsetWidth + parseInt(cardStyle.marginRight || 0);

    let currentPosition = 0;
    const maxScroll = container.scrollWidth - container.clientWidth;

    document.getElementById('nextBtn').addEventListener('click', () => {
        currentPosition += cardWidth;
        if (currentPosition > maxScroll) currentPosition = maxScroll;
        container.scrollTo({ left: currentPosition, behavior: 'smooth' });
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        currentPosition -= cardWidth;
        if (currentPosition < 0) currentPosition = 0;
        container.scrollTo({ left: currentPosition, behavior: 'smooth' });
    });

    window.addEventListener('resize', () => {
        currentPosition = container.scrollLeft;
    });
});
