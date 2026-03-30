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
                                    <a href="{{ route('otp.request') }}" class="auth-back-btn">
                                        <i class="ti-angle-left"></i>
                                    </a>
                                    
                                    {{-- Stepper Progress Indicator (Step 2 active) --}}
                                    <div class="auth-stepper-container">
                                        <div class="auth-stepper-line active"></div>
                                        <div class="auth-stepper-dot active"></div>
                                        <div class="auth-stepper-line active"></div>
                                        <div class="auth-stepper-dot active"></div>
                                        <div class="auth-stepper-line"></div>
                                        <div class="auth-stepper-dot"></div>
                                    </div>
                                    
                                    <div style="width: 18px;"></div>
                                </div>

                                {{-- Page Titles --}}
                                <div class="text-center mb-5">
                                    <h2 class="auth-title-large">Verifikasi kode</h2>
                                    <p class="auth-subtitle-small">BETA Server</p>
                                    <p class="auth-desc-text">
                                        Kami sudah mengirimkan kode ke <strong>{{ request('email') ?? session('otp_email') }}</strong>, silahkan cek email anda.
                                    </p>
                                </div>

                                {{-- Alert Messages --}}
                                @if (session('status'))
                                    <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4 py-2 px-3 text-center transition-all" style="font-size: 14px;">
                                        <i class="ti-check-box mr-1"></i> {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 py-2 px-3 transition-all" style="font-size: 14px;">
                                        <ul class="mb-0 list-unstyled text-center">
                                            @foreach ($errors->all() as $error)
                                                <li><i class="ti-info-alt mr-1"></i> {{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Verification Form --}}
                                <form method="POST" action="{{ route('otp.verify') }}" class="px-2">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ request('email') ?? session('otp_email') }}">

                                    <div class="mb-5">
                                        <label for="otp" class="form-label auth-form-label">Code</label>
                                        <input id="otp" type="text" name="otp"
                                               class="form-control auth-input-modern" 
                                               placeholder="Masukkan kode" required autofocus>
                                    </div>

                                    <div class="mb-4 pb-2">
                                        <button type="submit" class="btn auth-btn-pill-green w-100">
                                            Next
                                        </button>
                                    </div>

                                    <div class="text-center">
                                        <a href="{{ route('otp.request') }}" class="auth-link-small text-decoration-none" style="color: #666;">
                                            Resend code
                                        </a>
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