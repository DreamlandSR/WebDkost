<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @yield('styles')

</head>

<body>
<div class="page-wrapper">

    <div class="@yield('container_class', '')">
        @yield('content')
    </div>

</div>

    <script>
        // Cek elemen ada dulu sebelum pakai
        const carousel = document.getElementById('productCarousel');
        if (carousel) {
            const nameElem = document.getElementById('productName');
            const descElem = document.getElementById('productDesc');
            const textBox = document.getElementById('productText');

            const updateText = () => {
                const activeItem = carousel.querySelector('.carousel-item.active');
                if (!activeItem) return;
                const name = activeItem.getAttribute('data-name');
                const desc = activeItem.getAttribute('data-description');

                if (nameElem) nameElem.textContent = name;
                if (descElem) descElem.textContent = desc;

                if (textBox) {
                    textBox.classList.remove('fade-in');
                    void textBox.offsetWidth;
                    textBox.classList.add('fade-in');
                }
            };

            const bsCarousel = new bootstrap.Carousel(carousel);
            carousel.addEventListener('slid.bs.carousel', updateText);
        }
    </script>

</body>

</html>
