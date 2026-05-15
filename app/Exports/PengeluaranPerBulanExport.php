<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class PengeluaranPerBulanExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = (int) $bulan;
        $this->tahun = (int) $tahun;
    }

    public function view(): View
    {
        // Ambil data pengeluaran filter per bulan & tahun yang dipilih
        $pengeluarans = DB::table('pengeluaran')
            ->whereMonth('tgl_transaksi', $this->bulan)
            ->whereYear('tgl_transaksi', $this->tahun)
            ->orderBy('tgl_transaksi', 'asc')
            ->orderBy('kategori', 'asc')
            ->get();

        $namaBulan = Carbon::createFromDate($this->tahun, $this->bulan, 1)
            ->locale('id')
            ->translatedFormat('F Y');

        $totalNominal = $pengeluarans->sum('nominal');

        // Rekap per kategori untuk baris ringkasan
        $rekapKategori = $pengeluarans->groupBy('kategori')->map(fn($g) => $g->sum('nominal'));

        return view('exports.pengeluaran_per_bulan', compact(
            'pengeluarans',
            'namaBulan',
            'totalNominal',
            'rekapKategori'
        ));
    }

    public function styles(Worksheet $sheet)
    {
        $totalRows = DB::table('pengeluaran')
            ->whereMonth('tgl_transaksi', $this->bulan)
            ->whereYear('tgl_transaksi', $this->tahun)
            ->count();

        // baris 1 = judul, baris 2 = header kolom, baris 3..n = data, baris n+1 = total
        $footerRow = $totalRows + 3;

        // Judul laporan (baris 1) — 5 kolom A-E
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 12],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FF6979F8']],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Header kolom (baris 2)
        $sheet->getStyle('A2:E2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FF2C2C6C']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FFECE9FF']],
        ]);

        // Baris footer total
        $sheet->getStyle('A' . $footerRow . ':E' . $footerRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FFF3F4F6']],
        ]);

        // Format kolom nominal (E) sebagai angka
        $sheet->getStyle('E3:E' . $footerRow)
              ->getNumberFormat()
              ->setFormatCode('#,##0');

        return [];
    }

    public function title(): string
    {
        return 'Pengeluaran ' . Carbon::createFromDate($this->tahun, $this->bulan, 1)
            ->locale('id')
            ->translatedFormat('F Y');
    }
}
