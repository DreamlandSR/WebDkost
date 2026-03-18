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
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-xxl-6">
                        <div class="text-center my-3">
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
        <!-- About section two-->
        <section class="py-5 slide-in">
            <div class="container px-5 my-5">
                <div class="row gx-5 align-items-center">
                    <div class="col-lg-6 order-first order-lg-last">
                        <img class="img-fluid rounded mb-5 mb-lg-0" src="{{ asset('img/room-modern-3.png') }}" alt="Kamar Kos"
                            style="width: 450px; height: 300px; object-fit: cover;" />
                    </div>
                    <div class="col-lg-6">
                        <h2 class="fw-bolder">Alamat</h2>
                        <p class="lead fw-normal text-muted mb-0">
                            Jl. Mastrip, Krajan Timur, Sumbersari, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68121.
                        </p>
                    </div>
                </div>
            </div>
        </section>

            </div>
        </section>
    </main>

    @include('templates.main_footer')
    @include('templates.footer')
