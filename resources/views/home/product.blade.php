    @extends('templates.layout')

    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/override.css') }}">
    @endsection

    @section('content')

        @include('templates.header')
        @include('templates.navbar')

        <!-- HERO SECTION dengan Background Hijau -->
        <section class="hero-section" style="background-color: #00AB6B;">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h5 class="display-4 fw-bold text-white mb-4">
                            Carilah tempat dimana kamu dapat pulang dan dengan nyaman
                        </h5>

                        <!-- Search Box -->
                        <div class="search-box bg-white p-2 rounded-4 shadow-lg mx-auto" style="max-width: 700px;">
                            <div class="d-flex flex-column flex-md-row gap-2">
                                <!-- Input Search -->
                                <div class="flex-grow-1 position-relative">
                                    <i
                                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" class="form-control border-0 ps-5"
                                        placeholder="Cari kos impianmu disini"
                                        style="background-color: #F5F5F5; border-radius: 12px; height: 54px; font-size: 14px;">
                                </div>

                                <!-- Filter Dropdown -->
                                <div class="position-relative" style="min-width: 120px;">
                                    <select class="form-select border-0"
                                        style="background-color: #F5F5F5; border-radius: 12px; height: 54px; padding-left: 40px; appearance: none; cursor: pointer;">
                                        <option selected>Filter</option>
                                        <option value="kecil">Kos Kecil</option>
                                        <option value="medium">Kos Medium</option>
                                        <option value="mewah">Kos Mewah</option>
                                        <option value="ac">Dengan AC</option>
                                        <option value="wifi">Dengan WiFi</option>
                                        <option value="rating">Rating Tertinggi</option>
                                    </select>
                                    <i class="bi bi-sliders2 position-absolute top-50 start-0 translate-middle-y ms-3"
                                        style="color: #00AB6B; z-index: 10;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PILIHAN KOS KAMI SECTION -->
        <section class="py-5">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between mb-5">
                    <h2 class="fw-bold mb-0" style="color: #2D3748; font-size: 2rem;">Pilihan Kos Kami</h2>
                    <span class="text-muted small">
                        <i class="bi bi-sort-down-alt me-1"></i>Pemesanan terbanyak &amp; rating tertinggi
                    </span>
                </div>

                <div class="row g-4">
                    @php
                        /**
                         * Peta nama fasilitas → Bootstrap Icon.
                         * Cocokkan dengan kata kunci (case-insensitive) di nama_fasilitas.
                         */
                        $fasilitasIcons = [
                            'wifi' => 'bi-wifi',
                            'wi-fi' => 'bi-wifi',
                            'internet' => 'bi-wifi',
                            'ac' => 'bi-snow2',
                            'air' => 'bi-snow2',
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
                    @endphp

                    @forelse ($kamars as $kamar)
                        @php
                            $mainPhoto = $kamar->galeri->first();
                            $imageUrl = $mainPhoto
                                ? asset('storage/' . $mainPhoto->url_foto)
                                : asset('img/room-default.jpg');
                            $ratingVal = $kamar->rating ?? 0;
                            $reviewCount = $kamar->reviews->count();
                            $bookingCount = $kamar->bookings->count();
                            $fullStars = (int) floor($ratingVal);
                            $hasHalf = $ratingVal - $fullStars >= 0.5;
                            $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);
                            $fasilitasList = $kamar->fasilitas;
                            $fasShown = $fasilitasList->take(3);
                            $fasExtra = max(0, $fasilitasList->count() - 3);
                        @endphp

                        <div class="col-lg-3 col-md-6">
                            <div class="card kos-card border-0 shadow-sm h-100">

                                {{-- Foto --}}
                                <img src="{{ $imageUrl }}" class="card-img-top"
                                    style="height: 180px; object-fit: cover; border-radius: 12px 12px 0 0;"
                                    alt="{{ $kamar->nomor_kamar }}">

                                <div class="card-body d-flex flex-column">

                                    {{-- Badge tipe + jumlah pemesanan --}}
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge"
                                            style="background-color: rgba(0,171,107,0.12); color: #00AB6B; font-size: 0.72rem;">
                                            {{ ucfirst($kamar->tipe_kamar) }}
                                        </span>
                                        @if ($bookingCount > 0)
                                            <span class="text-muted" style="font-size: 0.72rem;">
                                                <i class="bi bi-people-fill me-1"></i>{{ $bookingCount }}x dipesan
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Nama Kamar --}}
                                    <h5 class="card-title fw-bold mb-3" style="font-size: 1.1rem;">
                                        {{ $kamar->nomor_kamar }}
                                    </h5>

                                    {{-- Fasilitas dengan icon mapping --}}
                                    @if ($fasilitasList->isNotEmpty())
                                        <div class="mb-3 d-flex flex-wrap gap-1">
                                            @foreach ($fasShown as $fas)
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
                                                <span class="badge bg-light text-dark"
                                                    style="font-size: 0.72rem; font-weight: 500; border: 1px solid #e2e8f0;">
                                                    <i
                                                        class="bi {{ $icon }} me-1 text-success"></i>{{ $fas->nama_fasilitas }}
                                                </span>
                                            @endforeach
                                            @if ($fasExtra > 0)
                                                <span class="badge bg-light text-muted"
                                                    style="font-size: 0.72rem; border: 1px solid #e2e8f0;">
                                                    +{{ $fasExtra }} lainnya
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted small mb-3">Fasilitas belum tersedia</p>
                                    @endif

                                    {{-- Rating bintang --}}
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="text-warning me-2" style="font-size: 0.85rem;">
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
                                        <span class="fw-bold me-1" style="font-size: 0.9rem;">
                                            {{ $ratingVal > 0 ? $ratingVal : '-' }}
                                        </span>
                                        <span class="text-muted" style="font-size: 0.78rem;">
                                            @if ($reviewCount > 0)
                                                ({{ $reviewCount }} ulasan)
                                            @else
                                                Belum ada ulasan
                                            @endif
                                        </span>
                                    </div>

                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('kamar.detail', $kamar->id_kamar) }}"
                                       class="btn btn-outline-green w-100"
                                       style="border-width: 2px; font-weight: 500; padding: 8px; border-radius: 8px;">
                                        Detail kamar
                                    </a>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-house-slash fs-1 text-muted mb-3 d-block"></i>
                            <p class="text-muted">Belum ada kamar tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>


        </div>

        @include('templates.main_footer')
        @include('templates.footer')
