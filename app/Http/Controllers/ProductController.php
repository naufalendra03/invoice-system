<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{

public function index(Request $request)
{

$search = $request->search;

$products = Product::when($search,function($query) use ($search){

$query->where('name','like','%'.$search.'%');

})
->latest()
->paginate(10);

return view('products.index',compact('products','search'));

}


public function create()
{

$companies = Company::all();

return view('products.create',compact('companies'));

}


public function store(Request $request)
{

$request->validate([
'name'=>'required',
'price'=>'required',
'stock'=>'required'
]);

Product::create($request->all());

return redirect()->route('products.index')
->with('success','Produk berhasil ditambahkan');

}


public function edit($id)
{

$product = Product::findOrFail($id);

$companies = Company::all();

return view('products.edit',compact('product','companies'));

}


public function update(Request $request,$id)
{

$product = Product::findOrFail($id);

$product->update($request->all());

return redirect()->route('products.index')
->with('success','Produk berhasil diupdate');

}


public function destroy($id)
{

$product = Product::findOrFail($id);

$product->delete();

return redirect()->route('products.index')
->with('success','Produk berhasil dihapus');

}

}