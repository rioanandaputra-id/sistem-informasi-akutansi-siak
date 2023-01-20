<table class="table table-borderless">
    <thead>
        <tr>
            <th colspan="5" style="text-align:center">Dokumen Laporan Kegiatan - {{ $divisi->nm_divisi }}</th>
        </tr>
    </thead>
</table>
@foreach($records AS $item)
<table>
    <tr>
        <th colspan="5">Misi: {{ $item->nm_misi }}</th>
    </tr>
    <thead>
        <tr>
            <th>Program</th>
            <th>Kegiatan</th>
            <th>Pagu Anggaran</th>
            <th>Realisasi</th>
            <th>%</th>
        </tr>
    </thead>
    <tbody>
        @foreach($item->program AS $program)
        @if($program->kegiatan != null)
        <tr>
            <td rowspan="{{ count($program->kegiatan) }}">{{ $program->nm_program }}</td>
            <td>{{ $program->kegiatan[0]->nm_kegiatan }}</td>
            <td>{{ $program->kegiatan[0]->pagu_anggaran }}</td>
            <td>{{ $program->kegiatan[0]->realisasi }}</td>
            <td>{{ ($program->kegiatan[0]->realisasi != null) ? number_format(($program->kegiatan[0]->realisasi / $program->kegiatan[0]->pagu_anggaran)*100, 2) : 0 }}</td>
        </tr>
        @for($i=1;$i < count($program->kegiatan);$i++)
        <tr>
            <td>{{ $program->kegiatan[$i]->nm_kegiatan }}</td>
            <td>{{ $program->kegiatan[$i]->pagu_anggaran }}</td>
            <td>{{ $program->kegiatan[$i]->realisasi }}</td>
            <td>{{ ($program->kegiatan[$i]->realisasi != null) ? number_format(($program->kegiatan[$i]->realisasi / $program->kegiatan[$i]->pagu_anggaran)*100, 2) : 0 }}</td>
        </tr>
        @endfor
        @endif
        @endforeach
    </tbody>
</table>
@endforeach