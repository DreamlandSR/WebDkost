@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper furnitur-table-wrapper" style="background-color: #fafbfc; min-height: 100vh;">

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-bold mb-0" style="color: #000; letter-spacing: -0.5px; font-size: 26px;">Kelola Furnitur</h2>
                                </div>
                                <div class="d-flex align-items-center mt-3 mt-md-0" style="font-size:14px; color:#888;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 8px"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('penyewa-furnitur.index') }}" class="btn-track-furnitur shadow-sm" style="margin-right: 12px;">
                                <i class="ti-pie-chart"></i> Track Furnitur Penyewa
                            </a>
                            <button type="button" class="btn-tambah shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahFurnitureModal">
                                <i class="ti-plus"></i> Tambah Furnitur
                            </button>
                        </div>
                    </div>

                    <!-- Custom Alert Notifications -->
                    @if(session('success'))
                    <div class="custom-alert success" id="successAlert">
                        <div class="custom-alert-icon">
                            <i class="ti-check"></i>
                        </div>
                        <div class="custom-alert-content">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="custom-alert-close" onclick="document.getElementById('successAlert').style.display='none'">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="custom-alert error" id="errorAlert">
                        <div class="custom-alert-icon">
                            <i class="ti-alert"></i>
                        </div>
                        <div class="custom-alert-content">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="custom-alert-close" onclick="document.getElementById('errorAlert').style.display='none'">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="custom-alert error" id="validationAlert">
                        <div class="custom-alert-icon">
                            <i class="ti-alert"></i>
                        </div>
                        <div class="custom-alert-content">
                            <strong>Ada error nih:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="custom-alert-close" onclick="document.getElementById('validationAlert').style.display='none'">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-0 rounded-4" style="box-shadow: 0 4px 30px rgba(0,0,0,0.03);">
                                <div class="card-body p-4 p-md-5">

                                    <form method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-2 gap-3 w-100">
                                        <div class="d-flex align-items-center w-100 mt-2 mt-md-0 d-md-flex justify-content-md-end" style="gap: 10px; max-width: 320px; ">
                                            <input type="text" name="search" class="form-control shadow-none w-100" placeholder="Cari nama furnitur" value="{{ request('search') }}"  style="border-radius:8px; font-size:13.5px; margin-right: 12px; border: 1px solid #d1d5db; background-color: #fdfdfd; height: 40px; line-height: 40px; padding: 0 12px 0 36px;">
                                            <button type="submit" class="btn border-0 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="background-color: #00a669; color: white; padding: 0; width: 36px; height: 36px; border-radius: 4px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                                <i class="ti-search" style="font-size: 15px;"></i>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="table-responsive" style="width: 100% !important; max-width: 100vw; overflow-x: auto; -webkit-overflow-scrolling: touch; display: block;">
                                        <table class="table align-middle" style="border-collapse: separate; border-spacing: 0; min-width: 850px; white-space: nowrap;">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 50px;">No</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 250px;">Nama Furnitur</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 100px;">Jumlah Tersedia</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 150px;">Harga/Sewa Tambahan</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 text-center px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 240px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($furnitur->currentPage() - 1) * $furnitur->perPage() + 1; @endphp
                                                @forelse ($furnitur as $item)
                                                <tr class="table-row-hover" style="transition: background 0.2s;">
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6;">{{ $no++ }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap fw-600" style="font-size: 14px; border-color: #f1f2f6;">{{ $item->nama_furnitur ?? '-' }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">
                                                        <span class="badge rounded-pill" style="background-color: #eff6ff; color: #3b82f6; font-weight: 600; font-size: 12px;">{{ $item->jumlah ?? 0 }} unit</span>
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap fw-600" style="font-size: 14px; border-color: #f1f2f6;">Rp {{ number_format($item->harga_sewa_tambahan ?? 0, 0, ',', '.') }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-center bg-transparent text-nowrap" style="border-color: #f1f2f6;">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id_furnitur }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1" style="background-color: #4f46e5; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Edit</a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal{{ $item->id_furnitur }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1" style="background-color: #ef4444; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Hapus</a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id_furnitur }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2" style="background-color: #3b82f6; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Detail</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted bg-transparent">Tidak ada data furnitur ditemukan.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 mb-2 gap-4 text-center">
                                        <span class="text-muted" style="font-size: 15px; font-weight: 500; letter-spacing: -0.2px;">
                                            Menampilkan {{ $furnitur->firstItem() ?? 0 }} - {{ $furnitur->lastItem() ?? 0 }} data dari total {{ $furnitur->total() }} data
                                        </span>
                                        <div class="d-flex align-items-center" style="gap: 25px;">
                                            @if ($furnitur->onFirstPage())
                                                <span class="text-muted d-flex align-items-center" style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </span>
                                            @else
                                                <a href="{{ $furnitur->previousPageUrl() . '&search=' . request('search') }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </a>
                                            @endif

                                            @if ($furnitur->hasMorePages())
                                                <a href="{{ $furnitur->nextPageUrl() . '&search=' . request('search') }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
                                                    Selanjutnya <i class="ti-angle-right ms-2 fw-bold" style="font-size: 15px;"></i>
                                                </a>
                                            @else
                                                <span class="text-muted d-flex align-items-center" style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    Selanjutnya <i class="ti-angle-right ms-2 fw-bold" style="font-size: 15px;"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal Tambah Furnitur -->
                <div class="modal fade" id="tambahFurnitureModal" tabindex="-1" aria-labelledby="tambahFurnitureModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">

                            <!-- Header -->
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div style="background: #ecfdf5; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-plus" style="color: #00a669; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" id="tambahFurnitureModalLabel" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Tambah Furnitur Baru</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Isi detail furnitur di bawah ini</p>
                                        </div>
                                    </div>
                                    <button type="button" data-bs-dismiss="modal" aria-label="Close"
                                        style="background: #f3f4f6; border: none; border-radius: 50%; width: 33px; height: 33px; display:flex; align-items:center; justify-content:center; cursor:pointer; color: #6b7280; font-size: 13px; flex-shrink:0; transition: background 0.2s;"
                                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="modal-body" style="padding: 18px 26px 20px; background: #fff; max-height: 70vh; overflow-y: auto;">
                                <form action="{{ route('furnitur.store') }}" method="POST" id="formTambahFurnitur">
                                    @csrf

                                    <!-- Nama Furnitur -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-box"></i></span>
                                            Nama Furnitur <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nama_furnitur" required
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                            placeholder="Contoh: Lemari, Meja, Kursi"
                                            value="{{ old('nama_furnitur') }}"
                                            onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>

                                    <!-- Kode Barang Dinamis -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-barcode"></i></span>
                                            Kode Barang <span class="text-danger">*</span>
                                        </label>
                                        <div id="dynamic-kode-container">
                                            <div class="d-flex gap-2 mb-2 kode-row">
                                                <input type="text" name="kode_item[]" required
                                                    style="flex:1; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                                    placeholder="Contoh: LMR-01"
                                                    onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                                <button type="button" class="btn border-0 shadow-sm d-flex align-items-center justify-content-center btn-remove-kode" style="background:#fef2f2; color:#ef4444; width:45px; border-radius:10px; display:none;" onclick="removeKodeRow(this)">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" onclick="addKodeRow()" class="btn border-0 mt-1 d-inline-flex align-items-center gap-1" style="background: #f3f4f6; color: #374151; font-size: 12.5px; font-weight: 600; padding: 6px 12px; border-radius: 8px;">
                                            <i class="ti-plus" style="font-size:10px;"></i> Tambah Kode
                                        </button>
                                        <small class="text-muted d-block mt-2" style="font-size:11px;">Jumlah furnitur akan otomatis terhitung dari seberapa banyak kode yang Anda masukkan.</small>
                                    </div>

                                    <!-- Harga Sewa Tambahan -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                                            Harga Sewa Tambahan <span class="text-danger">*</span>
                                        </label>
                                        <div style="position:relative;">
                                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#00a669; font-size:14px; pointer-events:none;">Rp</span>
                                            <input type="number" name="harga_sewa_tambahan" required min="0"
                                                style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s; -moz-appearance: textfield;"
                                                placeholder="0"
                                                value="{{ old('harga_sewa_tambahan') }}"
                                                onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                        </div>
                                        <small class="text-muted" style="font-size: 11px; margin-top: 4px; display: block;">Biaya tambahan jika penyewa menggunakan furnitur ini</small>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-end" style="gap: 12px; margin-top: 24px;">
                                        <button type="button" data-bs-dismiss="modal"
                                            style="padding: 9px 22px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: white; font-weight: 600; font-size: 13.5px; color: #6b7280; cursor:pointer; transition: all 0.2s;"
                                            onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">
                                            Batal
                                        </button>
                                        <button type="submit" id="submitTambahBtn"
                                            style="padding: 9px 26px; border-radius: 8px; border: none; background: linear-gradient(135deg, #00a669, #008a57); color: white; font-weight: 600; font-size: 13.5px; cursor:pointer; transition: opacity 0.2s;"
                                            onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modals for Each Furnitur (Detail, Edit, Hapus) -->
                @foreach($furnitur as $item)

                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal{{ $item->id_furnitur }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <div style="background: #ecfdf5; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
                                            <i class="ti-info-alt" style="color: #00a669; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px;">Detail Furnitur</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px;">Informasi lengkap furnitur</p>
                                        </div>
                                    </div>
                                    <button type="button" data-bs-dismiss="modal"
                                        style="background: #f3f4f6; border: none; border-radius: 50%; width: 32px; height: 32px; display:flex; align-items:center; justify-content:center; color: #6b7280; font-size: 12px; transition: 0.2s;"
                                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="modal-body" style="padding: 26px; background: #fff;">
                                <div class="row g-3" style="row-gap: 16px;">
                                    <div class="col-12">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Nama Furnitur</p>
                                            <p class="mb-0 fw-bold" style="color: #111827; font-size: 14px;">{{ $item->nama_furnitur }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Jumlah Tersedia</p>
                                            <p class="mb-0 fw-bold" style="color: #111827; font-size: 14px;">{{ $item->jumlah }} unit</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Harga Sewa Tambahan</p>
                                            <p class="mb-0 fw-bold text-success" style="font-size: 14px;">Rp {{ number_format($item->harga_sewa_tambahan, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-2" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Daftar Kode Barang Fisik</p>
                                            
                                            <div class="d-flex flex-wrap gap-2">
                                                @forelse($item->items as $kode)
                                                    <span class="badge rounded-pill" style="
                                                        padding: 6px 12px; margin: 5px; font-size: 12px; font-weight: 500;
                                                        background: {{ $kode->status_item === 'Tersedia' ? '#ecfdf5' : ($kode->status_item === 'Disewa' ? '#eff6ff' : '#fef2f2') }};
                                                        color: {{ $kode->status_item === 'Tersedia' ? '#00a669' : ($kode->status_item === 'Disewa' ? '#3b82f6' : '#ef4444') }};
                                                        border: 1px solid {{ $kode->status_item === 'Tersedia' ? '#a7f3d0' : ($kode->status_item === 'Disewa' ? '#bfdbfe' : '#fecaca') }};
                                                    ">
                                                        <i class="ti-barcode me-1" style="font-size: 10px;"></i>
                                                        {{ $kode->kode_item }}
                                                        <span style="opacity: 0.7; margin-left: 4px; font-size: 10px;">({{ $kode->status_item }})</span>
                                                    </span>
                                                @empty
                                                    <span style="color: #9ca3af; font-size: 12.5px; font-style: italic;">Belum ada kode barang yang didaftarkan.</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 bg-white p-4 pt-0">
                                <button type="button" class="btn w-100 shadow-sm" data-bs-dismiss="modal"
                                    style="background: #00a669; color: white; border-radius: 8px; padding: 10px; font-weight: 600; font-size: 13.5px; border: none;">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal{{ $item->id_furnitur }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div style="background: #eff6ff; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-pencil-alt" style="color: #3b82f6; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Edit Furnitur</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Perbarui data furnitur {{ $item->nama_furnitur }}</p>
                                        </div>
                                    </div>
                                    <button type="button" data-bs-dismiss="modal" aria-label="Close"
                                        style="background: #f3f4f6; border: none; border-radius: 50%; width: 33px; height: 33px; display:flex; align-items:center; justify-content:center; cursor:pointer; color: #6b7280; font-size: 13px; flex-shrink:0; transition: background 0.2s;"
                                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="modal-body" style="padding: 18px 26px 20px; background: #fff; max-height: 70vh; overflow-y: auto;">
                                <form action="{{ route('furnitur.update', $item->id_furnitur) }}" method="POST" id="formEditFurnitur{{ $item->id_furnitur }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Nama Furnitur -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-box"></i></span>
                                            Nama Furnitur <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nama_furnitur" value="{{ $item->nama_furnitur }}" required
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>

                                    <!-- Jumlah -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-package"></i></span>
                                            Jumlah Tersedia (Otomatis)
                                        </label>
                                        <input type="number" value="{{ $item->jumlah }}" readonly
                                            style="width:100%; padding: 11px 14px; border: 1px solid #e5e7eb; background: #f9fafb; border-radius: 10px; font-size: 14px; color: #6b7280; outline: none; cursor: not-allowed;">
                                        <small class="text-muted d-block mt-2" style="font-size:11px;">Jumlah tidak bisa diedit langsung. Silakan kelola kode barang untuk mengubah jumlah.</small>
                                    </div>

                                    <!-- Harga Sewa Tambahan -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                                            Harga Sewa Tambahan <span class="text-danger">*</span>
                                        </label>
                                        <div style="position:relative;">
                                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#3b82f6; font-size:14px; pointer-events:none;">Rp</span>
                                            <input type="number" name="harga_sewa_tambahan" value="{{ $item->harga_sewa_tambahan }}" required min="0"
                                                style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s; -moz-appearance: textfield;"
                                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                        </div>
                                        <small class="text-muted" style="font-size: 11px; margin-top: 4px; display: block;">Biaya tambahan jika penyewa menggunakan furnitur ini</small>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-end" style="gap: 12px; margin-top: 24px;">
                                        <button type="button" data-bs-dismiss="modal"
                                            style="padding: 9px 22px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: white; font-weight: 600; font-size: 13.5px; color: #6b7280; cursor:pointer; transition: 0.2s;"
                                            onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">
                                            Batal
                                        </button>
                                        <button type="submit" id="submitEditBtn{{ $item->id_furnitur }}"
                                            style="padding: 9px 26px; border-radius: 8px; border: none; background: #3b82f6; color: white; font-weight: 600; font-size: 13.5px; cursor:pointer; transition: opacity 0.2s;"
                                            onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Hapus -->
                <div class="modal fade" id="hapusModal{{ $item->id_furnitur }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <div style="background: #fef2f2; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-trash" style="color: #ef4444; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px;">Hapus Barang ({{ $item->nama_furnitur }})</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px;">Pilih kode barang yang ingin dihapus</p>
                                        </div>
                                    </div>
                                    <button type="button" data-bs-dismiss="modal"
                                        style="background: #f3f4f6; border: none; border-radius: 50%; width: 32px; height: 32px; display:flex; align-items:center; justify-content:center; color: #6b7280; font-size: 12px; transition: 0.2s;"
                                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="modal-body" style="padding: 26px; background: #fff; max-height: 60vh; overflow-y: auto;">
                                @php
                                    $tersediaItems = $item->items->where('status_item', 'Tersedia');
                                @endphp
                                
                                @if($tersediaItems->count() > 0)
                                    <div class="list-group">
                                        @foreach($tersediaItems as $kode)
                                            <div class="list-group-item d-flex justify-content-between align-items-center mb-2" style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px 16px;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ti-barcode" style="color:#6b7280;"></i>
                                                    <span style="font-weight: 600; font-size: 14px; color: #111827;">{{ $kode->kode_item }}</span>
                                                </div>
                                                <form action="{{ route('furnitur.item.destroy', $kode->id_item) }}" method="POST" class="form-hapus-item" data-kode="{{ $kode->kode_item }}" style="margin:0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm" style="background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; border-radius: 8px; font-size: 12px; padding: 5px 12px; font-weight: 600; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="ti-package" style="font-size: 40px; color: #d1d5db; margin-bottom: 12px; display: block;"></i>
                                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 0;">Tidak ada barang dengan status 'Tersedia' yang dapat dihapus.</p>
                                    </div>
                                @endif
                                
                                <hr style="border-color: #f3f4f6; margin: 24px 0;">
                                
                                <div class="text-center">
                                    <p style="color: #6b7280; font-size: 12.5px; margin-bottom: 12px;">Atau hapus semua data furnitur ini beserta seluruh kode barangnya</p>
                                    <form action="{{ route('furnitur.destroy', $item->id_furnitur) }}" method="POST" class="form-hapus-seluruh" data-nama="{{ $item->nama_furnitur }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn text-white w-100 shadow-sm" style="background: #ef4444; border-radius: 8px; font-size: 13.5px; padding: 10px; font-weight: 600; border: none; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                            Hapus Seluruh Furnitur
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach

                <script>
                    // Auto hide alerts after 5 seconds
                    setTimeout(function() {
                        const alerts = document.querySelectorAll('.custom-alert');
                        alerts.forEach(alert => {
                            alert.style.display = 'none';
                        });
                    }, 5000);

                    // Konfirmasi hapus
                    document.querySelectorAll('form[id^="formHapus"]').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            if (!confirm('Apakah Anda benar-benar yakin ingin menghapus?')) {
                                e.preventDefault();
                                return false;
                            }
                        });
                    });

                    // Form edit validation
                    document.querySelectorAll('form[id^="formEditFurnitur"]').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            const inputs = this.querySelectorAll('input[required], textarea[required], select[required]');
                            let isValid = true;

                            inputs.forEach(input => {
                                if (!input.value.trim()) {
                                    input.style.borderColor = '#ef4444';
                                    isValid = false;
                                } else {
                                    input.style.borderColor = '';
                                }
                            });

                            if (!isValid) {
                                e.preventDefault();
                                alert('Mohon lengkapi semua field yang diperlukan!');
                                return false;
                            }
                        });
                    });
                </script>

                <style>
                    .btn-tambah {
                        background: linear-gradient(135deg, #00a669, #008a57);
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        font-weight: 500;
                        font-size: 13.5px;
                        cursor: pointer;
                        transition: opacity 0.2s;
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                    }

                    .btn-tambah:hover {
                        opacity: 0.9;
                        color: black;
                        text-decoration: none;
                    }

                    .btn-track-furnitur {
                        background: #ffffff;
                        color: #374151;
                        border: 1.5px solid #e5e7eb;
                        padding: 10px 20px;
                        border-radius: 8px;
                        font-weight: 500;
                        font-size: 13.5px;
                        cursor: pointer;
                        transition: opacity 0.2s;
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        text-decoration: none;
                    }

                    .btn-track-furnitur:hover {
                        background: #f9fafb;
                        border-color: #d1d5db;
                        color: #111827;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                        transform: translateY(-2px);
                        text-decoration: none;
                    }

                    .btn-track-furnitur:active {
                        transform: translateY(0px);
                        box-shadow: none;
                    }

                    .table-row-hover:hover {
                        background-color: rgba(0, 166, 105, 0.03) !important;
                    }

                    .custom-alert {
                        display: flex;
                        align-items: flex-start;
                        gap: 12px;
                        padding: 16px 20px;
                        border-radius: 10px;
                        margin-bottom: 20px;
                        animation: slideIn 0.3s ease-in-out;
                    }

                    .custom-alert.success {
                        background-color: #f0fdf4;
                        border: 1px solid #86efac;
                        color: #166534;
                    }

                    .custom-alert.error {
                        background-color: #fef2f2;
                        border: 1px solid #fecaca;
                        color: #991b1b;
                    }

                    .custom-alert-icon {
                        flex-shrink: 0;
                        font-size: 18px;
                    }

                    .custom-alert-content {
                        flex: 1;
                        font-size: 14px;
                    }

                    .custom-alert-close {
                        background: none;
                        border: none;
                        cursor: pointer;
                        color: inherit;
                        font-size: 16px;
                        flex-shrink: 0;
                        opacity: 0.7;
                        transition: opacity 0.2s;
                    }

                    .custom-alert-close:hover {
                        opacity: 1;
                    }

                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateY(-10px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .badge {
                        white-space: nowrap;
                    }

                    .rounded-4 {
                        border-radius: 16px !important;
                    }
                </style>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function addKodeRow() {
        const container = document.getElementById('dynamic-kode-container');
        const rows = container.querySelectorAll('.kode-row');
        
        const newRow = document.createElement('div');
        newRow.className = 'd-flex gap-2 mb-2 kode-row';
        newRow.innerHTML = `
            <input type="text" name="kode_item[]" required
                style="flex:1; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                placeholder="Contoh: LMR-02"
                onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
            <button type="button" class="btn border-0 shadow-sm d-flex align-items-center justify-content-center btn-remove-kode" style="background:#fef2f2; color:#ef4444; width:45px; border-radius:10px;" onclick="removeKodeRow(this)">
                <i class="ti-trash"></i>
            </button>
        `;
        container.appendChild(newRow);
        updateRemoveButtons();
    }

    function removeKodeRow(btn) {
        const container = document.getElementById('dynamic-kode-container');
        const rows = container.querySelectorAll('.kode-row');
        if (rows.length > 1) {
            btn.closest('.kode-row').remove();
        }
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const container = document.getElementById('dynamic-kode-container');
        const rows = container.querySelectorAll('.kode-row');
        const btns = container.querySelectorAll('.btn-remove-kode');
        
        if (rows.length === 1) {
            btns[0].style.display = 'none'; // Sembunyikan tombol hapus jika hanya sisa 1
        } else {
            btns.forEach(btn => btn.style.display = 'flex'); // Tampilkan semua tombol hapus
        }
    }

    // SweetAlert untuk konfirmasi Hapus
    document.addEventListener('DOMContentLoaded', function () {
        const hapusItemForms = document.querySelectorAll('.form-hapus-item');
        hapusItemForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const kode = this.getAttribute('data-kode');
                Swal.fire({
                    html: `
                        <div style="text-align: center; margin-top: 5px;">
                            <div style="background: #fef2f2; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                            </div>
                            <h5 style="color: #111827; font-size: 15.5px; font-weight: 700; margin-bottom: 8px; letter-spacing: -0.2px;">Hapus Kode Barang?</h5>
                            <p style="color: #6b7280; font-size: 13px; line-height: 1.5; margin-bottom: 0;">Apakah Anda yakin ingin menghapus kode barang <strong style="color: #111827;">${kode}</strong>?</p>
                        </div>
                    `,
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#f3f4f6',
                    confirmButtonText: '<span style="font-weight: 600; font-size: 13px;">Ya, Hapus</span>',
                    cancelButtonText: '<span style="font-weight: 600; font-size: 13px; color: #4b5563;">Batal</span>',
                    reverseButtons: true,
                    width: '320px',
                    padding: '24px 20px',
                    customClass: {
                        popup: 'rounded-4 shadow-lg border-0',
                        actions: 'mt-3 mb-0 w-100 justify-content-center',
                        confirmButton: 'rounded-3 px-4 py-2 border-0 mx-2',
                        cancelButton: 'rounded-3 px-4 py-2 border-0 mx-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        const hapusSeluruhForms = document.querySelectorAll('.form-hapus-seluruh');
        hapusSeluruhForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const nama = this.getAttribute('data-nama');
                Swal.fire({
                    html: `
                        <div style="text-align: center; margin-top: 5px;">
                            <div style="background: #fef2f2; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"></path></svg>
                            </div>
                            <h5 style="color: #111827; font-size: 15.5px; font-weight: 700; margin-bottom: 8px; letter-spacing: -0.2px;">Hapus Seluruh Furnitur?</h5>
                            <p style="color: #6b7280; font-size: 13px; line-height: 1.5; margin-bottom: 16px;">Anda akan menghapus kategori <strong style="color: #111827;">${nama}</strong> beserta riwayatnya.</p>
                            <span style="background: #fef2f2; color: #ef4444; padding: 6px 12px; border-radius: 6px; font-size: 11.5px; font-weight: 600;">Tindakan ini tidak dapat dibatalkan</span>
                        </div>
                    `,
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#f3f4f6',
                    confirmButtonText: '<span style="font-weight: 600; font-size: 13px;">Hapus Semua</span>',
                    cancelButtonText: '<span style="font-weight: 600; font-size: 13px; color: #4b5563;">Batal</span>',
                    reverseButtons: true,
                    width: '340px',
                    padding: '24px 20px',
                    customClass: {
                        popup: 'rounded-4 shadow-lg border-0',
                        actions: 'mt-4 mb-0 w-100 justify-content-center',
                        confirmButton: 'rounded-3 px-3 py-2 border-0 mx-2',
                        cancelButton: 'rounded-3 px-4 py-2 border-0 mx-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
