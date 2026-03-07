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
                                    <h3 class="fw-bold mb-0" style="color: #000;">Data Produk</h3>
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
                                    <a href="{{ route('products.create') }}"
                                        class="btn btn-primary d-flex align-items-center rounded-pill">
                                        <i class="icon-plus mr-2 mb-1"></i>
                                        <span class="d-none d-md-block">Tambah Data</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle text-center custom-table">
                                            <thead>
                                                <tr>
                                                    <th class="col-no">No</th>
                                                    <th class="col-nama">Nama</th>
                                                    <th class="col-gambar">Gambar</th>
                                                    <th class="col-action">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($products as $p)
                                                    <tr>
                                                        <td>{{ $products->firstItem() + $loop->index }}</td>
                                                        <td>{{ $p->nama }}</td>
                                                        <td>
                                                            @foreach ($p->images as $image)
                                                                <img src="{{ $image->base64src }}" alt="Gambar Produk"
                                                                    width="50" class="rounded-circle">
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <!-- Tombol Modal -->
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                                data-bs-target="#detailModal{{ $p->id }}">
                                                                Detail
                                                            </button>

                                                            <a href="{{ route('products.edit', $p->id) }}"
                                                                class="btn btn-primary btn-sm">Edit</a>
                                                            <form action="{{ route('products.destroy', $p->id) }}"
                                                                method="POST" style="display:inline-block;">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Yakin hapus produk ini?')">Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                                    <!-- Modal Detail Produk -->

                                                    <div class="modal fade" id="detailModal{{ $p->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="detailModalLabel{{ $p->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Detail Produk</h5>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <p><strong>Nama:</strong> {{ $p->nama }}</p>
                                                                    <p><strong>Gambar:</strong><br>
                                                                        @foreach ($p->images as $image)
                                                                            <img src="{{ $image->base64src }}"
                                                                                alt="Gambar Produk" width="100"
                                                                                class="me-2 mb-2">
                                                                        @endforeach
                                                                    </p>
                                                                    <p><strong>Deskripsi:</strong><br>{{ $p->deskripsi }}
                                                                    </p>
                                                                    <p><strong>Stok:</strong> {{ $p->stock ? $p->stock->quantity : 0 }}</p>
                                                                    <p><strong>Berat:</strong> {{ $p->berat }}</p>
                                                                    <p><strong>Harga:</strong> Rp
                                                                        {{ number_format($p->harga, 0, ',', '.') }}</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Belum ada produk.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- paginate --}}
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        {{-- Informasi jumlah data yang ditampilkan --}}
                                        <div class="text-muted ml-4 mt-3">
                                            Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari
                                            total
                                            {{ $products->total() }} data
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                                        {{-- Tombol Kembali --}}
                                        @if ($products->onFirstPage())
                                            <span
                                                class="btn btn-link text-muted d-flex align-items-center text-decoration-none me-2 disabled">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </span>
                                        @else
                                            <a href="{{ $products->previousPageUrl() }}"
                                                class="btn btn-link text-dark d-flex align-items-center text-decoration-none me-2">
                                                <i class="ti-angle-left me-1"></i> Kembali
                                            </a>
                                        @endif

                                        {{-- Tombol Selanjutnya --}}
                                        @if ($products->hasMorePages())
                                            <a href="{{ $products->nextPageUrl() }}"
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
            </div>
        </div>
    </div>

    </div>
    {{-- <!-- content-wrapper ends -->

    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.
                Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a>
                from BootstrapDash. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with
                <i class="ti-heart text-danger ml-1"></i></span>
        </div>
    </footer>
    <!-- partial -->
    </div>
    <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div> --}}
@endsection
