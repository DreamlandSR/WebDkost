@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper" style="background: #f9fbfd; padding: 30px 20px;">
                    {{-- Main Content --}}
                    <div style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 200px);">
                        <div style="width: 100%; max-width: 900px;">
                            {{-- Form Container --}}
                            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
                                
                                {{-- Form Header --}}
                                <div style="background: linear-gradient(135deg, #40b883 0%, #2a9b6c 100%); padding: 24px; text-align: center;">
                                    <h3 class="text-white fw-bold mb-1" style="font-size: 24px;">Upload Kamar Baru</h3>
                                    <p class="text-white mb-0" style="font-size: 14px; opacity: 0.9;">Lengkapi informasi kamar di bawah untuk menambahkan kamar baru ke sistem</p>
                                </div>

                                {{-- Form Content --}}
                                <div style="padding: 40px 50px;">
                                    <form id="formTambahKamar" method="POST" action="{{ route('kamar.store') }}">
                                        @csrf
                                        
                                        {{-- Image Upload Box --}}
                                        <div class="form-group mb-5" style="text-align: center;">
                                            <div style="border: 2px dashed #ccc; border-radius: 10px; padding: 40px 20px; background: #fafafa; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.borderColor='#40b883'; this.style.background='#f0f8f5';" onmouseout="this.style.borderColor='#ccc'; this.style.background='#fafafa';">
                                                <input type="file" id="imageInput" name="image" style="display: none;" accept="image/*">
                                                <svg width="48" height="48" fill="none" stroke="#ccc" stroke-width="1.5" viewBox="0 0 24 24" style="display: inline-block; margin-bottom: 12px;">
                                                    <path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2z"></path>
                                                    <circle cx="8.5" cy="9.5" r="1.5"></circle>
                                                    <path d="M21 15l-5-5L5 21"></path>
                                                </svg>
                                                <p style="color: #999; font-size: 14px; margin-bottom: 4px;">
                                                    <span onclick="document.getElementById('imageInput').click();" style="cursor: pointer; color: #40b883; font-weight: 600;">Klik untuk upload</span> atau drag & drop
                                                </p>
                                                <small style="color: #aaa; font-size: 12px;">PNG, JPG, GIF up to 10MB</small>
                                            </div>
                                            <small style="color: #999; display: block; margin-top: 8px;">Thumbnail kamar (opsional)</small>
                                        </div>

                                        {{-- Form Fields Row 1 --}}
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                                            {{-- Nomor Kamar --}}
                                            <div class="form-group">
                                                <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 14px;">
                                                    Nomor Kamar <span style="color: #40b883;">*</span>
                                                </label>
                                                <input type="text" class="form-control" name="nomor_kamar" placeholder="Contoh: A101" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px 14px; font-size: 14px; transition: all 0.3s ease;" required onmouseover="this.style.borderColor='#40b883';" onmouseout="this.style.borderColor='#ddd';">
                                            </div>

                                            {{-- Tipe Kamar --}}
                                            <div class="form-group">
                                                <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 14px;">
                                                    Tipe Kamar <span style="color: #40b883;">*</span>
                                                </label>
                                                <select class="form-control" name="tipe_kamar" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px 14px; font-size: 14px; transition: all 0.3s ease;" required onmouseover="this.style.borderColor='#40b883';" onmouseout="this.style.borderColor='#ddd';">
                                                    <option value="">-- Pilih Tipe --</option>
                                                    <option value="Standard">Standard</option>
                                                    <option value="Premium">Premium</option>
                                                    <option value="Deluxe">Deluxe</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Form Fields Row 2 --}}
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                                            {{-- Harga --}}
                                            <div class="form-group">
                                                <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 14px;">
                                                    Harga per Bulan <span style="color: #40b883;">*</span>
                                                </label>
                                                <div style="display: flex; align-items: center; background: #f9fbfd; border: 1px solid #ddd; border-radius: 8px; padding: 0; transition: all 0.3s ease;" onmouseover="this.style.borderColor='#40b883';" onmouseout="this.style.borderColor='#ddd';">
                                                    <span style="padding: 0 14px; color: #999; font-weight: 600; font-size: 14px;">Rp</span>
                                                    <input type="number" class="form-control" name="harga" placeholder="1500000" style="border: none; background: transparent; padding: 12px 0; font-size: 14px;" required>
                                                </div>
                                            </div>

                                            {{-- Status --}}
                                            <div class="form-group">
                                                <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 14px;">
                                                    Status <span style="color: #40b883;">*</span>
                                                </label>
                                                <select class="form-control" name="status" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px 14px; font-size: 14px; transition: all 0.3s ease;" required onmouseover="this.style.borderColor='#40b883';" onmouseout="this.style.borderColor='#ddd';">
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Tersedia">Tersedia</option>
                                                    <option value="Terisi">Terisi</option>
                                                    <option value="Maintenance">Maintenance</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Deskripsi --}}
                                        <div class="form-group mb-5">
                                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 14px;">
                                                Deskripsi / Keterangan <span style="color: #40b883;">*</span>
                                            </label>
                                            <textarea class="form-control" name="deskripsi" rows="4" placeholder="Deskripsi kamar atau keterangan tambahan" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px 14px; font-size: 14px; transition: all 0.3s ease; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; resize: none;" required onmouseover="this.style.borderColor='#40b883';" onmouseout="this.style.borderColor='#ddd';"></textarea>
                                        </div>

                                        {{-- Fasilitas --}}
                                        <div class="form-group mb-5">
                                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block; font-size: 14px;">
                                                Fasilitas <span style="color: #40b883;">*</span>
                                            </label>
                                            <textarea class="form-control" name="fasilitas" rows="3" placeholder="WiFi, AC, Kasur, Lemari, TV, Kamar Mandi Pribadi (pisahkan dengan koma)" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px 14px; font-size: 14px; transition: all 0.3s ease; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; resize: none;" required onmouseover="this.style.borderColor='#40b883';" onmouseout="this.style.borderColor='#ddd';"></textarea>
                                        </div>

                                        {{-- Form Actions --}}
                                        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #f0f0f0;">
                                            <a href="{{ route('kamar.index') }}" class="btn btn-sm" style="background: white; color: #333; border: 1px solid #ddd; border-radius: 8px; padding: 12px 28px; font-weight: 600; cursor: pointer; text-decoration: none; transition: all 0.2s ease; font-size: 14px;">
                                                Batal
                                            </a>
                                            <button type="button" style="background: linear-gradient(135deg, #40b883 0%, #2a9b6c 100%); color: white; border: none; border-radius: 8px; padding: 12px 32px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(64, 184, 131, 0.3); font-size: 14px;" onclick="confirmSubmit()">
                                                Simpan Kamar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('layouts.sections.navbar')
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Upload --}}
    <div id="confirmUploadModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
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
                    <h5 style="font-weight: 700; color: #333; margin-bottom: 8px;">Upload Kamar Baru?</h5>
                    <p style="color: #666; font-size: 14px; margin: 0;">Apakah Anda yakin ingin menambahkan kamar baru dengan informasi yang telah diisi?</p>
                </div>
                <div class="modal-footer" style="background: #f9fbfd; border-top: 1px solid #f0f0f0; padding: 16px; gap: 8px;">
                    <button type="button" class="btn btn-sm" style="background: white; color: #333; border: 1px solid #ddd; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 13px;" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-sm" style="background: #40b883; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 13px;" onclick="submitForm()">Upload</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image upload handler
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    console.log('Image selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });

        function confirmSubmit() {
            // Validasi form sebelum menampilkan modal
            const form = document.getElementById('formTambahKamar');
            const nomorKamar = form.nomor_kamar.value.trim();
            const tipeKamar = form.tipe_kamar.value;
            const harga = form.harga.value;
            const status = form.status.value;
            const deskripsi = form.deskripsi.value.trim();
            const fasilitas = form.fasilitas.value.trim();

            if (!nomorKamar || !tipeKamar || !harga || !status || !deskripsi || !fasilitas) {
                alert('Mohon lengkapi semua field yang wajib diisi!');
                return;
            }

            $('#confirmUploadModal').modal('show');
        }

        function submitForm() {
            document.getElementById('formTambahKamar').submit();
            $('#confirmUploadModal').modal('hide');
        }
    </script>
    @endpush

@endsection
