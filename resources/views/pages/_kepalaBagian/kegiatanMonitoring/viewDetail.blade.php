@extends('layouts.adminlteMaster')
@section('breadcrumb')
@endsection

@section('content')
    @php
        $lockBtnDetailRba = '';
        $lockBtnLaksana = '';
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
                                        <b>RENCANA BISNIS & ANGGARAN KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                        <button onclick="history.back()" class="btn btn-sm noborder btn-light"><i
                                                class="fas fa-chevron-circle-left"></i>
                                            Kembali</button>
                                        <button id="locked" class="btn btn-sm noborder btn-light ml-2"><i
                                                class="fas fa-sign-in-alt"></i>
                                            Ajukan</button>
                                    </div>
                                </div>
                            </div>
                            @foreach ($kegiatan as $kgt)
                                @php
                                    $lockBtnDetailRba = $kgt->tgl_submit ? 'disabled' : '';
                                    $lockBtnLaksana = $kgt->rba_a_verif_wilayah == 'Disetujui Kepala Pengurus Wilayah' ? '' : 'disabled';
                                    $IdRba = $kgt->id_rba;
                                    $lockBtnImport = ($kgt->nm_divisi=="Bagian Pengelolaan Darah" AND empty($kgt->tgl_submit)) ? '' : 'disabled';
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
                                        <button id="importDetailRba"
                                            class="btn btn-sm noborder btn-light"><i class="fas fa-file-import"></i>
                                            Import</button>
                                        <button id="addDetailRba"
                                            class="btn btn-sm noborder btn-light ml-2"><i class="fas fa-plus-circle"></i>
                                            Tambah</button>
                                        <button id="deleteDetailRba"
                                            class="btn btn-sm noborder btn-light ml-2"><i class="fas fa-trash"></i>
                                            Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbDetailRba" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th><input type="checkbox" class="ckAllDetailRba"></th>
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
                                            <th><input class="ckItemDetailRba" type="checkbox"
                                                    value="{{ $drba->id_detail_rba }}"></th>
                                            @if ($lockBtnDetailRba == 'disabled')
                                                <td>{{ $drba->no_akun }} - {{ $drba->nm_akun }}</td>
                                            @else
                                                <td><a href="javascript:"
                                                    onclick="modalUpdateDetailRba(
                                                        '{!! $drba->id_detail_rba !!}',
                                                        '{!! $drba->id_akun !!}',
                                                        '{!! $drba->satuan !!}',
                                                        '{!! $drba->indikator !!}',
                                                        '{!! $drba->vol !!}',
                                                        '{!! $drba->tarif !!}',
                                                        '{!! $drba->total !!}',
                                                    )">{{ $drba->no_akun }} - {{ $drba->nm_akun }}</a></td>
                                            @endif
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
                                        <th></th>
                                        <th colspan="5">Total</th>
                                        <th class="text-right">{{ number_to_currency_without_rp($tbDetailRbaTotal, 0) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <hr>
                    @if($checkKdiv->jumlah>0)
                    @if($kegiatan[0]->a_verif_wilayah == '2')
                    <div class="row mt-4">
                        <div class="col">
                            <div class="row bg-danger p-2 mb-4">
                                <div class="col">
                                    <div class="text-center">
                                        <b>PERIODE PELAKSANAAN KEGIATAN BELUM DIMULAI!</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @else
                    @if($kegiatan[0]->nm_program != 'Non Program (Pendapatan)' || \Auth::user()->can('bendpenerimaan'))
                    <div class="row mt-4">
                        <div class="col">
                            <div class="row bg-success p-2 mb-4">
                                <div class="col">
                                    <div class="float-left">
                                        <b>PELAKSANAAN KEGIATAN</b>
                                    </div>
                                    <div class="float-right">
                                        <button id="addLaksKegiatan"
                                            class="btn btn-sm noborder btn-light"><i class="fas fa-plus-circle"></i>
                                            Tambah</button>
                                        <button id="deleteLaksKegiatan"
                                            class="btn btn-sm noborder btn-light ml-2"><i class="fas fa-trash"></i>
                                            Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped teble-bordered" id="tbLaksKegiatan" style="width: 100%">
                                <thead class="bg-info">
                                    <tr>
                                        <th style="width: 5px;"><input type="checkbox" class="ckAllLaksKegiatan"></th>
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
                                            <td><input class="ckItemLaksKegiatan" type="checkbox"
                                                    value="{{ $lkgt->id_laksana_kegiatan }}"></td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="javascript:" type="button" data-toggle="dropdown">
                                                        Pengajuan Ke-{{ $lkgt->urutan_laksana_kegiatan }}
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item mb-2"
                                                            href="{{ route('kepalabagian.KegiatanMonitoring.viewGetAllLaksanaDetail') }}?id_laksana_kegiatan={{ $lkgt->id_laksana_kegiatan }}">Detail
                                                            Pelaksanaan Kegiatan</a>
                                                        @if ($lkgt->tgl_ajuan == null)
                                                        <a class="dropdown-item" href="javascript:"
                                                            onclick="modalUpdateDetailLaks(
                                                                    '{!! $lkgt->id_laksana_kegiatan !!}',
                                                                    '{!! $lkgt->tahun !!}',
                                                                    '{!! $lkgt->tgl_ajuan !!}',
                                                                    '{!! $lkgt->waktu_pelaksanaan !!}',
                                                                    '{!! $lkgt->waktu_selesai !!}',
                                                                    '{!! $lkgt->lokasi !!}',
                                                                )">Ubah
                                                            Data</a>
                                                        @endif
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
                                        <th></th>
                                        <th colspan="4">Total</th>
                                        <th class="text-right">
                                            {{ number_to_currency_without_rp($tbLaksKegiatanTotal, 0) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="importDetailRbaMdl" class="modal" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Detail RENCANA BISNIS & ANGGARAN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formimportDetailRbaMdl" enctype="multipart/form-data">
                        <div class="row mb-2">
                            <div class="col">
                                <h5>Instruksi Import Data:</h5>
                                <ol>
                                    <li>Silahkan download template import data <a href="{{ route('kepalabagian.KegiatanMonitoring.apiGetAkun') }}" class="btn btn-warning btn-xs ml-2">Download Template</a></li>
                                    <li>Upload file pada form dibawah dan klik <strong>Submit</strong></li>
                                </ol>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="fileimportDetailRbaMdl">File: <i class="text-red">*</i></label>
                                <input type="file" class="form-control" id="fileimportDetailRbaMdl">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnimportDetailRbaMdl" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div id="addDetailRbaMdl" class="modal" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Detail RENCANA BISNIS & ANGGARAN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddDetailRbaMdl">
                        <div class="row mb-2">
                            <div class="col">
                                <label for="id_akunaddDetailRbaMdl">Akun: <i class="text-red">*</i></label>
                                <select id="id_akunaddDetailRbaMdl" class="form-control select2bs4">
                                    @foreach ($akun as $akn)
                                        <option value="{{ $akn->id_akun }}">{{ $akn->no_akun }} {{ $akn->nm_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="satuanaddDetailRbaMdl">Satuan: <i class="text-red">*</i></label>
                                <input type="text" class="form-control" id="satuanaddDetailRbaMdl">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="indikatoraddDetailRbaMdl">Indikator: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="indikatoraddDetailRbaMdl" min="1">
                            </div>
                            <div class="col">
                                <label for="voladdDetailRbaMdl">Volume: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="voladdDetailRbaMdl">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="tarifaddDetailRbaMdl">Tarif: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="tarifaddDetailRbaMdl">
                            </div>
                            <div class="col">
                                <label for="totaladdDetailRbaMdl">Total: <i class="text-red">*</i></label>
                                <input type="number" readonly class="form-control" id="totaladdDetailRbaMdl"
                                    value="0">
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

    <div id="updateDetailRbaMdl" class="modal" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Detail RENCANA BISNIS & ANGGARAN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formupdateDetailRbaMdl">
                        <div class="row mb-2">
                            <div class="col">
                                <input type="hidden" id="id_detail_rbaupdateDetailRbaMdl">
                                <label for="id_akunupdateDetailRbaMdl">Akun: <i class="text-red">*</i></label>
                                <select id="id_akunupdateDetailRbaMdl" class="form-control select2bs4">
                                    <option value="">---</option>
                                    @foreach ($akun as $akn)
                                        <option value="{{ $akn->id_akun }}">{{ $akn->no_akun }} {{ $akn->nm_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="satuanupdateDetailRbaMdl">Satuan: <i class="text-red">*</i></label>
                                <input type="text" class="form-control" id="satuanupdateDetailRbaMdl">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="indikatorupdateDetailRbaMdl">Indikator: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="indikatorupdateDetailRbaMdl" min="1">
                            </div>
                            <div class="col">
                                <label for="volupdateDetailRbaMdl">Volume: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="volupdateDetailRbaMdl">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="tarifupdateDetailRbaMdl">Tarif: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="tarifupdateDetailRbaMdl">
                            </div>
                            <div class="col">
                                <label for="totalupdateDetailRbaMdl">Total: <i class="text-red">*</i></label>
                                <input type="number" readonly class="form-control" id="totalupdateDetailRbaMdl"
                                    value="0">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnupdateDetailRbaMdl" class="btn btn-primary">Ubah</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div id="addLaksKegiatanMdl" class="modal" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pelaksanaan Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddLaksKegiatanMdl">
                        <div class="row mb-2">
                            <div class="col">
                                <label for="lokasiaddLaksKegiatanMdl">Lokasi: <i class="text-red">*</i></label>
                                <input type="text" placeholder="Lokasi Kegiatan" class="form-control"
                                    id="lokasiaddLaksKegiatanMdl">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2">
                                <label for="tahunaddLaksKegiatanMdl">Tahun: <i class="text-red">*</i></label>
                                <input type="number" value="{{ date('Y') }}" class="form-control"
                                    id="tahunaddLaksKegiatanMdl">
                            </div>
                            <div class="col-5">
                                <label for="waktu_pelaksanaanaddLaksKegiatanMdl">Waktu Pelakasanaan: <i
                                        class="text-red">*</i></label>
                                <input type="datetime-local" class="form-control"
                                    id="waktu_pelaksanaanaddLaksKegiatanMdl">
                            </div>
                            <div class="col-5">
                                <label for="waktu_selesaiaddLaksKegiatanMdl">Waktu Selesai: <i
                                        class="text-red">*</i></label>
                                <input type="datetime-local" class="form-control" id="waktu_selesaiaddLaksKegiatanMdl">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnaddLaksKegiatanMdl" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div id="updateLaksKegiatanMdl" class="modal" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Pelaksanaan Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formupdateLaksKegiatanMdl">
                        <div class="row mb-2">
                            <div class="col">
                                <input type="hidden" id="id_laksana_kegiatanupdateLaksKegiatanMdl">
                                <label for="lokasiupdateLaksKegiatanMdl">Lokasi: <i class="text-red">*</i></label>
                                <input type="text" placeholder="Lokasi Kegiatan" class="form-control"
                                    id="lokasiupdateLaksKegiatanMdl">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2">
                                <label for="tahunupdateLaksKegiatanMdl">Tahun: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="tahunupdateLaksKegiatanMdl">
                            </div>
                            <div class="col-5">
                                <label for="waktu_pelaksanaanupdateLaksKegiatanMdl">Waktu Pelakasanaan: <i
                                        class="text-red">*</i></label>
                                <input type="datetime-local" class="form-control"
                                    id="waktu_pelaksanaanupdateLaksKegiatanMdl">
                            </div>
                            <div class="col-5">
                                <label for="waktu_selesaiupdateLaksKegiatanMdl">Waktu Selesai: <i
                                        class="text-red">*</i></label>
                                <input type="datetime-local" class="form-control"
                                    id="waktu_selesaiupdateLaksKegiatanMdl">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnupdateLaksKegiatanMdl" class="btn btn-primary">Ubah Data</button>
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
            if ("{!! $lockBtnDetailRba !!}" == 'disabled') {
                $('#locked').prop("disabled", true);
                $('#addDetailRba').prop("disabled", true);
                $('#deleteDetailRba').prop("disabled", true);
            }
            if ("{!! $lockBtnImport !!}" == 'disabled') {
                $('#importDetailRba').prop("disabled", true);
            }

            if ("{!! $lockBtnLaksana !!}" == 'disabled') {
                $('#addLaksKegiatan').prop("disabled", true);
                $('#deleteLaksKegiatan').prop("disabled", true);
            }

            $(".ckAllDetailRba").change(function() {
                if (this.checked) {
                    $('.ckItemDetailRba').prop('checked', true);
                } else {
                    $('.ckItemDetailRba').prop('checked', false);
                }
            });

            $(".ckAllLaksKegiatan").change(function() {
                if (this.checked) {
                    $('.ckItemLaksKegiatan').prop('checked', true);
                } else {
                    $('.ckItemLaksKegiatan').prop('checked', false);
                }
            });

            $("#addDetailRba").click(function() {
                $('#addDetailRbaMdl').modal('show');
            });

            $("#importDetailRba").click(function() {
                $('#importDetailRbaMdl').modal('show');
            });

            $("#indikatoraddDetailRbaMdl").keyup(function() {
                $("#totaladdDetailRbaMdl").val($("#voladdDetailRbaMdl").val() * $("#tarifaddDetailRbaMdl").val() * $(this).val());
            });

            $("#indikatorupdateDetailRbaMdl").keyup(function() {
                $("#totaladdDetailRbaMdl").val($("#tarifaddDetailRbaMdl").val() * $("#voladdDetailRbaMdl").val() * $(this).val());
            });

            $("#tarifaddDetailRbaMdl").keyup(function() {
                $("#totaladdDetailRbaMdl").val($("#voladdDetailRbaMdl").val() * $("#indikatoraddDetailRbaMdl").val() * $(this).val());
            });

            $("#voladdDetailRbaMdl").keyup(function() {
                $("#totaladdDetailRbaMdl").val($("#tarifaddDetailRbaMdl").val() * $("#indikatoraddDetailRbaMdl").val() * $(this).val());
            });

            $("#tarifupdateDetailRbaMdl").keyup(function() {
                $("#totalupdateDetailRbaMdl").val($("#volupdateDetailRbaMdl").val() * $("#indikatorupdateDetailRbaMdl").val() * $(this).val());
            });

            $("#volupdateDetailRbaMdl").keyup(function() {
                $("#totalupdateDetailRbaMdl").val($("#tarifupdateDetailRbaMdl").val() * $("#indikatorupdateDetailRbaMdl").val() * $(this).val());
            });

            $("#btnimportDetailRbaMdl").click(function() {

                var formData = new FormData();
                formData.append("_token", "{!! csrf_token() !!}");
                formData.append("id_rba", "{!! $IdRba !!}");
                formData.append("file", $('#fileimportDetailRbaMdl')[0].files[0]);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('kepalabagian.KegiatanMonitoring.apiCreateImportDetailRba') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(this).prop("disabled", true);
                        Swal.showLoading();
                    },
                }).done(function(res) {
                    if (res.status) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Tambah Rincian RENCANA BISNIS & ANGGARAN Berhasil',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Tambah Rincian RENCANA BISNIS & ANGGARAN Gagal',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Tambah Rincian RENCANA BISNIS & ANGGARAN Gagal',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    console.log(res);
                    $(this).prop("disabled", false);
                });
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
                            title: 'Tambah Rincian RENCANA BISNIS & ANGGARAN Kegiatan Berhasil',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Tambah Rincian RENCANA BISNIS & ANGGARAN Kegiatan Gagal',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Tambah Rincian RENCANA BISNIS & ANGGARAN Kegiatan Gagal',
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
                                id_detail_rba: getIdDetailRba()
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
                                icon: 'error',
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

            $("#locked").click(function() {
                $("#locked").prop("disabled", true);
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "RENCANA BISNIS & ANGGARAN Kegiatan Akan Diajukan Kepada Verifikator, Anda Tidak Dapat Melakukan Perubahan RENCANA BISNIS & ANGGARAN Kegiatan Setelahnya!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Tidak, Batalkan!'
                }).then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{!! route('kepalabagian.KegiatanMonitoring.apiUpdate') !!}",
                            data: {
                                _token: "{!! csrf_token() !!}",
                                id_rba: "{!! $IdRba !!}",
                                id_kegiatan_divisi: "{!! request()->get('id_kegiatan_divisi') !!}",
                            }
                        }).done(function(res) {
                            if (res.status) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Data Berhasil Disimpan!',
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                                location.reload();
                            } else {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: res.message,
                                    showConfirmButton: true,
                                });
                                $("#locked").prop("disabled", false);
                            }
                        }).fail(function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Data Gagal Disimpan!',
                                showConfirmButton: true,
                            });
                            $("#locked").prop("disabled", false);
                        });
                    } else {
                        $("#locked").prop("disabled", false);
                    }
                });
            });

            $("#addLaksKegiatan").click(function() {
                $('#addLaksKegiatanMdl').modal('show');
            });

            $("#btnaddLaksKegiatanMdl").click(function() {
                if ($('#waktu_selesaiaddLaksKegiatanMdl').val() < $('#waktu_pelaksanaanaddLaksKegiatanMdl')
                    .val()) {
                    alert("Tanggal Pelaksanaan Kegiatan Tidak Valid!");
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('kepalabagian.KegiatanMonitoring.apiCreateLaksana') }}",
                        data: {
                            _token: "{!! csrf_token() !!}",
                            id_kegiatan_divisi: "{!! request()->get('id_kegiatan_divisi') !!}",
                            lokasi: $('#lokasiaddLaksKegiatanMdl').val(),
                            waktu_pelaksanaan: $('#waktu_pelaksanaanaddLaksKegiatanMdl').val(),
                            waktu_selesai: $('#waktu_selesaiaddLaksKegiatanMdl').val(),
                            tahun: $('#tahunaddLaksKegiatanMdl').val(),
                        },
                        beforeSend: function() {
                            $(this).prop("disabled", true);
                        },
                    }).done(function(res) {
                        if (res.status) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Tambah Detail Laksana Berhasil',
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            location.reload();
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Tambah Detail Laksana Gagal',
                                showConfirmButton: false,
                                timer: 1000,
                            });
                        }
                    }).fail(function(res) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Tambah Detail Laksana Gagal',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        console.log(res);
                        $(this).prop("disabled", false);
                    });
                }
            });

            $("#deleteLaksKegiatan").click(function() {
                $(this).prop("disabled", true);
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
                            url: "{!! route('kepalabagian.KegiatanMonitoring.apiDeleteLaksana') !!}",
                            data: {
                                _token: "{!! csrf_token() !!}",
                                id_laksana_kegiatan: getIdLaksKegiatan()
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
                                icon: 'error',
                                title: 'Data Gagal Dihapus!',
                                showConfirmButton: true,
                            });
                            $(this).prop("disabled", false);
                        });
                    } else {
                        $(this).prop("disabled", false);
                    }
                });
            });

            $("#btnupdateDetailRbaMdl").click(function() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('kepalabagian.KegiatanMonitoring.apiUpdateDetailRba') }}",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_detail_rba: $("#id_detail_rbaupdateDetailRbaMdl").val(),
                        id_akun: $("#id_akunupdateDetailRbaMdl").val(),
                        satuan: $("#satuanupdateDetailRbaMdl").val(),
                        indikator: $("#indikatorupdateDetailRbaMdl").val(),
                        vol: $("#volupdateDetailRbaMdl").val(),
                        tarif: $("#tarifupdateDetailRbaMdl").val(),
                        total: $("#totalupdateDetailRbaMdl").val(),
                    },
                    beforeSend: function() {
                        $(this).prop("disabled", true);
                    },
                }).done(function(res) {
                    if (res.status) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Ubah Rincian RENCANA BISNIS & ANGGARAN Kegiatan Berhasil',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Ubah Rincian RENCANA BISNIS & ANGGARAN Kegiatan Gagal',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Ubah Rincian RENCANA BISNIS & ANGGARAN Kegiatan Gagal',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    console.log(res);
                    $(this).prop("disabled", false);
                });
            });

            $("#btnupdateLaksKegiatanMdl").click(function() {
                if ($('#waktu_selesaiupdateLaksKegiatanMdl').val() < $('#waktu_pelaksanaanupdateLaksKegiatanMdl')
                    .val()) {
                    alert("Tanggal Pelaksanaan Kegiatan Tidak Valid!");
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('kepalabagian.KegiatanMonitoring.apiUpdateLaksana') }}",
                        data: {
                            _token: "{!! csrf_token() !!}",
                            id_laksana_kegiatan: $('#id_laksana_kegiatanupdateLaksKegiatanMdl').val(),
                            lokasi: $('#lokasiupdateLaksKegiatanMdl').val(),
                            waktu_pelaksanaan: $('#waktu_pelaksanaanupdateLaksKegiatanMdl').val(),
                            waktu_selesai: $('#waktu_selesaiupdateLaksKegiatanMdl').val(),
                            tahun: $('#tahunupdateLaksKegiatanMdl').val(),
                        },
                        beforeSend: function() {
                            $(this).prop("disabled", true);
                        },
                    }).done(function(res) {
                        if (res.status) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Ubah Detail Laksana Berhasil',
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            location.reload();
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Ubah Detail Laksana Gagal',
                                showConfirmButton: false,
                                timer: 1000,
                            });
                        }
                    }).fail(function(res) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Ubah Detail Laksana Gagal',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        console.log(res);
                        $(this).prop("disabled", false);
                    });
                }
            });
        });

        function getIdDetailRba() {
            let id = [];
            $('.ckItemDetailRba:checked').each(function() {
                id.push($(this).val());
            });
            return id;
        }

        function getIdLaksKegiatan() {
            let id = [];
            $('.ckItemLaksKegiatan:checked').each(function() {
                id.push($(this).val());
            });
            return id;
        }

        function modalUpdateDetailLaks(p1, p2, p3, p4, p5, p6) {
            $("#id_laksana_kegiatanupdateLaksKegiatanMdl").val(p1);
            $("#tahunupdateLaksKegiatanMdl").val(p2);
            $("#tgl_ajuanupdateLaksKegiatanMdl").val(p3);
            $("#waktu_pelaksanaanupdateLaksKegiatanMdl").val(p4);
            $("#waktu_selesaiupdateLaksKegiatanMdl").val(p5);
            $("#lokasiupdateLaksKegiatanMdl").val(p6);
            $('#updateLaksKegiatanMdl').modal('show');
        }

        function modalUpdateDetailRba(p1, p2, p3, p4, p5, p6, p7){
            $("#id_detail_rbaupdateDetailRbaMdl").val(p1);
            $("#id_akunupdateDetailRbaMdl").val(p2).trigger('change');
            $("#satuanupdateDetailRbaMdl").val(p3);
            $("#indikatorupdateDetailRbaMdl").val(p4);
            $("#volupdateDetailRbaMdl").val(p5);
            $("#tarifupdateDetailRbaMdl").val(p6);
            $("#totalupdateDetailRbaMdl").val(p7);
            $('#updateDetailRbaMdl').modal('show');
        }
    </script>
@endpush
