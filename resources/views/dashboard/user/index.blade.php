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
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 26px;">Kelola Pengguna Kamar</h4>
                        <div class="d-flex align-items-center gap-2" style="font-size:13px; color:#888;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                        </div>
                    </div>

                    {{-- Filter dan Search --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('user.index') }}" style="display: flex; gap: 12px;">
                                <input type="text" name="search" placeholder="Cari penghuni..." value="{{ $search }}" style="flex: 1; border: 1px solid #ddd; border-radius: 8px; padding: 10px 14px; font-size: 14px;">
                                <button type="submit" style="background: #6979f8; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; cursor: pointer;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block;">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.35-4.35"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Pengguna --}}
                    <div class="card border-0 rounded-4 shadow-sm" style="background:#fff; border: 1px solid #f0f0f0;">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 14px;">
                                    <thead style="background: #f9fbfd; border-bottom: 2px solid #e8eef2;">
                                        <tr>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center; width: 60px;">No</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Nomor Kamar</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Nama Penghuni</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">No. HP</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: right;">Total Harga</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($users) > 0)
                                            @foreach($users as $index => $user)
                                                <tr style="border-bottom: 1px solid #f0f0f0; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f9fbfd';" onmouseout="this.style.backgroundColor='transparent';">
                                                    <td style="color: #333; padding: 16px 12px; text-align: center; font-weight: 600;">{{ ($currentPage - 1) * 5 + $index + 1 }}</td>
                                                    <td style="color: #333; padding: 16px 12px; font-weight: 600;">
                                                        <span style="background: rgba(105, 121, 248, 0.1); color: #6979f8; padding: 6px 12px; border-radius: 6px; display: inline-block;">{{ $user['nomor_kamar'] }}</span>
                                                    </td>
                                                    <td style="color: #666; padding: 16px 12px;">{{ $user['nama'] }}</td>
                                                    <td style="color: #666; padding: 16px 12px;">{{ $user['no_hp'] }}</td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: right; font-weight: 600;">Rp {{ number_format($user['total_harga'], 0, ',', '.') }}</td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: center;">
                                                        <button type="button" class="btn btn-sm" style="background: #6979f8; color: white; border: none; border-radius: 6px; padding: 6px 10px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" data-toggle="tooltip" title="Detail" onclick="detailUser({{ $user['id'] }})">
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
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    <p style="margin: 0; font-size: 14px;">Tidak ada data pengguna</p>
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
                                        <a href="?page={{ $currentPage - 1 }}{{ $search ? '&search=' . $search : '' }}" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; text-decoration: none;">
                                            ← Kembali
                                        </a>
                                    @else
                                        <button type="button" disabled style="background: #ccc; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: not-allowed; opacity: 0.5;">
                                            ← Kembali
                                        </button>
                                    @endif

                                    <span style="color: #666; font-weight: 600; padding: 0 12px;">
                                        Halaman <strong style="color: #40b883;">{{ $currentPage }}</strong> dari <strong>{{ $totalPages }}</strong>
                                    </span>

                                    @if($currentPage < $totalPages)
                                        <a href="?page={{ $currentPage + 1 }}{{ $search ? '&search=' . $search : '' }}" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; text-decoration: none;">
                                            Selanjutnya →
                                        </a>
                                    @else
                                        <button type="button" disabled style="background: #ccc; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: not-allowed; opacity: 0.5;">
                                            Selanjutnya →
                                        </button>
                                    @endif
                                </div>
                                <div style="text-align: center; margin-top: 12px; color: #888; font-size: 12px;">
                                    Total {{ $total }} pengguna
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
                <div class="modal-header" style="background: linear-gradient(135deg, #40b883 0%, #2e9360 100%); border: none; padding: 20px;">
                    <h5 class="modal-title" style="color: white; font-weight: 700;">Detail Pengguna</h5>
                    <button type="button" class="close" style="color: white;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div style="margin-bottom: 16px;">
                        <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Nomor Kamar</strong></p>
                        <p id="detailNomorKamar" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Nama Penghuni</strong></p>
                        <p id="detailNama" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>No. HP</strong></p>
                        <p id="detailNoHp" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                    </div>
                    <div>
                        <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Total Harga</strong></p>
                        <p id="detailTotalHarga" style="color: #333; font-weight: 600; font-size: 18px; margin: 0;"></p>
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
        function detailUser(id) {
            document.getElementById('detailNomorKamar').innerText = 'D1';
            document.getElementById('detailNama').innerText = 'Rudi Kurniawan';
            document.getElementById('detailNoHp').innerText = '081234567890';
            document.getElementById('detailTotalHarga').innerText = 'Rp 1.500.000';
            $('#detailModal').modal('show');
        }
    </script>
    @endpush

@endsection
