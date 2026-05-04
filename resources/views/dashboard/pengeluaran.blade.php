@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper pengeluaran-table-wrapper">

                    {{-- ===== Page Header ===== --}}
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-bold mb-0" style="color: #000; letter-spacing: -0.5px; font-size: 26px;">
                                        Laporan Pengeluaran
                                    </h2>
                                </div>
                                <div class="d-flex align-items-center mt-3 mt-md-0" style="font-size:14px; color:#888;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24" style="margin-right: 8px">
                                        <path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                                        <path d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Tombol Tambah ===== --}}
                    <div class="row mb-4">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="button" class="btn-tambah shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                                <i class="ti-plus"></i> Tambah data
                            </button>
                        </div>
                    </div>

                    {{-- ===== Success Alert ===== --}}
                    @if(session('success'))
                    <div class="custom-alert success" id="successAlert">
                        <div class="custom-alert-icon"><i class="ti-check"></i></div>
                        <div class="custom-alert-content">{{ session('success') }}</div>
                        <button type="button" class="custom-alert-close"
                                onclick="document.getElementById('successAlert').style.display='none'">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    @endif

                    {{-- ===== Main Card ===== --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-0 rounded-4">
                                <div class="card-body p-4 p-md-5">

                                    {{-- Filter & Search --}}
                                    <form method="GET" action="{{ url()->current() }}"
                                          class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-2 gap-3 w-100">

                                        <div class="d-flex align-items-center" style="gap: 15px;">
                                            <label class="text-muted fw-medium mb-0"
                                                   style="font-size: 15px; white-space: nowrap;">Filter status</label>
                                            <select name="status" class="form-select shadow-none filter-select"
                                                    onchange="this.form.submit()">
                                                <option value="Semua"    {{ request('status') == 'Semua'    ? 'selected' : '' }}>Semua</option>
                                                <option value="Listrik"  {{ request('status') == 'Listrik'  ? 'selected' : '' }}>Listrik</option>
                                                <option value="PDAM"     {{ request('status') == 'PDAM'     ? 'selected' : '' }}>PDAM</option>
                                                <option value="Dapur"    {{ request('status') == 'Dapur'    ? 'selected' : '' }}>Dapur</option>
                                                <option value="Iuran"    {{ request('status') == 'Iuran'    ? 'selected' : '' }}>Iuran</option>
                                                <option value="Peralatan"{{ request('status') == 'Peralatan'? 'selected' : '' }}>Peralatan</option>
                                            </select>
                                        </div>

                                        <div class="d-flex align-items-center w-100 mt-2 mt-md-0 d-md-flex justify-content-md-end"
                                             style="gap: 10px; max-width: 320px;">
                                            <input type="text" name="search" class="form-control shadow-none w-100 search-input"
                                                   placeholder="Cari nama" value="{{ request('search') }}">
                                            <button type="submit" class="btn-search shadow-sm">
                                                <i class="ti-search" style="font-size: 15px;"></i>
                                            </button>
                                        </div>
                                    </form>

                                    {{-- Table --}}
                                    <div class="table-responsive" style="width: 100% !important; max-width: 100vw; overflow-x: auto; -webkit-overflow-scrolling: touch; display: block;">
                                        <table class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th class="text-nowrap" style="min-width: 50px;">No</th>
                                                    <th class="text-nowrap" style="min-width: 160px;">Kategori</th>
                                                    <th class="text-nowrap" style="min-width: 120px;">Nominal</th>
                                                    <th class="text-nowrap" style="min-width: 150px;">Tanggal</th>
                                                    <th class="text-nowrap" style="min-width: 200px;">Keterangan</th>
                                                    <th class="text-center text-nowrap" style="min-width: 220px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($pengeluarans->currentPage() - 1) * $pengeluarans->perPage() + 1; @endphp
                                                @forelse ($pengeluarans as $pengeluaran)
                                                <tr class="table-row-hover">
                                                    <td>{{ $no++ }}</td>
                                                    <td class="text-nowrap">{{ $pengeluaran->kategori ?? '-' }}</td>
                                                    <td class="text-nowrap">Rp {{ number_format($pengeluaran->nominal ?? 0, 0, ',', '.') }}</td>
                                                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($pengeluaran->tgl_transaksi)->format('d/m/Y') }}</td>
                                                    <td class="td-keterangan" title="{{ $pengeluaran->keterangan }}">
                                                        <div class="text-truncate">{{ $pengeluaran->keterangan ?? '-' }}</div>
                                                    </td>
                                                    <td class="text-center text-nowrap">
                                                        <a href="#" class="badge-action badge-edit"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#editModal{{ $pengeluaran->id_pengeluaran }}">Edit</a>
                                                        <a href="#" class="badge-action badge-hapus"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#hapusModal{{ $pengeluaran->id_pengeluaran }}">Hapus</a>
                                                        <a href="#" class="badge-action badge-detail"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#detailModal{{ $pengeluaran->id_pengeluaran }}">Detail</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted">
                                                        Tidak ada data laporan pengeluaran ditemukan.
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Pagination --}}
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 mb-2 gap-4 text-center">
                                        <span class="pagination-label">
                                            Menampilkan {{ $pengeluarans->firstItem() ?? 0 }} - {{ $pengeluarans->lastItem() ?? 0 }}
                                            data dari total {{ $pengeluarans->total() }} data
                                        </span>
                                        <div class="d-flex align-items-center" style="gap: 25px;">
                                            @if ($pengeluarans->onFirstPage())
                                                <span class="pagination-disabled">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </span>
                                            @else
                                                <a href="{{ $pengeluarans->previousPageUrl() }}" class="pagination-link">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </a>
                                            @endif

                                            @if ($pengeluarans->hasMorePages())
                                                <a href="{{ $pengeluarans->nextPageUrl() }}" class="pagination-link">
                                                    Selanjutnya <i class="ti-angle-right ms-2 fw-bold" style="font-size: 15px;"></i>
                                                </a>
                                            @else
                                                <span class="pagination-disabled">
                                                    Selanjutnya <i class="ti-angle-right ms-2 fw-bold" style="font-size: 15px;"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /content-wrapper --}}

                {{-- =====================================================
                     Modal Tambah Data
                     ===================================================== --}}
                <div class="modal fade" id="tambahDataModal" tabindex="-1"
                     aria-labelledby="tambahDataModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                        <div class="modal-content border-0"
                             style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">

                            {{-- Header --}}
                            <div class="modal-header-custom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div class="modal-icon-wrap modal-icon-green">
                                            <i class="ti-wallet" style="color: #00a669; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" id="tambahDataModalLabel"
                                                style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">
                                                Tambah Pengeluaran
                                            </h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">
                                                Isi detail pengeluaran di bawah ini
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="modal-body" style="padding: 18px 26px 20px; background: #fff;">
                                <form action="{{ route('pengeluaran.store') }}" method="POST">
                                    @csrf

                                    {{-- Kategori --}}
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2"
                                               style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;">
                                                <i class="ti-tag"></i>
                                            </span>
                                            Kategori
                                        </label>
                                        <div class="d-flex flex-wrap" style="gap: 10px;" id="kategoriGrid">
                                            @foreach(['Listrik','PDAM','Dapur','Iuran','Peralatan'] as $kat)
                                            <label class="kategori-pill">
                                                <input type="radio" name="kategori" value="{{ $kat }}" required
                                                       style="display:none;" onchange="selectKategori(this)">
                                                <span class="pill-label">{{ $kat }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Nominal --}}
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2"
                                               style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;">
                                                <i class="ti-money"></i>
                                            </span>
                                            Nominal
                                        </label>
                                        <div style="position: relative;">
                                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#00a669; font-size:14px; pointer-events:none;">Rp</span>
                                            <input type="number" name="nominal" id="nominalInput" required min="0"
                                                   class="form-input-custom form-input-nominal"
                                                   placeholder="0">
                                        </div>
                                    </div>

                                    {{-- Tanggal --}}
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2"
                                               style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;">
                                                <i class="ti-calendar"></i>
                                            </span>
                                            Tanggal Transaksi
                                        </label>
                                        <input type="date" name="tgl_transaksi" required
                                               class="form-input-custom"
                                               value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>

                                    {{-- Keterangan --}}
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2"
                                               style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;">
                                                <i class="ti-pencil-alt"></i>
                                            </span>
                                            Keterangan <span style="color: #9ca3af; font-weight: 400;">(Opsional)</span>
                                        </label>
                                        <textarea name="keterangan" rows="2"
                                                  class="form-input-custom"
                                                  placeholder="Catatan tambahan..."></textarea>
                                    </div>

                                    {{-- Buttons --}}
                                    <div class="d-flex justify-content-end" style="gap: 12px;">
                                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn-modal-save-green">Simpan</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>{{-- /tambahDataModal --}}


                {{-- =====================================================
                     Loop Modals: Detail, Edit, Hapus
                     ===================================================== --}}
                @foreach($pengeluarans as $pengeluaran)

                {{-- Modal Detail --}}
                <div class="modal fade" id="detailModal{{ $pengeluaran->id_pengeluaran }}"
                     tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
                        <div class="modal-content border-0 shadow-lg"
                             style="border-radius: 14px; overflow: hidden;">

                            <div class="modal-header-custom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <div class="modal-icon-wrap modal-icon-green">
                                            <i class="ti-info-alt" style="color: #00a669; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px;">
                                                Detail Pengeluaran
                                            </h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px;">
                                                Rincian lengkap transaksi pengeluaran
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="modal-body" style="padding: 26px; background: #fff;">
                                {{-- Baris 1: Kategori + Nominal --}}
                                <div class="row g-3" style="margin-bottom: 12px;">
                                    <div class="col-6">
                                        <div class="detail-box" style="margin-bottom: 0;">
                                            <p class="detail-box-label">Kategori</p>
                                            <p class="detail-box-value">{{ $pengeluaran->kategori }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="detail-box" style="margin-bottom: 0;">
                                            <p class="detail-box-label">Nominal</p>
                                            <p class="detail-box-value detail-box-value-green">
                                                Rp {{ number_format($pengeluaran->nominal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-box">
                                    <p class="detail-box-label">Tanggal Transaksi</p>
                                    <p class="detail-box-value">
                                        {{ \Carbon\Carbon::parse($pengeluaran->tgl_transaksi)->locale('id')->translatedFormat('l, d F Y') }}
                                    </p>
                                </div>
                                <div class="detail-box detail-box-accent">
                                    <p class="detail-box-label">Keterangan</p>
                                    <p class="detail-box-value-body">{{ $pengeluaran->keterangan ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="modal-footer border-0 bg-white p-4 pt-0">
                                <button type="button" class="btn w-100 shadow-sm" data-bs-dismiss="modal"
                                        style="background: #374151; color: white; border-radius: 8px; padding: 10px; font-weight: 600; font-size: 13.5px; border: none;">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>{{-- /detailModal --}}


                {{-- Modal Edit --}}
                <div class="modal fade" id="editModal{{ $pengeluaran->id_pengeluaran }}"
                     tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                        <div class="modal-content border-0"
                             style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">

                            <div class="modal-header-custom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div class="modal-icon-wrap modal-icon-blue">
                                            <i class="ti-pencil-alt" style="color: #3b82f6; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold"
                                                style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">
                                                Edit Pengeluaran
                                            </h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">
                                                Perbarui data pengeluaran
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="modal-body" style="padding: 18px 26px 20px; background: #fff;">
                                <form action="{{ route('pengeluaran.update', $pengeluaran->id_pengeluaran) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    {{-- Kategori --}}
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2"
                                               style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;">
                                                <i class="ti-tag"></i>
                                            </span>
                                            Kategori
                                        </label>
                                        <div class="d-flex flex-wrap" style="gap: 10px;">
                                            @foreach(['Listrik','PDAM','Dapur','Iuran','Peralatan'] as $kat)
                                            <label class="kategori-pill-edit">
                                                <input type="radio" name="kategori" value="{{ $kat }}"
                                                       {{ $pengeluaran->kategori == $kat ? 'checked' : '' }} required
                                                       style="display:none;"
                                                       onchange="updateEditPill(this, {{ $pengeluaran->id_pengeluaran }})">
                                                <span class="pill-label-edit pill-label-edit-{{ $pengeluaran->id_pengeluaran }} {{ $pengeluaran->kategori == $kat ? 'pill-active-edit' : '' }}">
                                                    {{ $kat }}
                                                </span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Nominal --}}
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2"
                                               style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;">
                                                <i class="ti-money"></i>
                                            </span>
                                            Nominal
                                        </label>
                                        <div style="position: relative;">
                                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#3b82f6; font-size:14px; pointer-events:none;">Rp</span>
                                            <input type="number" name="nominal" value="{{ $pengeluaran->nominal }}"
                                                   required min="0"
                                                   class="form-input-custom form-input-nominal form-input-edit">
                                        </div>
                                    </div>

                                    {{-- Tanggal --}}
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2"
                                               style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;">
                                                <i class="ti-calendar"></i>
                                            </span>
                                            Tanggal Transaksi
                                        </label>
                                        <input type="date" name="tgl_transaksi"
                                               value="{{ $pengeluaran->tgl_transaksi }}" required
                                               class="form-input-custom form-input-edit">
                                    </div>

                                    {{-- Keterangan --}}
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2"
                                               style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;">
                                                <i class="ti-pencil-alt"></i>
                                            </span>
                                            Keterangan
                                        </label>
                                        <textarea name="keterangan" rows="2"
                                                  class="form-input-custom form-input-edit">{{ $pengeluaran->keterangan }}</textarea>
                                    </div>

                                    {{-- Buttons --}}
                                    <div class="d-flex justify-content-end" style="gap: 12px;">
                                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn-modal-save-blue">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>{{-- /editModal --}}


                {{-- Modal Hapus --}}
                <div class="modal fade" id="hapusModal{{ $pengeluaran->id_pengeluaran }}"
                     tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                        <div class="modal-content border-0 shadow-lg"
                             style="border-radius: 16px; overflow: hidden;">
                            <div class="modal-body text-center" style="padding: 40px 30px;">

                                <div class="hapus-icon-wrap">
                                    <i class="ti-trash" style="color: #ef4444; font-size: 32px;"></i>
                                </div>

                                <h5 class="fw-bold mb-2" style="color: #111827; font-size: 18px;">
                                    Konfirmasi Hapus
                                </h5>
                                <p class="mb-4" style="color: #6b7280; font-size: 14px; line-height: 1.5;">
                                    Apakah Anda yakin ingin menghapus data pengeluaran ini?
                                    Tindakan ini tidak dapat dibatalkan.
                                </p>

                                <form action="{{ route('pengeluaran.destroy', $pengeluaran->id_pengeluaran) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="d-flex flex-column gap-3">
                                        <button type="submit" class="btn-hapus-confirm">
                                            Ya, Hapus Sekarang
                                        </button>
                                        <button type="button" class="btn-hapus-cancel" data-bs-dismiss="modal">
                                            Batalkan
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>{{-- /hapusModal --}}

                @endforeach

            </div>{{-- /main-panel --}}
        </div>
    </div>
@endsection
