<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdukPerBulanExport implements FromCollection, WithTitle, WithHeadings
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $startDate = Carbon::createFromDate($this->tahun, $this->bulan)->startOfMonth();
        $endDate = Carbon::createFromDate($this->tahun, $this->bulan)->endOfMonth();

        $produk = OrderItem::with(['product', 'order'])
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy('product.nama')
            ->map(function ($items, $namaProduk) {
                $jumlah = $items->sum('kuantitas');
                $hargaTotal = $items->sum(function ($item) {
                    return $item->kuantitas * $item->harga;
                });

                return [
                    'Nama Produk' => $namaProduk,
                    'Jumlah Laku (pcs)' => $jumlah,
                    'Harga Total' => $hargaTotal,
                ];
            })
            ->values()
            ->map(function ($item, $index) {
                return [
                    $index + 1,
                    $item['Nama Produk'],
                    $item['Jumlah Laku (pcs)'],
                    $item['Harga Total'],
                ];
            });

        return collect($produk);
    }

    public function headings(): array
    {
        return ['No', 'Nama Produk', 'Jumlah Laku (pcs)', 'Harga Total'];
    }

    public function title(): string
    {
        return Carbon::create()->month($this->bulan)->locale('id')->monthName;
    }
}
