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
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 26px;">Kelola Furnitur Kamar</h4>
                        <div class="d-flex align-items-center gap-2" style="font-size:13px; color:#888;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                        </div>
                    </div>

                    {{-- Filter dan Search --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('furniture.index') }}" style="display: flex; gap: 12px;">
                                <input type="text" name="search" placeholder="Cari nama..." value="{{ $search }}" style="flex: 1; border: 1px solid #ddd; border-radius: 8px; padding: 10px 14px; font-size: 14px;">
                                <button type="submit" style="background: #6979f8; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; cursor: pointer;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block;">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.35-4.35"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Furnitur --}}
                    <div class="card border-0 rounded-4 shadow-sm" style="background:#fff; border: 1px solid #f0f0f0;">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 14px;">
                                    <thead style="background: #f9fbfd; border-bottom: 2px solid #e8eef2;">
                                        <tr>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center; width: 60px;">No</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Nomor Kamar</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Nama Furnitur</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center;">Jumlah</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: right;">Harga Sewa</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($furnitures) > 0)
                                            @foreach($furnitures as $index => $furniture)
                                                <tr style="border-bottom: 1px solid #f0f0f0; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f9fbfd';" onmouseout="this.style.backgroundColor='transparent';">
                                                    <td style="color: #333; padding: 16px 12px; text-align: center; font-weight: 600;">{{ ($currentPage - 1) * 5 + $index + 1 }}</td>
                                                    <td style="color: #333; padding: 16px 12px; font-weight: 600;">
                                                        <span style="background: rgba(105, 121, 248, 0.1); color: #6979f8; padding: 6px 12px; border-radius: 6px; display: inline-block;">{{ $furniture['nomor_kamar'] }}</span>
                                                    </td>
                                                    <td style="color: #666; padding: 16px 12px;">{{ $furniture['nama_furnitur'] }}</td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: center; font-weight: 600;">{{ $furniture['jumlah'] }}</td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: right; font-weight: 600;">Rp {{ number_format($furniture['harga_sewa'], 0, ',', '.') }}</td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: center;">
                                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                                            <button type="button" class="btn btn-sm" style="background: #ff6b6b; color: white; border: none; border-radius: 6px; padding: 6px 10px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" data-toggle="tooltip" title="Hapus" onclick="deleteFurniture({{ $furniture['id'] }})">
                                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block;">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                                                                </svg>
                                                            </button>
                                                            <button type="button" class="btn btn-sm" style="background: #6979f8; color: white; border: none; border-radius: 6px; padding: 6px 10px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" data-toggle="tooltip" title="Detail" onclick="detailFurniture({{ $furniture['id'] }})">
                                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block;">
                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                                                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="display: inline-block; margin-bottom: 10px; opacity: 0.5;">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                    </svg>
                                                    <p style="margin: 0; font-size: 14px;">Tidak ada data furnitur</p>
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
                                    Total {{ $total }} furnitur
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @include('layouts.sections.navbar')
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div id="confirmDeleteModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border: none; padding: 20px; background: white;">
                    <button type="button" class="close" style="color: #999;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px; text-align: center;">
                    <svg width="48" height="48" fill="none" stroke="#ff6b6b" stroke-width="1.5" viewBox="0 0 24 24" style="display: inline-block; margin-bottom: 16px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <h5 style="font-weight: 700; color: #333; margin-bottom: 8px;">Hapus data</h5>
                    <p style="color: #666; font-size: 14px; margin: 0;">Apakah Anda yakin ingin menghapus furnitur ini?</p>
                </div>
                <div class="modal-footer" style="background: #f9fbfd; border-top: 1px solid #f0f0f0; padding: 16px; gap: 8px;">
                    <button type="button" class="btn btn-sm" style="background: white; color: #333; border: 1px solid #ddd; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 13px;" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-sm" style="background: #ff6b6b; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 13px;" onclick="confirmDelete()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #6979f8 0%, #4a54e1 100%); border: none; padding: 20px;">
                    <h5 class="modal-title" style="color: white; font-weight: 700;">Detail Furnitur</h5>
                    <button type="button" class="close" style="color: white;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="detail-content">
                        <div style="margin-bottom: 16px;">
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Nomor Kamar</strong></p>
                            <p id="detailNomarKamar" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Nama Furnitur</strong></p>
                            <p id="detailNamaFurnitur" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Jumlah</strong></p>
                            <p id="detailJumlah" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                        </div>
                        <div>
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Harga Sewa</strong></p>
                            <p id="detailHarga" style="color: #333; font-weight: 600; font-size: 18px; margin: 0;"></p>
                        </div>
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
        let currentDeleteId = null;

        function deleteFurniture(id) {
            currentDeleteId = id;
            $('#confirmDeleteModal').modal('show');
        }

        function confirmDelete() {
            console.log('Deleting furniture with id:', currentDeleteId);
            alert('Furnitur berhasil dihapus!');
            $('#confirmDeleteModal').modal('hide');
            // location.reload();
        }

        function detailFurniture(id) {
            document.getElementById('detailNomarKamar').innerText = 'D1';
            document.getElementById('detailNamaFurnitur').innerText = 'Lemari, Kursi';
            document.getElementById('detailJumlah').innerText = '2';
            document.getElementById('detailHarga').innerText = 'Rp 75.000';
            $('#detailModal').modal('show');
        }
    </script>
    @endpush

@endsection
