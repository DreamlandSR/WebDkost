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
                                
                                <h4 class="mb-2 text-center">Hai, Selamat datang</h4>
                                <p class="text-muted mb-4 text-center">Login untuk melanjutkan</p>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success mt-3">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label text-muted mb-1">Email Address</label>
                                        <input id="email" type="email" name="email"
                                            class="form-control form-control-sm rounded shadow-sm" 
                                            placeholder="Masukkan email anda" 
                                            value="{{ old('email', session('email')) }}" required autofocus>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label text-muted mb-1">Password</label>
                                        <input id="password" type="password" name="password"
                                            class="form-control form-control-sm rounded shadow-sm" 
                                            placeholder="**********" required>
                                    </div>
                                    
                                    <div class="mb-4 d-flex align-items-center">
                                        <p class="mb-0 me-1">Lupa password ?</p>
                                        <a href="{{ route('otp.request') }}" class="small text-decoration-none text-success">
                                            klik disini
                                        </a>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded shadow-sm"
                                            style="padding: 12px 0;">
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