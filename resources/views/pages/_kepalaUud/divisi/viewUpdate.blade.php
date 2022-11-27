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
                        <form action="{{ route('kepalauud.master.divisi.apiUpdate') }}" method="POST">
                            @csrf
                            @foreach ($divisi as $di)
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nm_divisi">Nama Akun: <i class="text-danger">*</i></label>
                                    <input type="hidden" value="{{ $di->id_divisi }}" name="id_divisi">
                                    <input type="text" class="form-control @error('nm_divisi') is-invalid @enderror"
                                        value="{{ $di->nm_divisi ?? old('nm_divisi') }}" id="nm_divisi" name="nm_divisi">
                                    @error('nm_divisi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @endforeach
                            <div class="card-footer mt-4">
                                <button type="submit" class="btn noborder btn-info mr-2"><i
                                    class="fas fa-edit"></i> Ubah Data</button>
                                <a href="{{ route('kepalauud.master.divisi.viewGetAll') }}" type="button" class="btn noborder btn-info"><i
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
