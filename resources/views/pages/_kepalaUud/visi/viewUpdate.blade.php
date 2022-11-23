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
                        <form action="{{ route('visi.apiUpdate') }}" method="POST">
                            @csrf
                            @foreach ($visi as $vsi)
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nm_visi">Nama Visi: <i class="text-danger">*</i></label>
                                    <input type="hidden" value="1" name="no_api">
                                    <input type="hidden" class="form-control" id="id_visi" name="id_visi" value="{{ $vsi->id_visi }}">
                                    <input type="text" class="form-control" id="nm_visi" name="nm_visi" value="{{ $vsi->nm_visi }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="periode">Tahun Periode: <i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="periode" name="periode" value="{{ $vsi->periode }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="a_aktif">Status: <i class="text-danger">*</i></label>
                                    <select name="a_aktif" id="a_aktif" class="form-control">
                                        <option value="">-</option>
                                        <option value="1" {{ ($vsi->a_aktif == 1) ? 'selected' : '' }}>Status 1</option>
                                        <option value="2" {{ ($vsi->a_aktif == 2) ? 'selected' : '' }}>Status 2</option>
                                        <option value="3" {{ ($vsi->a_aktif == 3) ? 'selected' : '' }}>Status 3</option>
                                    </select>
                                </div>
                            </div>
                            @endforeach
                            <div class="card-footer mt-4">
                                <button type="submit" class="btn noborder btn-info mr-2"><i
                                    class="fas fa-edit"></i> Ubah Data</button>
                                <a href="{{ route('visi.viewGetAll') }}" type="button" class="btn noborder btn-info"><i
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
