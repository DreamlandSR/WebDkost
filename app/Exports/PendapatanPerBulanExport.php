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

class PendapatanPerBulanExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
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
        // Join melalui rantai lengkap: pendapatan → pembayaran → tagihan → booking → users/kamar
        // Gunakan LEFT JOIN agar data tetap muncul walau relasi tidak lengkap
        $pendapatans = DB::table('pendapatan')
            ->leftJoin('pembayaran', 'pendapatan.id_pembayaran', '=', 'pembayaran.id_pembayaran')
            ->leftJoin('tagihan',    'pembayaran.id_tagihan',    '=', 'tagihan.id_tagihan')
            ->leftJoin('booking',    'tagihan.id_booking',       '=', 'booking.id_booking')
            ->leftJoin('users',      'booking.id_user',          '=', 'users.id_user')
            ->leftJoin('kamar',      'booking.id_kamar',         '=', 'kamar.id_kamar')
            ->select([
                'pendapatan.tgl_diterima',
                'users.nama as nama_penyewa',
                'users.no_telepon as telepon',
                'kamar.nomor_kamar',
                'tagihan.periode_bulan',
                'pembayaran.metode_pembayaran',
                'pembayaran.bank',
                'pembayaran.status_pembayaran',
                'pembayaran.jumlah_bayar',
                'pendapatan.nominal as nominal_diterima',
            ])
            ->whereMonth('pendapatan.tgl_diterima', $this->bulan)
            ->whereYear('pendapatan.tgl_diterima', $this->tahun)
            ->orderBy('pendapatan.tgl_diterima', 'desc')
            ->get();

        $namaBulan = Carbon::createFromDate($this->tahun, $this->bulan, 1)
            ->locale('id')
            ->translatedFormat('F Y');

        $totalNominal = $pendapatans->sum('nominal_diterima');

        return view('exports.pendapatan_per_bulan', compact(
            'pendapatans',
            'namaBulan',
            'totalNominal'
        ));
    }

    public function styles(Worksheet $sheet)
    {
        $totalRows = DB::table('pendapatan')
            ->whereMonth('tgl_diterima', $this->bulan)
            ->whereYear('tgl_diterima', $this->tahun)
            ->count();

        $footerRow = max($totalRows + 3, 4);

        // 8 kolom A-H (tanpa Denda)
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 12],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FF00A669']],
            'alignment' => ['horizontal' => 'center'],
        ]);

        $sheet->getStyle('A2:H2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FF1A5C3A']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FFE8F5E9']],
        ]);

        $sheet->getStyle('A' . $footerRow . ':H' . $footerRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FFF3F4F6']],
        ]);

        // Format kolom nominal: G=Jumlah Bayar, H=Total Diterima
        foreach (['G', 'H'] as $col) {
            $sheet->getStyle($col . '3:' . $col . $footerRow)
                  ->getNumberFormat()
                  ->setFormatCode('#,##0');
        }

        return [];
    }

    public function title(): string
    {
        return 'Pendapatan ' . Carbon::createFromDate($this->tahun, $this->bulan, 1)
            ->locale('id')
            ->translatedFormat('F Y');
    }
}
