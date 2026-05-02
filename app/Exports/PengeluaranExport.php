<?php

namespace App\Exports;

use App\Models\pengeluaran;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengeluaranExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        return view('exports.pengeluaran', [
            'pengeluarans' => pengeluaran::orderBy('tgl_transaksi', 'desc')->get()
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Styling the header row
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FF00A669'], // WebDkost green color
            ],
        ]);

        // Calculate total rows for styling the footer
        $totalRows = pengeluaran::count() + 1; // +1 for header
        $footerRow = $totalRows + 1;

        // Styling the footer row
        $sheet->getStyle('A' . $footerRow . ':E' . $footerRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFF3F4F6'], // Light gray
            ],
        ]);

        // Format nominal column to standard number format
        $sheet->getStyle('E2:E' . $footerRow)->getNumberFormat()->setFormatCode('#,##0');

        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
