<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-start" style="padding-left: 1.5rem;">
        <a class="d-flex align-items-center" href="{{ url('/') }}" style="text-decoration: none; gap: 10px;">
            <img src="{{ asset('img/dkos_logo.png') }}" alt="logo" style="width: auto; height: 35px;" />
            <h4 class="mb-0 fw-bold" style="color: #1a2035; letter-spacing: -0.5px; font-size: 22px;">Nexora<span style="color: #4a54e1;">Tech</span></h4>
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-search d-none d-lg-block">
                <div class="d-flex align-items-center" id="navbar-search-container" style="background: #f0f4f8; border-radius: 50px; padding: 0 16px; height: 38px; width: 260px; border: 1px solid transparent; transition: all 0.3s ease;" onmouseover="this.style.background='#eef2f6'; this.style.borderColor='rgba(0,0,0,0.05)';" onmouseout="this.style.background='#f0f4f8'; this.style.borderColor='transparent';">
                    <div class="d-flex align-items-center justify-content-center" id="navbar-search-icon" style="height: 100%;">
                        <i class="ti-search" style="color: #8a92a6; font-size: 14px;"></i>
                    </div>
                    <input type="text" class="form-control bg-transparent border-0 shadow-none ps-2 pe-0 m-0" id="navbar-search-input" placeholder="Cari di sini..."
                        aria-label="search" aria-describedby="search" style="color: #1a2035; font-size: 13px; height: 100%; padding-top: 0; padding-bottom: 0; outline: none;">
                </div>
            </li>
        </ul>


        <ul class="navbar-nav navbar-nav-right d-flex align-items-center" style="gap: 15px;">
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center p-0" id="notificationDropdown" href="#"
                    data-bs-toggle="dropdown" style="width: 42px; height: 42px; background: rgba(74, 84, 225, 0.08); border-radius: 50%; position: relative; transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);" onmouseover="this.style.transform='scale(1.08)';" onmouseout="this.style.transform='scale(1)';">
                    <i class="ti-bell mx-0" style="color: #4a54e1; font-size: 20px;"></i>
                    <span class="count" style="position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; border-radius: 50%; background: #ff4757; border: 2px solid #fff;"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list shadow-sm border-0 rounded-4 p-3"
                    aria-labelledby="notificationDropdown" style="min-width: 320px; top: 120%; margin-top: 10px;">
                    <p class="mb-3 fw-bold float-left dropdown-header text-dark" style="font-size: 15px; padding: 5px 10px;">Notifikasi Baru</p>
                    
                    <a class="dropdown-item preview-item p-3 rounded-4 mb-2 d-flex align-items-center" style="transition: all 0.2s ease; border: 1px solid transparent;" onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='transparent';">
                        <div class="preview-thumbnail flex-shrink-0" style="margin-right: 15px;">
                            <div class="preview-icon d-flex align-items-center justify-content-center border-0" style="width: 40px; height: 40px; border-radius: 12px; background: rgba(0, 166, 105, 0.1);">
                                <i class="ti-info-alt mx-0" style="color: #00a669; font-size: 18px;"></i>
                            </div>
                        </div>
                        <div class="preview-item-content flex-grow-1">
                            <h6 class="preview-subject fw-bold text-dark mb-1" style="font-size: 13.5px;">Server Normal</h6>
                            <p class="font-weight-light small-text mb-0 text-muted" style="font-size: 11px;">
                                Baru saja
                            </p>
                        </div>
                    </a>
                    
                    <a class="dropdown-item preview-item p-3 rounded-4 mb-2 d-flex align-items-center" style="transition: all 0.2s ease; border: 1px solid transparent;" onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='transparent';">
                        <div class="preview-thumbnail flex-shrink-0" style="margin-right: 15px;">
                            <div class="preview-icon d-flex align-items-center justify-content-center border-0" style="width: 40px; height: 40px; border-radius: 12px; background: rgba(255, 178, 89, 0.1);">
                                <i class="ti-settings mx-0" style="color: #ffb259; font-size: 18px;"></i>
                            </div>
                        </div>
                        <div class="preview-item-content flex-grow-1">
                            <h6 class="preview-subject fw-bold text-dark mb-1" style="font-size: 13.5px;">Pembaruan Sistem</h6>
                            <p class="font-weight-light small-text mb-0 text-muted" style="font-size: 11px;">
                                Pesan otomatis
                            </p>
                        </div>
                    </a>
                    
                    <a class="dropdown-item preview-item p-3 rounded-4 mb-0 d-flex align-items-center" style="transition: all 0.2s ease; border: 1px solid transparent;" onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='transparent';">
                        <div class="preview-thumbnail flex-shrink-0" style="margin-right: 15px;">
                            <div class="preview-icon d-flex align-items-center justify-content-center border-0" style="width: 40px; height: 40px; border-radius: 12px; background: rgba(105, 121, 248, 0.1);">
                                <i class="ti-user mx-0" style="color: #6979f8; font-size: 18px;"></i>
                            </div>
                        </div>
                        <div class="preview-item-content flex-grow-1">
                            <h6 class="preview-subject fw-bold text-dark mb-1" style="font-size: 13.5px;">Pengguna Baru</h6>
                            <p class="font-weight-light small-text mb-0 text-muted" style="font-size: 11px;">
                                2 hari yang lalu
                            </p>
                        </div>
                    </a>
                </div>
            </li>
            
            <li class="nav-item nav-profile ps-2">
                <a class="nav-link d-flex align-items-center gap-3 p-0" href="{{ url('/ProfilePage') }}" style="border-radius: 50px; padding: 4px 6px 4px 18px !important; background: #ffffff; border: 1px solid #f0f4f8; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 10px rgba(0,0,0,0.05)'; this.style.borderColor='rgba(0,0,0,0.08)';" onmouseout="this.style.boxShadow='none'; this.style.borderColor='#f0f4f8';">
                    <span class="d-none d-md-block fw-bold text-dark" style="font-size: 13.5px; letter-spacing: -0.2px;">{{ Auth::user()->nama ?? 'Admin' }}</span>
                    <img src="{{ asset(empty(Auth::user()->avatar) ? 'img/Batik 2.jpg' : 'storage/avatars/' . Auth::user()->avatar) }}"
                        alt="Profile" class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #fff;" />
                </a>
            </li>

            <li class="nav-item dropdown nav-settings d-none d-lg-flex ps-1">
                <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center p-0" href="#" id="settingsDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false" style="width: 42px; height: 42px; background: rgba(106, 123, 154, 0.08); border-radius: 50%; transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);" onmouseover="this.style.transform='scale(1.08)';" onmouseout="this.style.transform='scale(1)';">
                    <i class="ti-more-alt" style="color: #6a7b9a; font-size: 20px;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown border-0 shadow-sm rounded-4 p-2" aria-labelledby="settingsDropdown" style="top: 120%; margin-top: 10px; min-width: 180px;">
                    <a href="{{ url('/PengaturanPage') }}" class="dropdown-item rounded-3 mb-1" style="font-size: 13.5px; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                        <i class="ti-settings text-primary" style="margin-right: 12px; font-size: 16px;"></i>
                        Pengaturan
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="dropdown-item rounded-3 fw-bold border-0 bg-transparent w-100 text-start" style="color: #ff4757; font-size: 13.5px; transition: background 0.2s;" onmouseover="this.style.background='#fff4f4'" onmouseout="this.style.background='transparent'">
                            <i class="ti-power-off" style="margin-right: 12px; font-size: 16px; color: #ff4757;"></i>
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
