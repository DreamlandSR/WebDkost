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
                                    <a href="{{ route('register') }}" class="auth-back-btn">
                                        <i class="ti-angle-left"></i>
                                    </a>

                                    {{-- Stepper Progress Indicator (Step 3 active) --}}
                                    <div class="auth-stepper-container">
                                        <div class="auth-stepper-line active"></div>
                                        <div class="auth-stepper-dot active"></div>
                                        <div class="auth-stepper-line active"></div>
                                        <div class="auth-stepper-dot active"></div>
                                        <div class="auth-stepper-line active"></div>
                                        <div class="auth-stepper-dot active"></div>
                                    </div>

                                    <div style="width: 18px;"></div>
                                </div>

                                {{-- Success Icon --}}
                                <div class="mb-4 mt-2 d-flex justify-content-center">
                                    <div style="width: 85px; height: 85px; background-color: rgba(0, 166, 105, 0.08); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px rgba(0, 166, 105, 0.05);">
                                        <div style="width: 60px; height: 60px; background-color: rgba(0, 166, 105, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#00a669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Page Titles --}}
                                <div class="text-center mb-5">
                                    <h2 class="auth-title-large mb-1">Anda berhasil mendaftar !</h2>
                                    <p class="auth-subtitle-small">BETA Server</p>
                                    <p class="auth-desc-text mt-3 mx-auto" style="max-width: 280px;">
                                        Akun kamu berhasil terdaftar, silahkan klik tombol untuk lanjut
                                    </p>
                                </div>

                                <div class="mb-3 pb-2 px-2">
                                    <a href="{{ route('admin.dashboard') }}" class="btn auth-btn-pill-green w-100 d-flex justify-content-center align-items-center" style="font-size: 15px; padding: 14px 0;">
                                        Kembali
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
