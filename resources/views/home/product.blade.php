@extends('templates.layout')

@section('content')

    @include('templates.header')
    @include('templates.navbar')

    <div class="mx-4 py-5">
        <h2 class="text-center fw-bold mb-2">Galeri Batik</h2>
        <p class="text-center lead fw-normal text-muted mb-4">Produk terbaik kami</p>

        <!-- Masonry Grid -->
        <div class="grid-container">
            @foreach($products as $product)
                <div class="grid-item">
                    @if($product->mainImage && $product->mainImage->image_product)
                        <img src="data:image/jpeg;base64,{{ base64_encode($product->mainImage->image_product) }}"
                             alt="{{ $product->nama }}">
                    @else
                        <img src="{{ asset('img/batik 1.jpg') }}" alt="Default Image">
                    @endif
                    <div class="overlay">
                        <h5>{{ $product->nama }}</h5>
                        <a href="{{ url('/#android-download') }}" class="buy-btn">Beli Sekarang</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @include('templates.main_footer')
    @include('templates.footer')
