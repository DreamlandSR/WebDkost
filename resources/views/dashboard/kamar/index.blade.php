@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper pengeluaran-table-wrapper" style="background-color: #fafbfc; min-height: 100vh;">

                    <!-- Header Row -->
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-bold mb-0" style="color: #000; letter-spacing: -0.5px; font-size: 26px;">
                                        Kelola Kamar</h2>
                                </div>
                                <div class="d-flex align-items-center mt-3 mt-md-0" style="font-size:14px; color:#888;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24" style="margin-right: 8px">
                                        <path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z" />
                                        <path d="M16 2v4M8 2v4M3 10h18" />
                                    </svg>
                                    <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Button Row -->
                    <div class="row mb-4">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="button" class="btn-tambah shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#tambahKamarModal">
                                <i class="ti-plus"></i> Tambah Kamar
                            </button>
                        </div>
                    </div>

                    <!-- Alert Success -->
                    @if (session('success'))
                        <div class="custom-alert success" id="successAlert">
                            <div class="custom-alert-icon">
                                <i class="ti-check"></i>
                            </div>
                            <div class="custom-alert-content">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="custom-alert-close"
                                onclick="document.getElementById('successAlert').style.display='none'">
                                <i class="ti-close"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Alert Error -->
                    @if (session('error'))
                        <div class="custom-alert error" id="errorAlert">
                            <div class="custom-alert-icon">
                                <i class="ti-alert"></i>
                            </div>
                            <div class="custom-alert-content">
                                {{ session('error') }}
                            </div>
                            <button type="button" class="custom-alert-close"
                                onclick="document.getElementById('errorAlert').style.display='none'">
                                <i class="ti-close"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Main Table Card -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-0 rounded-4" style="box-shadow: 0 4px 30px rgba(0,0,0,0.03);">
                                <div class="card-body p-4 p-md-5">

                                    <!-- Filter & Search Form -->
                                    <form method="GET" action="{{ url()->current() }}"
                                        class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-2 gap-3 w-100">
                                        <div class="d-flex align-items-center" style="gap: 15px;">
                                            <label class="text-muted fw-medium mb-0"
                                                style="font-size: 15px; white-space: nowrap;">Filter status</label>
                                            <select name="status" class="form-select shadow-none"
                                                style="width: 140px; border-radius: 4px; padding: 6px 12px; font-size: 14px; cursor: pointer;"
                                                onchange="this.form.submit()">

                                                <option value="Semua" {{ !request('status') || request('status') == 'Semua' ? 'selected' : '' }}>
                                                    Semua
                                                </option>
                                                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>
                                                    Tersedia
                                                </option>
                                                <option value="terisi" {{ request('status') == 'terisi' ? 'selected' : '' }}>
                                                    Terisi
                                                </option>
                                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                                                    Maintenance
                                                </option>
                                            </select>
                                        </div>

                                        <div class="d-flex align-items-center w-100 mt-2 mt-md-0 d-md-flex justify-content-md-end"
                                            style="gap: 10px; max-width: 320px;">
                                            <input type="text" name="search" class="form-control shadow-none w-100"
                                                placeholder="Cari nomor kamar" value="{{ request('search') }}"
                                                style="border-radius: 4px; padding: 6px 12px; font-size: 14px;">
                                            <button type="submit"
                                                class="btn border-0 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="background-color: #00a669; color: white; padding: 0; width: 36px; height: 36px; border-radius: 4px; transition: transform 0.2s;"
                                                onmouseover="this.style.transform='scale(1.05)';"
                                                onmouseout="this.style.transform='scale(1)';">
                                                <i class="ti-search" style="font-size: 15px;"></i>
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Data Table -->
                                    <div class="table-responsive"
                                        style="width: 100% !important; max-width: 100vw; overflow-x: auto; -webkit-overflow-scrolling: touch; display: block;">
                                        <table class="table align-middle"
                                            style="border-collapse: separate; border-spacing: 0; min-width: 950px; white-space: nowrap;">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap"
                                                        style="font-size: 14px; border-color: #e5e7eb !important; min-width: 50px;">
                                                        No</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap"
                                                        style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">
                                                        Nomor Kamar</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap"
                                                        style="font-size: 14px; border-color: #e5e7eb !important; min-width: 110px;">
                                                        Tipe</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap"
                                                        style="font-size: 14px; border-color: #e5e7eb !important; min-width: 110px;">
                                                        Status</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap"
                                                        style="font-size: 14px; border-color: #e5e7eb !important; min-width: 130px;">
                                                        Harga/Bulan</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap"
                                                        style="font-size: 14px; border-color: #e5e7eb !important; min-width: 250px;">
                                                        Deskripsi</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 text-center px-3 text-nowrap"
                                                        style="font-size: 14px; border-color: #e5e7eb !important; min-width: 240px;">
                                                        Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($kamars->currentPage() - 1) * $kamars->perPage() + 1; @endphp
                                                @forelse ($kamars as $kamar)
                                                    <tr class="table-row-hover" style="transition: background 0.2s;">
                                                        <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent"
                                                            style="font-size: 14px; border-color: #f1f2f6;">
                                                            {{ $no++ }}</td>
                                                        <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap fw-600"
                                                            style="font-size: 14px; border-color: #f1f2f6;">
                                                            {{ $kamar->nomor_kamar ?? '-' }}</td>
                                                        <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap"
                                                            style="font-size: 14px; border-color: #f1f2f6;">
                                                            {{ $kamar->tipe_kamar ?? '-' }}</td>
                                                        <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 bg-transparent text-nowrap"
                                                            style="font-size: 14px; border-color: #f1f2f6;">
                                                            @if ($kamar->status_kamar == 'tersedia')
                                                                <span class="badge rounded-pill"
                                                                    style="background-color: #ecfdf5; color: #00a669; font-weight: 600; font-size: 12px;">Tersedia</span>
                                                            @elseif($kamar->status_kamar == 'terisi')
                                                                <span class="badge rounded-pill"
                                                                    style="background-color: #fef2f2; color: #ef4444; font-weight: 600; font-size: 12px;">Terisi</span>
                                                            @else
                                                                <span class="badge rounded-pill"
                                                                    style="background-color: #fef3c7; color: #d97706; font-weight: 600; font-size: 12px;">Maintenance</span>
                                                            @endif
                                                        </td>
                                                        <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap fw-600"
                                                            style="font-size: 14px; border-color: #f1f2f6;">Rp
                                                            {{ number_format($kamar->harga_per_bulan ?? 0, 0, ',', '.') }}
                                                        </td>
                                                        <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent"
                                                            style="font-size: 14px; border-color: #f1f2f6; max-width: 250px;"
                                                            title="{{ $kamar->deskripsi }}">
                                                            <div class="text-truncate" style="max-width: 100%;">
                                                                {{ Str::limit($kamar->deskripsi ?? '-', 40) }}</div>
                                                        </td>
                                                        <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-center bg-transparent text-nowrap"
                                                            style="border-color: #f1f2f6;">
                                                            <a href="#" data-bs-toggle="modal"
                                                                data-bs-target="#editModal{{ $kamar->id_kamar }}"
                                                                class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1"
                                                                style="background-color: #4f46e5; font-size: 13px; font-weight: 500; transition: opacity 0.2s;"
                                                                onmouseover="this.style.opacity='0.9';"
                                                                onmouseout="this.style.opacity='1';">Edit</a>
                                                            @if ($kamar->status_kamar == 'terisi')
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#kamarTerisiModal{{ $kamar->id_kamar }}"
                                                                    class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1"
                                                                    style="background-color: #9ca3af; font-size: 13px; font-weight: 500; transition: opacity 0.2s; cursor: not-allowed;"
                                                                    onmouseover="this.style.opacity='0.9';"
                                                                    onmouseout="this.style.opacity='1';">Hapus</a>
                                                            @else
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#hapusModal{{ $kamar->id_kamar }}"
                                                                    class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1"
                                                                    style="background-color: #ef4444; font-size: 13px; font-weight: 500; transition: opacity 0.2s;"
                                                                    onmouseover="this.style.opacity='0.9';"
                                                                    onmouseout="this.style.opacity='1';">Hapus</a>
                                                            @endif
                                                            <a href="#" data-bs-toggle="modal"
                                                                data-bs-target="#detailModal{{ $kamar->id_kamar }}"
                                                                class="badge rounded-pill text-white text-decoration-none px-4 py-2"
                                                                style="background-color: #3b82f6; font-size: 13px; font-weight: 500; transition: opacity 0.2s;"
                                                                onmouseover="this.style.opacity='0.9';"
                                                                onmouseout="this.style.opacity='1';">Detail</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7"
                                                            class="text-center py-5 text-muted bg-transparent">Tidak ada
                                                            data kamar ditemukan.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 mb-2 gap-4 text-center">
                                        <span class="text-muted"
                                            style="font-size: 15px; font-weight: 500; letter-spacing: -0.2px;">
                                            Menampilkan {{ $kamars->firstItem() ?? 0 }} - {{ $kamars->lastItem() ?? 0 }}
                                            data dari total {{ $kamars->total() }} data
                                        </span>
                                        <div class="d-flex align-items-center" style="gap: 25px;">
                                            @if ($kamars->onFirstPage())
                                                <span class="text-muted d-flex align-items-center"
                                                    style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i>
                                                    Kembali
                                                </span>
                                            @else
                                                <a href="{{ $kamars->previousPageUrl() . '&status=' . request('status') . '&search=' . request('search') }}"
                                                    class="text-dark text-decoration-none d-flex align-items-center pagination-link"
                                                    style="font-size: 15px; font-weight: 500; transition: color 0.2s;"
                                                    onmouseover="this.style.color='#00a669';"
                                                    onmouseout="this.style.color='#343a40';">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i>
                                                    Kembali
                                                </a>
                                            @endif

                                            @if ($kamars->hasMorePages())
                                                <a href="{{ $kamars->nextPageUrl() . '&status=' . request('status') . '&search=' . request('search') }}"
                                                    class="text-dark text-decoration-none d-flex align-items-center pagination-link"
                                                    style="font-size: 15px; font-weight: 500; transition: color 0.2s;"
                                                    onmouseover="this.style.color='#00a669';"
                                                    onmouseout="this.style.color='#343a40';">
                                                    Selanjutnya <i class="ti-angle-right ms-2 fw-bold"
                                                        style="font-size: 15px;"></i>
                                                </a>
                                            @else
                                                <span class="text-muted d-flex align-items-center"
                                                    style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    Selanjutnya <i class="ti-angle-right ms-2 fw-bold"
                                                        style="font-size: 15px;"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @include('dashboard.kamar.modals.create')

                    @foreach ($kamars as $kamar)
                        @include('dashboard.kamar.modals.semua_modal')
                    @endforeach
                </div>

                <div class="modal fade" id="confirmDeleteImageModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                            <div class="modal-body text-center" style="padding: 34px 28px;">

                                <div style="width: 74px; height: 74px; border-radius: 50%; background: #fef2f2; display:flex; align-items:center; justify-content:center; margin: 0 auto 20px;">
                                    <i class="ti-alert" style="font-size: 32px; color: #ef4444;"></i>
                                </div>

                                <h5 class="fw-bold mb-2" style="font-size: 18px; color:#111827;">
                                    Hapus Gambar?
                                </h5>

                                <p class="mb-4" style="font-size: 14px; color:#6b7280; line-height: 1.6;">
                                    Gambar ini akan dihapus setelah Anda menekan tombol
                                    <strong>Simpan Perubahan</strong>.
                                </p>

                                <div class="d-flex justify-content-center" style="gap: 12px;">
                                    <button type="button"
                                        class="btn"
                                        data-bs-dismiss="modal"
                                        style="padding: 9px 22px; border-radius: 10px; background:#f3f4f6; color:#4b5563; font-weight:600; border:none;">
                                        Batal
                                    </button>

                                    <button type="button"
                                        id="confirmDeleteImageBtn"
                                        class="btn"
                                        style="padding: 9px 24px; border-radius: 10px; background:#ef4444; color:white; font-weight:700; border:none;">
                                        Ya, Hapus
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // Toggle pill active state for Edit modal (tipe & status)
                    function updateEditPill(element, type, id) {
                        const selector = `.pill-label-${type}-${id}`;
                        const pills = document.querySelectorAll(selector);
                        pills.forEach(pill => {
                            pill.classList.remove('pill-active-edit');
                            const isChecked = pill.previousElementSibling && pill.previousElementSibling.checked;
                            if (isChecked) {
                                pill.classList.add('pill-active-edit');
                            }
                        });
                    }
                </script>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Memuat script fitur kamar jika sudah dipisahkan ke file sendiri --}}

        <script>
            // Pass existing kamar numbers to JS for validation
            window.existingKamarNumbers = @json(\App\Models\Kamar::pluck('nomor_kamar')->toArray());

            document.addEventListener('DOMContentLoaded', function() {
                // Logika Pagination Animation (Tetap gunakan logika Anda yang bagus tadi)
                const paginationLinks = document.querySelectorAll('.pagination-link');
                const tableContainer = document.querySelector('.table-responsive');

                paginationLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (tableContainer) {
                            tableContainer.style.opacity = '0.3';
                            tableContainer.style.transform = 'translateY(10px)';
                        }
                        this.style.pointerEvents = 'none';
                        this.innerHTML =
                            '<i class="ti-reload" style="animation: spin 1s linear infinite; margin-right: 5px;"></i> Memuat...';
                    });
                });

                // GLOBAL MODAL FUNCTIONS (Pindahkan dari semua_modal ke sini agar tidak duplikat)
                window.goToSlide = function(kamarId, index) {
                    const carousel = document.getElementById('carouselDetail' + kamarId);
                    if (carousel) {
                        const bsCarousel = bootstrap.Carousel.getOrCreateInstance(carousel);
                        bsCarousel.to(index);
                    }
                };
            });
        </script>
        <style>
            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush


@endsection
