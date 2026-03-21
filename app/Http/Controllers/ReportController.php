<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{

/*
|--------------------------------------------------------------------------
| LAPORAN PIUTANG
|--------------------------------------------------------------------------
*/

public function piutang(Request $request)
{
    $search = $request->search;
    $perPage = $request->per_page ?? 10;

    $sales = \App\Models\Sale::with(['customer','payments'])
        ->when($search, function($query) use ($search){
            $query->where('invoice_number','like',"%$search%")
                  ->orWhereHas('customer', function($q) use ($search){
                      $q->where('name','like',"%$search%");
                  });
        })
        ->latest()
        ->paginate($perPage)
        ->withQueryString();

    // FILTER hanya yg masih punya hutang
    $sales->getCollection()->transform(function($sale){
        $paid = $sale->payments->sum('amount');
        $remaining = $sale->total - $paid;

        if($remaining <= 0){
            return null;
        }

        return $sale;
    });

    $sales->setCollection(
        $sales->getCollection()->filter()
    );

    return view('reports.piutang', compact('sales','search','perPage'));
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

$aging = now()->startOfDay()->diffInDays($sale->due_date,false);

}


return view('reports.piutang_detail',compact(
'sale',
'paid',
'remaining',
'aging'
));

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