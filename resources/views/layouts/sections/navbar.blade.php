<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="d-flex align-items-center navbar-brand-link" href="{{ url('/') }}">
            <img src="{{ asset('img/dkos_logo.png') }}" alt="logo" class="navbar-brand-logo" />
            <h4 class="mb-0 fw-bold navbar-brand-title">Nexora<span>Tech</span></h4>
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-search d-none d-lg-block">
                <div class="d-flex align-items-center navbar-search-container" id="navbar-search-container">
                    <div class="d-flex align-items-center justify-content-center navbar-search-icon"
                        id="navbar-search-icon">
                        <i class="ti-search"></i>
                    </div>
                    <input type="text"
                        class="form-control bg-transparent border-0 shadow-none ps-2 pe-0 m-0 navbar-search-input"
                        id="navbar-search-input" placeholder="Cari di sini..." aria-label="search"
                        aria-describedby="search">
                </div>
            </li>
        </ul>

        {{-- Hamburger button - mobile only --}}
        <button class="d-flex d-lg-none align-items-center justify-content-center border-0 bg-transparent ms-auto me-2"
            onclick="openMobileSidebar()"
            style="width:38px; height:38px; border-radius:10px; background: rgba(106,123,154,0.08) !important;">
            <i class="ti-menu" style="font-size: 20px; color: #6a7b9a;"></i>
        </button>

        <ul class="navbar-nav navbar-nav-right d-flex align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center p-0 notification-icon-btn"
                    id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="ti-bell mx-0"></i>
                    {{-- hapus baris ini: <span class="count"></span> --}}
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list shadow-sm border-0 rounded-4 p-3 notification-dropdown"
                    aria-labelledby="notificationDropdown">
                    <p class="mb-3 fw-bold float-left dropdown-header text-dark dropdown-header-text">Notifikasi Baru
                    </p>

                    @php
                        \Carbon\Carbon::setLocale('id');
                        $latestKeluhan = \App\Models\Keluhan::latest('tgl_lapor')->first();
                        $latestUser = \App\Models\User::latest('created_at')->first();
                    @endphp

                    <a
                        class="dropdown-item preview-item p-3 rounded-4 mb-2 d-flex align-items-center notification-item">
                        <div class="preview-thumbnail flex-shrink-0 notification-thumbnail">
                            <div
                                class="preview-icon d-flex align-items-center justify-content-center border-0 notification-icon-bg icon-success-bg">
                                <i class="ti-info-alt mx-0 icon-success-color"></i>
                            </div>
                        </div>
                        <div class="preview-item-content flex-grow-1">
                            <h6 class="preview-subject fw-bold text-dark mb-1 notification-subject">Keluhan Pengguna
                            </h6>
                            <p class="font-weight-light small-text mb-0 text-muted notification-time">
                                {{ $latestKeluhan ? \Carbon\Carbon::parse($latestKeluhan->tgl_lapor)->diffForHumans() : 'Tidak ada keluhan' }}
                            </p>
                        </div>
                    </a>

                    <a
                        class="dropdown-item preview-item p-3 rounded-4 mb-2 d-flex align-items-center notification-item">
                        <div class="preview-thumbnail flex-shrink-0 notification-thumbnail">
                            <div
                                class="preview-icon d-flex align-items-center justify-content-center border-0 notification-icon-bg icon-warning-bg">
                                <i class="ti-settings mx-0 icon-warning-color"></i>
                            </div>
                        </div>
                        <div class="preview-item-content flex-grow-1">
                            <h6 class="preview-subject fw-bold text-dark mb-1 notification-subject">Pembaruan
                                Sistem</h6>
                            <p class="font-weight-light small-text mb-0 text-muted notification-time">
                                Pesan otomatis
                            </p>
                        </div>
                    </a>

                    <a
                        class="dropdown-item preview-item p-3 rounded-4 mb-0 d-flex align-items-center notification-item">
                        <div class="preview-thumbnail flex-shrink-0 notification-thumbnail">
                            <div
                                class="preview-icon d-flex align-items-center justify-content-center border-0 notification-icon-bg icon-info-bg">
                                <i class="ti-user mx-0 icon-info-color"></i>
                            </div>
                        </div>
                        <div class="preview-item-content flex-grow-1">
                            <h6 class="preview-subject fw-bold text-dark mb-1 notification-subject">Pengguna Baru</h6>
                            <p class="font-weight-light small-text mb-0 text-muted notification-time">
                                {{ $latestUser ? \Carbon\Carbon::parse($latestUser->created_at)->diffForHumans() : 'Tidak ada pengguna' }}
                            </p>
                        </div>
                    </a>
                </div>
            </li>

            <li class="nav-item nav-profile ps-2">
                <a class="nav-link d-flex align-items-center gap-3 p-0 profile-link" href="{{ url('/ProfilePage') }}">
                    <span
                        class="d-none d-md-block fw-bold text-dark mr-2 profile-name">{{ Str::words(Auth::user()->nama ?? 'Admin', 1, '') }}</span>
                    <img src="{{ asset(empty(Auth::user()->avatar) ? 'img/gambar1.png' : 'storage/avatars/' . Auth::user()->avatar) }}"
                        alt="Profile" class="rounded-circle shadow-sm profile-img" />
                </a>
            </li>

            <li class="nav-item dropdown nav-settings d-none d-lg-flex ps-1">
                <a class="nav-link dropdown-toggle p-0 settings-icon-btn" href="#" id="settingsDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="display:flex; align-items:center; justify-content:center;">
                    <i class="ti-more-alt" style="line-height:1;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown border-0 shadow-sm rounded-4 p-2 settings-dropdown"
                    aria-labelledby="settingsDropdown">
                    <a href="{{ url('/PengaturanPage') }}" class="dropdown-item rounded-3 mb-1 settings-item">
                        <i class="ti-settings text-primary"></i>
                        Pengaturan
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit"
                            class="dropdown-item rounded-3 fw-bold border-0 bg-transparent w-100 text-start logout-item">
                            <i class="ti-power-off"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
