<table class="table table-borderless">
    <thead>
        <tr>
            <th colspan="7" style="text-align:center">Dokumen RBA Kegiatan - {{ $records->nm_divisi }}</th>
        </tr>
    </thead>
</table>
<table>
    <thead>
        <tr>
            <th>Divisi:</th>
            <td colspan="6">{{ $records->nm_divisi }}</td>
        </tr>
        <tr>
            <th>Kegiatan:</th>
            <td colspan="6">{{ $records->nm_kegiatan }}</td>
        </tr>
        <tr>
            <th>Program:</th>
            <td colspan="6">{{ $records->nm_program }}</td>
        </tr>
        <tr>
            <th>Misi:</th>
            <td colspan="6">{{ $records->nm_misi }}</td>
        </tr>
        <tr>
            <th>Tgl Pengajuan:</th>
            <td colspan="6">{{ TglWaktuIndonesia($records->tgl_submit) }}</td>
        </tr>
    </thead>
    <tbody>
        <tr></tr>
        <tr>
            <th>Akun</th>
            <th>Uraian</th>
            <th>Satuan</th>
            <th>Volume</th>
            <th>Indikator</th>
            <th>Tarif</th>
            <th>Total</th>
        </tr>
        @foreach($records->detail AS $item)
        <tr>
            <td>{{ $item->no_akun }}</td>
            <td>{{ $item->nm_akun }}</td>
            <td>{{ $item->satuan }}</td>
            <td>{{ $item->vol }}</td>
            <td>{{ $item->indikator }}</td>
            <td>{{ $item->tarif }}</td>
            <td>{{ $item->total }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">TOTAL</th>
            <th>{{ array_sum(array_column($records->detail, 'total')) }}</th>
        </tr>
    </tfoot>
</table>