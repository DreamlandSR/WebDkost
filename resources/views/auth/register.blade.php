@extends('layout')

@section('content')
    <div class="bootstrap-scope">
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper auth-wrapper-modern px-0">

                    <div class="row w-100 mx-0 justify-content-center">
                        <div class="col-lg-5 col-md-7 col-sm-9 mx-auto">
                            <div class="card auth-card-modern shadow-sm border-0">

                                {{-- Header: Back Icon & Stepper --}}
                                <div class="d-flex align-items-center justify-content-between mb-5 px-1 mt-1">
                                    <a href="{{ url()->previous() }}" class="auth-back-btn">
                                        <i class="ti-angle-left"></i>
                                    </a>

                                    {{-- Stepper Progress Indicator (Wider & Smaller) --}}
                                    <div class="auth-stepper-container">
                                        <div class="auth-stepper-line active"></div>
                                        <div class="auth-stepper-dot active"></div>
                                        <div class="auth-stepper-line"></div>
                                        <div class="auth-stepper-dot"></div>
                                        <div class="auth-stepper-line"></div>
                                        <div class="auth-stepper-dot"></div>
                                    </div>

                                    <div style="width: 18px;"></div>
                                </div>

                                {{-- Page Titles --}}
                                <div class="text-center mb-5">
                                    <h2 class="auth-title-large">Langkah awal Mendaftar</h2>
                                </div>

                                {{-- Alert Messages --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 py-2 px-3 transition-all" style="font-size: 14px;">
                                        <ul class="mb-0 list-unstyled text-center">
                                            @foreach ($errors->all() as $error)
                                                <li><i class="ti-info-alt mr-1"></i> {{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif



                                {{-- Registration Form --}}
                                <form method="POST" action="{{ route('register') }}" class="px-2">
                                    @csrf

                                    {{-- Split Name Row --}}
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label for="nama_depan" class="form-label auth-form-label">Nama depan</label>
                                            <input id="nama_depan" type="text" name="nama_depan"
                                                   class="form-control auth-input-modern"
                                                   placeholder="Nama depan Anda" value="{{ old('nama_depan') }}" required autofocus>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nama_belakang" class="form-label auth-form-label">Nama belakang (Opsional)</label>
                                            <input id="nama_belakang" type="text" name="nama_belakang"
                                                   class="form-control auth-input-modern"
                                                   placeholder="Nama belakang Anda" value="{{ old('nama_belakang') }}">
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="mb-4">
                                        <label for="email" class="form-label auth-form-label">Email</label>
                                        <input id="email" type="email" name="email"
                                            class="form-control auth-input-modern"
                                            style="text-transform: lowercase;" {{-- Tambahkan ini --}}
                                            placeholder="Masukkan email Anda"
                                            value="{{ old('email') }}" required>
                                    </div>

                                    {{-- Phone Number --}}
                                    <div class="mb-4">
                                        <label for="no_hp" class="form-label auth-form-label">Nomor Handphone</label>
                                        <input id="no_hp" type="number" name="no_hp"
                                               class="form-control auth-input-modern"
                                               placeholder="Nomor handphone Anda" value="{{ old('no_hp') }}" required>
                                    </div>

                                    {{-- Address --}}
                                    <div class="mb-4">
                                        <label for="alamat" class="form-label auth-form-label">Alamat (Opsional)</label>
                                        <textarea id="alamat" name="alamat" rows="2"
                                                  class="form-control auth-input-modern"
                                                  style="resize: none;"
                                                  placeholder="Alamat lengkap Anda">{{ old('alamat') }}</textarea>
                                    </div>

                                    {{-- Password --}}
                                    <div class="mb-4">
                                        <label for="password" class="form-label auth-form-label">Password</label>
                                        <div class="position-relative d-flex align-items-center">
                                            <input id="password" type="password" name="password"
                                                   class="form-control auth-input-modern pe-5"
                                                   placeholder="********" required>
                                            <span class="position-absolute translate-middle-y top-50" style="right: 15px; cursor: pointer; z-index: 10; line-height: 1;" onclick="togglePassword('password')">
                                                <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#abb5be" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: block;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                <svg id="eye-off-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00a669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div class="mb-5">
                                        <label for="password_confirmation" class="form-label auth-form-label">Confirm password</label>
                                        <div class="position-relative d-flex align-items-center">
                                            <input id="password_confirmation" type="password" name="password_confirmation"
                                                   class="form-control auth-input-modern pe-5"
                                                   placeholder="********" required>
                                            <span class="position-absolute translate-middle-y top-50" style="right: 15px; cursor: pointer; z-index: 10; line-height: 1;" onclick="togglePassword('password_confirmation')">
                                                <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#abb5be" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: block;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                <svg id="eye-off-password_confirmation" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00a669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-4 pb-2">
                                        <button type="submit" class="btn auth-btn-pill-green w-100">
                                            Selanjutnya
                                        </button>
                                    </div>
                                </form>

                                <div class="text-center mt-3">
                                    <p class="mb-0 text-muted" style="font-size: 13px;">
                                        Sudah punya akun?
                                        <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: #00a669;">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById('eye-' + inputId);
            const eyeOffIcon = document.getElementById('eye-off-' + inputId);

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                input.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#00a669',
                customClass: {
                    popup: 'rounded-4 shadow-sm'
                }
            });
        @endif
    </script>
@endsection
