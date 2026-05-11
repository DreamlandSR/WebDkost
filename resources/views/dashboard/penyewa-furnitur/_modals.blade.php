{{-- ====================== MODAL TAMBAH MANUAL ====================== --}}
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:540px;">
        <div class="modal-content border-0" style="border-radius:14px; overflow:hidden; box-shadow:0 8px 40px rgba(0,0,0,0.13);">
            <div style="background:#fff; padding:22px 26px 18px; border-bottom:1px solid #f0f1f3;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <div style="background:#ecfdf5; border-radius:10px; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="ti-plus" style="color:#00a669; font-size:17px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color:#111827; font-size:16px;">Tambah Penyewa Furnitur</h5>
                            <p class="mb-0" style="color:#9ca3af; font-size:12px;">Tambahkan data sewa furnitur secara manual</p>
                        </div>
                    </div>
                    <button type="button" data-bs-dismiss="modal"
                        style="background:#f3f4f6; border:none; border-radius:50%; width:33px; height:33px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#6b7280;"
                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <i class="ti-close"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body" style="padding:20px 26px; background:#fff; max-height:70vh; overflow-y:auto;">
                <form action="{{ route('penyewa-furnitur.store') }}" method="POST" id="formTambah">
                    @csrf

                    {{-- Penyewa (User) --}}
                    <div class="mb-3">
                        <label class="form-label-custom">Penyewa <span class="text-danger">*</span></label>
                        <select name="id_user" required class="form-input-custom">
                            <option value="">-- Pilih Penyewa --</option>
                            @foreach($userList as $u)
                                <option value="{{ $u->id_user }}" {{ old('id_user')==$u->id_user?'selected':'' }}>
                                    {{ $u->nama }} ({{ $u->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Barang (Item Furnitur) --}}
                    <div class="mb-3">
                        <label class="form-label-custom">Barang (Kode) <span class="text-danger">*</span></label>
                        <select name="id_item" required class="form-input-custom">
                            <option value="">-- Pilih Barang Tersedia --</option>
                            @foreach($itemList as $item)
                                <option value="{{ $item->id_item }}" {{ old('id_item')==$item->id_item?'selected':'' }}>
                                    {{ $item->furnitur->nama_furnitur ?? '' }} — {{ $item->kode_item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Booking (opsional) --}}
                    <div class="mb-3">
                        <label class="form-label-custom">Booking Terkait <span class="text-muted" style="font-weight:400;">(opsional)</span></label>
                        <select name="id_booking" class="form-input-custom">
                            <option value="">-- Tanpa Booking --</option>
                            @foreach($bookingAktif as $b)
                                <option value="{{ $b->id_booking }}" {{ old('id_booking')==$b->id_booking?'selected':'' }}>
                                    #{{ $b->id_booking }} — {{ $b->user->nama ?? '' }} / Kamar {{ $b->kamar->nomor_kamar ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted" style="font-size:11px;">Kosongkan jika sewa furnitur di luar booking</small>
                    </div>


                    {{-- Tanggal --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label-custom">Tgl Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_mulai" required value="{{ old('tgl_mulai') }}" class="form-input-custom">
                        </div>
                        <div class="col-6">
                            <label class="form-label-custom">Tgl Selesai <span class="text-muted" style="font-weight:400;">(opsional)</span></label>
                            <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}" class="form-input-custom">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label-custom">Status <span class="text-danger">*</span></label>
                        <select name="status" required class="form-input-custom">
                            <option value="aktif"   {{ old('status','aktif')==='aktif'  ?'selected':'' }}>Aktif</option>
                            <option value="selesai" {{ old('status')==='selesai'?'selected':'' }}>Selesai</option>
                        </select>
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-3">
                        <label class="form-label-custom">Catatan</label>
                        <textarea name="catatan" rows="2" class="form-input-custom" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" data-bs-dismiss="modal" class="btn-modal-batal">Batal</button>
                        <button type="submit" class="btn-modal-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ====================== MODAL DETAIL ====================== --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px; overflow:hidden;">
            <div style="background:#fff; padding:22px 26px 18px; border-bottom:1px solid #f0f1f3;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <div style="background:#ecfdf5; border-radius:10px; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="ti-info-alt" style="color:#00a669; font-size:18px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color:#111827; font-size:16px;">Detail Penyewa Furnitur</h5>
                            <p class="mb-0" style="color:#9ca3af; font-size:12px;">Informasi lengkap</p>
                        </div>
                    </div>
                    <button type="button" data-bs-dismiss="modal"
                        style="background:#f3f4f6; border:none; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; color:#6b7280;"
                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <i class="ti-close"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body" style="padding:26px; background:#fff;">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="detail-card">
                            <p class="detail-label">Penyewa</p>
                            <p class="detail-value fw-bold" id="detail-penyewa">-</p>
                            <p class="detail-sub" id="detail-email">-</p>
                            <p class="detail-sub" id="detail-telp">-</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-card">
                            <p class="detail-label">Furnitur</p>
                            <p class="detail-value fw-bold" id="detail-furnitur">-</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-card">
                            <p class="detail-label">Kamar</p>
                            <p class="detail-value" id="detail-kamar">-</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="detail-card">
                            <p class="detail-label">Kode Barang</p>
                            <p class="detail-value" id="detail-kode">-</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="detail-card">
                            <p class="detail-label">Harga/Unit</p>
                            <p class="detail-value text-success fw-bold" id="detail-harga">-</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="detail-card">
                            <p class="detail-label">Status</p>
                            <span id="detail-status" class="badge rounded-pill px-3 py-2" style="font-size:12px; font-weight:600;">-</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-card">
                            <p class="detail-label">Tgl Mulai</p>
                            <p class="detail-value" id="detail-tgl-mulai">-</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-card">
                            <p class="detail-label">Tgl Selesai</p>
                            <p class="detail-value" id="detail-tgl-selesai">-</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-card">
                            <p class="detail-label">Catatan</p>
                            <p class="detail-value" id="detail-catatan" style="white-space:pre-line;">-</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-white px-4 pb-4 pt-0">
                <button type="button" class="btn w-100" data-bs-dismiss="modal"
                    style="background:#00a669; color:white; border-radius:8px; font-weight:600; font-size:13.5px; border:none; padding:10px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ====================== MODAL EDIT ====================== --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content border-0" style="border-radius:14px; overflow:hidden; box-shadow:0 8px 40px rgba(0,0,0,0.13);">
            <div style="background:#fff; padding:22px 26px 18px; border-bottom:1px solid #f0f1f3;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <div style="background:#eff6ff; border-radius:10px; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="ti-pencil-alt" style="color:#3b82f6; font-size:17px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color:#111827; font-size:16px;">Edit Status Sewa</h5>
                            <p class="mb-0" style="color:#9ca3af; font-size:12px;">Perbarui status & catatan</p>
                        </div>
                    </div>
                    <button type="button" data-bs-dismiss="modal"
                        style="background:#f3f4f6; border:none; border-radius:50%; width:33px; height:33px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#6b7280;"
                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <i class="ti-close"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body" style="padding:20px 26px; background:#fff;">
                <form id="editFormAction" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label-custom">Status <span class="text-danger">*</span></label>
                        <select name="status" id="editStatus" required class="form-input-custom">
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Tgl Selesai</label>
                        <input type="date" name="tgl_selesai" id="editTglSelesai" class="form-input-custom">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Catatan</label>
                        <textarea name="catatan" id="editCatatan" rows="2" class="form-input-custom" placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" data-bs-dismiss="modal" class="btn-modal-batal">Batal</button>
                        <button type="submit" class="btn-modal-simpan" style="background:#3b82f6;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ====================== MODAL HAPUS ====================== --}}
<div class="modal fade" id="hapusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-body text-center" style="padding:40px 30px;">
                <div style="background:#fef2f2; border-radius:50%; width:70px; height:70px; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                    <i class="ti-trash" style="color:#ef4444; font-size:32px;"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color:#111827; font-size:18px;">Konfirmasi Hapus</h5>
                <p class="mb-4" style="color:#6b7280; font-size:14px; line-height:1.5;">
                    Hapus data sewa furnitur <strong id="hapusNama"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="hapusFormAction" method="POST">
                    @csrf @method('DELETE')
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn text-white py-2 fw-bold"
                            style="background:#ef4444; border-radius:10px; font-size:14px; border:none;">
                            Ya, Hapus Sekarang
                        </button>
                        <button type="button" class="btn py-2" data-bs-dismiss="modal"
                            style="background:#f3f4f6; color:#4b5563; border-radius:10px; font-size:14px; border:none;">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label-custom { font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; display:block; }
    .form-input-custom {
        width:100%; padding:10px 14px; border:1.5px solid #e5e7eb; border-radius:10px;
        font-size:13.5px; color:#111827; outline:none; transition:border-color 0.2s, box-shadow 0.2s;
        background:white;
    }
    .form-input-custom:focus { border-color:#00a669; box-shadow:0 0 0 3px rgba(0,166,105,0.1); }
    .btn-modal-batal {
        padding:9px 22px; border-radius:8px; border:1.5px solid #e5e7eb;
        background:white; font-weight:600; font-size:13.5px; color:#6b7280; cursor:pointer; transition:0.2s;
    }
    .btn-modal-batal:hover { background:#f9fafb; }
    .btn-modal-simpan {
        padding:9px 26px; border-radius:8px; border:none;
        background:linear-gradient(135deg, #00a669, #008a57);
        color:white; font-weight:600; font-size:13.5px; cursor:pointer; transition:opacity 0.2s;
    }
    .btn-modal-simpan:hover { opacity:0.9; }
    .detail-card { background:#f9fafb; border:1px solid #f0f1f3; border-radius:10px; padding:12px 14px; }
    .detail-label { color:#6b7280; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px; }
    .detail-value { color:#111827; font-size:13.5px; margin-bottom:0; }
    .detail-sub { color:#9ca3af; font-size:12px; margin-bottom:0; }
</style>
