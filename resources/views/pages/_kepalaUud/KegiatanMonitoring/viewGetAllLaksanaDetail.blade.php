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
                    <div class="row">
                        <div class="col">
                            <div class="row bg-success p-2 mb-3">
                                <div class="col">
                                    <div class="float-left">
                                        <b>DETAIL PELAKSANAAN KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                        <button onclick="history.back()" class="btn btn-sm noborder btn-light"><i
                                                class="fas fa-chevron-circle-left"></i>
                                            Kembali</button>
                                    </div>
                                </div>
                            </div>
                            @foreach ($kegiatan as $kgt)
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
                                <table class="mb-3">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Info Pelaksanaan Kegiatan</th>
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
                                            <td>{!!status_verification_color($kgt->a_verif_kabag_keuangan) ?? '-' !!}</td>
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
                                        <b>RINCIAN DETAIL PELAKSANAAN KEGIATAN</b>
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
@endsection


@push('css')
@endpush
@push('js')
@endpush
