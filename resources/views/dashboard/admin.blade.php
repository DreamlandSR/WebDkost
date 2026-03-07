@extends('layout')


@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card tale-bg">
                                <div class="card-people mt-auto position-relative overflow-hidden">
                                    <div id="batikCarousel" class="carousel carousel-fade" data-bs-ride="carousel"
                                        data-bs-interval="5000">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('/img/Batik 2.jpg') }}" class="d-block w-100 rounded"
                                                    style="object-fit: cover; height: 300px;" alt="Batik Kopi Ijen">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h5>Batik Kopi Ijen</h5>
                                                    <p>Keindahan batik dengan nuansa kopi yang klasik.</p>
                                                </div>
                                            </div>

                                            <div class="carousel-item">
                                                <img src="{{ asset('/img/batik 1.jpg') }}" class="d-block w-100 rounded"
                                                    style="object-fit: cover; height: 300px;" alt="Batik Kopi Ijen 2">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h5>Batik Kopi Ijen 2</h5>
                                                    <p>Sentuhan baru pada motif batik tradisional.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="carousel-control-prev"
                                            style="border: none; outline: none; background-color: rgba(0, 0, 0, 0);"
                                            type="button" data-bs-target="#batikCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        </button>
                                        <button class="carousel-control-next"
                                            style="border: none; outline: none; background-color: rgba(0, 0, 0, 0);"
                                            type="button" data-bs-target="#batikCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 grid-margin transparent">
                            <div class="row">
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-tale">
                                        <div class="card-body">
                                            <p class="mb-4">Total Omset</p>
                                            <p class="mb-2" style="font-size: 24px">Rp
                                                {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
                                            <p>{{ $growthPembayaran }}% (30 days)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Product</p>
                                            <p class="fs-30 mb-2">{{ $totalProduk }}</p>
                                            <p>{{ $growthProduk }}% (30 days)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Total Register</p>
                                            <p class="fs-30 mb-2">{{ $totalRegister }}</p>
                                            <p>{{ $growthRegister }}% (30 days)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 stretch-card transparent">
                                    <div class="card card-light-danger">
                                        <div class="card-body">
                                            <p class="mb-4">Sold Product</p>
                                            <p class="fs-30 mb-2">{{ $totalSold }}</p>
                                            <p>{{ $growthSold }}% (30 days)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-title">Detail Pertumbuhan</p>
                                    <p>Detail chart di bawah ini merupakan data dari pesanan yang telah selesai diterima
                                        dalam setiap bulan selama 1 tahun</p>
                                    <canvas id="growthChart" width="600" height="300"></canvas>
                                    <div class="d-flex flex-wrap mb-5">

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Masukkan data dari PHP ke JavaScript --}}
                        @push('scripts')
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const growthChartData = @json($growthData);
                                    console.log('Chart Data:', growthChartData); // Debug data

                                    const ctx = document.getElementById('growthChart')?.getContext('2d');

                                    if (ctx && window.Chart) {
                                        // Hitung nilai maksimum yang dibulatkan ke kelipatan 20
                                        const maxDataValue = Math.max(...growthChartData.orders);
                                        const roundedMax = maxDataValue > 0 ? Math.ceil(maxDataValue / 20) * 20 : 20;

                                        new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: growthChartData.labels,
                                                datasets: [{
                                                    label: 'Order Selesai perbulan',
                                                    data: growthChartData.orders,
                                                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                                    borderColor: 'rgba(54, 162, 235, 1)',
                                                    borderWidth: 1,
                                                    borderRadius: 8
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                scales: {
                                                    y: {
                                                        beginAtZero: true,
                                                        max: roundedMax,
                                                        ticks: {
                                                            stepSize: 20,
                                                            callback: function(value) {
                                                                return value % 20 === 0 ? value : null;
                                                            }
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Jumlah Order Selesai'
                                                        }
                                                    },
                                                    x: {
                                                        title: {
                                                            display: true,
                                                            text: 'Bulan'
                                                        }
                                                    }
                                                },
                                                plugins: {
                                                    legend: {
                                                        display: false
                                                    },
                                                    tooltip: {
                                                        callbacks: {
                                                            label: function(context) {
                                                                return `Orders: ${context.raw}`;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    } else {
                                        console.error("Chart.js tidak ditemukan atau canvas tidak tersedia.");
                                    }
                                });
                            </script>
                        @endpush


                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card product-favorite-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <p class="card-title mb-0">Product Favorite</p>
                                        <a href="#" class="text-info font-weight-bold">Leaderboard</a>
                                    </div>

                                    <p class="font-weight-500 mb-4">Daftar produk batik terfavorit bulan ini.</p>

                                    <ul class="list-group list-group-flush product-favorite-list">
                                        @foreach ($productFavorite as $index => $produk)
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center product-item">
                                                <div class="d-flex align-items-center">
                                                    <span class="rank-badge">{{ $index + 1 }}</span>
                                                    <span class="font-weight-bold ml-4">{{ $produk->nama_produk }}</span>
                                                </div>
                                                <img src="{{ $produk->image_base64 }}" alt="{{ $produk->nama_produk }}"
                                                    class="product-image"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                        </div>

                        <!-- Custom CSS -->
                        <style>
                            /* Card Styling */
                            .product-favorite-card {
                                border-radius: 15px;
                                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
                                overflow: hidden;
                            }

                            /* List Styling */
                            .product-favorite-list {
                                max-height: 250px;
                                overflow-y: auto;
                                scrollbar-width: thin;
                                scrollbar-color: #6c5ce7 #f1f1f1;
                            }

                            .product-favorite-list::-webkit-scrollbar {
                                width: 6px;
                            }

                            .product-favorite-list::-webkit-scrollbar-track {
                                background: #f1f1f1;
                                border-radius: 10px;
                            }

                            .product-favorite-list::-webkit-scrollbar-thumb {
                                background-color: #6c5ce7;
                                border-radius: 10px;
                            }

                            /* List Item */
                            .product-item {
                                border: none;
                                transition: background 0.3s ease;
                                border-radius: 10px;
                                margin-bottom: 5px;
                            }

                            .product-item:hover {
                                background-color: #f5f7fa;
                            }

                            /* Badge Styling */
                            .rank-badge {
                                width: 35px;
                                height: 35px;
                                background: linear-gradient(135deg, #6c5ce7, #a29bfe);
                                color: #fff;
                                font-weight: bold;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 16px;
                                box-shadow: 0 3px 6px rgba(108, 92, 231, 0.5);
                            }

                            /* Image Styling */
                            .product-image {
                                width: 45px;
                                height: 45px;
                                border-radius: 50%;
                                object-fit: cover;
                                transition: transform 0.3s ease;
                            }

                            .product-image:hover {
                                transform: scale(1.1);
                            }

                            /* Title */
                            .card-title {
                                font-size: 1.3rem;
                                font-weight: 700;
                                color: #2d3436;
                            }
                        </style>

                    </div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card position-relative">
                                <div class="card-body">
                                    <div id="detailedReports"
                                        class="carousel slide detailed-report-carousel position-static pt-2"
                                        data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <div class="dropdown">
                                                        <a class="btn btn-primary rounded shadow-sm dropdown-toggle"
                                                            href="#" role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            Export
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                            <form action="{{ route('export.pesanan.pdf') }}"
                                                                method="GET" class="d-flex gap-2">
                                                                <select name="bulan" required>
                                                                    <option value="">Pilih Bulan</option>
                                                                    @for ($i = 1; $i <= 12; $i++)
                                                                        <option value="{{ $i }}">
                                                                            {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                                                                        </option>
                                                                    @endfor
                                                                </select>

                                                                <select name="tahun" required>
                                                                    <option value="">Pilih Tahun</option>
                                                                    @for ($y = now()->year; $y >= 2023; $y--)
                                                                        <option value="{{ $y }}">
                                                                            {{ $y }}</option>
                                                                    @endfor
                                                                </select>
                                                                <button type="submit">Export PDF</button>
                                                            </form>

                                                            <form action="{{ route('export.pesanan.tahunan') }}"
                                                            method="GET" class="d-flex gap-2">
                                                            <select name="tahun" class="form-select form-select-sm"
                                                                required>
                                                                <option value="">Pilih Tahun</option>
                                                                @for ($y = now()->year; $y >= 2023; $y--)
                                                                    <option value="{{ $y }}"
                                                                        {{ request('tahun') == $y ? 'selected' : '' }}>
                                                                        {{ $y }}</option>
                                                                @endfor
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-info">Export
                                                                Tahunan</button>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div
                                                        class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                                        <div class="ml-xl-4 mt-3">
                                                            <p class="card-title">Detail Laporan</p>
                                                            <h3 class="text-primary font-weight-bold mb-3 fs-3">
                                                                Rp {{ number_format($totalOmsetKeseluruhan, 0, ',', '.') }}
                                                            </h3>
                                                            <h6 class="font-weight-bold mb-xl-4 text-primary fs-4">Total
                                                                Omset Keseluruhan</h6>
                                                            <p class="mb-2 mb-xl-0">Jumlah keseluruhan pendapatan dari
                                                                penjualan batik serta pada detail di samping merupakan batik
                                                                terlaris.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-xl-9">
                                                        <div class="row">
                                                            <div class="col-md-6 border-right">
                                                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                                    <table class="table table-borderless report-table">
                                                                        @if (isset($produkTerlaris) && count($produkTerlaris))
                                                                            @foreach ($produkTerlaris as $produk)
                                                                                @php
                                                                                    $persen = min(
                                                                                        100,
                                                                                        ($produk->total_terjual /
                                                                                            $produkTerlaris->max(
                                                                                                'total_terjual',
                                                                                            )) *
                                                                                            100,
                                                                                    );
                                                                                    $warna = [
                                                                                        'bg-primary',
                                                                                        'bg-warning',
                                                                                        'bg-danger',
                                                                                        'bg-info',
                                                                                    ][$loop->index % 4];
                                                                                @endphp
                                                                                <tr>
                                                                                    <td colspan="3">
                                                                                        <div
                                                                                            class="d-flex justify-content-between">
                                                                                            <span
                                                                                                class="text-muted text-truncate d-block"
                                                                                                style="max-width: 70%;">
                                                                                                {{ $produk->nama_produk }}
                                                                                            </span>
                                                                                            <h6
                                                                                                class="font-weight-bold mb-0">
                                                                                                {{ $produk->total_terjual }}
                                                                                            </h6>
                                                                                        </div>
                                                                                        <div
                                                                                            class="progress progress-sm mt-1">
                                                                                            <div class="progress-bar {{ $warna }}"
                                                                                                role="progressbar"
                                                                                                style="width: {{ $persen }}%"
                                                                                                aria-valuenow="{{ $persen }}"
                                                                                                aria-valuemin="0"
                                                                                                aria-valuemax="100">
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td colspan="3" class="text-center">
                                                                                    Tidak ada data produk terlaris.
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    </table>
                                                                </div>
                                                            </div>


                                                            {{-- chart --}}
                                                            <div class="col-md-6 mt-3">
                                                                <canvas id="north-america-chart"></canvas>
                                                                <div id="north-america-legend"></div>
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
                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>



@endsection
