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
                                        <button id="locked" class="btn btn-sm noborder btn-light ml-2"><i
                                                class="fas fa-sign-in-alt"></i>
                                            Ajukan</button>
                                    </div>
                                </div>
                            </div>
                            <table class="mb-3" style="width: 100%">
                                <tbody>
                                    @foreach ($bku as $bk)
                                        @php
                                            $lockBtn = $spj->tgl_ajuan ? 'disabled' : '';
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
                                        <button id="deleteDetailSpj"
                                            class="btn btn-sm noborder btn-light ml-2"><i class="fas fa-trash"></i>
                                            Hapus</button>
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
                                        <td class="text-center"><button class="btn btn-primary btn-sm addDetailSpj" data-id="{{ $dlaks->id_detail_laksana_kegiatan }}" data-uraian="{{ $dlaks->no_akun . ' - ' . $dlaks->nm_akun }}"><i class="fas fa-plus"></i></button></td>
                                    </tr>

                                    @php
                                        $dspj = DB::select("
                                            SELECT
                                                dspj.id_detail_spj,
                                                dspj.id_spj,
                                                dspj.id_detail_laksana_kegiatan,
                                                dspj.id_akun,
                                                dspj.total,
                                                akun.no_akun,
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
                                        <td><input class="ckItemDetailSpj" type="checkbox"
                                                    value="{{ $item->id_detail_spj }}"></td>
                                        <td>{{ $item->no_akun }}</td>
                                        <td>
                                            @if($spj->tgl_ajuan == NULL)
                                            <div class="dropdown">
                                                <a href="javascript:" type="button" data-toggle="dropdown">
                                                    {{ $item->nm_akun }}
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:"
                                                        onclick="modalUpdateDetailSpj(
                                                                '{!! $item->id_detail_spj !!}',
                                                                '{!! $item->id_spj !!}',
                                                                '{!! $item->id_detail_laksana_kegiatan !!}',
                                                                '{!! $item->no_akun.'. '.$item->nm_akun !!}',
                                                                '{!! $item->id_akun !!}',
                                                                '{!! $item->total !!}'
                                                            )">Ubah Data
                                                    </a>
                                                </div>
                                            </div>
                                            @else
                                            {{ $item->nm_akun }}
                                            @endif
                                        </td>
                                        <td class="text-right">{{ number_to_currency_without_rp($item->total, 0) }}</td>
                                        <td><a class="btn btn-link btn-xs" href="javascript:" onclick="modalShowDetailSpj('{!! asset('storage/uploads/'.$item->nm_dokumen) !!}')">LIHAT BUKTI</a></td>
                                    </tr>
                                    @endforeach
                                    @if(count($detLaks) > 1)
                                    <tr class="bg-secondary"><td colspan="5"></td></tr>
                                    @endif
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

    <div id="updateDetailSpjMdl" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Detail SPJ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formupdateDetailSpjMdl" enctype="multipart/form-data">
                        <div class="row mb-2">
                            <div class="col">
                                <input type="hidden" id="id_detail_spjupdateDetailSpjMdl">
                                <input type="hidden" id="id_detail_laksana_kegiatanupdateDetailSpjMdl">
                                <label for="akunupdateDetailSpjMdl">Akun: <i class="text-red">*</i></label>
                                <input type="text" class="form-control" id="akunupdateDetailSpjMdl" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="id_akunupdateDetailSpjMdl">Akun: <i class="text-red">*</i></label>
                                <select id="id_akunupdateDetailSpjMdl" class="form-control">
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
                                <label for="id_akunupdateDetailSpjMdl">Realisasi: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="totalupdateDetailSpjMdl"
                                    value="0">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="id_akunupdateDetailSpjMdl">Bukti: <i class="text-red">*</i></label>
                                <input type="file" class="form-control" id="buktiupdateDetailSpjMdl">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnupdateDetailSpjMdl" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div id="showDetailSpjMdl" class="modal" tabindex="-1" role="dialog">
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
@endsection


@push('css')
<link rel="stylesheet" href="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush
@push('js')
<script src="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        if ("{!! $lockBtn !!}" == 'disabled') {
            $('#locked').prop("disabled", true);
            $(".addDetailSpj").prop('disabled', true);
            $('#deleteDetailSpj').prop("disabled", true);
            $('.ckItemDetailSpj').prop('disabled', true);
        }

        $(".addDetailSpj").click(function() {
            var id = $(this).data('id');
            var uraian = $(this).data('uraian');
            $('#addDetailSpjMdl').modal('show');
            $('#id_detail_laksana_kegiatanaddDetailSpjMdl').val(id);
            $('#akunaddDetailSpjMdl').val(uraian);
            $('#id_akunaddDetailSpjMdl').val(null).trigger('change');
            $('#totaladdDetailSpjMdl').val(0);
            $('#buktiaddDetailSpjMdl').val(null);
        });

        $("#btnaddDetailSpjMdl").click(function() {

            var formData = new FormData();
            formData.append("_token", "{!! csrf_token() !!}");
            formData.append("id_spj", "{!! $idSpj !!}");
            formData.append("id_detail_laksana_kegiatan", $("#id_detail_laksana_kegiatanaddDetailSpjMdl").val());
            formData.append("id_akun", $("#id_akunaddDetailSpjMdl").val());
            formData.append("total", $('#totaladdDetailSpjMdl').val());
            formData.append("dokumen", $('#buktiaddDetailSpjMdl')[0].files[0]);

            $.ajax({
                type: 'POST',
                url: "{{ route('bendaharapengeluaran.SPJKegiatanRutin.apiCreateDetailSpj') }}",
                data: formData,
                processData: false,
                contentType: false,
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
                        title: (res.message!='BadRequest') ? res.message : 'Tambah Rincian SPJ Gagal',
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

        $("#btnupdateDetailSpjMdl").click(function() {

            var formData = new FormData();
            formData.append("_token", "{!! csrf_token() !!}");
            formData.append("id_detail_spj", $("#id_detail_spjupdateDetailSpjMdl").val());
            formData.append("id_spj", "{!! $idSpj !!}");
            formData.append("id_detail_laksana_kegiatan", $("#id_detail_laksana_kegiatanupdateDetailSpjMdl").val());
            formData.append("id_akun", $("#id_akunupdateDetailSpjMdl").val());
            formData.append("total", $('#totalupdateDetailSpjMdl').val());
            formData.append("dokumen", $('#buktiupdateDetailSpjMdl')[0].files[0]);

            $.ajax({
                type: 'POST',
                url: "{{ route('bendaharapengeluaran.SPJKegiatanRutin.apiUpdateDetailSpj') }}",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(this).prop("disabled", true);
                },
            }).done(function(res) {
                if (res.status) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Update Rincian SPJ Berhasil',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: (res.message!='BadRequest') ? res.message : 'Update Rincian SPJ Gagal',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            }).fail(function(res) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Update Rincian SPJ Kegiatan Gagal',
                    showConfirmButton: false,
                    timer: 1000,
                });
                console.log(res);
                $(this).prop("disabled", false);
            });
        });

        $("#deleteDetailSpj").click(function() {
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
                        url: "{!! route('bendaharapengeluaran.SPJKegiatanRutin.apiDeleteDetailSpj') !!}",
                        data: {
                            _token: "{!! csrf_token() !!}",
                            id_detail_spj: getIdDetailSpj()
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

        $("#locked").click(function() {
            $("#locked").prop("disabled", true);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "SPJ Akan Diajukan Kepada Verifikator, Anda Tidak Dapat Melakukan Perubahan SPJ Setelahnya!",
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
                        url: "{!! route('bendaharapengeluaran.SPJKegiatanRutin.apiUpdate') !!}",
                        data: {
                            _token: "{!! csrf_token() !!}",
                            id_spj: "{!! $idSpj !!}",
                        }
                    }).done(function(res) {
                        if (res.status) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'SPJ Berhasil Diajukan!',
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
                            title: 'SPJ Gagal Diajukan!',
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

    function getIdDetailSpj() {
        let id = [];
        $('.ckItemDetailSpj:checked').each(function() {
            id.push($(this).val());
        });
        return id;
    }

    function modalUpdateDetailSpj(p1, p2, p3, p4, p5, p6) {
        $("#id_detail_spjupdateDetailSpjMdl").val(p1);
        $("#id_spjupdateDetailSpjMdl").val(p2);
        $("#id_detail_laksana_kegiatanupdateDetailSpjMdl").val(p3);
        $("#akunupdateDetailSpjMdl").val(p4);
        $("#id_akunupdateDetailSpjMdl").val(p5);
        $("#totalupdateDetailSpjMdl").val(p6);
        $('#updateDetailSpjMdl').modal('show');
    }

    function modalShowDetailSpj(p1) {
        $("#buktiShowDetailSpjMdl").attr('src', '#');
        $("#buktiShowDetailSpjMdl").attr('src', p1);
        $('#showDetailSpjMdl').modal('show');
    }
</script>
@endpush