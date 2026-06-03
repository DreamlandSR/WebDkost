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

                                        <div class="d-flex align-items-center">
                                            <button type="button"
                                                class="btn btn-export-pendapatan d-flex align-items-center"
                                                onclick="openExportModal('pendapatan')"
                                                title="Unduh laporan pendapatan per bulan" style = "margin-right: 12px;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                    <polyline points="7 10 12 15 17 10"/>
                                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                                </svg>
                                                Unduh Excel
                                            </button>

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
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button"
                                        class="btn btn-export-pengeluaran d-flex align-items-center gap-2"
                                        onclick="openExportModal('pengeluaran')"
                                        title="Unduh laporan pengeluaran per bulan">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        Unduh Excel
                                    </button>
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

    {{-- ════════════════════════════════════════════════════════
         MODAL EXPORT PENDAPATAN PER BULAN
    ════════════════════════════════════════════════════════ --}}
    <div class="export-modal-overlay" id="exportModalOverlay" onclick="closeExportModal()">
        <div class="export-modal" id="exportModal" onclick="event.stopPropagation()">

            {{-- Header Modal --}}
            <div class="export-modal__header" id="exportModalHeader">
                <div class="export-modal__header-icon" id="exportModalHeaderIcon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                </div>
                <div>
                    <h5 class="export-modal__title" id="exportModalTitle">Unduh Laporan Excel</h5>
                    <p class="export-modal__subtitle" id="exportModalSubtitle">Pilih bulan dan tahun untuk mengunduh laporan</p>
                </div>
                <button type="button" class="export-modal__close" onclick="closeExportModal()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            {{-- Body Modal --}}
            <div class="export-modal__body">
                {{-- Bulan --}}
                <div class="export-field">
                    <label class="export-field__label">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Bulan
                    </label>
                    <div class="export-month-grid" id="exportMonthGrid">
                        @php
                            $bulanList = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                        @endphp
                        @foreach ($bulanList as $i => $bln)
                            <button type="button"
                                class="export-month-btn {{ ($i + 1) === (int) now()->month ? 'active' : '' }}"
                                data-month="{{ $i + 1 }}"
                                onclick="selectMonth(this)">
                                {{ $bln }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Tahun --}}
                <div class="export-field">
                    <label class="export-field__label">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        Tahun
                    </label>
                    <div class="export-year-row">
                        <button type="button" class="export-year-nav" onclick="changeYear(-1)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="15 18 9 12 15 6"/>
                            </svg>
                        </button>
                        <span class="export-year-display" id="exportYearDisplay">{{ now()->year }}</span>
                        <button type="button" class="export-year-nav" onclick="changeYear(1)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Preview Info --}}
                <div class="export-preview" id="exportPreview">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span id="exportPreviewText">Laporan_Pendapatan_<span id="exportPreviewMonth">{{ now()->locale('id')->translatedFormat('F') }}</span>_<span id="exportPreviewYear">{{ now()->year }}</span>.xlsx</span>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="export-modal__footer">
                <button type="button" class="btn export-btn-cancel" onclick="closeExportModal()">
                    Batal
                </button>
                <a href="#" class="btn export-btn-download" id="exportDownloadBtn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Unduh Sekarang
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Kirim data PHP ke JavaScript via window object --}}
        <style>
            /* ═══════════ TOMBOL EXPORT ═══════════ */
            .btn-export-pendapatan {
                background: linear-gradient(135deg, #00a669 0%, #00c47c 100%);
                color: #fff;
                border: none;
                border-radius: 8px;
                font-size: 11.5px;
                font-weight: 600;
                padding: 7px 14px;
                transition: all .25s ease;
                box-shadow: 0 2px 8px rgba(0,166,105,.3);
                white-space: nowrap;
            }
            .btn-export-pendapatan:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 14px rgba(0,166,105,.45);
                color: #fff;
            }
            .btn-export-pengeluaran {
                background: linear-gradient(135deg, #6979f8 0%, #8b9cff 100%);
                color: #fff;
                border: none;
                border-radius: 8px;
                font-size: 11.5px;
                font-weight: 600;
                padding: 7px 14px;
                transition: all .25s ease;
                box-shadow: 0 2px 8px rgba(105,121,248,.3);
                white-space: nowrap;
            }
            .btn-export-pengeluaran:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 14px rgba(105,121,248,.45);
                color: #fff;
            }

            /* ═══════════ OVERLAY & MODAL ═══════════ */
            .export-modal-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(15,23,42,.5);
                backdrop-filter: blur(4px);
                z-index: 9999;
                align-items: center;
                justify-content: center;
                animation: overlayFadeIn .2s ease;
            }
            .export-modal-overlay.active { display: flex; }

            @keyframes overlayFadeIn { from { opacity: 0; } to { opacity: 1; } }
            @keyframes modalSlideIn  { from { opacity: 0; transform: translateY(-24px) scale(.97); } to { opacity: 1; transform: translateY(0) scale(1); } }

            .export-modal {
                background: #fff;
                border-radius: 18px;
                width: 100%;
                max-width: 440px;
                box-shadow: 0 20px 60px rgba(0,0,0,.18);
                animation: modalSlideIn .25s cubic-bezier(.34,1.56,.64,1);
                overflow: hidden;
            }

            /* Header */
            .export-modal__header {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 22px 24px 18px;
                border-bottom: 1px solid #f0f0f5;
            }
            .export-modal__header-icon {
                width: 46px; height: 46px;
                border-radius: 12px;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }
            .export-modal__header.green .export-modal__header-icon {
                background: #e8f5e9;
                color: #00a669;
            }
            .export-modal__header.purple .export-modal__header-icon {
                background: #ece9ff;
                color: #6979f8;
            }
            .export-modal__title {
                font-size: 16px;
                font-weight: 700;
                color: #1a1a2e;
                margin: 0 0 2px;
            }
            .export-modal__subtitle {
                font-size: 12px;
                color: #8a8fa8;
                margin: 0;
            }
            .export-modal__close {
                margin-left: auto;
                background: none;
                border: none;
                color: #aab0c4;
                padding: 6px;
                border-radius: 8px;
                cursor: pointer;
                transition: background .2s, color .2s;
            }
            .export-modal__close:hover { background: #f3f4f6; color: #555; }

            /* Body */
            .export-modal__body { padding: 20px 24px; }
            .export-field { margin-bottom: 18px; }
            .export-field__label {
                display: flex; align-items: center; gap: 6px;
                font-size: 12px;
                font-weight: 600;
                color: #6b7080;
                text-transform: uppercase;
                letter-spacing: .5px;
                margin-bottom: 10px;
            }

            /* Grid Bulan */
            .export-month-grid {
                display: grid;
                grid-template-columns: repeat(6, 1fr);
                gap: 6px;
            }
            .export-month-btn {
                background: #f6f7fa;
                border: 1.5px solid transparent;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 500;
                color: #4a4f68;
                padding: 7px 4px;
                cursor: pointer;
                transition: all .18s ease;
                text-align: center;
            }
            .export-month-btn:hover {
                background: #f0f1ff;
                border-color: #6979f8;
                color: #6979f8;
            }
            .export-month-btn.active {
                font-weight: 700;
                color: #fff;
            }
            /* Warna active akan diset via JS */
            .export-modal.type-pendapatan .export-month-btn.active {
                background: #00a669;
                border-color: #00a669;
                box-shadow: 0 2px 8px rgba(0,166,105,.35);
            }
            .export-modal.type-pengeluaran .export-month-btn.active {
                background: #6979f8;
                border-color: #6979f8;
                box-shadow: 0 2px 8px rgba(105,121,248,.35);
            }

            /* Navigasi Tahun */
            .export-year-row {
                display: flex;
                align-items: center;
                gap: 12px;
                background: #f6f7fa;
                border-radius: 10px;
                padding: 8px 14px;
                width: fit-content;
            }
            .export-year-nav {
                background: #fff;
                border: 1px solid #e0e2ec;
                border-radius: 7px;
                width: 30px; height: 30px;
                display: flex; align-items: center; justify-content: center;
                cursor: pointer;
                color: #4a4f68;
                transition: all .18s;
            }
            .export-year-nav:hover { background: #6979f8; border-color: #6979f8; color: #fff; }
            .export-year-display {
                font-size: 16px;
                font-weight: 700;
                color: #1a1a2e;
                min-width: 52px;
                text-align: center;
            }

            /* Preview */
            .export-preview {
                display: flex;
                align-items: center;
                gap: 8px;
                background: #f6f7fa;
                border-radius: 8px;
                padding: 9px 12px;
                font-size: 11.5px;
                color: #5a6080;
                margin-top: 4px;
                border: 1px dashed #d0d4e8;
            }

            /* Footer */
            .export-modal__footer {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 10px;
                padding: 16px 24px;
                border-top: 1px solid #f0f0f5;
            }
            .export-btn-cancel {
                background: #f3f4f6;
                border: none;
                color: #6b7080;
                font-size: 13px;
                font-weight: 600;
                padding: 9px 18px;
                border-radius: 8px;
                transition: background .2s;
            }
            .export-btn-cancel:hover { background: #e5e7eb; }
            .export-btn-download {
                display: flex; align-items: center; gap: 7px;
                font-size: 13px;
                font-weight: 700;
                padding: 9px 20px;
                border-radius: 8px;
                color: #fff;
                border: none;
                transition: all .22s;
            }
            .export-modal.type-pendapatan .export-btn-download {
                background: linear-gradient(135deg, #00a669, #00c47c);
                box-shadow: 0 3px 12px rgba(0,166,105,.35);
            }
            .export-modal.type-pengeluaran .export-btn-download {
                background: linear-gradient(135deg, #6979f8, #8b9cff);
                box-shadow: 0 3px 12px rgba(105,121,248,.35);
            }
            .export-btn-download:hover {
                transform: translateY(-1px);
                color: #fff;
            }
        </style>

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

            // ── Export Modal Logic ─────────────────────────
            const exportRoutes = {
                pendapatan : '{{ route("admin.export.pendapatan") }}',
                pengeluaran: '{{ route("admin.export.pengeluaran") }}',
            };

            const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

            let currentType  = 'pendapatan';
            let currentMonth = {{ now()->month }};
            let currentYear  = {{ now()->year }};

            function openExportModal(type) {
                currentType  = type;
                currentMonth = {{ now()->month }};
                currentYear  = {{ now()->year }};

                const modal    = document.getElementById('exportModal');
                const overlay  = document.getElementById('exportModalOverlay');
                const header   = document.getElementById('exportModalHeader');

                // Reset state
                modal.className = 'export-modal type-' + type;
                header.className = 'export-modal__header ' + (type === 'pendapatan' ? 'green' : 'purple');

                document.getElementById('exportModalTitle').textContent =
                    type === 'pendapatan' ? 'Unduh Laporan Pendapatan' : 'Unduh Laporan Pengeluaran';
                document.getElementById('exportModalSubtitle').textContent =
                    type === 'pendapatan'
                        ? 'Pilih periode untuk laporan pendapatan bulanan'
                        : 'Pilih periode untuk laporan pengeluaran bulanan';

                // Reset bulan
                document.querySelectorAll('.export-month-btn').forEach(btn => {
                    btn.classList.toggle('active', parseInt(btn.dataset.month) === currentMonth);
                });

                updateYearDisplay();
                updatePreview();

                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeExportModal() {
                document.getElementById('exportModalOverlay').classList.remove('active');
                document.body.style.overflow = '';
            }

            function selectMonth(btn) {
                document.querySelectorAll('.export-month-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentMonth = parseInt(btn.dataset.month);
                updatePreview();
            }

            function changeYear(delta) {
                const newYear = currentYear + delta;
                if (newYear < 2020 || newYear > {{ now()->year + 1 }}) return;
                currentYear = newYear;
                updateYearDisplay();
                updatePreview();
            }

            function updateYearDisplay() {
                document.getElementById('exportYearDisplay').textContent = currentYear;
            }

            function updatePreview() {
                const prefix = currentType === 'pendapatan' ? 'Laporan_Pendapatan' : 'Laporan_Pengeluaran';
                const bln    = monthNames[currentMonth - 1];
                document.getElementById('exportPreviewText').textContent =
                    prefix + '_' + bln + '_' + currentYear + '.xlsx';

                const url = exportRoutes[currentType] + '?bulan=' + currentMonth + '&tahun=' + currentYear;
                document.getElementById('exportDownloadBtn').href = url;
            }

            // Tutup modal jika tekan Escape
            document.addEventListener('keydown', e => { if (e.key === 'Escape') closeExportModal(); });
        </script>
    @endpush
@endsection
