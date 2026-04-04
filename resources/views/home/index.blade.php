@extends('templates.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/override.css') }}">
@endsection

@section('content')

    @include('templates.header')
    @include('templates.navbar')

    <main class="flex-shrink-0">

        <!-- Header-->
        <header class="py-5 fade-in">
            <div class="container px-5">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-8 col-xl-7 col-xxl-6">
                        <div class="my-5 text-center text-xl-start">
                            <h1 class="display-5 fw-bolder text-black mb-3 text-start">D'Kost</h1>
                            <p class="lead fw-normal text-black mb-4" style="text-align:left; font-size: 1rem;">
                                D’Kost Merupakan Platform Untuk pemesanan kos secara online dan terpercaya. Kos kami
                                dilengkapi
                                dengan fasilitas yang lengkap dengan harga yang terjangkau untuk semua kalangan
                            </p>
                            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                                <a class="btn btn-primary btn-lg px-4 me-sm-3" href="{{ url('/login') }}">Login</a>
                                <a class="btn btn-outline-green btn-lg px-4" href="{{ url('/about') }}">Selengkapnya</a>
                            </div>
                        </div>
                    </div>

                    <!-- carousel -->
                    <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
                        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel"
                            data-bs-interval="4000">
                            <div class="carousel-inner">
                                @php $headerIndex = 0; @endphp
                                @forelse ($kamars as $kamar)
                                    @foreach ($kamar->galeri as $foto)
                                        <div class="carousel-item {{ $headerIndex === 0 ? 'active' : '' }}"
                                            data-kamar-nama="{{ $kamar->nomor_kamar }}"
                                            data-kamar-desc="{{ Str::limit($kamar->deskripsi, 100) }}">
                                            <img src="{{ asset('storage/' . $foto->url_foto) }}"
                                                class="d-block w-100 rounded" style="height: 340px; object-fit: cover;"
                                                alt="{{ $kamar->nomor_kamar }}">
                                            <div class="carousel-caption d-block"
                                                style="background: rgba(0,0,0,0.45); border-radius: 8px; padding: 8px 12px; bottom: 10px;">
                                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">
                                                    {{ $kamar->nomor_kamar }}</h6>
                                                <p class="mb-0" style="font-size: 0.72rem; opacity: 0.9;">
                                                    {{ Str::limit($kamar->deskripsi, 70) }}</p>
                                            </div>
                                        </div>
                                        @php $headerIndex++; @endphp
                                    @endforeach
                                @empty
                                    <div class="carousel-item active">
                                        <img src="{{ asset('img/kamira.png') }}" class="d-block w-100 rounded"
                                            style="height: 340px; object-fit: cover;" alt="Default Kamar">
                                    </div>
                                @endforelse
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Features section-->
        <section class="py-5" id="features">
            <div class="container px-5 my-5 slide-in">
                <div class="row">
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h2 class="fw-bolder mb-0">Fitur yang ada pada Website</h2>
                    </div>
                    <div class="col-lg-8">
                        <div class="row px-3">
                            <div class="col-12 col-md-6 mb-5 h-100">
                                <div class="feature bg-icon text-white rounded-3 mb-4">
                                    <i class="bi bi-house-door-fill"></i>
                                </div>
                                <h2 class="h5 fw-bold">Kamar Kos</h2>
                                <p class="mb-0">Menyediakan berbagai macam kamar kos dari yang terjangkau hingga yang
                                    termewah.</p>
                            </div>
                            <div class="col-12 col-md-6 mb-5 h-100">
                                <div class="feature bg-icon text-white rounded-3 mb-4">
                                    <i class="bi bi-building"></i>
                                </div>
                                <h2 class="h5 fw-bold">Penjelasan Usaha</h2>
                                <p class="mb-0">Visi dan Misi kami sebagai penyedia kos.</p>
                            </div>
                            <div class="col-12 col-md-6 mb-5 mb-md-0 h-100">
                                <div class="feature bg-icon text-white rounded-3 mb-4">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <h2 class="h5 fw-bold">Laporan Keuangan Bulanan</h2>
                                <p class="mb-0">Laporan keuangan bulanan yang terdata dengan jelas.</p>
                            </div>
                            <div class="col-12 col-md-6 h-100">
                                <div class="feature bg-icon text-white rounded-3 mb-4">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <h2 class="h5 fw-bold">Kemudahan Akses Pembelian</h2>
                                <p class="mb-0">Kemudahan akses untuk pemesanan dan pembayaran dalam aplikasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Deskripsi --}}
        <div class="py-5 slide-in">
            <div class="container px-5 pb-5">
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-10 col-xl-7">
                        <div class="text-center">
                            <h2 class="fw-bolder">Kamar Kos Andalan Kami</h2>
                            <div class="fs-5 mb-4 text-muted">Temukan kenyamanan rumah dalam setiap kamar — dari yang
                                terjangkau hingga premium.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container my-5">
                <div class="row align-items-center g-4">
                    <!-- Teks (update dinamis via JS) -->
                    <div class="col-md-5">
                        <div id="productText">
                            <span class="badge text-bg-success mb-3"
                                id="productTipe">{{ isset($kamars[0]) ? ucfirst($kamars[0]->tipe_kamar) : '' }}</span>
                            <h2 class="fw-bold mb-2" id="productName">
                                {{ isset($kamars[0]) ? $kamars[0]->nomor_kamar : 'Kamar Belum Tersedia' }}
                            </h2>
                            <p class="text-muted" id="productDesc">
                                {{ isset($kamars[0]) ? $kamars[0]->deskripsi : 'Belum ada deskripsi kamar' }}
                            </p>
                            <p class="fw-bold text-success" id="productHarga">
                                Rp {{ isset($kamars[0]) ? number_format($kamars[0]->harga_per_bulan, 0, ',', '.') : '-' }}
                                / bulan
                            </p>
                            <a href="{{ url('/product') }}" class="btn btn-primary mt-2">Lihat Semua Kamar</a>
                        </div>
                    </div>

                    <!-- Carousel Gambar (semua foto dari galeri per kamar) -->
                    <div class="col-md-7">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel"
                            data-bs-interval="3500">
                            <div class="carousel-inner rounded shadow">
                                @php $prodIndex = 0; @endphp
                                @forelse ($kamars as $kamar)
                                    @forelse ($kamar->galeri as $foto)
                                        <div class="carousel-item {{ $prodIndex === 0 ? 'active' : '' }}"
                                            data-kamar-nama="{{ $kamar->nomor_kamar }}"
                                            data-kamar-desc="{{ $kamar->deskripsi }}"
                                            data-kamar-tipe="{{ ucfirst($kamar->tipe_kamar) }}"
                                            data-kamar-harga="Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }} / bulan">
                                            <img src="{{ asset('storage/' . $foto->url_foto) }}" class="d-block w-100"
                                                style="height: 350px; object-fit: cover;"
                                                alt="{{ $kamar->nomor_kamar }}">
                                        </div>
                                        @php $prodIndex++; @endphp
                                    @empty
                                        <div class="carousel-item {{ $prodIndex === 0 ? 'active' : '' }}"
                                            data-kamar-nama="{{ $kamar->nomor_kamar }}"
                                            data-kamar-desc="{{ $kamar->deskripsi }}"
                                            data-kamar-tipe="{{ ucfirst($kamar->tipe_kamar) }}"
                                            data-kamar-harga="Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }} / bulan">
                                            <img src="{{ asset('img/kamira.png') }}" class="d-block w-100"
                                                style="height: 350px; object-fit: cover;" alt="Default">
                                        </div>
                                        @php $prodIndex++; @endphp
                                    @endforelse
                                @empty
                                    <div class="carousel-item active">
                                        <img src="{{ asset('img/kamira.png') }}" class="d-block w-100"
                                            style="height: 350px; object-fit: cover;" alt="Default">
                                    </div>
                                @endforelse
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>

                            <!-- Indicator dots -->
                            <div class="carousel-indicators">
                                @php $dotIdx = 0; @endphp
                                @foreach ($kamars as $kamar)
                                    @foreach ($kamar->galeri as $foto)
                                        <button type="button" data-bs-target="#productCarousel"
                                            data-bs-slide-to="{{ $dotIdx }}"
                                            class="{{ $dotIdx === 0 ? 'active' : '' }}"
                                            aria-label="Slide {{ $dotIdx + 1 }}"></button>
                                        @php $dotIdx++; @endphp
                                    @endforeach
                                    @if ($kamar->galeri->isEmpty())
                                        <button type="button" data-bs-target="#productCarousel"
                                            data-bs-slide-to="{{ $dotIdx }}"
                                            class="{{ $dotIdx === 0 ? 'active' : '' }}"
                                            aria-label="Slide {{ $dotIdx + 1 }}"></button>
                                        @php $dotIdx++; @endphp
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- JS: Sinkronisasi teks dengan slide aktif --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.querySelector('#productCarousel');
                if (!carousel) return;

                carousel.addEventListener('slide.bs.carousel', function(e) {
                    const nextSlide = carousel.querySelectorAll('.carousel-item')[e.to];
                    if (!nextSlide) return;

                    document.getElementById('productName').textContent = nextSlide.dataset.kamarNama || '';
                    document.getElementById('productDesc').textContent = nextSlide.dataset.kamarDesc || '';
                    document.getElementById('productTipe').textContent = nextSlide.dataset.kamarTipe || '';
                    document.getElementById('productHarga').textContent = nextSlide.dataset.kamarHarga || '';
                });
            });
        </script>

        <!-- Testimonial section-->
        <div class="py-5 my-5 bg-light slide-in">
            <div class="container px-5 my-3">
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-10 col-xl-7">
                        <div class="text-center">
                            <h2 class="fw-bolder">Tentang Kami</h2>
                            <div class="fs-5 mb-4 fst-italic">"D'Kost hadir untuk memberikan solusi tempat tinggal yang
                                nyaman, aman, dan terjangkau.
                                Kami menyediakan berbagai pilihan kamar kos dengan fasilitas lengkap di lokasi strategis.
                                Dengan sistem pemesanan online yang mudah, transparansi harga, dan layanan pelanggan 24/7,
                                kami berkomitmen untuk memudahkan Anda menemukan kos impian."</div>
                            <div class="d-flex align-items-center justify-content-center">
                                <img class="rounded-circle me-3" style="width: 40px; height:40px; object-fit:cover;"
                                    src="{{ asset('/img/Frieren.jpeg') }}" alt="Team Profile" />
                                <div class="fw-bold">
                                    Tim D'Kost
                                    <span class="fw-bold text-primary mx-1">/</span>
                                    Solusi Kos Modern
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog preview section-->
        <section class="py-5 my-5 slide-in">
            <div class="px-5 slide-in">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-xl-6">
                        <h2 class="fw-bolder">Kos Terbaik</h2>
                        <p class="lead fw-normal text-muted mb-5">Kos terbaik berdasarkan rating pengguna</p>
                    </div>
                    <div class="col text-end">
                        <i class="bi bi-arrow-left-circle-fill fs-2 me-2" id="prevBtn" style="cursor: pointer;"></i>
                        <i class="bi bi-arrow-right-circle-fill fs-2" id="nextBtn" style="cursor: pointer;"></i>
                    </div>
                </div>

                <div id="carouselContainer" class="d-flex overflow-hidden">
                    @foreach ($kamars as $kamar)
                        @php
                            $image = $kamar->galeri->first();
                            $imageUrl = $image ? asset('storage/' . $image->url_foto) : null;
                        @endphp

                        <div class="product-card me-3 mx-3 mb-5" style="flex: 0 0 22%;">
                            <div class="card h-100 shadow-sm">
                                @if ($imageUrl)
                                    <img class="card-img-top rounded" src="{{ $imageUrl }}"
                                        style="height: 200px; object-fit: cover ;" alt="{{ $kamar->nomor_kamar }}">
                                @else
                                    <img class="card-img-top rounded" src="{{ asset('/img/room-default.jpg') }}"
                                        style="height: 200px; object-fit: cover;" alt="Default Kos Image">
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title fw-bold mb-0 fs-6">{{ $kamar->nomor_kamar }}</h5>
                                        @php
                                            $ratingVal = $kamar->rating ?? 0;
                                            $reviewCount = $kamar->reviews->count();
                                        @endphp
                                        <span class="badge bg-primary" title="{{ $reviewCount }} ulasan">
                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                            {{ $ratingVal > 0 ? $ratingVal : '-' }}
                                            @if ($reviewCount > 0)
                                                <small class="ms-1 text-white-50">({{ $reviewCount }})</small>
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-geo-alt-fill"></i> {{ $kamar->lokasi ?? 'Bondowoso' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Call to action-->
                <div id="android-download" class="py-5 px-3 px-md-5">
                    <div class="container-fluid px-0 px-md-4">
                        <div class="row align-items-center g-4">
                            <!-- Kiri - Teks -->
                            <div class="col-lg-6 col-md-7 mb-4 mb-md-0">
                                <div class="pe-0 pe-md-5">
                                    <h3 class="fw-bold mb-3">Download Sekarang juga !</h3>
                                    <h3 class="fw-bold mb-3">
                                        <span class="text-primary">Pembayaran</span> bisa lewat sini
                                    </h3>
                                    <p class="text-muted mb-0" style="max-width: 90%;">
                                        Pembayaran bisa menggunakan Aplikasi kami pada tombol download di samping →
                                    </p>
                                </div>
                            </div>

                            <!-- Kanan - Card Download -->
                            <div class="col-lg-6 col-md-5">
                                <div class="d-flex justify-content-md-end justify-content-center">
                                    <div class="card download-card border-0 shadow-lg p-4"
                                        style="background: linear-gradient(135deg, #00AB6B, #008C56); max-width: 400px; width: 100%;">
                                        <div class="d-flex flex-column flex-sm-row align-items-center gap-4">
                                            <!-- Kiri: Teks -->
                                            <div class="text-center text-sm-start text-white flex-grow-1">
                                                <h5 class="fw-bold text-white mb-2">For Android</h5>
                                                <p class="mb-3 text-white-50 small">Android 8.0+</p>
                                                <a href="{{ url('downloads/Healthy.pdf') }}"
                                                    class="btn btn-light px-4 py-2 fw-bold"
                                                    style="color: #00AB6B; border-radius: 8px;" download>
                                                    <i class="bi bi-download me-2"></i>Download
                                                </a>
                                            </div>

                                            <!-- Kanan: QR Code -->
                                            <div class="text-center">
                                                <div class="bg-white p-2 rounded-3 shadow-sm">
                                                    <img src="{{ asset('img/qrcode.png') }}" alt="QR Code"
                                                        class="img-fluid" style="max-width: 100px; height: auto;">
                                                </div>
                                                <p class="text-white-50 small mt-2 mb-0">Scan QR</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            </div>
        </section>
    </main>

    @include('templates.main_footer')

    @include('templates.footer')
