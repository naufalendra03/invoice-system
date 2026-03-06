<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class ReportController extends Controller
{

public function piutang()
{

$sales = Sale::with('customer','payments')
->where('status','!=','paid')
->latest()
->get();

return view('reports.piutang',compact('sales'));

}

public function dashboardPiutang()
{

$sales = \App\Models\Sale::with('payments','customer')->get();

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

$omset = \App\Models\Sale::whereMonth('date',date('m'))
->sum('total');

return view('reports.dashboard_piutang',compact(

'totalPiutang',
'totalInvoice',
'totalCustomer',
'omset'

));

}
}