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
                                        <b>DETAIL MONITORING BKU</b>
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
                                        <td style="min-width: 100px">Pengajuan</td>
                                        <td>:</td>
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
                                        <td>{{ number_to_currency_without_rp($bk->masuk, 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row bg-success p-2 mb-3">
                                <div class="col">
                                    <div class="float-left">
                                        <b>RINCIAN DETAIL MONITORING BKU</b>
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbRincianBku" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th>Akun</th>
                                        <th>Uraian</th>
                                        <th class="text-right">Masuk</th>
                                        <th class="text-right">Keluar</th>
                                        <th class="text-right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
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
    <script>
        $(document).ready(function() {});
    </script>
@endpush
