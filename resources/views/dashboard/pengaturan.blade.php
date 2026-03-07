@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper d-flex py-4">

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-12"> <!-- Lebar diperbesar -->
                                <div class="card shadow-sm rounded-lg">
                                    <div class="card-body text-center py-5">

                                        <!-- Avatar Canvas -->
                                        <div class="d-flex justify-content-center align-items-center rounded-circle mb-4"
                                            style="width: 80px; height: 80px; margin: 0 auto;">
                                            <img src="{{ asset(empty(Auth::user()->avatar) ? 'img/Batik 2.jpg' : 'storage/avatars/' . Auth::user()->avatar) }}"
                                                alt="Profile" class="rounded-circle"
                                                style="width: 100%; height: 100%; object-fit: cover;" />

                                        </div>

                                        <!-- Title -->
                                        <h5 class="text-muted mb-6 justify-content-start">Pengaturan Akun</h5>
                                        <!-- Lebih besar -->

                                        <!-- List Group -->
                                        <ul class="list-group list-group-flush text-left mb-4">
                                            <li class="list-group-item border-bottom py-3" style="font-size: 16px;">
                                                <a href="{{ url('/ProfilePage') }}"
                                                    style="text-decoration: none; color: inherit;">Detail Informasi Akun</a>
                                            </li>
                                            <li class="list-group-item border-bottom py-3" style="font-size: 16px;">
                                                <a href="{{ url('downloads/Healthy.pdf') }}"
                                                    style="text-decoration: none; color: inherit;">Panduan</a>
                                            </li>
                                            <li class="list-group-item border-bottom py-3" style="font-size: 16px;">
                                                <a href="{{ route('otp.request') }}"
                                                    style="text-decoration: none; color: inherit;">Lupa Password</a>
                                            </li>

                                        </ul>

                                        <!-- Buttons -->
                                        <div class="d-flex justify-content-start pb-2"> <!-- Align kiri -->
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm mr-3 px-4">Keluar</button>
                                            </form>
                                            <a href="{{ url('/AdminPage') }}"><button
                                                    class="btn btn-outline-secondary btn-sm px-4">Cancel</button></a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
@endsection
