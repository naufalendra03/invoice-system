<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Payment;

class PaymentController extends Controller
{

/*
|--------------------------------------------------------------------------
| LIST PAYMENT
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| FORM PEMBAYARAN
|--------------------------------------------------------------------------
*/

public function create($id)
{

    $sale = Sale::with('customer','payments')->findOrFail($id);

    $paid = $sale->payments->sum('amount');

    $remaining = $sale->total - $paid;

    return view('payments.create',compact('sale','paid','remaining'));

}

public function detail($id)
{

$sale = \App\Models\Sale::with([
'customer',
'company',
'items.product',
'payments'
])->findOrFail($id);

$paid = $sale->payments->sum('amount');

$remaining = $sale->total - $paid;

return view('payments.detail',compact(
'sale',
'paid',
'remaining'
));

}

/*
|--------------------------------------------------------------------------
| SIMPAN PEMBAYARAN
|--------------------------------------------------------------------------
*/

public function store(Request $request,$id)
{

    $request->validate([

        'amount' => 'required|numeric|min:1',
        'method' => 'nullable|string',
        'notes'  => 'nullable|string'

    ]);


    $sale = Sale::with('payments')->findOrFail($id);


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PAYMENT
    |--------------------------------------------------------------------------
    */

    Payment::create([

        'sale_id'      => $sale->id,
        'payment_date' => now(),
        'amount'       => $request->amount,
        'method'       => $request->method,
        'notes'        => $request->notes

    ]);


    /*
    |--------------------------------------------------------------------------
    | HITUNG TOTAL PEMBAYARAN TERBARU
    |--------------------------------------------------------------------------
    */

    $totalPaid = $sale->payments()->sum('amount');

    $remaining = $sale->total - $totalPaid;


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS INVOICE
    |--------------------------------------------------------------------------
    */

    if($totalPaid >= $sale->total){

        $sale->status = 'paid';

    }else{

        $sale->status = 'partial';

    }

    $sale->save();


    return redirect()
    ->route('sales.index')
    ->with('success','Pembayaran berhasil disimpan');


}

}