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
            <a class="nav-link {{ request()->is('dashboard/kamar/create*') ? 'active' : '' }}" href="{{ url('/dashboard/kamar/create') }}">
                <i class="ti-upload"></i>
                <span class="menu-title">Upload Kamar</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard/kamar*') && !request()->is('dashboard/kamar/create*') ? 'active' : '' }}" href="{{ url('/dashboard/kamar') }}">
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
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link logout-btn">
                <i class="ti-power-off"></i>
                <span class="menu-title">Keluar</span>
            </a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>
