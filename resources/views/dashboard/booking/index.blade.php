@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper booking-table-wrapper" style="background-color: #fafbfc; min-height: 100vh;">

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-bold mb-0" style="color: #000; letter-spacing: -0.5px; font-size: 26px;">Kelola Booking</h2>
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
                            <button type="button" class="btn-tambah shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                <i class="ti-plus"></i> Tambah Booking
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
                            <strong>Terjadi kesalahan validasi:</strong>
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
                                        <div class="d-flex align-items-center" style="gap: 15px;">
                                            <label class="text-muted fw-medium mb-0" style="font-size: 15px; white-space: nowrap;">Filter status</label>
                                            <select name="status" class="form-select shadow-none" style="width: 140px; border-radius: 4px; padding: 6px 12px; font-size: 14px; cursor: pointer;" onchange="this.form.submit()">
                                                <option value="Semua" {{ request('status') == 'Semua' || !request('status') ? 'selected' : '' }}>Semua</option>
                                                <option value="menunggu_pembayaran" {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Pending</option>
                                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                            </select>
                                        </div>

                                        <div class="d-flex align-items-center w-100 mt-2 mt-md-0 d-md-flex justify-content-md-end" style="gap: 10px; max-width: 320px;">
                                            <input type="text" name="search" class="form-control shadow-none w-100" placeholder="Cari nama pengguna atau kamar" value="{{ request('search') }}" style="border-radius: 4px; padding: 6px 12px; font-size: 14px;">
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
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 160px;">Nama Penyewa</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">Kamar</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 100px;">Durasi</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">Tgl Mulai</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">Tgl Akhir</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 text-center px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 150px;">Status</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 text-center px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 180px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($bookings->currentPage() - 1) * $bookings->perPage() + 1; @endphp
                                                @forelse ($bookings as $booking)
                                                <tr class="table-row-hover" style="transition: background 0.2s;">
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6;">{{ $no++ }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ \Illuminate\Support\Str::limit($booking->user->nama ?? 'Nama tidak diketahui', 25) }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ $booking->kamar->nomor_kamar ?? '-' }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ $booking->durasi_sewa_bulan ?? 0 }} bulan</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ $booking->tgl_mulai_sewa ? \Carbon\Carbon::parse($booking->tgl_mulai_sewa)->format('d M Y') : '-' }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ $booking->tgl_akhir_sewa ? \Carbon\Carbon::parse($booking->tgl_akhir_sewa)->format('d M Y') : '-' }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 bg-transparent text-nowrap text-center" style="font-size: 14px; border-color: #f1f2f6;">
                                                        @if($booking->status_booking == 'menunggu_pembayaran')
                                                            <span class="badge rounded-pill" style="background-color: #fef2f2; color: #ef4444; font-weight: 600; font-size: 12px;">Pending</span>
                                                        @elseif($booking->status_booking == 'aktif')
                                                            <span class="badge rounded-pill" style="background-color: #ecfdf5; color: #00a669; font-weight: 600; font-size: 12px;">Aktif</span>
                                                        @elseif($booking->status_booking == 'selesai')
                                                            <span class="badge rounded-pill" style="background-color: #eff6ff; color: #3b82f6; font-weight: 600; font-size: 12px;">Selesai</span>
                                                        @elseif($booking->status_booking == 'batal')
                                                            <span class="badge rounded-pill" style="background-color: #fef2f2; color: #ef4444; font-weight: 600; font-size: 12px;">Batal</span>
                                                        @elseif($booking->status_booking == 'expired')
                                                            <span class="badge rounded-pill" style="background-color: #f3f4f6; color: #6b7280; font-weight: 600; font-size: 12px;">Expired</span>
                                                        @endif
                                                    </td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-center bg-transparent text-nowrap" style="border-color: #f1f2f6;">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $booking->id_booking }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1" style="background-color: #4f46e5; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Edit</a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal{{ $booking->id_booking }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1" style="background-color: #ef4444; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Hapus</a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal{{ $booking->id_booking }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2" style="background-color: #3b82f6; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Detail</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-5 text-muted bg-transparent">Tidak ada data booking ditemukan.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 mb-2 gap-4 text-center">
                                        <span class="text-muted" style="font-size: 15px; font-weight: 500; letter-spacing: -0.2px;">
                                            Menampilkan {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} data dari total {{ $bookings->total() }} data
                                        </span>
                                        <div class="d-flex align-items-center" style="gap: 25px;">
                                            @if ($bookings->onFirstPage())
                                                <span class="text-muted d-flex align-items-center" style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </span>
                                            @else
                                                <a href="{{ $bookings->previousPageUrl() . '&status=' . request('status') . '&search=' . request('search') }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </a>
                                            @endif

                                            @if ($bookings->hasMorePages())
                                                <a href="{{ $bookings->nextPageUrl() . '&status=' . request('status') . '&search=' . request('search') }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
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

                <!-- Modal Tambah Booking -->
                <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
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
                                            <h5 class="mb-0 fw-bold" id="tambahModalLabel" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Tambah Booking Baru</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Buat pemesanan kamar baru</p>
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
                                <form action="{{ route('booking.store') }}" method="POST" id="formTambahBooking">
                                    @csrf

                                    <!-- Nama Penyewa -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-user"></i></span>
                                            Nama Penyewa <span class="text-danger">*</span>
                                        </label>
                                        <select name="id_user" required class="form-select"
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                            onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                            <option value="">-- Pilih Penyewa --</option>
                                            @foreach($users ?? [] as $user)
                                                <option value="{{ $user->id_user }}" {{ old('id_user') == $user->id_user ? 'selected' : '' }}>{{ $user->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Kamar -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-home"></i></span>
                                            Kamar <span class="text-danger">*</span>
                                        </label>
                                        <select name="id_kamar" required class="form-select" id="kamarSelectTambah"
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                            onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                            <option value="">-- Pilih Kamar --</option>
                                            @foreach($kamars ?? [] as $kamar)
                                                <option value="{{ $kamar->id_kamar }}" data-harga="{{ $kamar->harga_per_bulan ?? $kamar->harga ?? 0 }}">
                                                    {{ $kamar->nomor_kamar }} - {{ $kamar->tipe_kamar }} - Rp {{ number_format($kamar->harga_per_bulan ?? $kamar->harga ?? 0, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Tgl Mulai & Durasi -->
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                                <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-calendar"></i></span>
                                                Tgl Mulai <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="tgl_mulai_sewa" required
                                                style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                                value="{{ old('tgl_mulai_sewa', date('Y-m-d')) }}"
                                                min="{{ date('Y-m-d') }}"
                                                onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                                <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-timer"></i></span>
                                                Durasi (Bulan) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="durasi_sewa_bulan" required min="1" max="24"
                                                style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                                value="{{ old('durasi_sewa_bulan', 1) }}"
                                                onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                            <small class="text-muted" style="font-size: 11px;">Tanggal akhir akan dihitung otomatis</small>
                                        </div>
                                    </div>

                                    <!-- Biaya & Status -->
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                                <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                                                Biaya/Bulan <span class="text-danger">*</span>
                                            </label>
                                            <div style="position:relative;">
                                                <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#00a669; font-size:14px; pointer-events:none;">Rp</span>
                                                <input type="number" name="total_biaya_bulanan" required min="0"
                                                    style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s; -moz-appearance: textfield;"
                                                    id="biayaBulananTambah"
                                                    value="{{ old('total_biaya_bulanan') }}"
                                                    onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                            </div>
                                            <small class="text-muted" style="font-size: 11px; color: #00a669;" id="hargaInfoTambah">💡 Pilih kamar untuk mengisi otomatis harga</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                                <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-info-alt"></i></span>
                                                Status <span class="text-danger">*</span>
                                            </label>
                                            <select name="status_booking" required
                                                style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                                onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                                <option value="">-- Pilih Status --</option>
                                                <option value="menunggu_pembayaran" {{ old('status_booking') == 'menunggu_pembayaran' ? 'selected' : '' }}>Pending</option>
                                                <option value="aktif" {{ old('status_booking') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="selesai" {{ old('status_booking') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="batal" {{ old('status_booking') == 'batal' ? 'selected' : '' }}>Batal</option>
                                                <option value="expired" {{ old('status_booking') == 'expired' ? 'selected' : '' }}>Expired</option>
                                            </select>
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

                <!-- Modals for Each Booking -->
                @foreach($bookings as $booking)

                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal{{ $booking->id_booking }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <div style="background: #ecfdf5; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
                                            <i class="ti-info-alt" style="color: #00a669; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px;">Detail Booking</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px;">ID: #{{ $booking->id_booking }}</p>
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
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Nama Penyewa</p>
                                            <p class="mb-0 fw-bold" style="color: #111827; font-size: 14px;">{{ $booking->user->nama ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Email</p>
                                            <p class="mb-0" style="color: #111827; font-size: 14px;">{{ $booking->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Kamar</p>
                                            <p class="mb-0 fw-bold" style="color: #111827; font-size: 14px;">{{ $booking->kamar->nomor_kamar ?? '-' }} ({{ $booking->kamar->tipe_kamar ?? '-' }})</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Durasi Sewa</p>
                                            <p class="mb-0 fw-bold" style="color: #111827; font-size: 14px;">{{ $booking->durasi_sewa_bulan ?? 0 }} Bulan</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Mulai</p>
                                            <p class="mb-0" style="color: #111827; font-size: 14px;">{{ $booking->tgl_mulai_sewa ? \Carbon\Carbon::parse($booking->tgl_mulai_sewa)->format('d M Y') : '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Akhir</p>
                                            <p class="mb-0 fw-bold text-primary" style="font-size: 14px;">{{ $booking->tgl_akhir_sewa ? \Carbon\Carbon::parse($booking->tgl_akhir_sewa)->format('d M Y') : '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Biaya Per Bulan</p>
                                            <p class="mb-0 fw-bold text-success" style="font-size: 14px;">Rp {{ number_format($booking->total_biaya_bulanan ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                                            @if($booking->status_booking == 'menunggu_pembayaran')
                                                <span class="badge rounded-pill" style="background-color: #fef2f2; color: #ef4444; font-weight: 600;">Pending</span>
                                            @elseif($booking->status_booking == 'aktif')
                                                <span class="badge rounded-pill" style="background-color: #ecfdf5; color: #00a669; font-weight: 600;">Aktif</span>
                                            @elseif($booking->status_booking == 'selesai')
                                                <span class="badge rounded-pill" style="background-color: #eff6ff; color: #3b82f6; font-weight: 600;">Selesai</span>
                                            @elseif($booking->status_booking == 'batal')
                                                <span class="badge rounded-pill" style="background-color: #fef2f2; color: #ef4444; font-weight: 600;">Batal</span>
                                            @elseif($booking->status_booking == 'expired')
                                                <span class="badge rounded-pill" style="background-color: #f3f4f6; color: #6b7280; font-weight: 600;">Expired</span>
                                            @endif
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
                <div class="modal fade" id="editModal{{ $booking->id_booking }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div style="background: #eff6ff; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-pencil-alt" style="color: #3b82f6; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Edit Booking</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">ID: #{{ $booking->id_booking }}</p>
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
                                <form action="{{ route('booking.update', $booking->id_booking) }}" method="POST" id="formEditBooking{{ $booking->id_booking }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Nama Penyewa -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-user"></i></span>
                                            Nama Penyewa <span class="text-danger">*</span>
                                        </label>
                                        <select name="id_user" required
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                            <option value="">-- Pilih Penyewa --</option>
                                            @foreach($users ?? [] as $user)
                                                <option value="{{ $user->id_user }}" {{ $booking->id_user == $user->id_user ? 'selected' : '' }}>{{ $user->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Kamar -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-home"></i></span>
                                            Kamar <span class="text-danger">*</span>
                                        </label>
                                        <select name="id_kamar" required class="form-select" id="kamarSelectEdit{{ $booking->id_booking }}"
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                            <option value="">-- Pilih Kamar --</option>
                                            @foreach($allKamars ?? [] as $kamar)
                                                <option value="{{ $kamar->id_kamar }}" 
                                                    {{ $booking->id_kamar == $kamar->id_kamar ? 'selected' : '' }}
                                                    data-harga="{{ $kamar->harga_per_bulan ?? $kamar->harga ?? 0 }}">
                                                    {{ $kamar->nomor_kamar }} - {{ $kamar->tipe_kamar }} 
                                                    @if($kamar->status_kamar != 'tersedia')
                                                        ({{ ucfirst($kamar->status_kamar) }})
                                                    @endif
                                                    - Rp {{ number_format($kamar->harga_per_bulan ?? $kamar->harga ?? 0, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Tgl Mulai & Durasi -->
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                                <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-calendar"></i></span>
                                                Tgl Mulai <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="tgl_mulai_sewa" required
                                                style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                                value="{{ $booking->tgl_mulai_sewa }}"
                                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                                <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-timer"></i></span>
                                                Durasi (Bulan) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="durasi_sewa_bulan" required min="1" max="24"
                                                style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                                value="{{ $booking->durasi_sewa_bulan }}"
                                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                            <small class="text-muted" style="font-size: 11px;">Tanggal akhir akan dihitung otomatis</small>
                                        </div>
                                    </div>

                                    <!-- Biaya & Status -->
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                                <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                                                Biaya/Bulan <span class="text-danger">*</span>
                                            </label>
                                            <div style="position:relative;">
                                                <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#3b82f6; font-size:14px; pointer-events:none;">Rp</span>
                                                <input type="number" name="total_biaya_bulanan" required min="0"
                                                    style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s; -moz-appearance: textfield;"
                                                    id="biayaBulananEdit{{ $booking->id_booking }}"
                                                    value="{{ $booking->total_biaya_bulanan }}"
                                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                            </div>
                                            <small class="text-muted" style="font-size: 11px; color: #3b82f6;" id="hargaInfo{{ $booking->id_booking }}">💡 Pilih kamar untuk mengisi otomatis harga</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                                <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-info-alt"></i></span>
                                                Status <span class="text-danger">*</span>
                                            </label>
                                            <select name="status_booking" required
                                                style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                                <option value="menunggu_pembayaran" {{ $booking->status_booking == 'menunggu_pembayaran' ? 'selected' : '' }}>Pending</option>
                                                <option value="aktif" {{ $booking->status_booking == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="selesai" {{ $booking->status_booking == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="batal" {{ $booking->status_booking == 'batal' ? 'selected' : '' }}>Batal</option>
                                                <option value="expired" {{ $booking->status_booking == 'expired' ? 'selected' : '' }}>Expired</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-end" style="gap: 12px;">
                                        <button type="button" data-bs-dismiss="modal"
                                            style="padding: 9px 22px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: white; font-weight: 600; font-size: 13.5px; color: #6b7280; cursor:pointer; transition: all 0.2s;"
                                            onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">
                                            Batal
                                        </button>
                                        <button type="submit" id="submitEditBtn{{ $booking->id_booking }}"
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
                <div class="modal fade" id="hapusModal{{ $booking->id_booking }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                            <div class="modal-body text-center" style="padding: 40px 30px;">
                                <div style="background: #fef2f2; border-radius: 50%; width: 70px; height: 70px; display:flex; align-items:center; justify-content:center; margin: 0 auto 24px;">
                                    <i class="ti-trash" style="color: #ef4444; font-size: 32px;"></i>
                                </div>
                                <h5 class="fw-bold mb-2" style="color: #111827; font-size: 18px;">Konfirmasi Hapus</h5>
                                <p class="mb-4" style="color: #6b7280; font-size: 14px; line-height: 1.5;">Apakah Anda yakin ingin menghapus booking untuk <strong>{{ $booking->user->nama ?? 'Pengguna' }}</strong> di kamar <strong>{{ $booking->kamar->nomor_kamar ?? '-' }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>

                                <form action="{{ route('booking.destroy', $booking->id_booking) }}" method="POST" id="formHapus{{ $booking->id_booking }}">
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
                document.addEventListener('DOMContentLoaded', function() {
                    // ==================== MODAL TAMBAH ====================
                    const kamarSelectTambah = document.getElementById('kamarSelectTambah');
                    const biayaInputTambah = document.getElementById('biayaBulananTambah');
                    const hargaInfoTambah = document.getElementById('hargaInfoTambah');
                    
                    if (kamarSelectTambah && biayaInputTambah) {
                        kamarSelectTambah.addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const harga = selectedOption.getAttribute('data-harga');
                            
                            if (harga && harga > 0) {
                                biayaInputTambah.value = harga;
                                biayaInputTambah.style.transition = 'all 0.3s ease';
                                biayaInputTambah.style.backgroundColor = '#ecfdf5';
                                biayaInputTambah.style.borderColor = '#00a669';
                                
                                if (hargaInfoTambah) {
                                    hargaInfoTambah.innerHTML = '✅ Harga terisi otomatis: Rp ' + new Intl.NumberFormat('id-ID').format(harga);
                                    hargaInfoTambah.style.color = '#00a669';
                                }
                                
                                setTimeout(() => {
                                    biayaInputTambah.style.backgroundColor = '';
                                    biayaInputTambah.style.borderColor = '';
                                }, 1000);
                            } else {
                                biayaInputTambah.value = '';
                                biayaInputTambah.style.backgroundColor = '#fef2f2';
                                biayaInputTambah.style.borderColor = '#ef4444';
                                
                                if (hargaInfoTambah) {
                                    hargaInfoTambah.innerHTML = '⚠️ Kamar tidak memiliki harga, silakan isi manual';
                                    hargaInfoTambah.style.color = '#ef4444';
                                }
                                
                                setTimeout(() => {
                                    biayaInputTambah.style.backgroundColor = '';
                                    biayaInputTambah.style.borderColor = '';
                                }, 1000);
                            }
                        });
                        
                        if (kamarSelectTambah.value) {
                            kamarSelectTambah.dispatchEvent(new Event('change'));
                        }
                    }
                    
                    // ==================== MODAL EDIT ====================
                    @foreach($bookings as $booking)
                    (function() {
                        const kamarSelectEdit = document.getElementById('kamarSelectEdit{{ $booking->id_booking }}');
                        const biayaInputEdit = document.getElementById('biayaBulananEdit{{ $booking->id_booking }}');
                        const hargaInfoEdit = document.getElementById('hargaInfo{{ $booking->id_booking }}');
                        
                        if (kamarSelectEdit && biayaInputEdit) {
                            kamarSelectEdit.addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];
                                const harga = selectedOption.getAttribute('data-harga');
                                
                                if (harga && harga > 0) {
                                    biayaInputEdit.value = harga;
                                    biayaInputEdit.style.transition = 'all 0.3s ease';
                                    biayaInputEdit.style.backgroundColor = '#eff6ff';
                                    biayaInputEdit.style.borderColor = '#3b82f6';
                                    
                                    if (hargaInfoEdit) {
                                        hargaInfoEdit.innerHTML = '✅ Harga terisi otomatis: Rp ' + new Intl.NumberFormat('id-ID').format(harga);
                                        hargaInfoEdit.style.color = '#3b82f6';
                                    }
                                    
                                    setTimeout(() => {
                                        biayaInputEdit.style.backgroundColor = '';
                                        biayaInputEdit.style.borderColor = '';
                                    }, 1000);
                                } else {
                                    biayaInputEdit.value = '';
                                    biayaInputEdit.style.backgroundColor = '#fef2f2';
                                    biayaInputEdit.style.borderColor = '#ef4444';
                                    
                                    if (hargaInfoEdit) {
                                        hargaInfoEdit.innerHTML = '⚠️ Kamar tidak memiliki harga, silakan isi manual';
                                        hargaInfoEdit.style.color = '#ef4444';
                                    }
                                    
                                    setTimeout(() => {
                                        biayaInputEdit.style.backgroundColor = '';
                                        biayaInputEdit.style.borderColor = '';
                                    }, 1000);
                                }
                            });
                            
                            const editModal = document.getElementById('editModal{{ $booking->id_booking }}');
                            if (editModal) {
                                editModal.addEventListener('shown.bs.modal', function() {
                                    kamarSelectEdit.dispatchEvent(new Event('change'));
                                });
                            }
                        }
                    })();
                    @endforeach

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
                            if (!confirm('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) {
                                e.preventDefault();
                            }
                        });
                    });
                });
                </script>

                <style>
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