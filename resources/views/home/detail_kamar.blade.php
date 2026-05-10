@extends('templates.layout')

@section('container_class', 'container-fluid p-0')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/override.css') }}">
@endsection

@section('content')
    @include('templates.header')
    @include('templates.navbar')

    @php
        /* ===== HELPER: Fasilitas Icon Map ===== */
        $fasilitasIcons = [
            'wifi' => 'bi-wifi',
            'wi-fi' => 'bi-wifi',
            'internet' => 'bi-wifi',
            'ac' => 'bi-snow2',
            'air conditioner' => 'bi-snow2',
            'kasur' => 'bi-bed',
            'tempat tidur' => 'bi-bed',
            'lemari' => 'bi-door-closed',
            'almari' => 'bi-door-closed',
            'meja' => 'bi-journal-bookmark-fill',
            'belajar' => 'bi-journal-bookmark-fill',
            'kamar mandi' => 'bi-droplet-fill',
            'toilet' => 'bi-droplet-fill',
            'dapur' => 'bi-egg-fried',
            'masak' => 'bi-egg-fried',
            'parkir' => 'bi-car-front-fill',
            'motor' => 'bi-bicycle',
            'sepeda' => 'bi-bicycle',
            'kulkas' => 'bi-box',
            'tv' => 'bi-tv-fill',
            'televisi' => 'bi-tv-fill',
            'listrik' => 'bi-lightning-charge-fill',
            'air bersih' => 'bi-water',
            'keamanan' => 'bi-shield-check',
            'cctv' => 'bi-camera-video-fill',
            'laundry' => 'bi-basket-fill',
            'cuci' => 'bi-basket-fill',
            'gym' => 'bi-heart-pulse-fill',
            'olahraga' => 'bi-heart-pulse-fill',
            'kolam' => 'bi-water',
            'balkon' => 'bi-house-door-fill',
            'taman' => 'bi-tree-fill',
        ];

        $ratingVal = $kamar->rating ?? 0;
        $reviewCount = $kamar->reviews->count();
        $bookingCount = $kamar->bookings->count();
        $fullStars = (int) floor($ratingVal);
        $hasHalf = $ratingVal - $fullStars >= 0.5;
        $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);

        $mainPhoto = $kamar->galeri->first();
        $mainImageUrl = $mainPhoto ? asset('storage/' . $mainPhoto->url_foto) : asset('img/room-default.jpg');

        /* Rating distribution per bintang (1–5) */
        $ratingDist = [];
        for ($s = 5; $s >= 1; $s--) {
            $ratingDist[$s] = $kamar->reviews->where('rating', $s)->count();
        }
    @endphp

    {{-- ===== HERO STRIP ===== --}}
    <section class="hero-section flex-shrink-0 position-relative border-bottom"
        style="background: linear-gradient(135deg, #00AB6B 0%, #008151 100%); overflow: hidden;">
        {{-- Dekorasi pola titik abstrak --}}
        <div class="position-absolute w-100 h-100"
            style="top:0; left:0; pointer-events:none; opacity: 0.08; background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.8) 1px, transparent 0); background-size: 24px 24px;">
        </div>

        <div class="container py-4 position-relative" style="z-index: 1;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                {{-- Judul Halaman --}}
                <div class="page-title-section text-white">
                    <h2 class="fw-bold mb-1 d-flex align-items-center"
                        style="font-size: 1.8rem; letter-spacing: -0.5px; text-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <i class="bi bi-door-open-fill text-white-50 me-2" style="font-size: 1.5rem;"></i> Detail Informasi
                        Kamar
                    </h2>
                    <p class="mb-0 text-white-50" style="font-size: 0.95rem; font-weight: 500;">
                        Informasi lengkap, fasilitas, dan ulasan kamar
                    </p>
                </div>

                {{-- Breadcrumb Glassmorphism --}}
                <nav aria-label="breadcrumb" class="d-inline-flex px-4 py-2 shadow-sm"
                    style="background: rgba(0, 0, 0, 0.2); backdrop-filter: blur(10px); border-radius: 30px; border: 1px solid rgba(255,255,255,0.25);"
                    <ol class="breadcrumb mb-0 align-items-center modern-breadcrumb"
                        style="font-size:0.88rem; font-weight:500;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('index') }}"
                                class="text-white text-decoration-none d-flex align-items-center gap-1"
                                style="opacity: 0.9; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'"
                                onmouseout="this.style.opacity='0.9'">
                                <i class="bi bi-house-door-fill"></i> Beranda
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('product') }}" class="text-white text-decoration-none"
                                style="opacity: 0.9; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'"
                                onmouseout="this.style.opacity='0.9'">Kamar</a>
                        </li>
                        <li class="breadcrumb-item active text-white fw-bold d-flex align-items-center gap-1"
                            aria-current="page">
                            <i class="bi bi-tag-fill ms-1" style="font-size:0.8rem; opacity:0.8;"></i>
                            {{ $kamar->nomor_kamar }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <main class="flex-shrink-0 bg-light pb-5">
        <div class="container py-4">
            <div class="row g-4">

                {{-- ===== KOLOM KIRI: Galeri + Info ===== --}}
                <div class="col-lg-8">

                    {{-- Galeri --}}
                    <div class="bg-white rounded-4 shadow-sm p-3 mb-4">
                        <img id="mainImage" src="{{ $mainImageUrl }}" alt="{{ $kamar->nomor_kamar }}">

                        @if ($kamar->galeri->count() > 1)
                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                @foreach ($kamar->galeri as $idx => $foto)
                                    <img src="{{ asset('storage/' . $foto->url_foto) }}"
                                        class="gallery-thumb {{ $idx === 0 ? 'active' : '' }}"
                                        alt="Foto {{ $idx + 1 }}"
                                        onclick="changeMainImage(this, '{{ asset('storage/' . $foto->url_foto) }}')">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Info Kamar --}}
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        {{-- Tipe + Jumlah Pemesanan --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge"
                                style="background-color:rgba(0,171,107,0.12);color:#00AB6B;font-size:0.78rem;padding:5px 10px;border-radius:20px;">
                                {{ ucfirst($kamar->tipe_kamar) }}
                            </span>
                            @if ($bookingCount > 0)
                                <span class="text-muted" style="font-size:0.78rem;">
                                    <i class="bi bi-people-fill me-1 text-success"></i>{{ $bookingCount }}x dipesan
                                </span>
                            @endif
                        </div>

                        <h1 class="fw-bold mb-1" style="font-size:1.7rem;color:#1a202c;">{{ $kamar->nomor_kamar }}</h1>

                        {{-- Rating ringkas --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="text-warning">
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="bi bi-star-fill"></i>
                                @endfor
                                @if ($hasHalf)
                                    <i class="bi bi-star-half"></i>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="bi bi-star"></i>
                                @endfor
                            </div>
                            <span class="fw-bold">{{ $ratingVal > 0 ? $ratingVal : '-' }}</span>
                            <span class="text-muted small">
                                @if ($reviewCount > 0)
                                    ({{ $reviewCount }} ulasan)
                                @else
                                    Belum ada ulasan
                                @endif
                            </span>
                        </div>

                        {{-- Lokasi --}}
                        @if ($kamar->lokasi)
                            <p class="text-muted mb-3">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $kamar->lokasi }}
                            </p>
                        @endif

                        <hr class="my-3" style="border-color:#f0f0f0;">

                        {{-- Deskripsi --}}
                        <h5 class="fw-bold mb-2" style="color:#2d3748;">Deskripsi Kamar</h5>
                        <p class="text-muted lh-lg" style="font-size:0.92rem;">
                            {{ $kamar->deskripsi ?? 'Belum ada deskripsi untuk kamar ini.' }}
                        </p>
                    </div>

                    {{-- ===== Fasilitas ===== --}}
                    @if ($kamar->fasilitas->isNotEmpty())
                        <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                            <h5 class="fw-bold mb-3" style="color:#2d3748;">
                                <i class="bi bi-grid-3x3-gap-fill me-2 text-success"></i>Fasilitas Kamar
                            </h5>
                            <div class="row g-2">
                                @foreach ($kamar->fasilitas as $fas)
                                    @php
                                        $namaLower = strtolower($fas->nama_fasilitas);
                                        $icon = 'bi-check-circle';
                                        foreach ($fasilitasIcons as $keyword => $iconClass) {
                                            if (str_contains($namaLower, $keyword)) {
                                                $icon = $iconClass;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <div class="col-6 col-md-4">
                                        <div class="fas-item">
                                            <i class="bi {{ $icon }}"></i>
                                            <span>{{ $fas->nama_fasilitas }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ===== Ulasan / Review ===== --}}
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold mb-4" style="color:#2d3748;">
                            <i class="bi bi-chat-square-quote-fill me-2 text-success"></i>Ulasan Pengguna
                        </h5>

                        @if ($reviewCount > 0)
                            {{-- Ringkasan rating --}}
                            <div class="d-flex align-items-center gap-4 mb-4 p-3 rounded-3"
                                style="background:#f8fffe;border:1px solid #e2f5ed;">
                                <div class="text-center">
                                    <div style="font-size:3rem;font-weight:800;color:#00AB6B;line-height:1;">
                                        {{ $ratingVal }}
                                    </div>
                                    <div class="text-warning my-1">
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <i class="bi bi-star-fill"></i>
                                        @endfor
                                        @if ($hasHalf)
                                            <i class="bi bi-star-half"></i>
                                        @endif
                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <i class="bi bi-star"></i>
                                        @endfor
                                    </div>
                                    <div class="text-muted small">{{ $reviewCount }} ulasan</div>
                                </div>
                                <div class="flex-grow-1">
                                    @foreach ($ratingDist as $star => $cnt)
                                        @php $pct = $reviewCount > 0 ? ($cnt / $reviewCount * 100) : 0; @endphp
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="text-muted small" style="width:14px;">{{ $star }}</span>
                                            <i class="bi bi-star-fill text-warning" style="font-size:0.7rem;"></i>
                                            <div class="rating-bar-bg">
                                                <div class="rating-bar-fill" style="width:{{ $pct }}%;"></div>
                                            </div>
                                            <span class="text-muted small" style="width:18px;">{{ $cnt }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- List ulasan (Maksimal 3) --}}
                            @foreach ($kamar->reviews->take(3) as $review)
                                @php
                                    $initials = strtoupper(substr($review->user->nama ?? 'A', 0, 1));
                                    $dateField = $review->tgl_review ?? ($review->created_at ?? now());
                                    $diffDays = (int) abs(now()->diffInDays(\Carbon\Carbon::parse($dateField)));
                                    $timeAgo =
                                        $diffDays === 0
                                            ? 'Hari ini'
                                            : ($diffDays < 30
                                                ? $diffDays . ' hari yang lalu'
                                                : floor($diffDays / 30) . ' bulan yang lalu');
                                @endphp
                                <div class="review-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-placeholder">{{ $initials }}</div>
                                            <div>
                                                <div class="fw-bold" style="font-size:0.92rem;">
                                                    {{ $review->user->nama ?? 'Pengguna' }}
                                                </div>
                                                <div class="text-muted small">{{ $timeAgo }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge"
                                                style="background:#fff8e1;color:#f59e0b;border:1px solid #fde68a;border-radius:20px;padding:4px 10px;">
                                                <i class="bi bi-star-fill me-1"></i>{{ $review->rating }}
                                            </span>
                                        </div>
                                    </div>
                                    @if (!empty($review->komentar))
                                        <p class="mb-0 mt-3 text-muted lh-lg" style="font-size:0.88rem;">
                                            {{ $review->komentar }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach

                            @if ($reviewCount > 3)
                                <button class="btn btn-outline-success w-100 fw-bold mt-3"
                                    style="border-radius:12px; padding:12px; border-width: 1.5px;" data-bs-toggle="modal"
                                    data-bs-target="#modalSemuaUlasan">
                                    Lihat Semua Ulasan ({{ $reviewCount }})
                                </button>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-chat-square fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Belum ada ulasan untuk kamar ini.</p>
                            </div>
                        @endif
                    </div>

                </div>{{-- /kolom kiri --}}

                {{-- ===== KOLOM KANAN: Booking Card ===== --}}
                <div class="col-lg-4">
                    <div class="booking-card bg-white rounded-4 shadow-sm p-4">

                        {{-- Harga --}}
                        <div class="mb-3">
                            <div class="price-display">
                                Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}
                            </div>
                            <span class="text-muted small">/ bulan</span>
                        </div>

                        <hr style="border-color:#f0f0f0;">

                        {{-- Detail singkat --}}
                        <ul class="list-unstyled mb-4" style="font-size:0.88rem;">
                            <li class="d-flex justify-content-between py-1 border-bottom"
                                style="border-color:#f5f5f5!important;">
                                <span class="text-muted">Tipe Kamar</span>
                                <span class="fw-semibold">{{ ucfirst($kamar->tipe_kamar) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom"
                                style="border-color:#f5f5f5!important;">
                                <span class="text-muted">Lokasi</span>
                                <span class="fw-semibold">{{ $kamar->lokasi ?? 'Bondowoso' }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom"
                                style="border-color:#f5f5f5!important;">
                                <span class="text-muted">Status</span>
                                <span class="fw-semibold">
                                    @if (($kamar->status ?? 'tersedia') === 'tersedia')
                                        <span class="text-success"><i class="bi bi-circle-fill me-1"
                                                style="font-size:0.5rem;"></i>Tersedia</span>
                                    @else
                                        <span class="text-danger"><i class="bi bi-circle-fill me-1"
                                                style="font-size:0.5rem;"></i>Penuh</span>
                                    @endif
                                </span>
                            </li>
                        </ul>

                        {{-- Lama Sewa --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:0.85rem;color:#4a5568;">
                                <i class="bi bi-calendar3 me-1 text-success"></i>Lama Sewa
                            </label>
                            <select id="durasi" class="form-select" data-harga="{{ (int) $kamar->harga_per_bulan }}"
                                style="border-radius:10px; border:1.5px solid #e2e8f0; font-size:0.9rem; height:46px;">
                                <option value="1">1 Bulan</option>
                                <option value="3">3 Bulan</option>
                                <option value="6">6 Bulan</option>
                                <option value="12">12 Bulan (1 Tahun)</option>
                            </select>
                        </div>

                        {{-- Total Estimasi --}}
                        <div class="p-3 rounded-3 mb-4"
                            style="background:rgba(0,171,107,0.07); border:1px dashed #00AB6B;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-size:0.85rem;color:#4a5568;">Estimasi Total</span>
                                <span id="totalHarga" class="fw-bold text-success" style="font-size:1.1rem;">
                                    Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Pesan --}}
                        <a href="{{ route('index') }}#android-download" class="btn w-100 fw-bold py-3"
                            style="background:#00AB6B;color:white;border-radius:12px;font-size:1rem;letter-spacing:0.3px;">
                            <i class="bi bi-download me-2"></i>Unduh Aplikasi untuk Pesan
                        </a>

                        <p class="text-center text-muted mt-3 mb-0" style="font-size:0.78rem;">
                            <i class="bi bi-shield-check me-1 text-success"></i>Pembayaran aman &amp; terjamin
                        </p>
                    </div>
                </div>{{-- /kolom kanan --}}

            </div>{{-- /row --}}

            {{-- ===== REKOMENDASI KAMAR LAIN ===== --}}
            @if ($rekomendasi->isNotEmpty())
                <div class="mt-5">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold mb-0" style="color:#2d3748;">
                            <i class="bi bi-house-heart-fill me-2 text-success"></i>Kamar Lainnya
                        </h5>
                        <a href="{{ route('product') }}" class="text-success text-decoration-none small fw-semibold">
                            Lihat semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>

                    @php
                        $rekomFasicons = $fasilitasIcons;
                    @endphp

                    <div class="row g-3">
                        @foreach ($rekomendasi as $rek)
                            @php
                                $rekPhoto = $rek->galeri->first();
                                $rekImageUrl = $rekPhoto
                                    ? asset('storage/' . $rekPhoto->url_foto)
                                    : asset('img/room-default.jpg');
                                $rekRating = $rek->rating ?? 0;
                                $rekReviews = $rek->reviews->count();
                                $rekFasShown = $rek->fasilitas->take(2);
                                $rekFasExtra = max(0, $rek->fasilitas->count() - 2);
                            @endphp
                            <div class="col-md-4">
                                <div class="card rekomen-card shadow-sm h-100">
                                    <img src="{{ $rekImageUrl }}" class="card-img-top"
                                        style="height:160px;object-fit:cover;" alt="{{ $rek->nomor_kamar }}">
                                    <div class="card-body d-flex flex-column p-3">
                                        <span class="badge mb-1 align-self-start"
                                            style="background:rgba(0,171,107,0.12);color:#00AB6B;font-size:0.7rem;">
                                            {{ ucfirst($rek->tipe_kamar) }}
                                        </span>
                                        <h6 class="fw-bold mb-1">{{ $rek->nomor_kamar }}</h6>

                                        {{-- Fasilitas singkat --}}
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            @foreach ($rekFasShown as $fas)
                                                @php
                                                    $namaLw = strtolower($fas->nama_fasilitas);
                                                    $ic = 'bi-check-circle';
                                                    foreach ($rekomFasicons as $kw => $ico) {
                                                        if (str_contains($namaLw, $kw)) {
                                                            $ic = $ico;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <span class="badge bg-light text-dark"
                                                    style="font-size:0.68rem;border:1px solid #eee;">
                                                    <i
                                                        class="bi {{ $ic }} me-1 text-success"></i>{{ $fas->nama_fasilitas }}
                                                </span>
                                            @endforeach
                                            @if ($rekFasExtra > 0)
                                                <span class="badge bg-light text-muted"
                                                    style="font-size:0.68rem;border:1px solid #eee;">+{{ $rekFasExtra }}</span>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center gap-1 mb-2">
                                            <i class="bi bi-star-fill text-warning" style="font-size:0.78rem;"></i>
                                            <span class="fw-bold"
                                                style="font-size:0.82rem;">{{ $rekRating > 0 ? $rekRating : '-' }}</span>
                                            <span class="text-muted"
                                                style="font-size:0.75rem;">({{ $rekReviews }})</span>
                                        </div>

                                        <p class="fw-bold text-success mb-2 mt-auto" style="font-size:0.92rem;">
                                            Rp {{ number_format($rek->harga_per_bulan, 0, ',', '.') }}
                                            <small class="text-muted fw-normal">/ bln</small>
                                        </p>

                                        <a href="{{ route('kamar.detail', $rek->id_kamar) }}"
                                            class="btn btn-outline-green w-100"
                                            style="border-width:1.5px;font-size:0.82rem;padding:6px;border-radius:8px;">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>{{-- /container --}}
    </main>

    {{-- MODAL SEMUA ULASAN --}}
    <div class="modal fade" id="modalSemuaUlasan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"
                style="border-radius: 16px; border:none; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color:#2d3748;">
                        <i class="bi bi-chat-square-quote-fill me-2 text-success"></i>Semua Ulasan
                        ({{ $kamar->reviews->count() }})
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">

                    {{-- Filter Bintang --}}
                    <div class="d-flex flex-wrap gap-2 mb-4 pb-3 border-bottom">
                        <button class="btn btn-success btn-sm filter-review active" data-star="all"
                            style="border-radius: 20px; padding: 6px 16px; font-weight: 500;">Semua</button>
                        @for ($s = 5; $s >= 1; $s--)
                            <button class="btn btn-outline-secondary btn-sm filter-review"
                                data-star="{{ $s }}"
                                style="border-radius: 20px; padding: 6px 16px; font-weight: 500;">
                                <i class="bi bi-star-fill text-warning me-1"></i> {{ $s }} Bintang
                            </button>
                        @endfor
                    </div>

                    {{-- Daftar Ulasan Lengkap --}}
                    <div id="ulasanContainer" style="max-height: 480px; overflow-y: auto; padding-right: 6px;">
                        @foreach ($kamar->reviews as $review)
                            @php
                                $initials = strtoupper(substr($review->user->nama ?? 'A', 0, 1));
                                $dateField = $review->tgl_review ?? ($review->created_at ?? now());
                                $diffDays = (int) abs(now()->diffInDays(\Carbon\Carbon::parse($dateField)));
                                $timeAgo =
                                    $diffDays === 0
                                        ? 'Hari ini'
                                        : ($diffDays < 30
                                            ? $diffDays . ' hari yang lalu'
                                            : floor($diffDays / 30) . ' bulan yang lalu');
                            @endphp
                            <div class="review-card detail-review-item mb-3" data-rating="{{ $review->rating }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-placeholder">{{ $initials }}</div>
                                        <div>
                                            <div class="fw-bold" style="font-size:0.92rem;">
                                                {{ $review->user->nama ?? 'Pengguna' }}
                                            </div>
                                            <div class="text-muted small">{{ $timeAgo }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="badge"
                                            style="background:#fff8e1;color:#f59e0b;border:1px solid #fde68a;border-radius:20px;padding:4px 10px;">
                                            <i class="bi bi-star-fill me-1"></i>{{ $review->rating }}
                                        </span>
                                    </div>
                                </div>
                                @if (!empty($review->komentar))
                                    <p class="mb-0 mt-3 text-muted lh-lg" style="font-size:0.88rem;">
                                        {{ $review->komentar }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Empty state jika filter tidak ada data --}}
                    <div id="emptyReviewState" class="text-center py-5 d-none">
                        <i class="bi bi-chat-square-x fs-1 text-muted d-block mb-3"></i>
                        <p class="text-muted">Tidak ada ulasan untuk rating ini.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(el, url) {
            document.getElementById('mainImage').src = url;
            document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
        }
    </script>

    @include('templates.main_footer')
    @include('templates.footer')

@endsection
