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

                                    <h3 class="fw-bold mb-0" style="color: #000;">Status Pesanan</h3>

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
                                                <form method="GET" action="{{ route('pesanan.page') }}"
                                                    class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 w-100">

                                                    <label for="status"
                                                        class="mb-1 mb-md-0 small fw-semibold text-muted mr-3">Filter
                                                        Status</label>
                                                    <select name="status" id="status"
                                                        class="custom-select w-25 w-md-auto" onchange="this.form.submit()">
                                                        <option value="">Semua</option>
                                                        <option value="pending"
                                                            {{ request('status') == 'pending' ? 'selected' : '' }}>Ditunda
                                                        </option>
                                                        <option value="paid"
                                                            {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar
                                                        </option>
                                                        <option value="shipped"
                                                            {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim
                                                        </option>
                                                        <option value="completed"
                                                            {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                                                        </option>
                                                        <option value="cancelled"
                                                            {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                                            Dibatalkan</option>

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
                                                    <th class="col-nama">Nama</th>
                                                    <th class="col-tanggal">Tanggal Pesan</th>


                                                    <th class="col-metode">Metode Pengiriman</th>

                                                    <th class="col-status">Status</th>
                                                    <th class="col-action">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $index => $order)
                                                    <tr>
                                                        <td>{{ $orders->firstItem() + $index }}</td>
                                                        <td class="col-nama">{{ $order->user->nama ?? 'Tidak Diketahui' }}
                                                        </td>
                                                        <td class="col-tanggal">
                                                            {{ \Carbon\Carbon::parse($order->waktu_order)->format('d/m/Y') }}
                                                        </td>


                                                        <td class="col-metode">{{ $order->metode_pengiriman ?? '-' }}</td>


                                                        <td class="col-status">
                                                            @php
                                                                $badgeClass = match ($order->status) {
                                                                    'pending' => 'badge-warning',
                                                                    'paid' => 'badge-primary',
                                                                    'shipped' => 'badge-info',
                                                                    'completed' => 'badge-success',
                                                                    'cancelled' => 'badge-danger',
                                                                };
                                                            @endphp
                                                            <label
                                                                class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</label>
                                                        </td>
                                                        <td class="col-action">
                                                            <div class="d-flex justify-content-center gap-2">

                                                                <button class="btn btn-sm btn-primary mr-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal{{ $order->id }}">
                                                                    Edit
                                                                </button>

                                                                {{-- <form action="{{ route('order.destroy', $order->id) }}"
                                                                    method="POST" style="display:inline-block;">
                                                                    @csrf @method('DELETE')
                                                                    <button class="btn btn-sm btn-danger mr-1"
                                                                        onclick="return confirm('Yakin hapus produk ini?')">Hapus</button>
                                                                </form> --}}
                                                                <a href="#" class="btn btn-sm btn-info"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#detailModal{{ $order->id }}">
                                                                    Detail
                                                                </a>
                                                            </div>

                                                        </td>
                                                    </tr>

                                                    <!-- Modal Detail -->
                                                    <div class="modal fade" id="detailModal{{ $order->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="detailModalLabel{{ $order->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Detail Pesanan</h5>

                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <p><strong>Nama:</strong>
                                                                        {{ $order->user->nama ?? 'Tidak Diketahui' }}</p>
                                                                    <p><strong>Tanggal Pesan:</strong>
                                                                        {{ \Carbon\Carbon::parse($order->waktu_order)->format('d/m/Y') }}
                                                                    </p>
                                                                    <p><strong>Alamat
                                                                            Pemesanan:</strong><br>{{ $order->alamat_pemesanan ?? '-' }}
                                                                    </p>
                                                                    <p><strong>Catatan:</strong><br>{{ $order->notes ?? '-' }}
                                                                    </p>
                                                                    <p><strong>Metode Pengiriman:</strong>
                                                                        {{ $order->metode_pengiriman ?? '-' }}</p>
                                                                    <p><strong>Status:</strong>
                                                                        {{ ucfirst($order->status) }}</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-sm  btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Modal Edit -->
                                                    <div class="modal fade" id="editModal{{ $order->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="editModalLabel{{ $order->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-md" role="document">
                                                            <!-- Gunakan modal-sm untuk ukuran kecil -->
                                                            <div class="modal-content">
                                                                <form action="{{ route('order.update', $order->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-header bg-primary text-white py-2">
                                                                        <h6 class="modal-title"
                                                                            id="editModalLabel{{ $order->id }}">Edit
                                                                            Pesanan</h6>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="form-group mb-2">
                                                                            <label
                                                                                for="status{{ $order->id }}"><small><strong>Status</strong></small></label>
                                                                            <select name="status"
                                                                                id="status{{ $order->id }}"
                                                                                class="form-control form-control-sm">
                                                                                <option value="pending"
                                                                                    {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                                                    Pending</option>
                                                                                <option value="paid"
                                                                                    {{ $order->status == 'paid' ? 'selected' : '' }}>
                                                                                    Paid</option>
                                                                                <option value="shipped"
                                                                                    {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                                                                    Shipped</option>
                                                                                <option value="completed"
                                                                                    {{ $order->status == 'completed' ? 'selected' : '' }}>
                                                                                    Completed</option>
                                                                                <option value="cancelled"
                                                                                    {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                                                    Cancelled</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group mb-2">
                                                                            <label
                                                                                for="alamat_pemesanan{{ $order->id }}"><small><strong>Alamat</strong></small></label>
                                                                            <input type="text" name="alamat_pemesanan"
                                                                                id="alamat_pemesanan{{ $order->id }}"
                                                                                class="form-control form-control-sm"
                                                                                value="{{ $order->alamat_pemesanan }}">
                                                                        </div>

                                                                        <div class="form-group mb-2">
                                                                            <label
                                                                                for="metode_pengiriman{{ $order->id }}"><small><strong>Pengiriman</strong></small></label>
                                                                            <input type="text" name="metode_pengiriman"
                                                                                id="metode_pengiriman{{ $order->id }}"
                                                                                class="form-control form-control-sm"
                                                                                value="{{ $order->metode_pengiriman }}">
                                                                        </div>

                                                                        <div class="form-group mb-2">
                                                                            <label
                                                                                for="notes{{ $order->id }}"><small><strong>Catatan</strong></small></label>
                                                                            <textarea name="notes" id="notes{{ $order->id }}" class="form-control form-control-sm" rows="2">{{ $order->notes }}</textarea>
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

                                                    <!-- Modal Konfirmasi Hapus -->
                                                    {{-- <div class="modal fade" id="deleteModal{{ $order->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="deleteModalLabel{{ $order->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('order.destroy', $order->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="deleteModalLabel{{ $order->id }}">
                                                                            Konfirmasi Hapus</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Tutup">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Apakah Anda yakin ingin menghapus pesanan ini?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-danger">Ya,
                                                                            Hapus</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        {{-- Informasi jumlah data yang ditampilkan --}}
                                        <div class="text-muted ml-4 mt-3">
                                            Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari total
                                            {{ $orders->total() }} data
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">

                                        {{-- Tombol Kembali --}}
                                        @if ($orders->onFirstPage())
                                            <span
                                                class="btn btn-link text-muted d-flex align-items-center text-decoration-none me-2 disabled">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </span>
                                        @else
                                            <a href="{{ $orders->previousPageUrl() }}"
                                                class="btn btn-link text-dark d-flex align-items-center text-decoration-none me-2">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </a>
                                        @endif

                                        {{-- Tombol Selanjutnya --}}
                                        @if ($orders->hasMorePages())
                                            <a href="{{ $orders->nextPageUrl() }}"
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
