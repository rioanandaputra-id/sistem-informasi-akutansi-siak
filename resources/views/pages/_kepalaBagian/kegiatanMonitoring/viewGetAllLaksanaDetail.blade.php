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
                                        <button id="locked" class="btn btn-sm noborder btn-light ml-2"><i
                                                class="fas fa-lock"></i>
                                            Simpan</button>
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
                                            <td>{{ $kgt->a_verif_bend_kegiatan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Verifikasi</td>
                                            <td>:</td>
                                            <td>{{ $kgt->tgl_verif_bend_kegiatan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan Verifikasi</td>
                                            <td>:</td>
                                            <td>{{ $kgt->catatan ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="bg-warning mb-3">
                                    <strong>Total Anggaran Tersisa <span style="margin-left: 40px;">:</span> {{ number_to_currency_without_rp($kgt->total_anggaran_tersedia - $kgt->total_anggaran_terpakai)}}</strong>
                                </div>

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
                                        <th class="text-right">Jumlah</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tfDetailLaks = 0;
                                    @endphp
                                    @foreach ($detailLaks as $dlk)
                                        <tr>
                                            <th><input type="checkbox" class="ckItemDetailLaks" value="{{ $dlk->id_detail_laksana_kegiatan }}"></th>
                                            <td>{{ $dlk->no_akun }}</td>
                                            <td><a href="javascript:">{{ $dlk->nm_akun }}</a></td>
                                            <td class="text-right">{{ number_to_currency_without_rp($dlk->jumlah) }}</td>
                                            <td class="text-right">{{ number_to_currency_without_rp($dlk->total) }}</td>
                                        </tr>
                                        @php
                                            $tfDetailLaks += $dlk->total;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-info">
                                    <th></th>
                                    <th colspan="3">Total</th>
                                    <th class="text-right">{{ number_to_currency_without_rp($tfDetailLaks) }}</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addDetailLaksMdl" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Detail Rencana Anggaran Biaya</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddDetailLaksMdl">
                        <div class="row mb-2">
                            <div class="col">
                                <label for="id_detail_rbaaddDetailLaksMdl">Akun: <i class="text-red">*</i></label>
                                <select id="id_detail_rbaaddDetailLaksMdl" class="form-control">
                                    <option value="">---</option>
                                    @foreach ($akun as $akn)
                                        <option value="{{ $akn->id_detail_rba }}">{{ $akn->no_akun }} {{ $akn->nm_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="jumlahaddDetailLaksMdl">Jumlah: <i class="text-red">*</i></label>
                                <input type="number" class="form-control" id="jumlahaddDetailLaksMdl">
                            </div>
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
@endsection


@push('css')
    <link rel="stylesheet" href="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush
@push('js')
    <script src="{{ asset('adminlte320/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#addDetailLaks").click(function(){
                $("#addDetailLaksMdl").modal('show');
            });

            $("#btnaddDetailLaksMdl").click(function() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('kepalabagian.KegiatanMonitoring.apiCreateDetailLaksana') }}",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        id_laksana_kegiatan: "{!! request()->get('id_laksana_kegiatan') !!}",
                        id_detail_rba: $("#id_detail_rbaaddDetailLaksMdl").val(),
                        jumlah: $("#jumlahaddDetailLaksMdl").val(),
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
                            title: 'Tambah Rincian Pelaksanaan Kegiatan Berhasil',
                            showConfirmButton: true,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Tambah Rincian Pelaksanaan Kegiatan Gagal',
                            showConfirmButton: true,
                        });
                    }
                }).fail(function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Tambah Rincian Pelaksanaan Kegiatan Gagal',
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
                            url: "{!! route('kepalabagian.KegiatanMonitoring.apiDeleteDetailLaksana') !!}",
                            data: {
                                _token: "{!! csrf_token() !!}",
                                id_laksana_kegiatan: getIdDetailLaks()
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
        });

        function getIdDetailLaks() {
            let id = [];
            $('.ckItemDetailLaks:checked').each(function() {
                id.push($(this).val());
            });
            return id;
        }
    </script>
@endpush
