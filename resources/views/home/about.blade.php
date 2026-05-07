@extends('templates.layout')

@section('styles')
    {{-- Vite CSS --}}
    @vite([
        'resources/css/app.css',
    ])
@endsection

@section('content')
    @include('templates.header')
    @include('templates.navbar')

    <main class="about-page-main">
        <!-- Hero Section - Full width seperti beranda -->
        <section class="about-hero-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-8 text-center">
                        <h1 class="about-hero-title">Tentang Kami</h1>
                        <p class="about-hero-text">
                            "D'Kost hadir untuk memberikan solusi tempat tinggal yang nyaman, aman, dan terjangkau.
                            Kami menyediakan berbagai pilihan kamar kos dengan fasilitas lengkap di lokasi strategis.
                            Dengan sistem pemesanan online yang mudah, transparansi harga, dan layanan pelanggan 24/7,
                            kami berkomitmen untuk memudahkan Anda menemukan kos impian."
                        </p>
                        <a class="btn btn-primary btn-lg" href="#visi-misi">Pelajari Selengkapnya</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Visi & Misi Section - Layout card seperti beranda -->
        <section class="about-vision-section" id="visi-misi">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="about-image-wrapper">
                            <img src="{{ asset('img/room-modern-1.png') }}" 
                                 alt="Kamar Kos Modern"
                                 class="img-fluid about-section-image">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-content-wrapper">
                            <h2 class="about-section-title">Visi & Misi D'Kost</h2>
                            <p class="about-section-text">
                                "Menjadi solusi sewa kos online terpercaya dengan layanan cepat, 
                                transparansi harga, fasilitas lengkap, dan kemudahan transaksi 
                                untuk semua kalangan."
                            </p>
                            <div class="about-feature-list mt-4">
                                <div class="about-feature-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Layanan Cepat & Responsif</span>
                                </div>
                                <div class="about-feature-item">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Transparansi Harga</span>
                                </div>
                                <div class="about-feature-item">
                                    <i class="bi bi-building"></i>
                                    <span>Fasilitas Lengkap</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alamat Section - Alternating layout seperti beranda -->
       <section class="about-address-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <div class="about-map-wrapper">
                    <!-- TANPA API KEY - LANGSUNG PAKAI URL STATIC -->
                    <iframe 
                        class="about-iframe-map"
                        src="https://maps.google.com/maps?q=Jl.+Mastrip+Krajan+Timur+Sumbersari+Jember&output=embed"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="about-content-wrapper">
                    <h2 class="about-section-title">Lokasi Strategis</h2>
                    <p class="about-section-text">
                        Berada di pusat kota dengan akses mudah ke berbagai fasilitas umum:
                    </p>
                    <div class="about-address-card">
                        <i class="bi bi-geo-alt-fill"></i>
                        <div>
                            <strong>Alamat D'Kost</strong><br>
                            Jl. Mastrip, Krajan Timur, Sumbersari,<br>
                            Kec. Sumbersari, Kabupaten Jember,<br>
                            Jawa Timur 68121
                        </div>
                    </div>
                    <a href="https://www.google.com/maps/dir//Jl.+Mastrip+Krajan+Timur+Sumbersari+Jember" target="_blank" class="btn btn-outline-green mt-4">
                        <i class="bi bi-map"></i> Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
    </main>

    @include('templates.main_footer')
    @include('templates.footer')
@endsection

@push('scripts')
    @vite(['resources/js/app.js'])
@endpush