<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @yield('styles')

</head>

<body>
    @include('templates.header')

    <div class="container">
        @yield('content')
    </div>

    @include('templates.footer')

    <script>
        const carousel = document.getElementById('productCarousel');
        const nameElem = document.getElementById('productName');
        const descElem = document.getElementById('productDesc');
        const textBox = document.getElementById('productText');

        const updateText = () => {
            const activeItem = carousel.querySelector('.carousel-item.active');
            const name = activeItem.getAttribute('data-name');
            const desc = activeItem.getAttribute('data-description');

            nameElem.textContent = name;
            descElem.textContent = desc;

            // Animasi ulang teks
            textBox.classList.remove('fade-in');
            void textBox.offsetWidth; // trigger reflow
            textBox.classList.add('fade-in');
        };

        const bsCarousel = new bootstrap.Carousel(carousel);
        carousel.addEventListener('slid.bs.carousel', updateText);
    </script>

</body>

</html>
