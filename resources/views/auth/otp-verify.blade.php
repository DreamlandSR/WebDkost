@extends('layout')

@section('content')
    {{-- Pindahkan logika PHP ke paling atas agar bisa diakses seluruh elemen di halaman ini --}}
    @php
        $isRegister = session()->has('register_otp');
        $actionRoute = $isRegister ? route('register.verify-otp') : route('otp.verify');
        $backRoute = $isRegister ? route('register') : route('otp.request');
    @endphp

    <div class="bootstrap-scope">
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper auth-wrapper-modern px-0">

                    <div class="row w-100 mx-0 justify-content-center">
                        <div class="col-lg-5 col-md-7 col-sm-9 mx-auto">
                            <div class="card auth-card-modern shadow-sm border-0">

                                {{-- Header: Back Icon & Stepper --}}
                                <div class="d-flex align-items-center justify-content-between mb-5 px-1 mt-1">
                                    {{-- Tombol back sekarang dinamis menyesuaikan asal user --}}
                                    <a href="{{ $backRoute }}" class="auth-back-btn">
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
                                        Kami sudah mengirimkan kode ke
                                        <strong>{{ request('email') ?? session('otp_email') ?? session('register_data')['email'] ?? '' }}</strong>, silahkan cek email anda.
                                    </p>
                                </div>

                                {{-- Alert Messages --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 py-2 px-3 transition-all"
                                        style="font-size: 14px;">
                                        <ul class="mb-0 list-unstyled text-center">
                                            @foreach ($errors->all() as $error)
                                                <li><i class="ti-info-alt mr-1"></i> {{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ $actionRoute }}" class="px-2">
                                    @csrf

                                    {{-- Hidden email ini hanya untuk Lupa Password --}}
                                    @if (!$isRegister)
                                        <input type="hidden" name="email"
                                            value="{{ request('email') ?? session('otp_email') }}">
                                    @endif

                                    <div class="mb-5 text-center">
                                        <label class="form-label auth-form-label d-block text-start mb-3">Kode Verifikasi</label>
                                        <div class="d-flex justify-content-between otp-container" style="gap: 8px;">
                                            @for ($i = 0; $i < 6; $i++)
                                                <input type="text"
                                                    class="form-control auth-input-modern fw-bold"
                                                    style="
                                                        font-size: 24px !important;
                                                        width: 100% !important;
                                                        height: 60px !important;
                                                        line-height: 1 !important; /* Reset line height */
                                                        padding: 0 !important;
                                                        margin: 0 !important;
                                                        text-align: center !important;
                                                        display: flex !important;
                                                        align-items: center !important;
                                                        justify-content: center !important;
                                                        border-radius: 12px !important;
                                                        border: 2px solid #e1e5eb !important;
                                                    "
                                                    maxlength="1"
                                                    required
                                                    {{ $i == 0 ? 'autofocus' : '' }}>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="otp" id="otp-hidden">
                                    </div>

                                    <div class="mb-4 pb-2">
                                        <button type="submit" class="btn auth-btn-pill-green w-100">
                                            Next
                                        </button>
                                    </div>

                                    <div class="text-center">
                                        @if (!$isRegister)
                                            {{-- Tampilan jika sedang Lupa Password --}}
                                            <a href="{{ route('otp.request') }}"
                                                class="auth-link-small text-decoration-none" style="color: #666;">
                                                Resend code
                                            </a>
                                        @else
                                            {{-- Tampilan jika sedang Registrasi Akun Baru --}}
                                            <a href="{{ route('register') }}" class="auth-link-small text-decoration-none"
                                                style="color: #666;">
                                                Batalkan & Kembali
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll('.otp-container input[type="text"]');
            const hiddenInput = document.getElementById('otp-hidden');

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    // Allow only numbers
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');

                    if (e.target.value !== '') {
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    }
                    updateHiddenInput();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && e.target.value === '') {
                        if (index > 0) {
                            inputs[index - 1].focus();
                            inputs[index - 1].value = '';
                        }
                    }
                });

                // Handle paste
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                    if(pastedData) {
                        for(let i = 0; i < pastedData.length; i++) {
                            if(inputs[index + i]) {
                                inputs[index + i].value = pastedData[i];
                            }
                        }
                        if(index + pastedData.length < inputs.length) {
                            inputs[index + pastedData.length].focus();
                        } else {
                            inputs[inputs.length - 1].focus();
                        }
                        updateHiddenInput();
                    }
                });
            });

            function updateHiddenInput() {
                let otpValue = '';
                inputs.forEach(input => {
                    otpValue += input.value;
                });
                hiddenInput.value = otpValue;
            }
        });

        @if (session('status') || session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('status') ?? session('success') }}',
                confirmButtonColor: '#00a669',
                customClass: {
                    popup: 'rounded-4 shadow-sm pb-4', // pb-4 agar bagian bawah juga proporsional
                    icon: 'mt-4' // Memberikan margin top pada icon (Gunakan mt-4 untuk Bootstrap 4/5)
                }
            });
        @endif
    </script>
@endsection
