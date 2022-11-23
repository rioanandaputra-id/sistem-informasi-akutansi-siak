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
                                <select class="form-control" id="program" style="min-width: 750px">
                                    @foreach ($program as $pro)
                                        <option value="{{ $pro->id_program }}">
                                            {{ $pro->periode_program . ' - ' . $pro->nm_program }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="float-right">
                                @can('kepalauud')
                                    <a href="{{ route('kegiatan.viewCreate') }}" type="button" class="btn btn-info noborder">
                                        <i class="fas fa-plus-circle"></i> Tambah
                                    </a>
                                    <button id="delete" type="button" class="btn btn-info noborder"><i class="fas fa-trash"></i>
                                        Hapus</button>
                                @endcan
                                <button id="refresh" type="button" class="btn btn-info noborder"><i class="fas fa-sync"></i>
                                    Refresh</button>
                                @can('kepalabagian')
                                    <button id="selected" type="button" class="btn btn-info noborder"><i
                                            class="fas fa-sign-in-alt"></i> Ajukan</button>
                                @endcan
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

            $("#ckAll").change(function() {
                if (this.checked) {
                    $('.ckItem').prop('checked', true);
                } else {
                    $('.ckItem').prop('checked', false);
                }
            });

            $("#refresh").click(function() {
                $('#tbkegiatan').DataTable().ajax.reload();
            });

            $('#program').on('change', function() {
                $('#tbkegiatan').DataTable().clear().destroy();
                tbkegiatan();
            });

            $("#delete").click(function() {
                $("#delete").prop("disabled", true);
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak, Batalkan!'
                }).then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{!! route('kegiatan.apiDelete') !!}",
                            data: {
                                _token: "{!! csrf_token() !!}",
                                no_api: 0,
                                id_kegiatan: getId()
                            }
                        }).done(function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Data Berhasil Dihapus!',
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            $('#tbkegiatan').DataTable().ajax.reload();
                            $("#delete").prop("disabled", false);
                        }).fail(function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'erorr',
                                title: 'Data Gagal Dihapus!',
                                showConfirmButton: true,
                            });
                            $('#tbkegiatan').DataTable().ajax.reload();
                            $("#delete").prop("disabled", false);
                        });
                    } else {
                        $('#tbkegiatan').DataTable().ajax.reload();
                        $("#delete").prop("disabled", false);
                    }
                });
            });

            $("#selected").click(function() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('kegiatanDivisi.apiCreate') }}",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_kegiatan: getId()
                    },
                    beforeSend: function() {
                        $('#selected').prop("disabled", true);
                    },
                }).done(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Pengajuan Kegiatan Baru Berhasil',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    $('#selected').prop("disabled", false);
                    $('#tbkegiatan').DataTable().ajax.reload();
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Pengajuan Kegiatan Baru Gagal',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    console.log(res);
                    $('#selected').prop("disabled", false);
                });
            });
        });
    </script>

    <script>
        function getId() {
            let id = [];
            $('.ckItem:checked').each(function() {
                id.push($(this).val());
            });
            return id;
        }
    </script>

    @can('kepalauud')
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
                        url: '{{ route('kegiatan.apiGetAll') }}',
                        type: 'GET',
                        data: {
                            id_program: $('#program').val()
                        }
                    },
                    columns: [{
                            data: 'id_kegiatan',
                            name: 'id_kegiatan',
                            title: '<input type="checkbox" id="ckAll" />',
                            width: '5px',
                            render: function(data, type, row) {
                                return `<input type="checkbox" class="ckItem" value="${data}" />`;
                            }
                        }, {
                            data: 'nm_kegiatan',
                            name: 'nm_kegiatan',
                            title: 'Kegiatan',
                            render: function(data, type, row, meta) {
                                return `<a href="{!! route('kegiatan.viewUpdate') !!}?id_kegiatan=${row.id_kegiatan}">${data}</a>`;
                            }
                        },
                        {
                            data: 'a_aktif',
                            name: 'a_aktif',
                            title: 'Status',
                        },
                    ]
                });
            }
        </script>
    @else
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
                        url: '{{ route('kegiatan.apiGetAll') }}',
                        type: 'GET',
                        data: {
                            id_program: $('#program').val()
                        }
                    },
                    columns: [{
                        data: 'id_kegiatan',
                        name: 'id_kegiatan',
                        title: '<input type="checkbox" id="ckAll" />',
                        width: '5px',
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="ckItem" value="${data}" />`;
                        }
                    }, {
                        data: 'nm_kegiatan',
                        name: 'nm_kegiatan',
                        title: 'Nama',
                        render: function(data, type, row, meta) {
                            return `<a href="{!! route('kegiatan.viewUpdate') !!}?id_kegiatan=${row.id_kegiatan}">${data}</a>`;
                        }
                    }, ]
                });
            }
        </script>
    @endcan
@endpush
