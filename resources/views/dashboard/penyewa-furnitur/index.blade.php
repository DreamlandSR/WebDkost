@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper" style="background-color: #fafbfc; min-height: 100vh;">

                    {{-- Header --}}
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-bold mb-1" style="color: #111827; letter-spacing: -0.5px; font-size: 26px;">Penyewa Furnitur</h2>
                                    <p class="mb-0" style="color: #6b7280; font-size: 14.5px;">Tracking furnitur yang sedang atau pernah disewa oleh penyewa</p>
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
                        <div class="col-lg-12 d-flex justify-content-end gap-4">
                            <a href="{{ route('furnitur.index') }}" class="btn-back-furnitur shadow-sm" style="margin-right: 10px;">
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
                            <strong>Ada error nih:</strong>
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
                                    <form method="GET" action="{{ url()->current() }}" class="d-flex flex-wrap align-items-center gap-5 mb-4 pb-4 border-bottom" style="border-color: #f3f4f6 !important; ">
                                            <div class="position-relative" style="flex: 1; min-width: 200px; max-width: 260px; margin-right: 8px;">
                                                <input type="text" name="search" class="form-control shadow-none py-0 ps-4 pe-3" placeholder="Cari nama penyewa..."
                                                    value="{{ request('search') }}"
                                                    style="border-radius:8px; font-size:13.5px; margin-right: 12px; border: 1px solid #d1d5db; background-color: #fdfdfd; height: 40px; line-height: 40px; padding: 0 12px 0 36px;">
                                                <i class="ti-search position-absolute text-muted" style="left: 13px; top: 50%; margin-right: 8px; transform: translateY(-50%); font-size: 12px;"></i>
                                            </div>

                                            <select name="status" class="form-select shadow-none text-muted fw-medium"
                                                style="border-radius:8px; width:150px; font-size:13.5px; margin-right: 8px; border: 1px solid #d1d5db; height:40px; padding: 0 12px; cursor: pointer;">
                                                <option value="Semua" {{ request('status','Semua')==='Semua'?'selected':'' }}>Semua Status</option>
                                                <option value="aktif"   {{ request('status')==='aktif'  ?'selected':'' }}>Aktif</option>
                                                <option value="selesai" {{ request('status')==='selesai'?'selected':'' }}>Selesai</option>
                                            </select>

                                            <select name="id_furnitur" class="form-select shadow-none text-muted fw-medium"
                                                style="border-radius:8px; width:190px; font-size:13.5px; margin-right: 12px; border: 1px solid #d1d5db; height:40px; padding: 0 12px; cursor: pointer;">
                                                <option value="">Semua Furnitur</option>
                                                @foreach($furniturList as $f)
                                                    <option value="{{ $f->id_furnitur }}" {{ request('id_furnitur')==$f->id_furnitur?'selected':'' }}>{{ $f->nama_furnitur }}</option>
                                                @endforeach
                                            </select>

                                            <button type="submit" class="btn border-0 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="background: linear-gradient(135deg, #00a669, #008a57); color:white; width:40px; height:40px; border-radius:8px; transition: transform 0.2s;">
                                                <i class="ti-search" style="font-size:14px;"></i>
                                            </button>

                                            @if(request()->hasAny(['search','status','id_furnitur']))
                                            <a href="{{ route('penyewa-furnitur.index') }}" class="btn border-0 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="background:#f3f4f6; color:#6b7280; width:40px; height:40px; border-radius:8px; transition: background 0.2s;" title="Reset filter"
                                                onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                                <i class="ti-reload" style="font-size:13px;"></i>
                                            </a>
                                            @endif
                                        </form>

                                    {{-- Tabel --}}
                                    <div class="table-responsive">
                                        <table class="table align-middle" style="border-collapse:separate; border-spacing:0; min-width:900px;">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 50px;">No</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 150px;">Penyewa</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 150px;">Furnitur</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 100px;">Kamar</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">Kode Barang</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">Tgl Mulai</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">Tgl Selesai</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 100px;">Status</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 text-center px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 240px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($penyewaFurnitur->currentPage() - 1) * $penyewaFurnitur->perPage() + 1; @endphp
                                                @forelse($penyewaFurnitur as $item)
                                                <tr class="table-row-hover" style="transition: background 0.2s;">
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6;">{{ $no++ }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">
                                                        <div class="fw-bold" style="color:#111827; margin-bottom: 8px; font-size:14px;">{{ $item->user->nama ?? '-' }}</div>
                                                        <div style="color:#; font-size:12px;">{{ $item->user->no_telepon ?? '' }}</div>
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap fw-600" style="font-size: 14px; border-color: #f1f2f6;">{{ $item->item->furnitur->nama_furnitur ?? '-' }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">
                                                        @if($item->booking && $item->booking->kamar)
                                                            <span class="badge rounded-pill" style="background:#f0fdf4; color:#00a669; font-size:12px; font-weight:600;">
                                                                {{ $item->booking->kamar->nomor_kamar }}
                                                            </span>
                                                        @else
                                                            <span style="color:#9ca3af; font-size:13px;">Tanpa Booking</span>
                                                        @endif
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">
                                                        <span class="badge rounded-pill" style="background:#eff6ff; color:#3b82f6; font-size:12px; font-weight:600;">
                                                            {{ $item->item->kode_item ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">
                                                        {{ $item->tgl_mulai ? \Carbon\Carbon::parse($item->tgl_mulai)->format('d M Y') : '-' }}
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">
                                                        {{ $item->tgl_selesai ? \Carbon\Carbon::parse($item->tgl_selesai)->format('d M Y') : '-' }}
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">
                                                        @if($item->status === 'aktif')
                                                            <span class="badge rounded-pill" style="background:#ecfdf5; color:#00a669; font-size:12px; font-weight:600; padding:5px 12px;">Aktif</span>
                                                        @else
                                                            <span class="badge rounded-pill" style="background:#f0f9ff; color:#0284c7; font-size:12px; font-weight:600; padding:5px 12px;">Selesai</span>
                                                        @endif
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-center bg-transparent text-nowrap" style="border-color: #f1f2f6;">
                                                        <a href="#" onclick="loadDetail({{ $item->id_penyewa_furnitur }}, '{{ route('penyewa-furnitur.show', $item->id_penyewa_furnitur) }}'); return false;"
                                                            class="badge rounded-pill text-white text-decoration-none px-4 py-2"
                                                            style="background: linear-gradient(135deg, #3b82f6, #2563eb); font-size: 13px; font-weight: 500; transition: opacity 0.2s;"
                                                            onmouseover="this.style.opacity='0.85';" onmouseout="this.style.opacity='1';">
                                                            <i class=""></i> Detail
                                                        </a>
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
    height: 40px;
    padding: 0 18px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 13.5px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none;
    transition: opacity 0.2s;
    white-space: nowrap;
}
.btn-tambah {
    background: linear-gradient(135deg, #00a669, #008a57);
    color: white;
    border: none;
}

.btn-back-furnitur {
    background: #ffffff;
    color: #374151;
    border: 1.5px solid #e5e7eb;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 13.5px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.btn-back-furnitur:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #111827;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-back-furnitur:active {
    transform: translateY(0px);
    box-shadow: none;
}

.btn-tambah:hover { opacity: 0.9; color: white; }
.btn-back-furnitur {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #e5e7eb;
    transition: background 0.2s;
}
.btn-back-furnitur:hover { background: #e5e7eb; color: #111827; }
        .table-row-hover:hover { background-color: rgba(0,166,105,0.03) !important; }
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

    // Helper: buka modal manual
    function openModal(id) {
        const el = document.getElementById(id);
        el.classList.add('show');
        el.style.display = 'block';
        el.removeAttribute('aria-hidden');
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';

        let bd = document.getElementById('globalBackdrop');
        if (!bd) {
            bd = document.createElement('div');
            bd.id = 'globalBackdrop';
            bd.className = 'modal-backdrop fade show';
            document.body.appendChild(bd);
        }
    }

    // Helper: tutup modal manual
    function closeModal(id) {
        const el = document.getElementById(id);
        el.classList.remove('show');
        el.style.display = 'none';
        el.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';

        const bd = document.getElementById('globalBackdrop');
        if (bd) bd.remove();
    }

    // Tutup modal saat klik backdrop
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal') && e.target.classList.contains('show')) {
            closeModal(e.target.id);
        }
    });

    // Semua tombol data-bs-dismiss="modal"
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-bs-dismiss="modal"]');
        if (btn) {
            const modal = btn.closest('.modal');
            if (modal) closeModal(modal.id);
        }
    });

    // Load detail via AJAX
    function loadDetail(id, url) {
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(res => {
            if (!res.success) return alert('Data tidak ditemukan');
            const d = res.data;

            document.getElementById('detail-penyewa').textContent     = d.penyewa ?? '-';
            document.getElementById('detail-email').textContent       = d.penyewa_email ?? '-';
            document.getElementById('detail-telp').textContent        = d.penyewa_telp ?? '-';
            document.getElementById('detail-furnitur').textContent    = d.furnitur ?? '-';
            document.getElementById('detail-harga').textContent       = d.harga ?? '-';
            document.getElementById('detail-kode').textContent        = d.kode_item ?? '-';
            document.getElementById('detail-kamar').textContent       = d.kamar ?? '-';
            document.getElementById('detail-tgl-mulai').textContent   = d.tgl_mulai ?? '-';
            document.getElementById('detail-tgl-selesai').textContent = d.tgl_selesai ?? '-';

            const badge = document.getElementById('detail-status');
            badge.textContent      = d.status === 'aktif' ? 'Aktif' : 'Selesai';
            badge.style.background = d.status === 'aktif' ? '#ecfdf5' : '#f0f9ff';
            badge.style.color      = d.status === 'aktif' ? '#00a669' : '#0284c7';

            openModal('detailModal');
        })
        .catch(err => {
            console.error(err);
            alert('Gagal memuat detail: ' + err.message);
        });
    }

    // Open edit modal
    function openEdit(id, status, tglSelesai, catatan) {
        document.getElementById('editFormAction').action = `/dashboard/penyewa-furnitur/${id}`;
        document.getElementById('editStatus').value      = status;
        document.getElementById('editTglSelesai').value  = tglSelesai || '';
        document.getElementById('editCatatan').value     = catatan || '';
        openModal('editModal');
    }

    // Open hapus modal
    function openHapus(id, nama) {
        document.getElementById('hapusFormAction').action = `/dashboard/penyewa-furnitur/${id}`;
        document.getElementById('hapusNama').textContent  = nama;
        openModal('hapusModal');
    }
</script>
@endsection
