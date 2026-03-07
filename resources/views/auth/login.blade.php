@extends('layout')

@section('content')
    <div class="bootstrap-scope">
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper d-flex align-items-center auth px-0">
                    <div class="row w-100 mx-0">
                        <div class="col-lg-8 mx-auto">
                            <div class="auth-form-light d-flex flex-wrap p-4" style="border-radius: 10px;">

                                {{-- Form column --}}
                                <div class="col-12 col-md-6 pl-4">
                                    <div class="brand-logo mb-4">
                                        <img src="{{ asset('img/Asset 6.png') }}" alt="logo" class="img-fluid"
                                            style="max-width: 150px;">
                                    </div>
                                    <h4 class="mb-2">Hai, Selamat datang</h4>
                                    <p class="text-muted mb-4">Login untuk melanjutkan</p>

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
                                            <input id="email" type="email" name="email"
                                                class="form-control form-control-sm rounded shadow-sm" placeholder="Email"
                                                value="{{ old('email', session('email')) }}" required autofocus>
                                        </div>
                                        <div class="mb-3">
                                            <input id="password" type="password" name="password"
                                                class="form-control form-control-sm rounded shadow-sm" placeholder="Password" required>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="form-check">
                                                <label class="form-check-label text-muted">
                                                    <input type="checkbox" class="form-check-input">
                                                    Remember me
                                                </label>
                                            </div>
                                            <a href="{{ route('otp.request') }}" class="small text-decoration-none">
                                                Forgot password?
                                            </a>
                                        </div>

                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded shadow-sm"
                                                style="padding: 12px 0;">
                                                Login
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- Gambar column (hilang di mobile) --}}
                                <div
                                    class="col-12 col-md-6 d-none d-md-flex pl-4 pt-1 pr-1 justify-content-center align-items-center">

                                    <div class="w-100 h-100 rounded overflow-hidden"
                                        style="position: relative; background-color: #f5f5f5;">
                                        <img src="{{ asset('img/Batik 2.jpg') }}" alt="Batik Cicilia"
                                            class="img-fluid w-100 h-100"
                                            style="object-fit: cover; position: absolute; top: 0; left: 0;">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
