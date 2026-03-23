@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <style>
                    /* Custom overrides for the UI strictly */
                    .keluhan-table-wrapper select.form-select,
                    .keluhan-table-wrapper input.form-control {
                        border: 1px solid #c9ccdd !important;
                    }
                    .keluhan-table-wrapper select.form-select:focus,
                    .keluhan-table-wrapper input.form-control:focus {
                        outline: none !important;
                        box-shadow: 0 0 0 0.15rem rgba(0, 166, 105, 0.25) !important;
                        border-color: #00a669 !important;
                    }

                    /* Optimasi Layar HP (Mobile View) */
                    @media (max-width: 991px) {
                        .keluhan-table-wrapper .card-body {
                            padding: 20px 14px !important;
                        }
                        .keluhan-table-wrapper select.form-select,
                        .keluhan-table-wrapper input.form-control {
                            padding: 4px 10px !important;
                            height: 32px !important;
                            font-size: 11.5px !important;
                        }
                        .keluhan-table-wrapper .d-md-flex.justify-content-md-end {
                            justify-content: flex-start !important;
                        }
                        .keluhan-table-wrapper .btn.border-0 {
                            width: 32px !important;
                            height: 32px !important;
                            padding: 0 !important;
                        }
                        .keluhan-table-wrapper .btn.border-0 i {
                            font-size: 13px !important;
                        }
                        .keluhan-table-wrapper .card-body form,
                        .keluhan-table-wrapper .table-responsive,
                        .keluhan-table-wrapper * {
                            font-size: 11.5px !important;
                        }
                        .keluhan-table-wrapper .table th,
                        .keluhan-table-wrapper .table td,
                        .keluhan-table-wrapper .table * {
                            font-size: 11px !important;
                            padding: 8px 6px !important;
                            white-space: nowrap !important;
                        }
                        .keluhan-table-wrapper h2 {
                            font-size: 19px !important;
                        }
                        .keluhan-table-wrapper .badge {
                            font-size: 10px !important;
                            padding: 4px 8px !important;
                        }
                        .keluhan-table-wrapper .border-bottom-2 {
                            min-width: unset !important;
                        }
                    }
                </style>
                <div class="content-wrapper keluhan-table-wrapper" style="background-color: #fafbfc; min-height: 100vh;">

                    <div class="row mb-5">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-bold mb-0" style="color: #000; letter-spacing: -0.5px; font-size: 26px;">Status keluhan</h2>
                                </div>
                                <div class="d-flex align-items-center" style="font-size:14px; color:#888;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 10px"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-0 rounded-4" style="box-shadow: 0 4px 30px rgba(0,0,0,0.03);">
                                <div class="card-body p-4 p-md-5">

                                    <form method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-2 gap-3 w-100">
                                        <div class="d-flex align-items-center" style="gap: 15px;">
                                            <label class="text-muted fw-medium mb-0" style="font-size: 15px; white-space: nowrap;">Filter status</label>
                                            <select name="status" class="form-select shadow-none" style="width: 140px; border-radius: 4px; padding: 6px 12px; font-size: 14px; cursor: pointer;" onchange="this.form.submit()">
                                                <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </div>

                                        <div class="d-flex align-items-center w-100 mt-2 mt-md-0 d-md-flex justify-content-md-end" style="gap: 10px; max-width: 320px;">
                                            <input type="text" name="search" class="form-control shadow-none w-100" placeholder="Cari nama" value="{{ request('search') }}" style="border-radius: 4px; padding: 6px 12px; font-size: 14px;">
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
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 160px;">Nama</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">Kamar</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 250px;">Keluhan</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 text-center px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 150px;">Status</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 text-center px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 180px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($keluhans->currentPage() - 1) * $keluhans->perPage() + 1; @endphp
                                                @forelse ($keluhans as $keluhan)
                                                <tr style="transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fcfcfd';" onmouseout="this.style.backgroundColor='transparent';">
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6;">{{ $no++ }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ \Illuminate\Support\Str::limit($keluhan->user->nama ?? 'Nama tidak diketahui', 25) }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ $keluhan->kamar->nomor_kamar ?? 'Kos Kecil 01' }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6; max-width: 300px;" title="{{ $keluhan->deskripsi_masalah ?? 'Lampu mati' }}">
                                                        <div class="text-truncate" style="max-width: 100%;">{{ $keluhan->deskripsi_masalah ?? 'Lampu mati' }}</div>
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-center bg-transparent text-nowrap" style="border-color: #f1f2f6;">
                                                        @if($keluhan->status_keluhan == 'pending')
                                                            <span class="badge rounded-pill text-white fw-medium px-4 py-2" style="background-color: #ffb200; font-size: 13px; font-weight: 500;">Menunggu</span>
                                                        @elseif($keluhan->status_keluhan == 'diproses')
                                                            <span class="badge rounded-pill text-white fw-medium px-4 py-2" style="background-color: #3b82f6; font-size: 13px; font-weight: 500;">Diproses</span>
                                                        @else
                                                            <span class="badge rounded-pill text-white fw-medium px-4 py-2" style="background-color: #4caf50; font-size: 13px; font-weight: 500;">Selesai</span>
                                                        @endif
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-center bg-transparent text-nowrap" style="border-color: #f1f2f6;">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $keluhan->id_keluhan }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-2" style="background-color: #4f46e5; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Edit</a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal{{ $keluhan->id_keluhan }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2" style="background-color: #3b82f6; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Detail</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted bg-transparent">Tidak ada data status keluhan ditemukan.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 mb-2 gap-4 text-center">
                                        <span class="text-muted" style="font-size: 15px; font-weight: 500; letter-spacing: -0.2px;">
                                            Menampilkan {{ $keluhans->firstItem() ?? 0 }} - {{ $keluhans->lastItem() ?? 0 }} data dari total {{ $keluhans->total() }} data
                                        </span>
                                        <div class="d-flex align-items-center" style="gap: 25px;">
                                            @if ($keluhans->onFirstPage())
                                                <span class="text-muted d-flex align-items-center" style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </span>
                                            @else
                                                <a href="{{ $keluhans->previousPageUrl() }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </a>
                                            @endif

                                            @if ($keluhans->hasMorePages())
                                                <a href="{{ $keluhans->nextPageUrl() }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
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
            </div>
        </div>
    </div>

    <!-- Modals untuk Detail Keluhan -->
    @foreach($keluhans as $keluhan)
    <div class="modal fade" id="detailModal{{ $keluhan->id_keluhan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 900px; margin-top: 5vh;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <!-- Header -->
                <div class="modal-header border-0 bg-white" style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9 !important;">
                    <div class="d-flex align-items-center w-100">
                        <div class="bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px; margin-right: 15px; border: 1px solid #e2e8f0;">
                            <i class="ti-info-alt" style="font-size: 18px; color: #4f46e5;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="modal-title fw-bold mb-1" style="color: #334155; font-size: 16px;">Detail Keluhan</h5>
                            <p class="text-muted mb-0" style="font-size: 12px;">Informasi lengkap mengenai laporan penghuni</p>
                        </div>
                        <button type="button" class="shadow-none d-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="background-color: transparent; border: 1px solid #94a3b8; border-radius: 50%; width: 32px; height: 32px; padding: 0; opacity: 1; transition: all 0.2s; cursor: pointer;" onmouseover="this.style.backgroundColor='#f1f5f9';" onmouseout="this.style.backgroundColor='transparent';">
                            <i class="ti-close" style="font-size: 13px; color: #0f172a; text-shadow: 0 0 1px #0f172a;"></i>
                        </button>
                    </div>
                </div>

                <div class="modal-body bg-white" style="padding: 28px;">
                    <div class="row gx-5">
                        <!-- Left Column: Image -->
                        <div class="col-md-5 mb-4 mb-md-0 d-flex flex-column">
                            <p class="mb-3" style="color: #64748b; font-size: 11px; font-weight: 600; text-transform: uppercase;">FOTO BUKTI</p>
                            <div class="position-relative flex-grow-1 d-flex flex-column" style="width: 100%; min-height: 280px; border-radius: 8px; overflow: hidden; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                @if($keluhan->foto_bukti)
                                    <img src="{{ asset('storage/' . $keluhan->foto_bukti) }}" alt="Bukti" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted w-100 position-absolute" style="top: 0; left: 0;">
                                        <i class="ti-image mb-2" style="font-size: 32px; color: #cbd5e1;"></i>
                                        <span style="font-size: 12px; font-weight: 500;">Tidak ada foto</span>
                                    </div>
                                @endif

                                <!-- Status Badge Float -->
                                <div class="position-absolute" style="top: 15px; right: 15px;">
                                    @if($keluhan->status_keluhan == 'pending')
                                        <span class="badge rounded-pill" style="background-color: #f59e0b; color: #fff; font-weight: 500; font-size: 11px; padding: 6px 12px;">Menunggu</span>
                                    @elseif($keluhan->status_keluhan == 'diproses')
                                        <span class="badge rounded-pill" style="background-color: #3b82f6; color: #fff; font-weight: 500; font-size: 11px; padding: 6px 12px;">Diproses</span>
                                    @else
                                        <span class="badge rounded-pill" style="background-color: #10b981; color: #fff; font-weight: 500; font-size: 11px; padding: 6px 12px;">Selesai</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Details grid -->
                        <div class="col-md-7 d-flex flex-column">
                            <p class="mb-3" style="color: #64748b; font-size: 11px; font-weight: 600; text-transform: uppercase;">INFORMASI PENGIRIM</p>

                            <!-- Gap in row using g-4 -->
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <div class="p-3 h-100" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px;">
                                        <p class="text-muted mb-2 d-flex align-items-center" style="font-size: 11px;">
                                            <i class="ti-user" style="color: #8b5cf6; margin-right: 10px;"></i> Nama Lengkap
                                        </p>
                                        <h6 class="mb-0 text-truncate" style="color: #1e293b; font-size: 13px; font-weight: 500;" title="{{ $keluhan->user->nama ?? '-' }}">
                                            {{ \Illuminate\Support\Str::limit($keluhan->user->nama ?? '-', 22) }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 h-100" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px;">
                                        <p class="text-muted mb-2 d-flex align-items-center" style="font-size: 11px;">
                                            <i class="ti-home" style="color: #10b981; margin-right: 10px;"></i> Kamar
                                        </p>
                                        <h6 class="mb-0 text-truncate" style="color: #1e293b; font-size: 13px; font-weight: 500;">
                                            {{ $keluhan->kamar->nomor_kamar ?? '-' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="p-3" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px;">
                                        <p class="text-muted mb-2 d-flex align-items-center" style="font-size: 11px;">
                                            <i class="ti-calendar" style="color: #f59e0b; margin-right: 10px;"></i> Tanggal Dilaporkan
                                        </p>
                                        <h6 class="mb-0" style="color: #1e293b; font-size: 13px; font-weight: 500;">
                                            {{ \Carbon\Carbon::parse($keluhan->tgl_lapor ?? $keluhan->created_at)->locale('id')->translatedFormat('l, d F Y') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <p class="mb-3 mt-1" style="color: #64748b; font-size: 11px; font-weight: 600; text-transform: uppercase;">DESKRIPSI MASALAH</p>
                            <div class="p-3 position-relative flex-grow-1" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; border-left: 3px solid #3b82f6;">
                                <p class="mb-0 position-relative" style="color: #475569; font-size: 13px; line-height: 1.6;">
                                    {{ $keluhan->deskripsi_masalah ?? 'Tidak ada deskripsi yang diberikan.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 d-flex justify-content-end bg-white" style="padding: 16px 24px; border-top: 1px solid #f1f5f9 !important;">
                    <button type="button" class="btn text-white px-4 shadow-sm" data-bs-dismiss="modal" style="background-color: #334155; border-radius: 6px; font-size: 13px; font-weight: 500; border: none; padding-top: 8px; padding-bottom: 8px;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Modals untuk Edit Status Keluhan -->
    <div class="modal fade" id="editModal{{ $keluhan->id_keluhan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 900px; margin-top: 5vh;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <form action="{{ route('keluhan.updateStatus', $keluhan->id_keluhan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Header -->
                    <div class="modal-header border-0 bg-white" style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9 !important;">
                        <div class="d-flex align-items-center w-100">
                            <div class="bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px; margin-right: 15px; border: 1px solid #e2e8f0;">
                                <i class="ti-pencil-alt" style="font-size: 18px; color: #4f46e5;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="modal-title fw-bold mb-1" style="color: #334155; font-size: 16px;">Edit Status Keluhan</h5>
                                <p class="text-muted mb-0" style="font-size: 12px;">Perbarui status penanganan laporan penghuni</p>
                            </div>
                            <button type="button" class="shadow-none d-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="background-color: transparent; border: 1px solid #94a3b8; border-radius: 50%; width: 32px; height: 32px; padding: 0; opacity: 1; transition: all 0.2s; cursor: pointer;" onmouseover="this.style.backgroundColor='#f1f5f9';" onmouseout="this.style.backgroundColor='transparent';">
                                <i class="ti-close" style="font-size: 13px; color: #0f172a; text-shadow: 0 0 1px #0f172a;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="modal-body bg-white" style="padding: 28px;">
                        <div class="row gx-5">
                            <!-- Left Column: Image -->
                            <div class="col-md-5 mb-4 mb-md-0 d-flex flex-column">
                                <p class="mb-3" style="color: #64748b; font-size: 11px; font-weight: 600; text-transform: uppercase;">FOTO BUKTI</p>
                                <div class="position-relative flex-grow-1 d-flex flex-column" style="width: 100%; min-height: 280px; border-radius: 8px; overflow: hidden; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                    @if($keluhan->foto_bukti)
                                        <img src="{{ asset('storage/' . $keluhan->foto_bukti) }}" alt="Bukti" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted w-100 position-absolute" style="top: 0; left: 0;">
                                            <i class="ti-image mb-2" style="font-size: 32px; color: #cbd5e1;"></i>
                                            <span style="font-size: 12px; font-weight: 500;">Tidak ada foto</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Right Column: Details grid -->
                            <div class="col-md-7 d-flex flex-column">
                                <p class="mb-3" style="color: #64748b; font-size: 11px; font-weight: 600; text-transform: uppercase;">INFORMASI PENGIRIM</p>

                                <div class="row g-4 mb-4">
                                    <div class="col-sm-6">
                                        <div class="p-3 h-100" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px;">
                                            <p class="text-muted mb-2 d-flex align-items-center" style="font-size: 11px;">
                                                <i class="ti-user" style="color: #8b5cf6; margin-right: 10px;"></i> Nama Lengkap
                                            </p>
                                            <h6 class="mb-0 text-truncate" style="color: #1e293b; font-size: 13px; font-weight: 500;" title="{{ $keluhan->user->nama ?? '-' }}">
                                                {{ \Illuminate\Support\Str::limit($keluhan->user->nama ?? '-', 22) }}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="p-3 h-100" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px;">
                                            <p class="text-muted mb-2 d-flex align-items-center" style="font-size: 11px;">
                                                <i class="ti-home" style="color: #10b981; margin-right: 10px;"></i> Kamar
                                            </p>
                                            <h6 class="mb-0 text-truncate" style="color: #1e293b; font-size: 13px; font-weight: 500;">
                                                {{ $keluhan->kamar->nomor_kamar ?? '-' }}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="p-3" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px;">
                                            <p class="text-muted mb-2 d-flex align-items-center" style="font-size: 11px;">
                                                <i class="ti-calendar" style="color: #f59e0b; margin-right: 10px;"></i> Tanggal Dilaporkan
                                            </p>
                                            <h6 class="mb-0" style="color: #1e293b; font-size: 13px; font-weight: 500;">
                                                {{ \Carbon\Carbon::parse($keluhan->tgl_lapor ?? $keluhan->created_at)->locale('id')->translatedFormat('l, d F Y') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-12">
                                        <p class="mb-3 mt-1" style="color: #64748b; font-size: 11px; font-weight: 600; text-transform: uppercase;">UPDATE STATUS KELUHAN</p>
                                        <div class="position-relative">
                                            <select name="status_keluhan" class="form-select w-100 shadow-none" style="font-size: 13px; color: #1e293b; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px 15px; padding-right: 35px; height: auto; appearance: none; -webkit-appearance: none; cursor: pointer;">
                                                <option value="pending" {{ $keluhan->status_keluhan == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                                                <option value="diproses" {{ $keluhan->status_keluhan == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                                <option value="selesai" {{ $keluhan->status_keluhan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                            <svg class="position-absolute" style="top: 50%; transform: translateY(-50%); right: 15px; pointer-events: none;" width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 6L0 0H10L5 6Z" fill="#64748b"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <p class="mb-3 mt-1" style="color: #64748b; font-size: 11px; font-weight: 600; text-transform: uppercase;">DESKRIPSI MASALAH</p>
                                        <div class="p-3 position-relative flex-grow-1" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; border-left: 3px solid #3b82f6;">
                                            <p class="mb-0 position-relative" style="color: #475569; font-size: 13px; line-height: 1.6;">
                                                {{ $keluhan->deskripsi_masalah ?? 'Tidak ada deskripsi yang diberikan.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-0 d-flex justify-content-end bg-white" style="padding: 16px 24px; border-top: 1px solid #f1f5f9 !important; gap: 10px;">
                        <button type="button" class="btn text-white px-4 shadow-sm" data-bs-dismiss="modal" style="background-color: #64748b; border-radius: 6px; font-size: 13px; font-weight: 500; border: none; padding-top: 8px; padding-bottom: 8px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#475569';" onmouseout="this.style.backgroundColor='#64748b';">
                            Batal
                        </button>
                        <button type="submit" class="btn text-white px-4 shadow-sm" style="background-color: #00a669; border-radius: 6px; font-size: 13px; font-weight: 500; border: none; padding-top: 8px; padding-bottom: 8px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#00905a';" onmouseout="this.style.backgroundColor='#00a669';">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endsection
