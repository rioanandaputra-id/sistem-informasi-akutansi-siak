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
                    <div class="row mb-2">
                        <div class="col">
                            <div class="float-left">
                                <a href="{{ route('kepalauud.misi.viewCreate') }}" type="button" class="btn btn-info noborder">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </a>
                                <button id="refresh" type="button" class="btn btn-info noborder"><i
                                        class="fas fa-sync"></i> Refresh</button>
                                <button id="delete" type="button" class="btn btn-info noborder"><i
                                        class="fas fa-trash"></i> Hapus</button>
                            </div>
                            <div class="float-right text-bold">
                                Daftar Misi
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <table class="table table-striped teble-bordered" id="tbMisi" style="width: 100%">
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
            $('#tbMisi').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                paging: true,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('kepalauud.misi.apiGetAll') }}',
                    type: 'GET'
                },
                columns: [{
                    data: 'id_misi',
                    name: 'id_misi',
                    title: '<input type="checkbox" id="ckAll" />',
                    width: '5px',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="ckItem" value="${data}" />`;
                    }
                }, {
                    data: 'nm_misi',
                    name: 'nm_misi',
                    title: 'Misi',
                    render: function(data, type, row, meta) {
                        return `<a href="{!! route('kepalauud.misi.viewUpdate') !!}?id_misi=${row.id_misi}">${data}</a>`;
                    }
                }, {
                    data: 'periode',
                    name: 'periode',
                    title: 'Periode',
                }, {
                    data: 'a_aktif',
                    name: 'a_aktif',
                    title: 'Status',
                }, ]
            });

            $("#ckAll").change(function() {
                if (this.checked) {
                    $('.ckItem').prop('checked', true);
                } else {
                    $('.ckItem').prop('checked', false);
                }
            });

            $("#refresh").click(function() {
                $('#tbMisi').DataTable().ajax.reload();
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
                            url: "{!! route('kepalauud.misi.apiDelete') !!}",
                            data: {
                                _token: "{!! csrf_token() !!}",
                                no_api: 0,
                                id_misi: getId()
                            }
                        }).done(function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Data Berhasil Dihapus!',
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            $('#tbMisi').DataTable().ajax.reload();
                            $("#delete").prop("disabled", false);
                        }).fail(function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'erorr',
                                title: 'Data Gagal Dihapus!',
                                showConfirmButton: true,
                            });
                            $('#tbMisi').DataTable().ajax.reload();
                            $("#delete").prop("disabled", false);
                        });
                    } else {
                        $('#tbMisi').DataTable().ajax.reload();
                        $("#delete").prop("disabled", false);
                    }
                });
            });

            $("#confirm").click(function() {
                $('#tbMisi').DataTable().ajax.reload();
            });
        });

        function getId() {
            let id = [];
            $('.ckItem:checked').each(function() {
                id.push($(this).val());
            });
            return id;
        }
    </script>
@endpush
