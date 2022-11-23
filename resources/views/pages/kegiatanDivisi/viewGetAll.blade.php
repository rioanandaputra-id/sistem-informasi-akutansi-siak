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
                                <button id="refresh" type="button" class="btn btn-info">
                                    <i class="fas fa-sync"></i></button>
                                <button id="selected" type="button" class="btn btn-info">
                                    <i class="fas fa-sign-in-alt"></i></button>
                            </div>
                            <div class="float-right text-bold">
                                <div class="input-group">
                                    <select name="id_program" class="form-control" id="id_program">
                                        @foreach ($program as $pro)
                                            <option value="{{ $pro->id_program }}">
                                                {{ $pro->periode_program . ' - ' . $pro->nm_program }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="submit" id="filter">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table class="table table-striped teble-bordered" id="tbkegiatandivisi" style="width: 100%">
                                <thead class="bg-info"></thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                ...
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
            tbkegiatandivisi();

            $("#ckAll").change(function() {
                if (this.checked) {
                    $('.ckItem').prop('checked', true);
                } else {
                    $('.ckItem').prop('checked', false);
                }
            });

            $("#refresh").click(function() {
                $('#tbkegiatandivisi').DataTable().ajax.reload();
            });

            $("#filter").click(function() {
                $('#tbkegiatandivisi').DataTable().clear().destroy();
                tbkegiatandivisi();
            });

            $("#selected").click(function() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('kegiatanDivisi.apiUpdate') }}",
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

        function tbkegiatandivisi() {
            $('#tbkegiatandivisi').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                paging: true,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('kegiatanDivisi.apiGetAll') }}',
                    type: 'GET',
                    data: {
                        id_program: $('#id_program').val()
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
                    }, {
                        data: 'nm_kegiatan',
                        name: 'nm_kegiatan',
                        title: 'Kegiatan',
                    },
                    {
                        data: 'nm_divisi',
                        name: 'nm_divisi',
                        title: 'Divisi',
                    },
                ]
            });
        }
    </script>
@endpush
