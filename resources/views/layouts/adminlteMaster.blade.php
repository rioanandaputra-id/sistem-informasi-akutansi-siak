<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon" />
    <title>{{ $info['title'] }} | PMI</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte320/dist/css/adminlte.min.css') }}">
    <style>
        .noborder {
            border-radius: 0 !important;
            font-weight: bold;
        }
    </style>
    @stack('css')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('layouts.adminlteNavbar')
        @include('layouts.adminlteSidebar')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    @yield('breadcrumb')
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    @include('layouts.flashMessage')
                    @yield('content')
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">v1.0</div>
            <strong>Hak Cipta &copy; {{ date('Y') }} <a href="{{ url('/') }}">SIAK</a></strong>
        </footer>
    </div>

    <script src="{{ asset('adminlte320/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte320/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
</body>

</html>
