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
                                        @for($i=0;$i<3;$i++)
                                        <option value="{{ date('Y')-$i }}">{{ date('Y')-$i }}</option>
                                        @endfor
                                    </select>
                                    <select class="form-control" id="program">
                                        <option value="-" selected disabled>Pilih</option>
                                        @foreach ($info['kegiatan'] as $kgt)
                                        <option value="{{ $kgt }}">
                                            {{ $kgt }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="float-right text-bold">
                                <div class="input-group">
                                    <button id="refresh" type="button" class="btn btn-info noborder">
                                        <i class="fas fa-sync"></i> Refresh
                                    </button>
                                    <button id="btnAdd" type="button" class="btn btn-info noborder ml-2">
                                        <i class="fas fa-plus"></i> Tambah
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

    <div id="SelectedMdl" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kegiatan Rutin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formSelectedMdl">
                        <select id="akun" class="form-control mb-2">
                            @foreach($akun AS $r)
                                <option value="{{ $r->id_akun }}">{{ '['.$r->no_akun.'] - '.$r->nm_akun }}</option>
                            @endforeach
                        </select>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <input type="text" class="form-control" id="nm_kegiatan" placeholder="Kegiatan" required>
                            </div>
                            <div class="col-md-6 col-12">
                                <input type="number" class="form-control" id="biaya_kegiatan" placeholder="Biaya Kegiatan" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSelectedMdl" class="btn btn-primary">Simpan</button>
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
            tbkegiatan();
            $('#btnAdd').hide();

            $("#refresh").click(function() {
                $('#tbkegiatan').DataTable().ajax.reload();
            });

            $('#tahun').on('change', function() {
                $('#tbkegiatan').DataTable().clear().destroy();
                tbkegiatan();
            });

            $('#program').on('change', function() {
                if($(this).val() == "Rutin") {
                    $('#btnAdd').show();
                } else {
                    $('#btnAdd').hide();
                }
                $('#tbkegiatan').DataTable().clear().destroy();
                tbkegiatan();
            });

            $("#btnAdd").click(function() {
                $('#SelectedMdl').modal('show');
            });

            $("#btnSelectedMdl").click(function() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('kepalabagian.ManajemenKeuangan.penganggaran.apiUpdate') }}',
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_akun: $('#akun').val(),
                        nm_kegiatan: $('#nm_kegiatan').val(),
                        biaya_kegiatan: $('#biaya_kegiatan').val(),
                    },
                    beforeSend: function() {
                        $(this).prop("disabled", true);
                    },
                }).done(function(res) {
                    if (res.status) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Tambah Kegiatan Berhasil',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        $(this).prop("disabled", false);
                        $('#formSelectedMdl').trigger("reset");
                        $('#SelectedMdl').modal('hide');
                        $('#tbkegiatan').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Tambah Kegiatan Gagal',
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
                    url: '{{ route('kepalabagian.ManajemenKeuangan.penganggaran.apiGetAll') }}',
                    type: 'GET',
                    data: {
                        program: $('#program').val(),
                        tahun: $('#tahun').val()
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
                    }, {
                        data: 'nm_divisi',
                        name: 'nm_divisi',
                        title: 'Bagian',
                    },
                    {
                        data: 'a_aktif',
                        name: 'a_aktif',
                        title: 'Status',
                    }
                ]
            });
        }
    </script>
@endpush