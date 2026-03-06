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

public function index()
{

$payments = Payment::with('sale.customer')
->latest()
->paginate(10);

return view('payments.index',compact('payments'));

}



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
| HITUNG TOTAL PEMBAYARAN
|--------------------------------------------------------------------------
*/

$totalPaid = $sale->payments()->sum('amount') + $request->amount;

$remaining = $sale->total - $totalPaid;


/*
|--------------------------------------------------------------------------
| UPDATE STATUS INVOICE
|--------------------------------------------------------------------------
*/

if($remaining <= 0){

$sale->status = 'paid';

}elseif($totalPaid > 0){

$sale->status = 'partial';

}else{

$sale->status = 'unpaid';

}

$sale->save();


return redirect()
->route('sales.index')
->with('success','Pembayaran berhasil disimpan');

}

}