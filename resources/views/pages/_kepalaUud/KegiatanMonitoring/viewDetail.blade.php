@extends('layouts.adminlteMaster')
@section('breadcrumb')
@endsection

@section('content')
    @php
        $lockedVerif = '';
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
                                        <button id="verif" class="btn btn-sm noborder btn-light ml-2"><i
                                                class="fas fa-sign-in-alt"></i>
                                            Verifikasi</button>
                                    </div>
                                </div>
                            </div>
                            @foreach ($kegiatan as $kgt)
                                @php
                                    $lockedVerif = $kgt->rba_tgl_verif_rba != null ? 'disabled' : '';
                                    $IdRba = $kgt->id_rba;
                                @endphp
                                <table>
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Info Kegiatan</th>
                                        </tr>
                                        <tr>
                                            <td style="min-width: 200px">Divisi</td>
                                            <td>:</td>
                                            <td>{{ $kgt->nm_divisi }}</td>
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
                                        <tr>
                                            <td style="min-width: 200px">Waktu Simpan</td>
                                            <td>:</td>
                                            <td>{{ $kgt->tgl_submit }}</td>
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
                                            <td>{{ $kgt->kdiv_tgl_verif_rba }}</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan</td>
                                            <td>:</td>
                                            <td>{{ $kgt->kdiv_catatan }}</td>
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
                                            <td>{{ $kgt->rba_tgl_verif_rba }}</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan</td>
                                            <td>:</td>
                                            <td>{{ $kgt->rba_catatan_verif_rba }}</td>
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
                                            <td>{{ $kgt->rba_tgl_verif_wilayah }}</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan</td>
                                            <td>:</td>
                                            <td>{{ $kgt->rba_catatan_verif_wilayah }}</td>
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
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbDetailRba" style="width: 100%">
                                <thead class="bg-success">
                                    <tr>
                                        <th><input type="checkbox" class="ckAllDetailRba"></th>
                                        <th>Akun</th>
                                        <th>Satuan</th>
                                        <th>Indikator</th>
                                        <th>Volume</th>
                                        <th>Tarif</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tbDetailRbaTotal = 0;
                                    @endphp
                                    @foreach ($detailRba as $drba)
                                        <tr>
                                            <th><input class="ckItemDetailRba" type="checkbox"
                                                    value="{{ $drba->id_detail_rba }}">
                                            </th>
                                            <td>{{ $drba->no_akun }} - {{ $drba->nm_akun }}</td>
                                            <td>{{ $drba->satuan }}</td>
                                            <td>{{ $drba->indikator }}</td>
                                            <td>{{ $drba->vol }}</td>
                                            <td>{{ $drba->tarif }}</td>
                                            <td>{{ $drba->total }}</td>
                                        </tr>
                                        @php
                                            $tbDetailRbaTotal += $drba->total;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-success">
                                    <tr>
                                        <th></th>
                                        <th colspan="5">Total</th>
                                        <th>{{ $tbDetailRbaTotal }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-4">
                        <div class="col">
                            <div class="row bg-purple p-2 mb-4">
                                <div class="col">
                                    <div class="float-left">
                                        <b>DETAIL PELAKSANAAN KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbDetailLaksKegiatan" style="width: 100%">
                                <thead class="bg-purple">
                                    <tr>
                                        <th><input type="checkbox" class="ckAllDetailLaksKegiatan"></th>
                                        <th>Akun</th>
                                        <th>Tgl. Ajuan</th>
                                        <th>Tgl. Verif</th>
                                        <th>Catatan</th>
                                        <th>Wkt. Laks</th>
                                        <th>Wkt. Selesai</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tbDetailLaksKegiatanTotal = 0;
                                    @endphp
                                    @foreach ($detailLaksKegiatan as $dlk)
                                        <tr>
                                            <th><input class="ckItemDetailLaksKegiatan" type="checkbox"
                                                    value="{{ $dlk->id_laksana_kegiatan }}">
                                            </th>
                                            <td>{{ $dlk->no_akun }} - {{ $dlk->nm_akun }}</td>
                                            <td>{{ $dlk->tgl_ajuan }}</td>
                                            <td>{{ $dlk->tgl_verif_bend_kegiatan }}</td>
                                            <td>{{ $dlk->catatan }}</td>
                                            <td>{{ $dlk->waktu_pelaksanaan }}</td>
                                            <td>{{ $dlk->waktu_selesai }}</td>
                                            <td>{{ $dlk->tahun }}</td>
                                            <td>{{ $dlk->a_verif_bend_kegiatan }}</td>
                                            <td>{{ $dlk->jumlah }}</td>
                                            <td>{{ $dlk->total }}</td>
                                        </tr>
                                        @php
                                            if ($dlk->a_verif_bend_kegiatan != 'Ditolak Bend. Kegiatan') {
                                                $tbDetailLaksKegiatanTotal += $dlk->total;
                                            }
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-purple">
                                    <tr>
                                        <th></th>
                                        <th colspan="9">Total</th>
                                        <th>{{ $tbDetailLaksKegiatanTotal }}</th>
                                    </tr>
                                </tfoot>
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
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush
@push('js')
    <script src="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ("{!! $lockedVerif !!}" == 'disabled') {
                $('#verif').prop("disabled", true);
            }

            $("#verif").click(function() {
                $('#VerifMdl').modal('show');
            });

            $("#btnVerifMdl").click(function() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('kepalauud.kegiatanMonitoring.apiUpdate') }}",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_kegiatan_divisi: ["{{ request()->get('id_kegiatan_divisi') }}"],
                        a_verif_rba: $('#a_verif_rbaVerifMdl').val(),
                        catatan_verif_rba: $('#catatanVerifMdl').val(),
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
                        location.reload();
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
    </script>
@endpush
