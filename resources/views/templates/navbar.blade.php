<nav class="navbar navbar-expand-lg sticky-top bg-transparent text-white">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('/img/griya batik_bru.png') }}" height="35" alt="Logo SMKN7 Jember" loading="lazy" />
            <small class="fs-6 ml-2">Griya Batik</small>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                <li class="nav-item me-4">
                    <a class="nav-link" href="{{ route('index') }}">Beranda</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link" href="{{ route('about') }}">Tentang Kami</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link" href="{{ route('product') }}">Produk</a>
                </li>
            </ul>
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Masuk</a>
            @endguest
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.querySelector('.navbar');

        function handleScroll() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }

        window.addEventListener('scroll', handleScroll);
        handleScroll();
    });
</script>
