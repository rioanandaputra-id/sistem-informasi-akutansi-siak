@extends('template.adminlte')
@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-info" id="SectionCardJabatan">
                <div class="card-header">
                    <h3 class="card-title">{{ Str::upper($info['title']) }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                            <div class="float-left">
                                <a href="{{ route('pengguna.viewCreate') }}" type="button" class="btn btn-info"><i
                                        class="fas fa-plus-circle"></i></a>
                                <a href="javascript:$('#tbPengguna').DataTable().ajax.reload();" type="button"
                                    class="btn btn-info"><i class="fas fa-sync"></i></a>
                            </div>
                            <div class="float-right text-bold">
                                Daftar Pengguna
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table class="table table-striped teble-bordered" id="tbPengguna" style="width: 100%">
                                <thead class="bg-info"></thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte320/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush
@push('js')
    <script src="{{ asset('adminlte320/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#tbPengguna').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                paging: true,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('pengguna.apiGetAll') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'nm_pengguna',
                        name: 'nm_pengguna',
                        title: 'Nama',
                    }, {
                        data: 'jk',
                        name: 'jk',
                        title: 'JK',
                    }, {
                        data: 'no_hp',
                        name: 'no_hp',
                        title: 'Telpon',
                    }, {
                        data: 'nm_peran',
                        name: 'nm_peran',
                        title: 'Peran',
                    },
                    {
                        data: 'id_pengguna',
                        name: 'id_pengguna',
                        title: 'Aksi',
                        render: function(data, type, row, meta) {
                            return `
                                <a href="#" type="button" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="#" type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
@endpush
