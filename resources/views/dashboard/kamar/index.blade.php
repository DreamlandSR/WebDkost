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
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 26px;">Kelola Data Kamar</h4>
                        <div class="d-flex align-items-center gap-2" style="font-size:13px; color:#888;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                        </div>
                    </div>

                    {{-- Filter dan Tools --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <label for="statusFilter" style="font-weight: 600; color: #333; white-space: nowrap;">Filter Status:</label>
                                <select id="statusFilter" class="form-select" style="max-width: 200px; border: 1px solid #ddd; border-radius: 8px; padding: 8px 12px; font-size: 14px;" onchange="filterStatus(this.value)">
                                    <option value="">Semua Status</option>
                                    <option value="Tersedia" {{ $status === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="Terisi" {{ $status === 'Terisi' ? 'selected' : '' }}>Terisi</option>
                                    <option value="Maintenance" {{ $status === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('kamar.create') }}" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; transition: all 0.3s ease;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block; margin-right: 6px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah Kamar
                            </a>
                        </div>
                    </div>

                    {{-- Tabel Kamar --}}
                    <div class="card border-0 rounded-4 shadow-sm" style="background:#fff; border: 1px solid #f0f0f0;">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 14px;">
                                    <thead style="background: #f9fbfd; border-bottom: 2px solid #e8eef2;">
                                        <tr>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center; width: 60px;">No</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Nomor Kamar</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Tipe Kamar</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px;">Fasilitas</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: right;">Harga</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center;">Status</th>
                                            <th style="color: #666; font-weight: 700; padding: 16px 12px; text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($kamars) > 0)
                                            @foreach($kamars as $index => $kamar)
                                                <tr style="border-bottom: 1px solid #f0f0f0; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f9fbfd';" onmouseout="this.style.backgroundColor='transparent';">
                                                    <td style="color: #333; padding: 16px 12px; text-align: center; font-weight: 600;">{{ ($currentPage - 1) * 5 + $index + 1 }}</td>
                                                    <td style="color: #333; padding: 16px 12px; font-weight: 600;">
                                                        <span style="background: rgba(64, 184, 131, 0.1); color: #40b883; padding: 6px 12px; border-radius: 6px; display: inline-block;">{{ $kamar['nomor_kamar'] }}</span>
                                                    </td>
                                                    <td style="color: #666; padding: 16px 12px;">{{ $kamar['tipe_kamar'] }}</td>
                                                    <td style="color: #666; padding: 16px 12px; max-width: 250px; white-space: normal;">
                                                        <small>{{ $kamar['fasilitas'] }}</small>
                                                    </td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: right; font-weight: 600;">Rp {{ number_format($kamar['harga'], 0, ',', '.') }}</td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: center;">
                                                        @if($kamar['status'] === 'Tersedia')
                                                            <span style="background: rgba(64, 184, 131, 0.15); color: #40b883; padding: 6px 12px; border-radius: 6px; display: inline-block; font-size: 12px; font-weight: 600;">{{ $kamar['status'] }}</span>
                                                        @elseif($kamar['status'] === 'Terisi')
                                                            <span style="background: rgba(105, 121, 248, 0.15); color: #6979f8; padding: 6px 12px; border-radius: 6px; display: inline-block; font-size: 12px; font-weight: 600;">{{ $kamar['status'] }}</span>
                                                        @else
                                                            <span style="background: rgba(255, 178, 89, 0.15); color: #ffb259; padding: 6px 12px; border-radius: 6px; display: inline-block; font-size: 12px; font-weight: 600;">{{ $kamar['status'] }}</span>
                                                        @endif
                                                    </td>
                                                    <td style="color: #333; padding: 16px 12px; text-align: center;">
                                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                                            <button type="button" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 6px; padding: 6px 10px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" data-toggle="tooltip" title="Edit Kamar" onclick="editKamar({{ $kamar['id'] }})">
                                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block;">
                                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                                </svg>
                                                            </button>
                                                            <button type="button" class="btn btn-sm" style="background: #ff6b6b; color: white; border: none; border-radius: 6px; padding: 6px 10px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" data-toggle="tooltip" title="Hapus Kamar" onclick="deleteKamar({{ $kamar['id'] }})">
                                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block;">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                                                                </svg>
                                                            </button>
                                                            <button type="button" class="btn btn-sm" style="background: #6979f8; color: white; border: none; border-radius: 6px; padding: 6px 10px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" data-toggle="tooltip" title="Lihat Detail" onclick="detailKamar({{ $kamar['id'] }})">
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
                                                <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                                                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="display: inline-block; margin-bottom: 10px; opacity: 0.5;">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                    </svg>
                                                    <p style="margin: 0; font-size: 14px;">Tidak ada data kamar yang sesuai dengan filter</p>
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
                                        <a href="?page={{ $currentPage - 1 }}{{ $status ? '&status=' . $status : '' }}" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; text-decoration: none;">
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
                                        <a href="?page={{ $currentPage + 1 }}{{ $status ? '&status=' . $status : '' }}" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; text-decoration: none;">
                                            Selanjutnya →
                                        </a>
                                    @else
                                        <button type="button" disabled style="background: #ccc; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: not-allowed; opacity: 0.5;">
                                            Selanjutnya →
                                        </button>
                                    @endif
                                </div>
                                <div style="text-align: center; margin-top: 12px; color: #888; font-size: 12px;">
                                    Total {{ $total }} kamar
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @include('layouts.sections.navbar')
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #40b883 0%, #2a9b6c 100%); border: none; padding: 20px;">
                    <h5 class="modal-title" style="color: white; font-weight: 700;">Edit data kamar</h5>
                    <button type="button" class="close" style="color: white;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <form id="editForm">
                        <div class="form-group mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 13px;">Kamar</label>
                            <input type="text" class="form-control" id="editNomorKamar" placeholder="Masukkan nomor kamar" style="border: 1px solid #ddd; border-radius: 8px; padding: 10px 12px; font-size: 14px;">
                        </div>
                        <div class="form-group mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 13px;">Tipe kamar</label>
                            <select class="form-control" id="editTipeKamar" style="border: 1px solid #ddd; border-radius: 8px; padding: 10px 12px; font-size: 14px;">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Standard">Standard</option>
                                <option value="Premium">Premium</option>
                                <option value="Deluxe">Deluxe</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 13px;">Status kamar</label>
                            <select class="form-control" id="editStatus" style="border: 1px solid #ddd; border-radius: 8px; padding: 10px 12px; font-size: 14px;">
                                <option value="">-- Pilih Status --</option>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Terisi">Terisi</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 13px;">Fasilitas</label>
                            <input type="text" class="form-control" id="editFasilitas" placeholder="Ex: Listrik, WiFi, PDAM" style="border: 1px solid #ddd; border-radius: 8px; padding: 10px 12px; font-size: 14px;">
                        </div>
                        <div class="form-group mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 13px;">Deskripsi kamar</label>
                            <textarea class="form-control" id="editDeskripsi" rows="3" placeholder="Deskripsi atau keterangan tambahan" style="border: 1px solid #ddd; border-radius: 8px; padding: 10px 12px; font-size: 14px; resize: none;"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="background: #f9fbfd; border-top: 1px solid #f0f0f0; padding: 16px;">
                    <button type="button" class="btn btn-sm" style="background: #999; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 13px;" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 13px;" onclick="confirmSaveEdit()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Simpan --}}
    <div id="confirmSaveModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border: none; padding: 20px; background: white;">
                    <button type="button" class="close" style="color: #999;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px; text-align: center;">
                    <svg width="48" height="48" fill="none" stroke="#40b883" stroke-width="1.5" viewBox="0 0 24 24" style="display: inline-block; margin-bottom: 16px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M16 12l-4 4-2-2"></path>
                    </svg>
                    <h5 style="font-weight: 700; color: #333; margin-bottom: 8px;">Simpan Perubahan?</h5>
                    <p style="color: #666; font-size: 14px; margin: 0;">Apakah Anda yakin ingin menyimpan perubahan data kamar ini?</p>
                </div>
                <div class="modal-footer" style="background: #f9fbfd; border-top: 1px solid #f0f0f0; padding: 16px; gap: 8px;">
                    <button type="button" class="btn btn-sm" style="background: white; color: #333; border: 1px solid #ddd; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 13px;" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 13px;" onclick="saveEdit()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
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
                    <p style="color: #666; font-size: 14px; margin: 0;">Apakah Anda yakin ingin menghapus data kamar ini? Tindakan ini tidak dapat dibatalkan.</p>
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
                    <h5 class="modal-title" style="color: white; font-weight: 700;">Detail Kamar</h5>
                    <button type="button" class="close" style="color: white;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="detail-content">
                        <div style="margin-bottom: 16px;">
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Nomor Kamar</strong></p>
                            <p id="detailNomorKamar" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Tipe Kamar</strong></p>
                            <p id="detailTipeKamar" style="color: #333; font-weight: 600; font-size: 16px; margin: 0;"></p>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Fasilitas</strong></p>
                            <p id="detailFasilitas" style="color: #666; font-size: 14px; margin: 0; line-height: 1.5;"></p>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Harga</strong></p>
                            <p id="detailHarga" style="color: #333; font-weight: 600; font-size: 18px; margin: 0;"></p>
                        </div>
                        <div>
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;"><strong>Status</strong></p>
                            <div id="detailStatus"></div>
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
        let currentEditId = null;
        let currentDeleteId = null;

        function filterStatus(status) {
            const currentUrl = new URL(window.location);
            if (status) {
                currentUrl.searchParams.set('status', status);
            } else {
                currentUrl.searchParams.delete('status');
            }
            currentUrl.searchParams.set('page', '1');
            window.location = currentUrl.toString();
        }

        function editKamar(id) {
            currentEditId = id;
            // TODO: Load data dari backend berdasarkan ID
            // Untuk sekarang, isi dengan data dummy atau hardcode
            document.getElementById('editNomorKamar').value = 'A101';
            document.getElementById('editTipeKamar').value = 'Standard';
            document.getElementById('editStatus').value = 'Tersedia';
            document.getElementById('editFasilitas').value = 'WiFi, AC, Kasur, Lemari';
            document.getElementById('editDeskripsi').value = 'Kamar standar dengan fasilitas lengkap';
            
            $('#editModal').modal('show');
        }

        function confirmSaveEdit() {
            $('#editModal').modal('hide');
            $('#confirmSaveModal').modal('show');
        }

        function saveEdit() {
            // TODO: Submit form ke backend
            const nomorKamar = document.getElementById('editNomorKamar').value;
            const tipeKamar = document.getElementById('editTipeKamar').value;
            const status = document.getElementById('editStatus').value;
            const fasilitas = document.getElementById('editFasilitas').value;
            const deskripsi = document.getElementById('editDeskripsi').value;

            console.log('Saving kamar:', {
                id: currentEditId,
                nomor_kamar: nomorKamar,
                tipe_kamar: tipeKamar,
                status: status,
                fasilitas: fasilitas,
                deskripsi: deskripsi
            });

            // Simulasi simpan
            alert('Data kamar telah berhasil diperbarui!');
            $('#confirmSaveModal').modal('hide');
            
            // Uncomment untuk auto refresh halaman
            // location.reload();
        }

        function deleteKamar(id) {
            currentDeleteId = id;
            $('#confirmDeleteModal').modal('show');
        }

        function confirmDelete() {
            // TODO: Delete data dari backend
            console.log('Deleting kamar with id:', currentDeleteId);
            
            alert('Data kamar telah berhasil dihapus!');
            $('#confirmDeleteModal').modal('hide');
            
            // Uncomment untuk auto refresh halaman
            // location.reload();
        }

        function detailKamar(id) {
            // TODO: Load dan tampilkan detail kamar dari backend
            // Untuk sekarang, isi dengan data dummy
            document.getElementById('detailNomorKamar').innerText = 'A101';
            document.getElementById('detailTipeKamar').innerText = 'Standard';
            document.getElementById('detailFasilitas').innerText = 'WiFi, AC, Kasur, Lemari';
            document.getElementById('detailHarga').innerText = 'Rp 1.500.000';
            
            const statusBadge = document.createElement('span');
            statusBadge.style.cssText = 'background: rgba(64, 184, 131, 0.15); color: #40b883; padding: 6px 12px; border-radius: 6px; display: inline-block; font-size: 12px; font-weight: 600;';
            statusBadge.innerText = 'Tersedia';
            document.getElementById('detailStatus').innerHTML = '';
            document.getElementById('detailStatus').appendChild(statusBadge);
            
            $('#detailModal').modal('show');
        }
    </script>
    @endpush

@endsection
