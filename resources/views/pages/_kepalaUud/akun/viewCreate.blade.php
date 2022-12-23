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
                        <form action="{{ route('kepalauud.master.akun.apiCreate') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="no_akun_induk">No. Induk:</label>
                                    <select name="no_akun_induk" id="no_akun_induk" class="form-control @error('no_akun_induk') is-invalid @enderror">
                                        <option value="">-</option>
                                        @foreach ($noInduk as $ni)
                                            <option value="{{ $ni->id_akun }}" {{ (old('no_akun_induk') == $ni->no_akun) ? 'selected' : ''}}>{{ $ni->no_akun }} - {{ $ni->nm_akun }}</option>
                                        @endforeach
                                    </select>
                                    @error('no_akun_induk')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="no_akun">Elemen: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control @error('elemen') is-invalid @enderror" value="{{ old('elemen') }}" id="elemen" name="elemen" maxlength="1" placeholder="0" required>
                                    @error('elemen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="no_akun">Sub Elemen: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control @error('sub_elemen') is-invalid @enderror" value="{{ old('elemen') }}" id="sub_elemen" name="sub_elemen" maxlength="1" placeholder="0" required>
                                    @error('sub_elemen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="no_akun">Jenis: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control @error('jenis') is-invalid @enderror" value="{{ old('elemen') }}" id="jenis" name="jenis" maxlength="2" placeholder="00" required>
                                    @error('jenis')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="no_akun">No. Akun: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control @error('no_akun') is-invalid @enderror" value="{{ old('no_akun') }}" id="no_akun" name="no_akun" maxlength="4" placeholder="0000" required>
                                    @error('no_akun')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="nm_akun">Nama Akun: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control @error('nm_akun') is-invalid @enderror" value="{{ old('nm_akun') }}" id="nm_akun" name="nm_akun" placeholder="Nama Akun" required>
                                    @error('nm_akun')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="keterangan">Keterangan:</label>
                                    <input type="text" class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan') }}" id="keterangan" name="keterangan">
                                    @error('keterangan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer mt-4">
                                <button type="submit" class="btn noborder btn-info mr-2"><i
                                    class="fas fa-plus-circle"></i> Tambah Data</button>
                                <a href="{{ route('kepalauud.master.akun.viewGetAll') }}" type="button" class="btn noborder btn-info"><i
                                        class="fas fa-chevron-circle-left"></i> Kembali</a>
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
