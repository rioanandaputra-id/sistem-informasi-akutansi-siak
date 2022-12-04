<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi</title>
    <link rel="icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte320/dist/css/adminlte.min.css') }}">
    @stack('css')
</head>

<body class="hold-transition login-page"
    style="background: url('images/bg.png') no-repeat; background-size: 100% 100%;">

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
                        <h1>Registrasi</h1>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row mb-2">
                        <div class="col">
                            <label for="id_divisi">{{ __('Divisi') }}: <i class="text-danger">*</i></label>
                            <select name="id_divisi" id="id_divisi"
                                class="form-control @error('id_divisi') is-invalid @enderror" required autofocus>
                                <option value=""></option>
                                @foreach ($divisis as $d)
                                    <option {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}
                                        value="{{ $d->id_divisi }}">{{ $d->nm_divisi }}</option>
                                @endforeach
                            </select>
                            @error('id_divisi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="id_role">{{ __('Role') }}: <i class="text-danger">*</i></label>
                            <select name="id_role" id="id_role"
                                class="form-control @error('id_role') is-invalid @enderror" required autofocus>
                                <option value=""></option>
                                @foreach ($roles as $r)
                                    <option {{ old('id_role') == $r->id_role ? 'selected' : '' }}
                                        value="{{ $r->id_role }}">{{ $r->role_name }}</option>
                                @endforeach
                            </select>
                            @error('id_role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="full_name">{{ __('Nama Lengkap') }}: <i class="text-danger">*</i></label>
                            <input id="full_name" type="text"
                                class="form-control @error('full_name') is-invalid @enderror" name="full_name"
                                value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="gender">{{ __('Jenis Kelamin') }}: <i class="text-danger">*</i></label>
                            <select name="gender" id="gender"
                                class="form-control @error('gender') is-invalid @enderror" required>
                                <option value=""></option>
                                <option {{ old('gender') == 'L' ? 'selected' : '' }} value="L">Laki-laki</option>
                                <option {{ old('gender') == 'P' ? 'selected' : '' }} value="P">Perempuan</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="username">{{ __('Username') }}: <i class="text-danger">*</i></label>
                            <input id="username" type="username"
                                class="form-control @error('username') is-invalid @enderror" name="username"
                                value="{{ old('username') }}" required>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="password">{{ __('Password') }}: <i class="text-danger">*</i></label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="email">{{ __('Email') }}: <i class="text-danger">*</i></label>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="phone">{{ __('No. Telpon') }}</label>
                            <input id="phone" type="number"
                                class="form-control @error('phone') is-invalid @enderror" name="phone">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <label for="address">{{ __('Alamat') }}</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary" style="width: 100%; border-radius: 0;">
                                {{ __('Register') }}
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
