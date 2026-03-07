<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Rekap Produk</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px 10px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center;">Laporan Rekap Produk Terjual</h2>
    <p style="text-align: right; margin-top: 40px;">
        Bulan Laporan: <strong>{{ $bulan }}</strong>
    </p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Jumlah Laku (pcs)</th>
                <th>Harga Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($produkTerjual as $produk)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $produk->product->nama ?? 'Produk tidak ditemukan' }}</td>
                    <td>{{ $produk->total_kuantitas }}</td>
                    <td>Rp {{ number_format($produk->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>
