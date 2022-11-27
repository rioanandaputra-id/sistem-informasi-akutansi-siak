@extends('layouts.adminlteMaster')
@push('css')
@endpush
@push('js')
@endpush

@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-info" id="SectionCardUnitOrga">
                <div class="card-header">
                    <h3 class="card-title">{{ Str::upper($info['title']) }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3">
                        <form action="{{ route('kepalauud.master.manAkses.apiCreate') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="id_divisi">Nama Divisi: <i class="text-danger">*</i></label>
                                    <select name="id_divisi" id="id_divisi"
                                        class="form-control @error('id_divisi') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($divisi as $di)
                                            <option value="{{ $di->id_divisi }}"
                                                {{ old('id_divisi') == $di->id_divisi ? 'selected' : '' }}>
                                                {{ $di->nm_divisi }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_divisi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="id_role">Role: <i class="text-danger">*</i></label>
                                    <select name="id_role" id="id_role"
                                        class="form-control @error('id_role') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($role as $rol)
                                            <option value="{{ $rol->id_role }}"
                                                {{ old('id_role') == $rol->id_role ? 'selected' : '' }}>
                                                {{ $rol->role_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="full_name">Nama Lengkap: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                        value="{{ old('full_name') }}" id="full_name" name="full_name">
                                    @error('full_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="gender">Jenis Kelamin: <i class="text-danger">*</i></label>
                                    <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                        <option value=""></option>
                                        <option value="L" {{ (old('gender') == 'L') ? 'selected' : ''}}>Laki-laki</option>
                                        <option value="P" {{ (old('gender') == 'P') ? 'selected' : ''}}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="username">Username: <i class="text-danger">*</i></label>
                                    <input type="username" class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username') }}" id="username" name="username">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password">Password: <i class="text-danger">*</i></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        value="{{ old('password') }}" id="password" name="password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="phone">No. Telpon:</label>
                                    <input type="phone" class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" id="phone" name="phone">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email: <i class="text-danger">*</i></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" id="email" name="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="address">Alamat:</label>
                                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="a_active">Status: <i class="text-danger">*</i></label>
                                    <select name="a_active" id="a_active" class="form-control @error('a_active') is-invalid @enderror">
                                        <option value=""></option>
                                        <option value="1" {{ (old('a_active') == '1') ? 'selected' : ''}}>Aktif</option>
                                        <option value="0" {{ (old('a_active') == '0') ? 'selected' : ''}}>Non Aktif</option>
                                    </select>
                                    @error('a_active')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer mt-4">
                                <button type="submit" class="btn noborder btn-info mr-2"><i class="fas fa-plus-circle"></i>
                                    Tambah Data</button>
                                <a href="{{ route('kepalauud.master.manAkses.viewGetAll') }}" type="button"
                                    class="btn noborder btn-info"><i class="fas fa-chevron-circle-left"></i> Kembali</a>
                                <div class="float-right">
                                    <strong><i class="text-danger">*</i> Bidang harus diisi..</strong>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
