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
                                <div class="d-flex align-items-center gap-2" style="font-size:14px; color:#888;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
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
                                                        <a href="#" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-2" style="background-color: #4f46e5; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Edit</a>
                                                        <a href="#" class="badge rounded-pill text-white text-decoration-none px-4 py-2" style="background-color: #3b82f6; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Detail</a>
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
@endsection
