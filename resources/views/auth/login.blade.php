<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link rel="icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte320/dist/css/adminlte.min.css') }}">
    @stack('css')
</head>

<body class="hold-transition login-page"
    style="background: url('images/bg.png') no-repeat; background-size: 100% 100%;">
    <div class="login-box">
        <div class="card" style="border-radius: 0;">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1">
                    <img src="{{ asset('images/pmi.png') }}" class="img-fluid">
                </a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Masukkan username dan password</p>
                <form method="POST" action="{{ route('login') }}" class="mb-2">
                    @csrf
                    <div class="input-group mb-3">
                        <input id="username" type="username"
                            class="form-control @error('username') is-invalid @enderror" name="username"
                            value="{{ old('username') }}" required autocomplete="username" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-7">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">
                                    {{ __('Ingat Saya') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary" style="width: 100%; border-radius: 0;">LOGIN</button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col">
                        @if (Route::has('password.request'))
                            Lupa Password ?
                            <a href="{{ route('password.request') }}">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        @if (Route::has('register'))
                            Belum Punya Akun ?
                            <a href="{{ route('register') }}">
                                Registrasi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('adminlte320/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte320/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
