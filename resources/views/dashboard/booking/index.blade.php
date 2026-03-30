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
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 26px;">Kelola Booking & Pembayaran</h4>
                        <div class="d-flex align-items-center gap-2" style="font-size:13px; color:#888;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                        </div>
                    </div>

                    {{-- Filter dan Search --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('booking.index') }}" style="display: flex; gap: 12px;">
                                <input type="text" name="search" placeholder="Cari nama..." value="{{ $search }}" style="flex: 1; border: 1px solid #ddd; border-radius: 8px; padding: 10px 14px; font-size: 14px;">
                                <button type="submit" style="background: #6979f8; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; cursor: pointer;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block;">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.35-4.35"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('booking.index') }}" style="display: flex; gap: 12px;">
                                <select name="status_filter" style="flex: 1; border: 1px solid #ddd; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #333;">
                                    <option value="">Semua Status</option>
                                    <option value="Berhasil" {{ $statusFilter === 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
                                    <option value="Menunggu" {{ $statusFilter === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Dibayar" {{ $statusFilter === 'Dibayar' ? 'selected' : '' }}>Dibayar</option>
                                    <option value="Piutangan" {{ $statusFilter === 'Piutangan' ? 'selected' : '' }}>Piutangan</option>
                                </select>
                                <button type="submit" style="background: #40b883; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; cursor: pointer;">Filter</button>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Booking & Pembayaran --}}
                    <div class="card border-0 rounded-4 shadow-sm" style="background:#fff; border: 1px solid #f0f0f0;">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 14px;">
                                    <thead style="background: #f9fbfd; border-bottom: 2px solid #e8eef2;">
                                        <tr>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center; width: 60px;">No</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Nama</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Status Pembayaran</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Tanggal Pembayaran</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: right;">Jumlah Pembayaran</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($bookings) > 0)
                                            @foreach($bookings as $index => $booking)
                                                <tr style="border-bottom: 1px solid #f0f0f0; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f9fbfd';" onmouseout="this.style.backgroundColor='transparent';">
                                                    <td style="color: #333; padding: 16px 12px; text-align: center; font-weight: 600;">{{ ($currentPage - 1) * 5 + $index + 1 }}</td>
                                                    <td style="color: #333; padding: 16px 12px; font-weight: 600;">{{ $booking['nama'] }}</td>
                                                    <td style="color: #333; padding: 16px 12px;">
                                                        @php
                                                            $statusStyles = [
                                                                'Berhasil' => ['bg' => '#d4edda', 'text' => '#155724'],
                                                                'Menunggu' => ['bg' => '#fff3cd', 'text' => '#856404'],
                                                                'Dibayar' => ['bg' => '#cce5ff', 'text' => '#0c5a96'],
                                                                'Piutangan' => ['bg' => '#f8d7da', 'text' => '#721c24'],
                                                            ];
                                                            $style = $statusStyles[$booking['status_pembayaran']] ?? ['bg' => '#f0f0f0', 'text' => '#666'];
                                                        @endphp
                                                        <span style="background: {{ $style['bg'] }}; color: {{ $style['text'] }}; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 12px; display: inline-block;">
                                                            {{ $booking['status_pembayaran'] }}
                                                        </span>
                                                    </td>
                                                    <td style="color: #666; padding: 16px 12px;">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $booking['tanggal_pembayaran'])->locale('id')->translatedFormat('d M Y') }}</td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: right; font-weight: 600;">Rp {{ number_format($booking['jumlah_pembayaran'], 0, ',', '.') }}</td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: center;">
                                                        <button type="button" class="btn btn-sm" style="background: #6979f8; color: white; border: none; border-radius: 6px; padding: 6px 10px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" data-toggle="tooltip" title="Detail" onclick="detailBooking({{ $booking['id'] }})">
                                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block;">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                                                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="display: inline-block; margin-bottom: 10px; opacity: 0.5;">
                                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                    </svg>
                                                    <p style="margin: 0; font-size: 14px;">Tidak ada data booking</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if($totalPages > 1)
                                <div style="display: flex; justify-content: center; align-items: center; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
                                    @if($currentPage > 1)
                                        <a href="?page={{ $currentPage - 1 }}{{ $search ? '&search=' . $search : '' }}{{ $statusFilter ? '&status_filter=' . $statusFilter : '' }}" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 8px; padding: 8px 16px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; text-decoration: none;">
                                            ← Kembali
                                        </a>
                                    @else
                                        <button type="button" disabled style="background: #ccc; color: white; border: none; border-radius: 8px; padding: 8px 16px; font-weight: 600; cursor: not-allowed; opacity: 0.5;">
                                            ← Kembali
                                        </button>
                                    @endif

                                    <span style="color: #666; font-weight: 600; padding: 0 12px;">
                                        Halaman <strong style="color: #40b883;">{{ $currentPage }}</strong> dari <strong>{{ $totalPages }}</strong>
                                    </span>

                                    @if($currentPage < $totalPages)
                                        <a href="?page={{ $currentPage + 1 }}{{ $search ? '&search=' . $search : '' }}{{ $statusFilter ? '&status_filter=' . $statusFilter : '' }}" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 8px; padding: 8px 16px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; text-decoration: none;">
                                            Selanjutnya →
                                        </a>
                                    @else
                                        <button type="button" disabled style="background: #ccc; color: white; border: none; border-radius: 8px; padding: 8px 16px; font-weight: 600; cursor: not-allowed; opacity: 0.5;">
                                            Selanjutnya →
                                        </button>
                                    @endif
                                </div>
                                <div style="text-align: center; margin-top: 12px; color: #888; font-size: 12px;">
                                    Total {{ $total }} booking
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @include('layouts.sections.navbar')
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #6979f8 0%, #4a54e1 100%); border: none; padding: 20px;">
                    <h5 class="modal-title" style="color: white; font-weight: 700;">Detail Booking & Pembayaran</h5>
                    <button type="button" class="close" style="color: white;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div style="margin-bottom: 16px;">
                        <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Nama</strong></p>
                        <p id="detailNama" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Status Pembayaran</strong></p>
                        <p id="detailStatus" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Tanggal Pembayaran</strong></p>
                        <p id="detailTanggal" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                    </div>
                    <div>
                        <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Jumlah Pembayaran</strong></p>
                        <p id="detailJumlah" style="color: #333; font-weight: 600; font-size: 18px; margin: 0;"></p>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f9fbfd; border-top: 1px solid #f0f0f0; padding: 16px;">
                    <button type="button" class="btn btn-sm" style="background: #ddd; color: #333; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer;" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function detailBooking(id) {
            document.getElementById('detailNama').innerText = 'Rudi Kurniawan';
            document.getElementById('detailStatus').innerText = 'Berhasil';
            document.getElementById('detailTanggal').innerText = '15 Feb 2026';
            document.getElementById('detailJumlah').innerText = 'Rp 1.500.000';
            $('#detailModal').modal('show');
        }
    </script>
    @endpush

@endsection
