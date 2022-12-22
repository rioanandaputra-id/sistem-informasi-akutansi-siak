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
                                        <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                                        @for($i=1;$i<3;$i++)
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
                paging: true,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('kepalauud.Export.Rba.apiGetAll') }}',
                    type: 'GET',
                    data: {
                        tahun: $('#tahun').val(),
                        divisi: $('#divisi').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        title: 'No.',
                        width: '5px',
                    },
                    {
                        data: 'nm_kegiatan',
                        name: 'nm_kegiatan',
                        title: 'Nama Kegiatan',
                    },
                    {
                        data: 'nm_divisi',
                        name: 'nm_divisi',
                        title: 'Nama Divisi',
                    },
                    {
                        data: 'id_rba',
                        name: 'id_rba',
                        title: 'Status',
                        render: function(data, type, row) {
                            if(row.id_kegiatan_divisi == null) {
                                return '-';
                            } else if (row.id_rba != null && row.tgl_submit == null) {
                                return 'Belum Submit';
                            } else {
                                var route = row.id_kegiatan;
                                return `<a href="${route}" class="btn btn-info"><i class="fas fa-print"></i> Print</a>`;
                            }
                        }
                    }
                ]
            });
        }
    </script>
@endpush