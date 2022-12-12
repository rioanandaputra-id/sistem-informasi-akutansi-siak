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
                                    <button id="refresh" type="button" class="btn btn-info noborder">
                                        <i class="fas fa-sync"></i> Refresh
                                    </button>
                                    <button id="verif" type="button" class="btn btn-info noborder ml-2">
                                        <i class="fas fa-sign-in-alt"></i> Verifikasi
                                    </button>
                                </div>
                            </div>
                            <div class="float-right text-bold">
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
                            <select id="divisi" class="form-control filter">
                                <option value="">-- Semua Bagian --</option>
                                @foreach ($divisi as $div)
                                    <option value="{{ $div->id_divisi }}">{{ $div->nm_divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <select id="kegiatan" class="form-control filter">
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
                            <table class="table table-striped teble-bordered" id="tbkegiatandivisi" style="width: 100%">
                                <thead class="bg-info"></thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="VerifMdl" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formVerifMdl">
                        <select id="a_verif_rbaVerifMdl" class="form-control mb-2">
                            <option value="">-- Verifikasi --</option>
                            <option value="2">Disetujui</option>
                            <option value="3">Ditolak</option>
                        </select>
                        <textarea id="catatanVerifMdl" cols="30" rows="10" class="form-control" placeholder="Catataan.."></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnVerifMdl" class="btn btn-primary">Verifikasi</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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

            $('.filter').on('change', function() {
                if ($(this).val() == 1) {
                    $('#verif').prop("disabled", false);
                } else {
                    $('#verif').prop("disabled", true);
                }
                $('#tbkegiatandivisi').DataTable().clear().destroy();
                tbkegiatandivisi();
            });

            $("#verif").click(function() {
                $('#VerifMdl').modal('show');
            });

            $("#btnVerifMdl").click(function() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('timrba.kegiatanMonitoring.apiUpdate') }}",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_kegiatan_divisi: getId(),
                        a_verif_rba: $('#a_verif_rbaVerifMdl').val(),
                        catatan: $('#catatanVerifMdl').val(),
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
                        $('#formVerifMdl').trigger("reset");
                        $('#VerifMdl').modal('hide');
                        $('#tbkegiatandivisi').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Verifikasi Kegiatan Gagal',
                            showConfirmButton: true,
                        });
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Verifikasi Kegiatan Gagal',
                        showConfirmButton: true,
                    });
                    console.log(res);
                    $(this).prop("disabled", false);
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
                    url: '{{ route('timrba.kegiatanMonitoring.apiGetAll') }}',
                    type: 'GET',
                    data: {
                        kdiv_a_verif_rba: $('#status').val(),
                        id_kegiatan: $('#kegiatan').val(),
                        id_divisi: $('#divisi').val(),
                    },
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
                        render: function(data, type, row) {
                            return `<a href="{{ route('timrba.kegiatanMonitoring.viewDetail') }}?id_kegiatan_divisi=${row.id_kegiatan_divisi}">${data}</a>`;
                        }
                    },
                    {
                        data: 'nm_program',
                        name: 'nm_program',
                        title: 'Program',
                    },
                    {
                        data: 'nm_misi',
                        name: 'nm_misi',
                        title: 'Misi',
                    }, {
                        data: 'nm_divisi',
                        name: 'nm_divisi',
                        title: 'Divisi',
                    }, {
                        data: 'rba_a_verif_wilayah',
                        name: 'rba_a_verif_wilayah',
                        title: 'Status',
                        render: function(data, type, row) {
                            if (row.rba_a_verif_wilayah != "Belum Diverifikasi Kepala Wilayah") {
                                return row.rba_a_verif_wilayah;
                            } else if (row.rba_a_verif_rba != "Belum Diverifikasi Kepala UUD") {
                                return row.rba_a_verif_rba;
                            } else {
                                return row.kdiv_a_verif_rba;
                            }
                        }
                    },
                    {
                        data: 'rba_a_verif_rba',
                        name: 'rba_a_verif_rba',
                        visible: false,
                    },
                    {
                        data: 'kdiv_a_verif_rba',
                        name: 'kdiv_a_verif_rba',
                        visible: false,
                    },
                ]
            });
        }
    </script>
@endpush
