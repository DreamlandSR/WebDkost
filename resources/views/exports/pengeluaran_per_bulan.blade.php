<table>
    <thead>
        <tr>
            <th colspan="5">Laporan Pengeluaran Bulanan — {{ $namaBulan }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Tanggal Transaksi</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Nominal (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pengeluarans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->translatedFormat('d F Y') }}</td>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                {{-- Cast ke int untuk hilangkan .00 dari decimal(15,2) --}}
                <td>{{ (int) $item->nominal }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Tidak ada data pengeluaran untuk bulan ini.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        {{-- Baris kosong pemisah --}}
        <tr><td colspan="5"></td></tr>

        {{-- Rekap per Kategori --}}
        @if($rekapKategori->isNotEmpty())
            <tr>
                <td colspan="5"><strong>Rekap per Kategori</strong></td>
            </tr>
            @foreach ($rekapKategori as $kategori => $jumlah)
                <tr>
                    <td colspan="4">{{ $kategori }}</td>
                    <td>{{ (int) $jumlah }}</td>
                </tr>
            @endforeach
            <tr><td colspan="5"></td></tr>
        @endif

        {{-- Total keseluruhan --}}
        <tr>
            <td colspan="4"><strong>TOTAL PENGELUARAN</strong></td>
            <td>{{ (int) $totalNominal }}</td>
        </tr>
    </tfoot>
</table>
