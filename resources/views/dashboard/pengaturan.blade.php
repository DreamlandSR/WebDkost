@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper pt-5 px-5 pb-2" style="background: #f8fafc; min-height: calc(100vh - 70px);">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="background: #ffffff;">
                                    {{-- Header Decoration --}}
                                    <div style="height: 120px; background: linear-gradient(45deg, #00a669 0%, #00c985 100%); position: relative; overflow: hidden;">
                                        <svg class="position-absolute" style="top: -20px; right: -20px; opacity: 0.2; transform: rotate(15deg);" width="120" height="120" viewBox="0 0 100 100">
                                            <circle cx="50" cy="50" r="40" fill="white" />
                                        </svg>
                                        <svg class="position-absolute" style="bottom: 0; left: 0; opacity: 0.15;" width="100%" height="80" viewBox="0 0 100 100" preserveAspectRatio="none">
                                            <path d="M0 100 C 30 0 70 0 100 100 Z" fill="white"></path>
                                        </svg>
                                    </div>

                                    <div class="card-body pt-0 px-4 pb-5 text-center">
                                        {{-- Avatar Section --}}
                                        <div class="position-relative d-inline-block" style="margin-top: -50px; margin-bottom: 20px;">
                                            <div class="rounded-circle p-1 bg-white shadow-sm">
                                                <div class="rounded-circle overflow-hidden border" style="width: 100px; height: 100px;">
                                                    <img src="{{ asset(empty(Auth::user()->avatar) ? 'img/Batik 2.jpg' : 'storage/avatars/' . Auth::user()->avatar) }}"
                                                        alt="Profile" class="w-100 h-100" style="object-fit: cover;" />
                                                </div>
                                            </div>
                                            <div class="position-absolute" style="bottom: 5px; right: 5px;">
                                                <div class="bg-success border border-white rounded-circle" style="width: 15px; height: 15px;"></div>
                                            </div>
                                        </div>

                                        <h4 class="fw-bold mb-1 text-dark">{{ Auth::user()->name ?? 'Admin' }}</h4>
                                        <p class="text-muted small mb-4">{{ Auth::user()->email ?? 'admin@dkost.com' }}</p>

                                        <div class="text-start mb-4">
                                            <label class="text-uppercase text-muted fw-bold mb-3" style="font-size: 11px; letter-spacing: 1px;">Pengaturan Akun</label>

                                            <div class="list-group list-group-flush rounded-4 overflow-hidden border">
                                                {{-- Detail Informasi Akun --}}
                                                <a href="{{ url('/ProfilePage') }}" class="list-group-item list-group-item-action border-0 py-3 d-flex align-items-center justify-content-between transition-all" style="font-size: 15px;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-3 d-flex align-items-center justify-content-center bg-light mr-3" style="width: 38px; height: 38px;">
                                                            <i class="ti-user text-success" style="font-size: 18px;"></i>
                                                        </div>
                                                        <span class="fw-medium">Detail Informasi Akun</span>
                                                    </div>
                                                    <i class="ti-angle-right text-muted small"></i>
                                                </a>

                                                {{-- Panduan --}}
                                                <a href="{{ url('downloads/Healthy.pdf') }}" class="list-group-item list-group-item-action border-0 py-3 d-flex align-items-center justify-content-between border-top" style="font-size: 15px;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-3 d-flex align-items-center justify-content-center bg-light mr-3" style="width: 38px; height: 38px;">
                                                            <i class="ti-book text-success" style="font-size: 18px;"></i>
                                                        </div>
                                                        <span class="fw-medium">Panduan Penggunaan</span>
                                                    </div>
                                                    <i class="ti-angle-right text-muted small"></i>
                                                </a>

                                                {{-- Lupa Password --}}
                                                <a href="{{ route('otp.request') }}" class="list-group-item list-group-item-action border-0 py-3 d-flex align-items-center justify-content-between border-top" style="font-size: 15px;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-3 d-flex align-items-center justify-content-center bg-light mr-3" style="width: 38px; height: 38px;">
                                                            <i class="ti-lock text-success" style="font-size: 18px;"></i>
                                                        </div>
                                                        <span class="fw-medium">Ganti Password</span>
                                                    </div>
                                                    <i class="ti-angle-right text-muted small"></i>
                                                </a>

                                                {{-- Tambah Akun (Placeholder for consistency with image) --}}
                                                <a href="#" class="list-group-item list-group-item-action border-0 py-3 d-flex align-items-center justify-content-between border-top" style="font-size: 15px;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-3 d-flex align-items-center justify-content-center bg-light mr-3" style="width: 38px; height: 38px;">
                                                            <i class="ti-plus text-success" style="font-size: 18px;"></i>
                                                        </div>
                                                        <span class="fw-medium">Tambah Akun</span>
                                                    </div>
                                                    <i class="ti-angle-right text-muted small"></i>
                                                </a>
                                            </div>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex gap-4">
                                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success px-4 py-2 fw-semibold rounded-3 d-flex align-items-center justify-content-center"
                                                        style="background-color: #00a669; border: none; transition: all 0.3s ease; font-size: 14px; margin-right: 15px;">
                                                        Keluar
                                                    </button>
                                                </form>
                                                <a href="{{ url('/AdminPage') }}" class="btn btn-outline-secondary px-4 py-2 fw-medium rounded-3" style="font-size: 14px;">
                                                    Cancel
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .transition-all {
            transition: all 0.2s ease-in-out;
        }
        .list-group-item-action:hover {
            background-color: #f0fdf4 !important;
            transform: scale(1.01);
        }
        .list-group-item-action:hover .bg-light {
            background-color: #dcfce7 !important;
        }
        #logout-form button:hover {
            background-color: #008a57 !important;
            box-shadow: 0 4px 12px rgba(0, 166, 105, 0.25);
            transform: translateY(-1px);
        }
    </style>
@endsection
