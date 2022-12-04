<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi alamat email</title>
    <link rel="icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte320/dist/css/adminlte.min.css') }}">
    @stack('css')
</head>

<body class="hold-transition login-page"
    style="background: url('/images/bg.png') no-repeat; background-size: 100% 100%;">
    <div class="container pt-4">
        <div class="card" style="border-radius: 0;">
            <div class="card-header">
                <div class="float-left">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/pmi.png') }}" width="200">
                    </a>
                </div>
                <div class="float-right">
                    <div class="row mt-4 mr-2">
                        <h1>Verifikasi alamat email Anda</h1>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                    </div>
                @endif

                <b>{{ Auth::user()->full_name }}</b>, <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a><br>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                {{ __('Sebelum melanjutkan, periksa email Anda untuk tautan verifikasi.') }}
                {{ __('Jika Anda tidak menerima email') }},
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit"
                        class="btn btn-link p-0 m-0 align-baseline">{{ __('klik di sini untuk kirim ulang') }}</button>.
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('adminlte320/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte320/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
