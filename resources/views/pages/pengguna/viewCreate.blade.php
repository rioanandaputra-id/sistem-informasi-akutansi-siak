@extends('template.adminlte')
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
                        <form action="" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nm_pengguna">Nama Lengkap: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" id="nm_pengguna" name="nm_pengguna">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jk">Jenis Kelamin: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" id="jk" name="jk">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="no_hp">No. Telpon: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="alamat">Alamat: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" id="alamat" name="alamat">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="username">Username: <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" id="username" name="username">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password">Password: <i class="text-danger">*</i></label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <div class="card-footer mt-4">
                                <button type="submit" class="btn noborder btn-info mr-2">Tambah Data</button>
                                <button type="button" class="btn noborder btn-info" onclick="history.back()">Kembali <i
                                        class="fas fa-chevron-circle-left"></i></button>
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
