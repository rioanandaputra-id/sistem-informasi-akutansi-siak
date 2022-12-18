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
                            <div class="float-right">
                                <b>Daftar Kegiatan</b>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col">
                            <select id="status" class="form-control filter">
                                <option value="">-- Status --</option>
                                <option value="1">Belum Diverifikasi</option>
                                <option value="2">Disetujui</option>
                                <option value="3">Ditolak</option>
                            </select>
                        </div>
                        <div class="col">
                            <select id="divisi" class="form-control select2bs4 filter">
                                <option value="">-- Semua Bagian --</option>
                                @foreach ($divisi as $div)
                                    <option value="{{ $div->id_divisi }}">{{ $div->nm_divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <select id="kegiatan" class="form-control select2bs4 filter">
                                <option value="">-- Semua Kegiatan --</option>
                                @foreach ($kegiatan as $kgt)
                                    <option value="{{ $kgt->id_kegiatan }}">{{ $kgt->nm_kegiatan }}</option>
                                @endforeach
                            </select>
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
            $("#status").change(function() {
                $('#tbBku').DataTable().clear().destroy();
                tbBku();
            });
            $("#kegiatan").change(function() {
                $('#tbBku').DataTable().clear().destroy();
                tbBku();
            });
            $("#divisi").change(function() {
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
                    url: '{{ route('kepalabagian.SPJKegiatanMonitoring.apiGetAll') }}',
                    type: 'GET',
                    data: {
                        a_verif: $('#status').val(),
                        id_kegiatan: $('#kegiatan').val(),
                        id_divisi: $('#divisi').val(),
                    },
                },
                columns: [{
                    data: 'urutan_laksana_kegiatan',
                    name: 'urutan_laksana_kegiatan',
                    title: 'Pengajuan',
                    render: function(data, type, row) {
                        return `<a href="{!! route('kepalabagian.SPJKegiatanMonitoring.viewDetail') !!}?id_laksana_kegiatan=${row.id_laksana_kegiatan}">Pelaksanaan Ke-${row.urutan_laksana_kegiatan}</a>`;
                    }
                }, 
                {
                    data: 'nm_divisi',
                    name: 'nm_divisi',
                    title: 'Bagian'
                },
                {
                    data: 'nm_kegiatan',
                    name: 'nm_kegiatan',
                    title: 'Kegiatan',
                    render: function(data, type, row) {
                        return `${row.nm_kegiatan},<br>${row.nm_program},<br>${row.nm_misi}`;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    title: 'Status'
                },
                {
                    data: 'total_realisasi',
                    name: 'total_realisasi',
                    title: 'Total Realisasi',
                    className: 'dt-right'
                },]
            });
        }
    </script>
@endpush
