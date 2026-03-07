@extends('layout')


@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center p-3">
                                <div class="mb-2 mb-md-0">
                                    <h3 class="fw-bold mb-0" style="color: #000;">Status Pengiriman</h3>
                                </div>
                                <div class="d-flex flex-wrap gap-3">
                                    {{-- <div class="d-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-sm">
                                        <span class="text-muted me-2 d-none d-sm-block">Tampilkan</span>
                                        <select class="form-select border-0 bg-transparent pe-3">
                                            <option selected>10</option>
                                            <option>25</option>
                                            <option>50</option>
                                        </select>
                                    </div> --}}
                                    {{--
                                    <button class="btn btn-outline-primary d-flex align-items-center rounded-pill">
                                        <i class="icon-download me-2"></i>
                                        <span class="d-none d-md-block">Export</span>
                                    </button> --}}

                                    <!-- Tombol Tambah Produk Buka Modal -->
                                    <a href="{{ route('pengiriman.create') }}"
                                        class="btn btn-primary d-flex align-items-center rounded-pill">
                                        <i class="icon-plus mr-2 mb-1"></i>
                                        <span class="d-none d-md-block">Tambah data</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        </div>
                    @endif


                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div class="row mb-3">
                                            {{-- Dropdown kiri --}}
                                            <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                <form method="GET" action="{{ route('pengiriman.index') }}"
                                                    class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 w-100">
                                                    <label for="status_pengiriman"
                                                        class="mb-1 mb-md-0 small fw-semibold text-muted mr-3">Filter
                                                        Status</label>
                                                    <select name="status_pengiriman" id="status_pengiriman"
                                                        class="custom-select w-25 w-md-auto" onchange="this.form.submit()">
                                                        <option value="">Semua</option>
                                                        <option value="diproses"
                                                            {{ request('status_pengiriman') == 'diproses' ? 'selected' : '' }}>
                                                            Diproses
                                                        </option>
                                                        <option value="dikirim"
                                                            {{ request('status_pengiriman') == 'dikirim' ? 'selected' : '' }}>
                                                            Dikirim
                                                        </option>
                                                        <option value="dalam_perjalanan"
                                                            {{ request('status_pengiriman') == 'dalam_perjalanan' ? 'selected' : '' }}>
                                                            Dalam Perjalanan
                                                        </option>
                                                        <option value="sampai"
                                                            {{ request('status_pengiriman') == 'sampai' ? 'selected' : '' }}>
                                                            Sampai
                                                        </option>
                                                        <option value="gagal"
                                                            {{ request('status_pengiriman') == 'gagal' ? 'selected' : '' }}>
                                                            Gagal</option>
                                                    </select>
                                                </form>
                                            </div>

                                            {{-- Search kanan --}}
                                            <div class="col-12 col-md-6">
                                                <form method="GET" action="{{ route('pesanan.page') }}"
                                                    class="d-flex flex-column flex-md-row justify-content-end align-items-start align-items-md-center gap-2 w-100">
                                                    <div class="input-group input-group-sm w-100" style="max-width: 300px;">
                                                        <input type="text" name="search" class="form-control"
                                                            placeholder="Cari nama..." value="{{ request('search') }}">
                                                        <button type="submit" class="btn-primary btn-sm mx-3">
                                                            <i class="ti-search"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <table class="table table-sm align-middle text-center custom-table">
                                            <thead>
                                                <tr>
                                                    <th class="col-no">No</th>
                                                    <th class="col-nama">Nama Pemesan</th>
                                                    <th class="col-status">Jasa Kurir</th>
                                                    <th class="col-metode">Nomor Resi</th>
                                                    <th class="col-status">Status Pengiriman</th>
                                                    <th class="col-action">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pengiriman as $index => $item)
                                                    <tr>
                                                        <td>{{ $pengiriman->firstItem() + $index }}</td>
                                                        <td class="col-nama">
                                                            {{ $item->order->user->nama ?? 'Tidak diketahui' }}
                                                        </td>
                                                        <td class="col-status">
                                                            {{ $item->jasa_kurir ?? '-' }}
                                                        </td>
                                                        <td class="col-metode">
                                                            {{ $item->nomor_resi ?? '-' }}
                                                        </td>
                                                        <td class="col-status">
                                                            @php
                                                                $badgeClass = match ($item->status_pengiriman) {
                                                                    'diproses' => 'badge-warning',
                                                                    'dikirim' => 'badge-primary',
                                                                    'dalam_perjalanan' => 'badge-info',
                                                                    'sampai' => 'badge-success',
                                                                    'gagal' => 'badge-danger',
                                                                    default => 'badge-secondary',
                                                                };
                                                            @endphp
                                                            <label class="badge {{ $badgeClass }}">
                                                                {{ ucfirst($item->status_pengiriman) }}
                                                            </label>
                                                        </td>
                                                        <td class="col-action">
                                                            <div class="d-flex justify-content-center gap-2">
                                                                <button class="btn btn-sm btn-primary mr-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal{{ $item->id }}">
                                                                    Edit
                                                                </button>
                                                                <form action="{{ route('pengiriman.destroy', $item->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger mr-1"
                                                                        onclick="return confirm('Yakin hapus data pengiriman ini?')">
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                                <a href="#" class="btn btn-sm btn-info"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#detailModal{{ $item->id }}">
                                                                    Detail
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Modal Detail -->
                                                    <div class="modal fade" id="detailModal{{ $item->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="detailModalLabel{{ $item->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Detail Pengiriman</h5>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <p><strong>Nama Pemesan:</strong>
                                                                        {{ $item->order->user->nama ?? 'Tidak diketahui' }}
                                                                    </p>
                                                                    <p><strong>Jasa Kurir:</strong>
                                                                        {{ $item->jasa_kurir ?? '-' }}</p>
                                                                    <p><strong>Nomor Resi:</strong>
                                                                        {{ $item->nomor_resi ?? '-' }}</p>
                                                                    <p><strong>Tanggal Dikirim:</strong>
                                                                        @if ($item->tanggal_dikirim)
                                                                            {{ \Carbon\Carbon::parse($item->tanggal_dikirim)->format('d/m/Y') }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </p>
                                                                    <p><strong>Status Pengiriman:</strong>
                                                                        {{ ucfirst($item->status_pengiriman) }}</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Edit -->
                                                    <div class="modal fade" id="editModal{{ $item->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="editModalLabel{{ $item->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-md" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('pengiriman.update', $item->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-header bg-primary text-white py-2">
                                                                        <h6 class="modal-title"
                                                                            id="editModalLabel{{ $item->id }}">
                                                                            Edit Pengiriman
                                                                        </h6>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group mb-2">
                                                                            <label
                                                                                for="status_pengiriman{{ $item->id }}">
                                                                                <small><strong>Status
                                                                                        Pengiriman</strong></small>
                                                                            </label>
                                                                            <select name="status_pengiriman"
                                                                                id="status_pengiriman{{ $item->id }}"
                                                                                class="form-control form-control-sm">
                                                                                <option value="diproses"
                                                                                    {{ $item->status_pengiriman == 'diproses' ? 'selected' : '' }}>
                                                                                    Diproses
                                                                                </option>
                                                                                <option value="dikirim"
                                                                                    {{ $item->status_pengiriman == 'dikirim' ? 'selected' : '' }}>
                                                                                    Dikirim
                                                                                </option>
                                                                                <option value="dalam_perjalanan"
                                                                                    {{ $item->status_pengiriman == 'dalam_perjalanan' ? 'selected' : '' }}>
                                                                                    Dalam Perjalanan
                                                                                </option>
                                                                                <option value="sampai"
                                                                                    {{ $item->status_pengiriman == 'sampai' ? 'selected' : '' }}>
                                                                                    Sampai
                                                                                </option>
                                                                                <option value="gagal"
                                                                                    {{ $item->status_pengiriman == 'gagal' ? 'selected' : '' }}>
                                                                                    Gagal
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group mb-2">
                                                                            <label for="jasa_kurir{{ $item->id }}">
                                                                                <small><strong>Jasa Kurir</strong></small>
                                                                            </label>
                                                                            <input type="text" name="jasa_kurir"
                                                                                id="jasa_kurir{{ $item->id }}"
                                                                                class="form-control form-control-sm"
                                                                                value="{{ $item->jasa_kurir }}">
                                                                        </div>
                                                                        <div class="form-group mb-2">
                                                                            <label for="nomor_resi{{ $item->id }}">
                                                                                <small><strong>Nomor Resi</strong></small>
                                                                            </label>
                                                                            <input type="text" name="nomor_resi"
                                                                                id="nomor_resi{{ $item->id }}"
                                                                                class="form-control form-control-sm"
                                                                                value="{{ $item->nomor_resi }}">
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer py-2">
                                                                        <button type="button"
                                                                            class="btn btn-secondary btn-sm"
                                                                            data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="submit"
                                                                            class="btn btn-success btn-sm">
                                                                            <i class="fas fa-save me-1"></i> Simpan
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        {{-- Informasi jumlah data yang ditampilkan --}}
                                        <div class="text-muted ml-4 mt-3">
                                            Menampilkan {{ $pengiriman->firstItem() }} - {{ $pengiriman->lastItem() }}
                                            dari total
                                            {{ $pengiriman->total() }} data
                                        </div>
                                    </div>



                                    <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">

                                        {{-- Tombol Kembali --}}
                                        @if ($pengiriman->onFirstPage())
                                            <span
                                                class="btn btn-link text-muted d-flex align-items-center text-decoration-none me-2 disabled">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </span>
                                        @else
                                            <a href="{{ $pengiriman->previousPageUrl() }}"
                                                class="btn btn-link text-dark d-flex align-items-center text-decoration-none me-2">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </a>
                                        @endif

                                        {{-- Tombol Selanjutnya --}}
                                        @if ($pengiriman->hasMorePages())
                                            <a href="{{ $pengiriman->nextPageUrl() }}"
                                                class="btn btn-link text-dark d-flex align-items-center text-decoration-none ms-2">
                                                Selanjutnya <i class="ti-angle-right ms-1"></i>
                                            </a>
                                        @else
                                            <span
                                                class="btn btn-link text-muted d-flex align-items-center text-decoration-none ms-2 disabled">
                                                Selanjutnya <i class="ti-angle-right ms-1"></i>
                                            </span>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
@endsection
