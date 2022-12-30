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
                                <div class="input-group">
                                    <select class="form-control mr-2" id="tahun">
                                        <option value="-" selected>-- Semua Tahun --</option>
                                        @for($i=0;$i<3;$i++)
                                        <option value="{{ date('Y')-$i }}">{{ date('Y')-$i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="float-left">
                                <div class="input-group">
                                    <select class="form-control mr-2" id="divisi">
                                        <option value="-" selected>-- Semua Bagian --</option>
                                        @foreach($divisi AS $n=>$r)
                                        <option value="{{ $r->id_divisi }}">{{ $r->nm_divisi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="float-right text-bold">
                                <div class="input-group">
                                    <button id="refresh" type="button" class="btn btn-info noborder">
                                        <i class="fas fa-sync"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <table class="table table-striped teble-bordered" id="tbkegiatan" style="width: 100%">
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
            tbkegiatan();
            $('#btnAdd').hide();

            $("#refresh").click(function() {
                $('#tbkegiatan').DataTable().ajax.reload();
            });

            $('#tahun').on('change', function() {
                $('#tbkegiatan').DataTable().clear().destroy();
                tbkegiatan();
            });

            $('#divisi').on('change', function() {
                $('#tbkegiatan').DataTable().clear().destroy();
                tbkegiatan();
            });
        });
    </script>

    <script>
        function tbkegiatan() {
            $('#tbkegiatan').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                paging: false,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('kepalauud.ManajemenKeuangan.perencanaan.apiGetAll') }}',
                    type: 'GET',
                    data: {
                        tahun: $('#tahun').val(),
                        divisi: $('#divisi').val()
                    }
                },
                columns: [{
                        data: 'id_kegiatan_divisi',
                        name: 'id_kegiatan_divisi',
                        title: '<input type="checkbox" id="ckAll" />',
                        width: '5px',
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="ckItem" value="${data}" />`;
                        }
                    },
                    {
                        data: 'nm_program',
                        name: 'nm_program',
                        title: 'Program',
                    },
                    {
                        data: 'periode',
                        name: 'periode',
                        title: 'Periode',
                    },
                    {
                        data: 'nm_kegiatan',
                        name: 'nm_kegiatan',
                        title: 'Kegiatan',
                    },
                    {
                        data: 'nm_divisi',
                        name: 'nm_divisi',
                        title: 'Divisi',
                    },
                ],
            });
        }
    </script>
@endpush