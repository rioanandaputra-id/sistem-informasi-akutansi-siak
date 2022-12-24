@extends('layouts.adminlteMaster')
@section('breadcrumb')
@endsection

@section('content')
@php
    $lockedBtn = '';
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
                                        <b>DETAIL PELAKSANAAN KEGIATAN RUTIN</b>
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
                                $lockedBtn = $kgt->tgl_verif_kabag_keuangan != null ? 'disabled' : '';
                            @endphp
                                <table class="mb-3">
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
                                <table class="mb-3">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Info Pelaksanaan Kegiatan Rutin</th>
                                        </tr>
                                        <tr>
                                            <td style="min-width: 200px">Urutan Pengajuan</td>
                                            <td>:</td>
                                            <td>Pengajuan Ke-{{ $kgt->urutan_laksana_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Pengajuan</td>
                                            <td>:</td>
                                            <td>{{ tglWaktuIndonesia($kgt->tgl_ajuan) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Pelaksanaan</td>
                                            <td>:</td>
                                            <td>{{ tglWaktuIndonesia($kgt->waktu_pelaksanaan) ?? '-' }} <span
                                                    class="mr-2 ml-2">-</span>
                                                {{ tglWaktuIndonesia($kgt->waktu_selesai) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status Verifikasi</td>
                                            <td>:</td>
                                            <td>{!! status_verification_color($kgt->a_verif_kabag_keuangan) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Verifikasi</td>
                                            <td>:</td>
                                            <td>{{ tglWaktuIndonesia($kgt->tgl_verif_kabag_keuangan) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan Verifikasi</td>
                                            <td>:</td>
                                            <td>{{ $kgt->catatan ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="bg-purple mb-3">
                                    <strong>Total Anggaran Tersisa <span style="margin-left: 40px;">:</span>
                                        {{ number_to_currency_without_rp($kgt->total_anggaran_tersedia - $kgt->total_anggaran_terpakai) }}</strong>
                                </div>
                            @endforeach
                            <div class="row bg-success p-2 mb-3">
                                <div class="col">
                                    <div class="float-left">
                                        <b>RINCIAN DETAIL PELAKSANAAN KEGIATAN RUTIN</b>
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbDetailLaks" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th>No. Akun</th>
                                        <th>Uraian</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tfDetailLaks = 0;
                                    @endphp
                                    @foreach ($detailLaks as $dlk)
                                        <tr>
                                            <td>{{ $dlk->no_akun }}</td>
                                            <td>{{ $dlk->nm_akun }}</td>
                                            <td class="text-right">{{ number_to_currency_without_rp($dlk->total) }}</td>
                                        </tr>
                                        @php
                                            $tfDetailLaks += $dlk->total;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-info">
                                    <th colspan="2">Total</th>
                                    <th class="text-right">{{ number_to_currency_without_rp($tfDetailLaks) }}</th>
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
                    <h5 class="modal-title">Verifikasi Kegiatan Rutin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formVerifMdl">
                        <select id="a_verif_kabag_keuanganVerifMdl" class="form-control mb-2">
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
            if ("{!! $lockedBtn !!}" == 'disabled') {
                $('#verif').prop("disabled", true);
            }

            $("#verif").click(function() {
                $('#VerifMdl').modal('show');
            });

            $("#btnVerifMdl").click(function() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('bendaharapengeluaran.KegiatanRutinPelaksana.apiUpdate') }}",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_laksana_kegiatan: ["{!! request()->get('id_laksana_kegiatan') !!}"],
                        a_verif_kabag_keuangan: $('#a_verif_kabag_keuanganVerifMdl').val(),
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
                            title: 'Verifikasi Pelaksanaan Kegiatan Rutin Berhasil',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Verifikasi Pelaksanaan Kegiatan Rutin Gagal',
                            showConfirmButton: true,
                        });
                        console.log(res);
                        $(this).prop("disabled", false);
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Verifikasi Pelaksanaan Kegiatan Rutin Gagal',
                        showConfirmButton: true,
                    });
                    console.log(res);
                    $(this).prop("disabled", false);
                });
            });
        });
    </script>
@endpush
