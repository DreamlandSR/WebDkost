@extends('layout')

@section('content')
    <div class="bootstrap-scope">
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper d-flex align-items-center auth px-0">
                    <div class="row w-100 mx-0">
                        <div class="col-lg-4 col-md-6 col-sm-8 mx-auto">
                            <div class="auth-form-light p-4" style="border-radius: 10px;">

                                <div class="brand-logo mb-4 d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('img/dkos_logo.png') }}" alt="logo" class="img-fluid me-2"
                                        style="max-width: 50px;">
                                </div>

                                {{-- Title and back button --}}
                                <div class="mb-4 d-flex align-items-center">
                                    <a href="{{ url()->previous() }}" class="me-2 text-dark" style="font-size: 24px; line-height: 1;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-arrow-left">
                                            <line x1="19" y1="12" x2="5" y2="12" />
                                            <polyline points="12 19 5 12 12 5" />
                                        </svg>
                                    </a>
                                    <h4 class="mb-0 fw-bold">Daftar Akun</h4>
                                </div>

                                <p class="text-muted mb-4">Daftarkan akun anda sekarang!</p>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success mb-4">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <!-- Name -->
                                    <div class="mb-3">
                                        <label for="nama" class="form-label text-muted mb-1">Nama Lengkap</label>
                                        <input id="nama" class="form-control form-control-sm rounded shadow-sm"
                                            placeholder="Masukkan nama lengkap" type="text" name="nama" 
                                            value="{{ old('nama') }}" required autofocus>
                                        @error('nama')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label text-muted mb-1">Email Address</label>
                                        <input id="email" class="form-control form-control-sm rounded shadow-sm"
                                            placeholder="Masukkan email" type="email" name="email" 
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label text-muted mb-1">Nomor Telepon</label>
                                        <input id="no_hp" class="form-control form-control-sm rounded shadow-sm"
                                            placeholder="Masukkan nomor telepon" type="number" name="no_hp"
                                            value="{{ old('no_hp') }}" required>
                                        @error('no_hp')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label text-muted mb-1">Password</label>
                                        <input id="password" class="form-control form-control-sm rounded shadow-sm"
                                            placeholder="Masukkan password" type="password" name="password" 
                                            required autocomplete="new-password">
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label text-muted mb-1">Konfirmasi Password</label>
                                        <input id="password_confirmation"
                                            class="form-control form-control-sm rounded shadow-sm"
                                            placeholder="Masukkan ulang password" type="password" 
                                            name="password_confirmation" required>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded shadow-sm"
                                            style="padding: 12px 0;">
                                            Daftar
                                        </button>
                                    </div>
                                </form>
                                
                                <div class="text-center mt-3">
                                    <p class="mb-0 text-muted small">
                                        Sudah punya akun? 
                                        <a href="{{ route('login') }}" class="text-success text-decoration-none">
                                            Login disini
                                        </a>
                                    </p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection