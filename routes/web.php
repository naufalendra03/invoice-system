<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $companies = Company::count();
    $customers = Customer::count();
    $products = Product::count();
    $sales = Sale::count();

    // Invoice terbaru
    $latestSales = Sale::with('customer')
        ->latest()
        ->take(5)
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
    | HUTANG JATUH TEMPO
    |--------------------------------------------------------------------------
    */

    $overdues = Sale::where('status','!=','paid')
        ->whereNotNull('due_date')
        ->whereDate('due_date','<', now())
        ->with('customer')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | HUTANG H-3 SEBELUM JATUH TEMPO
    |--------------------------------------------------------------------------
    */

    $dueSoon = Sale::where('status','!=','paid')
        ->whereNotNull('due_date')
        ->whereBetween('due_date', [now(), now()->addDays(3)])
        ->with('customer')
        ->get();


    return view('dashboard', compact(
        'companies',
        'customers',
        'products',
        'sales',
        'latestSales',
        'overdues',
        'dueSoon',     // ← ini yang tadi kurang
        'months',
        'totals'
    ));

})->middleware(['auth','verified'])->name('dashboard');



/*
|--------------------------------------------------------------------------
| AUTH REQUIRED
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class,'destroy'])->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | MASTER DATA
    |--------------------------------------------------------------------------
    */

    Route::resource('companies', CompanyController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);


    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI
    |--------------------------------------------------------------------------
    */

    Route::resource('sales', SaleController::class);
    Route::resource('payments', PaymentController::class);

    // Print invoice + surat jalan
    Route::get('/sales/{id}/print',[SaleController::class,'print'])
        ->name('sales.print');

    // Pembayaran invoice
    Route::get('/sales/{id}/payment',[PaymentController::class,'create'])
        ->name('sales.payment');

    Route::post('/sales/{id}/payment',[PaymentController::class,'store'])
        ->name('sales.payment.store');


    /*
    |--------------------------------------------------------------------------
    | REPORT
    |--------------------------------------------------------------------------
    */

    Route::get('/reports/piutang',[ReportController::class,'piutang'])
        ->name('reports.piutang');

    Route::get('/reports/dashboard-piutang',[ReportController::class,'dashboardPiutang'])
        ->name('reports.dashboard.piutang');

});


require __DIR__.'/auth.php';