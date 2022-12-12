<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('images/logo.ico') }}" alt="PMI" class="brand-image" style="opacity: .8">
        <span class="brand-text text-danger ml-2"><strong>PMI LAMPUNG</strong></span>
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
                        <a href="{{ route('kepalawilayah.kegiatanMonitoring.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'MonitoringKegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Monitoring Kegiatan</p>
                        </a>
                    </li>
                @endcan
                @can('kepalauud')
                    <li
                        class="nav-item {{ $info['site_active'] == 'Divisi' || ($info['site_active'] == 'Akun') | ($info['site_active'] == 'ManAkses') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ $info['site_active'] == 'Divisi' || ($info['site_active'] == 'Akun') | ($info['site_active'] == 'ManAkses') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Master
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('kepalauud.master.divisi.viewGetAll') }}"
                                    class="nav-link {{ $info['site_active'] == 'Divisi' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Divisi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kepalauud.master.akun.viewGetAll') }}"
                                    class="nav-link {{ $info['site_active'] == 'Akun' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Akun</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kepalauud.master.manAkses.viewGetAll') }}"
                                    class="nav-link {{ $info['site_active'] == 'ManAkses' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Man. Akses</p>
                                </a>
                            </li>
                        </ul>
                    </li>
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
                    </li><li class="nav-header">MANAJEMEN KEUANGAN</li>
                    <li class="nav-item">
                        <a href="{{ route('kepalauud.ManajemenKeuangan.perencanaan.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Perencanaan' ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>Perencanaan</p>
                        </a>
                    </li>
                    <li class="nav-item {{ $info['site_active'] == 'Penganggaran' || $info['site_active'] == 'PenganggaranPendapatan' || $info['site_active'] == 'PenganggaranPengeluaran' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ $info['site_active'] == 'Penganggaran' || $info['site_active'] == 'PenganggaranPendapatan' || $info['site_active'] == 'PenganggaranPengeluaran' ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>
                                Penganggaran
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('kepalauud.ManajemenKeuangan.penganggaranPendapatan.viewGetAll') }}"
                                    class="nav-link {{ $info['site_active'] == 'PenganggaranPendapatan' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pendapatan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kepalauud.ManajemenKeuangan.penganggaranPengeluaran.viewGetAll') }}"
                                    class="nav-link {{ $info['site_active'] == 'PenganggaranPengeluaran' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengeluaran</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalauud.ManajemenKeuangan.penatausahaan.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Penatausahaan' ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>Penatausahaan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalauud.ManajemenKeuangan.pelaporan.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Pelaporan' ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>Pelaporan</p>
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
                    <li
                        class="nav-item {{ $info['site_active'] == 'KegiatanBaru' || $info['site_active'] == 'KegiatanDiajukan' || $info['site_active'] == 'KegiatanPelaksana' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ $info['site_active'] == 'KegiatanBaru' || $info['site_active'] == 'KegiatanDiajukan' || $info['site_active'] == 'KegiatanPelaksana' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Kegiatan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('kepalabagian.Kegiatan.viewGetAll') }}"
                                    class="nav-link {{ $info['site_active'] == 'KegiatanBaru' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ajukan Kegiatan Baru</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kepalabagian.KegiatanMonitoring.viewGetAll') }}"
                                    class="nav-link {{ $info['site_active'] == 'KegiatanDiajukan' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kegiatan Telah Diajukan</p>
                                </a>
                            </li>
                            @if (Auth::user()->id_divisi == 'f270590c-78f2-4980-be9a-edf0becc3f4f')
                                <li class="nav-item">
                                    <a href="{{ route('kepalabagian.KegiatanPelaksana.viewGetAll') }}"
                                        class="nav-link {{ $info['site_active'] == 'KegiatanPelaksana' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pelaksanaan Kegiatan</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalabagian.SPJKegiatan.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'SPJKegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>SPJ Kegiatan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalabagian.BkuMonitoring.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'MonitoringBKU' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Monitoring BKU</p>
                        </a>
                    </li>

                    <li class="nav-header">MANAJEMEN KEUANGAN</li>
                    <li class="nav-item">
                        <a href="{{ route('kepalabagian.ManajemenKeuangan.perencanaan.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Perencanaan' ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>Perencanaan</p>
                        </a>
                    </li>
                    <li class="nav-item {{ $info['site_active'] == 'Penganggaran' || $info['site_active'] == 'PenganggaranPendapatan' || $info['site_active'] == 'PenganggaranPengeluaran' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ $info['site_active'] == 'Penganggaran' || $info['site_active'] == 'PenganggaranPendapatan' || $info['site_active'] == 'PenganggaranPengeluaran' ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>
                                Penganggaran
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('kepalabagian.ManajemenKeuangan.penganggaranPendapatan.viewGetAll') }}"
                                    class="nav-link {{ $info['site_active'] == 'PenganggaranPendapatan' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pendapatan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kepalabagian.ManajemenKeuangan.penganggaranPengeluaran.viewGetAll') }}"
                                    class="nav-link {{ $info['site_active'] == 'PenganggaranPengeluaran' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengeluaran</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalabagian.ManajemenKeuangan.penatausahaan.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Penatausahaan' ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>Penatausahaan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepalabagian.ManajemenKeuangan.pelaporan.viewGetAll') }}"
                            class="nav-link {{ $info['site_active'] == 'Pelaporan' ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>Pelaporan</p>
                        </a>
                    </li>
                @endcan
                @can('bendpenerimaan')
                    <li class="nav-item">
                        <a href="#"
                            class="nav-link {{ $info['site_active'] == 'VerifikasiKegiatan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Verifikasi Kegiatan</p>
                        </a>
                    </li>
                @endcan
                @can('bendpengeluaran')
                    <li class="nav-item {{ $info['site_active'] == 'KegiatanRutin' || $info['site_active'] == 'KegiatanRutinPelaksana' ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $info['site_active'] == 'KegiatanRutin' || $info['site_active'] == 'KegiatanRutinPelaksana' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Kegiatan Rutin
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('bendaharapengeluaran.kegiatanRutin.viewGetAll') }}" class="nav-link {{ $info['site_active'] == 'KegiatanRutin' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tambah Data</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('bendaharapengeluaran.KegiatanRutinPelaksana.viewGetAll') }}" class="nav-link {{ $info['site_active'] == 'KegiatanRutinPelaksana' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pelaksanaan</p>
                                </a>
                            </li>
                        </ul>
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
                            {{-- <li class="nav-item {{ $info['site_active'] == 'SPJ Program Kegiatan' ? 'active' : '' }}">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>SPJ Program Kegiatan</p>
                                </a>
                            </li> --}}
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
