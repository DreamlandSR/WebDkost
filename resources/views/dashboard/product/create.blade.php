@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="card shadow-sm rounded p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                                @if (session('errors') && session('errors')->has('exception'))
                                    <div class="text-muted small mt-2">
                                        {{ session('errors')->first('exception') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row gx-4 gy-3">
                                <div class="col-md-4 position-relative">
                                    <input type="file" name="image_product[]" id="image-input" multiple hidden>

                                    <!-- Wadah upload dan preview -->
                                    <div id="image-upload-wrapper">
                                        <!-- Kotak Upload -->
                                        <label id="upload-box" for="image-input"
                                            class="upload-box d-flex align-items-center justify-content-center">
                                            +
                                        </label>

                                        <!-- Preview Gambar -->
                                        <div id="image-preview" class="d-none position-relative">
                                            <img id="main-preview" class="img-fluid rounded"
                                                style="width: 100%; height: 100%; object-fit: cover;" />
                                            <!-- Tombol X -->
                                            <button type="button" id="remove-image" class="btn btn-danger px-2 py-0"
                                                style="position: absolute; top: 6px; right: 6px;">×</button>

                                            <div id="image-count"
                                                class="position-absolute end-0 bottom-0 me-2 mb-2 px-2 py-1 bg-primary text-white rounded small d-none">
                                                +2</div>
                                        </div>
                                    </div>
                                </div>



                                <style>
                                    .upload-box {
                                        width: 100%;
                                        height: 200px;
                                        border: 2px dashed #ccc;
                                        border-radius: 8px;
                                        font-size: 2.5rem;
                                        color: #999;
                                        cursor: pointer;
                                    }


                                    #image-preview img {
                                        border-radius: 8px;
                                        max-height: 200px;
                                        object-fit: cover;
                                    }

                                    #image-upload-wrapper {
                                        position: relative;
                                        width: 100%;
                                        height: 200px;
                                    }

                                    .upload-box,
                                    #image-preview {
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        height: 100%;
                                    }
                                </style>


                                <!-- Form Inputs -->
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nama" class="form-label fw-bold">Nama Produk</label>
                                            <input type="text" class="form-control" id="nama" name="nama"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="harga" class="form-label fw-bold">Harga</label>
                                            <input type="number" class="form-control" id="harga" name="harga"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="quantity">Jumlah Stok</label>
                                            <input type="number" id="quantity" name="quantity"
                                                class="form-control @error('quantity') is-invalid @enderror"
                                                value="{{ old('quantity') }}" required min="0">
                                            @error('quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="berat" class="form-label fw-bold">Berat (Gram)</label>
                                            <input type="number" class="form-control" id="berat" name="berat"
                                                required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required></textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label fw-bold">Status</label>
                                            <select class="custom-select w-50 w-md-auto" id="status" name="status">
                                                <option value="available">Tersedia</option>
                                                <option value="unavailable">Habis</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary rounded shadow-sm mr-3"
                                    style="padding: 12px 0; width:150px">Simpan</button>
                                <a href="{{ route('products.index') }}"
                                    class="btn btn-outline-secondary rounded shadow-sm text-center"
                                    style="padding: 12px 0; width:150px">Batal</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Script untuk preview gambar dan hitung jumlah -->
    <script>
        const input = document.getElementById('image-input');
        const uploadBox = document.getElementById('upload-box');
        const previewContainer = document.getElementById('image-preview');
        const mainPreview = document.getElementById('main-preview');
        const removeButton = document.getElementById('remove-image');
        const countBadge = document.getElementById('image-count');

        let fileList = [];

        input.addEventListener('change', function(e) {
            fileList = Array.from(e.target.files);

            if (fileList.length > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    mainPreview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                    uploadBox.classList.add('d-none'); // HILANGKAN kotak upload
                };
                reader.readAsDataURL(fileList[0]);

                // Tampilkan badge jika gambar lebih dari 1
                if (fileList.length > 1) {
                    countBadge.innerText = `+${fileList.length - 1}`;
                    countBadge.classList.remove('d-none');
                } else {
                    countBadge.classList.add('d-none');
                }
            } else {
                resetPreview();
            }
        });

        removeButton.addEventListener('click', function() {
            resetPreview();
        });

        function resetPreview() {
            previewContainer.classList.add('d-none');
            uploadBox.classList.remove('d-none');
            input.value = ""; // reset input file
            fileList = [];
            mainPreview.src = '';
            countBadge.classList.add('d-none');
        }
    </script>
@endsection
