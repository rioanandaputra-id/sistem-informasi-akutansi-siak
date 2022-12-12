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
                                <button id="refresh" type="button" class="btn btn-info noborder"><i
                                        class="fas fa-sync"></i>
                                    Refresh</button>
                                <button id="selected" type="button" class="btn btn-info noborder"><i
                                        class="fas fa-sign-in-alt"></i> Ajukan</button>
                                <!-- <a href="{{ route('kepalabagian.Kegiatan.viewCreate') }}" type="button"
                                    class="btn btn-info noborder">
                                    <i class="fas fa-plus-circle"></i> Rutin
                                </a> -->
                            </div>
                            <div class="float-right">
                                <b>Daftar Kegiatan</b>
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
                            <table class="table table-striped teble-bordered" id="tbKegiatan" style="width: 100%">
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
            tbKegiatan();

            $(".ckAlltbKegiatan").change(function() {
                if (this.checked) {
                    $('.ckItemtbKegiatan').prop('checked', true);
                } else {
                    $('.ckItemtbKegiatan').prop('checked', false);
                }
            });

            $('.filter').on('change', function() {
                $('#tbKegiatan').DataTable().clear().destroy();
                tbKegiatan();
            });

            $("#refresh").click(function() {
                $('#tbKegiatan').DataTable().ajax.reload();
            });

            $("#selected").click(function() {
                Swal.fire({
                    title: 'Apakah Anda Yakin!',
                    text: "Ingin Mengajukan Kegiatan Baru?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Yakin!',
                    cancelButtonText: 'Tidak, Batalkan!'
                }).then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('kepalabagian.Kegiatan.apiCreate') }}",
                            data: {
                                _token: "{!! csrf_token() !!}",
                                id_kegiatan: getIdtbKegiatan()
                            },
                        }).done(function(res) {
                            if (res.status) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Pengajuan Kegiatan Baru Berhasil',
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                            } else {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'Pengajuan Kegiatan Baru Gagal',
                                    showConfirmButton: true,
                                });
                            }
                            $('#tbKegiatan').DataTable().ajax.reload();
                            console.log(res);
                        }).fail(function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Pengajuan Kegiatan Baru Gagal',
                                showConfirmButton: true,
                            });
                            console.log(res);
                        });
                    }
                });
            });
        });

        function getIdtbKegiatan() {
            let id = [];
            $('.ckItemtbKegiatan:checked').each(function() {
                id.push($(this).val());
            });
            return id;
        }

        function tbKegiatan() {
            $('#tbKegiatan').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                paging: true,
                info: true,
                ordering: false,
                ajax: {
                    url: '{{ route('kepalabagian.Kegiatan.apiGetAll') }}',
                    type: 'GET',
                    data: {
                        id_program: $('#id_program').val()
                    }
                },
                columns: [{
                    data: 'id_kegiatan',
                    name: 'id_kegiatan',
                    title: '<input type="checkbox" class="ckAlltbKegiatan" />',
                    width: '5px',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="ckItemtbKegiatan" value="${data}" />`;
                    }
                }, {
                    data: 'nm_kegiatan',
                    name: 'nm_kegiatan',
                    title: 'Kegiatan',
                }, {
                    data: 'nm_program',
                    name: 'nm_program',
                    title: 'Program',
                }, {
                    data: 'nm_misi',
                    name: 'nm_misi',
                    title: 'Misi',
                }, ]
            });
        }
    </script>
@endpush
