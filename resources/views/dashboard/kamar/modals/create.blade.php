<style>
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
<div class="modal fade" id="tambahKamarModal" tabindex="-1" aria-labelledby="tambahKamarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">

            <!-- Header -->
            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <div style="background: #ecfdf5; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i class="ti-home" style="color: #00a669; font-size: 17px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" id="tambahKamarModalLabel" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Tambah Kamar Baru</h5>
                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Isi detail kamar di bawah ini</p>
                        </div>
                    </div>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close"
                        style="background: #f3f4f6; border: none; border-radius: 50%; width: 33px; height: 33px; display:flex; align-items:center; justify-content:center; cursor:pointer; color: #6b7280; font-size: 13px; flex-shrink:0; transition: background 0.2s;"
                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <i class="ti-close"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 18px 26px 20px; background: #fff; max-height: 70vh; overflow-y: auto;">
                @if($errors->any())
                <div class="mb-3" role="alert"
                    style="background: linear-gradient(135deg, #fff5f5, #fff0f0); border-left: 3px solid #f87171; border-radius: 12px; padding: 13px 16px; position: relative; animation: slideDown 0.3s ease;">
                    <div class="d-flex align-items-start" style="gap: 10px;">
                        <div style="background: #fee2e2; border-radius: 8px; width: 30px; height: 30px; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px;">
                            <i class="ti-alert" style="color: #ef4444; font-size: 13px;"></i>
                        </div>
                        <div style="flex:1;">
                            <p style="margin:0 0 4px 0; font-size: 12.5px; font-weight: 700; color: #dc2626; letter-spacing: 0.3px;">Terdapat kesalahan input</p>
                            <ul class="mb-0" style="padding-left: 16px; margin: 0;">
                                @foreach($errors->all() as $error)
                                    <li style="font-size: 12.5px; color: #7f1d1d; margin-bottom: 2px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" onclick="this.closest('[role=alert]').style.display='none'"
                            style="background: none; border: none; color: #9ca3af; cursor: pointer; font-size: 14px; padding: 0; line-height:1; flex-shrink:0;">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                </div>
                @endif

                <form action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data" id="formTambahKamar">
                    @csrf

                    <!-- Nomor Kamar -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-home"></i></span>
                            Nomor Kamar <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nomor_kamar" required
                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                            placeholder="Contoh: 101, A1"
                            value="{{ old('nomor_kamar') }}"
                            onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                    </div>

                    <!-- Tipe Kamar -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-tag"></i></span>
                            Tipe Kamar <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap" style="gap: 10px;" id="tipeGrid">
                            @foreach(['biasa', 'sedang', 'mewah'] as $tipe)
                            <label class="tipe-pill" style="cursor:pointer;">
                                <input type="radio" name="tipe_kamar" value="{{ $tipe }}" onchange="selectTipe(this)" {{ old('tipe_kamar') == $tipe ? 'checked' : '' }}>
                                <span class="pill-label" style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $tipe }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('tipe_kamar')
                            <div class="d-flex align-items-center mt-2" style="gap: 7px; background: linear-gradient(135deg, #fffbeb, #fef3c7); border-left: 3px solid #f59e0b; border-radius: 8px; padding: 8px 12px; animation: slideDown 0.25s ease;">
                                <span style="background: #fde68a; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="ti-alert" style="color: #d97706; font-size: 11px;"></i>
                                </span>
                                <span style="font-size: 12.5px; color: #92400e; font-weight: 500;">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Status Kamar -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-info-alt"></i></span>
                            Status Kamar <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap" style="gap: 10px;" id="statusGrid">
                            @foreach(['tersedia', 'terisi', 'maintenance'] as $status)
                            <label class="status-pill" style="cursor:pointer;">
                                <input type="radio" name="status_kamar" value="{{ $status }}" onchange="selectStatus(this)" {{ old('status_kamar') == $status ? 'checked' : '' }}>
                                <span class="pill-label" style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $status }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('status_kamar')
                            <div class="d-flex align-items-center mt-2" style="gap: 7px; background: linear-gradient(135deg, #fffbeb, #fef3c7); border-left: 3px solid #f59e0b; border-radius: 8px; padding: 8px 12px; animation: slideDown 0.25s ease;">
                                <span style="background: #fde68a; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="ti-alert" style="color: #d97706; font-size: 11px;"></i>
                                </span>
                                <span style="font-size: 12.5px; color: #92400e; font-weight: 500;">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                            Harga/Bulan <span class="text-danger">*</span>
                        </label>
                        <div style="position:relative;">
                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#00a669; font-size:14px; pointer-events:none;">Rp</span>
                            <input type="number" name="harga" required min="0"
                                style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s; -moz-appearance: textfield;"
                                placeholder="0"
                                value="{{ old('harga') }}"
                                onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-pencil-alt"></i></span>
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="2"
                            style="width:100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; resize: none; transition: 0.2s;"
                            placeholder="Deskripsi kamar..."
                            onfocus="this.style.borderColor='#00a669'; this.style.boxShadow='0 0 0 3px rgba(0,166,105,0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- Fasilitas -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-list"></i></span>
                            Fasilitas
                        </label>
                        <div class="dropdown">
                            <button class="w-100 text-start d-flex justify-content-between align-items-center" type="button" id="fasilitasDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-flip="false" data-bs-boundary="viewport"
                                style="padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; background: white; outline: none; transition: 0.2s;">
                                <span id="fasilitasSelectedCount">Pilih Fasilitas</span>
                                <i class="ti-angle-down" style="font-size: 12px; color: #9ca3af;"></i>
                            </button>
                            <div class="dropdown-menu shadow-sm" aria-labelledby="fasilitasDropdown" style="width: 100%; padding: 15px; max-height: 250px; overflow-y: auto; border-radius: 12px; border: 1.5px solid #f0f1f3; margin-top: 5px;">
                                @php
                                    $availableFasilitas = ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Kasur', 'Lemari', 'Meja', 'Kursi', 'TV', 'Air Panas', 'Dapur'];
                                @endphp
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                                    @foreach($availableFasilitas as $f)
                                    <div class="form-check" style="margin: 0; padding-left: 1.75em;">
                                        <input class="form-check-input fasilitas-checkbox" type="checkbox" name="fasilitas[]" value="{{ $f }}" id="f{{ $loop->index }}"
                                            {{ is_array(old('fasilitas')) && in_array($f, old('fasilitas')) ? 'checked' : '' }}
                                            onchange="updateFasilitasCount()"
                                            style="cursor: pointer; width: 16px; height: 16px; margin-top: 0.15em;">
                                        <label class="form-check-label" for="f{{ $loop->index }}" style="font-size: 13px; color: #4b5563; cursor:pointer; user-select: none; margin-left: 5px;">
                                            {{ $f }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function updateFasilitasCount() {
                            const checkboxes = document.querySelectorAll('.fasilitas-checkbox:checked');
                            const countSpan = document.getElementById('fasilitasSelectedCount');
                            if (checkboxes.length === 0) {
                                countSpan.textContent = 'Pilih Fasilitas';
                                countSpan.style.color = '#9ca3af';
                            } else {
                                countSpan.textContent = checkboxes.length + ' Fasilitas dipilih';
                                countSpan.style.color = '#111827';
                            }
                        }
                        // Initialize on load
                        document.addEventListener('DOMContentLoaded', updateFasilitasCount);
                    </script>

                    <!-- Multiple Image Upload -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #ecfdf5; color: #00a669; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-image"></i></span>
                            Foto Kamar
                        </label>
                        <div class="image-input-wrapper">
                            <input type="file" name="images[]" id="imageInput" accept="image/*" multiple style="display:none;">
                            <label for="imageInput" id="imageUploadLabel" class="image-upload-label" style="display:block; border: 2px dashed #e5e7eb; border-radius: 10px; padding: 20px; text-align:center; cursor:pointer; transition: 0.2s;"
                                onmouseover="this.style.borderColor='#00a669'; this.style.backgroundColor='#f9fafb';"
                                onmouseout="this.style.borderColor='#e5e7eb'; this.style.backgroundColor='white';">
                                <i class="ti-image" style="font-size: 32px; color: #9ca3af; display:block; margin-bottom:8px;"></i>
                                <p style="margin:0; color: #6b7280; font-size: 13px;">Klik atau drag gambar ke sini</p>
                                <p style="margin:4px 0 0 0; color: #9ca3af; font-size: 12px;">Bisa pilih banyak (Hold Ctrl/Cmd) | PNG, JPG, GIF (max 2MB each)</p>
                            </label>
                            <div id="imagePreview" style="display: none; flex-wrap: wrap; gap: 12px; margin-top: 15px;"></div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end" style="gap: 12px;">
                        <button type="button" data-bs-dismiss="modal"
                            style="padding: 9px 22px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: white; font-weight: 600; font-size: 13.5px; color: #6b7280; cursor:pointer; transition: all 0.2s;"
                            onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">
                            Batal
                        </button>
                        <button type="submit" id="submitTambahBtn"
                            style="padding: 9px 26px; border-radius: 8px; border: none; background: linear-gradient(135deg, #00a669, #008a57); color: white; font-weight: 600; font-size: 13.5px; cursor:pointer; transition: opacity 0.2s;"
                            onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah ada error validasi dari Laravel
        @if ($errors->has('tipe_kamar') || $errors->has('status_kamar') || $errors->has('nomor_kamar'))

            // 1. Munculkan kembali modal Tambah Kamar secara otomatis
            var modalEl = document.getElementById('tambahKamarModal');
            if (modalEl) {
                var myModal = new bootstrap.Modal(modalEl);
                myModal.show();
            }

            // 2. Warnai ulang pill yang sebelumnya sudah dipilih (biar tidak jadi abu-abu lagi)
            document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
                if (radio.name === 'tipe_kamar') window.selectTipe(radio);
                if (radio.name === 'status_kamar') window.selectStatus(radio);
            });

        @endif
    });
</script>

@endpush
