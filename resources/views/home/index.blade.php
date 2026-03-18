@extends('templates.layout')

@section('content')

@include('templates.header')
@include('templates.navbar')

@php use Illuminate\Support\Str; @endphp

<main class="flex-shrink-0">

    <!-- HERO -->
    <header class="py-5">
        <div class="container px-5">
            <h1 class="fw-bold">Kos Halmahera</h1>
            <p class="text-muted">Temukan kamar terbaik untuk kamu</p>
        </div>
    </header>

    <!-- LIST KAMAR -->
    <section class="py-5 bg-light">
        <div class="container px-5">

            <h2 class="fw-bold mb-4">Daftar Kamar</h2>

            @if($kamars->count() > 0)
                <div class="row">

                    @foreach ($kamars as $kamar)
                        @php
                            $image = $kamar->galeri->first();
                            $base64 = $image ? base64_encode($image->url_foto) : null;
                            $mime = $image ? (new \finfo(FILEINFO_MIME_TYPE))->buffer($image->url_foto) : null;
                        @endphp

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm h-100">

                                {{-- GAMBAR --}}
                                @if ($image)
                                    <img class="card-img-top"
                                        src="data:{{ $mime }};base64,{{ $base64 }}"
                                        style="height:200px; object-fit:cover;">
                                @else
                                    <img class="card-img-top"
                                        src="{{ asset('img/kamira.png') }}"
                                        style="height:200px; object-fit:cover;">
                                @endif

                                <div class="card-body">
                                    <h5 class="fw-bold">{{ ucfirst($kamar->tipe_kamar) }}</h5>

                                    <p class="mb-1 text-muted">
                                        No Kamar: {{ $kamar->nomor_kamar }}
                                    </p>

                                    <p class="text-muted">
                                        {{ Str::limit($kamar->deskripsi, 80) }}
                                    </p>

                                    <h6 class="text-primary">
                                        Rp {{ number_format($kamar->harga_per_bulan) }} / bulan
                                    </h6>
                                </div>

                                <div class="card-footer bg-white border-0">
                                    <span class="badge bg-{{ $kamar->status_kamar == 'tersedia' ? 'success' : ($kamar->status_kamar == 'terisi' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($kamar->status_kamar) }}
                                    </span>
                                </div>

                            </div>
                        </div>

                    @endforeach

                </div>
            @else
                <div class="text-center">
                    <p class="text-muted">Belum ada kamar tersedia</p>
                </div>
            @endif

        </div>
    </section>

</main>

@include('templates.main_footer')
@include('templates.footer')

@endsection