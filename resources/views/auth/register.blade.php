@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="id_role" class="mb-2">{{ __('Jenis Akun') }}: <i class="text-danger">*</i></label>
                            <div class="col-md-12">
                                <select name="id_role" id="id_role" class="form-control @error('id_role') is-invalid @enderror" required autofocus>
                                    <option value=""></option>
                                        @foreach ($roles as $r)
                                            <option {{ (old('id_role') == $r->id_role) ? 'selected' : '' }} value="{{ $r->id_role }}">{{ $r->role_name }}</option>
                                        @endforeach
                                </select>
                                @error('id_role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="full_name" class="mb-2">{{ __('Nama Lengkap') }}: <i class="text-danger">*</i></label>
                            <div class="col-md-12">
                                <input id="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name') }}" required>
                                @error('full_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="username" class="mb-2">{{ __('Username') }}: <i class="text-danger">*</i></label>
                            <div class="col-md-12">
                                <input id="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="mb-2">{{ __('Password') }}: <i class="text-danger">*</i></label>
                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password-confirm" class="mb-2">{{ __('Konfirmasi Password') }}: <i class="text-danger">*</i></label>
                            <div class="col-md-12">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="phone" class="mb-2">{{ __('No. Telpon') }}</label>
                            <div class="col-md-12">
                                <input id="phone" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="mb-2">{{ __('Email') }}: <i class="text-danger">*</i></label>
                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="gender" class="mb-2">{{ __('Jenis Kelamin') }}: <i class="text-danger">*</i></label>
                            <div class="col-md-12">
                                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    <option value=""></option>
                                    <option {{ (old('gender') == 'L') ? 'selected' : '' }} value="L">Laki-laki</option>
                                    <option {{ (old('gender') == 'P') ? 'selected' : '' }} value="P">Perempuan</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="address" class="mb-2">{{ __('Alamat') }}</label>
                            <div class="col-md-12">
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-12 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
