<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithEvents,
    WithCustomStartCell,
    ShouldAutoSize
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PiutangExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected Collection $sales;

    public function __construct($sales)
    {
        $this->sales = collect($sales);
    }

    public function collection()
    {
        return $this->sales;
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            'TANGGAL',
            'NO.I',
            'NO.SJ',
            'NO.PO',
            'CUSTOMER',
            'RP',
            'TGL.BYR',
            'KET',
        ];
    }

    public function map($sale): array
{
    $lastPayment = $sale->payments
        ->sortByDesc('payment_date')
        ->first();

    return [
        $sale->date
            ? Carbon::parse($sale->date)->format('d/m/Y')
            : '-',

        str_replace('INV-', '', $sale->invoice_number),

        $sale->surat_jalan_number ?? '-',

        $sale->po_number ?? '-',

        $sale->customer->name ?? '-',

        (float) $sale->total,

        $lastPayment
            ? Carbon::parse($lastPayment->payment_date)->format('d/m/Y')
            : '-',

        '', // KET kosong untuk input manual
    ];
}

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $sheet->setCellValue('A1', 'BP');

                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('A3:H' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getStyle('A3:H3')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('F4:F' . $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                $sheet->getStyle('H4:H' . $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                $sheet->getColumnDimension('A')->setWidth(14);
                $sheet->getColumnDimension('B')->setWidth(10);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(18);
                $sheet->getColumnDimension('E')->setWidth(32);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(15);
            }
        ];
    }
}