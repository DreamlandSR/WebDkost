@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper" style="background:#f9fbfd;">

                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 26px;">Selamat datang, {{ Auth::user()->nama ?? 'Ryan' }} !</h4>
                        <div class="d-flex align-items-center gap-2" style="font-size:13px; color:#888;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                        </div>
                    </div>

                    {{-- Info Kos Cards --}}
                    <h5 class="fw-semibold mb-3 mt-4" style="color:#000; font-size: 17px;">Informasi Kos D'kost</h5>
                    <div class="row g-4 mb-5 mt-2">
                        <div class="col-md-4">
                            <div class="card border-0 rounded-4 h-100 shadow" style="background: linear-gradient(135deg, #6979f8 0%, #4a54e1 100%); position: relative; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 1rem 3rem rgba(105, 121, 248, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';">
                                <svg class="position-absolute" width="160" height="160" style="bottom: -40px; right: -40px; opacity: 0.1;" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" fill="#ffffff" />
                                    <circle cx="80" cy="20" r="25" fill="#ffffff" />
                                </svg>
                                <div class="card-body p-4 pb-4 position-relative" style="z-index: 1;">
                                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                                        <p class="text-white fw-semibold mb-0" style="font-size:12px; letter-spacing: 0.5px; opacity: 0.9; text-transform: uppercase;">Kamar Tersedia</p>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px; height:36px; background: rgba(255,255,255,0.2);">
                                            <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                        </div>
                                    </div>
                                    <h3 class="text-white fw-bold mb-0" style="font-size: 30px; letter-spacing: -1px;">{{ $totalKamarTersedia ?? 24 }} <span class="fw-normal" style="font-size: 14px; opacity:0.8; letter-spacing: 0;">Kamar</span></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 rounded-4 h-100 shadow" style="background: linear-gradient(135deg, #40b883 0%, #2a9b6c 100%); position: relative; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 1rem 3rem rgba(64, 184, 131, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';">
                                <svg class="position-absolute" width="160" height="160" style="bottom: -30px; right: -30px; opacity: 0.1;" viewBox="0 0 100 100">
                                    <polygon points="50 15, 100 100, 0 100" fill="#ffffff"/>
                                </svg>
                                <div class="card-body p-4 pb-4 position-relative" style="z-index: 1;">
                                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                                        <p class="text-white fw-semibold mb-0" style="font-size:12px; letter-spacing: 0.5px; opacity: 0.9; text-transform: uppercase;">Kamar Terisi</p>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px; height:36px; background: rgba(255,255,255,0.2);">
                                            <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        </div>
                                    </div>
                                    <h3 class="text-white fw-bold mb-0" style="font-size: 30px; letter-spacing: -1px;">{{ $totalKamarTerisi ?? 12 }} <span class="fw-normal" style="font-size: 14px; opacity:0.8; letter-spacing: 0;">Kamar</span></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 rounded-4 h-100 shadow" style="background: linear-gradient(135deg, #ffb259 0%, #f9982b 100%); position: relative; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 1rem 3rem rgba(255, 178, 89, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';">
                                <svg class="position-absolute" width="160" height="160" style="bottom: -20px; right: -20px; opacity: 0.15;" viewBox="0 0 100 100">
                                    <path d="M0 100 Q 50 50, 100 100 Z" fill="#ffffff"/>
                                    <path d="M50 100 Q 75 75, 100 100 Z" fill="#ffffff" opacity="0.5"/>
                                </svg>
                                <div class="card-body p-4 pb-4 position-relative" style="z-index: 1;">
                                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                                        <p class="text-white fw-semibold mb-0" style="font-size:12px; letter-spacing: 0.5px; opacity: 0.9; text-transform: uppercase;">Pendapatan bulanan</p>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px; height:36px; background: rgba(255,255,255,0.25);">
                                            <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                        </div>
                                    </div>
                                    <h3 class="text-white fw-bold mb-0" style="font-size: 26px; letter-spacing: -0.5px;">Rp {{ number_format($totalPembayaran ?? 24000000, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Chart + Keluhan --}}
                    <div class="row g-4 mb-5">
                        {{-- Pertumbuhan Chart --}}
                        <div class="col-md-7">
                            <div class="card border-0 rounded-4 h-100 shadow-sm p-4" style="background:#fff; border: 1px solid #f0f0f0; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.08)';" onmouseout="this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e0f5eb; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0, 166, 105, 0.15); margin-right: 20px;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                  <path d="M4 19L10 13L14 17L21 9" stroke="#00a669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                  <path d="M15 9H21V15" stroke="#00a669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1" style="font-size: 19px; color:#111; letter-spacing: -0.5px;">Pertumbuhan Pendapatan</h5>
                                                <div class="text-muted" style="font-size:12px;">Data statistik penyewaan per bulan selama 1 tahun</div>
                                            </div>
                                        </div>
                                        <span class="badge rounded-pill d-flex align-items-center gap-1" style="background: rgba(0,166,105,0.1); color: #00a669; font-size: 11px; padding: 6px 12px; border: 1px solid rgba(0,166,105,0.2);">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                                            +14.5%
                                        </span>
                                    </div>
                                    <div class="mt-4 mb-4 text-secondary" style="font-size: 13px; line-height: 1.6; max-width: 95%;">
                                        Grafik ini merepresentasikan kenaikan dan tingkat fluktuasi jumlah booking kamar yang telah selesai beserta performa pendapatan dari setiap bulannya.
                                    </div>
                                    <div style="height: 230px; margin-top: 15px;">
                                        <canvas id="growthChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Keluhan Terbaru --}}
                        <div class="col-md-5">
                            <div class="card border-0 rounded-4 shadow-sm h-100 p-4" style="background-color: #fcfdfd;">
                                <div class="card-body p-2">
                                    <h5 class="fw-bold mb-4" style="font-size: 20px; color:#000;">Keluhan pengguna</h5>
                                    
                                    @php
                                        // Fake data to match design if empty
                                        $dummyKeluhan = [
                                            (object)['nama' => 'Anoymous', 'nomor_kamar' => '5', 'tgl_lapor' => now()->subDay(), 'deskripsi_masalah' => 'Mas, gentengnya bocor'],
                                            (object)['nama' => 'Anoymous', 'nomor_kamar' => '5', 'tgl_lapor' => now()->subDay(), 'deskripsi_masalah' => 'Mas, gentengnya bocor'],
                                            (object)['nama' => 'Anoymous', 'nomor_kamar' => '5', 'tgl_lapor' => now()->subDay(), 'deskripsi_masalah' => 'Mas, gentengnya bocor']
                                        ];
                                        $listKeluhan = !empty($keluhanTerbaru) && count($keluhanTerbaru) > 0 ? $keluhanTerbaru : $dummyKeluhan;
                                    @endphp

                                    @foreach ($listKeluhan as $index => $keluhan)
                                        @if($index < 3)
                                        <div class="mb-3 p-3 rounded-4 shadow-sm" style="background:#fff; border: 1px solid #f0f0f0; border-left: 5px solid #00a669; transition: all 0.25s ease;" onmouseover="this.style.transform='translateX(4px)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.08)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
                                            <div class="d-flex align-items-center mb-2 pb-2" style="border-bottom: 1px solid #f5f5f5;">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px; height:38px; background:rgba(0,166,105,0.08); margin-right: 14px;">
                                                    <svg width="18" height="18" fill="none" stroke="#00a669" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="fw-bold mb-0 text-dark" style="font-size:13px;">{{ $keluhan->nama ?? 'Anoymous' }}</div>
                                                        <span class="badge rounded-pill fw-medium" style="background:#eafcf4; color:#00a669; font-size:9px; padding: 4px 8px;">Baru saja</span>
                                                    </div>
                                                    <div class="text-muted" style="font-size:11px; margin-top:2px;">
                                                        Kamar {{ $keluhan->nomor_kamar ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-start mt-2 pt-1 px-1">
                                                <div style="margin-right: 14px; margin-left: 10px; color:#00a669; opacity:0.85;" class="flex-shrink-0 mt-1">
                                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                                </div>
                                                <div style="font-size:13px; font-weight: 500; line-height: 1.5; color:#555;">
                                                    "{{ Str::limit($keluhan->deskripsi_masalah, 65) }}"
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pengeluaran --}}
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <div>
                                    <h5 class="fw-bold mb-1" style="font-size: 20px; color:#1a2035; letter-spacing: -0.5px;">Pengeluaran Bulanan</h5>
                                    <div class="text-muted" style="font-size: 13px;">Rincian transaksi dan biaya operasional</div>
                                </div>
                                <a href="{{ url('/laporan/pengeluaran') }}" class="btn btn-sm border-0 rounded-pill" style="background:#f0f4f8; color:#4a54e1; font-weight:600; font-size:12px; padding: 8px 16px;">Lihat Laporan</a>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-8 pe-md-4">
                                    @php
                                        // Colors matching modern fintech aesthetics
                                        $colors = ['#ff4757', '#ffa502', '#2ed573', '#1e90ff', '#5352ed', '#ff7f50', '#a83279', '#00b894'];
                                        
                                        $dummyPengeluaran = [
                                            (object)['nominal' => 1500000, 'kategori' => 'Listrik', 'color' => '#6979f8'],
                                            (object)['nominal' => 125000, 'kategori' => 'Sapu', 'color' => '#00a669'],
                                            (object)['nominal' => 300000, 'kategori' => 'PDAM', 'color' => '#ffb259'],
                                            (object)['nominal' => 100000, 'kategori' => 'Selang', 'color' => '#2979ff'],
                                            (object)['nominal' => 250000, 'kategori' => 'Dapur', 'color' => '#ff4757'],
                                            (object)['nominal' => 85000, 'kategori' => 'Cikrak', 'color' => '#9c88ff'],
                                            (object)['nominal' => 149000, 'kategori' => 'Dapur', 'color' => '#44bd32'],
                                            (object)['nominal' => 75000, 'kategori' => 'Sampah', 'color' => '#e84118'],
                                        ];
                                        $listPengeluaran = !empty($pengeluaranBulanan) && count($pengeluaranBulanan) > 0 ? $pengeluaranBulanan : $dummyPengeluaran;

                                        // Calculate Chart Data & Total
                                        $totalPengeluaran = 0;
                                        $chartLabels = [];
                                        $chartData = [];
                                        $chartColors = [];
                                        
                                        foreach($listPengeluaran as $index => $item) {
                                            $totalPengeluaran += $item->nominal;
                                            if($index < 4) {
                                                $chartLabels[] = $item->kategori;
                                                $chartData[] = $item->nominal;
                                                $chartColors[] = isset($item->color) ? $item->color : $colors[$index % 8];
                                            }
                                        }
                                        
                                        // Sum remaining items as 'Lainnya'
                                        if (count($listPengeluaran) > 4) {
                                            $lainnyaSum = 0;
                                            for($i = 4; $i < count($listPengeluaran); $i++) {
                                                $lainnyaSum += $listPengeluaran[$i]->nominal;
                                            }
                                            $chartLabels[] = 'Lainnya';
                                            $chartData[] = $lainnyaSum;
                                            $chartColors[] = '#cbd5e1';
                                        }
                                        
                                        $totalFormatted = 'Rp ' . number_format($totalPengeluaran, 0, ',', '.');
                                        $currentMonth = \Carbon\Carbon::now()->locale('id')->translatedFormat('F');
                                    @endphp
                                    
                                    <div class="row g-3">
                                        @foreach ($listPengeluaran as $index => $item)
                                            @if($index < 8)
                                                <div class="col-md-6 mb-2">
                                                    <div class="d-flex align-items-center p-3 rounded-4 shadow-sm" style="background:#ffffff; border: 1px solid rgba(0,0,0,0.03); transition: all 0.3s ease; cursor: default;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.06)'; this.style.borderColor='rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)'; this.style.borderColor='rgba(0,0,0,0.03)';">
                                                        @php $color = isset($item->color) ? $item->color : $colors[$index % 8]; @endphp
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:42px; height:42px; background: {{ $color }}15; margin-right: 15px;">
                                                            <div class="rounded-circle" style="width: 10px; height: 10px; background: {{ $color }}; box-shadow: 0 0 8px {{ $color }}60;"></div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="text-muted mb-1" style="font-size:10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;">{{ $item->kategori }}</div>
                                                            <div class="fw-bold" style="font-size:15px; color:#1a2035; letter-spacing: -0.3px;">Rp {{ number_format($item->nominal, 0, ',', '.') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center mt-5 mt-md-0 border-start ps-md-4">
                                    <div class="position-relative d-flex align-items-center justify-content-center" style="width: 250px; height: 250px;">
                                        <canvas id="pengeluaranChart" style="position: relative; z-index: 2;"></canvas>
                                        
                                        <div class="position-absolute d-flex flex-column align-items-center justify-content-center w-100 h-100" style="z-index: 1;">
                                            <div class="d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(74, 84, 225, 0.1); border-radius: 50%; margin-bottom: 6px;">
                                                <i class="ti-wallet" style="color: #4a54e1; font-size: 18px;"></i>
                                            </div>
                                            <div class="text-muted" style="font-size:10px; font-weight: 700; text-transform:uppercase; letter-spacing: 1px; margin-bottom: 2px;">Total {{ $currentMonth }}</div>
                                            <h4 class="fw-bold mb-0" style="font-size: 19px; color:#1a2035; letter-spacing: -0.5px;">{{ $totalFormatted }}</h4>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <span class="badge rounded-pill fw-medium d-inline-flex align-items-center gap-2" style="background:#f0f4f8; color:#6a7b9a; font-size:11.5px; padding: 8px 20px;">
                                            <i class="ti-stats-down" style="color: #ff4757;"></i> Ringkasan Pengeluaran
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Bar Chart - Pertumbuhan
        const ctx = document.getElementById('growthChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        data: [6.2, 3.7, 1.4, 3.7, 7.2, 3.7, 1.5, 5, 1.2, 3.1, 2.1, 6.2],
                        backgroundColor: 'rgba(0, 166, 105, 0.85)',
                        hoverBackgroundColor: '#00a669',
                        borderWidth: 0,
                        barPercentage: 0.55
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    tooltips: {
                        backgroundColor: '#fff',
                        titleFontColor: '#333',
                        bodyFontColor: '#666',
                        borderColor: '#e5e5e5',
                        borderWidth: 1,
                        xPadding: 12,
                        yPadding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return "Pendapatan: " + tooltipItem.yLabel + " M";
                            }
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: { beginAtZero: true, max: 7, stepSize: 1, fontColor: '#999', fontSize: 11, padding: 10 },
                            gridLines: { display:true, color: 'rgba(0,0,0,0.04)', drawBorder: false, zeroLineColor: 'rgba(0,0,0,0.06)' }
                        }],
                        xAxes: [{
                            ticks: { fontColor: '#999', fontSize: 11, padding: 10 },
                            gridLines: { display: false, drawBorder: false }
                        }]
                    }
                }
            });
        }

        // Donut Chart - Pengeluaran
        const ctx2 = document.getElementById('pengeluaranChart')?.getContext('2d');
        if (ctx2) {
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: {!! json_encode($chartColors) !!},
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 80,
                    legend: { display: false },
                    tooltips: {
                        backgroundColor: '#fff',
                        titleFontColor: '#333',
                        bodyFontColor: '#666',
                        borderColor: '#e5e5e5',
                        borderWidth: 1,
                        xPadding: 12,
                        yPadding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var index = tooltipItem.index;
                                var nominal = dataset.data[index];
                                return data.labels[index] + ': Rp ' + nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
    @endpush
@endsection
