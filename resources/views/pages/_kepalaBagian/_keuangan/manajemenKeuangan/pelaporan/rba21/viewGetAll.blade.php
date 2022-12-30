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
                    <div class="row">
                        <div class="col">
                            <table class="table table-striped teble-bordered" id="tbdivisi" style="width: 100%">
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
            tbdivisi();
            $('#btnAdd').hide();

            $("#refresh").click(function() {
                $('#tbdivisi').DataTable().ajax.reload();
            });
        });
    </script>

    <script>
        function tbdivisi() {
            $('#tbdivisi').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                paging: false,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('kepalabagian.ManajemenKeuangan.pelaporan.Rba21.apiGetAll') }}',
                    type: 'GET',
                },
                columns: [{
                        data: 'id_divisi',
                        name: 'id_divisi',
                        title: '<input type="checkbox" id="ckAll" />',
                        width: '5px',
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="ckItem" value="${data}" />`;
                        }
                    },
                    {
                        data: 'nm_divisi',
                        name: 'nm_divisi',
                        title: 'Bagian',
                    },
                    {
                        data: 'id_divisi',
                        name: 'id_divisi',
                        title: 'Export',
                        className: 'dt-right',
                        width: '10%',
                        render: function(data, type, row) {
                            return `<a href="{{ url('kepalauud/Export/Rba/export?id_divisi=${data}') }}" class="btn btn-info btn-sm"><i class="fas fa-file-excel mr-2"></i>Excel</a>`;
                        }
                    }
                ]
            });
        }
    </script>
@endpush