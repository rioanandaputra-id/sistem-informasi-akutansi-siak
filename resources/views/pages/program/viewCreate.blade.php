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
                        <form action="{{ route('program.apiCreate') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="id_misi">Nama Misi: <i class="text-danger">*</i></label>
                                    <select name="id_misi" id="id_misi" class="form-control @error('id_misi') is-invalid @enderror">
                                        <option value="">-</option>
                                        @foreach ($misi as $msi)
                                            <option value="{{ $msi->id_misi }}" {{ (old('id_misi') == $msi->id_misi) ? 'selected' : ''}}>{{  $msi->nm_misi }} ({{  $msi->periode }})</option>
                                        @endforeach
                                    </select>
                                    @error('id_misi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nm_program">Nama Program: <i class="text-danger">*</i></label>
                                    <input type="hidden" value="1" name="no_api">
                                    <input type="text" class="form-control @error('nm_program') is-invalid @enderror" value="{{ old('nm_program') }}" id="nm_program" name="nm_program">
                                    @error('nm_program')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="periode">Periode Program: <i class="text-danger">*</i></label>
                                    <input type="number" value="{{ date('Y') }}" class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode') }}" id="periode" name="periode">
                                    @error('periode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="a_aktif">Status Program: <i class="text-danger">*</i></label>
                                    <select name="a_aktif" id="a_aktif" class="form-control @error('a_aktif') is-invalid @enderror">
                                        <option value="">-</option>
                                        <option value="1" {{ (old('a_aktif') == 1) ? 'selected' : ''}}>Aktif</option>
                                        <option value="0" {{ (old('a_aktif') == 2) ? 'selected' : ''}}>Non Aktif</option>
                                    </select>
                                    @error('a_aktif')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer mt-4">
                                <button type="submit" class="btn noborder btn-info mr-2">Tambah Data</button>
                                <a href="{{ route('visi.viewGetAll') }}" type="button" class="btn noborder btn-info">Kembali <i
                                        class="fas fa-chevron-circle-left"></i></a>
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
