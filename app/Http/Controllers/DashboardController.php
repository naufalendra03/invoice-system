<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{

public function index()
{

/*
|--------------------------------------------------------------------------
| TOTAL DATA
|--------------------------------------------------------------------------
*/

$companies = Company::count();
$customers = Customer::count();
$products = Product::count();
$sales = Sale::count();


/*
|--------------------------------------------------------------------------
| INVOICE TERBARU (MAX 3)
|--------------------------------------------------------------------------
*/

$latestSales = Sale::with('customer')
    ->latest()
    ->take(3)
    ->get();


/*
|--------------------------------------------------------------------------
| GRAFIK OMZET BULANAN
|--------------------------------------------------------------------------
*/

$omzet = Sale::selectRaw('MONTH(date) as month, SUM(total) as total')
    ->where('status','paid')
    ->groupBy('month')
    ->orderBy('month')
    ->get();

$months = [];
$totals = [];

foreach($omzet as $row){

    $months[] = date("F", mktime(0,0,0,$row->month,1));
    $totals[] = $row->total;

}


/*
|--------------------------------------------------------------------------
| INVOICE OVERDUE (PRIORITAS 1)
|--------------------------------------------------------------------------
*/

$overdues = Sale::where('status','!=','paid')
    ->whereNotNull('due_date')
    ->whereDate('due_date','<', now()->toDateString()) // ✅ FIX
    ->with('customer')
    ->orderBy('due_date','asc')
    ->take(5)
    ->get();


/*
|--------------------------------------------------------------------------
| NOTIF H-3 JATUH TEMPO (PRIORITAS 2)
|--------------------------------------------------------------------------
*/

$dueSoon = Sale::where('status','!=','paid')
    ->whereNotNull('due_date')
    ->whereBetween('due_date', [
        now()->startOfDay(),          // ✅ termasuk hari ini
        now()->addDays(3)->endOfDay() // ✅ sampai H+3 full
    ])
    ->with('customer')
    ->orderBy('due_date','asc')
    ->take(5)
    ->get();


/*
|--------------------------------------------------------------------------
| GABUNG NOTIF (OVERDUE DI ATAS)
|--------------------------------------------------------------------------
*/

$notifications = $overdues->concat($dueSoon);


/*
|--------------------------------------------------------------------------
| TOTAL STATUS
|--------------------------------------------------------------------------
*/

$unpaid = Sale::where('status','unpaid')->count();
$paid = Sale::where('status','paid')->count();

$totalSales = Sale::sum('total');


/*
|--------------------------------------------------------------------------
| RETURN VIEW
|--------------------------------------------------------------------------
*/

return view('dashboard',compact(

'companies',
'customers',
'products',
'sales',

'latestSales',

'months',
'totals',

'overdues',
'dueSoon',
'notifications', // ✅ penting

'unpaid',
'paid',
'totalSales'

));

}

}