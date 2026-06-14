<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Exports\PiutangExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

/*
|--------------------------------------------------------------------------
| LAPORAN PIUTANG (FINAL)
|--------------------------------------------------------------------------
*/

public function piutang(Request $request)
{
    $search   = $request->search;
    $status   = $request->status;
    $dateFrom = $request->date_from;
    $dateTo   = $request->date_to;
    $perPage  = $request->per_page ?? 10;

    $query = Sale::with(['customer', 'payments'])
        ->whereHas('payments', function ($q) {
            // boleh kosong, hanya agar relasi siap
        }, '>=', 0);

    /*
    | SEARCH
    */
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('invoice_number', 'like', "%{$search}%")
                ->orWhere('surat_jalan_number', 'like', "%{$search}%")
                ->orWhere('po_number', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
        });
    }

    /*
    | FILTER STATUS BERDASARKAN EFFECTIVE STATUS
    */
    if ($status) {

        if ($status === 'overdue') {
            $query->where('status', '!=', 'paid')
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', now()->toDateString());
        }

        elseif ($status === 'unpaid') {
            $query->where('status', 'unpaid')
                ->where(function ($q) {
                    $q->whereNull('due_date')
                      ->orWhereDate('due_date', '>=', now()->toDateString());
                });
        }

        elseif ($status === 'partial') {
            $query->where('status', 'partial')
                ->where(function ($q) {
                    $q->whereNull('due_date')
                      ->orWhereDate('due_date', '>=', now()->toDateString());
                });
        }

        elseif ($status === 'paid') {
            $query->where('status', 'paid');
        }
    }

    /*
    | FILTER DATE
    */
    if ($dateFrom) {
        $query->whereDate('date', '>=', $dateFrom);
    }

    if ($dateTo) {
        $query->whereDate('date', '<=', $dateTo);
    }

    /*
    | AMBIL HANYA PIUTANG:
    | total pembayaran < total invoice
    */
    $query->whereRaw('
        (
            SELECT COALESCE(SUM(payments.amount), 0)
            FROM payments
            WHERE payments.sale_id = sales.id
        ) < sales.total
    ');
/*
|--------------------------------------------------------------------------
| HITUNG RATA-RATA HARI PEMBAYARAN PER CUSTOMER
|--------------------------------------------------------------------------
*/

$customerAverageDays = [];

$allSalesForAverage = Sale::with(['payments'])
    ->whereHas('payments')
    ->get()
    ->groupBy('customer_id');

foreach ($allSalesForAverage as $customerId => $customerSales) {

    $days = [];

    foreach ($customerSales as $customerSale) {

        $lastPayment = $customerSale->payments
            ->sortByDesc('payment_date')
            ->first();

        if ($lastPayment && $customerSale->date) {

            $days[] = \Carbon\Carbon::parse($customerSale->date)
                ->diffInDays(\Carbon\Carbon::parse($lastPayment->payment_date));
        }
    }

    $customerAverageDays[$customerId] = count($days)
        ? round(array_sum($days) / count($days))
        : null;
}
    /*
    | PAGINATION BENAR
    */
    $sales = $query
        ->latest('date')
        ->paginate($perPage)
        ->withQueryString();

    return view('reports.piutang', compact(
    'sales',
    'search',
    'status',
    'dateFrom',
    'dateTo',
    'perPage',
    'customerAverageDays'
));
}

/*
|--------------------------------------------------------------------------
| DASHBOARD PIUTANG + OMSET
|--------------------------------------------------------------------------
*/

public function dashboardPiutang(Request $request)
{

$start = $request->start_date ?? date('Y-m-01');
$end = $request->end_date ?? date('Y-m-d');


/*
|--------------------------------------------------------------------------
| DATA PENJUALAN
|--------------------------------------------------------------------------
*/

$sales = Sale::with('payments','customer')
->whereBetween('date',[$start,$end])
->get();


$totalPiutang = 0;
$totalInvoice = 0;
$customers = [];


foreach($sales as $sale){

$paid = $sale->payments->sum('amount');
$remaining = $sale->total - $paid;

if($remaining > 0){

$totalPiutang += $remaining;
$totalInvoice++;

$customers[$sale->customer_id] = true;

}

}

$totalCustomer = count($customers);


/*
|--------------------------------------------------------------------------
| OMSET PERIODE
|--------------------------------------------------------------------------
*/

$omset = Sale::whereBetween('date',[$start,$end])
->where('status','paid')
->sum('total');


/*
|--------------------------------------------------------------------------
| GRAFIK OMSET BERDASARKAN FILTER (FIX)
|--------------------------------------------------------------------------
*/

$period = CarbonPeriod::create($start, $end);

$labels = [];
$data = [];

foreach ($period as $date) {

    $labels[] = $date->format('d M');

    $daily = Sale::whereDate('date', $date)
        ->where('status','paid')
        ->sum('total');

    $data[] = $daily;
}

/*
|--------------------------------------------------------------------------
| RETURN VIEW
|--------------------------------------------------------------------------
*/

return view('reports.dashboard_piutang',compact(

'totalPiutang',
'totalInvoice',
'totalCustomer',
'omset',

'start',
'end',

'labels',
'data'

));

}


/*
|--------------------------------------------------------------------------
| DETAIL PIUTANG
|--------------------------------------------------------------------------
*/

public function detailPiutang($id)
{
    $sale = Sale::with([
        'customer',
        'company',
        'items.product',
        'payments'
    ])->findOrFail($id);

    $paid = $sale->payments->sum('amount');
    $remaining = $sale->total - $paid;

    /*
    |--------------------------------------------------------------------------
    | UMUR PIUTANG
    |--------------------------------------------------------------------------
    */
    $aging = 0;

    if ($sale->due_date) {
        $aging = now()->startOfDay()->diffInDays($sale->due_date, false);
    }

    /*
    |--------------------------------------------------------------------------
    | AVERAGE DAY TO PAY CUSTOMER
    |--------------------------------------------------------------------------
    */
    $customerSales = Sale::with('payments')
        ->where('customer_id', $sale->customer_id)
        ->whereHas('payments')
        ->get();

    $days = [];

    foreach ($customerSales as $customerSale) {
        $lastPayment = $customerSale->payments
            ->sortByDesc('payment_date')
            ->first();

        if ($lastPayment && $customerSale->date) {
            $days[] = \Carbon\Carbon::parse($customerSale->date)
                ->diffInDays(\Carbon\Carbon::parse($lastPayment->payment_date));
        }
    }

    $averageDayToPay = count($days)
        ? round(array_sum($days) / count($days))
        : null;

    return view('reports.piutang_detail', compact(
        'sale',
        'paid',
        'remaining',
        'aging',
        'averageDayToPay'
    ));
}

public function export(Request $request)
{
    $query = Sale::with(['customer', 'payments']);

    /*
    | SEARCH
    */
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('invoice_number', 'like', '%' . $request->search . '%')
                ->orWhere('surat_jalan_number', 'like', '%' . $request->search . '%')
                ->orWhere('po_number', 'like', '%' . $request->search . '%')
                ->orWhereHas('customer', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search . '%');
                });
        });
    }

    /*
    | FILTER STATUS BERDASARKAN EFFECTIVE STATUS
    */
    if ($request->status) {

        if ($request->status === 'overdue') {
            $query->where('status', '!=', 'paid')
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', now()->toDateString());
        }

        elseif ($request->status === 'unpaid') {
            $query->where('status', 'unpaid')
                ->where(function ($q) {
                    $q->whereNull('due_date')
                      ->orWhereDate('due_date', '>=', now()->toDateString());
                });
        }

        elseif ($request->status === 'partial') {
            $query->where('status', 'partial')
                ->where(function ($q) {
                    $q->whereNull('due_date')
                      ->orWhereDate('due_date', '>=', now()->toDateString());
                });
        }

        elseif ($request->status === 'paid') {
            $query->where('status', 'paid');
        }
    }

    /*
    | FILTER DATE
    */
    if ($request->date_from) {
        $query->whereDate('date', '>=', $request->date_from);
    }

    if ($request->date_to) {
        $query->whereDate('date', '<=', $request->date_to);
    }

    /*
    | HANYA PIUTANG
    */
    $query->whereRaw('
        (
            SELECT COALESCE(SUM(payments.amount), 0)
            FROM payments
            WHERE payments.sale_id = sales.id
        ) < sales.total
    ');

    $data = $query->latest('date')->get();

    return Excel::download(
        new PiutangExport($data),
        'laporan-piutang.xlsx'
    );
}
/*
|--------------------------------------------------------------------------
| KIRIM LAPORAN OMSET KE WHATSAPP
|--------------------------------------------------------------------------
*/

public function sendOmsetWhatsapp(Request $request)
{
    $start = $request->start_date;
    $end = $request->end_date;

    if(!$start || !$end){
        return back()->with('error','Tanggal laporan belum dipilih');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA PENJUALAN
    |--------------------------------------------------------------------------
    */

    $sales = Sale::whereBetween('date',[$start,$end])
        ->where('status','paid') // 🔥 penting biar sesuai omset
        ->get();

    $total = $sales->sum('total');
    $count = $sales->count();

    // ✅ HITUNG CUSTOMER UNIK
    $customerCount = $sales->pluck('customer_id')->unique()->count();


    /*
    |--------------------------------------------------------------------------
    | FORMAT TANGGAL
    |--------------------------------------------------------------------------
    */

    $startFormat = Carbon::parse($start)->format('d M Y');
    $endFormat = Carbon::parse($end)->format('d M Y');


    /*
    |--------------------------------------------------------------------------
    | FORMAT PESAN WA
    |--------------------------------------------------------------------------
    */

    $message = "
📊 LAPORAN OMSET

Periode : $startFormat - $endFormat

Jumlah Invoice : $count
Jumlah Customer : $customerCount
Total Omset : Rp ".number_format($total)."

Invoice System
";


    /*
    |--------------------------------------------------------------------------
    | NOMOR OWNER
    |--------------------------------------------------------------------------
    */

    $phone = env('OWNER_PHONE');

    $phone = preg_replace('/[^0-9]/','',$phone);

    if(substr($phone,0,1) == "0"){
        $phone = "62".substr($phone,1);
    }


    /*
    |--------------------------------------------------------------------------
    | KIRIM WA
    |--------------------------------------------------------------------------
    */

    Http::withHeaders([
        'Authorization'=>env('FONNTE_TOKEN')
    ])->post('https://api.fonnte.com/send',[
        'target'=>$phone,
        'message'=>$message
    ]);

    return back()->with('success','Laporan omset berhasil dikirim ke WhatsApp');
}

}