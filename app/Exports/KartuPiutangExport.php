<?php

namespace App\Exports;

use App\Models\Sale;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KartuPiutangExport implements FromArray, WithStyles, ShouldAutoSize
{
    protected $search;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($search = null, $dateFrom = null, $dateTo = null)
    {
        $this->search = $search;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['KARTU PIUTANG / HISTORY BARANG CUSTOMER'];
        $rows[] = [];
        $rows[] = [
            'No',
            'Tanggal',
            'No. Invoice',
            'No. SJ',
            'No. PO',
            'Customer',
            'Nama & Uraian Barang',
            'Satuan',
            'Banyaknya',
            'Harga @Rp',
            'Jumlah',
            'Total',
            'Ongkir'
        ];

        $sales = Sale::with(['customer', 'items.product'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', "%{$this->search}%")
                        ->orWhere('surat_jalan_number', 'like', "%{$this->search}%")
                        ->orWhere('po_number', 'like', "%{$this->search}%")
                        ->orWhereHas('customer', function ($customer) {
                            $customer->where('name', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('items.product', function ($product) {
                            $product->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('date', '<=', $this->dateTo);
            })
            ->latest()
            ->get();

        $no = 1;

        foreach ($sales as $sale) {
            foreach ($sale->items as $index => $item) {
                $rows[] = [
                    $index == 0 ? $no : '',
                    $index == 0 ? Carbon::parse($sale->date)->format('d/m/Y') : '',
                    $index == 0 ? str_replace('INV-', '', $sale->invoice_number) : '',
                    $index == 0 ? ($sale->surat_jalan_number ?? '-') : '',
                    $index == 0 ? ($sale->po_number ?? '-') : '',
                    $index == 0 ? ($sale->customer->name ?? '-') : '',
                    $item->product->name ?? '-',
                    $item->product->unit ?? '-',
                    $this->formatQty($item->qty),
                    $item->price,
                    $item->subtotal,
                    $index == 0 ? $sale->items->sum('subtotal') : '',
                    $index == 0 ? ($sale->ongkir ?? 0) : '',
                ];
            }

            $no++;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        $sheet->mergeCells('A1:M1');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->getStyle('A3:M3')->getFont()->setBold(true);

        $sheet->getStyle('A3:M3')->getFill()
            ->setFillType('solid')
            ->getStartColor()
            ->setARGB('FFD9D9D9');

        $sheet->getStyle("A3:M{$highestRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle('thin');

        $sheet->getStyle("J4:M{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        $sheet->getStyle("A3:M{$highestRow}")
            ->getAlignment()
            ->setVertical('top');

        $sheet->getStyle("A3:E{$highestRow}")
            ->getAlignment()
            ->setHorizontal('center');

        $sheet->getStyle("H3:I{$highestRow}")
            ->getAlignment()
            ->setHorizontal('center');

        $sheet->getStyle("J3:M{$highestRow}")
            ->getAlignment()
            ->setHorizontal('right');

        return [];
    }

    private function formatQty($qty)
    {
        return rtrim(rtrim(number_format($qty, 3, ',', ''), '0'), ',');
    }
}