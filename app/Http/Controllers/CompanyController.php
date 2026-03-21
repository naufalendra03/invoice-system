<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

public function index(Request $request)
{
    $search = $request->search;
    $perPage = $request->per_page ?? 10;

    $companies = \App\Models\Company::when($search, function($query) use ($search){
        $query->where('name','like',"%$search%")
              ->orWhere('code','like',"%$search%");
    })
    ->latest()
    ->paginate($perPage)
    ->withQueryString();

    return view('companies.index', compact('companies','search','perPage'));
}

public function create()
{
    return view('companies.create');
}


public function store(Request $request)
{

    $request->validate([
        'name'=>'required',
        'code'=>'required'
    ]);

    Company::create($request->all());

    return redirect()->route('companies.index')
        ->with('success','Company berhasil ditambahkan');
}


public function edit($id)
{
    $company = Company::findOrFail($id);
    return view('companies.edit', compact('company'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50',
        'address' => 'nullable|string',
        'phone' => 'nullable|string',
        'rekening' => 'nullable|string', // ✅ boleh kosong
    ]);

    $company = Company::findOrFail($id);

    $company->update([
        'name' => $request->name,
        'code' => $request->code,
        'address' => $request->address,
        'phone' => $request->phone,
        'rekening' => $request->rekening,
    ]);

    return redirect()->route('companies.index')
        ->with('success','Company berhasil diupdate');
}


public function destroy($id)
{
    $company = Company::findOrFail($id);
    $company->delete();

    return redirect()->route('companies.index')
        ->with('success','Company berhasil dihapus');
}

}