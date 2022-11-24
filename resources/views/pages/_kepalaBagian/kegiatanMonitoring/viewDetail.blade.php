@extends('layouts.adminlteMaster')
@section('breadcrumb')
@endsection

@section('content')
    @php
        $lockBtn = 'disabled';
        $IdRba = '';
    @endphp
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
                            <div class="row bg-success p-2 mb-3">
                                <div class="col">
                                    <div class="float-left">
                                        <b>RENCANA ANGGARAN BIAYA KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                        <button onclick="history.back()" class="btn btn-sm noborder btn-light"><i
                                                class="fas fa-chevron-circle-left"></i>
                                            Kembali</button>
                                    </div>
                                </div>
                            </div>
                            @foreach ($kegiatan as $kgt)
                                @php
                                    $lockBtn = $kgt->rba_a_verif_wilayah == 'Disetujui Kepala Wilayah' ? '' : 'disabled';
                                    $IdRba = $kgt->id_rba;
                                @endphp
                                <table>
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Info Kegiatan</th>
                                        </tr>
                                        <tr>
                                            <td style="min-width: 200px">Misi</td>
                                            <td>:</td>
                                            <td>{{ $kgt->nm_misi }}</td>
                                        </tr>
                                        <tr>
                                            <td>Program</td>
                                            <td>:</td>
                                            <td>{{ $kgt->nm_program }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kegiatan</td>
                                            <td>:</td>
                                            <td>{{ $kgt->nm_kegiatan }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="mt-3">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Verifikasi Tim RBA</th>
                                        </tr>
                                        <tr>
                                            <td style="min-width: 200px">Status</td>
                                            <td>:</td>
                                            <td>{{ $kgt->kdiv_a_verif_rba }}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu</td>
                                            <td>:</td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan</td>
                                            <td>:</td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="mt-3">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Verifikasi Kepala UUD</th>
                                        </tr>
                                        <tr>
                                            <td style="min-width: 200px">Status</td>
                                            <td>:</td>
                                            <td>{{ $kgt->rba_a_verif_rba }}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu</td>
                                            <td>:</td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan</td>
                                            <td>:</td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="mt-3 mb-4">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Verifikasi Kepala Wilayah</th>
                                        </tr>
                                        <tr>
                                            <td style="min-width: 200px">Status</td>
                                            <td>:</td>
                                            <td>{{ $kgt->rba_a_verif_wilayah }}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu</td>
                                            <td>:</td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan</td>
                                            <td>:</td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endforeach
                            <div class="row bg-success p-2 mb-4">
                                <div class="col">
                                    <div class="float-left">
                                        <b>DETAIL RENCANA ANGGARAN BIAYA KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                        <button {{ $lockBtn }} id="addDetailRba"
                                            class="btn btn-sm noborder btn-light"><i class="fas fa-plus-circle"></i>
                                            Tambah</button>
                                        <button {{ $lockBtn }} id="deleteDetailRba"
                                            class="btn btn-sm noborder btn-light ml-2"><i class="fas fa-trash"></i>
                                            Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbDetailRba" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th><input type="checkbox" id="ckAll"></th>
                                        <th>Akun</th>
                                        <th>Vol</th>
                                        <th>Satuan</th>
                                        <th>Indikator</th>
                                        <th>Tarif</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detailRba as $drba)
                                        <tr>
                                            <th><input class="ckItem" type="checkbox" value="{{ $drba->id_detail_rba }}">
                                            </th>
                                            <td>{{ $drba->no_akun }} - {{ $drba->nm_akun }}</td>
                                            <td>{{ $drba->vol }}</td>
                                            <td>{{ $drba->satuan }}</td>
                                            <td>{{ $drba->indikator }}</td>
                                            <td>{{ $drba->tarif }}</td>
                                            <td>{{ $drba->total }}</td>
                                            <td>{{ $drba->a_setuju }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addDetailRbaMdl" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Detail Rencana Anggaran Biaya</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddDetailRbaMdl">
                        <div class="row mb-2">
                            <div class="col">
                                <select id="id_akunaddDetailRbaMdl" class="form-control">
                                    <option value="">-- Akun --</option>
                                    @foreach ($akun as $akn)
                                        <option value="{{ $akn->id_akun }}">{{ $akn->no_akun }} {{ $akn->nm_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" placeholder="Vol" id="voladdDetailRbaMdl">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Satuan" id="satuanaddDetailRbaMdl">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" placeholder="Indikator"
                                    id="indikatoraddDetailRbaMdl">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <input type="number" class="form-control" placeholder="Tarif" id="tarifaddDetailRbaMdl">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" placeholder="Total" id="totaladdDetailRbaMdl">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnaddDetailRbaMdl" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush
@push('js')
    <script src="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#ckAll").change(function() {
                if (this.checked) {
                    $('.ckItem').prop('checked', true);
                } else {
                    $('.ckItem').prop('checked', false);
                }
            });

            $("#addDetailRba").click(function() {
                $('#addDetailRbaMdl').modal('show');
            });

            $("#btnaddDetailRbaMdl").click(function() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('kepalabagian.KegiatanMonitoring.apiCreateDetailRba') }}",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_rba: "{!! $IdRba !!}",
                        id_akun: $('#id_akunaddDetailRbaMdl').val(),
                        vol: $('#voladdDetailRbaMdl').val(),
                        satuan: $('#satuanaddDetailRbaMdl').val(),
                        indikator: $('#indikatoraddDetailRbaMdl').val(),
                        tarif: $('#tarifaddDetailRbaMdl').val(),
                        total: $('#totaladdDetailRbaMdl').val(),
                    },
                    beforeSend: function() {
                        $(this).prop("disabled", true);
                    },
                }).done(function(res) {
                    if (res.status) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Tambah Detail RAB Berhasil',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Tambah Detail RAB Gagal',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Tambah Detail RAB Gagal',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    console.log(res);
                    $(this).prop("disabled", false);
                });
            });

            $("#deleteDetailRba").click(function() {
                $("#deleteDetailRba").prop("disabled", true);
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
                            url: "{!! route('kepalabagian.KegiatanMonitoring.apiDeleteDetailRba') !!}",
                            data: {
                                _token: "{!! csrf_token() !!}",
                                id_detail_rba: getId()
                            }
                        }).done(function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Data Berhasil Dihapus!',
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            location.reload();
                        }).fail(function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'erorr',
                                title: 'Data Gagal Dihapus!',
                                showConfirmButton: true,
                            });
                            $("#deleteDetailRba").prop("disabled", false);
                        });
                    } else {
                        $("#deleteDetailRba").prop("disabled", false);
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
    </script>
@endpush
