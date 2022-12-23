@extends('layouts.adminlteMaster')
@section('content')
    @php
        $idSpj = $spj->id_spj;
        $lockBtn = $spj->tgl_verif_kabag_keuangan ? 'disabled' : '';
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
                                        <button id="verif" class="btn btn-sm noborder btn-light ml-2"><i
                                                class="fas fa-sign-in-alt"></i>
                                            Verifikasi</button>
                                    </div>
                                </div>
                            </div>
                            <table class="mb-3" style="width: 100%">
                                <tbody>
                                    @foreach ($bku as $bk)
                                        @php
                                            $idDivisi = $bk->id_divisi;
                                            $idLaksanaKegiatan = $bk->id_laksana_kegiatan;
                                        @endphp
                                        <tr>
                                            <th colspan="3">Info SPJ</th>
                                        </tr>
                                        <tr>
                                            <td style="width: 200px">Pengajuan</td>
                                            <td style="width: 10px;">:</td>
                                            <td>Pelaksanaan Ke-{{ $bk->urutan_laksana_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Bagian</td>
                                            <td>:</td>
                                            <td>{{ $bk->nm_divisi }}</td>
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
                                            <td>{{ number_to_currency_without_rp($bk->total_masuk, 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table class="my-3">
                                <tbody>
                                    <tr>
                                        <th colspan="3">Verifikasi</th>
                                    </tr>
                                    <tr>
                                        <td style="min-width: 200px">Status</td>
                                        <td>:</td>
                                        <td>{!! status_verification_color($spj->a_verif_kabag_keuangan) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Waktu</td>
                                        <td>:</td>
                                        <td>{{ tglWaktuIndonesia($spj->tgl_verif_kabag_keuangan) ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Catatan</td>
                                        <td>:</td>
                                        <td>{{ $spj->catatan ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row bg-success p-2 mb-3">
                                <div class="col">
                                    <div class="float-left">
                                        <b>RINCIAN DETAIL</b>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbRinciSpj" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th>Akun</th>
                                        <th>Uraian</th>
                                        <th class="text-right">Anggaran</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detLaks as $no=>$dlaks)
                                    <tr>
                                        <td>{{ $no+1 }}</td>
                                        <td>{{ $dlaks->no_akun }}</td>
                                        <td>{{ $dlaks->nm_akun }}</td>
                                        <td class="text-right">{{ number_to_currency_without_rp($dlaks->total, 0) }}</td>
                                        <td class="text-center"></td>
                                    </tr>

                                    @php
                                        $dspj = DB::select("
                                            SELECT
                                                dspj.id_detail_spj,
                                                dspj.id_spj,
                                                dspj.id_detail_laksana_kegiatan,
                                                dspj.id_akun,
                                                dspj.total,
                                                CONCAT(akun.elemen, akun.sub_elemen, akun.jenis, akun.no_akun) AS no_akun,
                                                akun.nm_akun,
                                                dok.nm_dokumen
                                            FROM
                                                detail_spj AS dspj
                                                JOIN akun ON akun.id_akun=dspj.id_akun
                                                JOIN dokumen AS dok ON dok.id_dokumen=dspj.id_dokumen
                                            WHERE
                                                dspj.id_detail_laksana_kegiatan='".$dlaks->id_detail_laksana_kegiatan."'
                                                AND dspj.deleted_at IS NULL
                                        ");
                                    @endphp
                                    @foreach($dspj AS $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->no_akun }}</td>
                                        <td>{{ $item->nm_akun }}</td>
                                        <td class="text-right">{{ number_to_currency_without_rp($item->total, 0) }}</td>
                                        <td><a class="btn btn-link btn-xs" href="javascript:" onclick="modalShowDetailSpj('{!! asset('storage/uploads/'.$item->nm_dokumen) !!}')">LIHAT BUKTI</a></td>
                                    </tr>
                                    @endforeach
                                    @if(count($detLaks) > 1)
                                    <tr class="bg-secondary"><td colspan="5"></td></tr>
                                    @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-info">
                                    <tr>
                                        <th colspan="3">TOTAL REALISASI</th>
                                        <th class="text-right">{{ number_to_currency($totDetSpj, 0) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="showDetailSpjMdl" class="modal"  role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Dokumen SPJ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="#" class="img-fluid" id="buktiShowDetailSpjMdl" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div id="verifMdl" class="modal"  role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi SPJ Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formVerifMdl">
                        <select id="a_verifMdl" class="form-control mb-2">
                            <option value="">-- Verifikasi --</option>
                            <option value="2">Disetujui</option>
                            <option value="3">Ditolak</option>
                        </select>
                        <textarea id="catatanMdl" cols="30" rows="10" class="form-control" placeholder="Catataan.."></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnMdl" class="btn btn-primary">Verifikasi</button>
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
        if ("{!! $lockBtn !!}" == 'disabled') {
            $('#verif').prop("disabled", true);
        }
        
        $("#verif").click(function() {
            $('#verifMdl').modal('show');
        });

        $("#btnMdl").click(function() {
            $.ajax({
                type: 'POST',
                url: "{{ route('kepalabagian.SPJKegiatanMonitoring.apiUpdate') }}",
                data: {
                    _token: "{!! csrf_token() !!}",
                    id_spj: "{!! $idSpj !!}",
                    a_verif: $('#a_verifMdl').val(),
                    catatan: $('#catatanMdl').val(),
                    id_divisi: "{!! $idDivisi !!}",
                    id_laksana_kegiatan: "{!! $idLaksanaKegiatan !!}",
                },
                beforeSend: function() {
                    $(this).prop("disabled", true);
                },
            }).done(function(res) {
                if (res.status) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Verifikasi SPJ Kegiatan Berhasil',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Verifikasi SPJ Kegiatan Gagal',
                        showConfirmButton: true,
                    });
                    console.log(res);
                    $(this).prop("disabled", false);
                }
            }).fail(function(res) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Verifikasi SPJ Kegiatan Gagal',
                    showConfirmButton: true,
                });
                console.log(res);
                $(this).prop("disabled", false);
            });
        });
    });

    function getIdDetailSpj() {
        let id = [];
        $('.ckItemDetailSpj:checked').each(function() {
            id.push($(this).val());
        });
        return id;
    }

    function modalShowDetailSpj(p1) {
        $("#buktiShowDetailSpjMdl").attr('src', '#');
        $("#buktiShowDetailSpjMdl").attr('src', p1);
        $('#showDetailSpjMdl').modal('show');
    }
</script>
@endpush