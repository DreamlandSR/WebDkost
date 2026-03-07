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
                                    <h3 class="fw-bold mb-0" style="color: #000;">Detail Pesanan</h3>
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
                                            <div class="col-12">
                                                <form method="GET" action="{{ route('detail.page') }}"
                                                    class="d-flex flex-column flex-md-row justify-content-end align-items-start align-items-md-center gap-2 w-100">
                                                    <div class="input-group input-group-sm w-100" style="max-width: 300px;">
                                                        <input type="text" name="search" class="form-control"
                                                            placeholder="Cari nama produk..."
                                                            value="{{ request('search') }}">
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
                                                    <th class="col-produk">Nama Produk</th>
                                                    <th class="col-jumlah">Jumlah(pcs)</th>
                                                    <th class="col-harga">Harga</th>
                                                    <th class="col-total">Total Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = ($groupedOrders->currentPage() - 1) * $groupedOrders->perPage() + 1;
                                                @endphp
                                                @foreach ($groupedOrders as $order)
                                                    <tr>

                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $order->user->nama ?? 'Tidak Diketahui' }}</td>
                                                        <td>
                                                            @if($order->orderItems->count() > 1)
                                                                <div class="text-start">
                                                                    @foreach($order->orderItems as $item)
                                                                        <div class="mb-1">
                                                                            • {{ $item->product->nama ?? 'Produk tidak ditemukan' }}
                                                                            ({{ $item->kuantitas }}x)
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                {{ $order->orderItems->first()->product->nama ?? 'Produk tidak ditemukan' }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $order->orderItems->sum('kuantitas') }}</td>
                                                        <td>
                                                            @if($order->orderItems->count() > 1)
                                                                <div class="text-start">
                                                                    @foreach($order->orderItems as $item)
                                                                        <div class="mb-1">
                                                                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                Rp {{ number_format($order->orderItems->first()->harga, 0, ',', '.') }}
                                                            @endif
                                                        </td>
                                                        <td>Rp {{ number_format($order->orderItems->sum('subtotal'), 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">

                                        {{-- Tombol Kembali --}}
                                        @if ($groupedOrders->onFirstPage())

                                            <span
                                                class="btn btn-link text-muted d-flex align-items-center text-decoration-none me-2 disabled">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </span>
                                        @else

                                            <a href="{{ $groupedOrders->previousPageUrl() }}"

                                                class="btn btn-link text-dark d-flex align-items-center text-decoration-none me-2">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </a>
                                        @endif

                                        {{-- Tombol Selanjutnya --}}

                                        @if ($groupedOrders->hasMorePages())
                                            <a href="{{ $groupedOrders->nextPageUrl() }}"

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
