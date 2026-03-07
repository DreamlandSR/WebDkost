@extends('layout')

@section('content')
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-8 mx-auto">
                    <div class="auth-form-light d-flex flex-wrap p-4" style="border-radius: 10px;">

                        {{-- Form Section --}}
                        <div class="col-12 col-md-6 px-4 py-5">
                            {{-- Logo --}}
                            <div class="mb-5 d-flex justify-content-center">
                                <img src="{{ asset('img/Asset 6.png') }}" alt="logo" class="img-fluid"
                                    style="max-width: 100px;">
                            </div>

                            {{-- Title and Back Button --}}
                            <div class="mb-3 d-flex align-items-center">
                                <a href="{{ url()->previous() }}" class="mr-2 text-dark" style="font-size: 24px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-arrow-left">
                                        <line x1="19" y1="12" x2="5" y2="12" />
                                        <polyline points="12 19 5 12 12 5" />
                                    </svg>
                                </a>
                                <h3 class="mb-0 font-weight-bold">Reset Password</h3>
                            </div>

                            <p class="mb-3">Masukkan password baru Anda</p>

                            <form method="POST" action="{{ route('otp.reset.password', ['email' => $email]) }}">
                                @csrf

                                <div class="mb-3">
                                    <input type="password" name="password"
                                        class="form-control form-control-sm rounded shadow-sm" placeholder="Password Baru"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <input type="password" name="password_confirmation"
                                        class="form-control form-control-sm rounded shadow-sm"
                                        placeholder="Konfirmasi Password" required>
                                </div>


                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded shadow-sm"
                                        style="padding: 12px 0;">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Image Section --}}
                        <div class="col-12 col-md-6 d-none d-md-flex justify-content-center align-items-center pr-1">
                            <div class="w-100 h-100 rounded overflow-hidden" style="position: relative;">
                                <img src="{{ asset('img/Batik 2.jpg') }}" alt="Batik" class="img-fluid w-100 h-100"
                                    style="object-fit: cover; position: absolute; top: 0; left: 0;">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
