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
                                    <a href="{{ url('/') }}" class="auth-back-btn">
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
                                    <h2 class="auth-title-large">Pemulihan password</h2>
                                    <p class="auth-subtitle-small">BETA Server</p>
                                    <p class="auth-desc-text">
                                        Masukkan email anda untuk mendapatkan kode pemulihan.
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

                                {{-- Recovery Form --}}
                                <form method="POST" action="{{ route('otp.send') }}" class="px-2">
                                    @csrf

                                    <div class="mb-5">
                                        <label for="email" class="form-label auth-form-label">Email</label>
                                        <input id="email" type="email" name="email"
                                               class="form-control auth-input-modern" 
                                               placeholder="Masukkan email anda" value="{{ old('email') }}" required autofocus>
                                    </div>

                                    <div class="mb-4 pb-2">
                                        <button type="submit" class="btn auth-btn-pill-green w-100">
                                            Selanjutnya
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