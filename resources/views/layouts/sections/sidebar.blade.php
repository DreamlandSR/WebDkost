<nav class="sidebar sidebar-offcanvas d-none d-lg-block" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="{{ asset('img/dkos_logo.png') }}" alt="logo">
        </div>
        <div class="brand-text">
            <div class="title">Admin</div>
            <div class="subtitle">{{ Auth::user()->nama ?? 'Admin Dummy' }}</div>
        </div>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item mt-3">
            <a class="nav-link {{ request()->is('admin*') ? 'active' : '' }}" href="{{ url('/admin') }}">
                <i class="ti-view-grid"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('laporan/keluhan*') ? 'active' : '' }}" href="{{ url('/laporan/keluhan') }}">
                <i class="ti-bell"></i>
                <span class="menu-title">Laporan Keluhan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('laporan/pengeluaran*') ? 'active' : '' }}" href="{{ url('/laporan/pengeluaran') }}">
                <i class="ti-bar-chart"></i>
                <span class="menu-title">Laporan Pengeluaran</span>
            </a>
        </li>

        <li class="nav-section-title">
            <span>Kelola Data</span>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard/kamar*') ? 'active' : '' }}" href="{{ url('/dashboard/kamar') }}">
                <i class="ti-home"></i>
                <span class="menu-title">Kelola Kamar</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard/furnitur*') ? 'active' : '' }}" href="{{ url('/dashboard/furnitur') }}">
                <i class="ti-layout"></i>
                <span class="menu-title">Kelola Furnitur</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard/user*') ? 'active' : '' }}" href="{{ url('/dashboard/user') }}">
                <i class="ti-user"></i>
                <span class="menu-title">Kelola User</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard/tagihan*') ? 'active' : '' }}" href="{{ url('/dashboard/tagihan') }}">
                <i class="ti-receipt"></i>
                <span class="menu-title">Kelola Tagihan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard/booking*') ? 'active' : '' }}" href="{{ url('/dashboard/booking') }}">
                <i class="ti-calendar"></i>
                <span class="menu-title">Kelola Booking</span>
            </a>
        </li>
        
        <li class="nav-section-title">
            <span>Sistem</span>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('PengaturanPage*') ? 'active' : '' }}" href="{{ url('/PengaturanPage') }}">
                <i class="ti-settings"></i>
                <span class="menu-title">Pengaturan</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#logoutModal" class="nav-link logout-btn">
                <i class="ti-power-off"></i>
                <span class="menu-title">Keluar</span>
            </a>
        </li>
    </ul>
</nav>

<!-- Modal Konfirmasi Logout Modern -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 24px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); overflow: hidden;">
            
            {{-- Decorative Header --}}
            <div style="height: 6px; background: linear-gradient(90deg, #f43f5e 0%, #fb7185 100%); width: 100%;"></div>
            
            <div class="modal-body text-center p-5">
                {{-- Glowing Icon Container --}}
                <div class="mb-4 position-relative d-inline-block">
                    <div class="pulse-ring"></div>
                    <div class="logout-icon-glow" style="width: 85px; height: 85px; background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%); color: #f43f5e; border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 36px; box-shadow: 0 15px 35px rgba(244, 63, 94, 0.2); transform: rotate(-5deg); transition: all 0.5s ease;">
                        <i class="ti-power-off"></i>
                    </div>
                </div>

                <h3 class="fw-800 mb-2" style="color: #0f172a; letter-spacing: -1px; font-size: 24px;">Siap untuk Keluar?</h3>
                <p class="text-muted mb-5" style="font-size: 15px; line-height: 1.6; padding: 0 10px;">Anda akan segera meninggalkan sesi Admin. Pastikan semua pekerjaan Anda telah tersimpan dengan aman.</p>
                
                <div class="d-flex justify-content-center align-items-center mt-2" style="gap: 14px;">
                    <button type="button" 
                            class="btn fw-bold py-3 text-white border-0 shadow-lg"
                            style="background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); border-radius: 16px; font-size: 14px; min-width: 160px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);"
                            onclick="document.getElementById('logout-form').submit();"
                            onmouseover="this.style.transform='translateY(-3px) scale(1.02)'; this.style.boxShadow='0 15px 30px rgba(225, 29, 72, 0.25)';"
                            onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 8px 15px rgba(225, 29, 72, 0.15)';">
                        Ya, Keluar
                    </button>

                    <button type="button" 
                            class="btn fw-700 py-3 border-0" 
                            data-bs-dismiss="modal"
                            style="background: #f8fafc; color: #64748b; border-radius: 16px; font-size: 14px; min-width: 120px; transition: all 0.2s;"
                            onmouseover="this.style.background='#f1f5f9'; this.style.color='#1e293b';"
                            onmouseout="this.style.background='#f8fafc'; this.style.color='#64748b';">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }
    
    .pulse-ring {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 110px;
        height: 110px;
        background: rgba(244, 63, 94, 0.1);
        border-radius: 50%;
        animation: pulse-ring 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        z-index: -1;
    }

    @keyframes pulse-ring {
        0% { transform: translate(-50%, -50%) scale(0.33); opacity: 1; }
        80%, 100% { transform: translate(-50%, -50%) scale(1.2); opacity: 0; }
    }

    #logoutModal .logout-icon-glow:hover {
        transform: rotate(0deg) scale(1.1) !important;
        box-shadow: 0 20px 45px rgba(244, 63, 94, 0.3) !important;
    }
</style>

<form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
    @csrf
</form>
