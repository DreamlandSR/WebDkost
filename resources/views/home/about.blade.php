@extends('templates.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/override.css') }}">
@endsection

@section('content')

    @include('templates.header')
    @include('templates.navbar')

    <main class="flex-shrink-0 fade-in">
        <!-- Header-->
        <header class="py-5 slide-in">
            <div class="container px-5">
                <div class="row justify-content-center move-up">
                    <div class="col-lg-8 col-xxl-6">
                        <div class="text-center mb-3">
                            <h1 class="fw-bolder mb-3">Tentang Kami</h1>
                            <p class="lead fw-normal text-muted mb-4">
                              "D'Kost hadir untuk memberikan solusi tempat tinggal yang nyaman, aman, dan terjangkau.
                        Kami menyediakan berbagai pilihan kamar kos dengan fasilitas lengkap di lokasi strategis.
                        Dengan sistem pemesanan online yang mudah, transparansi harga, dan layanan pelanggan 24/7,
                        kami berkomitmen untuk memudahkan Anda menemukan kos impian."</p>
                            <a class="btn btn-primary btn-lg" href="#scroll-target">Pelajari Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- About section one-->
        <section class="py-5 bg-light slide-in" id="scroll-target">
            <div class="container px-5 my-5">
                <div class="row gx-5 align-items-center">
                    <div class="col-lg-6">
                        <img src="{{ asset('img/room-modern-1.png') }}" alt="Kamar Kos Modern"
                            class="img-fluid rounded mb-5 mb-lg-0"
                            style="width: 450px; height: 300px; object-fit: cover;" />
                    </div>
                    <div class="col-lg-6">
                        <h2 class="fw-bolder">Visi & Misi D'Kost</h2>
                        <p class="lead fw-normal text-muted mb-0">
                            "Menjadi solusi sewa kos online terpercaya dengan layanan cepat, transparansi harga,
                            fasilitas lengkap, dan kemudahan transaksi untuk semua kalangan."
                        </p>
                    </div>
                </div>

            </div>
        </section>
        <!-- About section two — Alamat + Map -->
        <section class="py-5 slide-in">
            <div class="container px-5 my-5">
                <div class="row gx-5 align-items-center">
                    <div class="col-lg-6 order-first order-lg-last">
                        <a href="https://www.google.com/maps/search/Jl.+Mastrip,+Krajan+Timur,+Sumbersari,+Kec.+Sumbersari,+Kabupaten+Jember,+Jawa+Timur+68121" target="_blank" rel="noopener noreferrer" class="d-block map-wrapper rounded shadow overflow-hidden mb-5 mb-lg-0" title="Buka di Google Maps">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.437!2d113.7134!3d-8.1725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695b5e6c2c5e7%3A0x3c0!2sJl.+Mastrip%2C+Krajan+Timur%2C+Sumbersari%2C+Kec.+Sumbersari%2C+Kabupaten+Jember%2C+Jawa+Timur+68121!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
                                width="100%" height="300"
                                style="border:0; pointer-events:none;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                            <div class="map-overlay-hint">
                                <i class="bi bi-geo-alt-fill me-2"></i>Klik untuk buka di Google Maps
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <h2 class="fw-bolder">Alamat</h2>
                        <p class="lead fw-normal text-muted mb-3">
                            Jl. Mastrip, Krajan Timur, Sumbersari, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68121.
                        </p>
                        <a href="https://www.google.com/maps/search/Jl.+Mastrip,+Krajan+Timur,+Sumbersari,+Kec.+Sumbersari,+Kabupaten+Jember,+Jawa+Timur+68121" target="_blank" rel="noopener noreferrer" class="btn btn-outline-green">
                            <i class="bi bi-map me-2"></i>Lihat di Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('templates.main_footer')
    @include('templates.footer')

    @endsection