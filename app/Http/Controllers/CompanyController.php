<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

public function index()
{
    $companies = Company::latest()->get();
    return view('companies.index', compact('companies'));
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


public function update(Request $request,$id)
{

    $company = Company::findOrFail($id);

    $company->update($request->all());

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