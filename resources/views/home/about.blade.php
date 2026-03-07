@extends('templates.layout')

@section('content')

    @include('templates.header')
    @include('templates.navbar')

    <main class="flex-shrink-0 fade-in">
        <!-- Header-->
        <header class="py-5 slide-in">
            <div class="container px-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-xxl-6">
                        <div class="text-center my-3">
                            <h1 class="fw-bolder mb-3">Visi dan Misi kami sebagai pengembang Batik</h1>
                            <p class="lead fw-normal text-muted mb-4">
                                "Mewujudkan batik berkualitas yang inovatif dan berdaya saing global, dengan tetap menjaga
                                nilai tradisi serta mengembangkan kreativitas, tanggung jawab, dan semangat pelestarian
                                budaya dalam setiap helai kain batik yang dihasilkan."</p>
                            <a class="btn btn-primary btn-lg" href="#scroll-target">Read our story</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- About section one-->
        <section class="py-5 bg-light slide-in" id="scroll-target">
            <div class="container px-5 my-5">
                <div class="row gx-5 align-items-center">
                    <div class="col-lg-6">
                        <img src="{{ asset('img/sanggar-batik.jpg') }}" alt="..."
                            class="img-fluid rounded mb-5 mb-lg-0"
                            style="width: 450px; height: 300px; object-fit: cover;" />
                    </div>
                    <div class="col-lg-6">
                        <h2 class="fw-bolder">Pendirian Kami</h2>
                        <p class="lead fw-normal text-muted mb-0">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto est, ut esse a labore aliquam
                            beatae expedita. Blanditiis impedit numquam libero molestiae et fugit cupiditate, quibusdam
                            expedita, maiores eaque quisquam.
                        </p>
                    </div>
                </div>

            </div>
        </section>
        <!-- About section two-->
        <section class="py-5 slide-in">
            <div class="container px-5 my-5">
                <div class="row gx-5 align-items-center">
                    <div class="col-lg-6 order-first order-lg-last">
                        <img class="img-fluid rounded mb-5 mb-lg-0" src="{{ asset('img/mitra.jpeg') }}" alt="..."
                            style="width: 450px; height: 300px; object-fit: cover;" />
                    </div>
                    <div class="col-lg-6">
                        <h2 class="fw-bolder">Pertumbuhan Mitra</h2>
                        <p class="lead fw-normal text-muted mb-0">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto est, ut esse a labore aliquam
                            beatae expedita. Blanditiis impedit numquam libero molestiae et fugit cupiditate, quibusdam
                            expedita, maiores eaque quisquam.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team members section-->
        <section class="py-5 bg-light slide-in">
            <div class="container px-5 my-5">
                <div class="text-center">
                    <h2 class="fw-bolder">Struktur organisasi</h2>
                    <p class="lead fw-normal text-muted mb-5">Pengurus dan Penanggung Jawab</p>
                </div>
                <div class="row gx-5 row-cols-1 row-cols-sm-2 row-cols-xl-4 justify-content-center">
                    <div class="col mb-5 mb-xl-0">
                        <div class="text-center">
                            <img class="img-fluid rounded-circle mb-4 px-4"
                                src="https://dummyimage.com/150x150/ced4da/6c757d" alt="..." />
                            <h5 class="fw-bolder">Junedi</h5>
                            <div class="fst-italic text-muted">Pemilik</div>
                        </div>
                    </div>
                    <div class="col mb-5 mb-xl-0">
                        <div class="text-center">
                            <img class="img-fluid rounded-circle mb-4 px-4"
                                src="https://dummyimage.com/150x150/ced4da/6c757d" alt="..." />
                            <h5 class="fw-bolder">Jaenedy</h5>
                            <div class="fst-italic text-muted">Manajer Keuangan</div>
                        </div>
                    </div>
                    <div class="col mb-5 mb-sm-0">
                        <div class="text-center">
                            <img class="img-fluid rounded-circle mb-4 px-4"
                                src="https://dummyimage.com/150x150/ced4da/6c757d" alt="..." />
                            <h5 class="fw-bolder">Fatimah</h5>
                            <div class="fst-italic text-muted">Manajer Operasional</div>
                        </div>
                    </div>
                    <div class="col mb-5">
                        <div class="text-center">
                            <img class="img-fluid rounded-circle mb-4 px-4"
                                src="https://dummyimage.com/150x150/ced4da/6c757d" alt="..." />
                            <h5 class="fw-bolder">Manohara</h5>
                            <div class="fst-italic text-muted">Manajer Pemasaran</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('templates.main_footer')
    @include('templates.footer')
