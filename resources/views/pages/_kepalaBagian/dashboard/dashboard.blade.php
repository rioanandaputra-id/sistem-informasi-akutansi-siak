@extends('layouts.adminlteMaster')
@push('css')
<link rel="stylesheet" href="{{ asset('adminlte320/plugins/fullcalendar/main.css') }}">
@endpush
@push('js')
<script src="{{ asset('adminlte320/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte320/plugins/fullcalendar/main.js') }}"></script>
<script>
$(document).ready(function() {
    $('#calendar').fullcalendar();
});
</script>
@endpush

@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-primary">
                <div class="card-body p-0">
                    <div id="calendar" data-url="/"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
