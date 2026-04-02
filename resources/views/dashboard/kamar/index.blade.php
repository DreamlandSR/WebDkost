@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper pengeluaran-table-wrapper" style="background-color: #fafbfc; min-height: 100vh;">

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-bold mb-0" style="color: #000; letter-spacing: -0.5px; font-size: 26px;">Kelola Kamar</h2>
                                </div>
                                <div class="d-flex align-items-center mt-3 mt-md-0" style="font-size:14px; color:#888;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 8px"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="button" class="btn-tambah shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahKamarModal">
                                <i class="ti-plus"></i> Tambah Kamar
                            </button>
                        </div>
                    </div>

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

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-0 rounded-4" style="box-shadow: 0 4px 30px rgba(0,0,0,0.03);">
                                <div class="card-body p-4 p-md-5">

                                    <form method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-2 gap-3 w-100">
                                        <div class="d-flex align-items-center" style="gap: 15px;">
                                            <label class="text-muted fw-medium mb-0" style="font-size: 15px; white-space: nowrap;">Filter status</label>
                                            <select name="status" class="form-select shadow-none" style="width: 140px; border-radius: 4px; padding: 6px 12px; font-size: 14px; cursor: pointer;" onchange="this.form.submit()">
                                                <option value="Semua" {{ request('status') == 'Semua' || !request('status') ? 'selected' : '' }}>Semua</option>
                                                <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                <option value="Terisi" {{ request('status') == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                                                <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                            </select>
                                        </div>

                                        <div class="d-flex align-items-center w-100 mt-2 mt-md-0 d-md-flex justify-content-md-end" style="gap: 10px; max-width: 320px;">
                                            <input type="text" name="search" class="form-control shadow-none w-100" placeholder="Cari nomor kamar" value="{{ request('search') }}" style="border-radius: 4px; padding: 6px 12px; font-size: 14px;">
                                            <button type="submit" class="btn border-0 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="background-color: #00a669; color: white; padding: 0; width: 36px; height: 36px; border-radius: 4px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                                <i class="ti-search" style="font-size: 15px;"></i>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="table-responsive" style="width: 100% !important; max-width: 100vw; overflow-x: auto; -webkit-overflow-scrolling: touch; display: block;">
                                        <table class="table align-middle" style="border-collapse: separate; border-spacing: 0; min-width: 950px; white-space: nowrap;">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 50px;">No</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">Nomor Kamar</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 110px;">Tipe</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 110px;">Status</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 130px;">Harga/Bulan</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 250px;">Deskripsi</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 text-center px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 240px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($kamars->currentPage() - 1) * $kamars->perPage() + 1; @endphp
                                                @forelse ($kamars as $kamar)
                                                <tr class="table-row-hover" style="transition: background 0.2s;">
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6;">{{ $no++ }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap fw-600" style="font-size: 14px; border-color: #f1f2f6;">{{ $kamar->nomor_kamar ?? '-' }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ $kamar->tipe_kamar ?? '-' }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">
                                                        @if($kamar->status_kamar == 'tersedia')
                                                            <span class="badge rounded-pill" style="background-color: #ecfdf5; color: #00a669; font-weight: 600; font-size: 12px;">Tersedia</span>
                                                        @elseif($kamar->status_kamar == 'terisi')
                                                            <span class="badge rounded-pill" style="background-color: #fef2f2; color: #ef4444; font-weight: 600; font-size: 12px;">Terisi</span>
                                                        @else
                                                            <span class="badge rounded-pill" style="background-color: #fef3c7; color: #d97706; font-weight: 600; font-size: 12px;">Maintenance</span>
                                                        @endif
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap fw-600" style="font-size: 14px; border-color: #f1f2f6;">Rp {{ number_format($kamar->harga_per_bulan ?? 0, 0, ',', '.') }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6; max-width: 250px;" title="{{ $kamar->deskripsi }}">
                                                        <div class="text-truncate" style="max-width: 100%;">{{ Str::limit($kamar->deskripsi ?? '-', 40) }}</div>
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-center bg-transparent text-nowrap" style="border-color: #f1f2f6;">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $kamar->id_kamar }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1" style="background-color: #4f46e5; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Edit</a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal{{ $kamar->id_kamar }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1" style="background-color: #ef4444; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Hapus</a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal{{ $kamar->id_kamar }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2" style="background-color: #3b82f6; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Detail</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-5 text-muted bg-transparent">Tidak ada data kamar ditemukan.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 mb-2 gap-4 text-center">
                                        <span class="text-muted" style="font-size: 15px; font-weight: 500; letter-spacing: -0.2px;">
                                            Menampilkan {{ $kamars->firstItem() ?? 0 }} - {{ $kamars->lastItem() ?? 0 }} data dari total {{ $kamars->total() }} data
                                        </span>
                                        <div class="d-flex align-items-center" style="gap: 25px;">
                                            @if ($kamars->onFirstPage())
                                                <span class="text-muted d-flex align-items-center" style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </span>
                                            @else
                                                <a href="{{ $kamars->previousPageUrl() . '&status=' . request('status') . '&search=' . request('search') }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </a>
                                            @endif

                                            @if ($kamars->hasMorePages())
                                                <a href="{{ $kamars->nextPageUrl() . '&status=' . request('status') . '&search=' . request('search') }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
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

                <!-- Modal Tambah Kamar -->
                <div class="modal fade" id="tambahKamarModal" tabindex="-1" aria-labelledby="tambahKamarModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">

                            <!-- Header -->
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div style="background: #ecfdf5; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-home" style="color: #00a669; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" id="tambahKamarModalLabel" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Tambah Kamar Baru</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Isi detail kamar di bawah ini</p>
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
                                @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="border-radius: 10px;">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; right: 10px; top: 10px;"></button>
                                </div>
                                @endif

                                <form action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data" id="formTambahKamar">
                                    @csrf

                                    <!-- Nomor Kamar -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-home"></i></span>
                                            Nomor Kamar <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nomor_kamar" required
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                            placeholder="Contoh: 101, A1"
                                            value="{{ old('nomor_kamar') }}"
                                            onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>

                                    <!-- Tipe Kamar -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-tag"></i></span>
                                            Tipe Kamar <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex flex-wrap" style="gap: 10px;" id="tipeGrid">
                                            @foreach(['biasa', 'sedang', 'mewah'] as $tipe)
                                            <label class="tipe-pill" style="cursor:pointer;">
                                                <input type="radio" name="tipe_kamar" value="{{ $tipe }}" required style="display:none;" onchange="selectTipe(this)" {{ old('tipe_kamar') == $tipe ? 'checked' : '' }}>
                                                <span class="pill-label" style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $tipe }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Status Kamar -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-info-alt"></i></span>
                                            Status Kamar <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex flex-wrap" style="gap: 10px;" id="statusGrid">
                                            @foreach(['Tersedia', 'Terisi', 'Maintenance'] as $status)
                                            <label class="status-pill" style="cursor:pointer;">
                                                <input type="radio" name="status_kamar" value="{{ $status }}" required style="display:none;" onchange="selectStatus(this)" {{ old('status_kamar') == $status ? 'checked' : '' }}>
                                                <span class="pill-label" style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $status }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Harga -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                                            Harga/Bulan <span class="text-danger">*</span>
                                        </label>
                                        <div style="position:relative;">
                                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#00a669; font-size:14px; pointer-events:none;">Rp</span>
                                            <input type="number" name="harga" required min="0"
                                                style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s; -moz-appearance: textfield;"
                                                placeholder="0"
                                                value="{{ old('harga') }}"
                                                onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-pencil-alt"></i></span>
                                            Deskripsi
                                        </label>
                                        <textarea name="deskripsi" rows="2"
                                            style="width:100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; resize: none; transition: 0.2s;"
                                            placeholder="Deskripsi kamar..."
                                            onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">{{ old('deskripsi') }}</textarea>
                                    </div>

                                    <!-- Image Upload -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-image"></i></span>
                                            Foto Kamar
                                        </label>
                                        <div class="image-input-wrapper">
                                            <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;" onchange="previewImage(event)">
                                            <label for="imageInput" class="image-upload-label" style="display:block; border: 2px dashed #e5e7eb; border-radius: 10px; padding: 20px; text-align:center; cursor:pointer; transition: 0.2s;"
                                                onmouseover="this.style.borderColor='#00a669'; this.style.backgroundColor='#f9fafb';"
                                                onmouseout="this.style.borderColor='#e5e7eb'; this.style.backgroundColor='white';">
                                                <i class="ti-image" style="font-size: 32px; color: #9ca3af; display:block; margin-bottom:8px;"></i>
                                                <p style="margin:0; color: #6b7280; font-size: 13px;">Klik atau drag gambar ke sini</p>
                                                <p style="margin:4px 0 0 0; color: #9ca3af; font-size: 12px;">PNG, JPG, GIF (max 2MB)</p>
                                            </label>
                                            <div id="imagePreview"></div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-end" style="gap: 12px;">
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

                <!-- Modals for Each Kamar (Detail, Edit, Hapus) -->
                @foreach($kamars as $kamar)

                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal{{ $kamar->id_kamar }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <div style="background: #ecfdf5; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
                                            <i class="ti-info-alt" style="color: #00a669; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px;">Detail Kamar</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px;">Informasi lengkap kamar {{ $kamar->nomor_kamar }}</p>
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
                                <div class="row g-4">
                                    @php $mainImage = $kamar->galeri ? $kamar->galeri->firstWhere('is_main', 1) : null; @endphp
                                    @if($mainImage)
                                    <div class="col-12">
                                        <div style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px; overflow: hidden;">
                                            <img src="{{ asset('storage/' . $mainImage->url_foto) }}" alt="{{ $kamar->nomor_kamar }}" style="width: 100%; height: 250px; object-fit: cover;">
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Kamar</p>
                                            <p class="mb-0 fw-bold" style="color: #111827; font-size: 14px;">{{ $kamar->nomor_kamar }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Tipe</p>
                                            <p class="mb-0 fw-bold" style="color: #111827; font-size: 14px;">{{ $kamar->tipe_kamar }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                                            <p class="mb-0 fw-bold" style="color: #111827; font-size: 14px;">{{ $kamar->status_kamar }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Harga/Bulan</p>
                                            <p class="mb-0 fw-bold text-success" style="font-size: 14px;">Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px; border-left: 3px solid #00a669;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Deskripsi</p>
                                            <p class="mb-0" style="color: #4b5563; font-size: 13.5px; line-height: 1.6;">{{ $kamar->deskripsi ?? '-' }}</p>
                                        </div>
                                    </div>
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
                </div>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal{{ $kamar->id_kamar }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div style="background: #eff6ff; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-pencil-alt" style="color: #3b82f6; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Edit Kamar</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Perbarui data kamar {{ $kamar->nomor_kamar }}</p>
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
                                <form action="{{ route('kamar.update', $kamar->id_kamar) }}" method="POST" enctype="multipart/form-data" id="formEditKamar{{ $kamar->id_kamar }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Nomor Kamar -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-home"></i></span>
                                            Nomor Kamar <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nomor_kamar" value="{{ $kamar->nomor_kamar }}" required
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>

                                    <!-- Tipe Kamar -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-tag"></i></span>
                                            Tipe Kamar <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex flex-wrap" style="gap: 10px;">
                                            @foreach(['biasa', 'sedang', 'mewah'] as $tipe)
                                            <label class="tipe-pill-edit" style="cursor:pointer;">
                                                <input type="radio" name="tipe_kamar" value="{{ $tipe }}" {{ $kamar->tipe_kamar == $tipe ? 'checked' : '' }} required style="display:none;" onchange="updateEditPill(this, 'tipe', {{ $kamar->id_kamar }})">
                                                <span class="pill-label-tipe-{{ $kamar->id_kamar }} {{ $kamar->tipe_kamar == $tipe ? 'pill-active-edit' : '' }}"
                                                    style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $tipe }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Status Kamar -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-info-alt"></i></span>
                                            Status Kamar <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex flex-wrap" style="gap: 10px;">
                                            @foreach(['tersedia', 'terisi', 'maintenance'] as $status)
                                            <label class="status-pill-edit" style="cursor:pointer;">
                                                <input type="radio" name="status_kamar" value="{{ $status }}" {{ $kamar->status_kamar == $status ? 'checked' : '' }} required style="display:none;" onchange="updateEditPill(this, 'status', {{ $kamar->id_kamar }})">
                                                <span class="pill-label-status-{{ $kamar->id_kamar }} {{ $kamar->status_kamar == $status ? 'pill-active-edit' : '' }}"
                                                    style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $status }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Harga -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                                            Harga/Bulan <span class="text-danger">*</span>
                                        </label>
                                        <div style="position:relative;">
                                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#3b82f6; font-size:14px; pointer-events:none;">Rp</span>
                                            <input type="number" name="harga" value="{{ $kamar->harga_per_bulan }}" required min="0"
                                                style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s; -moz-appearance: textfield;"
                                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-pencil-alt"></i></span>
                                            Deskripsi
                                        </label>
                                        <textarea name="deskripsi" rows="2"
                                            style="width:100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; resize: none; transition: 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">{{ $kamar->deskripsi }}</textarea>
                                    </div>

                                    <!-- Image Upload -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-image"></i></span>
                                            Foto Kamar
                                        </label>
                                        @php $mainImage = $kamar->galeri ? $kamar->galeri->firstWhere('is_main', 1) : null; @endphp
                                        @if($mainImage)
                                        <div style="margin-bottom: 10px; text-align: center;">
                                            <img src="{{ asset('storage/' . $mainImage->url_foto) }}" alt="{{ $kamar->nomor_kamar }}" style="max-width: 150px; max-height: 100px; border-radius: 8px; border: 1px solid #e5e7eb;">
                                            <p style="font-size: 12px; color: #6b7280; margin: 6px 0 0 0;">Foto saat ini</p>
                                        </div>
                                        @endif
                                        <div class="image-input-wrapper">
                                            <input type="file" name="image" id="imageInput{{ $kamar->id_kamar }}" accept="image/*" style="display:none;" onchange="previewImageEdit(event, {{ $kamar->id_kamar }})">
                                            <label for="imageInput{{ $kamar->id_kamar }}" class="image-upload-label" style="display:block; border: 2px dashed #e5e7eb; border-radius: 10px; padding: 15px; text-align:center; cursor:pointer; transition: 0.2s;"
                                                onmouseover="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#f9fafb';"
                                                onmouseout="this.style.borderColor='#e5e7eb'; this.style.backgroundColor='white';">
                                                <i class="ti-image" style="font-size: 24px; color: #9ca3af; display:block; margin-bottom:6px;"></i>
                                                <p style="margin:0; color: #6b7280; font-size: 12px;">Klik atau drag untuk ubah</p>
                                            </label>
                                            <div id="imagePreviewEdit{{ $kamar->id_kamar }}"></div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-end" style="gap: 12px;">
                                        <button type="button" data-bs-dismiss="modal"
                                            style="padding: 9px 22px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: white; font-weight: 600; font-size: 13.5px; color: #6b7280; cursor:pointer; transition: 0.2s;"
                                            onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">
                                            Batal
                                        </button>
                                        <button type="submit" id="submitEditBtn{{ $kamar->id_kamar }}"
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
                <div class="modal fade" id="hapusModal{{ $kamar->id_kamar }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                            <div class="modal-body text-center" style="padding: 40px 30px;">
                                <div style="background: #fef2f2; border-radius: 50%; width: 70px; height: 70px; display:flex; align-items:center; justify-content:center; margin: 0 auto 24px;">
                                    <i class="ti-trash" style="color: #ef4444; font-size: 32px;"></i>
                                </div>
                                <h5 class="fw-bold mb-2" style="color: #111827; font-size: 18px;">Konfirmasi Hapus</h5>
                                <p class="mb-4" style="color: #6b7280; font-size: 14px; line-height: 1.5;">Apakah Anda yakin ingin menghapus kamar <strong>{{ $kamar->nomor_kamar }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>

                                <form action="{{ route('kamar.destroy', $kamar->id_kamar) }}" method="POST" id="formHapus{{ $kamar->id_kamar }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="d-flex flex-column gap-3">
                                        <button type="submit" class="btn text-white py-2 fw-bold" style="background: #ef4444; border-radius: 10px; font-size: 14px; border: none;">Ya, Hapus Sekarang</button>
                                        <button type="button" class="btn py-2 fw-600" data-bs-dismiss="modal" style="background: #f3f4f6; color: #4b5563; border-radius: 10px; font-size: 14px; border: none; margin-top: 12px;">Batalkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach

                <script>
// TAMBAHKAN INI DI AWAL SCRIPT UNTUK DEBUGGING
console.log('Script loaded successfully');
console.log('Jumlah kamar:', {{ $kamars->count() }});

// Tambahkan event listener untuk semua form edit dengan debugging
document.querySelectorAll('form[id^="formEditKamar"]').forEach(form => {
    console.log('Form edit ditemukan:', form.id);
    console.log('Action URL:', form.action);
    console.log('Method:', form.method);
    
    form.addEventListener('submit', function(e) {
        console.log('Form ' + this.id + ' disubmit');
        
        // Cek data yang akan dikirim
        const formData = new FormData(this);
        console.log('Data yang dikirim:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Validasi field yang required
        const nomorKamar = this.querySelector('input[name="nomor_kamar"]');
        const tipeKamar = this.querySelector('input[name="tipe_kamar"]:checked');
        const statusKamar = this.querySelector('input[name="status_kamar"]:checked');
        const harga = this.querySelector('input[name="harga"]');
        
        let errors = [];
        
        if (!nomorKamar.value.trim()) {
            errors.push('Nomor kamar harus diisi!');
            nomorKamar.style.border = '2px solid #ef4444';
            nomorKamar.focus();
        } else {
            nomorKamar.style.border = '';
        }
        
        if (!tipeKamar) {
            errors.push('Tipe kamar harus dipilih!');
        }
        
        if (!statusKamar) {
            errors.push('Status kamar harus dipilih!');
        }
        
        if (!harga.value || harga.value <= 0) {
            errors.push('Harga harus diisi dan lebih dari 0!');
            harga.style.border = '2px solid #ef4444';
        } else {
            harga.style.border = '';
        }
        
        if (errors.length > 0) {
            e.preventDefault();
            alert('❌ Gagal menyimpan:\n\n' + errors.join('\n'));
            console.log('Validation errors:', errors);
            return false;
        }
        
        // Konfirmasi sebelum submit
        if (!confirm('Apakah Anda yakin ingin menyimpan perubahan?')) {
            e.preventDefault();
            return false;
        }
        
        // Loading state
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton && !submitButton.disabled) {
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="ti-reload"></i> Menyimpan...';
            
            // Simpan original text untuk restore jika terjadi error
            this.setAttribute('data-original-text', originalText);
        }
        
        console.log('Form akan disubmit ke:', this.action);
        return true;
    });
    
    // Tambahkan event listener untuk error response
    form.addEventListener('submit-error', function(e) {
        console.error('Submit error:', e.detail);
        alert('❌ Terjadi kesalahan:\n\n' + (e.detail.message || 'Silakan coba lagi'));
        
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton && submitButton.disabled) {
            submitButton.disabled = false;
            submitButton.innerHTML = this.getAttribute('data-original-text') || 'Simpan Perubahan';
        }
    });
});

// Tambahkan interceptor untuk AJAX/fetch errors (jika menggunakan)
const originalFetch = window.fetch;
window.fetch = function(...args) {
    return originalFetch.apply(this, args).then(response => {
        if (!response.ok) {
            console.error('Fetch error:', response.status, response.statusText);
            if (response.status === 419) {
                alert('❌ Sesi telah berakhir. Silakan refresh halaman.');
            } else if (response.status === 500) {
                alert('❌ Terjadi kesalahan server. Silakan coba lagi.');
            }
        }
        return response;
    }).catch(error => {
        console.error('Network error:', error);
        alert('❌ Koneksi error. Periksa koneksi internet Anda.');
        throw error;
    });
};

// Tambahkan event listener untuk form tambah
const tambahForm = document.getElementById('formTambahKamar');
if (tambahForm) {
    tambahForm.addEventListener('submit', function(e) {
        console.log('Form tambah disubmit');
        
        // Validasi file upload
        const fileInput = this.querySelector('input[name="image"]');
        if (fileInput && fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            
            if (!validTypes.includes(file.type)) {
                e.preventDefault();
                alert('❌ Tipe file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                return false;
            }
            
            if (file.size > 2 * 1024 * 1024) {
                e.preventDefault();
                alert('❌ Ukuran file terlalu besar. Maksimal 2MB.');
                return false;
            }
        }
        
        return true;
    });
}

// Tambahkan event listener untuk menampilkan error dari session
document.addEventListener('DOMContentLoaded', function() {
    // Tampilkan error jika ada
    const errorAlert = document.querySelector('.custom-alert.error');
    if (errorAlert) {
        console.log('Error alert ditemukan:', errorAlert.innerText);
        setTimeout(() => {
            errorAlert.style.display = 'none';
        }, 5000);
    }
    
    // Tampilkan success jika ada
    const successAlert = document.querySelector('.custom-alert.success');
    if (successAlert) {
        console.log('Success alert ditemukan:', successAlert.innerText);
        setTimeout(() => {
            successAlert.style.display = 'none';
        }, 3000);
    }
    
    // Tambahkan style untuk field error
    const errorFields = document.querySelectorAll('.is-invalid');
    errorFields.forEach(field => {
        field.style.border = '2px solid #ef4444';
    });
});

// Fungsi untuk menampilkan error modal
function showErrorModal(message) {
    // Buat modal error jika belum ada
    let errorModal = document.getElementById('errorModal');
    if (!errorModal) {
        errorModal = document.createElement('div');
        errorModal.id = 'errorModal';
        errorModal.className = 'modal fade';
        errorModal.setAttribute('tabindex', '-1');
        errorModal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: #ef4444; color: white;">
                        <h5 class="modal-title">⚠️ Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="color: white;"></button>
                    </div>
                    <div class="modal-body">
                        <p id="errorMessage" style="color: #ef4444;"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(errorModal);
    }
    
    document.getElementById('errorMessage').innerText = message;
    const modal = new bootstrap.Modal(errorModal);
    modal.show();
}

// Fungsi untuk menampilkan success modal
function showSuccessModal(message) {
    let successModal = document.getElementById('successModal');
    if (!successModal) {
        successModal = document.createElement('div');
        successModal.id = 'successModal';
        successModal.className = 'modal fade';
        successModal.setAttribute('tabindex', '-1');
        successModal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: #00a669; color: white;">
                        <h5 class="modal-title">✓ Sukses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="color: white;"></button>
                    </div>
                    <div class="modal-body">
                        <p id="successMessage" style="color: #00a669;"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(successModal);
    }
    
    document.getElementById('successMessage').innerText = message;
    const modal = new bootstrap.Modal(successModal);
    modal.show();
}

// Override alert untuk debugging
const originalAlert = window.alert;
window.alert = function(message) {
    console.log('Alert:', message);
    originalAlert(message);
};


                    function selectTipe(radio) {
                        document.querySelectorAll('#tambahKamarModal .pill-label').forEach(el => {
                            el.style.background = '#f9fafb';
                            el.style.color = '#6b7280';
                            el.style.borderColor = '#e5e7eb';
                            el.style.fontWeight = '500';
                        });
                        const lbl = radio.nextElementSibling;
                        lbl.style.background = '#ecfdf5';
                        lbl.style.color = '#00a669';
                        lbl.style.borderColor = '#00a669';
                        lbl.style.fontWeight = '600';
                    }

                    function selectStatus(radio) {
                        document.querySelectorAll('#tambahKamarModal .status-pill .pill-label').forEach(el => {
                            el.style.background = '#f9fafb';
                            el.style.color = '#6b7280';
                            el.style.borderColor = '#e5e7eb';
                            el.style.fontWeight = '500';
                        });
                        const lbl = radio.nextElementSibling;
                        lbl.style.background = '#ecfdf5';
                        lbl.style.color = '#00a669';
                        lbl.style.borderColor = '#00a669';
                        lbl.style.fontWeight = '600';
                    }

                    function updateEditPill(radio, type, id) {
    if (type === 'tipe') {
        // Reset semua tipe
        document.querySelectorAll('.pill-label-tipe-' + id).forEach(el => {
            el.classList.remove('pill-active-edit');
            el.style.background = '#f9fafb';
            el.style.color = '#6b7280';
            el.style.borderColor = '#e5e7eb';
            el.style.fontWeight = '500';
        });
        // Set yang dipilih
        const selectedLabel = radio.nextElementSibling;
        selectedLabel.classList.add('pill-active-edit');
        selectedLabel.style.background = '#ecfdf5';
        selectedLabel.style.color = '#00a669';
        selectedLabel.style.borderColor = '#00a669';
        selectedLabel.style.fontWeight = '600';
    } 
    else if (type === 'status') {
        // Reset semua status
        document.querySelectorAll('.pill-label-status-' + id).forEach(el => {
            el.classList.remove('pill-active-edit');
            el.style.background = '#f9fafb';
            el.style.color = '#6b7280';
            el.style.borderColor = '#e5e7eb';
            el.style.fontWeight = '500';
        });
        // Set yang dipilih
        const selectedLabel = radio.nextElementSibling;
        selectedLabel.classList.add('pill-active-edit');
        selectedLabel.style.background = '#ecfdf5';
        selectedLabel.style.color = '#00a669';
        selectedLabel.style.borderColor = '#00a669';
        selectedLabel.style.fontWeight = '600';
    }
}

                    function previewImage(event) {
                        const file = event.target.files[0];
                        const preview = document.getElementById('imagePreview');
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.innerHTML = `<div style="margin-top: 10px; text-align: center;"><img src="${e.target.result}" alt="Preview" style="max-width: 150px; max-height: 120px; border-radius: 8px; border: 1px solid #e5e7eb;"></div>`;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            preview.innerHTML = '';
                        }
                    }

                    function previewImageEdit(event, id) {
                        const file = event.target.files[0];
                        const preview = document.getElementById('imagePreviewEdit' + id);
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.innerHTML = `<div style="margin-top: 10px; text-align: center;"><img src="${e.target.result}" alt="Preview" style="max-width: 150px; max-height: 100px; border-radius: 8px; border: 1px solid #e5e7eb;"></div>`;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            preview.innerHTML = '';
                        }
                    }

                    // Reset modal tambah saat ditutup
                    const tambahModal = document.getElementById('tambahKamarModal');
                    if (tambahModal) {
                        tambahModal.addEventListener('hidden.bs.modal', function () {
                            // Reset form
                            const form = this.querySelector('form');
                            if (form) form.reset();
                            
                            // Reset radio buttons style
                            document.querySelectorAll('#tambahKamarModal .pill-label').forEach(el => {
                                el.style.background = '#f9fafb';
                                el.style.color = '#6b7280';
                                el.style.borderColor = '#e5e7eb';
                                el.style.fontWeight = '500';
                            });
                            document.querySelectorAll('#tambahKamarModal input[type="radio"]').forEach(r => r.checked = false);
                            
                            // Reset preview gambar
                            document.getElementById('imagePreview').innerHTML = '';
                            
                            // Reset file input
                            const fileInput = document.getElementById('imageInput');
                            if (fileInput) fileInput.value = '';
                        });
                    }

                    // Loading state untuk form submit
                    document.querySelectorAll('form').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            const submitButton = this.querySelector('button[type="submit"]');
                            if (submitButton && !submitButton.disabled) {
                                const originalText = submitButton.innerHTML;
                                submitButton.disabled = true;
                                submitButton.innerHTML = '<i class="ti-reload"></i> Menyimpan...';
                                
                                // Optional: restore button after timeout if needed
                                setTimeout(() => {
                                    if (submitButton.disabled) {
                                        submitButton.disabled = false;
                                        submitButton.innerHTML = originalText;
                                    }
                                }, 5000);
                            }
                        });
                    });

                    // Konfirmasi hapus
                    document.querySelectorAll('form[id^="formHapus"]').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            if (!confirm('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) {
                                e.preventDefault();
                            }
                        });
                    });

                    // Auto hide alert after 3 seconds
                    setTimeout(function() {
                        const alerts = document.querySelectorAll('.custom-alert');
                        alerts.forEach(alert => {
                            alert.style.display = 'none';
                        });
                    }, 3000);
                </script>

                <style>
                    .pill-active-edit {
                        background: #ecfdf5 !important;
                        color: #00a669 !important;
                        border-color: #00a669 !important;
                        font-weight: 600 !important;
                    }

                    .btn-tambah {
                        background: linear-gradient(135deg, #00a669, #008a57) !important;
                        color: white !important;
                        border: none !important;
                        padding: 10px 24px !important;
                        border-radius: 8px !important;
                        font-weight: 600 !important;
                        font-size: 14px !important;
                        display: inline-flex !important;
                        align-items: center !important;
                        gap: 6px !important;
                        transition: all 0.2s !important;
                    }

                    .btn-tambah:hover {
                        opacity: 0.9 !important;
                        transform: translateY(-2px) !important;
                    }

                    .table-row-hover:hover {
                        background-color: #fafbfc !important;
                    }

                    .custom-alert {
                        display: flex;
                        align-items: center;
                        padding: 16px 20px;
                        border-radius: 10px;
                        margin-bottom: 20px;
                        font-size: 14px;
                        font-weight: 500;
                        animation: slideIn 0.3s ease-out;
                    }

                    .custom-alert.success {
                        background-color: #ecfdf5;
                        color: #00a669;
                        border-left: 4px solid #00a669;
                    }

                    .custom-alert.error {
                        background-color: #fef2f2;
                        color: #ef4444;
                        border-left: 4px solid #ef4444;
                    }

                    .custom-alert-icon {
                        margin-right: 12px;
                        font-size: 18px;
                    }

                    .custom-alert-content {
                        flex: 1;
                    }

                    .custom-alert-close {
                        background: none;
                        border: none;
                        color: inherit;
                        cursor: pointer;
                        font-size: 18px;
                        padding: 0;
                        margin-left: 12px;
                    }

                    .alert-danger {
                        background-color: #fef2f2;
                        border: 1px solid #fecaca;
                        color: #991b1b;
                        border-radius: 10px;
                        position: relative;
                    }

                    .btn-close {
                        background: transparent;
                        border: none;
                        font-size: 20px;
                        cursor: pointer;
                        padding: 0;
                        line-height: 1;
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
                </style>

            </div>
        </div>
    </div>
@endsection