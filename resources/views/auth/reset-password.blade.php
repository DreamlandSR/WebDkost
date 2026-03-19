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

                                {{-- Title and Back Button --}}
                                <div class="mb-4 d-flex align-items-center">
                                    <a href="{{ url()->previous() }}" class="me-2 text-dark" style="font-size: 24px; line-height: 1;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-arrow-left">
                                            <line x1="19" y1="12" x2="5" y2="12" />
                                            <polyline points="12 19 5 12 12 5" />
                                        </svg>
                                    </a>
                                    <h4 class="mb-0 fw-bold">Reset Password</h4>
                                </div>

                                <p class="text-muted mb-4">Masukkan password baru Anda</p>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('otp.reset.password', ['email' => $email]) }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="password" class="form-label text-muted mb-1">Password Baru</label>
                                        <input id="password" type="password" name="password"
                                            class="form-control form-control-sm rounded shadow-sm" 
                                            placeholder="Masukkan password baru" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label text-muted mb-1">Konfirmasi Password</label>
                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                            class="form-control form-control-sm rounded shadow-sm"
                                            placeholder="Konfirmasi password" required>
                                    </div>

                                    @error('password')
                                        <div class="text-danger small mb-3">{{ $message }}</div>
                                    @enderror

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded shadow-sm"
                                            style="padding: 12px 0;">
                                            Reset Password
                                        </button>
                                    </div>
                                </form>
                                
                                <div class="text-center mt-3">
                                    <a href="{{ route('login') }}" class="small text-decoration-none text-success">
                                        Kembali ke halaman login
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection