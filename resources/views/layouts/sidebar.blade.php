<aside id="sb-sidebar">
    <!-- Brand / Header -->
    <a href="{{ route('dashboard') }}" class="sb-sidebar-brand" style="border-bottom: 2px solid #FEE2E2; background: linear-gradient(135deg, #FEE2E2 0%, rgba(254, 226, 226, 0.5) 100%);">
        <i class="bi bi-gift-fill" style="color: #DC2626; font-size: 1.5rem;"></i>
        <span style="color: #DC2626; font-weight: 800;">Sembako</span>
    </a>

    <!-- Sidebar Navigation Menu -->
    <nav class="sb-sidebar-menu">
        <div class="sb-menu-heading">Menu Utama</div>

        <!-- Dashboard: Common to all roles -->
        <a href="{{ route('dashboard') }}" class="sb-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>
            <span>Dashboard</span>
        </a>

        @if(auth()->user()->role === 'super_admin')
            <!-- Super Admin Menus -->
            <a href="{{ route('users.index') }}" class="sb-menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Manajemen User</span>
            </a>
            <a href="{{ route('jenis-bantuan.index') }}" class="sb-menu-item {{ request()->routeIs('jenis-bantuan.*') ? 'active' : '' }}">
                <i class="bi bi-gift"></i>
                <span>Jenis Bantuan</span>
            </a>
            <a href="{{ route('penerima.index') }}" class="sb-menu-item {{ request()->routeIs('penerima.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span>Data Penerima</span>
            </a>
            <a href="{{ route('pengajuan.index') }}" class="sb-menu-item {{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>Pengajuan Bantuan</span>
            </a>
            <a href="{{ route('survei.index') }}" class="sb-menu-item {{ request()->routeIs('survei.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check"></i>
                <span>Survei Lapangan</span>
            </a>
            <a href="{{ route('verifikasi.index') }}" class="sb-menu-item {{ request()->routeIs('verifikasi.*') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i>
                <span>Verifikasi</span>
            </a>
            <a href="{{ route('penyaluran.index') }}" class="sb-menu-item {{ request()->routeIs('penyaluran.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i>
                <span>Penyaluran</span>
            </a>
            {{-- Laporan submenu --}}
            <a class="sb-menu-item d-flex justify-content-between align-items-center {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="#submenuLaporanSA" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('laporan.*') ? 'true' : 'false' }}">
                <span><i class="bi bi-file-bar-graph me-1"></i> Laporan</span>
                <i class="bi bi-chevron-down" style="font-size:.7rem;"></i>
            </a>
            <div id="submenuLaporanSA" class="collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}" style="padding-left:1rem;">
                <a href="{{ route('laporan.index') }}" class="sb-menu-item {{ request()->routeIs('laporan.index') ? 'active' : '' }}" style="font-size:.82rem;">
                    <i class="bi bi-bar-chart"></i><span>Statistik</span>
                </a>
                <a href="{{ route('laporan.pengajuan') }}" class="sb-menu-item {{ request()->is('laporan/pengajuan*') ? 'active' : '' }}" style="font-size:.82rem;">
                    <i class="bi bi-file-earmark-text"></i><span>Lap. Pengajuan</span>
                </a>
                <a href="{{ route('laporan.penyaluran') }}" class="sb-menu-item {{ request()->is('laporan/penyaluran*') ? 'active' : '' }}" style="font-size:.82rem;">
                    <i class="bi bi-truck"></i><span>Lap. Penyaluran</span>
                </a>
            </div>
        @endif

        @if(auth()->user()->role === 'admin')
            <!-- Admin Menus -->
            <a href="{{ route('penerima.index') }}" class="sb-menu-item {{ request()->routeIs('penerima.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span>Data Penerima</span>
            </a>
            <a href="{{ route('pengajuan.index') }}" class="sb-menu-item {{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>Pengajuan Bantuan</span>
            </a>
            <a href="{{ route('survei.index') }}" class="sb-menu-item {{ request()->routeIs('survei.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check"></i>
                <span>Survei Lapangan</span>
            </a>
            <a href="{{ route('verifikasi.index') }}" class="sb-menu-item {{ request()->routeIs('verifikasi.*') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i>
                <span>Verifikasi</span>
            </a>
            <a href="{{ route('penyaluran.index') }}" class="sb-menu-item {{ request()->routeIs('penyaluran.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i>
                <span>Penyaluran</span>
            </a>
            <a href="{{ route('jenis-bantuan.index') }}" class="sb-menu-item {{ request()->routeIs('jenis-bantuan.*') ? 'active' : '' }}">
                <i class="bi bi-gift"></i>
                <span>Jenis Bantuan</span>
            </a>
            {{-- Laporan submenu --}}
            <a class="sb-menu-item d-flex justify-content-between align-items-center {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="#submenuLaporanAdmin" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('laporan.*') ? 'true' : 'false' }}">
                <span><i class="bi bi-file-bar-graph me-1"></i> Laporan</span>
                <i class="bi bi-chevron-down" style="font-size:.7rem;"></i>
            </a>
            <div id="submenuLaporanAdmin" class="collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}" style="padding-left:1rem;">
                <a href="{{ route('laporan.index') }}" class="sb-menu-item {{ request()->routeIs('laporan.index') ? 'active' : '' }}" style="font-size:.82rem;">
                    <i class="bi bi-bar-chart"></i><span>Statistik</span>
                </a>
                <a href="{{ route('laporan.pengajuan') }}" class="sb-menu-item {{ request()->is('laporan/pengajuan*') ? 'active' : '' }}" style="font-size:.82rem;">
                    <i class="bi bi-file-earmark-text"></i><span>Lap. Pengajuan</span>
                </a>
                <a href="{{ route('laporan.penyaluran') }}" class="sb-menu-item {{ request()->is('laporan/penyaluran*') ? 'active' : '' }}" style="font-size:.82rem;">
                    <i class="bi bi-truck"></i><span>Lap. Penyaluran</span>
                </a>
            </div>
        @endif

        @if(auth()->user()->role === 'petugas')
            <!-- Petugas Menus -->
            <a href="{{ route('pengajuan.index') }}" class="sb-menu-item {{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-person"></i>
                <span>Pengajuan Bantuan</span>
            </a>
            <a href="{{ route('survei.index') }}" class="sb-menu-item {{ request()->routeIs('survei.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check"></i>
                <span>Survei Lapangan</span>
            </a>
            <a href="{{ route('penyaluran.index') }}" class="sb-menu-item {{ request()->routeIs('penyaluran.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i>
                <span>Penyaluran</span>
            </a>
        @endif

        @if(auth()->user()->role === 'pimpinan')
            <!-- Pimpinan Menus -->
            <a href="{{ route('persetujuan.index') }}" class="sb-menu-item {{ request()->routeIs('persetujuan.*') ? 'active' : '' }}">
                <i class="bi bi-check-circle"></i>
                <span>Persetujuan</span>
            </a>
            <a href="{{ route('penyaluran.index') }}" class="sb-menu-item {{ request()->routeIs('penyaluran.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i>
                <span>Penyaluran</span>
            </a>
            {{-- Laporan submenu --}}
            <a class="sb-menu-item d-flex justify-content-between align-items-center {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="#submenuLaporanPim" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('laporan.*') ? 'true' : 'false' }}">
                <span><i class="bi bi-file-bar-graph me-1"></i> Laporan</span>
                <i class="bi bi-chevron-down" style="font-size:.7rem;"></i>
            </a>
            <div id="submenuLaporanPim" class="collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}" style="padding-left:1rem;">
                <a href="{{ route('laporan.index') }}" class="sb-menu-item {{ request()->routeIs('laporan.index') ? 'active' : '' }}" style="font-size:.82rem;">
                    <i class="bi bi-bar-chart"></i><span>Statistik</span>
                </a>
                <a href="{{ route('laporan.pengajuan') }}" class="sb-menu-item {{ request()->is('laporan/pengajuan*') ? 'active' : '' }}" style="font-size:.82rem;">
                    <i class="bi bi-file-earmark-text"></i><span>Lap. Pengajuan</span>
                </a>
                <a href="{{ route('laporan.penyaluran') }}" class="sb-menu-item {{ request()->is('laporan/penyaluran*') ? 'active' : '' }}" style="font-size:.82rem;">
                    <i class="bi bi-truck"></i><span>Lap. Penyaluran</span>
                </a>
            </div>
        @endif
    </nav>
</aside>
