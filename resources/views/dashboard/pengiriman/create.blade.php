@extends('layout') {{-- Sesuaikan dengan nama layout utama Anda --}}

@section('content')
    @include('layouts.sections.navbar') {{-- Sesuaikan path jika perlu --}}

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar') {{-- Sesuaikan path jika perlu --}}

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title mb-3"> Tambah Data Pengiriman </h3>
                        <nav aria-label="breadcrumb" style="background-color: white; outline:none">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('pengiriman.index') }}">Pengiriman</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="card shadow-sm rounded p-4">
                        <div class="card-body">
                            {{-- <h4 class="card-title">Form Tambah Data Pengiriman</h4> --}}
                            <form method="POST" action="{{ route('pengiriman.store') }}">
                                @csrf
                                <div class="row gx-4 gy-3">

                                    {{-- Form Inputs untuk Pengiriman --}}
                                    <div class="col-md-12"> {{-- Atau bagi menjadi col-md-6 jika ingin 2 kolom --}}
                                        <div class="row">
                                            {{-- resources/views/dashboard/pengiriman/create.blade.php --}}

                                            {{-- ... (bagian atas blade view) ... --}}

                                           <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                <label for="order_id" class="form-label fw-bold">Nama Pemesan</label>
                                                <select class="custom-select w-100 w-md-auto @error('order_id') is-invalid @enderror"
                                                    id="order_id" name="order_id" required>
                                                    <option value="">Pilih nama pemesan</option>
                                                    @if (isset($orders) && $orders->count() > 0)
                                                        @foreach ($orders as $order)
                                                            <option value="{{ $order->id }}"
                                                                {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                                Order #{{ $order->id }}
                                                                {{-- Tampilkan nama user jika relasi user ada dan nama user ada --}}
                                                                @if ($order->user && $order->user->nama)
                                                                    - {{ $order->user->nama }}
                                                                @elseif($order->user && $order->user->name)
                                                                    {{-- Fallback jika nama kolomnya 'name' --}}
                                                                    - {{ $order->user->name }}
                                                                @else
                                                                    - (User tidak ditemukan)
                                                                @endif
                                                                {{-- Anda juga bisa menampilkan info lain dari order jika perlu --}}
                                                                {{-- (Total: Rp {{ number_format($order->total_harga, 0, ',', '.') }}) --}}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option disabled>Tidak ada order dengan status "paid" yang siap
                                                            dikirim.</option>
                                                    @endif
                                                </select>
                                                @error('order_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- ... (bagian bawah blade view) ... --}}
                                            <div class="col-md-6 mb-3">
                                                <label for="status_pengiriman" class="form-label fw-bold">Status
                                                    Pengiriman</label>
                                                <select class="custom-select w-100 w-md-auto @error('status_pengiriman') is-invalid @enderror"
                                                    id="status_pengiriman" name="status_pengiriman" required>
                                                    <option value="diproses"
                                                        {{ old('status_pengiriman') == 'diproses' ? 'selected' : '' }}>
                                                        Diproses</option>
                                                    <option value="dikirim"
                                                        {{ old('status_pengiriman') == 'dikirim' ? 'selected' : '' }}>
                                                        Dikirim</option>
                                                    <option value="dalam_perjalanan"
                                                        {{ old('status_pengiriman') == 'dalam_perjalanan' ? 'selected' : '' }}>
                                                        Dalam Perjalanan</option>
                                                    <option value="sampai"
                                                        {{ old('status_pengiriman') == 'sampai' ? 'selected' : '' }}>Sampai
                                                    </option>
                                                    <option value="gagal"
                                                        {{ old('status_pengiriman') == 'gagal' ? 'selected' : '' }}>Gagal
                                                    </option>
                                                </select>
                                                @error('status_pengiriman')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="nomor_resi" class="form-label fw-bold">Nomor Resi</label>
                                                <input type="text"
                                                    class="form-control @error('nomor_resi') is-invalid @enderror"
                                                    id="nomor_resi" name="nomor_resi" value="{{ old('nomor_resi') }}">
                                                @error('nomor_resi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="jasa_kurir" class="form-label fw-bold">Jasa Kurir</label>
                                                <input type="text"
                                                    class="form-control @error('jasa_kurir') is-invalid @enderror"
                                                    id="jasa_kurir" name="jasa_kurir" value="{{ old('jasa_kurir') }}">
                                                @error('jasa_kurir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="tanggal_dikirim_display" class="form-label fw-bold">Tanggal
                                                    Dikirim</label>
                                                {{-- Input yang dilihat pengguna, akan diisi oleh datepicker --}}
                                                <input type="text"
                                                    class="form-control datepicker-id @error('tanggal_dikirim') is-invalid @enderror"
                                                    id="tanggal_dikirim_display" placeholder="dd/mm/yyyy"
                                                    value="{{ old('tanggal_dikirim_formatted', now()->format('d/m/Y')) }}">
                                                {{-- Format tampilan dd/mm/yyyy --}}

                                                {{-- Input tersembunyi untuk mengirim data dalam format YYYY-MM-DD ke server --}}
                                                <input type="hidden" name="tanggal_dikirim" id="tanggal_dikirim_actual"
                                                    value="{{ old('tanggal_dikirim', now()->format('Y-m-d')) }}">

                                                @error('tanggal_dikirim')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Jika Anda memutuskan untuk menambahkan tanggal_sampai dan catatan --}}
                                            {{-- Pastikan kolom ada di tabel & $fillable model, dan aktifkan validasi di controller --}}
                                            {{--
                                            <div class="col-md-6 mb-3">
                                                <label for="tanggal_sampai" class="form-label fw-bold">Tanggal Sampai (Estimasi/Aktual)</label>
                                                <input type="date" class="form-control @error('tanggal_sampai') is-invalid @enderror" id="tanggal_sampai" name="tanggal_sampai" value="{{ old('tanggal_sampai') }}">
                                                @error('tanggal_sampai')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label for="catatan" class="form-label fw-bold">Catatan (Opsional)</label>
                                                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
                                                @error('catatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            --}}

                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary rounded shadow-sm mr-3"
                                        style="padding: 10px 0; width:150px;">Simpan</button>
                                    <a href="{{ route('pengiriman.index') }}"
                                        class="btn btn-outline-secondary rounded shadow-sm text-center"
                                        style="padding: 10px 0; width:150px;">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> {{-- End content-wrapper --}}

                {{-- Footer bisa diletakkan di sini atau di layout utama --}}
                {{-- @include('layouts.sections.footer') --}}
            </div> {{-- End main-panel --}}
        </div> {{-- End page-body-wrapper --}}
    </div> {{-- End container-scroller --}}
@endsection

@push('styles')
    <style>
        /* Anda bisa menambahkan CSS custom di sini jika perlu */
        .form-label.fw-bold {
            font-weight: 600 !important;
            /* Atau sesuaikan dengan tema Anda */
        }

        .form-select {
            /* Styling dasar untuk select agar mirip dengan form-control */
            display: block;
            width: 100%;
            padding: .375rem 2.25rem .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            /* Sesuaikan dengan warna teks tema Anda */
            background-color: #fff;
            /* Sesuaikan dengan background tema Anda */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right .75rem center;
            background-size: 16px 12px;
            border: 1px solid #ced4da;
            /* Sesuaikan dengan border tema Anda */
            border-radius: .25rem;
            /* Sesuaikan dengan border-radius tema Anda */
            appearance: none;
        }

        .form-select.is-invalid {
            border-color: #dc3545;
            /* Warna error Bootstrap */
            padding-right: calc(1.5em + .75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23dc3545' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"), url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23dc3545' viewBox='0 0 12 12'%3e%3cpath stroke='%23dc3545' d='M10.293 1.293a1 1 0 0 0-1.414 0L6 4.586 2.707 1.293a1 1 0 0 0-1.414 1.414L4.586 6 1.293 9.293a1 1 0 1 0 1.414 1.414L6 7.414l3.293 3.293a1 1 0 0 0 1.414-1.414L7.414 6l3.293-3.293a1 1 0 0 0 0-1.414z'/%3e%3c/svg%3e");
            background-position: right .75rem center, center right 2.25rem;
            background-size: 16px 12px, calc(.75em + .375rem) calc(.75em + .375rem);
        }
    </style>
@endpush



