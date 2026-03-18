    @extends('templates.layout')

    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/override.css') }}">
    @endsection

    @section('content')

        @include('templates.header')
        @include('templates.navbar')

<!-- HERO SECTION dengan Background Hijau -->
        <section class="hero-section" style="background-color: #00AB6B;">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h5 class="display-4 fw-bold text-white mb-4">
                            Carilah tempat dimana kamu dapat pulang dan dengan nyaman
                        </h5>
                        
                        <!-- Search Box -->
                        <div class="search-box bg-white p-2 rounded-4 shadow-lg mx-auto" style="max-width: 700px;">
    <div class="d-flex flex-column flex-md-row gap-2">
        <!-- Input Search -->
        <div class="flex-grow-1 position-relative">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" 
                   class="form-control border-0 ps-5" 
                   placeholder="Cari kos impianmu disini" 
                   style="background-color: #F5F5F5; border-radius: 12px; height: 54px; font-size: 14px;">
        </div>
        
        <!-- Filter Dropdown -->
        <div class="position-relative" style="min-width: 120px;">
            <select class="form-select border-0" 
                    style="background-color: #F5F5F5; border-radius: 12px; height: 54px; padding-left: 40px; appearance: none; cursor: pointer;">
                <option selected>Filter</option>
                <option value="kecil">Kos Kecil</option>
                <option value="medium">Kos Medium</option>
                <option value="mewah">Kos Mewah</option>
                <option value="ac">Dengan AC</option>
                <option value="wifi">Dengan WiFi</option>
                <option value="rating">Rating Tertinggi</option>
            </select>
            <i class="bi bi-sliders2 position-absolute top-50 start-0 translate-middle-y ms-3" style="color: #00AB6B; z-index: 10;"></i>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>
        </section>
        
<!-- PILIHAN KOS KAMI SECTION -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-5" style="color: #2D3748; font-size: 2rem;">Pilihan Kos Kami</h2>
        
        <div class="row g-4">
            <!-- KAMAR 1: Kos kecil 01 -->
            <div class="col-lg-3 col-md-6">
                <div class="card kos-card border-0 shadow-sm h-100">
                    <div class="card-img-top bg-light" style="height: 180px; border-radius: 12px 12px 0 0; background: linear-gradient(135deg, #E0E0E0, #BDBDBD);"></div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3" style="font-size: 1.2rem;">Kos kecil 01</h5>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-bed me-1"></i>Kasur</span>
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-door-closed me-1"></i>Lemari</span>
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-wifi me-1"></i>Wifi</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <span class="fw-bold">4.7</span>
                        </div>
                        <a href="#" class="btn btn-outline-primary w-100" style="border-color: #00AB6B; color: #00AB6B; border-width: 2px; font-weight: 500; padding: 8px; border-radius: 8px;">Detail kamar</a>
                    </div>
                </div>
            </div>

            <!-- KAMAR 2: Kos Medium 01 -->
            <div class="col-lg-3 col-md-6">
                <div class="card kos-card border-0 shadow-sm h-100">
                    <div class="card-img-top bg-light" style="height: 180px; border-radius: 12px 12px 0 0; background: linear-gradient(135deg, #E0E0E0, #BDBDBD);"></div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3" style="font-size: 1.2rem;">Kos Medium 01</h5>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-wifi me-1"></i>WiFi</span>
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-journal-bookmark-fill me-1"></i>Meja belajar</span>
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-bed me-1"></i>Kasur</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <span class="fw-bold">4.7</span>
                        </div>
                        <a href="#" class="btn btn-outline-primary w-100" style="border-color: #00AB6B; color: #00AB6B; border-width: 2px; font-weight: 500; padding: 8px; border-radius: 8px;">Detail kamar</a>
                    </div>
                </div>
            </div>

            <!-- KAMAR 3: Kos Mewah 01 -->
            <div class="col-lg-3 col-md-6">
                <div class="card kos-card border-0 shadow-sm h-100">
                    <div class="card-img-top bg-light" style="height: 180px; border-radius: 12px 12px 0 0; background: linear-gradient(135deg, #E0E0E0, #BDBDBD);"></div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3" style="font-size: 1.2rem;">Kos Mewah 01</h5>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-snow2 me-1"></i>AC</span>
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-journal-bookmark-fill me-1"></i>Meja belajar</span>
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-bed me-1"></i>Kasur +4</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <span class="fw-bold">4.7</span>
                        </div>
                        <a href="#" class="btn btn-outline-primary w-100" style="border-color: #00AB6B; color: #00AB6B; border-width: 2px; font-weight: 500; padding: 8px; border-radius: 8px;">Detail kamar</a>
                    </div>
                </div>
            </div>

            <!-- KAMAR 4: Kos kecil 02 -->
            <div class="col-lg-3 col-md-6">
                <div class="card kos-card border-0 shadow-sm h-100">
                    <div class="card-img-top bg-light" style="height: 180px; border-radius: 12px 12px 0 0; background: linear-gradient(135deg, #E0E0E0, #BDBDBD);"></div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3" style="font-size: 1.2rem;">Kos kecil 02</h5>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-wifi me-1"></i>Wifi</span>
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-journal-bookmark-fill me-1"></i>Meja belajar</span>
                            <span class="badge bg-light text-dark me-1 mb-2"><i class="bi bi-bed me-1"></i>Kasur</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <span class="fw-bold">4.7</span>
                        </div>
                        <a href="#" class="btn btn-outline-primary w-100" style="border-color: #00AB6B; color: #00AB6B; border-width: 2px; font-weight: 500; padding: 8px; border-radius: 8px;">Detail kamar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
            <!-- Masonry Grid -->
            <div class="grid-container">
                @foreach($products as $product)
                    <div class="grid-item">
                        @if($product->mainImage && $product->mainImage->image_product)
                            <img src="data:image/jpeg;base64,{{ base64_encode($product->mainImage->image_product) }}"
                                alt="{{ $product->nama }}">
                        @else
                            <img src="{{ asset('img/room-default.jpg') }}" alt="Default Kamar Kos">
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
