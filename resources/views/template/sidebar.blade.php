<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-text text-sm ml-2"><strong>SISTEM INFORMASI AKUTANSI</strong></span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-2 pb-1 mb-3 d-flex">
            <div class="image mt-2">
                <img src="{{ asset('adminlte320/dist/img/user2-160x160.jpg') }}" width="200" height="200" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Rio Ananda Putra</a>
                <span class="text-primary text-sm">Kepala PMI Wilayah/Kuasa</span>
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
                    <a href="{{ route('dashboard') }}" class="nav-link {{ $info['site_active'] == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dahsboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengguna.viewGetAll') }}" class="nav-link {{ $info['site_active'] == 'manakses' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Man. Akses</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
