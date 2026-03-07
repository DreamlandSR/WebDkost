@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper py-4">
                    <div class="container">
                        <div class="card shadow-sm rounded-lg p-4">
                            <div class="row align-items-center mb-4">
                                <!-- Profile Photo dan Form Upload -->
                                <div class="col-md-2 text-center">
                                    <form action="{{ route('profile.photo.update') }}" method="POST"
                                        enctype="multipart/form-data" id="photoForm">
                                        @csrf
                                        @method('PATCH')

                                        <!-- Preview Foto -->
                                        <img src="{{ Auth::user()->avatar ? asset('storage/avatars/' . Auth::user()->avatar) : asset('img/Batik 2.jpg') }}"
                                            class="rounded-circle img-fluid mb-4"
                                            style="width: 100px; height: 100px; object-fit: cover;" alt="Profile Photo"
                                            id="preview">

                                        <!-- Input file tersembunyi -->
                                        <input type="file" name="avatar" id="photoInput" class="d-none" accept="image/*"
                                            onchange="document.getElementById('photoForm').submit();">

                                        <!-- Tombol -->
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('photoInput').click();">
                                            Ganti Foto
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Alert sukses -->
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <!-- Form Profil -->
                            <hr class="my-2">
                            <h5 class="fw-bold my-4">Data Diri</h5>

                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="nama" class="form-label text-primary">Nama</label>
                                    <input type="text" class="form-control" name="nama" id="nama"
                                        value="{{ old('nama', Auth::user()->nama) }}">
                                    @error('nama')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label text-primary">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        value="{{ old('email', Auth::user()->email) }}">
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="no_hp" class="form-label text-primary">Nomor Handphone</label>
                                    <input type="text" class="form-control" name="no_hp" id="no_hp"
                                        value="{{ old('no_hp', Auth::user()->no_hp ?? '') }}">
                                    @error('no_hp')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary rounded shadow-sm mr-3">Simpan</button>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary rounded shadow-sm text-center">Cancel</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
