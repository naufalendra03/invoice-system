<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class DashboardController extends Controller
{

public function index()
{

$overdues = Sale::where('status','!=','paid')
->whereDate('due_date','<',now())
->with('customer')
->get();


$unpaid = Sale::where('status','unpaid')->count();

$paid = Sale::where('status','paid')->count();

$totalSales = Sale::sum('total');


return view('dashboard',compact(

'overdues',
'unpaid',
'paid',
'totalSales'

));

}

}
