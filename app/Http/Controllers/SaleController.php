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
    $perPage = $request->per_page ?? 10;

    $sales = \App\Models\Sale::with(['customer','company'])
        ->when($search, function($query) use ($search){
            $query->where('invoice_number','like',"%$search%")
                  ->orWhereHas('customer', function($q) use ($search){
                      $q->where('name','like',"%$search%");
                  })
                  ->orWhereHas('company', function($q) use ($search){
                      $q->where('name','like',"%$search%");
                  });
        })
        ->latest()
        ->paginate($perPage)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | HANDLE STATUS OVERDUE (TANPA SAVE KE DB)
    |--------------------------------------------------------------------------
    */

    foreach($sales as $sale){

        if(
            $sale->status != 'paid' &&
            $sale->due_date &&
            \Carbon\Carbon::parse($sale->due_date)->isPast()
        ){
            $sale->status = 'overdue';
        }

    }

    return view('sales.index', compact('sales','search','perPage'));
}


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
'date'=>'required'
]);


$company = Company::find($request->company_id);

$invoiceNumber = $this->generateInvoiceNumber();

$suratJalanNumber = $this->generateSuratJalan($company->code);


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
'status'=>'unpaid'

]);


/*
SIMPAN ITEM
*/

foreach($request->product_id as $key=>$product){

$qty = $request->qty[$key];

SalesItem::create([

'sale_id'=>$sale->id,
'product_id'=>$product,
'price'=>$request->price[$key],
'qty'=>$qty,
'subtotal'=>$request->subtotal[$key]

]);

$productModel = Product::find($product);

$productModel->save();

}


/*
GENERATE PDF INVOICE
*/

$sale = Sale::with('customer','company','items.product')->find($sale->id);

$pdf = Pdf::loadView('pdf.invoice', compact('sale'));

$filename = 'invoice-'.$sale->invoice_number.'.pdf';

Storage::disk('public')->put('invoices/'.$filename, $pdf->output());

$sale->pdf_path = 'storage/invoices/'.$filename;

$sale->save();


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


/*
=====================================
KIRIM WHATSAPP
=====================================
*/

public function sendWhatsapp($id)
{

$sale = Sale::with('customer')->findOrFail($id);


/*
NOMOR OWNER
*/

$phone = env('OWNER_PHONE');

$phone = preg_replace('/[^0-9]/','',$phone);

if(substr($phone,0,1) == "0"){
$phone = "62".substr($phone,1);
}


/*
LINK PUBLIC INVOICE
*/

$invoiceLink = url('/invoice/'.$sale->id);


/*
PESAN WA
*/

$message = "
📄 INVOICE BARU

Invoice : {$sale->invoice_number}
Customer : {$sale->customer->name}
Total : Rp ".number_format($sale->total)."
Status : ".strtoupper($sale->status)."

Klik link berikut untuk melihat invoice:
$invoiceLink
";


/*
KIRIM WA
*/

Http::withHeaders([
'Authorization' => env('FONNTE_TOKEN')
])->post('https://api.fonnte.com/send',[

'target'=>$phone,
'message'=>$message

]);


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

}