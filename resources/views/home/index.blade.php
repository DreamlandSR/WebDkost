@extends('templates.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/override.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">  {{-- ✅ GANTI ke landingpage.css --}}
@endsection

@section('content')
    @include('templates.header')
    @include('templates.navbar')

    <!-- ✅ TAMBAHKAN SCROLL CONTAINER (WAJIB!) -->
    <div id="scrollContainer" class="scroll-container">
        
        <!-- Section 1: Hero -->
        <section class="full-page-section" id="section0">
            <div class="container px-5">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 animate-left">
                        <span class="badge bg-success mb-3 px-3 py-2 animate-fade-up">Welcome to D'Kost</span>
                        <h1 class="display-4 fw-bolder mb-4">Temukan <span class="text-success">Kos Impian</span> Anda Sekarang</h1>
                        <p class="lead mb-4" style="font-size: 1.2rem; font-weight: 500; color: #2c3e50; line-height: 1.8;">
                            D'Kost merupakan platform pemesanan kos online dan terpercaya. 
                            Dilengkapi fasilitas lengkap dengan harga terjangkau untuk semua kalangan.
                        </p>
                        <div class="d-flex gap-3">
                            <a class="btn btn-modern text-white px-5 py-3" href="{{ url('/login') }}">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Mulai Sekarang
                            </a>
                            <a class="btn btn-outline-modern px-5 py-3" href="{{ url('/about') }}">
                                <i class="bi bi-play-circle me-2"></i>Selengkapnya
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-6 animate-right">
                        <div class="position-relative">
                            <div class="float-animation">
                                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                                    <div class="carousel-inner rounded-4 shadow-lg">
                                        @php $headerIndex = 0; @endphp
                                        @forelse ($kamars as $kamar)
                                            @foreach ($kamar->galeri as $foto)
                                                <div class="carousel-item {{ $headerIndex === 0 ? 'active' : '' }}">
                                                    <div class="landing-image-wrapper">
                                                        <img src="{{ asset('storage/' . $foto->url_foto) }}"
                                                            class="d-block w-100 rounded-4 landing-section-image"
                                                            alt="{{ $kamar->nomor_kamar }}">
                                                    </div>
                                                    <div class="carousel-caption d-block glass-effect">
                                                        <h5 class="fw-bold">{{ $kamar->nomor_kamar }}</h5>
                                                        <p class="mb-0">{{ Str::limit($kamar->deskripsi, 70) }}</p>
                                                    </div>
                                                </div>
                                                @php $headerIndex++; @endphp
                                            @endforeach
                                        @empty
                                            <div class="carousel-item active">
                                                <div class="landing-image-wrapper">
                                                    <img src="{{ asset('img/kamira.png') }}" class="d-block w-100 rounded-4 landing-section-image" alt="Default Kamar">
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon bg-success rounded-circle p-3" aria-hidden="true"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                                        <span class="carousel-control-next-icon bg-success rounded-circle p-3" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Features -->
        <section class="full-page-section bg-light" id="section1">
            <div class="container px-5">
                <div class="text-center mb-5 animate-fade-up">
                    <span class="badge bg-success mb-3 px-3 py-2">Fitur Unggulan</span>
                    <h2 class="fw-bolder">Kenapa Memilih D'Kost?</h2>
                    <p class="lead mb-4" style="font-size: 1.2rem; font-weight: 500; color: #2c3e50; line-height: 1.8;">Kami menyediakan layanan terbaik untuk kenyamanan Anda</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6 animate-fade-up" style="animation-delay: 0.1s">
                        <div class="modern-card p-4 text-center h-100">
                            <div class="feature-icon bg-success bg-gradient text-white rounded-3 mb-4 d-inline-flex p-3">
                                <i class="bi bi-house-door-fill fs-1"></i>
                            </div>
                            <h3 class="h5 fw-bold">Kamar Kos</h3>
                            <p class="mb-0 text-muted">Menyediakan berbagai macam kamar kos dari yang terjangkau hingga termewah</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 animate-fade-up" style="animation-delay: 0.2s">
                        <div class="modern-card p-4 text-center h-100">
                            <div class="feature-icon bg-success bg-gradient text-white rounded-3 mb-4 d-inline-flex p-3">
                                <i class="bi bi-building fs-1"></i>
                            </div>
                            <h3 class="h5 fw-bold">Profil Usaha</h3>
                            <p class="mb-0 text-muted">Visi dan Misi kami sebagai penyedia kos terpercaya</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 animate-fade-up" style="animation-delay: 0.3s">
                        <div class="modern-card p-4 text-center h-100">
                            <div class="feature-icon bg-success bg-gradient text-white rounded-3 mb-4 d-inline-flex p-3">
                                <i class="bi bi-graph-up fs-1"></i>
                            </div>
                            <h3 class="h5 fw-bold">Laporan Keuangan</h3>
                            <p class="mb-0 text-muted">Laporan keuangan bulanan yang terdata dengan jelas</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 animate-fade-up" style="animation-delay: 0.4s">
                        <div class="modern-card p-4 text-center h-100">
                            <div class="feature-icon bg-success bg-gradient text-white rounded-3 mb-4 d-inline-flex p-3">
                                <i class="bi bi-phone fs-1"></i>
                            </div>
                            <h3 class="h5 fw-bold">Akses Mudah</h3>
                            <p class="mb-0 text-muted">Kemudahan akses untuk pemesanan dan pembayaran</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Featured Rooms -->
        <section class="full-page-section" id="section2">
            <div class="container px-5">
                <div class="text-center mb-5 animate-fade-up">
                    <span class="badge bg-success mb-3 px-3 py-2">Kamar Andalan</span>
                    <h2 class="fw-bolder">Kamar Populer</h2>
                    <p class="lead mb-4" style="font-size: 1.2rem; font-weight: 500; color: #2c3e50; line-height: 1.8;">Pilihan terbaik dengan fasilitas premium</p>
                </div>
                <div class="row align-items-center g-5">
                    <div class="col-md-5 animate-left">
                        <div id="productText">
                            <span class="badge bg-success mb-3 px-3 py-2" id="productTipe">
                                {{ isset($kamars[0]) ? ucfirst($kamars[0]->tipe_kamar) : 'Standard' }}
                            </span>
                            <h2 class="fw-bold mb-3" id="productName">
                                {{ isset($kamars[0]) ? $kamars[0]->nomor_kamar : 'Kamar Belum Tersedia' }}
                            </h2>
                            <p class="text-muted mb-4" id="productDesc">
                                {{ isset($kamars[0]) ? $kamars[0]->deskripsi : 'Belum ada deskripsi kamar' }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <p class="fw-bold text-success fs-3 mb-0" id="productHarga">
                                    Rp {{ isset($kamars[0]) ? number_format($kamars[0]->harga_per_bulan, 0, ',', '.') : '-' }}
                                </p>
                            </div>
                            <a href="{{ url('/product') }}" class="btn btn-modern text-white px-4">
                                Lihat Semua Kamar <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-7 animate-right">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
                            <div class="carousel-inner rounded-4 shadow-lg">
                                @php $prodIndex = 0; @endphp
                                @forelse ($kamars as $kamar)
                                    @forelse ($kamar->galeri as $foto)
                                        <div class="carousel-item {{ $prodIndex === 0 ? 'active' : '' }}"
                                            data-kamar-nama="{{ $kamar->nomor_kamar }}"
                                            data-kamar-desc="{{ $kamar->deskripsi }}"
                                            data-kamar-tipe="{{ ucfirst($kamar->tipe_kamar) }}"
                                            data-kamar-harga="Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}">
                                            <div class="landing-image-wrapper">
                                                <img src="{{ asset('storage/' . $foto->url_foto) }}" class="d-block w-100 rounded-4 landing-section-image" alt="{{ $kamar->nomor_kamar }}">
                                            </div>
                                        </div>
                                        @php $prodIndex++; @endphp
                                    @empty
                                        <div class="carousel-item {{ $prodIndex === 0 ? 'active' : '' }}"
                                            data-kamar-nama="{{ $kamar->nomor_kamar }}"
                                            data-kamar-desc="{{ $kamar->deskripsi }}"
                                            data-kamar-tipe="{{ ucfirst($kamar->tipe_kamar) }}"
                                            data-kamar-harga="Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}">
                                            <div class="landing-image-wrapper">
                                                <img src="{{ asset('img/kamira.png') }}" class="d-block w-100 rounded-4 landing-section-image" alt="Default">
                                            </div>
                                        </div>
                                        @php $prodIndex++; @endphp
                                    @endforelse
                                @empty
                                    <div class="carousel-item active">
                                        <div class="landing-image-wrapper">
                                            <img src="{{ asset('img/kamira.png') }}" class="d-block w-100 rounded-4 landing-section-image" alt="Default">
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-success rounded-circle p-3"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-success rounded-circle p-3"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Testimonials -->
        <section class="full-page-section bg-light" id="section3">
            <div class="container px-5">
                <div class="text-center mb-5 animate-fade-up">
                    <span class="badge bg-success mb-3 px-3 py-2">Testimoni</span>
                    <h2 class="fw-bolder">Apa Kata Mereka?</h2>
                    <p class="lead text-muted">Pengalaman dari pelanggan yang sudah tinggal di D'Kost</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-4 animate-fade-up" style="animation-delay: 0.1s">
                        <div class="modern-card p-4 h-100">
                            <div class="d-flex mb-3">
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                            <p class="mb-3">"Layanan sangat memuaskan, kamar bersih dan nyaman. Lokasi strategis dekat dengan kampus."</p>
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" 
                                    src="{{ asset('/img/room-modern-1.png') }}" alt="User">
                                <div>
                                    <h6 class="fw-bold mb-0">Ahmad Rizki</h6>
                                    <small class="text-muted">Mahasiswa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 animate-fade-up" style="animation-delay: 0.2s">
                        <div class="modern-card p-4 h-100">
                            <div class="d-flex mb-3">
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                            <p class="mb-3">"Fasilitas lengkap, harga terjangkau, dan sistem pemesanan online yang sangat mudah digunakan."</p>
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" 
                                    src="{{ asset('/img/room-modern-1.png') }}" alt="User">
                                <div>
                                    <h6 class="fw-bold mb-0">Siti Nurhaliza</h6>
                                    <small class="text-muted">Karyawan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 animate-fade-up" style="animation-delay: 0.3s">
                        <div class="modern-card p-4 h-100">
                            <div class="d-flex mb-3">
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                            </div>
                            <p class="mb-3">"Security bagus, lingkungan bersih, dan pelayanan customer service yang responsif 24/7."</p>
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" 
                                    src="{{ asset('/img/room-modern-1.png') }}" alt="User">
                                <div>
                                    <h6 class="fw-bold mb-0">Budi Santoso</h6>
                                    <small class="text-muted">Pengusaha</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 5: About & Download -->
        <section class="full-page-section" id="section4">
            <div class="container px-5">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6 animate-left">
                        <span class="badge bg-success mb-3 px-3 py-2">Tentang Kami</span>
                        <h2 class="fw-bolder mb-4">D'Kost<br><span class="text-success">Solusi Kos Modern</span></h2>
                        <p>
                            D'Kost hadir untuk memberikan solusi tempat tinggal yang nyaman, aman, dan terjangkau.
                            Kami menyediakan berbagai pilihan kamar kos dengan fasilitas lengkap di lokasi strategis.
                        </p>
                        <div class="d-flex gap-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                                <span>Sistem Online</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                                <span>Transparansi Harga</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                                <span>Layanan 24/7</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-modern text-white">
                                <i class="bi bi-play-circle me-2"></i>Pelajari Lebih
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-6 animate-right">
                        <div class="glass-effect p-5 text-center rounded-4">
                            <h3 class="fw-bold mb-3">Download Aplikasi Kami</h3>
                            <p class="mb-4">Pembayaran bisa melalui aplikasi D'Kost</p>
                            <div class="row align-items-center g-4">
                                <div class="col-md-6">
                                    <a href="{{ url('downloads/Healthy.pdf') }}" class="btn btn-modern text-white w-100 py-3" download>
                                        <i class="bi bi-download me-2"></i>Download for Android
                                    </a>
                                    <small class="text-muted d-block mt-2">Android 8.0+</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-white p-3 rounded-3 d-inline-block">
                                        <img src="{{ asset('img/qrcode.png') }}" alt="QR Code" class="img-fluid" style="max-width: 120px;">
                                    </div>
                                    <small class="text-muted d-block mt-2">Scan QR Code</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ✅ TAMBAHKAN NAVIGATION DOTS (DIBUTUHKAN JS) -->
        <div class="nav-dots">
            <div class="nav-dot active"></div>
            <div class="nav-dot"></div>
            <div class="nav-dot"></div>
            <div class="nav-dot"></div>
            <div class="nav-dot"></div>
        </div>

        <!-- ✅ TAMBAHKAN PROGRESS BAR (DIBUTUHKAN JS) -->
        <div class="scroll-progress">
            <div id="scrollProgress"></div>
        </div>

    </div> {{-- ✅ TUTUP SCROLL CONTAINER --}}

    @include('templates.main_footer')
    @include('templates.footer')

    <!-- ✅ JAVASCRIPT YANG SUDAH DIPERBAIKI -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek semua elemen yang dibutuhkan
            const scrollContainer = document.getElementById('scrollContainer');
            const scrollProgress = document.getElementById('scrollProgress');
            const navDots = document.querySelectorAll('.nav-dot');
            const sections = document.querySelectorAll('.full-page-section');
            
            // Debug: cek apakah elemen ditemukan
            console.log('scrollContainer:', scrollContainer);
            console.log('scrollProgress:', scrollProgress);
            console.log('navDots count:', navDots.length);
            console.log('sections count:', sections.length);
            
            // VALIDASI - Jika scrollContainer tidak ada, STOP
            if (!scrollContainer) {
                console.error('ERROR: #scrollContainer tidak ditemukan!');
                return;
            }
            
            if (!scrollProgress) {
                console.error('ERROR: #scrollProgress tidak ditemukan!');
            }
            
            // Update scroll progress
            function updateScrollProgress() {
                if (!scrollProgress) return;
                const scrollTop = scrollContainer.scrollTop;
                const scrollHeight = scrollContainer.scrollHeight - scrollContainer.clientHeight;
                const progress = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
                scrollProgress.style.width = progress + '%';
            }

            // Update active nav dot
            function updateActiveDot() {
                if (navDots.length === 0) return;
                
                const scrollTop = scrollContainer.scrollTop;
                const windowHeight = scrollContainer.clientHeight;
                
                sections.forEach((section, index) => {
                    const sectionTop = section.offsetTop;
                    const sectionBottom = sectionTop + section.offsetHeight;
                    
                    if (scrollTop + windowHeight / 2 >= sectionTop && scrollTop + windowHeight / 2 < sectionBottom) {
                        navDots.forEach(dot => dot.classList.remove('active'));
                        if (navDots[index]) navDots[index].classList.add('active');
                    }
                });
            }

            // Scroll to section on dot click
            if (navDots.length > 0) {
                navDots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        if (sections[index]) {
                            sections[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });
            }

            // Add scroll event listeners
            scrollContainer.addEventListener('scroll', () => {
                updateScrollProgress();
                updateActiveDot();
            });

            // Initial calls
            updateScrollProgress();
            updateActiveDot();

            // Carousel text synchronization
            const carousel = document.querySelector('#productCarousel');
            if (carousel) {
                carousel.addEventListener('slide.bs.carousel', function(e) {
                    const nextSlide = carousel.querySelectorAll('.carousel-item')[e.to];
                    if (nextSlide) {
                        const productName = document.getElementById('productName');
                        const productDesc = document.getElementById('productDesc');
                        const productTipe = document.getElementById('productTipe');
                        const productHarga = document.getElementById('productHarga');
                        
                        if (productName) productName.textContent = nextSlide.dataset.kamarNama || '';
                        if (productDesc) productDesc.textContent = nextSlide.dataset.kamarDesc || '';
                        if (productTipe) productTipe.textContent = nextSlide.dataset.kamarTipe || '';
                        if (productHarga) productHarga.textContent = nextSlide.dataset.kamarHarga || '';
                    }
                });
            }

            // Intersection Observer for fade animations
            const fadeElements = document.querySelectorAll('.animate-fade-up, .animate-left, .animate-right, .animate-scale');
            if (fadeElements.length > 0) {
                const fadeObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translate(0) scale(1)';
                            fadeObserver.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });
                
                fadeElements.forEach(element => {
                    element.style.opacity = '0';
                    if (element.classList.contains('animate-fade-up')) {
                        element.style.transform = 'translateY(30px)';
                    } else if (element.classList.contains('animate-left')) {
                        element.style.transform = 'translateX(-50px)';
                    } else if (element.classList.contains('animate-right')) {
                        element.style.transform = 'translateX(50px)';
                    } else if (element.classList.contains('animate-scale')) {
                        element.style.transform = 'scale(0.9)';
                    }
                    fadeObserver.observe(element);
                });
            }
            
            console.log('✅ JavaScript initialized successfully');
        });
    </script>
@endsection