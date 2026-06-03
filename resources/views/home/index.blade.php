@extends('templates.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/override.css') }}">
@endsection

@section('content')

    @include('templates.header')
    @include('templates.navbar')

    <main class="flex-shrink-0 fade-in">

        <!-- Header-->
        <header class="py-5 hero-section">
            <div class="container-fluid px-4 px-md-5">
                <div class="row align-items-center moveup">
                    <div class="col-lg-5 col-xl-5">
                        <div class="my-2 text-start">
                            <h1 class="display-5 fw-bolder text-black mb-4">
                                <span id="typeTitle"></span><span class="typing-cursor">|</span>
                            </h1>
                            <p class="lead fw-normal text-black mb-5" style="font-size: 1rem;">
                                <span id="typeDesc"></span><span class="typing-cursor typing-cursor-desc" style="display:none;">|</span>
                            </p>
                            <div class="d-grid gap-3 d-sm-flex flex-wrap justify-content-sm-start hero-buttons" style="opacity: 0;">
                                <a class="btn btn-primary btn-lg px-4 me-sm-3" href="{{ url('/login') }}">Login</a>
                                <a class="btn btn-outline-green btn-lg px-4" href="{{ url('/about') }}">Selengkapnya</a>
                            </div>
                        </div>
                    </div>

                    <!-- carousel — tampil di lg ke atas -->
                    <div class="col-lg-7 col-xl-7 d-none d-lg-block text-center">
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
                                                class="d-block w-100 rounded"
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
                                            alt="Default Kamar">
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

        {{-- Typing Animation Script --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const titleEl = document.getElementById('typeTitle');
                const descEl = document.getElementById('typeDesc');
                const cursorTitle = document.querySelector('.typing-cursor');
                const cursorDesc = document.querySelector('.typing-cursor-desc');
                const buttons = document.querySelector('.hero-buttons');

                const titleText = "D'Kost";
                const descText = "D'Kost Merupakan Platform Untuk pemesanan kamar kos secara online dan terpercaya. Kamar Kos kami dilengkapi dengan fasilitas yang lengkap dengan harga yang terjangkau untuk semua kalangan";

                let i = 0, j = 0;

                function typeTitle() {
                    if (i < titleText.length) {
                        titleEl.textContent += titleText.charAt(i);
                        i++;
                        setTimeout(typeTitle, 90);
                    } else {
                        // Title done → hide title cursor, show desc cursor, start desc
                        cursorTitle.style.display = 'none';
                        cursorDesc.style.display = 'inline';
                        setTimeout(typeDesc, 300);
                    }
                }

                function typeDesc() {
                    if (j < descText.length) {
                        descEl.textContent += descText.charAt(j);
                        j++;
                        setTimeout(typeDesc, 18);
                    } else {
                        // Desc done → hide cursor, fade in buttons
                        cursorDesc.classList.add('typing-done');
                        if (buttons) {
                            buttons.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                            buttons.style.transform = 'translateY(10px)';
                            requestAnimationFrame(() => {
                                buttons.style.opacity = '1';
                                buttons.style.transform = 'translateY(0)';
                            });
                        }
                    }
                }

                // Start after a small delay
                setTimeout(typeTitle, 500);
            });
        </script>

        {{-- ═══════ Kamar Kos Andalan Kami ═══════ --}}
        <div class="py-5 slide-in" id="section-andalan" style = "height: auto;">
            <div class="container-fluid px-4 px-md-5 pb-5">
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

            <div class="container-fluid px-4 px-md-5 my-3">
                <div class="row align-items-center g-4">
                    <!-- Teks kamar — FIXED: tidak pakai JS yang bikin hilang-muncul -->
                    <div class="col-md-5 ps-md-5">
                        <div id="productText">
                            @if(isset($kamars[0]))
                                <span class="badge text-bg-success mb-3" id="productTipe">{{ ucfirst($kamars[0]->tipe_kamar) }}</span>
                                <h2 class="fw-bold mb-2" id="productName">{{ $kamars[0]->nomor_kamar }}</h2>
                                <p class="text-muted" id="productDesc">{{ $kamars[0]->deskripsi }}</p>
                                <p class="fw-bold text-success" id="productHarga">
                                    Rp {{ number_format($kamars[0]->harga_per_bulan, 0, ',', '.') }} / bulan
                                </p>
                            @else
                                <h2 class="fw-bold mb-2">Kamar Belum Tersedia</h2>
                                <p class="text-muted">Belum ada deskripsi kamar</p>
                            @endif
                            <a href="{{ url('/product') }}" class="btn btn-sub mt-2">Lihat Semua Kamar</a>
                        </div>
                    </div>

                    <!-- Carousel Gambar — FIXED: 1 slide per kamar, bukan per foto -->
                    <div class="col-md-7">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel"
                            data-bs-interval="3500">
                            <div class="carousel-inner rounded shadow">
                                @forelse ($kamars as $idx => $kamar)
                                    @php
                                        $foto = $kamar->galeri->first();
                                        $fotoUrl = $foto ? asset('storage/' . $foto->url_foto) : asset('img/kamira.png');
                                    @endphp
                                    <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}"
                                        data-kamar-nama="{{ $kamar->nomor_kamar }}"
                                        data-kamar-desc="{{ $kamar->deskripsi }}"
                                        data-kamar-tipe="{{ ucfirst($kamar->tipe_kamar) }}"
                                        data-kamar-harga="Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }} / bulan">
                                        <img src="{{ $fotoUrl }}" class="d-block w-100"
                                            alt="{{ $kamar->nomor_kamar }}">
                                    </div>
                                @empty
                                    <div class="carousel-item active">
                                        <img src="{{ asset('img/kamira.png') }}" class="d-block w-100"
                                            alt="Default">
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
                                @foreach ($kamars as $idx => $kamar)
                                    <button type="button" data-bs-target="#productCarousel"
                                        data-bs-slide-to="{{ $idx }}"
                                        class="{{ $idx === 0 ? 'active' : '' }}"
                                        aria-label="Slide {{ $idx + 1 }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- JS: Sinkronisasi teks dengan slide aktif — FIXED --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.querySelector('#productCarousel');
                if (!carousel) return;

                const nameEl = document.getElementById('productName');
                const descEl = document.getElementById('productDesc');
                const tipeEl = document.getElementById('productTipe');
                const hargaEl = document.getElementById('productHarga');

                if (!nameEl || !descEl) return;

                carousel.addEventListener('slid.bs.carousel', function(e) {
                    const slide = e.relatedTarget;
                    if (!slide) return;

                    nameEl.textContent = slide.dataset.kamarNama || '';
                    descEl.textContent = slide.dataset.kamarDesc || '';
                    if (tipeEl) tipeEl.textContent = slide.dataset.kamarTipe || '';
                    if (hargaEl) hargaEl.textContent = slide.dataset.kamarHarga || '';
                });
            });
        </script>

        <!-- Testimonial section-->
        <div class="py-5">
            <div class="container-fluid px-4 px-md-5 my-3">
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-10 col-xl-7">
                        <div class="text-center">
                            <h2 class="fw-bolder">Tentang Kami</h2>
                            <div class="fs-5 mb-4 fst-italic">"D'Kost hadir untuk memberikan solusi tempat tinggal yang
                                nyaman, aman, dan terjangkau.
                                Kami menyediakan berbagai pilihan kamar kos dengan fasilitas lengkap di lokasi strategis.
                                Dengan sistem pemesanan online yang mudah, transparansi harga, dan layanan pelanggan 24/7,
                                kami berkomitmen untuk memudahkan Anda menemukan Kamar kos impian."</div>
                            <div class="d-flex align-items-center justify-content-center">
                                <img class="rounded-circle me-3" style="width: 40px; height:40px; object-fit:cover;"
                                    src="{{ asset('/img/Frieren.jpeg') }}" alt="Team Profile"
                                    onerror="this.src='{{ asset('img/dkos_logo.png') }}'" />
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

        <!-- Kos Terbaik section — Modern Slider -->
        <section class="py-5 my-0 slide-in" id="section-terbaik">
            <div class="container-fluid px-4 px-md-5 slide-in">
                <div class="text-center mb-5">
                    <h2 class="fw-bolder">Kamar Terbaik</h2>
                    <p class="lead fw-normal text-muted">Kamar terbaik berdasarkan rating pengguna</p>
                </div>

                <div class="terbaik-slider-wrapper">
                    {{-- Navigation arrows --}}
                    <button class="ulasan-nav ulasan-nav-prev" id="terbaikPrev" aria-label="Previous room">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="ulasan-nav ulasan-nav-next" id="terbaikNext" aria-label="Next room">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    {{-- Track --}}
                    <div class="ulasan-slider-viewport">
                        <div class="ulasan-slider-track" id="terbaikTrack">
                            @foreach ($kamars as $kamar)
                                @php
                                    $image = $kamar->galeri->first();
                                    $imageUrl = $image ? asset('storage/' . $image->url_foto) : asset('img/room-default-1.png');
                                    $ratingVal = $kamar->rating ?? 0;
                                    $reviewCount = $kamar->reviews->count();
                                @endphp
                                <div class="ulasan-slide terbaik-slide">
                                    <div class="terbaik-card-modern">
                                        <div class="terbaik-img-wrapper">
                                            <img src="{{ $imageUrl }}" alt="{{ $kamar->nomor_kamar }}">
                                            <div class="terbaik-rating-badge">
                                                <i class="bi bi-star-fill"></i>
                                                {{ $ratingVal > 0 ? $ratingVal : '-' }}
                                            </div>
                                        </div>
                                        <div class="terbaik-card-body">
                                            <h5 class="terbaik-room-name">{{ $kamar->nomor_kamar }}</h5>
                                            <div class="terbaik-meta">
                                                <span><i class="bi bi-geo-alt-fill me-1"></i>{{ $kamar->lokasi ?? 'Bondowoso' }}</span>
                                                @if ($reviewCount > 0)
                                                    <span class="ulasan-date-sep">•</span>
                                                    <span>{{ $reviewCount }} ulasan</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Dot indicators --}}
                    <div class="ulasan-dots" id="terbaikDots"></div>
                </div>

                {{-- Terbaik Slider JS --}}
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const track = document.getElementById('terbaikTrack');
                    const viewport = track ? track.parentElement : null;
                    const prevBtn = document.getElementById('terbaikPrev');
                    const nextBtn = document.getElementById('terbaikNext');
                    const dotsContainer = document.getElementById('terbaikDots');
                    if (!track || !viewport) return;

                    const slides = track.querySelectorAll('.terbaik-slide');
                    if (slides.length === 0) return;

                    let currentIndex = 0;
                    let autoPlayTimer = null;
                    let slidesPerView = getSlidesPerView();

                    function getSlidesPerView() {
                        const w = window.innerWidth;
                        if (w >= 1200) return 4;
                        if (w >= 992) return 3;
                        if (w >= 768) return 2;
                        return 1;
                    }

                    function getMaxIndex() {
                        return Math.max(0, slides.length - slidesPerView);
                    }

                    function buildDots() {
                        dotsContainer.replaceChildren();
                        const total = getMaxIndex() + 1;
                        for (let i = 0; i < total; i++) {
                            const dot = document.createElement('button');
                            dot.className = 'ulasan-dot' + (i === currentIndex ? ' active' : '');
                            dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                            dot.addEventListener('click', function() {
                                goTo(i);
                                resetAutoPlay();
                            });
                            dotsContainer.appendChild(dot);
                        }
                    }

                    function updateDots() {
                        const dots = dotsContainer.querySelectorAll('.ulasan-dot');
                        dots.forEach(function(d, i) {
                            d.classList.toggle('active', i === currentIndex);
                        });
                    }

                    function goTo(index) {
                        const max = getMaxIndex();
                        currentIndex = Math.max(0, Math.min(index, max));
                        const slideWidth = slides[0].offsetWidth;
                        const gap = 24;
                        track.style.transform = 'translateX(-' + (currentIndex * (slideWidth + gap)) + 'px)';
                        updateDots();
                        updateNavButtons();
                    }

                    function updateNavButtons() {
                        if (prevBtn) prevBtn.classList.toggle('disabled', currentIndex === 0);
                        if (nextBtn) nextBtn.classList.toggle('disabled', currentIndex >= getMaxIndex());
                    }

                    function nextSlide() {
                        goTo(currentIndex >= getMaxIndex() ? 0 : currentIndex + 1);
                    }

                    function prevSlide() {
                        goTo(currentIndex <= 0 ? getMaxIndex() : currentIndex - 1);
                    }

                    function startAutoPlay() {
                        stopAutoPlay();
                        autoPlayTimer = setInterval(nextSlide, 4500);
                    }

                    function stopAutoPlay() {
                        if (autoPlayTimer) { clearInterval(autoPlayTimer); autoPlayTimer = null; }
                    }

                    function resetAutoPlay() {
                        stopAutoPlay();
                        startAutoPlay();
                    }

                    if (prevBtn) prevBtn.addEventListener('click', function() { prevSlide(); resetAutoPlay(); });
                    if (nextBtn) nextBtn.addEventListener('click', function() { nextSlide(); resetAutoPlay(); });

                    let touchStartX = 0;
                    viewport.addEventListener('touchstart', function(e) { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
                    viewport.addEventListener('touchend', function(e) {
                        const diff = touchStartX - e.changedTouches[0].screenX;
                        if (Math.abs(diff) > 50) {
                            if (diff > 0) nextSlide(); else prevSlide();
                            resetAutoPlay();
                        }
                    }, { passive: true });

                    viewport.addEventListener('mouseenter', stopAutoPlay);
                    viewport.addEventListener('mouseleave', startAutoPlay);

                    window.addEventListener('resize', function() {
                        slidesPerView = getSlidesPerView();
                        if (currentIndex > getMaxIndex()) currentIndex = getMaxIndex();
                        buildDots();
                        goTo(currentIndex);
                    });

                    buildDots();
                    goTo(0);
                    startAutoPlay();
                });
                </script>

                {{-- ═══════ ULASAN PENGHUNI — Slider Modern ═══════ --}}
                <div class="py-5 slide-in" id="section-ulasan">
                    <div class="text-center mb-5">
                        <h2 class="fw-bolder">Ulasan Penghuni</h2>
                        <p class="lead fw-normal text-muted">Apa kata mereka tentang pengalaman tinggal di D'Kost</p>
                    </div>

                    @php
                        $allReviews = collect();
                        foreach ($kamars as $k) {
                            foreach ($k->reviews as $rev) {
                                $rev->kamar_nama = $k->nomor_kamar;
                                $allReviews->push($rev);
                            }
                        }
                        $latestReviews = $allReviews
                            ->filter(fn($rev) => $rev->rating >= 4)
                            ->sortByDesc('tgl_review')
                            ->take(8)
                            ->values();
                    @endphp

                    @if($latestReviews->isNotEmpty())
                        {{-- Slider wrapper --}}
                        <div class="ulasan-slider-wrapper">
                            {{-- Navigation arrows --}}
                            <button class="ulasan-nav ulasan-nav-prev" id="ulasanPrev" aria-label="Previous review">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="ulasan-nav ulasan-nav-next" id="ulasanNext" aria-label="Next review">
                                <i class="bi bi-chevron-right"></i>
                            </button>

                            {{-- Track --}}
                            <div class="ulasan-slider-viewport">
                                <div class="ulasan-slider-track" id="ulasanTrack">
                                    @foreach ($latestReviews as $review)
                                        <div class="ulasan-slide">
                                            <div class="ulasan-card-modern">
                                                {{-- User info (top) --}}
                                                <div class="ulasan-user-info">
                                                    <div class="ulasan-avatar-ring">
                                                        <div class="ulasan-avatar-inner">
                                                            {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div class="ulasan-user-meta">
                                                        <h6 class="ulasan-user-name">{{ $review->user->name ?? 'Pengguna' }}</h6>
                                                        <div class="ulasan-user-detail">
                                                            <span><i class="bi bi-door-open-fill me-1"></i>{{ $review->kamar_nama }}</span>
                                                            @if($review->tgl_review)
                                                                <span class="ulasan-date-sep">•</span>
                                                                <span>{{ \Carbon\Carbon::parse($review->tgl_review)->format('d M Y') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Stars --}}
                                                <div class="ulasan-stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                    @endfor
                                                </div>

                                                {{-- Comment --}}
                                                <p class="ulasan-comment">
                                                    "{{ Str::limit($review->komentar, 250) }}"
                                                </p>

                                                {{-- Quote icon (decorative, bottom-right) --}}
                                                <div class="ulasan-quote-icon">
                                                    <i class="bi bi-quote"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Dot indicators --}}
                            <div class="ulasan-dots" id="ulasanDots"></div>
                        </div>

                        {{-- Slider JS --}}
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const track = document.getElementById('ulasanTrack');
                            const viewport = track ? track.parentElement : null;
                            const prevBtn = document.getElementById('ulasanPrev');
                            const nextBtn = document.getElementById('ulasanNext');
                            const dotsContainer = document.getElementById('ulasanDots');
                            if (!track || !viewport) return;

                            const slides = track.querySelectorAll('.ulasan-slide');
                            if (slides.length === 0) return;

                            let currentIndex = 0;
                            let autoPlayTimer = null;
                            let slidesPerView = getSlidesPerView();

                            function getSlidesPerView() {
                                const w = window.innerWidth;
                                if (w >= 1200) return 3;
                                if (w >= 992) return 2;
                                return 1;
                            }

                            function getMaxIndex() {
                                return Math.max(0, slides.length - slidesPerView);
                            }

                            function buildDots() {
                                // Safe DOM clearing without innerHTML
                                dotsContainer.replaceChildren();
                                const total = getMaxIndex() + 1;
                                for (let i = 0; i < total; i++) {
                                    const dot = document.createElement('button');
                                    dot.className = 'ulasan-dot' + (i === currentIndex ? ' active' : '');
                                    dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                                    dot.addEventListener('click', function() {
                                        goTo(i);
                                        resetAutoPlay();
                                    });
                                    dotsContainer.appendChild(dot);
                                }
                            }

                            function updateDots() {
                                const dots = dotsContainer.querySelectorAll('.ulasan-dot');
                                dots.forEach(function(d, i) {
                                    d.classList.toggle('active', i === currentIndex);
                                });
                            }

                            function goTo(index) {
                                const max = getMaxIndex();
                                currentIndex = Math.max(0, Math.min(index, max));
                                const slideWidth = slides[0].offsetWidth;
                                const gap = 24;
                                track.style.transform = 'translateX(-' + (currentIndex * (slideWidth + gap)) + 'px)';
                                updateDots();
                                updateNavButtons();
                            }

                            function updateNavButtons() {
                                if (prevBtn) prevBtn.classList.toggle('disabled', currentIndex === 0);
                                if (nextBtn) nextBtn.classList.toggle('disabled', currentIndex >= getMaxIndex());
                            }

                            function nextSlide() {
                                goTo(currentIndex >= getMaxIndex() ? 0 : currentIndex + 1);
                            }

                            function prevSlide() {
                                goTo(currentIndex <= 0 ? getMaxIndex() : currentIndex - 1);
                            }

                            function startAutoPlay() {
                                stopAutoPlay();
                                autoPlayTimer = setInterval(nextSlide, 5000);
                            }

                            function stopAutoPlay() {
                                if (autoPlayTimer) { clearInterval(autoPlayTimer); autoPlayTimer = null; }
                            }

                            function resetAutoPlay() {
                                stopAutoPlay();
                                startAutoPlay();
                            }

                            // Event listeners
                            if (prevBtn) prevBtn.addEventListener('click', function() { prevSlide(); resetAutoPlay(); });
                            if (nextBtn) nextBtn.addEventListener('click', function() { nextSlide(); resetAutoPlay(); });

                            // Touch/swipe support
                            let touchStartX = 0;
                            let touchEndX = 0;
                            viewport.addEventListener('touchstart', function(e) { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
                            viewport.addEventListener('touchend', function(e) {
                                touchEndX = e.changedTouches[0].screenX;
                                const diff = touchStartX - touchEndX;
                                if (Math.abs(diff) > 50) {
                                    if (diff > 0) nextSlide(); else prevSlide();
                                    resetAutoPlay();
                                }
                            }, { passive: true });

                            // Pause on hover
                            viewport.addEventListener('mouseenter', stopAutoPlay);
                            viewport.addEventListener('mouseleave', startAutoPlay);

                            // Responsive
                            window.addEventListener('resize', function() {
                                slidesPerView = getSlidesPerView();
                                if (currentIndex > getMaxIndex()) currentIndex = getMaxIndex();
                                buildDots();
                                goTo(currentIndex);
                            });

                            // Initialize
                            buildDots();
                            goTo(0);
                            startAutoPlay();
                        });
                        </script>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-chat-square-text fs-1 text-muted mb-3 d-block"></i>
                            <p class="text-muted">Belum ada ulasan dari penghuni.</p>
                        </div>
                    @endif
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
                                        Pembayaran bisa menggunakan Aplikasi kami pada tombol download <span class="download-dir-side">di samping →</span><span class="download-dir-below">di bawah ↓</span>
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
                                                <a href="{{ asset('downloads/dkost2.apk') }}"
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
        </section>
    </main>

    @include('templates.main_footer')

    @include('templates.footer')

@endsection
