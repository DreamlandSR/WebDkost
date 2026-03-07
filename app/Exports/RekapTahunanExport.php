<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapTahunanExport implements WithMultipleSheets
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function sheets(): array
    {
        $sheets = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $sheets[] = new ProdukPerBulanExport($bulan, $this->tahun);
        }

        return $sheets;
    }
}
