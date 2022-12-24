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
                                <a href="{{ route('kepalabagian.kegiatanPendapatan.viewCreate') }}" type="button"
                                    class="btn btn-info noborder">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </a>
                                <button id="delete" type="button" class="btn btn-info noborder"><i
                                        class="fas fa-trash"></i>
                                    Hapus</button>
                                <button id="refresh" type="button" class="btn btn-info noborder"><i
                                        class="fas fa-sync"></i>
                                    Refresh</button>
                            </div>
                            <div class="float-right">
                                <b>Daftar Kegiatan Pendapatan</b>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <table class="table table-striped teble-bordered" id="tbKegiatanPendapatan" style="width: 100%">
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
            tbKegiatanPendapatan();

            $("#ckAll").change(function() {
                if (this.checked) {
                    $('.ckItem').prop('checked', true);
                } else {
                    $('.ckItem').prop('checked', false);
                }
            });

            $("#refresh").click(function() {
                $('#tbKegiatanPendapatan').DataTable().ajax.reload();
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
                            url: "{!! route('kepalabagian.kegiatanPendapatan.apiDelete') !!}",
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
                            $('#tbKegiatanPendapatan').DataTable().ajax.reload();
                            $("#delete").prop("disabled", false);
                        }).fail(function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'erorr',
                                title: 'Data Gagal Dihapus!',
                                showConfirmButton: true,
                            });
                            $('#tbKegiatanPendapatan').DataTable().ajax.reload();
                            $("#delete").prop("disabled", false);
                        });
                    } else {
                        $('#tbKegiatanPendapatan').DataTable().ajax.reload();
                        $("#delete").prop("disabled", false);
                    }
                });
            });
        });

        function getId() {
            let id = [];
            $('.ckItem:checked').each(function() {
                id.push($(this).val());
            });
            return id;
        }

        function tbKegiatanPendapatan() {
            $('#tbKegiatanPendapatan').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                paging: true,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('kepalabagian.kegiatanPendapatan.apiGetAll') }}',
                    type: 'GET',
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
                        title: 'Kegiatan Pendapatan',
                        render: function(data, type, row, meta) {
                            return `<a href="{!! route('kepalabagian.kegiatanPendapatan.viewUpdate') !!}?id_kegiatan=${row.id_kegiatan}">${data}</a>`;
                        }
                    },
                    {
                        data: 'periode',
                        name: 'periode',
                        title: 'Periode',
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
@endpush
