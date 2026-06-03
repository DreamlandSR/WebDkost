{{-- OVERLAY --}}
<div id="mobileSidebarOverlay" onclick="closeMobileSidebar()"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1060;"></div>

{{-- MOBILE SIDEBAR - struktur sama persis dengan sidebar.blade.php --}}
<nav class="sidebar sidebar-offcanvas" id="mobileSidebar"
     style="position:fixed; top:0; left:0; bottom:0; z-index:1070;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;">

    {{-- Tombol close --}}
    <button onclick="closeMobileSidebar()"
            class="border-0 bg-transparent"
            style="position:absolute; top:16px; right:16px; z-index:10; color:#6a7b9a;">
        <i class="ti-close" style="font-size:18px;"></i>
    </button>

    {{-- Brand - sama dengan sidebar.blade.php --}}
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="{{ asset('img/dkos_logo.png') }}" alt="logo">
        </div>
        <div class="brand-text">
            <div class="title">Admin</div>
            <div class="subtitle">{{ Auth::user()->nama ?? 'Admin Dummy' }}</div>
        </div>
    </div>

    {{-- Menu - sama persis dengan sidebar.blade.php --}}
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
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();"
               class="nav-link logout-btn">
                <i class="ti-power-off"></i>
                <span class="menu-title">Keluar</span>
            </a>
            <form id="mobile-logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>

<style>
    @media (max-width: 991px) {
        /* Sembunyikan sidebar desktop */
        .sidebar.sidebar-offcanvas:not(#mobileSidebar) {
            display: none !important;
        }
    }
</style>

<script>
    function openMobileSidebar() {
        document.getElementById('mobileSidebar').style.transform = 'translateX(0)';
        document.getElementById('mobileSidebarOverlay').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeMobileSidebar() {
        document.getElementById('mobileSidebar').style.transform = 'translateX(-100%)';
        document.getElementById('mobileSidebarOverlay').style.display = 'none';
        document.body.style.overflow = '';
    }
</script>
