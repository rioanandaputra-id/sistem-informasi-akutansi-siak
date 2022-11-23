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
                                    <select id="status" class="form-control mr-2">
                                        <option value="1">Belum Diverifikasi</option>
                                        <option value="2">Disetujui</option>
                                        <option value="3">Tidak Disetujui</option>
                                    </select>
                                    <select class="form-control" id="program" style="min-width: 620px">
                                        @foreach ($program as $pro)
                                            <option value="{{ $pro->id_program }}">
                                                {{ $pro->periode_program . ' - ' . $pro->nm_program }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="float-right text-bold">
                                <div class="input-group">
                                    <button id="refresh" type="button" class="btn btn-info noborder">
                                        <i class="fas fa-sync"></i> Refresh</button>
                                            <button id="selected" type="button" class="btn btn-info noborder ml-2">
                                            <i class="fas fa-sign-in-alt"></i> Konfirmasi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
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

    <div id="SelectedMdl" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formSelectedMdl">
                        <select id="a_verif_rbaSelectedMdl" class="form-control mb-2">
                            <option value="">-- Status Verifikasi --</option>
                            <option value="2">Setujui</option>
                            <option value="3">Tidak Setujui</option>
                        </select>
                        <textarea id="catatanSelectedMdl" cols="30" rows="10" class="form-control" placeholder="Catataan.."></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSelectedMdl" class="btn btn-primary">Verifikasi</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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

            $("#selected").click(function() {
                $('#SelectedMdl').modal('show');
            });

            $("#btnSelectedMdl").click(function() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('kepalawilayah.KegiatanMonitoring.apiUpdate') }}',
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_rba: getId(),
                        a_verif_wilayah: $('#a_verif_rbaSelectedMdl').val(),
                        catatan: $('#catatanSelectedMdl').val(),
                    },
                    beforeSend: function() {
                        $(this).prop("disabled", true);
                    },
                }).done(function(res) {
                    if (res.status) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Verifikasi Kegiatan Berhasil',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        $(this).prop("disabled", false);
                        $('#formSelectedMdl').trigger("reset");
                        $('#SelectedMdl').modal('hide');
                        $('#tbkegiatandivisi').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Verifikasi Kegiatan Gagal',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Verifikasi Kegiatan Gagal',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    console.log(res);
                    $(this).prop("disabled", false);
                });
            });

            $('#program').on('change', function() {
                $('#tbkegiatandivisi').DataTable().clear().destroy();
                tbkegiatandivisi();
            });

            $('#status').on('change', function() {
                $('#tbkegiatandivisi').DataTable().clear().destroy();
                tbkegiatandivisi();
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
                    url: '{{ route('kepalawilayah.KegiatanMonitoring.apiGetAll') }}',
                    type: 'GET',
                    data: {
                        id_program: $('#program').val(),
                        a_verif_wilayah: $('#status').val(),
                    }
                },
                columns: [{
                        data: 'id_rba',
                        name: 'id_rba',
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
                    {
                        data: 'a_verif_wilayah',
                        name: 'a_verif_wilayah',
                        title: 'Status',
                    },
                ]
            });
        }
    </script>
@endpush
