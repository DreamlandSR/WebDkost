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

                                {{-- Page Titles --}}
                                <div class="text-center mb-5">
                                    <h2 class="auth-title-large">Password baru</h2>
                                    <p class="auth-subtitle-small">BETA Server</p>
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

                                {{-- Reset password form --}}
                                <form method="POST" action="{{ route('otp.reset.password', ['email' => $email]) }}" class="px-2">
                                    @csrf

                                    {{-- Password baru --}}
                                    <div class="mb-4">
                                        <label for="password" class="form-label auth-form-label">Password baru</label>
                                        <div class="position-relative">
                                            <input id="password" type="password" name="password"
                                                   class="form-control auth-input-modern" 
                                                   placeholder="********" required autofocus>
                                            <span class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword('password')">
                                                <i class="ti-eye text-muted" id="toggle-icon-password"></i>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Konfirmasi password --}}
                                    <div class="mb-5">
                                        <label for="password_confirmation" class="form-label auth-form-label">Konfirmasi password</label>
                                        <div class="position-relative">
                                            <input id="password_confirmation" type="password" name="password_confirmation"
                                                   class="form-control auth-input-modern" 
                                                   placeholder="********" required>
                                            <span class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword('password_confirmation')">
                                                <i class="ti-eye text-muted" id="toggle-icon-confirmation"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-4 pb-2">
                                        <button type="submit" class="btn auth-btn-pill-green w-100">
                                            Next
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

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const iconId = inputId === 'password' ? 'toggle-icon-password' : 'toggle-icon-confirmation';
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off'); // Note: Make sure ti-eye-off exists or use another one
                // Using ti-eye with style changes as fallback if ti-eye-off doesn't exist
                icon.style.color = '#28a745'; 
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
                icon.style.color = '#999';
            }
        }
    </script>
@endsection

