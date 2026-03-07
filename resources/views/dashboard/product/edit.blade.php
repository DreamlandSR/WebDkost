@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel flex-grow-1">
                <div class="content-wrapper">

                    <h3 class="fw-bold mb-4 text-2xl md:text-3xl text-gray-800">Edit Produk</h3>

                    {{-- Notifikasi --}}
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-md" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-md" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-md" role="alert">
                            <h6 class="alert-heading fw-semibold"><i class="fas fa-ban me-2"></i>Terjadi Kesalahan:</h6>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data"
                        class="bg-white p-4 md:p-6 shadow-lg rounded-lg">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Kolom Kiri: Gambar Produk --}}
                            <div class="col-lg-5 col-md-12">

                                <div class="card-header bg-gradient-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Gambar Produk</h5>
                                </div>
                                <div class="card-body">
                                    {{-- Gambar Saat Ini --}}
                                    <div class="mb-4">
                                        <label class="form-label">Gambar Saat Ini</label>
                                        <div class="row g-2" id="current-images">
                                            @if ($product->images && count($product->images) > 0)
                                                @foreach ($product->images as $image)
                                                    <div class="col-md-4 col-sm-6 col-6 mb-2"
                                                        id="image-{{ $image->id }}">
                                                        <div class="position-relative group">
                                                            <img src="{{ $image->base64src ?? 'https://placehold.co/150x150/e2e8f0/94a3b8?text=Gambar' }}"
                                                                alt="Product Image"
                                                                class="img-thumbnail w-full h-auto object-cover aspect-square"
                                                                onerror="this.onerror=null; this.src='https://placehold.co/150x150/e2e8f0/94a3b8?text=Error';">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-image opacity-0 group-hover:opacity-100 transition-opacity"
                                                                data-image-id="{{ $image->id }}" style="z-index: 10;">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-12">
                                                    <p class="text-muted text-center p-3 bg-light rounded-md">Belum ada
                                                        gambar.</p>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="hidden" name="delete_images" id="delete_images" value="">
                                    </div>

                                    <hr class="my-4">

                                    {{-- Upload Gambar Baru --}}
                                    <div>
                                        <label for="new-image-input" class="form-label">Upload Gambar Baru</label>
                                        <input type="file"
                                            class="form-control @error('image_product.*') is-invalid @enderror"
                                            name="image_product[]" id="new-image-input" accept="image/*" multiple>
                                        <div class="form-text mt-1">Pilih satu atau lebih gambar (JPG, JPEG, PNG). Maks
                                            2MB/file.</div>
                                        @error('image_product.*')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                        {{-- Preview Container untuk gambar baru --}}
                                        <div id="new-preview-container" class="row g-2 mt-3"></div>
                                    </div>
                                </div>

                            </div>

                            {{-- Kolom Kanan: Detail Produk --}}
                            <div class="col-lg-7 col-md-12">

                                <div class="card-header bg-gradient-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Detail Produk</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Produk</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            id="nama" name="nama" value="{{ old('nama', $product->nama) }}"
                                            required>
                                        @error('nama')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                                            required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="harga" class="form-label">Harga</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                    class="form-control @error('harga') is-invalid @enderror"
                                                    id="harga" name="harga"
                                                    value="{{ old('harga', $product->harga) }}" step="1"
                                                    min="0" required> {{-- Mengubah step menjadi 1 untuk Rupiah --}}
                                            </div>
                                            @error('harga')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="quantity" class="form-label">Stok/Quantity</label>
                                            <input type="number"
                                                class="form-control @error('quantity') is-invalid @enderror"
                                                id="quantity" name="quantity"
                                                value="{{ old('quantity', $product->stock ? $product->stock->quantity : 0) }}"
                                                min="0" required>
                                            @error('quantity')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="custom-select w-25 w-md-auto @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="available"
                                                {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>
                                                Tersedia</option>
                                            <option value="out_of_stock"
                                                {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>
                                                Stok Habis</option>
                                            <option value="hidden"
                                                {{ old('status', $product->status) == 'hidden' ? 'selected' : '' }}>
                                                Disembunyikan</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Informasi Tambahan & Info Upload --}}
                        {{-- <div class="row g-4 mt-2">
                         <div class="col-lg-6 col-md-12">
                            <div class="card bg-light shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="card-title fw-semibold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Produk</h6>
                                    <small class="text-muted">
                                        <strong>ID Produk:</strong> {{ $product->id }}<br>
                                        <strong>Nama:</strong> {{ $product->nama }}<br>
                                        <strong>Status:</strong> <span class="badge bg-{{ $product->status == 'available' ? 'success' : ($product->status == 'hidden' ? 'secondary' : ($product->status == 'out_of_stock' || $product->status == 'unavailable' ? 'danger' : 'info')) }}">{{ ucfirst(str_replace('_', ' ', $product->status)) }}</span><br>
                                        <strong>Total Gambar Saat Ini:</strong> <span id="current-image-count">{{ $product->images ? $product->images->count() : 0 }}</span> gambar
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="card bg-light shadow-sm h-100" id="upload-info" style="display: none;">
                                <div class="card-body">
                                    <h6 class="card-title fw-semibold mb-3"><i class="fas fa-cloud-upload-alt me-2 text-primary"></i>Info Upload Baru</h6>
                                    <small class="text-muted">
                                        <strong>Gambar Baru Akan Diupload:</strong> <span id="total-new-images">0</span> gambar<br>
                                        <strong>Status:</strong> <span class="text-success fw-semibold">Siap untuk disimpan</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                        {{-- Tombol Aksi --}}
                        <div class="mt-4 pt-4 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded shadow-sm text-center mr-3">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary rounded shadow-sm">
                                <i class="fas fa-save me-2"></i>Update Produk
                            </button>
                        </div>
                    </form>

                </div>
                {{-- content-wrapper ends --}}
            </div>
            {{-- main-panel ends --}}
        </div>
        {{-- page-body-wrapper ends --}}
    </div>
    {{-- container-scroller ends --}}

    {{-- Custom Confirm Modal HTML --}}
    <div class="modal" id="customConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customConfirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" onclick="hideCustomConfirm()" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="customConfirmMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="resolveCustomConfirm(false)">Batal</button>
                    <button type="button" class="btn btn-primary" id="customConfirmOkButton"
                        onclick="resolveCustomConfirm(true)">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete existing images
            const deleteButtons = document.querySelectorAll('.delete-image');
            const deleteImagesInput = document.getElementById('delete_images');
            let deletedImageIds = [];

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const imageId = this.getAttribute('data-image-id');
                    const imageContainer = document.getElementById('image-' + imageId);

                    // Konfirmasi hapus
                    if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                        // Sembunyikan gambar
                        imageContainer.style.display = 'none';

                        // Tambahkan ID ke array yang akan dihapus
                        deletedImageIds.push(imageId);
                        deleteImagesInput.value = deletedImageIds.join(',');

                        // Update info total gambar
                        updateImageCount();
                    }
                });
            });

            // Handle preview new images
            const newImageInput = document.getElementById('new-image-input');
            const newPreviewContainer = document.getElementById('new-preview-container');
            const uploadInfo = document.getElementById('upload-info');
            const totalNewImagesSpan = document.getElementById('total-new-images');
            let selectedFiles = [];

            newImageInput.addEventListener('change', function(e) {
                newPreviewContainer.innerHTML = ''; // Bersihkan container sebelumnya
                selectedFiles = Array.from(e.target.files);

                if (selectedFiles.length > 0) {
                    uploadInfo.style.display = 'block';
                    totalNewImagesSpan.textContent = selectedFiles.length;
                } else {
                    uploadInfo.style.display = 'none';
                }

                selectedFiles.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const div = document.createElement('div');
                            div.className = 'col-md-4 mb-3';
                            div.innerHTML = `
                                <div class="position-relative">
                                    <img src="${event.target.result}"
                                         class="img-thumbnail"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 remove-new-preview"
                                            data-file-index="${index}" style="z-index: 10;">
                                        <i class="fa fa-times"></i> ×
                                    </button>
                                    <div class="position-absolute top-0 start-0 m-1">
                                        <span class="badge bg-success">Baru</span>
                                    </div>
                                    <small class="d-block text-center mt-1 text-truncate" style="max-width: 150px;">${file.name}</small>
                                    <small class="d-block text-center text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                                </div>
                            `;
                            newPreviewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Handle remove new preview
                setTimeout(() => {
                    document.querySelectorAll('.remove-new-preview').forEach(button => {
                        button.addEventListener('click', function() {
                            const fileIndex = parseInt(this.getAttribute(
                                'data-file-index'));

                            if (confirm('Hapus preview gambar ini?')) {
                                // Remove from visual display
                                this.closest('.col-md-4').remove();

                                // Remove from selectedFiles array
                                selectedFiles.splice(fileIndex, 1);

                                // Update file input
                                updateFileInput();

                                // Update info
                                if (selectedFiles.length > 0) {
                                    totalNewImagesSpan.textContent = selectedFiles
                                        .length;
                                } else {
                                    uploadInfo.style.display = 'none';
                                }
                            }
                        });
                    });
                }, 100);
            });

            // Function to update file input with remaining files
            function updateFileInput() {
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                newImageInput.files = dt.files;
            }

            // Auto-update status based on quantity
            const quantityInput = document.getElementById('quantity');
            const statusSelect = document.getElementById('status');

            quantityInput.addEventListener('input', function() {
                const quantityValue = parseInt(this.value) || 0;

                if (quantityValue === 0) {
                    if (statusSelect.value === 'available') {
                        if (confirm('Quantity bernilai 0, ubah status menjadi "Stok Habis"?')) {
                            statusSelect.value = 'out_of_stock';
                        }
                    }
                } else if (statusSelect.value === 'out_of_stock' || statusSelect.value === 'unavailable') {
                    if (confirm('Quantity tersedia, ubah status menjadi "Tersedia"?')) {
                        statusSelect.value = 'available';
                    }
                }
            });

            // Function to update image count info
            function updateImageCount() {
                const visibleImages = document.querySelectorAll(
                    '#current-images .col-md-4:not([style*="display: none"])').length;
                document.getElementById('current-image-count').textContent = visibleImages;
            }

            // Form validation before submit
            document.querySelector('form').addEventListener('submit', function(e) {
                const quantity = parseInt(quantityInput.value) || 0;
                const status = statusSelect.value;

                // Warning if status doesn't match quantity
                if (quantity === 0 && status === 'available') {
                    if (!confirm('Warning: Status "Tersedia" dengan quantity 0. Lanjutkan?')) {
                        e.preventDefault();
                        return false;
                    }
                }

                if (quantity > 0 && (status === 'out_of_stock' || status === 'unavailable')) {
                    if (!confirm(
                            'Warning: Ada quantity tersedia tapi status menunjukkan tidak tersedia. Lanjutkan?'
                            )) {
                        e.preventDefault();
                        return false;
                    }
                }
            });
        });
    </script>
@endsection
