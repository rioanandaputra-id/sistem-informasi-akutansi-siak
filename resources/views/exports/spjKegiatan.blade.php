<table class="table table-borderless">
    <thead>
        <tr>
            <th colspan="3" style="text-align:center">Dokumen SPJ Kegiatan - {{ $records->nm_divisi }}</th>
        </tr>
    </thead>
</table>
<table>
    <thead>
        <tr>
            <th>Divisi:</th>
            <td colspan="2">{{ $records->nm_divisi }}</td>
        </tr>
        <tr>
            <th>Kegiatan:</th>
            <td colspan="2">{{ $records->nm_kegiatan }}</td>
        </tr>
        <tr>
            <th>Program:</th>
            <td colspan="2">{{ $records->nm_program }}</td>
        </tr>
        <tr>
            <th>Misi:</th>
            <td colspan="2">{{ $records->nm_misi }}</td>
        </tr>
        <tr>
            <th>Tgl Pengajuan:</th>
            <td colspan="2">{{ TglWaktuIndonesia($records->tgl_submit) }}</td>
        </tr>
    </thead>
    <tbody>
        @if($records->detail != null)
        @foreach($records->detail AS $item)
        <tr></tr>
        <tr>
            <th>Urutan Pelaksanaan</th>
            <td colspan="2">{{ $item->urutan_laksana_kegiatan }}</td>
        </tr>
        <tr>
            <th>Waktu Pelaksanaan</th>
            <td colspan="2">{{ TglWaktuIndonesia($item->waktu_pelaksanaan) . ' - ' . TglWaktuIndonesia($item->waktu_selesai) }}</td>
        </tr>
        <tr>
            <th>Lokasi Pelaksanaan</th>
            <td colspan="2">{{ $item->lokasi }}</td>
        </tr>
        <tr>
            <th>Akun</th>
            <th>Uraian</th>
            <th>Total</th>
        </tr>
        @foreach($item->spj AS $values)
        <tr>
            <td>{{ $values->no_akun }}</td>
            <td>{{ $values->nm_akun }}</td>
            <td>{{ $values->total_realisasi }}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="2">TOTAL</th>
            <th>{{ array_sum(array_column($values, 'total_realisasi')) }}</th>
        </tr>
        @endforeach
        @else
        <tr></tr>
        <tr>
            <th colspan="3" style="text-align:center">Kegiatan Belum Terlaksana</th>
        </tr>
        @endif
    </tbody>
</table>