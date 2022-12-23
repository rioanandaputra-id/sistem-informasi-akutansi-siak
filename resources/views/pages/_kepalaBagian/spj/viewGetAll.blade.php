@extends('layouts.adminlteMaster')

@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-info">
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
                    <div class="row mb-3">
                        <div class="col">
                            <div class="float-left">
                                <button id="refresh" type="button" class="btn btn-info noborder"><i
                                        class="fas fa-sync"></i>
                                    Refresh</button>
                            </div>
                            <div class="float-left">
                                <div class="input-group">
                                    <select class="form-control ml-2" id="nm_kegiatan">
                                        <option value="-" selected>-- Semua Kegiatan --</option>
                                        @foreach($kegiatan AS $n=>$r)
                                        <option value="{{ $r->id_kegiatan }}">{{ $r->nm_kegiatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="float-right">
                                <b>Daftar Kegiatan</b>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <table class="table table-striped teble-bordered" id="tbBku" style="width: 100%">
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
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('adminlte320/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            tbBku();
            $("#refresh").click(function() {
                $('#tbBku').DataTable().ajax.reload();
            });

            $('#nm_kegiatan').on('change', function() {
                $('#tbBku').DataTable().clear().destroy();
                tbBku();
            });
        });

        function tbBku() {
            $('#tbBku').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                paging: true,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('kepalabagian.SPJKegiatan.apiGetAll') }}',
                    type: 'GET',
                    data: {
                        nm_kegiatan: $('#nm_kegiatan').val(),
                    }
                },
                columns: [
                {
                    data: 'nm_kegiatan',
                    name: 'nm_kegiatan',
                    title: 'Kegiatan',
                    render: function(data, type, row) {
                        return `${row.nm_kegiatan},<br>${row.nm_program},<br>${row.nm_misi}`;
                    }
                },{
                    data: 'urutan_laksana_kegiatan',
                    name: 'urutan_laksana_kegiatan',
                    title: 'Pengajuan',
                    render: function(data, type, row) {
                        return `<a href="{!! route('kepalabagian.SPJKegiatan.viewDetail') !!}?id_laksana_kegiatan=${row.id_laksana_kegiatan}">Pelaksanaan Ke-${row.urutan_laksana_kegiatan}</a>`;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    title: 'Status'
                },
                {
                    data: 'total_masuk',
                    name: 'total_masuk',
                    title: 'Anggaran',
                    className: 'dt-right'
                },
                {
                    data: 'total_realisasi',
                    name: 'total_realisasi',
                    title: 'Realisasi',
                    className: 'dt-right'
                },]
            });
        }
    </script>
@endpush
