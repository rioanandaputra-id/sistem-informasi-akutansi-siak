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
                        <form action="{{ route('kegiatan.apiUpdate') }}" method="POST">
                            @csrf
                            @foreach ($kegiatan as $kgt)
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="id_program">Nama Program: <i class="text-danger">*</i></label>
                                        <select name="id_program" id="id_program"
                                            class="form-control @error('id_program') is-invalid @enderror">
                                            <option value="">-</option>
                                            @foreach ($program as $pgm)
                                                <option value="{{ $pgm->id_program }}"
                                                    {{ (old('id_program') ?? $kgt->id_program == $pgm->id_program) ? 'selected' : '' }}>Program:
                                                    {{ $pgm->nm_program }} ({{ $pgm->periode_program }}) | Misi:
                                                    {{ $pgm->nm_misi }} ({{ $pgm->periode_misi }})</option>
                                            @endforeach
                                        </select>
                                        @error('id_program')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="nm_kegiatan">Nama Kegiatan: <i class="text-danger">*</i></label>
                                        <input type="hidden" value="1" name="no_api">
                                        <input type="hidden" value="{{ $kgt->id_kegiatan }}" name="id_kegiatan">
                                        <input type="text"
                                            class="form-control @error('nm_kegiatan') is-invalid @enderror"
                                            value="{{ old('nm_kegiatan') ??  $kgt->nm_kegiatan}}" id="nm_kegiatan" name="nm_kegiatan">
                                        @error('nm_kegiatan')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="a_aktif">Status Kegiatan: <i class="text-danger">*</i></label>
                                        <select name="a_aktif" id="a_aktif"
                                            class="form-control @error('a_aktif') is-invalid @enderror">
                                            <option value="">-</option>
                                            <option value="1" {{ old('a_aktif') ?? $kgt->a_aktif == 1 ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="2" {{ old('a_aktif') ?? $kgt->a_aktif == 2 ? 'selected' : '' }}>Non Aktif
                                            </option>
                                        </select>
                                        @error('a_aktif')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                            <div class="card-footer mt-4">
                                <button type="submit" class="btn noborder btn-info mr-2">Ubah Data</button>
                                <a href="{{ route('kegiatan.viewGetAll') }}" type="button"
                                    class="btn noborder btn-info">Kembali <i class="fas fa-chevron-circle-left"></i></a>
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
