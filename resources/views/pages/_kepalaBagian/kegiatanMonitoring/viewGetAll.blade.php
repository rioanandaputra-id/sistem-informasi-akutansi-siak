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
                                <button id="refresh" type="button" class="btn btn-info noborder">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>
                            </div>
                            <div class="float-right text-bold">
                                <b>Daftar Kegiatan</b>
                                <select class="form-control-sm" id="kategori">
                                    <option value="program" selected>Program</option>
                                    <option value="rutin">Rutin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col">
                            <select id="id_program" class="form-control filter">
                                <option value="">-- program --</option>
                                @foreach ($program as $kgt)
                                    <option value="{{ $kgt->id_program }}">[ {{ $kgt->periode }} ] {{ $kgt->nm_program }}</option>
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

            $('.filter').on('change', function() {
                $('#tbkegiatandivisi').DataTable().clear().destroy();
                tbkegiatandivisi();
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
                    url: '{{ route('kepalabagian.KegiatanMonitoring.apiGetAll') }}',
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
                        render: function(data, type, row) {
                            return `<a href="{{ route('kepalabagian.KegiatanMonitoring.viewDetail') }}?id_kegiatan_divisi=${row.id_kegiatan_divisi}">${data}</a>`;
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
                        render: function(data, type, row) {
                            if (row.id_misi === null) {
                                return '-';
                            } else {
                                return row.nm_misi;
                            }
                        }
                    }, {
                        data: 'nm_divisi',
                        name: 'nm_divisi',
                        title: 'Divisi',
                    }, {
                        data: 'rba_a_verif_wilayah',
                        name: 'rba_a_verif_wilayah',
                        title: 'Status',
                        render: function(data, type, row) {
                            if (row.tgl_submit === null) {
                                return 'Belum Disimpan';
                            } else if (row.rba_a_verif_wilayah != "Belum Diverifikasi Kepala Wilayah") {
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

        function tbkegiatanrutin() {
            $('#tbkegiatandivisi').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                paging: true,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('kepalabagian.KegiatanRutin.apiGetAll') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'id_kegiatan_rutin',
                        name: 'id_kegiatan_rutin',
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
                            return `<a href="{{ route('kepalabagian.KegiatanRutin.viewDetail') }}?id_kegiatan_rutin=${row.id_kegiatan_rutin}">${data}</a>`;
                        }
                    }, {
                        data: 'nm_divisi',
                        name: 'nm_divisi',
                        title: 'Divisi',
                    }, {
                        data: 'a_verif_kabag_keuangan',
                        name: 'a_verif_kabag_keuangan',
                        title: 'Status',
                        render: function(data, type, row) {
                            if (row.tgl_submit === null) {
                                return "Belum Disimpan";
                            } else if (row.a_verif_rba == "1") {
                                return "Belum Diverifikasi Tim RBA";
                            } else if (row.a_verif_kabag_keuangan == "1") {
                                return "Belum Diverifikasi Bendahara Pengeluaran";
                            } else {
                                return "Terverifikasi";
                            }
                        }
                    }
                ]
            });
        }
    </script>
@endpush
