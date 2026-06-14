<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Exports\KartuPiutangExport;
use Maatwebsite\Excel\Facades\Excel;

class KartuPiutangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $perPage = $request->per_page ?? 10;

        $sales = $this->queryData($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('reports.kartu-piutang', compact(
            'sales',
            'search',
            'dateFrom',
            'dateTo',
            'perPage'
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new KartuPiutangExport(
                $request->search,
                $request->date_from,
                $request->date_to
            ),
            'kartu-piutang-history-barang.xlsx'
        );
    }

    private function queryData(Request $request)
    {
        $search = $request->search;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        return Sale::with([
                'customer',
                'company',
                'items.product',
                'payments'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('surat_jalan_number', 'like', "%{$search}%")
                        ->orWhere('po_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customer) use ($search) {
                            $customer->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('items.product', function ($product) use ($search) {
                            $product->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('date', '<=', $dateTo);
            });
    }
}