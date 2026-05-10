<!-- hapus data -->
 <div class="modal fade" id="hapusModal{{ $kamar->id_kamar }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body text-center" style="padding: 40px 30px;">
                <div style="background: #fef2f2; border-radius: 50%; width: 70px; height: 70px; display:flex; align-items:center; justify-content:center; margin: 0 auto 24px;">
                    <i class="ti-trash" style="color: #ef4444; font-size: 32px;"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color: #111827; font-size: 18px;">Konfirmasi Hapus</h5>
                <p class="mb-4" style="color: #6b7280; font-size: 14px; line-height: 1.5;">Apakah Anda yakin ingin menghapus kamar <strong>{{ $kamar->nomor_kamar }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>

                <form action="{{ route('kamar.destroy', $kamar->id_kamar) }}" method="POST" id="formHapus{{ $kamar->id_kamar }}">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex flex-column gap-3">
                        <button type="submit" class="btn text-white py-2 fw-bold" style="background: #ef4444; border-radius: 10px; font-size: 14px; border: none;">Ya, Hapus Sekarang</button>
                        <button type="button" class="btn py-2 fw-600" data-bs-dismiss="modal" style="background: #f3f4f6; color: #4b5563; border-radius: 10px; font-size: 14px; border: none; margin-top: 12px;">Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Detail data -->
<div class="modal fade" id="detailModal{{ $kamar->id_kamar }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 680px;">
        <div class="modal-content border-0" style="border-radius: 18px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.18);">

            @php
                $galeri     = $kamar->galeri;
                $hasImages  = $galeri && $galeri->count() > 0;
                $mainImage  = $hasImages ? ($galeri->firstWhere('is_main', true) ?? $galeri->first()) : null;
                $statusColor = match($kamar->status_kamar) {
                    'tersedia'    => ['bg' => '#ecfdf5', 'text' => '#059669', 'dot' => '#10b981'],
                    'terisi'      => ['bg' => '#fef2f2', 'text' => '#dc2626', 'dot' => '#ef4444'],
                    'maintenance' => ['bg' => '#fffbeb', 'text' => '#d97706', 'dot' => '#f59e0b'],
                    default       => ['bg' => '#f3f4f6', 'text' => '#6b7280', 'dot' => '#9ca3af'],
                };
                $tipeColor = match($kamar->tipe_kamar) {
                    'mewah'  => ['bg' => '#fdf4ff', 'text' => '#9333ea'],
                    'sedang' => ['bg' => '#eff6ff', 'text' => '#3b82f6'],
                    default  => ['bg' => '#f0fdf4', 'text' => '#16a34a'],
                };
            @endphp

            {{-- ── HERO IMAGE ── --}}
            <div style="position: relative; height: {{ $hasImages ? '260px' : '0' }}; background: #1e293b; overflow: hidden;">
                @if($hasImages)
                    {{-- Carousel slides --}}
                    <div id="carouselDetail{{ $kamar->id_kamar }}" style="width:100%; height:100%; position:relative;">
                        @foreach($galeri as $idx => $gambar)
                            <div class="detail-slide-{{ $kamar->id_kamar }}"
                                 style="position:absolute; inset:0; opacity:{{ $idx == 0 ? 1 : 0 }}; transition: opacity 0.45s ease;">
                                <img src="{{ asset('storage/' . $gambar->url_foto) }}"
                                     style="width:100%; height:100%; object-fit:cover;">
                                @if($gambar->is_main)
                                <div style="position:absolute; top:14px; left:14px; background:rgba(0,0,0,0.55); backdrop-filter:blur(6px); color:#fcd34d; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; display:flex; align-items:center; gap:5px;">
                                    <i class="ti-star"></i> Gambar Utama
                                </div>
                                @endif
                            </div>
                        @endforeach

                        {{-- Counter badge --}}
                        @if($galeri->count() > 1)
                        <div style="position:absolute; top:14px; right:14px; background:rgba(0,0,0,0.5); backdrop-filter:blur(6px); color:white; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px;" id="slideCounter{{ $kamar->id_kamar }}">
                            1 / {{ $galeri->count() }}
                        </div>
                        {{-- Arrow buttons --}}
                        <button type="button" onclick="detailPrev{{ $kamar->id_kamar }}()"
                            style="position:absolute; left:14px; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.25); backdrop-filter:blur(8px); border:1.5px solid rgba(255,255,255,0.35); color:white; border-radius:50%; width:48px; height:48px; font-size:28px; line-height:1; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; box-shadow:0 2px 12px rgba(0,0,0,0.2);"
                            onmouseover="this.style.background='rgba(255,255,255,0.45)'; this.style.transform='translateY(-50%) scale(1.08)';" onmouseout="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='translateY(-50%) scale(1)';">&#8249;</button>
                        <button type="button" onclick="detailNext{{ $kamar->id_kamar }}()"
                            style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.25); backdrop-filter:blur(8px); border:1.5px solid rgba(255,255,255,0.35); color:white; border-radius:50%; width:48px; height:48px; font-size:28px; line-height:1; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; box-shadow:0 2px 12px rgba(0,0,0,0.2);"
                            onmouseover="this.style.background='rgba(255,255,255,0.45)'; this.style.transform='translateY(-50%) scale(1.08)';" onmouseout="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='translateY(-50%) scale(1)';">&#8250;</button>
                        @endif
                    </div>

                    {{-- Gradient overlay --}}
                    <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 55%); pointer-events:none;"></div>

                    {{-- Thumbnail strip --}}
                    @if($galeri->count() > 1)
                    <div style="position:absolute; bottom:12px; left:50%; transform:translateX(-50%); display:flex; gap:6px;">
                        @foreach($galeri as $idx => $gambar)
                        <img src="{{ asset('storage/' . $gambar->url_foto) }}"
                             id="thumb{{ $kamar->id_kamar }}_{{ $idx }}"
                             onclick="goToDetailSlide{{ $kamar->id_kamar }}({{ $idx }})"
                             style="width:44px; height:34px; object-fit:cover; border-radius:5px; cursor:pointer; border: 2px solid {{ $idx == 0 ? '#fff' : 'transparent' }}; opacity:{{ $idx == 0 ? 1 : 0.6 }}; transition:0.2s;">
                        @endforeach
                    </div>
                    @endif
                @endif

                {{-- Close button always on top --}}
                <button type="button" data-bs-dismiss="modal"
                    style="position:absolute; top:12px; right:12px; background:rgba(0,0,0,0.4); backdrop-filter:blur(6px); border:none; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; color:white; font-size:12px; cursor:pointer; transition:0.2s; z-index:10;"
                    onmouseover="this.style.background='rgba(0,0,0,0.7)'" onmouseout="this.style.background='rgba(0,0,0,0.4)'">
                    <i class="ti-close"></i>
                </button>
            </div>

            {{-- ── HEADER STRIP (no image fallback) ── --}}
            @if(!$hasImages)
            <div style="background:#fff; padding:20px 24px 0; display:flex; justify-content:space-between; align-items:center;">
                <div class="d-flex align-items-center" style="gap:10px;">
                    <div style="background:#ecfdf5; border-radius:10px; width:38px; height:38px; display:flex; align-items:center; justify-content:center;">
                        <i class="ti-info-alt" style="color:#00a669; font-size:17px;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold" style="color:#111827; font-size:15px;">Detail Kamar</h5>
                        <p class="mb-0" style="color:#9ca3af; font-size:12px;">Kamar {{ $kamar->nomor_kamar }}</p>
                    </div>
                </div>
                <button type="button" data-bs-dismiss="modal"
                    style="background:#f3f4f6; border:none; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; color:#6b7280; font-size:12px; cursor:pointer;">
                    <i class="ti-close"></i>
                </button>
            </div>
            @endif

            {{-- ── BODY ── --}}
            <div style="padding:22px 24px 8px; background:#fff;">

                {{-- Room number + badges --}}
                <div style="background:linear-gradient(135deg,#f0fdf4 0%,#eff6ff 100%); border:1px solid #e2f5ec; border-radius:14px; padding:16px 18px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div style="background:linear-gradient(135deg,#00a669,#059669); border-radius:12px; width:48px; height:48px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 12px rgba(0,166,105,0.3);">
                            <i class="ti-home" style="color:white; font-size:20px;"></i>
                        </div>
                        <div>
                            <p style="margin:0; font-size:10px; color:#6b9f8a; font-weight:700; text-transform:uppercase; letter-spacing:0.8px;">Nomor Kamar</p>
                            <h3 style="margin:2px 0 0; font-size:30px; font-weight:900; color:#064e3b; letter-spacing:-1px; line-height:1;">{{ $kamar->nomor_kamar }}</h3>
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-end;">
                        {{-- Status badge --}}
                        <span style="background:{{ $statusColor['bg'] }}; color:{{ $statusColor['text'] }}; font-size:12px; font-weight:700; padding:5px 12px; border-radius:20px; display:flex; align-items:center; gap:5px; border:1px solid {{ $statusColor['dot'] }}33;">
                            <span style="width:7px; height:7px; border-radius:50%; background:{{ $statusColor['dot'] }};"></span>
                            {{ ucfirst($kamar->status_kamar) }}
                        </span>
                        {{-- Tipe badge --}}
                        <span style="background:{{ $tipeColor['bg'] }}; color:{{ $tipeColor['text'] }}; font-size:12px; font-weight:700; padding:5px 12px; border-radius:20px; border:1px solid {{ $tipeColor['text'] }}22;">
                            {{ ucfirst($kamar->tipe_kamar) }}
                        </span>
                    </div>
                </div>

                {{-- Info cards row --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:14px;">
                    <div style="background:#f8faff; border:1px solid #e8efff; border-radius:12px; padding:14px 16px; display:flex; align-items:center; gap:12px;">
                        <div style="background:#eff6ff; border-radius:8px; width:36px; height:36px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i class="ti-money" style="color:#3b82f6; font-size:15px;"></i>
                        </div>
                        <div>
                            <p style="margin:0; font-size:10px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Harga/Bulan</p>
                            <p style="margin:0; font-size:14px; font-weight:800; color:#059669;">Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div style="background:#fafafa; border:1px solid #f0f0f0; border-radius:12px; padding:14px 16px; display:flex; align-items:center; gap:12px;">
                        <div style="background:#f0fdf4; border-radius:8px; width:36px; height:36px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i class="ti-image" style="color:#10b981; font-size:15px;"></i>
                        </div>
                        <div>
                            <p style="margin:0; font-size:10px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Foto</p>
                            <p style="margin:0; font-size:14px; font-weight:800; color:#111827;">{{ $hasImages ? $galeri->count() . ' foto' : 'Belum ada' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div style="background:#fafafa; border:1px solid #f0f1f3; border-left:3px solid #00a669; border-radius:10px; padding:14px 16px; margin-bottom:12px;">
                    <p style="margin:0 0 4px; font-size:10px; color:#9ca3af; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                        <i class="ti-pencil-alt" style="margin-right:4px;"></i>Deskripsi
                    </p>
                    <p style="margin:0; color:{{ $kamar->deskripsi ? '#374151' : '#9ca3af' }}; font-size:13.5px; line-height:1.65; font-style:{{ $kamar->deskripsi ? 'normal' : 'italic' }};">
                        {{ $kamar->deskripsi ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>

                {{-- Fasilitas --}}
                <div style="margin-bottom:6px;">
                    <p style="margin:0 0 8px; font-size:10px; color:#9ca3af; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                        <i class="ti-list" style="margin-right:4px;"></i>Fasilitas
                    </p>
                    <div class="dropdown">
                        <button class="btn w-100 text-start d-flex justify-content-between align-items-center" type="button" id="fasilitasDetailDropdown{{ $kamar->id_kamar }}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-flip="false" data-bs-boundary="viewport"
                            style="background:#fdfdfd; border:1px solid #f0f1f3; border-radius:10px; padding:12px 16px; font-size:13.5px; color:#4b5563;">
                            <span><i class="ti-check-box" style="color:#00a669; margin-right:8px;"></i> Lihat {{ $kamar->fasilitas->count() }} Fasilitas</span>
                            <i class="ti-angle-down" style="font-size:10px; color:#9ca3af;"></i>
                        </button>
                        <div class="dropdown-menu shadow-sm border-0" aria-labelledby="fasilitasDetailDropdown{{ $kamar->id_kamar }}" style="width: 100%; padding: 15px; border-radius:12px; margin-top:5px; box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;">
                            @if($kamar->fasilitas && $kamar->fasilitas->count() > 0)
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                                    @foreach($kamar->fasilitas as $f)
                                    <div style="display: flex; align-items: center; gap: 8px; color: #4b5563; font-size: 13px; padding: 4px 0;">
                                        <i class="ti-check" style="color: #00a669; font-size: 11px;"></i>
                                        <span>{{ $f->nama_fasilitas }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p style="margin:0; color: #9ca3af; font-size: 13px; font-style: italic; text-align:center;">Tidak ada fasilitas.</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── FOOTER ── --}}
            <div style="padding:14px 24px 20px; background:#fff;">
                <button type="button" class="btn w-100 fw-bold" data-bs-dismiss="modal"
                    style="background:linear-gradient(135deg,#374151,#1f2937); color:white; border-radius:10px; padding:11px; font-size:13.5px; border:none; letter-spacing:0.2px; transition:0.2s;"
                    onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

<script>
(function() {
    var currentSlide{{ $kamar->id_kamar }} = 0;
    var totalSlides{{ $kamar->id_kamar }} = {{ $hasImages ? $galeri->count() : 0 }};

    function showSlide{{ $kamar->id_kamar }}(idx) {
        var slides = document.querySelectorAll('.detail-slide-{{ $kamar->id_kamar }}');
        if (!slides.length) return;
        if (idx >= totalSlides{{ $kamar->id_kamar }}) idx = 0;
        if (idx < 0) idx = totalSlides{{ $kamar->id_kamar }} - 1;
        slides.forEach(function(s) { s.style.opacity = 0; });
        slides[idx].style.opacity = 1;
        currentSlide{{ $kamar->id_kamar }} = idx;

        // counter
        var counter = document.getElementById('slideCounter{{ $kamar->id_kamar }}');
        if (counter) counter.textContent = (idx + 1) + ' / ' + totalSlides{{ $kamar->id_kamar }};

        // thumbnails
        for (var i = 0; i < totalSlides{{ $kamar->id_kamar }}; i++) {
            var th = document.getElementById('thumb{{ $kamar->id_kamar }}_' + i);
            if (th) {
                th.style.borderColor  = i === idx ? '#fff' : 'transparent';
                th.style.opacity      = i === idx ? '1' : '0.55';
            }
        }
    }

    window.goToDetailSlide{{ $kamar->id_kamar }} = function(idx) { showSlide{{ $kamar->id_kamar }}(idx); };
    window.detailPrev{{ $kamar->id_kamar }}      = function() { showSlide{{ $kamar->id_kamar }}(currentSlide{{ $kamar->id_kamar }} - 1); };
    window.detailNext{{ $kamar->id_kamar }}      = function() { showSlide{{ $kamar->id_kamar }}(currentSlide{{ $kamar->id_kamar }} + 1); };
})();
</script>

<!-- edit data -->

 <div class="modal fade" id="editModal{{ $kamar->id_kamar }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">
            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <div style="background: #eff6ff; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i class="ti-pencil-alt" style="color: #3b82f6; font-size: 17px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Edit Kamar</h5>
                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Perbarui data kamar {{ $kamar->nomor_kamar }}</p>
                        </div>
                    </div>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close"
                        style="background: #f3f4f6; border: none; border-radius: 50%; width: 33px; height: 33px; display:flex; align-items:center; justify-content:center; cursor:pointer; color: #6b7280; font-size: 13px; flex-shrink:0; transition: background 0.2s;"
                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <i class="ti-close"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body" style="padding: 18px 26px 20px; background: #fff; max-height: 70vh; overflow-y: auto;">
                <form action="{{ route('kamar.update', $kamar->id_kamar) }}" method="POST" enctype="multipart/form-data" id="formEditKamar{{ $kamar->id_kamar }}">
                    @csrf
                    @method('PUT')

                    <!-- Nomor Kamar -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-home"></i></span>
                            Nomor Kamar <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nomor_kamar" value="{{ $kamar->nomor_kamar }}" required
                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                    </div>

                    <!-- Tipe Kamar -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-tag"></i></span>
                            Tipe Kamar <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap" style="gap: 10px;" id="tipeGridEdit{{ $kamar->id_kamar }}">
                            @foreach(['biasa', 'sedang', 'mewah'] as $tipe)
                            <label class="tipe-pill-edit" style="cursor:pointer; margin: 0;">
                                <input type="radio" name="tipe_kamar" value="{{ $tipe }}"
                                    {{ $kamar->tipe_kamar == $tipe ? 'checked' : '' }}
                                    style="display:none;"
                                    onchange="updateEditPill(this, 'tipe', {{ $kamar->id_kamar }})">
                                <span class="pill-label-tipe-{{ $kamar->id_kamar }} {{ $kamar->tipe_kamar == $tipe ? 'pill-active-edit' : '' }}"
                                    style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $tipe }}</span>
                            </label>
                            @endforeach
                        </div>
                        <small id="error-tipe-js-{{ $kamar->id_kamar }}" class="text-danger mt-1" style="display:none; font-size: 12px;">
                            <i class="ti-alert"></i> Harap pilih tipe kamar!
                        </small>
                    </div>

                    <!-- Status Kamar -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-info-alt"></i></span>
                            Status Kamar <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap" style="gap: 10px;">
                            @foreach(['tersedia', 'terisi', 'maintenance'] as $status)
                            <label class="status-pill-edit" style="cursor:pointer;">
                                <input type="radio" name="status_kamar" value="{{ $status }}" {{ $kamar->status_kamar == $status ? 'checked' : '' }} required style="display:none;" onchange="updateEditPill(this, 'status', {{ $kamar->id_kamar }})">
                                <span class="pill-label-status-{{ $kamar->id_kamar }} {{ $kamar->status_kamar == $status ? 'pill-active-edit' : '' }}"
                                    style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280; transition: all 0.15s; user-select:none;">{{ $status }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Harga -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-money"></i></span>
                            Harga/Bulan <span class="text-danger">*</span>
                        </label>
                        <div style="position:relative;">
                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:600; color:#3b82f6; font-size:14px; pointer-events:none;">Rp</span>
                            <input type="number" name="harga" value="{{ $kamar->harga_per_bulan }}" required min="0"
                                style="width:100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s; -moz-appearance: textfield;"
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-pencil-alt"></i></span>
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="2"
                            style="width:100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; resize: none; transition: 0.2s;"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">{{ $kamar->deskripsi }}</textarea>
                    </div>

                    <!-- Fasilitas -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-list"></i></span>
                            Fasilitas
                        </label>
                        <div class="dropdown">
                            <button class="w-100 text-start d-flex justify-content-between align-items-center" type="button" id="fasilitasDropdownEdit{{ $kamar->id_kamar }}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-flip="false" data-bs-boundary="viewport"
                                style="padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; background: white; outline: none; transition: 0.2s;">
                                <span id="fasilitasSelectedCountEdit{{ $kamar->id_kamar }}">
                                    @php
                                        $selectedFasilitas = $kamar->fasilitas ? $kamar->fasilitas->pluck('nama_fasilitas')->toArray() : [];
                                        echo count($selectedFasilitas) > 0 ? count($selectedFasilitas) . ' Fasilitas dipilih' : 'Pilih Fasilitas';
                                    @endphp
                                </span>
                                <i class="ti-angle-down" style="font-size: 12px; color: #9ca3af;"></i>
                            </button>
                            <div class="dropdown-menu shadow-sm" aria-labelledby="fasilitasDropdownEdit{{ $kamar->id_kamar }}" style="width: 100%; padding: 15px; max-height: 250px; overflow-y: auto; border-radius: 12px; border: 1.5px solid #f0f1f3; margin-top: 5px;">
                                @php
                                    $availableFasilitas = ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Kasur', 'Lemari', 'Meja', 'Kursi', 'TV', 'Air Panas', 'Dapur'];
                                @endphp
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                                    @foreach($availableFasilitas as $f)
                                    <div class="form-check" style="margin: 0; padding-left: 1.75em;">
                                        <input class="form-check-input fasilitas-checkbox-edit-{{ $kamar->id_kamar }}" type="checkbox" name="fasilitas[]" value="{{ $f }}" id="fe{{ $kamar->id_kamar }}{{ $loop->index }}"
                                            {{ in_array($f, $selectedFasilitas) ? 'checked' : '' }}
                                            onchange="updateFasilitasCountEdit({{ $kamar->id_kamar }})"
                                            style="cursor: pointer; width: 16px; height: 16px; margin-top: 0.15em;">
                                        <label class="form-check-label" for="fe{{ $kamar->id_kamar }}{{ $loop->index }}" style="font-size: 13px; color: #4b5563; cursor:pointer; user-select: none; margin-left: 5px;">
                                            {{ $f }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        if (typeof updateFasilitasCountEdit !== 'function') {
                            window.updateFasilitasCountEdit = function(id) {
                                const checkboxes = document.querySelectorAll('.fasilitas-checkbox-edit-' + id + ':checked');
                                const countSpan = document.getElementById('fasilitasSelectedCountEdit' + id);
                                if (checkboxes.length === 0) {
                                    countSpan.textContent = 'Pilih Fasilitas';
                                    countSpan.style.color = '#9ca3af';
                                } else {
                                    countSpan.textContent = checkboxes.length + ' Fasilitas dipilih';
                                    countSpan.style.color = '#111827';
                                }
                            }
                        }
                    </script>

                    <!-- Multiple Image Upload + Gallery -->
                    <div class="mb-3">
                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-image"></i></span>
                            Galeri Foto
                        </label>

                        <div class="image-input-wrapper">
                            <input type="file" name="images[]" id="imageInput{{ $kamar->id_kamar }}" accept="image/*" multiple style="display:none;">

                            @if($kamar->galeri && $kamar->galeri->count() > 0)
                                <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 10px;" id="galleryContainer{{ $kamar->id_kamar }}">
                                    @foreach($kamar->galeri as $gambar)
                                    <div class="gallery-item" id="gallery-item-{{ $gambar->id_galeri }}" style="width: 100px; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05); position: relative; flex-shrink: 0;">
                                        <div style="width: 100%; height: 80px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; position: relative;">
                                            <button type="button" onclick="deleteImage({{ $gambar->id_galeri }}, {{ $kamar->id_kamar }})" style="position: absolute; top: 4px; right: 4px; background: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; z-index: 10; transition: background 0.2s;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='rgba(239, 68, 68, 0.9)'" title="Hapus Gambar">
                                                <i class="ti-close"></i>
                                            </button>
                                            <img src="{{ asset('storage/' . $gambar->url_foto) }}" alt="Gallery" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;">
                                        </div>
                                        <div style="padding: 6px; text-align: center; background: #f9fafb; border-top: 1px solid #f0f0f0;">
                                            @if($gambar->is_main)
                                                <small style="font-size: 10px; color: #10b981; font-weight: 600; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="Gambar Utama">Gambar Utama</small>
                                            @else
                                                <a href="javascript:void(0)" class="btn-set-main" data-id="{{ $gambar->id_galeri }}" data-kamar="{{ $kamar->id_kamar }}" style="font-size: 10px; color: #3b82f6; text-decoration: none; display: block; font-weight: 500; transition: 0.2s;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#3b82f6'">Jadikan Utama</a>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach

                                    <div id="imagePreviewEdit{{ $kamar->id_kamar }}" style="display: flex; flex-wrap: wrap; gap: 12px;"></div>

                                    <label for="imageInput{{ $kamar->id_kamar }}" id="imageUploadLabelEdit{{ $kamar->id_kamar }}" style="width: 100px; height: 109px; border: 2px dashed #3b82f6; border-radius: 8px; background: #eff6ff; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: all 0.2s; color: #3b82f6; margin: 0;"
                                        onmouseover="this.style.background='#dbeafe';"
                                        onmouseout="this.style.background='#eff6ff';">
                                        <i class="ti-plus" style="font-size: 24px; margin-bottom: 4px;"></i>
                                        <span style="font-size: 11px; font-weight: 600;">Tambah</span>
                                    </label>
                                </div>
                            @else
                                <label for="imageInput{{ $kamar->id_kamar }}" id="imageUploadLabelEdit{{ $kamar->id_kamar }}" class="image-upload-label" style="display:block; border: 2px dashed #e5e7eb; border-radius: 10px; padding: 20px; text-align:center; cursor:pointer; transition: 0.2s;"
                                    onmouseover="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#f9fafb';"
                                    onmouseout="this.style.borderColor='#e5e7eb'; this.style.backgroundColor='white';">
                                    <i class="ti-image" style="font-size: 32px; color: #9ca3af; display:block; margin-bottom:8px;"></i>
                                    <p style="margin:0; color: #6b7280; font-size: 13px;">Klik atau drag gambar ke sini</p>
                                    <p style="margin:4px 0 0 0; color: #9ca3af; font-size: 12px;">Bisa pilih banyak (Hold Ctrl/Cmd) | PNG, JPG, GIF (max 2MB each)</p>
                                </label>
                                <div id="imagePreviewEdit{{ $kamar->id_kamar }}" style="display: none; flex-wrap: wrap; gap: 12px; margin-top: 15px; min-height: 100px;"></div>
                            @endif
                        </div>

                        <div id="deleteImagesContainer{{ $kamar->id_kamar }}"></div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end" style="gap: 12px;">
                        <button type="button" data-bs-dismiss="modal"
                            style="padding: 9px 22px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: white; font-weight: 600; font-size: 13.5px; color: #6b7280; cursor:pointer; transition: 0.2s;"
                            onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">
                            Batal
                        </button>
                        <button type="submit" id="submitEditBtn{{ $kamar->id_kamar }}"
                            style="padding: 9px 26px; border-radius: 8px; border: none; background: #3b82f6; color: white; font-weight: 600; font-size: 13.5px; cursor:pointer; transition: opacity 0.2s;"
                            onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
