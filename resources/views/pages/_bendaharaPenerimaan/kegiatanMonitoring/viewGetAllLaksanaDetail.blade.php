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
                                        <b>DETAIL PELAKSANAAN KEGIATAN</b>
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
                                $lockedBtn = $kgt->tgl_ajuan != null ? 'disabled' : '';
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
                                            <td style="min-width: 200px">Misi</td>
                                            <td>:</td>
                                            <td>[ {{ $kgt->periode_misi }} ] {{ $kgt->nm_misi }}</td>
                                        </tr>
                                        <tr>
                                            <td>Program</td>
                                            <td>:</td>
                                            <td>[ {{ $kgt->periode_program }} ] {{ $kgt->nm_program }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kegiatan</td>
                                            <td>:</td>
                                            <td>{{ $kgt->nm_kegiatan }}</td>
                                        </tr>
                                        <tr><td colspan="3">&nbsp;</td></tr>
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
                                        <tr><td colspan="3">&nbsp;</td></tr>
                                        <tr class="bg-purple">
                                            <td>Total Anggaran (RBA)</td>
                                            <td>:</td>
                                            <td>{{ number_to_currency_without_rp($kgt->total_anggaran_tersedia) }}</td>
                                        </tr>
                                        <tr class="bg-info">
                                            <td>Total Anggaran (Realisasi Sementara)</td>
                                            <td>:</td>
                                            <td>{{ number_to_currency_without_rp($kgt->total_anggaran_terpakai) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endforeach
                            <div class="row bg-success p-2 mb-3">
                                <div class="col">
                                    <div class="float-left">
                                        <b>RINCIAN DETAIL PELAKSANAAN KEGIATAN</b>
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
                                <tbody>
                                    @php
                                        $tfDetailLaks = 0;
                                    @endphp
                                    @foreach ($detailLaks as $dlk)
                                        <tr>
                                            <th><input type="checkbox" class="ckItemDetailLaks"
                                                    value="{{ $dlk->id_detail_laksana_kegiatan }}"></th>
                                            <td>{{ $dlk->no_akun }}</td>
                                            @if ($lockedBtn == 'disabled')
                                                <td>{{ $dlk->nm_akun }}</td>
                                            @else
                                                <td><a href="javascript:" onclick="updateDetailLaks(
                                                    '{!! $dlk->id_detail_laksana_kegiatan !!}',
                                                    '{!! $dlk->id_detail_rba !!}',
                                                    '{!! $dlk->total !!}',
                                                )">{{ $dlk->nm_akun }}</a></td>
                                            @endif
                                            <td class="text-right">{{ number_to_currency_without_rp($dlk->total) }}</td>
                                        </tr>
                                        @php
                                            $tfDetailLaks += $dlk->total;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-info">
                                    <th></th>
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

    <div id="addDetailLaksMdl" class="modal"  role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Rincian Detail Pelaksanaan Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddDetailLaksMdl">
                        <div class="row mb-2">
                            <div class="col">
                                <label for="id_detail_rbaaddDetailLaksMdl">Akun: <i class="text-red">*</i></label>
                                <select id="id_detail_rbaaddDetailLaksMdl" class="form-control select2bs4">
                                    @foreach ($akun as $akn)
                                        <option value="{{ $akn->id_detail_rba }}">{{ $akn->no_akun }} {{ $akn->nm_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="totaladdDetailLaksMdl">Total: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="totaladdDetailLaksMdl">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnaddDetailLaksMdl" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div id="updateDetailLaksMdl" class="modal"  role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Rincian Detail Pelaksanaan Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formupdateDetailLaksMdl">
                        <div class="row mb-2">
                            <div class="col">
                                <input type="hidden" id="id_detail_laksana_kegiatanupdateDetailLaksMdl">
                                <label for="id_detail_rbaupdateDetailLaksMdl">Akun: <i class="text-red">*</i></label>
                                <select id="id_detail_rbaupdateDetailLaksMdl" class="form-control select2bs4">
                                    @foreach ($akun as $akn)
                                        <option value="{{ $akn->id_detail_rba }}">{{ $akn->no_akun }} {{ $akn->nm_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="totalupdateDetailLaksMdl">Total: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="totalupdateDetailLaksMdl">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnupdateDetailLaksMdl" class="btn btn-primary">Ubah</button>
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
                $('#locked').prop("disabled", true);
                $('#addDetailLaks').prop("disabled", true);
                $('#deleteDetailLaks').prop("disabled", true);
            }

            $("#addDetailLaks").click(function() {
                $("#addDetailLaksMdl").modal('show');
            });

            $("#btnaddDetailLaksMdl").click(function() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('bendaharapenerimaan.KegiatanMonitoring.apiCreateDetailLaksana') }}",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_laksana_kegiatan: "{!! request()->get('id_laksana_kegiatan') !!}",
                        id_detail_rba: $("#id_detail_rbaaddDetailLaksMdl").val(),
                        total: $("#totaladdDetailLaksMdl").val(),
                    },
                    beforeSend: function() {
                        $(this).prop("disabled", true);
                    },
                }).done(function(res) {
                    if (res.status) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Tambah Rincian Detail Pelaksanaan Kegiatan Berhasil',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Tambah Rincian Detail Pelaksanaan Kegiatan Gagal',
                            showConfirmButton: true,
                        });
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Tambah Rincian Detail Pelaksanaan Kegiatan Gagal',
                        showConfirmButton: true,
                    });
                    console.log(res);
                    $(this).prop("disabled", false);
                });
            });

            $(".ckAllDetailLaks").change(function() {
                if (this.checked) {
                    $('.ckItemDetailLaks').prop('checked', true);
                } else {
                    $('.ckItemDetailLaks').prop('checked', false);
                }
            });

            $("#deleteDetailLaks").click(function() {
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
                            url: "{!! route('bendaharapenerimaan.KegiatanMonitoring.apiDeleteDetailLaksana') !!}",
                            data: {
                                _token: "{!! csrf_token() !!}",
                                id_detail_laksana_kegiatan: getIdDetailLaks()
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

            $("#btnupdateDetailLaksMdl").click(function(){
                $.ajax({
                    type: 'POST',
                    url: "{{ route('bendaharapenerimaan.KegiatanMonitoring.apiUpdateDetailLaksana') }}",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_detail_laksana_kegiatan: $("#id_detail_laksana_kegiatanupdateDetailLaksMdl").val(),
                        id_laksana_kegiatan: "{!! request()->get('id_laksana_kegiatan') !!}",
                        id_detail_rba: $("#id_detail_rbaupdateDetailLaksMdl").val(),
                        total: $("#totalupdateDetailLaksMdl").val(),
                    },
                    beforeSend: function() {
                        $(this).prop("disabled", true);
                    },
                }).done(function(res) {
                    if (res.status) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Ubah Rincian Detail Pelaksanaan Kegiatan Berhasil',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Ubah Rincian Detail Pelaksanaan Kegiatan Gagal',
                            showConfirmButton: true,
                        });
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Ubah Rincian Detail Pelaksanaan Kegiatan Gagal',
                        showConfirmButton: true,
                    });
                    console.log(res);
                    $(this).prop("disabled", false);
                });
            });

            $("#locked").click(function() {
                $("#locked").prop("disabled", true);
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Pelaksanaan Kegiatan Akan Diajukan Kepada Verifikator, Anda Tidak Dapat Melakukan Perubahan Pelaksanaan Kegiatan Setelahnya!",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ajukan!',
                    cancelButtonText: 'Tidak, Batalkan!'
                }).then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{!! route('bendaharapenerimaan.KegiatanMonitoring.apiUpdateLaksana') !!}",
                            data: {
                                _token: "{!! csrf_token() !!}",
                                type_request: 'ajuan',
                                id_laksana_kegiatan: "{!! request()->get('id_laksana_kegiatan') !!}",
                            }
                        }).done(function(res) {
                            if (res.status) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Pengajuan Berhasil',
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
                                title: 'Pengajuan Gagal!',
                                showConfirmButton: true,
                            });
                            $("#locked").prop("disabled", false);
                        });
                    } else {
                        $("#locked").prop("disabled", false);
                    }
                });
            });
        });

        function getIdDetailLaks() {
            let id = [];
            $('.ckItemDetailLaks:checked').each(function() {
                id.push($(this).val());
            });
            return id;
        }

        function updateDetailLaks(p1,p2,p3){
            $('#id_detail_laksana_kegiatanupdateDetailLaksMdl').val(p1);
            $('#id_detail_rbaupdateDetailLaksMdl').val(p2);
            $('#totalupdateDetailLaksMdl').val(p3);
            $("#updateDetailLaksMdl").modal('show');
        }
    </script>
@endpush
