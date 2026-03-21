<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

public function index(Request $request)
{
    $search = $request->search;
    $perPage = $request->per_page ?? 10;

    $customers = \App\Models\Customer::when($search, function($query) use ($search){
        $query->where('name','like',"%$search%")
              ->orWhere('phone','like',"%$search%");
    })
    ->latest()
    ->paginate($perPage)
    ->withQueryString();

    return view('customers.index', compact('customers','search','perPage'));
}

public function create()
{
return view('customers.create');
}


public function store(Request $request)
{

$request->validate([
'name'=>'required',
'phone'=>'required'
]);

Customer::create($request->all());

return redirect()->route('customers.index')
->with('success','Customer berhasil ditambahkan');

}


public function edit($id)
{

$customer = Customer::findOrFail($id);

return view('customers.edit',compact('customer'));

}


public function update(Request $request,$id)
{

$customer = Customer::findOrFail($id);

$customer->update($request->all());

return redirect()->route('customers.index')
->with('success','Customer berhasil diupdate');

}


public function destroy($id)
{

$customer = Customer::findOrFail($id);

$customer->delete();

return redirect()->route('customers.index')
->with('success','Customer berhasil dihapus');

}

}