@extends('layouts.adminlteMaster')
@section('breadcrumb')
@endsection

@section('content')
    @php
        $lockedBtnVerif = '';
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
                                        <b>RENCANA BISNIS & ANGGARAN KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                        <button onclick="history.back()" class="btn btn-sm noborder btn-light"><i
                                                class="fas fa-chevron-circle-left"></i>
                                            Kembali</button>
                                            <button id="verif" class="btn btn-sm noborder btn-light ml-2"><i
                                                class="fas fa-check-circle"></i>
                                            Verifikasi</button>
                                    </div>
                                </div>
                            </div>
                            @foreach ($kegiatan as $kgt)
                                @php
                                    $lockedBtnVerif = $kgt->rba_tgl_verif_rba != null ? 'disabled' : '';
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
                                            <td style="min-width: 200px">Waktu Pengajuan</td>
                                            <td>:</td>
                                            <td>{{ tglWaktuIndonesia($kgt->tgl_submit) ?? '-' }}</td>
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
                                            <td>{!! status_verification_color($kgt->kdiv_a_verif_rba) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu</td>
                                            <td>:</td>
                                            <td>{{ tglWaktuIndonesia($kgt->kdiv_tgl_verif_rba) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan</td>
                                            <td>:</td>
                                            <td>{{ $kgt->kdiv_catatan ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="mt-3">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Verifikasi Kepala UDD</th>
                                        </tr>
                                        <tr>
                                            <td style="min-width: 200px">Status</td>
                                            <td>:</td>
                                            <td>{!! status_verification_color($kgt->rba_a_verif_rba) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu</td>
                                            <td>:</td>
                                            <td>{{ tglWaktuIndonesia($kgt->rba_tgl_verif_rba) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan</td>
                                            <td>:</td>
                                            <td>{{ $kgt->rba_catatan_verif_rba ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="mt-3 mb-4">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Verifikasi Kepala Pengurus Wilayah</th>
                                        </tr>
                                        <tr>
                                            <td style="min-width: 200px">Status</td>
                                            <td>:</td>
                                            <td>{!! status_verification_color($kgt->rba_a_verif_wilayah) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu</td>
                                            <td>:</td>
                                            <td>{{ tglWaktuIndonesia($kgt->rba_tgl_verif_wilayah) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan</td>
                                            <td>:</td>
                                            <td>{{ $kgt->rba_catatan_verif_wilayah ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endforeach
                            <div class="row bg-success p-2 mb-4">
                                <div class="col">
                                    <div class="float-left">
                                        <b>RINCIAN RENCANA BISNIS & ANGGARAN KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbDetailRba" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th>Akun</th>
                                        <th>Satuan</th>
                                        <th class="text-right">Indikator</th>
                                        <th class="text-right">Volume</th>
                                        <th class="text-right">Tarif</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tbDetailRbaTotal = 0;
                                    @endphp
                                    @foreach ($detailRba as $drba)
                                        <tr>
                                                <td>{{ $drba->no_akun }} - {{ $drba->nm_akun }}</td>
                                            <td>{{ $drba->satuan }}</td>
                                            <td class="text-right">{{ $drba->indikator }}</td>
                                            <td class="text-right">{{ $drba->vol }}</td>
                                            <td class="text-right">{{ number_to_currency_without_rp($drba->tarif, 0) }}
                                            </td>
                                            <td class="text-right">{{ number_to_currency_without_rp($drba->total, 0) }}
                                            </td>
                                        </tr>
                                        @php
                                            $tbDetailRbaTotal += $drba->total;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-info">
                                    <tr>
                                        <th colspan="5">Total</th>
                                        <th class="text-right">{{ number_to_currency_without_rp($tbDetailRbaTotal, 0) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-4">
                        <div class="col">
                            <div class="row bg-success p-2 mb-4">
                                <div class="col">
                                    <div class="float-left">
                                        <b>PELAKSANAAN KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbLaksKegiatan" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th>Urutan Pengajuan</th>
                                        <th>Status Verifikasi</th>
                                        <th>Waktu Pengajuan</th>
                                        <th class="text-center">Waktu Pelaksanaan</th>
                                        <th class="text-right">Total Anggaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tbLaksKegiatanTotal = 0;
                                    @endphp
                                    @foreach ($laksKegiatan as $lkgt)
                                        <tr>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="javascript:" type="button" data-toggle="dropdown">
                                                        Pengajuan Ke-{{ $lkgt->urutan_laksana_kegiatan }}
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item mb-2"
                                                            href="{{ route('kepalauud.KegiatanMonitoring.viewGetAllLaksanaDetail') }}?id_laksana_kegiatan={{ $lkgt->id_laksana_kegiatan }}">Detail
                                                            Pelaksanaan Kegiatan</a>
                                                    </div>
                                            </td>
                                            <td>{!! status_verification_color($lkgt->a_verif_kabag_keuangan) !!}</td>
                                            <td>{{ tglWaktuIndonesia($lkgt->tgl_ajuan) ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ tglWaktuIndonesia($lkgt->waktu_pelaksanaan) }} <span
                                                    class="ml-2 mr-2">-</span>
                                                {{ tglWaktuIndonesia($lkgt->waktu_selesai) }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_to_currency_without_rp($lkgt->total_anggaran, 0) }}</td>
                                        </tr>
                                        @php
                                            if (!str_contains($lkgt->a_verif_kabag_keuangan, 'Ditolak')) {
                                                $tbLaksKegiatanTotal += $lkgt->total_anggaran;
                                            }
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-info">
                                    <tr>
                                        <th colspan="4">Total</th>
                                        <th class="text-right">
                                            {{ number_to_currency_without_rp($tbLaksKegiatanTotal, 0) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="VerifMdl" class="modal"  role="dialog">
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
            if ("{!! $lockedBtnVerif !!}" == 'disabled') {
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
