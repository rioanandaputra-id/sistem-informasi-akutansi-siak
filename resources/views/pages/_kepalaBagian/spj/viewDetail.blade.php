@extends('layouts.adminlteMaster')
@section('content')
    @php
        $idSpj = $spj->id_spj;
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
                                        <b>DETAIL KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                        <button onclick="history.back()" class="btn btn-sm noborder btn-light"><i
                                                class="fas fa-chevron-circle-left"></i>
                                            Kembali</button>
                                    </div>
                                </div>
                            </div>
                            <table class="mb-3" style="width: 100%">
                                <tbody>
                                    @foreach ($bku as $bk)
                                        <tr>
                                            <th colspan="3">Info SPJ</th>
                                        </tr>
                                        <tr>
                                            <td style="width: 200px">Pengajuan</td>
                                            <td style="width: 10px;">:</td>
                                            <td>Pelaksanaan Ke-{{ $bk->urutan_laksana_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kegiatan</td>
                                            <td>:</td>
                                            <td>{{ $bk->nm_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Program</td>
                                            <td>:</td>
                                            <td>{{ $bk->nm_program }}</td>
                                        </tr>
                                        <tr>
                                            <td>Misi</td>
                                            <td>:</td>
                                            <td>{{ $bk->nm_misi }}</td>
                                        </tr>
                                        <tr class="bg-purple">
                                            <td>Total Anggaran</td>
                                            <td>:</td>
                                            <td>{{ number_to_currency_without_rp($bk->total_masuk - $bk->total_keluar, 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row bg-success p-2 mb-3">
                                <div class="col">
                                    <div class="float-left">
                                        <b>RINCIAN DETAIL</b>
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbRinciSpj" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th width="5%"></th>
                                        <th>Akun</th>
                                        <th>Uraian</th>
                                        <th class="text-right">Anggaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detLaks as $no=>$dlaks)
                                    <tr>
                                        <td><button class="btn btn-primary btn-sm addDetailSpj" data-id="{{ $dlaks->id_detail_laksana_kegiatan }}" data-uraian="{{ $dlaks->no_akun . ' - ' . $dlaks->nm_akun }}" data-total="{{ $dlaks->total }}"><i class="fas fa-plus"></i></button></td>
                                        <td>{{ $dlaks->no_akun }}</td>
                                        <td>{{ $dlaks->nm_akun }}</td>
                                        <td class="text-right">{{ number_to_currency_without_rp($dlaks->total, 0) }}</td>
                                    </tr>

                                    @php
                                        $spj = DB::select("
                                            SELECT
                                                dspj.id_detail_spj,
                                                dspj.total,
                                                akun.no_akun,
                                                akun.nm_akun
                                            FROM
                                                detail_spj AS dspj
                                                JOIN akun ON akun.id_akun=dspj.id_akun
                                            WHERE
                                                dspj.id_detail_laksana_kegiatan='".$dlaks->id_detail_laksana_kegiatan."'
                                        ");
                                    @endphp
                                    @foreach($spj AS $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->no_akun }}</td>
                                        <td>{{ $item->nm_akun }}</td>
                                        <td class="text-right">{{ number_to_currency_without_rp($item->total, 0) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-secondary"><td colspan="4"></td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addDetailSpjMdl" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Detail SPJ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddDetailSpjMdl" enctype="multipart/form-data">
                        <div class="row mb-2">
                            <div class="col">
                                <input type="hidden" id="id_detail_laksana_kegiatanaddDetailSpjMdl">
                                <label for="akunaddDetailSpjMdl">Akun: <i class="text-red">*</i></label>
                                <input type="text" class="form-control" id="akunaddDetailSpjMdl" readonly>
                            </div>
                            <!-- <div class="col">
                                <label for="detailtotaladdDetailSpjMdl">Total: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="detailtotaladdDetailSpjMdl" readonly>
                            </div> -->
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="id_akunaddDetailSpjMdl">Akun: <i class="text-red">*</i></label>
                                <select id="id_akunaddDetailSpjMdl" class="form-control">
                                    <option value="">---</option>
                                    @foreach ($akun as $akn)
                                        <option value="{{ $akn->id_akun }}">{{ $akn->no_akun }} {{ $akn->nm_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="id_akunaddDetailSpjMdl">Realisasi: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="totaladdDetailSpjMdl"
                                    value="0">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="id_akunaddDetailSpjMdl">Bukti: <i class="text-red">*</i></label>
                                <input type="file" class="form-control" id="buktiaddDetailSpjMdl">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnaddDetailSpjMdl" class="btn btn-primary">Tambah</button>
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
        $(".addDetailSpj").click(function() {
            var id = $(this).data('id');
            var uraian = $(this).data('uraian');
            // var total = $(this).data('total');
            $('#addDetailSpjMdl').modal('show');
            $('#id_detail_laksana_kegiatanaddDetailSpjMdl').val(id);
            $('#akunaddDetailSpjMdl').val(uraian);
            // $('#detailtotaladdDetailSpjMdl').val(total);
        });

        $("#btnaddDetailSpjMdl").click(function() {
            $.ajax({
                type: 'POST',
                url: "{{ route('kepalabagian.SPJKegiatan.apiCreateDetailSpj') }}",
                data: {
                    _token: "{!! csrf_token() !!}",
                    id_spj: '{{ $idSpj }}',
                    id_detail_laksana_kegiatan: $('#id_detail_laksana_kegiatanaddDetailSpjMdl').val(),
                    id_akun: $('#id_akunaddDetailSpjMdl').val(),
                    total: $('#totaladdDetailSpjMdl').val(),
                    dokumen: $('#buktiaddDetailSpjMdl').val(),
                },
                beforeSend: function() {
                    $(this).prop("disabled", true);
                },
            }).done(function(res) {
                if (res.status) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Tambah Rincian SPJ Berhasil',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Tambah Rincian SPJ Gagal',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            }).fail(function(res) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Tambah Rincian SPJ Kegiatan Gagal',
                    showConfirmButton: false,
                    timer: 1000,
                });
                console.log(res);
                $(this).prop("disabled", false);
            });
        });
    });
</script>
@endpush