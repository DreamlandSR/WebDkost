@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper" style="background-color: #fafbfc; min-height: 100vh;">

                    {{-- Header --}}
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-bold mb-0" style="color: #000; letter-spacing: -0.5px; font-size: 26px;">Penyewa Furnitur</h2>
                                    <p class="mb-0 mt-1" style="color: #6b7280; font-size: 13.5px;">Tracking furnitur yang sedang atau pernah disewa oleh penyewa</p>
                                </div>
                                <div class="d-flex align-items-center mt-3 mt-md-0" style="font-size:14px; color:#888;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 8px"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Tambah --}}
                    <div class="row mb-4">
                        <div class="col-lg-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('furnitur.index') }}" class="btn-back-furnitur shadow-sm">
                                <i class="ti-arrow-left"></i> Kelola Furnitur
                            </a>
                            <button type="button" class="btn-tambah shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                <i class="ti-plus"></i> Tambah Manual
                            </button>
                        </div>
                    </div>

                    {{-- Alerts --}}
                    @if(session('success'))
                    <div class="custom-alert success" id="successAlert">
                        <div class="custom-alert-icon"><i class="ti-check"></i></div>
                        <div class="custom-alert-content">{{ session('success') }}</div>
                        <button type="button" class="custom-alert-close" onclick="this.parentElement.style.display='none'"><i class="ti-close"></i></button>
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="custom-alert error" id="errorAlert">
                        <div class="custom-alert-icon"><i class="ti-alert"></i></div>
                        <div class="custom-alert-content">{{ session('error') }}</div>
                        <button type="button" class="custom-alert-close" onclick="this.parentElement.style.display='none'"><i class="ti-close"></i></button>
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="custom-alert error" id="validationAlert">
                        <div class="custom-alert-icon"><i class="ti-alert"></i></div>
                        <div class="custom-alert-content">
                            <strong>Terjadi kesalahan validasi:</strong>
                            <ul class="mb-0 mt-1">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                        </div>
                        <button type="button" class="custom-alert-close" onclick="this.parentElement.style.display='none'"><i class="ti-close"></i></button>
                    </div>
                    @endif

                    {{-- Card Tabel --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-0 rounded-4" style="box-shadow: 0 4px 30px rgba(0,0,0,0.03);">
                                <div class="card-body p-4 p-md-5">

                                    {{-- Filter & Search --}}
                                    <form method="GET" action="{{ url()->current() }}" class="d-flex flex-wrap gap-2 mb-4 pb-2">
                                        <input type="text" name="search" class="form-control shadow-none" placeholder="Cari nama penyewa..."
                                            value="{{ request('search') }}" style="border-radius:8px; max-width:220px; font-size:14px;">

                                        <select name="status" class="form-select shadow-none" style="border-radius:8px; max-width:160px; font-size:14px;">
                                            <option value="Semua" {{ request('status','Semua')==='Semua'?'selected':'' }}>Semua Status</option>
                                            <option value="aktif"   {{ request('status')==='aktif'  ?'selected':'' }}>Aktif</option>
                                            <option value="selesai" {{ request('status')==='selesai'?'selected':'' }}>Selesai</option>
                                        </select>

                                        <select name="id_furnitur" class="form-select shadow-none" style="border-radius:8px; max-width:200px; font-size:14px;">
                                            <option value="">Semua Furnitur</option>
                                            @foreach($furniturList as $f)
                                                <option value="{{ $f->id_furnitur }}" {{ request('id_furnitur')==$f->id_furnitur?'selected':'' }}>{{ $f->nama_furnitur }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn border-0 shadow-sm d-flex align-items-center justify-content-center"
                                            style="background:#00a669; color:white; padding:0; width:36px; height:36px; border-radius:8px;">
                                            <i class="ti-search" style="font-size:15px;"></i>
                                        </button>
                                        @if(request()->hasAny(['search','status','id_furnitur']))
                                        <a href="{{ route('penyewa-furnitur.index') }}" class="btn border-0 shadow-sm d-flex align-items-center justify-content-center"
                                            style="background:#f3f4f6; color:#6b7280; padding:0; width:36px; height:36px; border-radius:8px;" title="Reset filter">
                                            <i class="ti-close" style="font-size:13px;"></i>
                                        </a>
                                        @endif
                                    </form>

                                    {{-- Tabel --}}
                                    <div class="table-responsive">
                                        <table class="table align-middle" style="border-collapse:separate; border-spacing:0; min-width:900px;">
                                            <thead>
                                                <tr>
                                                    <th class="pf-th">No</th>
                                                    <th class="pf-th">Penyewa</th>
                                                    <th class="pf-th">Furnitur</th>
                                                    <th class="pf-th">Kamar</th>
                                                    <th class="pf-th">Kode Barang</th>
                                                    <th class="pf-th">Tgl Mulai</th>
                                                    <th class="pf-th">Tgl Selesai</th>
                                                    <th class="pf-th">Status</th>
                                                    <th class="pf-th text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($penyewaFurnitur->currentPage() - 1) * $penyewaFurnitur->perPage() + 1; @endphp
                                                @forelse($penyewaFurnitur as $item)
                                                <tr class="table-row-hover">
                                                    <td class="pf-td">{{ $no++ }}</td>
                                                    <td class="pf-td">
                                                        <div class="fw-bold" style="color:#111827; font-size:14px;">{{ $item->user->nama ?? '-' }}</div>
                                                        <div style="color:#9ca3af; font-size:12px;">{{ $item->user->no_telepon ?? '' }}</div>
                                                    </td>
                                                    <td class="pf-td fw-600" style="color:#111827;">{{ $item->item->furnitur->nama_furnitur ?? '-' }}</td>
                                                    <td class="pf-td">
                                                        @if($item->booking && $item->booking->kamar)
                                                            <span class="badge rounded-pill" style="background:#f0fdf4; color:#00a669; font-size:12px; font-weight:600;">
                                                                {{ $item->booking->kamar->nomor_kamar }}
                                                            </span>
                                                        @else
                                                            <span style="color:#9ca3af; font-size:13px;">Tanpa Booking</span>
                                                        @endif
                                                    </td>
                                                    <td class="pf-td">
                                                        <span class="badge rounded-pill" style="background:#eff6ff; color:#3b82f6; font-size:12px; font-weight:600;">
                                                            {{ $item->item->kode_item ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="pf-td" style="color:#374151;">
                                                        {{ $item->tgl_mulai ? \Carbon\Carbon::parse($item->tgl_mulai)->format('d M Y') : '-' }}
                                                    </td>
                                                    <td class="pf-td" style="color:#374151;">
                                                        {{ $item->tgl_selesai ? \Carbon\Carbon::parse($item->tgl_selesai)->format('d M Y') : '-' }}
                                                    </td>
                                                    <td class="pf-td">
                                                        @if($item->status === 'aktif')
                                                            <span class="badge rounded-pill" style="background:#ecfdf5; color:#00a669; font-size:12px; font-weight:600; padding:5px 12px;">
                                                                <i class="ti-check" style="font-size:10px;"></i> Aktif
                                                            </span>
                                                        @else
                                                            <span class="badge rounded-pill" style="background:#f0f9ff; color:#0284c7; font-size:12px; font-weight:600; padding:5px 12px;">
                                                                <i class="ti-flag" style="font-size:10px;"></i> Selesai
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="pf-td text-center">
                                                        <button onclick="loadDetail({{ $item->id_penyewa_furnitur }})"
                                                            class="badge rounded-pill text-white border-0 px-3 py-2 me-1"
                                                            style="background:#3b82f6; font-size:12px; cursor:pointer;">
                                                            Detail
                                                        </button>
                                                        <button onclick="openEdit({{ $item->id_penyewa_furnitur }}, '{{ $item->status }}', '{{ $item->tgl_selesai }}', '{{ addslashes($item->catatan ?? '') }}')"
                                                            class="badge rounded-pill text-white border-0 px-3 py-2 me-1"
                                                            style="background:#4f46e5; font-size:12px; cursor:pointer;">
                                                            Edit
                                                        </button>
                                                        <button onclick="openHapus({{ $item->id_penyewa_furnitur }}, '{{ addslashes($item->furnitur->nama_furnitur ?? '') }}')"
                                                            class="badge rounded-pill text-white border-0 px-3 py-2"
                                                            style="background:#ef4444; font-size:12px; cursor:pointer;">
                                                            Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-5 text-muted bg-transparent">
                                                        <i class="ti-package" style="font-size:40px; opacity:0.3; display:block; margin-bottom:12px;"></i>
                                                        Belum ada data penyewa furnitur.
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Pagination --}}
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                                        <span class="text-muted" style="font-size:14px;">
                                            Menampilkan {{ $penyewaFurnitur->firstItem() ?? 0 }} - {{ $penyewaFurnitur->lastItem() ?? 0 }}
                                            dari {{ $penyewaFurnitur->total() }} data
                                        </span>
                                        <div class="d-flex align-items-center" style="gap:20px;">
                                            @if($penyewaFurnitur->onFirstPage())
                                                <span class="text-muted" style="font-size:14px; opacity:0.4; cursor:not-allowed;"><i class="ti-angle-left me-1"></i> Kembali</span>
                                            @else
                                                <a href="{{ $penyewaFurnitur->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                                                    class="text-dark text-decoration-none" style="font-size:14px; font-weight:500;"
                                                    onmouseover="this.style.color='#00a669'" onmouseout="this.style.color='#343a40'">
                                                    <i class="ti-angle-left me-1"></i> Kembali
                                                </a>
                                            @endif
                                            @if($penyewaFurnitur->hasMorePages())
                                                <a href="{{ $penyewaFurnitur->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                                                    class="text-dark text-decoration-none" style="font-size:14px; font-weight:500;"
                                                    onmouseover="this.style.color='#00a669'" onmouseout="this.style.color='#343a40'">
                                                    Selanjutnya <i class="ti-angle-right ms-1"></i>
                                                </a>
                                            @else
                                                <span class="text-muted" style="font-size:14px; opacity:0.4; cursor:not-allowed;">Selanjutnya <i class="ti-angle-right ms-1"></i></span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- end content-wrapper --}}

                @include('dashboard.penyewa-furnitur._modals')

            </div>{{-- end main-panel --}}
        </div>
    </div>

    <style>
        .btn-tambah {
            background: linear-gradient(135deg, #00a669, #008a57);
            color: white; border: none; padding: 10px 20px; border-radius: 8px;
            font-weight: 500; font-size: 13.5px; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px; text-decoration: none;
            transition: opacity 0.2s;
        }
        .btn-tambah:hover { opacity: 0.9; color: white; }
        .btn-back-furnitur {
            background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;
            padding: 10px 16px; border-radius: 8px; font-weight: 500; font-size: 13.5px;
            cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none; transition: background 0.2s;
        }
        .btn-back-furnitur:hover { background: #e5e7eb; color: #111827; }
        .table-row-hover:hover { background-color: rgba(0,166,105,0.03) !important; }
        .pf-th {
            border-bottom: 2px solid #e5e7eb !important; border-top: 0; border-left: 0; border-right: 0;
            font-size: 13px; font-weight: 700; color: #374151; padding: 10px 12px; white-space: nowrap;
        }
        .pf-td {
            border-bottom: 1px solid #f1f2f6 !important; border-top: 0; border-left: 0; border-right: 0;
            font-size: 13.5px; color: #374151; padding: 14px 12px; background: transparent;
        }
        .custom-alert {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 16px 20px; border-radius: 10px; margin-bottom: 20px;
            animation: slideIn 0.3s ease-in-out;
        }
        .custom-alert.success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
        .custom-alert.error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .custom-alert-icon    { flex-shrink:0; font-size:18px; }
        .custom-alert-content { flex:1; font-size:14px; }
        .custom-alert-close   { background:none; border:none; cursor:pointer; color:inherit; font-size:16px; flex-shrink:0; opacity:.7; }
        .custom-alert-close:hover { opacity:1; }
        @keyframes slideIn { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        .rounded-4 { border-radius: 16px !important; }
    </style>

    <script>
        // Auto hide alerts
        setTimeout(() => document.querySelectorAll('.custom-alert').forEach(a => a.style.display='none'), 5000);

        // Load detail via AJAX
        function loadDetail(id) {
            fetch(`/dashboard/penyewa-furnitur/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(res => {
                if (!res.success) return alert('Data tidak ditemukan');
                const d = res.data;
                document.getElementById('detail-penyewa').textContent    = d.penyewa;
                document.getElementById('detail-email').textContent      = d.penyewa_email;
                document.getElementById('detail-telp').textContent       = d.penyewa_telp;
                document.getElementById('detail-furnitur').textContent   = d.furnitur;
                document.getElementById('detail-harga').textContent      = d.harga;
                document.getElementById('detail-kode').textContent       = d.kode_item;
                document.getElementById('detail-kamar').textContent      = d.kamar;
                document.getElementById('detail-tgl-mulai').textContent  = d.tgl_mulai;
                document.getElementById('detail-tgl-selesai').textContent= d.tgl_selesai;
                document.getElementById('detail-catatan').textContent    = d.catatan;
                const badge = document.getElementById('detail-status');
                badge.textContent = d.status === 'aktif' ? 'Aktif' : 'Selesai';
                badge.style.background = d.status === 'aktif' ? '#ecfdf5' : '#f0f9ff';
                badge.style.color      = d.status === 'aktif' ? '#00a669' : '#0284c7';
                new bootstrap.Modal(document.getElementById('detailModal')).show();
            })
            .catch(() => alert('Gagal memuat detail.'));
        }

        // Open edit modal
        function openEdit(id, status, tglSelesai, catatan) {
            document.getElementById('editFormAction').action = `/dashboard/penyewa-furnitur/${id}`;
            document.getElementById('editStatus').value      = status;
            document.getElementById('editTglSelesai').value  = tglSelesai || '';
            document.getElementById('editCatatan').value     = catatan || '';
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        // Open hapus modal
        function openHapus(id, nama) {
            document.getElementById('hapusFormAction').action = `/dashboard/penyewa-furnitur/${id}`;
            document.getElementById('hapusNama').textContent  = nama;
            new bootstrap.Modal(document.getElementById('hapusModal')).show();
        }
    </script>
@endsection
