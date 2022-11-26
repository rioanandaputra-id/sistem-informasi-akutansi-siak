<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-text text-sm ml-2"><strong>SISTEM INFORMASI AKUTANSI</strong></span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-2 pb-1 mb-3 d-flex">
            <div class="image mt-2">
                <img src="{{ asset('adminlte320/dist/img/user2-160x160.jpg') }}" width="200" height="200"
                    alt="User Image">
            </div>
            <div class="info">
                <span class="text-light d-block">{{ Auth::user()->full_name }}</span>
                <span class="text-primary text-sm">{{ userRole(Auth::user()->id_user)[0]->role_name }}</span>
            </div>
        </div>
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ $info['site_active'] == 'Dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fas fa-list"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @can('kepalawilayah')
                    <li class="nav-item">
                        <a href="{{ route('kepalawilayah.kegiatanMonitoring.viewGetAll') }}" class="nav-link {{ $info['site_active'] == 'MonitoringKegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Monitoring Kegiatan</p>
                        </a>
                    </li>
                @endcan
                @can('kepalauud')
                    <li class="nav-item">
                        <a href="{{ route('kepalauud.visi.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Visi' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Visi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalauud.misi.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Misi' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Misi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalauud.program.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Program' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Program</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalauud.kegiatan.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Kegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Kegiatan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalauud.kegiatanMonitoring.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'MonitoringKegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Monitoring Kegiatan</p>
                        </a>
                    </li>
                @endcan
                @can('timrba')
                    <li class="nav-item">
                        <a href="{{ route('timrba.kegiatanMonitoring.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'MonitoringKegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Monitoring Kegiatan</p>
                        </a>
                    </li>
                @endcan
                @can('kepalabagian')
                    <li class="nav-item {{ $info['site_active'] == 'Kegiatan' || $info['site_active'] == 'MonitoringKegiatan' ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $info['site_active'] == 'Kegiatan' || $info['site_active'] == 'MonitoringKegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Kegiatan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('kepalabagian.Kegiatan.viewGetAll') }}" class="nav-link {{ $info['site_active'] == 'Kegiatan' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengajuan Kegiatan Baru</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kepalabagian.KegiatanMonitoring.viewGetAll') }}" class="nav-link {{ $info['site_active'] == 'MonitoringKegiatan' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Monitoring Kegiatan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ $info['site_active'] == 'Monitoring BKU' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Monitoring BKU</p>
                        </a>
                    </li>
                @endcan
                @can('bendpenerimaan')
                    <li class="nav-item">
                        <a href="#"
                            class="nav-link {{ $info['site_active'] == 'Verifikasi Kegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Verifikasi Kegiatan</p>
                        </a>
                    </li>
                @endcan
                @can('bendpengeluaran')
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ $info['site_active'] == 'Kegiatan Rutin' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Kegiatan Rutin</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#"
                            class="nav-link {{ $info['site_active'] == 'Verifikasi Kegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Verifikasi Kegiatan</p>
                        </a>
                    </li>
                    <li class="nav-item {{ $info['site_active'] == 'SPJ' ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $info['site_active'] == 'SPJ' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                SPJ
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item {{ $info['site_active'] == 'SPJ Program Kegiatan' ? 'active' : '' }}">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>SPJ Program Kegiatan</p>
                                </a>
                            </li>
                            <li class="nav-item {{ $info['site_active'] == 'SPJ Kegiatan Rutin' ? 'active' : '' }}">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>SPJ Kegiatan Rutin</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('bendkegiatan')
                    <li class="nav-item">
                        <a href="#"
                            class="nav-link {{ $info['site_active'] == 'Verifikasi Kegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Verifikasi Kegiatan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ $info['site_active'] == 'SPJ' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>SPJ</p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>
</aside>
