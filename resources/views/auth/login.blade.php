@extends('layout')

@section('content')
    <div class="bootstrap-scope">
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper auth-wrapper-modern px-0">

                    <div class="row w-100 mx-0 justify-content-center">
                        <div class="col-lg-5 col-md-7 col-sm-9 mx-auto">
                            <div class="card auth-card-modern shadow-sm border-0">

                                <div class="text-center mb-4">
                                    <div class="brand-logo mb-3 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('img/dkos_logo.png') }}" alt="logo"
                                            class="img-fluid auth-logo-img">
                                    </div>
                                    <h2 class="auth-title-large mb-1">Hai, Selamat datang</h2>
                                    <p class="text-muted auth-subtitle-text">Login untuk melanjutkan</p>
                                </div>

                                @if ($errors->any())
                                    <div
                                        class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 pb-3 px-3 transition-all auth-alert-text">
                                        <ul class="mb-0 list-unstyled">
                                            @foreach ($errors->all() as $error)
                                                <li><i class="ti-info-alt mr-1"></i> {{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div
                                        class="alert alert-success border-0 rounded-3 shadow-sm mb-4 py-2 px-3 text-center transition-all auth-alert-text">
                                        <i class="ti-check-box mr-1"></i> {{ session('success') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}" class="px-2">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="email" class="form-label auth-form-label">Email Address</label>
                                        <input id="email" type="email" name="email"
                                            class="form-control auth-input-modern" placeholder="Masukkan email anda"
                                            value="{{ old('email', session('email')) }}" required autofocus>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label auth-form-label">Password</label>
                                        <div class="position-relative d-flex align-items-center">
                                            <input id="password" type="password" name="password"
                                                class="form-control auth-input-modern pe-5" placeholder="**********"
                                                required>
                                            <span class="auth-toggle-password" data-target="password">
                                                <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" width="18"
                                                    height="18" viewBox="0 0 24 24" fill="none" stroke="#abb5be"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    style="display: block;">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <svg id="eye-off-password" xmlns="http://www.w3.org/2000/svg" width="18"
                                                    height="18" viewBox="0 0 24 24" fill="none" stroke="#00a669"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    style="display: none;">
                                                    <path
                                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                                    </path>
                                                    <line x1="1" y1="1" x2="23" y2="23">
                                                    </line>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-4 d-flex align-items-center">
                                        <span class="text-muted mr-1 auth-forgot-text">Lupa password ?</span>
                                        <a href="{{ route('otp.request') }}"
                                            class="small text-decoration-none auth-link-forgot">
                                            klik disini
                                        </a>
                                    </div>

                                    <div class="mb-4 pb-2">
                                        <button type="submit" class="btn auth-btn-pill-green w-100">
                                            Masuk
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    window.togglePassword = function(inputId) {
        const input  = document.getElementById(inputId);
        const eyeOn  = document.getElementById('eye-' + inputId);
        const eyeOff = document.getElementById('eye-off-' + inputId);

        const isPassword = input.type === 'password';

        input.type           = isPassword ? 'text'  : 'password';
        eyeOn.style.display  = isPassword ? 'none'  : 'block';
        eyeOff.style.display = isPassword ? 'block' : 'none';
    };
</script>
@endpush
