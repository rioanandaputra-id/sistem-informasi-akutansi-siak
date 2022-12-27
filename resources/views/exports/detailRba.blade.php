<table>
    <thead>
        <th>SAMPLE KODE</th>
        <th>SAMPLE NAME</th>
        <th>VOLUME</th>
        <th>SATUAN</th>
        <th>INDIKATOR</th>
        <th>TARIF</th>
    </thead>
    <tbody>
        @foreach($akun AS $item)
        <tr>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->nm_akun }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>