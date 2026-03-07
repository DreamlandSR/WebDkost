@extends('templates.layout')

@section('content')

    @include('templates.header')
    @include('templates.navbar')

    <main class="flex-shrink-0">

        <!-- Header-->
        <header class="py-5 fade-in">
            <div class="container px-5">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-8 col-xl-7 col-xxl-6">
                        <div class="my-5 text-center text-xl-start">
                            <h1 class="display-5 fw-bolder text-black mb-2 text-start">Griya Batik</h1>
                            <p class="lead fw-normal text-black mb-4 pr-5" style="text-align:left; font-size: 18px;">
                                Griya Batik Disabilitas adalah wadah kreatif bagi penyandang disabilitas untuk berkarya
                                melalui seni batik. Mengusung nilai inklusif dan pemberdayaan, Griya Batik menyatukan budaya
                                dan semangat kesetaraan dalam setiap motifnya.
                            </p>
                            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                                <a class="btn btn-primary btn-lg px-4 me-sm-3" href={{ url('/login') }}>Mulai</a>
                                <a class="btn btn-light btn-lg px-4" href={{ url('/about') }}>Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>

                    <!-- carousel -->
                    <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
                        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel"
                            data-interval="6000">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('/img/Batik 2.jpg') }}" class="d-block w-100 rounded"
                                        alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('/img/batik 1.jpg') }}" class="d-block w-100 rounded" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('/img/batik 3.jpg') }}" class="d-block w-100 rounded" alt="... ">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Features section-->
        <section class="py-5" id="features">
            <div class="container px-5 my-5 slide-in">
                <div class="row">
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h2 class="fw-bolder mb-0">Fitur yang ada pada Website</h2>
                    </div>
                    <div class="col-lg-8">
                        <div class="row px-3">
                            <div class="col-12 col-md-6 mb-5 h-100">
                                <div class="feature bg-primary text-white rounded-3 mb-4">
                                    <i class="bi bi-collection"></i>
                                </div>
                                <h2 class="h5 fw-bold">Promosi Produk</h2>
                                <p class="mb-0">Melakukan promosi online melalui website resmi kami</p>
                            </div>
                            <div class="col-12 col-md-6 mb-5 h-100">
                                <div class="feature bg-primary text-white rounded-3 mb-4">
                                    <i class="bi bi-building"></i>
                                </div>
                                <h2 class="h5 fw-bold">Informasi omset</h2>
                                <p class="mb-0">Mengetahui informasi omset pada halaman admin</p>
                            </div>
                            <div class="col-12 col-md-6 mb-5 mb-md-0 h-100">
                                <div class="feature bg-primary text-white rounded-3 mb-4">
                                    <i class="bi bi-toggles2"></i>
                                </div>
                                <h2 class="h5 fw-bold">Jumlah Produk terjual</h2>
                                <p class="mb-0">Mengetahui jumlah produk yang terjual
                                </p>
                            </div>
                            <div class="col-12 col-md-6 h-100">
                                <div class="feature bg-primary text-white rounded-3 mb-4">
                                    <i class="bi bi-toggles2"></i>
                                </div>
                                <h2 class="h5 fw-bold">Update status pembayaran</h2>
                                <p class="mb-0">Memudahkan melakukan update status tanpa harus memberitahu melalui kontak</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Deskripsi --}}
        <div class="py-5 slide-in">
            <div class="container px-5 pb-5">
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-10 col-xl-7">
                        <div class="text-center">
                            <h2 class="fw-bolder">Produk yang kami buat</h2>
                            <div class="fs-4 mb-4">"Kami membuat berbagai macam batik misalnya Batik Daun Singkong, Batik Blufire, Batik Topeng Konah, dan lain - lain."</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container my-5">
                <div class="row align-items-center">
                    <!-- Teks -->
                    <div class="col-md-6">
                        <div id="productText" class="fade-in">
                            <h2 class="fw-bold" id="productName">{{ $products[0]->nama }}</h2>
                            <p class="text-muted"><i class="bi bi-geo-alt-fill"></i> Bondowoso</p>
                            <p id="productDesc">{{ $products[0]->deskripsi }}</p>
                            <a href="#" class="btn btn-primary mb-3">Baca selengkapnya</a>
                        </div>
                    </div>

                    <!-- Carousel Gambar -->
                    <div class="col-md-6">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($products as $index => $product)
                                    @php
                                        $image = $product->images->first();
                                        $base64 = $image ? base64_encode($image->image_product) : null;
                                        $mime = $image
                                            ? (new \finfo(FILEINFO_MIME_TYPE))->buffer($image->image_product)
                                            : null;
                                    @endphp
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}"
                                        data-name="{{ $product->nama }}" data-description="{{ $product->deskripsi }}">
                                        @if ($image)
                                            <img src="data:{{ $mime }};base64,{{ $base64 }}"
                                                class="img-square d-block w-100" alt="Product Image">
                                        @else
                                            <img src="{{ asset('img/kamira.png') }}" class="img-square d-block w-100"
                                                alt="Default Image">
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Testimonial section-->
        <div class="py-5 my-5 bg-light slide-in">
            <div class="container px-5 my-3">
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-10 col-xl-7">
                        <div class="text-center">
                            <h2 class="fw-bolder">Tentang Kami</h2>
                            <div class="fs-5 mb-4 fst-italic">"Kami adalah pembuat batik disabilitas yang berasal dari
                                Bondowoso dengan membuat berbagai macam
                                inovasi dan berdaya saing global, dengan tetap menjaga nilai tradisi dalam mengembangkan batik."</div>
                            <div class="d-flex align-items-center justify-content-center">
                                <img class="rounded-circle me-3" style="width: 40px; height:40px; object-fit:cover;"
                                    src="{{ asset('/img/batik 2.jpg') }}" alt="Nelson Mandela" />
                                <div class="fw-bold">
                                    Ryan Adi Saputra
                                    <span class="fw-bold text-primary mx-1">/</span>
                                    Pengembara
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog preview section-->
        <section class="py-5 my-5 slide-in">
            <div class="px-5 slide-in">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-xl-6">
                        <h2 class="fw-bolder">Batik Populer</h2>
                        <p class="lead fw-normal text-muted mb-5">Batik populer khas Bondowoso</p>
                    </div>
                    <div class="col text-end">
                        <i class="bi bi-arrow-left-circle-fill fs-2 me-2" id="prevBtn" style="cursor: pointer;"></i>
                        <i class="bi bi-arrow-right-circle-fill fs-2" id="nextBtn" style="cursor: pointer;"></i>
                    </div>
                </div>

                <div id="carouselContainer" class="d-flex overflow-hidden">
                    @foreach ($products as $product)
                        @php
                            $image = $product->images->first();
                            $base64 = $image ? base64_encode($image->image_product) : null;
                            $mime = $image ? (new \finfo(FILEINFO_MIME_TYPE))->buffer($image->image_product) : null;
                        @endphp

                        <div class="product-card me-3 mx-3 mb-5" style="flex: 0 0 22%;">
                            <div class="card h-100 shadow-sm">
                                @if ($image)
                                    <img class="card-img-top rounded"
                                        src="data:{{ $mime }};base64,{{ $base64 }}"
                                        style="height: 200px; object-fit: cover ;" alt="{{ $product->nama }}">
                                @else
                                    <img class="card-img-top rounded" src="{{ asset('/img/batik 1.jpg') }}"
                                        style="height: 200px; object-fit: cover;" alt="Default Image">
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title fw-bold mb-0 fs-6">{{ $product->nama }}</h5>
                                        <span class="badge bg-primary">
                                            <i class="bi bi-star-fill text-warning me-1"></i>{{ $product->rating ?? '0' }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-geo-alt-fill"></i> {{ $product->lokasi ?? 'Bondowoso' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Call to action-->
                <div  id="android-download" class="py-5">
                    <div class="row align-items-center">
                        <!-- Kiri -->
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h4><strong>Download Sekarang juga !</strong></h4>
                            <h4><strong><span class="text-primary">Pembayaran</span> bisa lewat sini</strong></h4>
                            <p>Pembayaran bisa menggunakan Aplikasi kami pada tombol download di samping →</p>
                        </div>

                        <!-- Kanan -->
                        <div class="col-12 col-md-4 ms-md-auto">
                            <div
                                class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 border rounded shadow-sm bg-light p-4">
                                <!-- Kiri: Teks -->
                                <div class="text-center text-md-start">
                                    <h5><strong>For Android</strong></h5>
                                    <p class="mb-2 text-muted">Android 8.0+</p>
                                    <a href="{{ url('downloads/Healthy.pdf') }}" class="btn btn-primary"
                                        download>Download</a>
                                </div>

                                <!-- Kanan: QR Code -->
                                <div>
                                    <img src="{{ asset('img/qrcode.png') }}" alt="QR Code" class="img-fluid"
                                        style="max-width: 100px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    @include('templates.main_footer')

    @include('templates.footer')
