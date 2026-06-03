<table>
    <thead>
        <tr>
            <th colspan="8">Laporan Pendapatan Bulanan — {{ $namaBulan }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Tanggal Diterima</th>
            <th>Nama Penyewa</th>
            <th>No. Telepon</th>
            <th>No. Kamar</th>
            <th>Periode Tagihan</th>
            <th>Metode Pembayaran</th>
            <th>Total Diterima (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pendapatans as $index => $item)
            @php
                $metode = $item->metode_pembayaran
                    ? ucfirst(str_replace('_', ' ', $item->metode_pembayaran))
                    : '-';
                if ($item->bank) {
                    $metode .= ' (' . strtoupper($item->bank) . ')';
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tgl_diterima)->translatedFormat('d F Y') }}</td>
                <td>{{ $item->nama_penyewa ?? '-' }}</td>
                <td>{{ $item->telepon ?? '-' }}</td>
                <td>{{ $item->nomor_kamar ?? '-' }}</td>
                <td>{{ $item->periode_bulan ? \Carbon\Carbon::parse($item->periode_bulan)->translatedFormat('F Y') : '-' }}</td>
                <td>{{ $metode }}</td>
                <td>{{ (int) ($item->nominal_diterima ?? 0) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">Tidak ada data pendapatan untuk bulan ini.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7"><strong>TOTAL PENDAPATAN DITERIMA</strong></td>
            <td>{{ (int) $totalNominal }}</td>
        </tr>
    </tfoot>
</table>
