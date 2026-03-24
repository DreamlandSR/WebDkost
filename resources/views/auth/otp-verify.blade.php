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
                                    <img src="{{ asset('img/Asset 6.png') }}" alt="logo" class="img-fluid me-2"
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
                                    <h4 class="mb-0 fw-bold">Verifikasi OTP</h4>
                                </div>

                                <p class="text-muted mb-4">Masukkan kode OTP yang dikirim ke email Anda</p>

                                @if (session('status'))
                                    <div class="alert alert-success mb-4">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('otp.verify') }}">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ request('email') }}">

                                    <div class="mb-4">
                                        <label for="otp" class="form-label text-muted mb-1">Kode OTP</label>
                                        <input id="otp" type="text" name="otp"
                                            class="form-control form-control-sm rounded shadow-sm" 
                                            placeholder="Masukkan kode OTP" required>
                                        @error('otp')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded shadow-sm"
                                            style="padding: 12px 0;">
                                            Verifikasi
                                        </button>
                                    </div>
                                </form>
                                
                                <div class="text-center mt-3">
                                    <p class="mb-0 text-muted small">
                                        Tidak menerima kode? 
                                        <a href="{{ route('otp.request') }}" class="text-success text-decoration-none">
                                            Kirim ulang
                                        </a>
                                    </p>
                                </div>
                                
                                <div class="text-center mt-2">
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