@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper pt-5 px-4 pb-4" style="background: #f8fafc; min-height: calc(100vh - 70px);">

                    <div class="container d-flex justify-content-center">
                        <div class="card border-0 rounded-4 shadow-sm p-4" style="background: #ffffff; max-width: 850px; width: 100%;">

                            {{-- Avatar Section (Static) --}}
                            <div class="text-center mb-3">
                                <div class="rounded-circle p-1 bg-white shadow-sm border d-inline-block" style="width: 120px; height: 120px; overflow: hidden;">
                                    <img src="{{ Auth::user()->avatar ? asset('storage/avatars/' . Auth::user()->avatar) : asset('img/Batik 2.jpg') }}"
                                         class="w-100 h-100" style="object-fit: cover;" alt="Profile Photo" id="preview">
                                </div>
                                <h3 class="fw-bold mt-3 mb-4" style="color: #64748b; font-size: 24px;">Pengaturan Akun</h3>
                            </div>

                            {{-- Alert Sukses --}}
                            @if (session('success'))
                                <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4 py-2 px-3 d-flex align-items-center">
                                    <i class="ti-check-box mr-2"></i>
                                    <span>{{ session('success') }}</span>
                                </div>
                            @endif

                            {{-- Profile Form --}}
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-4 mb-4">
                                    {{-- Nama --}}
                                    <div class="col-md-6">
                                        <label for="nama" class="form-label mb-2 fw-semibold d-block text-start" style="color: #00c985; font-size: 14px;">Nama</label>
                                        <input type="text" class="form-control rounded-3 py-2 px-3 border-light-subtle shadow-none transition-all"
                                               name="nama" id="nama" value="{{ old('nama', Auth::user()->nama) }}"
                                               style="background: #fcfcfc; font-size: 14px; border: 1.5px solid #eaeaea;">
                                        @error('nama')
                                            <div class="text-danger small mt-1" style="font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Peran --}}
                                    <div class="col-md-6">
                                        <label class="form-label mb-2 fw-semibold d-block text-start" style="color: #00c985; font-size: 14px;">Peran</label>
                                        <input type="text" class="form-control rounded-3 py-2 px-3 border-light-subtle shadow-none"
                                               value="{{ ucfirst(Auth::user()->role ?? 'Admin') }}" readonly
                                               style="background: #f3f4f6; color: #6b7280; font-size: 14px; border: 1.5px solid #eaeaea; cursor: not-allowed;">
                                    </div>

                                    {{-- No Handphone --}}
                                    <div class="col-md-6">
                                        <label for="no_telepon" class="form-label mb-2 mt-4 fw-semibold d-block text-start" style="color: #00c985; font-size: 14px;">No Handphone</label>
                                        <input type="text" class="form-control rounded-3 py-2 px-3 border-light-subtle shadow-none transition-all"
                                               name="no_telepon" id="no_telepon" value="{{ old('no_telepon', Auth::user()->no_telepon ?? '') }}"
                                               style="background: #fcfcfc; font-size: 14px; border: 1.5px solid #eaeaea;">
                                        @error('no_telepon')
                                            <div class="text-danger small mt-1" style="font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-md-6">
                                        <label for="email" class="form-label mb-2 mt-4 fw-semibold d-block text-start" style="color: #00c985; font-size: 14px;">Email</label>
                                        <input type="email" class="form-control rounded-3 py-2 px-3 border-light-subtle shadow-none transition-all"
                                               name="email" id="email" value="{{ old('email', Auth::user()->email) }}"
                                               style="background: #fcfcfc; font-size: 14px; border: 1.5px solid #eaeaea;">
                                        @error('email')
                                            <div class="text-danger small mt-1" style="font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex align-items-center mt-5 mb-2 px-1">
                                    <button type="submit" class="btn btn-success px-5 py-2 fw-semibold rounded-3 d-flex align-items-center justify-content-center transition-all mr-3"
                                            style="background: #00a669; border: none; font-size: 15px; min-width: 140px; box-shadow: 0 4px 10px rgba(0, 166, 105, 0.2);">
                                        Simpan
                                    </button>
                                    <a href="{{ url('/AdminPage') }}" class="btn px-5 py-2 fw-medium rounded-3 transition-all"
                                       style="background: #ffffff; color: #64748b; border: 1.5px solid #d1d5db; font-size: 15px; min-width: 140px;">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .transition-all {
            transition: all 0.25s ease-in-out;
        }

        input.form-control:focus {
            border-color: #00c985 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(0, 201, 133, 0.08) !important;
        }

        .btn-success:hover {
            background: #008a57 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 166, 105, 0.25) !important;
        }

        a.btn:hover {
            background: #f9fafb !important;
            border-color: #9ca3af !important;
            color: #374151 !important;
            transform: translateY(-2px);
        }

        .rounded-4 {
            border-radius: 1.25rem !important;
        }

        .rounded-3 {
            border-radius: 0.75rem !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-md-6 {
                margin-bottom: 0.5rem;
            }
            .mt-5 {
                margin-top: 2.5rem !important;
            }
            .btn {
                flex: 1;
                padding-left: 0 !important;
                padding-right: 0 !important;
                min-width: unset !important;
            }
        }
    </style>
@endsection
