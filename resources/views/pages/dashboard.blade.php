@extends('layouts.adminlteMaster')
@push('css')
<link rel="stylesheet" href="{{ asset('adminlte320/plugins/fullcalendar/main.css') }}" />
@endpush

@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $realisasiKegiatan }}</h3>
                    <h5>Realisasi Kegiatan</h5>
                </div>
                <div class="icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $spjday }}</h3>
                    <h5>SPJ Hari Ini</h5>
                </div>
                <div class="icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $spjmonth }}</h3>
                    <h5>SPJ Bulan Ini</h5>
                </div>
                <div class="icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $spjyears }}</h3>
                    <h5>SPJ Tahun Ini</h5>
                </div>
                <div class="icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Pendapatan Diverifikasi Tahun {{ date('Y') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-6">
                            <div class="description-block border-right">
                                <h4>{{ number_to_currency(0, 0) }}</h4>
                                <span class="description-text">RENCANA PENDAPATAN</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-6">
                            <div class="description-block border-right">
                                <h4>{{ number_to_currency(0, 0) }}</h4>
                                <span class="description-text">REALISASI PENDAPATAN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><div class="row">
        <div class="col">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Pengeluaran Diverifikasi Tahun {{ date('Y') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-6">
                            <div class="description-block border-right">
                                <h4>{{ number_to_currency(array_sum(array_column($pengeluaran, 'rencana_pengeluaran')), 0) }}</h4>
                                <span class="description-text">RENCANA PENGELUARAN</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-6">
                            <div class="description-block">
                                <h4>{{ number_to_currency(array_sum(array_column($pengeluaran, 'realisasi_pengeluaran')), 0) }}</h4>
                                <span class="description-text">REALISASI PENGELUARAN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Kalender Kegiatan</h3>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="calendarModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalTitle" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                </div>
                <div id="modalBody" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="{{ asset('adminlte320/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte320/plugins/fullcalendar/main.js') }}"></script>
<script>
    function convertDate(date)
    {
        var tahun = date.getFullYear();
        var bulan = date.getMonth();
        var tanggal = date.getDate();
        var hari = date.getDay();
        var jam = date.getHours();
        var menit = date.getMinutes();
        switch(hari) {
            case 0: hari = "Minggu"; break;
            case 1: hari = "Senin"; break;
            case 2: hari = "Selasa"; break;
            case 3: hari = "Rabu"; break;
            case 4: hari = "Kamis"; break;
            case 5: hari = "Jum'at"; break;
            case 6: hari = "Sabtu"; break;
        }
        var tampilTanggal = hari + ", " + tanggal + "/" + bulan + "/" + tahun + " jam " + jam + ":" + menit;

        return tampilTanggal;
    }
    
    $(document).ready(function() {
        let kegiatan = <?php echo json_encode($kegiatan) ?>;
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            initialView: 'dayGridMonth',
            events: function( fetchInfo, successCallback, failureCallback ) { 
                var events = [];
                $.each(kegiatan, function(i, item) {
                    events.push({
                        start: item.waktu_pelaksanaan,
                        title: item.nm_kegiatan,
                        end: item.waktu_selesai,
                        divisi: item.nm_divisi,
                        lokasi: item.lokasi
                    })
                })
                successCallback(events);
            },
            editable  : false,
            eventClick:  function(event, jsEvent, view) {
                console.log(event);
                $('#modalTitle').html('Kegiatan');
                var html = `
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Bagian</th>
                                <td>:</td>
                                <td>`+event.event._def.extendedProps.divisi+`</td>
                            </tr>
                            <tr>
                                <th>Kegiatan</th>
                                <td>:</td>
                                <td>`+event.event.title+`</td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>:</td>
                                <td>`+event.event._def.extendedProps.lokasi+`</td>
                            </tr>
                            <tr>
                                <th>Waktu Pelaksanaan</th>
                                <td>:</td>
                                <td>`+convertDate(event.event.start)+`</td>
                            </tr>
                            <tr>
                                <th>Waktu Selesai</th>
                                <td>:</td>
                                <td>`+convertDate(event.event.end)+`</td>
                            </tr>
                        </tbody>
                    </table>
                `;
                $('#modalBody').html(html);
                $('#calendarModal').modal();
            },
        });
        calendar.render();
    });
</script>
@endpush

