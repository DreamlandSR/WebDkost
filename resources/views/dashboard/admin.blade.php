@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper dashboard-wrapper">

                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 26px;">
                            Selamat datang, {{ Auth::user()->nama ?? 'Admin' }}!
                        </h4>
                        <div class="d-flex align-items-center dashboard-header-date">
                        <svg width="18" height="18" class="mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                        </svg>
                        <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F, Y') }}</span>
                    </div>
                    </div>

                    {{-- Info Kos Cards --}}
                    <h5 class="fw-semibold mb-3 mt-4 section-title">Informasi Kos D'kost</h5>
                    <div class="row g-4 mb-5 mt-2">

                        {{-- Kamar Tersedia --}}
                        <div class="col-md-4 mb-2">
                            <div class="card info-card info-card--purple shadow h-100">
                                <svg class="info-card__bg-shape" width="160" height="160" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" fill="#ffffff" />
                                    <circle cx="80" cy="20" r="25" fill="#ffffff" />
                                </svg>
                                <div class="card-body p-4 position-relative" style="z-index: 1;">
                                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                                        <p class="text-white fw-semibold mb-0 info-card__label">Kamar Tersedia</p>
                                        <div class="info-card__icon-wrap">
                                            <svg width="18" height="18" fill="none" stroke="#ffffff"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                <polyline points="9 22 9 12 15 12 15 22" />
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-white fw-bold mb-0 info-card__value">
                                        {{ $totalKamarTersedia ?? 24 }}
                                        <span class="info-card__unit">Kamar</span>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        {{-- Kamar Terisi --}}
                        <div class="col-md-4 mb-2">
                            <div class="card info-card info-card--green shadow h-100">
                                <svg class="info-card__bg-shape" style="bottom: -30px; right: -30px;" width="160"
                                    height="160" viewBox="0 0 100 100">
                                    <polygon points="50 15, 100 100, 0 100" fill="#ffffff" />
                                </svg>
                                <div class="card-body p-4 position-relative" style="z-index: 1;">
                                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                                        <p class="text-white fw-semibold mb-0 info-card__label">Kamar Terisi</p>
                                        <div class="info-card__icon-wrap">
                                            <svg width="18" height="18" fill="none" stroke="#ffffff"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-white fw-bold mb-0 info-card__value">
                                        {{ $totalKamarTerisi ?? 12 }}
                                        <span class="info-card__unit">Kamar</span>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        {{-- Pendapatan Bulanan --}}
                        <div class="col-md-4 mb-2">
                            <div class="card info-card info-card--orange shadow h-100">
                                <svg class="info-card__bg-shape" style="bottom: -20px; right: -20px; opacity: 0.15;"
                                    width="160" height="160" viewBox="0 0 100 100">
                                    <path d="M0 100 Q 50 50, 100 100 Z" fill="#ffffff" />
                                    <path d="M50 100 Q 75 75, 100 100 Z" fill="#ffffff" opacity="0.5" />
                                </svg>
                                <div class="card-body p-4 position-relative" style="z-index: 1;">
                                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                                        <p class="text-white fw-semibold mb-0 info-card__label">Pendapatan Bulanan</p>
                                        <div class="info-card__icon-wrap">
                                            <svg width="18" height="18" fill="none" stroke="#ffffff"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <line x1="12" y1="1" x2="12" y2="23" />
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-white fw-bold mb-0 info-card__value--income">
                                        Rp {{ number_format($totalPembayaran ?? 24000000, 0, ',', '.') }}
                                    </h3>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Chart + Keluhan --}}
                    <div class="row g-4 mb-5">

                        {{-- Pertumbuhan Chart --}}
                        <div class="col-md-7">
                            <div class="card growth-card shadow-sm h-100 p-4">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            {{-- Icon header ikut berubah sesuai growth --}}
                                            <div class="growth-card__icon-wrap">
                                                @if (($growthPembayaran ?? 0) >= 0)
                                                    {{-- Trending up --}}
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <path d="M4 19L10 13L14 17L21 9" stroke="#00a669"
                                                            stroke-width="2.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path d="M15 9H21V15" stroke="#00a669" stroke-width="2.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                @else
                                                    {{-- Trending down --}}
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <path d="M4 5L10 11L14 7L21 15" stroke="#ff4757"
                                                            stroke-width="2.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path d="M15 15H21V9" stroke="#ff4757" stroke-width="2.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                @endif
                                            </div>

                                            <div>
                                                <h5 class="fw-bold mb-1 growth-card__title">Pertumbuhan Pendapatan</h5>
                                                <div class="text-muted growth-card__subtitle">
                                                    Dibanding bulan {{ $prevMonth->locale('id')->translatedFormat('F Y') }}
                                                </div>
                                            </div>
                                        </div>

                                        @php $isPositive = ($growthPembayaran ?? 0) >= 0; @endphp
                                        <span
                                            class="badge rounded-pill d-flex align-items-center gap-1 {{ $isPositive ? 'growth-card__badge--positive' : 'growth-card__badge--negative' }}">
                                            @if ($isPositive)
                                                <svg width="12" height="12" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                                    <polyline points="17 6 23 6 23 12" />
                                                </svg>
                                                +{{ $growthPembayaran ?? 0 }}%
                                            @else
                                                <svg width="12" height="12" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6" />
                                                    <polyline points="17 18 23 18 23 12" />
                                                </svg>
                                                {{ $growthPembayaran }}%
                                            @endif
                                        </span>
                                    </div>

                                    <p class="text-secondary growth-card__description mt-4 mb-4">
                                        Grafik ini merepresentasikan kenaikan dan tingkat fluktuasi jumlah booking kamar
                                        yang
                                        telah selesai beserta performa pendapatan dari setiap bulannya.
                                    </p>

                                    <div class="growth-card__chart-container">
                                        <canvas id="growthChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Keluhan Terbaru --}}
                        <div class="col-md-5">
                            <div class="card keluhan-card shadow-sm h-100 p-4">
                                <div class="card-body p-2">
                                    <h5 class="fw-bold mb-4 keluhan-card__title">Keluhan pengguna</h5>

                                    @forelse ($keluhanTerbaru as $index => $keluhan)
                                        @if ($index < 3)
                                            @php
                                                $tgl = \Carbon\Carbon::parse($keluhan->tgl_lapor);
                                                $diffMenit = (int) $tgl->diffInMinutes(now());
                                                $diffJam = (int) $tgl->diffInHours(now());
                                                $diffHari = (int) $tgl->diffInDays(now());

                                                if ($diffMenit < 1) {
                                                    $badgeClass = 'keluhan-item__badge--baru';
                                                    $waktuLabel = 'Baru saja';
                                                } elseif ($diffMenit < 60) {
                                                    $badgeClass = 'keluhan-item__badge--menit';
                                                    $waktuLabel = $diffMenit . ' menit lalu';
                                                } elseif ($diffJam < 24) {
                                                    $badgeClass = 'keluhan-item__badge--jam';
                                                    $waktuLabel = $diffJam . ' jam lalu';
                                                } elseif ($diffHari < 7) {
                                                    $badgeClass = 'keluhan-item__badge--lama';
                                                    $waktuLabel = $diffHari . ' hari lalu';
                                                } else {
                                                    $badgeClass = 'keluhan-item__badge--lama';
                                                    $waktuLabel = $tgl->locale('id')->translatedFormat('d M Y');
                                                }
                                            @endphp

                                            <div class="keluhan-item">
                                                <div class="d-flex align-items-center keluhan-item__header">
                                                    <div class="keluhan-item__avatar">
                                                        <svg width="18" height="18" fill="none"
                                                            stroke="#00a669" stroke-width="2" viewBox="0 0 24 24">
                                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                            <circle cx="12" cy="7" r="4" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="fw-bold text-dark keluhan-item__name">
                                                                {{ $keluhan->nama ?? 'Anonymous' }}
                                                            </div>
                                                            <span
                                                                class="badge rounded-pill fw-medium keluhan-item__badge {{ $badgeClass }}">
                                                                {{ $waktuLabel }}
                                                            </span>
                                                        </div>
                                                        <div class="text-muted keluhan-item__room">
                                                            Kamar {{ $keluhan->nomor_kamar ?? '-' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-start mt-2 pt-1 px-1">
                                                    <span class="keluhan-item__icon">
                                                        <svg width="15" height="15" fill="none"
                                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path
                                                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                                                        </svg>
                                                    </span>
                                                    <div class="keluhan-item__text">
                                                        "{{ Str::limit($keluhan->deskripsi_masalah, 65) }}"
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    @empty
                                        {{-- Tampil jika tidak ada keluhan --}}
                                        <div class="text-center text-muted py-4">
                                            <svg width="40" height="40" fill="none" stroke="#ccc"
                                                stroke-width="1.5" viewBox="0 0 24 24" class="mb-2">
                                                <path
                                                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                                            </svg>
                                            <p style="font-size:13px;">Tidak ada keluhan saat ini</p>
                                        </div>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Pengeluaran --}}
                    @php
                        $colors = [
                            '#6979f8',
                            '#00a669',
                            '#ffb259',
                            '#2979ff',
                            '#ff4757',
                            '#9c88ff',
                            '#44bd32',
                            '#e84118',
                        ];

                        $hasPengeluaran = !empty($pengeluaranBulanan) && count($pengeluaranBulanan) > 0;
                        $listPengeluaran = $hasPengeluaran ? $pengeluaranBulanan : [];

                        $totalPengeluaran = 0;
                        $chartLabels = [];
                        $chartData = [];
                        $chartColors = [];

                        if ($hasPengeluaran) {
                            foreach ($listPengeluaran as $index => $item) {
                                $totalPengeluaran += $item->nominal;
                                if ($index < 4) {
                                    $chartLabels[] = $item->kategori;
                                    $chartData[] = $item->nominal;
                                    $chartColors[] = $item->color ?? $colors[$index % 8];
                                }
                            }

                            if (count($listPengeluaran) > 4) {
                                $lainnya = 0;
                                for ($i = 4; $i < count($listPengeluaran); $i++) {
                                    $lainnya += $listPengeluaran[$i]->nominal;
                                }
                                $chartLabels[] = 'Lainnya';
                                $chartData[] = $lainnya;
                                $chartColors[] = '#cbd5e1';
                            }
                        }

                        $totalFormatted = 'Rp ' . number_format($totalPengeluaran, 0, ',', '.');
                        $currentMonth = \Carbon\Carbon::now()->locale('id')->translatedFormat('F');
                    @endphp

                    <div class="card pengeluaran-card border-0 shadow-sm mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <div>
                                    <h5 class="fw-bold mb-1 pengeluaran-card__title">Pengeluaran Bulanan</h5>
                                    <div class="text-muted pengeluaran-card__subtitle">Rincian transaksi dan biaya
                                        operasional bulan {{ $currentMonth }}</div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('pengeluaran.export') }}" class="btn btn-success d-flex align-items-center gap-2 shadow-sm" style="border-radius: 8px; font-size: 12px; font-weight: 600; padding: 8px 16px; background-color: #00a669; border: none;">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        Unduh Excel
                                    </a>
                                    <a href="{{ url('/laporan/pengeluaran') }}" class="pengeluaran-card__link">
                                        Lihat Laporan
                                    </a>
                                </div>
                            </div>

                            @if ($hasPengeluaran)
                                {{-- Ada Data: tampilkan list + donut chart --}}
                                <div class="row align-items-center">
                                    {{-- List Items --}}
                                    <div class="col-md-8 pe-md-4">
                                        <div class="row g-3">
                                            @foreach ($listPengeluaran as $index => $item)
                                                @if ($index < 8)
                                                    @php $color = $item->color ?? $colors[$index % 8]; @endphp
                                                    <div class="col-md-6 mb-2">
                                                        <div class="pengeluaran-item shadow-sm">
                                                            <div class="pengeluaran-item__icon-wrap"
                                                                style="background: {{ $color }}15;">
                                                                <div class="pengeluaran-item__dot"
                                                                    style="background: {{ $color }}; box-shadow: 0 0 8px {{ $color }}60;">
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="text-muted pengeluaran-item__label">
                                                                    {{ $item->kategori }}</div>
                                                                <div class="pengeluaran-item__nominal">Rp
                                                                    {{ number_format($item->nominal, 0, ',', '.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Donut Chart --}}
                                    <div
                                        class="col-md-4 d-flex flex-column align-items-center justify-content-center mt-5 mt-md-0 border-start ps-md-4">
                                        <div class="donut-wrapper">
                                            <canvas id="pengeluaranChart"
                                                style="position: relative; z-index: 2;"></canvas>
                                            <div class="donut-center">
                                                <div class="donut-center__icon-wrap">
                                                    <i class="ti-wallet" style="color: #4a54e1; font-size: 18px;"></i>
                                                </div>
                                                <div class="text-muted donut-center__label">Total {{ $currentMonth }}
                                                </div>
                                                <div class="donut-center__total">{{ $totalFormatted }}</div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <span class="donut-summary-badge fw-medium">
                                                <i class="ti-stats-down" style="color: #ff4757;"></i>
                                                Ringkasan Pengeluaran
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            @else
                                {{-- Kosong: tampilan empty state menarik --}}
                                <div class="pengeluaran-empty-state">
                                    {{-- Animasi ikon dompet kosong --}}
                                    <div class="pengeluaran-empty-state__icon-wrap">
                                        <div class="pengeluaran-empty-state__rings">
                                            <div class="ring ring--1"></div>
                                            <div class="ring ring--2"></div>
                                            <div class="ring ring--3"></div>
                                        </div>
                                        <div class="pengeluaran-empty-state__icon">
                                            <svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                                                stroke="#6979f8" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                                                <line x1="1" y1="10" x2="23" y2="10" />
                                                <path d="M17 14h.01" />
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Teks --}}
                                    <h6 class="pengeluaran-empty-state__title">Belum Ada Pengeluaran</h6>
                                    <p class="pengeluaran-empty-state__desc">
                                        Tidak ada catatan pengeluaran untuk bulan
                                        <strong>{{ $currentMonth }}</strong>.<br>
                                        Tambahkan pengeluaran baru melalui menu laporan.
                                    </p>

                                    {{-- Placeholder Skeleton Item --}}
                                    <div class="pengeluaran-empty-state__skeleton">
                                        @for ($i = 0; $i < 4; $i++)
                                            <div class="skeleton-item">
                                                <div class="skeleton-dot"></div>
                                                <div class="skeleton-lines">
                                                    <div class="skeleton-line skeleton-line--label"></div>
                                                    <div class="skeleton-line skeleton-line--value"></div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>

                                    {{-- CTA Button --}}
                                    <a href="{{ url('/laporan/pengeluaran') }}"
                                        class="pengeluaran-empty-state__cta">
                                        <svg width="16" height="16" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <line x1="12" y1="5" x2="12" y2="19" />
                                            <line x1="5" y1="12" x2="19" y2="12" />
                                        </svg>
                                        Tambah Pengeluaran
                                    </a>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>{{-- .content-wrapper --}}
            </div>{{-- .main-panel --}}
        </div>{{-- .page-body-wrapper --}}
    </div>{{-- .container-scroller --}}

    @push('scripts')
        {{-- Kirim data PHP ke JavaScript via window object --}}
        <script>
            window.dashboardData = {
                growth: {
                    labels: {!! json_encode(
                        $growthData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    ) !!},
                    orders: {!! json_encode($growthData['orders'] ?? [6.2, 3.7, 1.4, 3.7, 7.2, 3.7, 1.5, 5, 1.2, 3.1, 2.1, 6.2]) !!},
                },
                pengeluaran: {
                    labels: {!! json_encode($chartLabels) !!},
                    values: {!! json_encode($chartData) !!},
                    colors: {!! json_encode($chartColors) !!},
                },
            };
        </script>
    @endpush
@endsection
