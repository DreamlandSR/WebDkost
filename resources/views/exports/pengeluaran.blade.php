<table>
    <thead>
        <tr>
            <th style="width: 50px; text-align: center;">No</th>
            <th style="width: 150px; text-align: center;">Tanggal Transaksi</th>
            <th style="width: 150px;">Kategori</th>
            <th style="width: 300px;">Keterangan</th>
            <th style="width: 150px; text-align: right;">Nominal (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $no = 1;
        @endphp
        @foreach($pengeluarans as $p)
            @php $total += $p->nominal; @endphp
            <tr>
                <td style="text-align: center;">{{ $no++ }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($p->tgl_transaksi)->format('d/m/Y') }}</td>
                <td>{{ $p->kategori }}</td>
                <td>{{ $p->keterangan ?? '-' }}</td>
                <td style="text-align: right;">{{ $p->nominal }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bold; padding-right: 10px;">TOTAL KESELURUHAN</td>
            <td style="text-align: right; font-weight: bold;">{{ $total }}</td>
        </tr>
    </tfoot>
</table>
