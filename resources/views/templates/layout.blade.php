<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', "D'Kost")</title>
    <link rel="icon" href="{{ asset('img/dkos_logo.png') }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Google Fonts: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @yield('styles')

</head>

<body class="@yield('body_class', '')">
<div class="page-wrapper">

    @yield('content')

</div>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
