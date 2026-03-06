<x-app-layout>

<div class="py-8">
<div class="max-w-xl mx-auto">

<div class="bg-white shadow p-6 rounded">

<h2 class="text-xl font-bold mb-6">

Tambah Produk

</h2>

<form action="{{ route('products.store') }}" method="POST">

@csrf

<div class="mb-4">

<label>Company</label>

<select name="company_id"
class="w-full border p-2 rounded">

@foreach($companies as $company)

<option value="{{ $company->id }}">
{{ $company->name }}
</option>

@endforeach

</select>

</div>

<div class="mb-4">

<label>Nama Produk</label>

<input type="text"
name="name"
class="w-full border p-2 rounded">

</div>

<div class="mb-4">

<label>Satuan</label>

<input type="text"
name="unit"
class="w-full border p-2 rounded">

</div>

<div class="mb-4">

<label>Harga</label>

<input type="number"
name="price"
class="w-full border p-2 rounded">

</div>

<div class="mb-4">

<label>Stok</label>

<input type="number"
name="stock"
class="w-full border p-2 rounded">

</div>

<button class="bg-blue-600 text-white px-4 py-2 rounded">

Simpan

</button>

</form>

</div>

</div>
</div>

</x-app-layout>