<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
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
                        <h1>Reset Password</h1>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}: <i
                                class="text-danger">*</i></label>

                        <div class="col-md-6">
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" style="width: 100%; border-radius: 0;">
                                {{ __('Kirim Tautan Atur Ulang Password') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <i class="text-red">*)</i> Bidang Wajib Diisi!
            </div>
        </div>
    </div>

    <script src="{{ asset('adminlte320/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte320/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
