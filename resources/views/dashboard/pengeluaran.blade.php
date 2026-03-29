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
                                    <h2 class="fw-bold mb-0" style="color: #000; letter-spacing: -0.5px; font-size: 26px;">Laporan Pengeluaran</h2>
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
                            <button type="button" class="btn-tambah shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                                <i class="ti-plus"></i> Tambah data
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

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-0 rounded-4" style="box-shadow: 0 4px 30px rgba(0,0,0,0.03);">
                                <div class="card-body p-4 p-md-5">

                                    <form method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-2 gap-3 w-100">
                                        <div class="d-flex align-items-center" style="gap: 15px;">
                                            <label class="text-muted fw-medium mb-0" style="font-size: 15px; white-space: nowrap;">Filter status</label>
                                            <select name="status" class="form-select shadow-none" style="width: 140px; border-radius: 4px; padding: 6px 12px; font-size: 14px; cursor: pointer;" onchange="this.form.submit()">
                                                <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua</option>
                                                <option value="Listrik" {{ request('status') == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                                                <option value="PDAM" {{ request('status') == 'PDAM' ? 'selected' : '' }}>PDAM</option>
                                                <option value="Dapur" {{ request('status') == 'Dapur' ? 'selected' : '' }}>Dapur</option>
                                                <option value="Iuran" {{ request('status') == 'Iuran' ? 'selected' : '' }}>Iuran</option>
                                                <option value="Peralatan" {{ request('status') == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
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
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 160px;">Kategori</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 120px;">Nominal</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 150px;">Tanggal</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 200px;">Keterangan</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 text-center px-3 text-nowrap" style="font-size: 14px; border-color: #e5e7eb !important; min-width: 220px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($pengeluarans->currentPage() - 1) * $pengeluarans->perPage() + 1; @endphp
                                                @forelse ($pengeluarans as $pengeluaran)
                                                <tr class="table-row-hover" style="transition: background 0.2s;">
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6;">{{ $no++ }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ $pengeluaran->kategori ?? '-' }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">Rp {{ number_format($pengeluaran->nominal ?? 0, 0, ',', '.') }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent text-nowrap" style="font-size: 14px; border-color: #f1f2f6;">{{ \Carbon\Carbon::parse($pengeluaran->tgl_transaksi)->format('d/m/Y') }}</td>
                                                    <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6; max-width: 250px;" title="{{ $pengeluaran->keterangan }}">
                                                        <div class="text-truncate" style="max-width: 100%;">{{ $pengeluaran->keterangan ?? '-' }}</div>
                                                    </td>
                                                     <td class="border-bottom-1 border-top-0 border-start-0 border-end-0 py-4 px-3 text-center bg-transparent text-nowrap" style="border-color: #f1f2f6;">
                                                         <a href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $pengeluaran->id_pengeluaran }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1" style="background-color: #4f46e5; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Edit</a>
                                                         <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal{{ $pengeluaran->id_pengeluaran }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2 me-1" style="background-color: #ef4444; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Hapus</a>
                                                         <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal{{ $pengeluaran->id_pengeluaran }}" class="badge rounded-pill text-white text-decoration-none px-4 py-2" style="background-color: #3b82f6; font-size: 13px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Detail</a>
                                                     </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted bg-transparent">Tidak ada data laporan pengeluaran ditemukan.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 mb-2 gap-4 text-center">
                                        <span class="text-muted" style="font-size: 15px; font-weight: 500; letter-spacing: -0.2px;">
                                            Menampilkan {{ $pengeluarans->firstItem() ?? 0 }} - {{ $pengeluarans->lastItem() ?? 0 }} data dari total {{ $pengeluarans->total() }} data
                                        </span>
                                        <div class="d-flex align-items-center" style="gap: 25px;">
                                            @if ($pengeluarans->onFirstPage())
                                                <span class="text-muted d-flex align-items-center" style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </span>
                                            @else
                                                <a href="{{ $pengeluarans->previousPageUrl() }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </a>
                                            @endif

                                            @if ($pengeluarans->hasMorePages())
                                                <a href="{{ $pengeluarans->nextPageUrl() }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#00a669';" onmouseout="this.style.color='#343a40';">
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

                <!-- Modal Tambah Data -->
                <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">

                            <!-- Clean White Header -->
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div style="background: #ecfdf5; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-wallet" style="color: #00a669; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" id="tambahDataModalLabel" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Tambah Pengeluaran</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Isi detail pengeluaran di bawah ini</p>
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
                            <div class="modal-body" style="padding: 18px 26px 20px; background: #fff;">
                                <form action="{{ route('pengeluaran.store') }}" method="POST">
                                    @csrf

                                    <!-- Kategori as pill grid -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-tag"></i></span>
                                            Kategori
                                        </label>
                                        <div class="d-flex flex-wrap" style="gap: 10px;" id="kategoriGrid">
                                            @foreach(['Listrik','PDAM','Dapur','Iuran','Peralatan'] as $kat)
                                            <label class="kategori-pill" style="cursor:pointer;">
                                                <input type="radio" name="kategori" value="{{ $kat }}" required style="display:none;" onchange="selectKategori(this)">
                                                <span class="pill-label" style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $kat }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Nominal -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                                            Nominal
                                        </label>
                                        <div style="position:relative;">
                                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#00a669; font-size:14px; pointer-events:none;">Rp</span>
                                            <input type="number" name="nominal" id="nominalInput" required min="0"
                                                style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s; -moz-appearance: textfield;"
                                                placeholder="0"
                                                onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                        </div>
                                    </div>

                                    <!-- Tanggal -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-calendar"></i></span>
                                            Tanggal Transaksi
                                        </label>
                                        <input type="date" name="tgl_transaksi" required
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                            onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>

                                    <!-- Keterangan -->
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-pencil-alt"></i></span>
                                            Keterangan <span style="color: #9ca3af; font-weight:400;">(Opsional)</span>
                                        </label>
                                        <textarea name="keterangan" rows="2"
                                            style="width:100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; resize: none; transition: border-color 0.2s, box-shadow 0.2s;"
                                            placeholder="Catatan tambahan..."
                                            onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"></textarea>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-end" style="gap: 12px;">
                                        <button type="button" data-bs-dismiss="modal"
                                            style="padding: 9px 22px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: white; font-weight: 600; font-size: 13.5px; color: #6b7280; cursor:pointer; transition: all 0.2s;"
                                            onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">
                                            Batal
                                        </button>
                                        <button type="submit"
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


                <script>
                    function selectKategori(radio) {
                        document.querySelectorAll('.pill-label').forEach(el => {
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

                    // Reset modal state on close
                    document.getElementById('tambahDataModal').addEventListener('hidden.bs.modal', function () {
                        document.querySelectorAll('.pill-label').forEach(el => {
                            el.style.background = '#f9fafb';
                            el.style.color = '#6b7280';
                            el.style.borderColor = '#e5e7eb';
                            el.style.fontWeight = '500';
                        });
                        document.querySelectorAll('#tambahDataModal input[type="radio"]').forEach(r => r.checked = false);
                    });
                </script>

                <!-- Additional Modals (Edit, Hapus, Detail) -->
                @foreach($pengeluarans as $pengeluaran)
                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal{{ $pengeluaran->id_pengeluaran }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <div style="background: #ecfdf5; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
                                            <i class="ti-info-alt" style="color: #00a669; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px;">Detail Pengeluaran</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px;">Rincian lengkap transaksi pengeluaran</p>
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
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Kategori</p>
                                            <p class="mb-0 fw-bold" style="color: #111827; font-size: 14px;">{{ $pengeluaran->kategori }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Nominal</p>
                                            <p class="mb-0 fw-bold text-success" style="font-size: 14px;">Rp {{ number_format($pengeluaran->nominal, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Transaksi</p>
                                            <p class="mb-0 fw-600" style="color: #111827; font-size: 14px;">{{ \Carbon\Carbon::parse($pengeluaran->tgl_transaksi)->locale('id')->translatedFormat('l, d F Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="p-3" style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px; border-left: 3px solid #00a669;">
                                            <p class="text-muted mb-1" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Keterangan</p>
                                            <p class="mb-0" style="color: #4b5563; font-size: 13.5px; line-height: 1.6;">{{ $pengeluaran->keterangan ?? '-' }}</p>
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
                <div class="modal fade" id="editModal{{ $pengeluaran->id_pengeluaran }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div style="background: #eff6ff; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-pencil-alt" style="color: #3b82f6; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Edit Pengeluaran</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Perbarui data pengeluaran</p>
                                        </div>
                                    </div>
                                    <button type="button" data-bs-dismiss="modal" aria-label="Close"
                                        style="background: #f3f4f6; border: none; border-radius: 50%; width: 33px; height: 33px; display:flex; align-items:center; justify-content:center; cursor:pointer; color: #6b7280; font-size: 13px; flex-shrink:0; transition: background 0.2s;"
                                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="modal-body" style="padding: 18px 26px 20px; background: #fff;">
                                <form action="{{ route('pengeluaran.update', $pengeluaran->id_pengeluaran) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-tag"></i></span>
                                            Kategori
                                        </label>
                                        <div class="d-flex flex-wrap" style="gap: 10px;">
                                            @foreach(['Listrik','PDAM','Dapur','Iuran','Peralatan'] as $kat)
                                            <label class="kategori-pill-edit" style="cursor:pointer;">
                                                <input type="radio" name="kategori" value="{{ $kat }}" {{ $pengeluaran->kategori == $kat ? 'checked' : '' }} required style="display:none;" onchange="updateEditPill(this, {{ $pengeluaran->id_pengeluaran }})">
                                                <span class="pill-label-edit-{{ $pengeluaran->id_pengeluaran }} {{ $pengeluaran->kategori == $kat ? 'pill-active-edit' : '' }}"
                                                    style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $kat }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                                            Nominal
                                        </label>
                                        <div style="position:relative;">
                                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#3b82f6; font-size:14px; pointer-events:none;">Rp</span>
                                            <input type="number" name="nominal" value="{{ $pengeluaran->nominal }}" required min="0"
                                                style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;"
                                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-calendar"></i></span>
                                            Tanggal Transaksi
                                        </label>
                                        <input type="date" name="tgl_transaksi" value="{{ $pengeluaran->tgl_transaksi }}" required
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>
                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-pencil-alt"></i></span>
                                            Keterangan
                                        </label>
                                        <textarea name="keterangan" rows="2"
                                            style="width:100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; resize: none; transition: 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">{{ $pengeluaran->keterangan }}</textarea>
                                    </div>
                                    <div class="d-flex justify-content-end" style="gap: 12px;">
                                        <button type="button" data-bs-dismiss="modal"
                                            style="padding: 9px 22px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: white; font-weight: 600; font-size: 13.5px; color: #6b7280; cursor:pointer; transition: 0.2s;"
                                            onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">Batal</button>
                                        <button type="submit"
                                            style="padding: 9px 26px; border-radius: 8px; border: none; background: #3b82f6; color: white; font-weight: 600; font-size: 13.5px; cursor:pointer; transition: opacity 0.2s;"
                                            onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Hapus -->
                <div class="modal fade" id="hapusModal{{ $pengeluaran->id_pengeluaran }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                            <div class="modal-body text-center" style="padding: 40px 30px;">
                                <div style="background: #fef2f2; border-radius: 50%; width: 70px; height: 70px; display:flex; align-items:center; justify-content:center; margin: 0 auto 24px;">
                                    <i class="ti-trash" style="color: #ef4444; font-size: 32px;"></i>
                                </div>
                                <h5 class="fw-bold mb-2" style="color: #111827; font-size: 18px;">Konfirmasi Hapus</h5>
                                <p class="mb-4" style="color: #6b7280; font-size: 14px; line-height: 1.5;">Apakah Anda yakin ingin menghapus data pengeluaran ini? Tindakan ini tidak dapat dibatalkan.</p>

                                <form action="{{ route('pengeluaran.destroy', $pengeluaran->id_pengeluaran) }}" method="POST">
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
                    function updateEditPill(radio, id) {
                        document.querySelectorAll('.pill-label-edit-' + id).forEach(el => {
                            el.classList.remove('pill-active-edit');
                        });
                        radio.nextElementSibling.classList.add('pill-active-edit');
                    }
                </script>

            </div>
        </div>
    </div>
@endsection
