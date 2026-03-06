<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SalesItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{

    // =========================
    // LIST INVOICE
    // =========================

    public function index()
{

    $sales = Sale::with('customer','company')
    ->latest()
    ->paginate(10);

    foreach($sales as $sale){

        if($sale->status != 'paid' && $sale->due_date && $sale->due_date < now()){

            $sale->status = 'overdue';

        }

    }

    return view('sales.index',compact('sales'));

}



    // =========================
    // FORM CREATE INVOICE
    // =========================

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



    // =========================
    // SIMPAN INVOICE
    // =========================

    public function store(Request $request)
    {

        $request->validate([

            'company_id'=>'required',
            'customer_id'=>'required',
            'date'=>'required'

        ]);


        // AMBIL DATA COMPANY
        $company = Company::find($request->company_id);


        // AUTO NOMOR INVOICE
        $invoiceNumber = $this->generateInvoiceNumber();


        // AUTO NOMOR SURAT JALAN
        $suratJalanNumber = $this->generateSuratJalan($company->code);



        $sale = Sale::create([

            'company_id'=>$request->company_id,
            'customer_id'=>$request->customer_id,
            'invoice_number'=>$invoiceNumber,
            'surat_jalan_number'=>$suratJalanNumber,
            'date'=>$request->date,
            'due_date'=>$request->due_date,
            'total'=>$request->total,
            'status'=>'unpaid'

        ]);



        // SIMPAN ITEM BARANG
        foreach($request->product_id as $key=>$product){

            $qty = $request->qty[$key];

            SalesItem::create([

                'sale_id'=>$sale->id,
                'product_id'=>$product,
                'price'=>$request->price[$key],
                'qty'=>$qty,
                'subtotal'=>$request->subtotal[$key]

            ]);


            // UPDATE STOK PRODUK
            $productModel = Product::find($product);

            $productModel->stock -= $qty;

            $productModel->save();

        }



        return redirect()->route('sales.index')
        ->with('success','Invoice berhasil dibuat');

    }



    // =========================
    // DETAIL INVOICE
    // =========================

    public function show($id)
    {

        $sale = Sale::with('customer','company','items.product')
        ->findOrFail($id);

        return view('sales.show',compact('sale'));

    }



    // =========================
    // DELETE INVOICE
    // =========================

    public function destroy($id)
    {

        $sale = Sale::findOrFail($id);

        $sale->delete();

        return redirect()->route('sales.index')
        ->with('success','Invoice berhasil dihapus');

    }



    // =========================
    // GENERATE NOMOR INVOICE
    // =========================

    private function generateInvoiceNumber()
    {

        $last = Sale::latest()->first();

        $number = $last ? $last->id + 1 : 1;

        return 'INV-'.str_pad($number,4,'0',STR_PAD_LEFT);

    }



    // =========================
    // GENERATE NOMOR SURAT JALAN
    // =========================

    private function generateSuratJalan($companyCode)
    {

        $month = $this->romanMonth(date('n'));

        $year = date('y');

        $count = Sale::count() + 1;

        return $count.'/'.$companyCode.'/'.$month.'/'.$year;

    }



    // =========================
    // KONVERSI BULAN ROMAWI
    // =========================

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

    public function print($id)
{

    $sale = Sale::with('customer','company','items.product')
    ->findOrFail($id);

    $pdf = Pdf::loadView('sales.print',compact('sale'));

    return $pdf->stream('invoice-'.$sale->invoice_number.'.pdf');

}

}