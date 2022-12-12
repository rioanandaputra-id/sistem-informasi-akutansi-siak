@extends('layouts.adminlteMaster')
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
                                            <th colspan="3">Info BKU</th>
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
                            <table class="table table-striped teble-bordered" id="tbRincianBku" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Akun</th>
                                        <th>Uraian</th>
                                        <th class="text-right">Masuk</th>
                                        <th class="text-right">Keluar</th>
                                        <th class="text-right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rincBku as $rbk)
                                        <tr>
                                            <td>{{ tglWaktuIndonesia($rbk->tanggal) }}</td>
                                            <td>{{ $rbk->no_akun ?? '-'}}</td>
                                            <td>{{ $rbk->nm_akun ?? '-'}}</td>
                                            <td class="text-right">{{ number_to_currency_without_rp($rbk->masuk, 0) }}</td>
                                            <td class="text-right">{{ number_to_currency_without_rp($rbk->keluar, 0) }}</td>
                                            <td class="text-right">{{ number_to_currency_without_rp($rbk->saldo, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <div class="row bg-success p-2 mb-3">
                                <div class="col">
                                    <div class="float-left">
                                        <b>RINCIAN SPJ KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                        <button id="addDetailLaks" class="btn btn-sm noborder btn-light"><i
                                                class="fas fa-plus-circle"></i>
                                            Tambah</button>
                                        <button id="deleteDetailLaks" class="btn btn-sm noborder btn-light ml-2"><i
                                                class="fas fa-trash"></i>
                                            Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbDetailLaks" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th><input type="checkbox" class="ckAllDetailLaks"></th>
                                        <th>No. Akun</th>
                                        <th>Uraian</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tfoot class="bg-info">
                                    <th></th>
                                    <th colspan="2">Total</th>
                                    <th class="text-right"></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
