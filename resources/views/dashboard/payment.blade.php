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
                                    <h3 class="fw-bold mb-0" style="color: #000;">Detail Pembayaran</h3>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div class="row mb-3">
                                            {{-- Dropdown kiri --}}
                                            <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                <form method="GET" action="{{ route('payment.page') }}"
                                                    class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 w-100">
                                                    <label for="status"
                                                        class="mb-1 mb-md-0 small fw-semibold text-muted mr-3">Filter
                                                        Status</label>
                                                    <select name="status" id="status"
                                                        class="custom-select w-25 w-md-auto" onchange="this.form.submit()">
                                                        <option value="">Semua</option>
                                                        <option value="pending"
                                                            {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                            Ditunda</option>
                                                        <option value="completed"
                                                            {{ request('status') == 'completed' ? 'selected' : '' }}>
                                                            Berhasil</option>
                                                        <option value="failed"
                                                            {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal
                                                        </option>
                                                        <option value="refunded"
                                                            {{ request('status') == 'refunded' ? 'selected' : '' }}>
                                                            Pengembalian</option>
                                                    </select>
                                                </form>
                                            </div>

                                            {{-- Search kanan --}}
                                            <div class="col-12 col-md-6">
                                                <form method="GET" action="{{ route('payment.page') }}"
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
                                                    <th class="col-nama">Nama Pembeli</th>
                                                    <th class="col-metode">Metode Pembayaran</th>
                                                    <th class="col-status">Status Pembayaran</th>
                                                    <th class="col-tanggal">Waktu Pembayaran</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($payments as $index => $payment)
                                                    <tr>
                                                        <td>{{ $payments->firstItem() + $index }}</td>
                                                        <td>{{ $payment->order->user->nama ?? 'Tidak Diketahui' }}</td>
                                                        <td>{{ $payment->metode_pembayaran }}</td>
                                                        <td>
                                                            @php
                                                                $badgeClass = match ($payment->status_pembayaran) {
                                                                    'pending' => 'badge-warning',
                                                                    'completed' => 'badge-success',
                                                                    'failed' => 'badge-danger',
                                                                    'refunded' => 'badge-secondary',
                                                                    default => 'badge-light',
                                                                };
                                                            @endphp
                                                            <label class="badge {{ $badgeClass }}">
                                                                {{ ucfirst($payment->status_pembayaran) }}
                                                            </label>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($payment->waktu_pembayaran)->format('d M Y, H:i') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        {{-- Informasi jumlah data yang ditampilkan --}}
                                        <div class="text-muted ml-4 mt-3">
                                            Menampilkan {{ $payments->firstItem() }} - {{ $payments->lastItem() }} dari
                                            total
                                            {{ $payments->total() }} data
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                                        {{-- Tombol Kembali --}}
                                        @if ($payments->onFirstPage())
                                            <span
                                                class="btn btn-link text-muted d-flex align-items-center text-decoration-none me-2 disabled">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </span>
                                        @else
                                            <a href="{{ $payments->previousPageUrl() }}"
                                                class="btn btn-link text-dark d-flex align-items-center text-decoration-none me-2">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </a>
                                        @endif

                                        {{-- Tombol Selanjutnya --}}
                                        @if ($payments->hasMorePages())
                                            <a href="{{ $payments->nextPageUrl() }}"
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
