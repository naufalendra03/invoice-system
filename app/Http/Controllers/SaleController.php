<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SalesItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SaleController extends Controller
{

/*
=====================================
LIST INVOICE
=====================================
*/

public function index(Request $request)
{
    $search = $request->search;
    $status = $request->status;
    $dateFrom = $request->date_from;
    $dateTo = $request->date_to;
    $perPage = $request->per_page ?? 10;

    $sales = Sale::with(['customer', 'company'])
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('surat_jalan_number', 'like', "%{$search}%")
                    ->orWhere('po_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customer) use ($search) {
                        $customer->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('company', function ($company) use ($search) {
                        $company->where('name', 'like', "%{$search}%");
                    });
            });
        })

        ->when($status, function ($query) use ($status) {

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

        })

        ->when($dateFrom, function ($query) use ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        })

        ->when($dateTo, function ($query) use ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        })

        ->latest('date')
        ->paginate($perPage)
        ->withQueryString();

    foreach ($sales as $sale) {
        if (
            $sale->status != 'paid' &&
            $sale->due_date &&
            \Carbon\Carbon::parse($sale->due_date)->isPast()
        ) {
            $sale->status = 'overdue';
        }
    }

    return view('sales.index', compact(
        'sales',
        'search',
        'status',
        'dateFrom',
        'dateTo',
        'perPage'
    ));
}
    /*
    |--------------------------------------------------------------------------
    | HANDLE STATUS OVERDUE (TANPA SAVE KE DB)
    |--------------------------------------------------------------------------
    */

/*
=====================================
FORM CREATE INVOICE
=====================================
*/

public function create()
{

$customers = Customer::all();
$products = Product::all();
$companies = Company::all();

return view('sales.create',compact(
'customers',
'products',
'companies'
));

}


/*
=====================================
SIMPAN INVOICE
=====================================
*/

public function store(Request $request)
{

$request->validate([
'company_id'=>'required',
'customer_id'=>'required',
'ongkir' => 'nullable|numeric|min:0',
'date'=>'required'
]);

$company = Company::find($request->company_id);

$invoiceNumber = $this->generateInvoiceNumber();
$suratJalanNumber = $this->generateSuratJalan($company->code);

$subtotalBarang = 0;
/*
CREATE SALE
*/

$sale = Sale::create([
'company_id'=>$request->company_id,
'customer_id'=>$request->customer_id,
'invoice_number'=>$invoiceNumber,
'surat_jalan_number'=>$suratJalanNumber,
'po_number'=>$request->po_number,
'date'=>$request->date,
'due_date'=>$request->due_date,
'total'=>$request->total,
'ongkir' => $request->ongkir ?? 0,
'status'=>'unpaid'
]);


/*
SIMPAN ITEM
*/

foreach($request->product_id as $key => $product){

    if(!$product){
        continue;
    }

    $qty = $this->parseNumber($request->qty[$key] ?? 0);
    $price = $this->parseNumber($request->price[$key] ?? 0);

    $subtotal = $qty * $price;

    SalesItem::create([
        'sale_id' => $sale->id,
        'product_id' => $product,
        'price' => $price,
        'qty' => $qty,
        'subtotal' => $subtotal
    ]);

    $subtotalBarang += $subtotal;
}

/*
GENERATE PDF
*/

$sale = Sale::with('customer','company','items.product')->find($sale->id);

$pdf = Pdf::loadView('pdf.invoice', compact('sale'));

$filename = 'invoice-'.$sale->invoice_number.'.pdf';

Storage::disk('public')->put('invoices/'.$filename, $pdf->output());

$sale->pdf_path = 'storage/invoices/'.$filename;
$sale->save();


/*
=====================================
KIRIM WA OTOMATIS (FIX)
=====================================
*/

$sale = Sale::with('customer')->find($sale->id);

$this->sendWhatsappNotification($sale);


return redirect()->route('sales.index')
->with('success','Invoice berhasil dibuat');

}


/*
=====================================
DETAIL INVOICE
=====================================
*/

public function detail($id)
{

$sale = Sale::with([
'customer',
'company',
'items.product',
'payments'
])->findOrFail($id);

$paid = $sale->payments->sum('amount');

$remaining = $sale->total - $paid;

return view('sales.detail',compact(
'sale',
'paid',
'remaining'
));

}


/*
=====================================
DELETE
=====================================
*/

public function destroy($id)
{

$sale = Sale::findOrFail($id);

$sale->delete();

return redirect()->route('sales.index')
->with('success','Invoice berhasil dihapus');

}


/*
=====================================
GENERATE NOMOR INVOICE
=====================================
*/

private function generateInvoiceNumber()
{

$last = Sale::latest()->first();

$number = $last ? $last->id + 1 : 1;

return 'INV-'.str_pad($number,4,'0',STR_PAD_LEFT);

}


/*
=====================================
GENERATE NOMOR SURAT JALAN
=====================================
*/

private function generateSuratJalan($companyCode)
{

$month = $this->romanMonth(date('n'));

$year = date('y');

$count = Sale::count() + 1;

return $count.'/'.$companyCode.'/'.$month.'/'.$year;

}


/*
=====================================
ROMAN MONTH
=====================================
*/

private function romanMonth($month)
{

$romans = [

1=>'I',
2=>'II',
3=>'III',
4=>'IV',
5=>'V',
6=>'VI',
7=>'VII',
8=>'VIII',
9=>'IX',
10=>'X',
11=>'XI',
12=>'XII'

];

return $romans[$month];

}


/*
=====================================
PRINT INVOICE
=====================================
*/

public function printInvoice($id)
{

$sale = Sale::with('customer','company','items.product')
->findOrFail($id);

$pdf = Pdf::loadView('pdf.invoice',compact('sale'))
->setPaper('A4');

return $pdf->stream('invoice-'.$sale->invoice_number.'.pdf');

}


/*
=====================================
PRINT SURAT JALAN
=====================================
*/

public function printSuratJalan($id)
{

$sale = Sale::with('customer','company','items.product')
->findOrFail($id);

$pdf = Pdf::loadView('pdf.surat_jalan',compact('sale'))
->setPaper('A4');

$filename = str_replace('/','-',$sale->surat_jalan_number);

return $pdf->stream('surat-jalan-'.$filename.'.pdf');

}

public function edit($id)
{
    $sale = Sale::with('items.product')->findOrFail($id);

    if($sale->status == 'paid'){
        return redirect()->back()->with('error','Invoice sudah lunas, tidak bisa diedit');
    }

    $products = Product::all();

    return view('sales.edit', compact('sale','products'));
}

public function update(Request $request, $id)
{
    $sale = Sale::findOrFail($id);

    if ($sale->status == 'paid') {
        return back()->with('error', 'Invoice sudah lunas');
    }

    $request->validate([
        'ongkir' => 'nullable|numeric|min:0',
        'product_id' => 'required|array',
        'qty' => 'required|array',
        'price' => 'required|array',
    ]);

    SalesItem::where('sale_id', $sale->id)->delete();

    $subtotalBarang = 0;

    foreach($request->product_id as $i => $product){

    if(!$product){
        continue;
    }

    $qty = $this->parseNumber($request->qty[$i] ?? 0);
    $price = $this->parseNumber($request->price[$i] ?? 0);

    $subtotal = $qty * $price;

    SalesItem::create([
        'sale_id' => $sale->id,
        'product_id' => $product,
        'qty' => $qty,
        'price' => $price,
        'subtotal' => $subtotal
    ]);

    $subtotalBarang += $subtotal;
}

    $ongkir = $request->ongkir ?? 0;

    $grandTotal = $subtotalBarang + $ongkir;

    $sale->update([
        'total' => $grandTotal,
        'ongkir' => $ongkir
    ]);

    return redirect()->route('sales.detail', $sale->id)
        ->with('success', 'Invoice berhasil diupdate');
}
/*
=====================================
KIRIM WHATSAPP
=====================================
*/

public function sendWhatsapp($id)
{

$sale = Sale::with('customer')->findOrFail($id);

$this->sendWhatsappNotification($sale);

return back()->with('success','Notifikasi invoice berhasil dikirim ke WhatsApp');

}


/*
=====================================
PUBLIC INVOICE PAGE
=====================================
*/

public function publicInvoice($id)
{

$sale = Sale::with([
'customer',
'company',
'items.product',
'payments'
])->findOrFail($id);

$paid = $sale->payments->sum('amount');

$remaining = $sale->total - $paid;

return view('public.invoice',compact(
'sale',
'paid',
'remaining'
));

}

private function parseNumber($value)
{
    if ($value === null || $value === '') {
        return 0;
    }

    $value = trim((string) $value);

    // Jika pakai koma desimal: 1,5 atau 60.000,50
    if (str_contains($value, ',')) {
        $value = str_replace('.', '', $value);   // hapus titik ribuan
        $value = str_replace(',', '.', $value);  // koma jadi desimal
    }

    // Jika tidak ada koma, biarkan titik sebagai desimal: 60000.00
    return (float) $value;
}

private function sendWhatsappNotification($sale)
{
    // ✅ ambil nomor OWNER dari .env
    $phone = env('OWNER_PHONE');

    if(!$phone) return;

    // format nomor
    $phone = preg_replace('/[^0-9]/','',$phone);

    if(substr($phone,0,1) == "0"){
        $phone = "62".substr($phone,1);
    }

    // link invoice
    $invoiceLink = url('/invoice/'.$sale->id);

    // format tanggal
    $dueDate = $sale->due_date 
    ? \Carbon\Carbon::parse($sale->due_date)->format('d M Y')
    : '-';

    // pesan WA (lebih profesional)
    $message = "
📊 *NOTIF INVOICE BARU*

Invoice : {$sale->invoice_number}
Customer : {$sale->customer->name}
Total : Rp ".number_format($sale->total)."
Ongkir : Rp ".number_format($sale->ongkir ?? 0)."
Jatuh Tempo : {$dueDate}
Status : ".strtoupper($sale->status)."

🔗 Lihat Invoice:
$invoiceLink

Invoice System
";

    // kirim WA ke OWNER
    Http::withHeaders([
        'Authorization' => env('FONNTE_TOKEN')
    ])->post('https://api.fonnte.com/send',[
        'target'=>$phone,
        'message'=>$message
    ]);
}
}